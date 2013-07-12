<?php
/**
 * @file SchemaBundle.php
 * SchemaBundle for symfony
 *
 * @author Yarco <yarco.wang@gmail.com>
 * @since 2013/07/12
 * @copyright BSD
 */
/* vim: set tabstop=2 shiftwidth=2 softtabstop=2 noexpandtab ai si: */

namespace Schema\Bundle;

use Schema\Bundle\Command\GenerateSchemaEntityCommand;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Console\Application;

/**
 * @class SchemaBundle
 */
class SchemaBundle extends Bundle
{
	public function registerCommands(Application $application)
	{
		$application->add(new GenerateSchemaEntityCommand());
	}
}

