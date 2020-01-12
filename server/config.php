<?php
/* >_ Developed by Vy Nghia */
require 'lib/class/confession.class.php';

/* WEBSITE URL */
define('WEBURL', 'https://nghia.org/cfs');

/* MYSQL DATABASE */
$db = new Database;
$db->dbhost('db_host');
$db->dbuser('db_user');
$db->dbpass('db_pass');
$db->dbname('db_name');

$con = $db->connect();

if( /* Exception Access */
	!strpos($_SERVER["SCRIPT_NAME"], "auth") &&
	!strpos($_SERVER["SCRIPT_NAME"], "login") &&
	!strpos($_SERVER["SCRIPT_NAME"], "config") &&
	!strpos($_SERVER["SCRIPT_NAME"], "action") &&
	!strpos($_SERVER["SCRIPT_NAME"], "install") &&
	!strpos($_SERVER["SCRIPT_NAME"], "database"))
{
	if(!$con)
		die("Connection failed");
	else
	{
		$testdb = mysqli_fetch_array(mysqli_query($con, "select * from admin"));
		
		if(!$testdb)
			die("database's null");
	}
}

$config = new config(WEBURL);

/* CALL APP SDK */
include ('app.fb.php');