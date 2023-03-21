<?php
// DB credentials.
define('DB_HOST', 'sql304.epizy.com');
define('DB_USER', 'epiz_33221249');
define('DB_PASS', 'aTjJFbZ3Wtk');
define('DB_NAME', 'epiz_33221249_carrental');
// Establish database connection.
// $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
$dbh = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
?>