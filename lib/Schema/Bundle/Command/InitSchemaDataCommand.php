<?php
/**
 * @file InitSchemaDataCommand.php
 *
 * @author Yarco <yarco.wang@gmail.com>
 * @since 2013/07/19
 * @copyright BSD
 */
/* vim: set tabstop=2 shiftwidth=2 softtabstop=2 noexpandtab ai si: */

namespace Schema\Bundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @class InitSchemaDataCommand
 * insert initialize data into database
 */
class InitSchemaDataCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this->setName('init:schema:data')
			->setAliases(array('schema:init:data'))
			->setDescription('Insert initialize data into database')
			->addOption('entity', null, InputOption::VALUE_REQUIRED, 'The entity class name to initialize (shortcut notation)')
			->addOption('schema', null, InputOption::VALUE_REQUIRED, 'Schema name (filename without .ini)')
			->addOption('lang', null, InputOption::VALUE_REQUIRED, 'Language information', 'default')
			->setHelp(<<<EOF
The <info>schema:init:data</info> task add initialize data into database
by the entity name and schema name:

<info>php app/console schema:init:data --entity=AcmeBlogBundle:Blog/Post --schema=Post</info>

The schema should be under <comment>schemas/</comment> dir.

For example, content of <info>schemas/System.ini</info> might be:

[init]
default = System.csv
en = System.csv

That means if you execute the command, it will default import the System.csv under the directory
with System.ini. And the --lang option, figure out which one. For example, here you may want
<info>--lang=en</info>
EOF
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$schemaDir = $this->getContainer()->get('kernel')->getRootDir() . '/../schemas';
		$schema = $input->getOption('schema');
		$lang = $input->getOption('lang');

		$file = sprintf("%s/%s.ini", $schemaDir, $schema);
		$data = parse_ini_file($file, true);
		
		$file = $data['init'][$lang];
		$fields = array_flip(array_keys($data['fields']));
		$file = sprintf("%s/%s", $schemaDir, $file); // csv file

		$entity = $input->getOption('entity');
		list($bundle, $entity) = explode(':', $entity);

		$class = $this->getContainer()->get('doctrine')->getAliasNamespace($bundle) . '\\' . $entity;
		$em = $this->getContainer()->get('doctrine')->getManager();

		$fp = fopen($file, 'r');
		$n = 0;
		while($row = fgetcsv($fp)) {
			$o = new $class;
			foreach($fields as $k => $pos) {
				call_user_func(array($o, 'set' . $k), $row[$pos]);
			}
			$em->persist($o);
			$n++;
		}
		$em->flush();

		$output->writeln(sprintf("%d records are saved.\n", $n));
	}
}
