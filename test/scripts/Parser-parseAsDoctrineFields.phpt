--TEST--
Parser::parseAsDoctrineFields -- parse a schema into doctrine acceptable fields string

--FILE--
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Schema\Parser;

Parser::$schemaDir = __DIR__ . '/../schemas';

$o = new Parser;
print_r($o->parseAsDoctrineFields('test'));

// a real example
print_r($o->parseAsDoctrineFields('News'));

--EXPECT--
Array
(
    [aa] => Array
        (
            [fieldName] => aa
            [type] => boolean
            [length] => 
        )

    [ab] => Array
        (
            [fieldName] => ab
            [type] => boolean
            [length] => 
        )

    [ac] => Array
        (
            [fieldName] => ac
            [type] => boolean
            [length] => 
        )

    [a] => Array
        (
            [fieldName] => a
            [type] => boolean
            [length] => 
        )

    [ad] => Array
        (
            [fieldName] => ad
            [type] => boolean
            [length] => 
        )

    [b] => Array
        (
            [fieldName] => b
            [type] => integer
            [length] => 
        )

    [x] => Array
        (
            [fieldName] => x
            [type] => float
            [length] => 
        )

    [xa] => Array
        (
            [fieldName] => xa
            [type] => float
            [length] => 
        )

    [c] => Array
        (
            [fieldName] => c
            [type] => date
            [length] => 
        )

    [ca] => Array
        (
            [fieldName] => ca
            [type] => date
            [length] => 
        )

    [cb] => Array
        (
            [fieldName] => cb
            [type] => date
            [length] => 
        )

    [d] => Array
        (
            [fieldName] => d
            [type] => time
            [length] => 
        )

    [e] => Array
        (
            [fieldName] => e
            [type] => datetime
            [length] => 
        )

    [f] => Array
        (
            [fieldName] => f
            [type] => string
            [length] => 
        )

    [g] => Array
        (
            [fieldName] => g
            [type] => text
            [length] => 
        )

)
Array
(
    [title] => Array
        (
            [fieldName] => title
            [type] => string
            [length] => 
        )

    [author] => Array
        (
            [fieldName] => author
            [type] => string
            [length] => 
        )

    [pubdate] => Array
        (
            [fieldName] => pubdate
            [type] => datetime
            [length] => 
        )

    [content] => Array
        (
            [fieldName] => content
            [type] => text
            [length] => 
        )

)
