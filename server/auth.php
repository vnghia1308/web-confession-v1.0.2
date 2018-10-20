<?php
/* >_ Developed by Vy Nghia */
require 'config.php';
session_start();
error_reporting(0);

switch($_GET['login']){
	case 'admin':
		if($_POST['username'] !== '' && $_POST['password'] !== '')
		{
			$admin = new Admin;
			$admin->checkadmin($con, $_POST['username'], $_POST['password']);
			
			if($status == true)
			{
				echo ('success');
				$_SESSION['admin'] = $_POST['username'];
			}
			else 
				echo ('failed');
		} else 
			echo ('null');
		break;
	case 'install':
	if($_POST['password'] !== '')
	{
		define('PASSWORD', file_get_contents('lib/data/auth/password/install.pass'));
				
		if(md5($_POST['password']) === PASSWORD)
		{
			echo ('success');
			$_SESSION['install'] = true;
		}
		else
			echo ('failed');
	}
}
	