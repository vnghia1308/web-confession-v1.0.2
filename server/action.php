<?php
/* >_ Developed by Vy Nghia */
require '../login.php';
error_reporting(0);

switch($_GET['do']){
	/* LOGIN HANDLING */
	case 'login':
		if($_GET['to'] == 'facebook')
			include('login.php');
		break;
		
	/* LOGOUT HANDLING */
	case 'logout':
		//logout admin
		if($_GET['type'] == 'admin')
		{
			if(isset($_SESSION['admin']))
				unset($_SESSION['admin']);
		}
		
		//logout install
		if($_GET['type'] == 'install'){
			if(isset($_SESSION['install']))
				unset($_SESSION['install']);
		}
		
		if(isset($_SERVER[ 'HTTP_REFERER' ]))
			header("Location: " . $_SERVER[ 'HTTP_REFERER' ]);
		else 
			header("Location: /");
		break;
		
	/* FACEBOOK MODE CONFIG*/
	case 'config':
		if($_GET['type'] == 'facebook')
		{
			if($_POST['fb-mode'] == null) $_POST['fb-mode'] = 0;
				mysqli_query($con, "UPDATE `facebook` SET `page_id`='{$_POST['page-id']}', `fb_mode` = {$_POST['fb-mode']}, `content` = '".base64_encode($_POST['fb-content'])."' WHERE 1");
		}
		break;

	/* APPROVAL/RE-APPROVAL/DELETE POST */
	case 'approval':
		if(isset($_POST['id']) && isset($_POST['type']) && isset($_SESSION['admin']))
		{
			$fbquery = mysqli_query($con, 'SELECT * FROM `facebook` WHERE 1');
			$fb = mysqli_fetch_array($fbquery);

			/* HANDLING REQUEST */
			if($_POST['type'] == 'allow')
				mysqli_query($con, "UPDATE `post` SET `approval`=1,`time_approval`='".date("Y-m-d H:i:s")."' WHERE `id` = {$_POST['id']}");
			elseif($_POST['type'] == 're-approval')
				mysqli_query($con, "UPDATE `post` SET `approval`=0 WHERE `id` = {$_POST['id']}");
			else
				mysqli_query($con, "DELETE FROM `post` WHERE `id` = {$_POST['id']}");

			/* CHECK FACEBOOK POST PAGE MODE */
			if($_POST['type'] == 'allow' && $fb['fb_mode'] == 1)
				$arApproval = array('facebook' => true);
			else
				$arApproval = array('facebook' => false);
			echo json_encode($arApproval);
		}
		break;

	/* POST AS PAGE */
	case 'post':
		if(isset($_SESSION['admin'])){
			/* GET FACEBOOK INFO */
			$fbquery = mysqli_query($con, 'SELECT * FROM `facebook` WHERE 1');
			$fbs = mysqli_fetch_array($fbquery);

			/* GET POST INFO */
			$pstquery = mysqli_query($con, "SELECT * FROM `post` WHERE `id` = {$_POST['id']}");
			$post = mysqli_fetch_array($pstquery);

			/* DEFINE CONTENT */
			$fbs['content'] = str_replace("{{content}}", base64_decode($post['content']), base64_decode($fbs['content']));

			if(isset($accessToken) && isset($fbs['content']))
			{
				$pages = $fb->get('/me/accounts');
				$pages = $pages->getGraphEdge()->asArray();
				foreach ($pages as $key)
				{
					if ($key['id'] == $fbs['page_id'])
					{
						if($post['image'] !== '')
							$post = $fb->post('/' . $key['id'] . '/photos', array('message' => $fbs['content'], 'source' => $fb->fileToUpload('../media/image/'.$post['image'])), $key['access_token']);
						else
							$post = $fb->post('/' . $key['id'] . '/feed', array('message' => $fbs['content']), $key['access_token']);
					}
				}
			}
			
			mysqli_query($con, "UPDATE `post` SET `posted_page` = 1 WHERE `id` = {$_POST['id']}"); //set status is posted for id
		}
		break;

	/* ADMIN PASSWORD CHANGE */
	case 'change':
		if(isset($_SESSION['admin']) && $_POST['password'])
		{
			if($_GET['type'] == 'admin')
				mysqli_query($con, "UPDATE `admin` SET `password` = '{$_POST['password']}' WHERE 1");
			unset($_SESSION['admin']);
			echo (true);
		}
		break;
	case 'install':
		if(isset($_SESSION['install']))
		{
			if($_GET['type'] == 'mysql')
				include('lib/data/mysql/install/database.install.php');
		}
		break;
}
