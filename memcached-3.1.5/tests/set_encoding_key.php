<?php
include dirname (__FILE__) . '/config.inc';
$m = memc_get_instance ();
$key = uniqid ('encoding_test_');

var_dump ($m->setEncodingKey("Hello"));
var_dump ($m->set ($key, 'set using encoding'));
var_dump ($m->get ($key));

echo "OK" . PHP_EOL;

# Change the encryption key. The old value will be inaccessible.
var_dump ($m->setEncodingKey("World"));
var_dump ($m->get ($key));

echo "OK" . PHP_EOL;

# Restore the original key to retrieve old values again.
var_dump ($m->setEncodingKey("Hello"));
var_dump ($m->get ($key));

echo "OK" . PHP_EOL;

# With a new encoding key we can still write new values,
# this works as expected with libmemcached 1.0.18 and higher.
var_dump ($m->setEncodingKey("World"));
var_dump ($m->get ($key));
var_dump ($m->set ($key, 'set using encoding'));
var_dump ($m->get ($key));

echo "OK" . PHP_EOL;

?>
