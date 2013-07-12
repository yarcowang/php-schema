<?php
/**
 * @file Parser.php
 * schema parser
 *
 * @author Yarco <yarco.wang@gmail.com>
 * @since 2013/07/12
 * @copyright BSD
 */
/* vim: set tabstop=2 shiftwidth=2 softtabstop=2 noexpandtab ai si: */

namespace Schema;

// schema dir contains schemas
if (!defined('SCHEMA_DIR')) define('SCHEMA_DIR', __DIR__ . '/schemas');

// regular express
define('RE_DATE', '/^\d{1,4}-\d{1,2}(?:-\d{1,2})?$/');
define('RE_TIME', '/^\d{1,2}:\d{1,2}(?::\d{1,2})?$/');
define('RE_DATETIME', '/^\d{1,4}-\d{1,2}(?:-\d{1,2})?\s\d{1,2}:\d{1,2}(?:\:\d{1,2})?$/');

class Parser
{
	public static $schemaDir = SCHEMA_DIR;

	/** parse a schema into doctrine acceptable fields string {{{
	 *
	 * @param string schema name
	 * @return string which can be used in doctrine
	 * @api public
	 */
	public function parseAsDoctrineFields($schema)
	{
		$file = sprintf("%s/%s.ini", self::$schemaDir, $schema);
		$schemaInfo = parse_ini_file($file, true);

		$ret = array();
/* TODO:
# from doctrine doc
Decimal (restricted floats, NOTE Only works with a setlocale() configuration that uses decimal points!)
Array (serialized into a text field for all vendors by default)
Object (serialized into a text field for all vendors by default)

# from doctine Type.php
const SIMPLE_ARRAY = 'simple_array';
const JSON_ARRAY = 'json_array';
const BIGINT = 'bigint';
const DATETIMETZ = 'datetimetz';
const DECIMAL = 'decimal';
const OBJECT = 'object';
const SMALLINT = 'smallint';
const BLOB = 'blob';
const GUID = 'guid';
*/
		// try to guess each field
		foreach($schemaInfo['fields'] as $fieldName => $fieldExample) {
			if ($fieldExample === '' || $fieldExample === '0' || filter_var($fieldExample, FILTER_VALIDATE_BOOLEAN)) {
				$ret[$fieldName] = array('fieldName' => $fieldName, 'type' => 'boolean', 'length' => null);
			} else if (filter_var($fieldExample, FILTER_VALIDATE_INT)) {
				$ret[$fieldName] = array('fieldName' => $fieldName, 'type' => 'integer', 'length' => null);
			} else if (filter_var($fieldExample, FILTER_VALIDATE_FLOAT)) {
				$ret[$fieldName] = array('fieldName' => $fieldName, 'type' => 'float', 'length' => null);
			} else if (filter_var($fieldExample, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => RE_DATE)))) {
				$ret[$fieldName] = array('fieldName' => $fieldName, 'type' => 'date', 'length' => null);
			} else if (filter_var($fieldExample, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => RE_TIME)))) {
				$ret[$fieldName] = array('fieldName' => $fieldName, 'type' => 'time', 'length' => null);
			} else if (filter_var($fieldExample, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => RE_DATETIME)))) {
				$ret[$fieldName] = array('fieldName' => $fieldName, 'type' => 'datetime', 'length' => null);
			} else if (is_string($fieldExample)) {
				if (strlen($fieldExample) < 80) {
					$ret[$fieldName] = array('fieldName' => $fieldName, 'type' => 'string', 'length' => null);
				} else {
					$ret[$fieldName] = array('fieldName' => $fieldName, 'type' => 'text', 'length' => null);
				}
			}
		}

		return $ret;
	}/*}}}*/
}


