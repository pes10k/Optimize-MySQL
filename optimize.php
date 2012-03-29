<?php

/**
 * A quick script to optimize all tables on a database.
 *
 * @author Peter Snyder <snyderp@gmail.com>
 */

require_once 'Console/CommandLine.php';

$parser = new Console_CommandLine();

$parser->description = 'Database optimization utility.';
$parser->version = '0.1';

$parser->addOption('db_name', array(
  'short_name'  => '-n',
  'long_name'   => '--database-name',
  'description' => 'The database to optimize.',
  'action'      => 'StoreString',
));

$parser->addOption('db_pass', array(
  'short_name'  => '-p',
  'long_name'   => '--password',
  'description' => 'The password to the database for the given user.',
  'action'      => 'StoreString',
));

$parser->addOption('db_user', array(
  'short_name'  => '-u',
  'long_name'   => '--user',
  'description' => 'A username that has access to the given database.',
  'action'      => 'StoreString',
));

try {
  $result = $parser->parse();
} catch (Exception $exc) {
  $parser->displayError($exc->getMessage());
}

$DB = new PDO('mysql:dbname=' . $result->options['db_name'] . ';host=127.0.0.1', $result->options['db_user'], $result->options['db_pass']);

$rs = $DB->query('SHOW TABLES;');

foreach ($rs as $row) {

  $rs2 = $DB->query('OPTIMIZE TABLE ' . $row[0]);
  echo 'Optimized table `' . $result->options['db_name'] . '`.`' . $row[0] . '`' . PHP_EOL;
}
