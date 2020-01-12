<?php
/* >_ Developed by Vy Nghia */
error_reporting(0);
require 'lib/class/confession.class.php';

/* WEBSITE DOMAIN */
define('WEBURL', '{1}');

/* MYSQL DATABASE */
$db = new Database;
$db->dbhost('{2}');
$db->dbuser('{3}');
$db->dbpass('{4}');
$db->dbname('{5}');

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
		die("connect to database failed");
	else
	{
		$testdb = mysqli_fetch_array(mysqli_query($con, "select * from post"));
		
		if(!$testdb)
			die("database's null");
	}
}

$config = new config(WEBURL);

/* CALL APP SDK */
include ('app.fb.php');
