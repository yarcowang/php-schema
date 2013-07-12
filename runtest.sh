#!/usr/bin/env bash

PHP=`which php`
RUNTEST=`pwd`/test/run-tests.php
TEST_PHP_EXECUTABLE=$PHP $PHP $RUNTEST test/scripts
