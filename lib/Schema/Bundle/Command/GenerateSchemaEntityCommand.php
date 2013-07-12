<?php
/**
 * @file GenerateSchemaEntityCommand.php
 *
 * @author Yarco <yarco.wang@gmail.com>
 * @since 2013/07/12
 * @copyright BSD
 */
/* vim: set tabstop=2 shiftwidth=2 softtabstop=2 noexpandtab ai si: */

namespace Schema\Bundle\Command;

use Schema\Parser;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Generator\DoctrineEntityGenerator;

class GenerateSchemaEntityCommand extends GenerateDoctrineCommand
{
	protected function configure()
	{
		$this->setName('schema:generate:entity')
			->setAliases(array('generate:schema:entity'))
			->setDescription('Generates a new Doctrine entity inside a bundle according to a schema')
			->addOption('entity', null, InputOption::VALUE_REQUIRED, 'The entity class name to initialize (shortcut notation)')
			->addOption('schema', null, InputOption::VALUE_REQUIRED, 'Schema name (filename without .ini)')
			->addOption('format', null, InputOption::VALUE_REQUIRED, 'Use the format for configuration files (php, xml, yml, or annotation)', 'annotation')
			->addOption('with-repository', null, InputOption::VALUE_NONE, 'Whether to generate the entity repository or not')
			->setHelp(<<<EOT
The <info>schema:generate:entity</info> task generates a new Doctrine
entity inside a bundle by a schema definition:

<info>php app/console schema:generate:entity --entity=AcmeBlogBundle:Blog/Post</info>

The above command would initialize a new entity in the following entity
namespace <info>Acme\BlogBundle\Entity\Blog\Post</info>.

You can also optionally specify the schema you want to generate in the new
entity:

<info>php app/console schema:generate:entity --entity=AcmeBlogBundle:Blog/Post  --schema=Post</info>

by a Post schema which should be <comment>schemas/Post.ini</comment>

The command can also generate the corresponding entity repository class with the
<comment>--with-repository</comment> option:

<info>php app/console schema:generate:entity --entity=AcmeBlogBundle:Blog/Post --with-repository</info>

By default, the command uses annotations for the mapping information; change it
with <comment>--format</comment>:

<info>php app/console schema:generate:entity --entity=AcmeBlogBundle:Blog/Post --format=yml</info>

To deactivate the interaction mode, simply use the `--no-interaction` option
without forgetting to pass all needed options:

<info>php app/console schema:generate:entity --entity=AcmeBlogBundle:Blog/Post --format=annotation --schema=Post --with-repository --no-interaction</info>
EOT
		);

	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$dialog = $this->getDialogHelper();

		if ($input->isInteractive()) {
			if (!$dialog->askConfirmation($output, $dialog->getQuestion('Do you confirm generation', 'yes', '?'), true)) {
				$output->writeln('<error>Command aborted</error>');
				return 1;
			}
		}

		list($bundle, $entity) = $this->parseShortcutNotation($input->getOption('entity'));
		$dialog->writeSection($output, 'Entity generation');
		$bundle = $this->getContainer()->get('kernel')->getBundle($bundle);

		// schema dir
		Parser::$schemaDir = $this->getContainer()->get('kernel')->getRootDir() . '/../schemas';
		$o = new Parser;
		$fields = $o->parseAsDoctrineFields($input->getOption('schema'));

		$generator = $this->getGenerator();
		$generator->generate($bundle, $entity, $input->getOption('format'), $fields, $input->getOption('with-repository'));
		$output->writeln('Generating the entity code: <info>OK</info>');
		$dialog->writeGeneratorSummary($output, array());
	}

	protected function createGenerator()
	{
		return new DoctrineEntityGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('doctrine'));
	}
}
