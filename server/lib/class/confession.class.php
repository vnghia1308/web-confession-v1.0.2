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

class config
{
	public $weburl;
	
	public function __construct($url){ 
		$this->weburl = $url;
    }
	
	public function GetWebUrl(){
		return $this->weburl;
	}
}

class Website extends config
{
	protected $db;
	
	public function __construct($db){ 
		$this->db = $db;
    } 
	
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
	
	public function GetPublicPost($p){
		$q = mysqli_query($this->db, "SELECT * FROM `post` WHERE `approval` = 1 ORDER BY `time_approval` DESC LIMIT 10 OFFSET $p");
		$a = array();
		$i = "";
		
		$t = '<div class="social-feed-separated" id="post-id-{ID}">
<div class="social-feed-box">
<div class="social-avatar">
<small class="text-muted">{TIME_AGO} (phê duyệt vào {TIME_APPROVAL})</small>
</div>
<div class="social-body">
<p>{CONTENT}</p>
{IMAGE}
</div>
</div>
</div>';
		
		while($f = mysqli_fetch_array($q)){
			if($f['image'] !== '')
				$i = '<img src="media/image/' . $f['image'] . '" width="100%" height="100%"/>';
			else
				$i = null;
			
			$c = ["{ID}", "{TIME_AGO}", "{TIME_APPROVAL}", "{CONTENT}", "{IMAGE}"];
			$r = [$f["id"], $this->timeAgo(strtotime($f['time'])), $this->timeAgo(strtotime($f['time_approval'])), htmlspecialchars(base64_decode($f['content'])), $i];
			
			$nC = str_replace($c, $r, $t);
			
			array_push($a, $nC);
		}
		
		return $a;
	}
}

class Admin extends Website
{
	protected $db;
	
	public function __construct($db){ 
		$this->db = $db;
    } 
	
	public function checkadmin($username, $password){
		$sql = mysqli_query($this->db, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
		
		if(mysqli_num_rows($sql) > 0)
			return true;
		else
			return false;
	}
	
	public function checkAccessToken($accessToken){		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch,CURLOPT_URL, 'https://graph.facebook.com/me/?access_token='.$accessToken);
		$j = curl_exec($ch);
		curl_close($ch);
		
		$u = json_decode($j);
		if(isset($u->id))
			return true;
		else
			return false;
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
	
	public function GetPost($p, $s){
		$q = mysqli_query($this->db, "SELECT * FROM `post` WHERE `approval` = $s ORDER BY `time` DESC LIMIT 10 OFFSET $p");
		$a = array();
		
		if($s == 0){
			$t = '<div class="social-feed-separated" id="post-id-{ID}">
<div class="social-feed-box">
<div class="social-avatar">
<small class="text-muted">{TIME_AGO}</small>
</div>
<div class="social-body">
<p>{CONTENT}</p>
{IMAGE}
<p><hr></p>
<div class="">
<button class="btn btn-primary btn-rounded btn-sm" id="approval" data-id="{ID}" data-type="allow"><i class="fa fa-check"></i> Phê duyệt bài viết này</button>
<button class="btn btn-danger btn-rounded btn-sm" id="approval" data-id="{ID}" data-type="deny"><i class="fa fa-times"></i> Xóa bài bài viết này</button>
</div>
</div>
</div>
</div>';
		} else {
			$t = '<div class="social-feed-separated" id="post-id-{ID}">
<div class="social-feed-box">
<div class="social-avatar">
<small class="text-muted">{TIME_AGO}</small>
</div>
<div class="social-body">
<p>{CONTENT}</p>
{IMAGE}
<p><hr></p>
<div class="">
<button class="btn btn-warning btn-rounded btn-sm" id="approval" data-id="{ID}" data-type="re-approval"><i class="fa fa-refresh"></i> Đưa về trạng thái phê duyệt</button>
<button class="btn btn-danger btn-rounded btn-sm" id="approval" data-id="{ID}" data-type="deny"><i class="fa fa-times"></i> Xóa bài bài viết này</button>
</div>
</div>
</div>
</div>';
		}
		
		while($f = mysqli_fetch_array($q)){			
			if($f['image'] !== '')
				$i = '<img src="media/image/' . $f['image'] . '" width="100%" height="100%"/>';
			else
				$i = null;
			
			$c = ["{ID}", "{TIME_AGO}", "{CONTENT}", "{IMAGE}"];
			$r = [$f["id"], parent::timeAgo(strtotime($f['time'])), htmlspecialchars(base64_decode($f['content'])), $i];
			
			$nC = str_replace($c, $r, $t);
			
			array_push($a, $nC);
		}
		
		return $a;
	}
}
