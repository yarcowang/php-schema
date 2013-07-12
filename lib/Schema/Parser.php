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

// utility functions
function is_date($string)
{
	return preg_match('/^\d{1,4}-\d{1,2}(?:-\d{1,2})?$/', $string);
}

function is_time($string)
{
	return preg_match('/^\d{1,2}:\d{1,2}(?::\d{1,2})?$/', $string);
}

function is_datetime($string)
{
	return preg_match('/^\d{1,4}-\d{1,2}(?:-\d{1,2})?\s\d{1,2}:\d{1,2}(?:\:\d{1,2})?$/', $string);
}

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
			if (is_bool($fieldExample)) {
				$ret[] = sprintf("%s boolean", $fieldName);
			} else if (is_int($fieldExample)) {
				$ret[] = sprintf("%s integer", $fieldName);
			} else if (is_float($fieldExample)) {
				$ret[] = sprintf("%s float", $fieldName);
			} else if (is_date($fieldExample)) {
				$ret[] = sprintf("%s date", $fieldName);
			} else if (is_time($fieldExample)) {
				$ret[] = sprintf("%s time", $fieldName);
			} else if (is_datetime($fieldExample)) {
				$ret[] = sprintf("%s datetime", $fieldName);
			} else if (is_string($fieldExample)) {
				if (strlen($fieldExample) < 80) {
					$ret[] = sprintf("%s string", $fieldName);
				} else {
					$ret[] = sprintf("%s text", $fieldName);
				}
			}
		}

		return implode(' ', $ret);
	}/*}}}*/
}


