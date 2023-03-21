<?php 
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','carrental');
// Establish database connection.
$dbh = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
?>