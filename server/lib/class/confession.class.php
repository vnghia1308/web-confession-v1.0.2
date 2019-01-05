<?php
/* >_ Developed by Vy Nghia */
class Database
{
	protected $dbhost;
	protected $dbuser;
	protected $dbpass;
	protected $dbname;
	
	public function dbhost($dbhost)
	{
		$this->dbhost = $dbhost;
	}
	
	public function dbuser($dbuser)
	{
		$this->dbuser = $dbuser;
	}
	
	public function dbpass($dbpass)
	{
		$this->dbpass = $dbpass;
	}
	
	public function dbname($dbname)
	{
		$this->dbname = $dbname;
	}
	
	public function connect()
	{
		$con = mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass);		
		mysqli_select_db($con, $this->dbname);
		
		return $con;
	}
	
	public function dbinfo($db)
	{
		echo $this->$db;
	}
}

class Website
{
	public function sendImage($FILES)
	{
		global $content, $Filename;
		
		if (!file_exists('media/image'))
			mkdir('media/image', 0777, true);
		
		if (isset($_FILES))
		{
			if(is_array($_FILES))
			{
				$FileExt = strtolower(end(explode('.',$_FILES['image']['name'])));
				$FileAllow = array("jpeg","jpg","png", "");		
				if(in_array($FileExt, $FileAllow) === true)
				{
					if(is_uploaded_file($_FILES['image']['tmp_name']))
					{
						$Filename = md5(time()).'-'.$_FILES['image']['name'];
						$sourcePath = $_FILES['image']['tmp_name'];
						$targetPath = 'media/image/'.$Filename;
						if(move_uploaded_file($sourcePath,$targetPath))
							$content = array('status' => true, 'content' => 'Bài viết của bạn sẽ chờ được phê duyệt trước khi xuất hiện trên website', 'error' => false);
						else
							$content = array('status' => false, 'content' => 'Không thể upload file lên server' , 'error' => true);
					} 
				}
				else
					$content = array('status' => false, 'content' => 'Chỉ chấp nhận định dạng là ảnh', 'error' => true);
			}
		}
	}
	
	public function timeAgo($time_ago)
	{
	  $cur_time 	= time();
	  $time_elapsed = $cur_time - $time_ago;
	  $seconds 		= $time_elapsed ;
	  $minutes 		= round($time_elapsed / 60 );
	  $hours 		= round($time_elapsed / 3600);
	  $days 		= round($time_elapsed / 86400 );
	  $weeks 		= round($time_elapsed / 604800);
	  $months 		= round($time_elapsed / 2600640 );
	  $years 		= round($time_elapsed / 31207680 );
	  // Seconds
	  if($seconds <= 60){
		return "$seconds giây trước";
	  }
	  //Minutes
	  else if($minutes <=60){
		if($minutes==1){
		  return "1 phút trước";
		}
		else{
		  return "$minutes phút trước";
		}
	  }
	  //Hours
	  else if($hours <=24){
		if($hours==1){
		  return "1 giờ trước";
		}else{
		  return "$hours giờ trước";
		}
	  }
	  //Days
	  else if($days <= 7){
		if($days==1){
		  return "hôm qua";
		}else{
		  return "$days ngày tước";
		}
	  }
	  //Weeks
	  else if($weeks <= 4.3){
		if($weeks==1){
		  return "1 tuần trước";
		}else{
		  return "$weeks tuần trước";
		}
	  }
	  //Months
	  else if($months <=12){
		if($months==1){
		  return "1 tháng trước";
		}else{
		  return "$months tháng trước";
		}
	  }
	  //Years
	  else{
		if($years==1){
		  return "1 năm trước";
		}else{
		  return "$years năm trước";
		}
	  }
	}
}

class Admin
{
	public function checkadmin($db, $username, $password){
		global $status;
		$query = mysqli_query($db, "SELECT * FROM `admin` WHERE 1");
		$admin = mysqli_fetch_array($query);
		
		if($admin['username'] == $username && $admin['password'] == $password)
			$status = true;
		else 
			$status = false;		
	}
	
	public function checkAccessToken($accessToken){
		global $arToken;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch,CURLOPT_URL, 'https://graph.facebook.com/me/?access_token='.$accessToken);
		$getUserJson = curl_exec($ch);
		curl_close($ch);
		
		$user = json_decode($getUserJson);
		if(isset($user->id))
			$check = true;
		else
			$check = false;
		$arToken = array('check' => $check);
	}
	
	public function GetPageList($accessToken)
	{
		$pageList = array();
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch,CURLOPT_URL, 'https://graph.facebook.com/me/accounts?access_token='.$accessToken);
		$rs = curl_exec($ch);
		curl_close($ch);
		
		$p = json_decode($rs, true);
		
		foreach($p["data"] as &$v)
		{
			array_push($pageList, array("id" => $v["id"], "name" => $v["name"]));
		}
		
		return (array)$pageList;
	}
}
