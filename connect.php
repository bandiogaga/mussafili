<?php
ob_start();
//connect.php

//This script connects to MySQL using the PDO object
//This can be included in web pages where a database connection is needed.
session_cache_limiter('private');
$cache_limiter = session_cache_limiter();
session_cache_expire(0.1);
$cache_expire = session_cache_expire();
session_start();
define('MYSQL_USER', 'stbisorg_gaga');
define('MYSQL_PASSWORD', 'p0gaga8619');
define('MYSQL_HOST', 'localhost');
define('MYSQL_DATABASE', 'stbisorg_new');

//set error mode to exceptions and turn off emulated prepared statements

$pdoOptions = array(
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_EMULATE_PREPARES => false
);

//Connect to MYSQL and instantiate the PDO object

$pdo = new PDO(
	"mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DATABASE, 
	MYSQL_USER,
	MYSQL_PASSWORD,
	$pdoOptions
);
?>
