<?php
/* >_ Developed by Vy Nghia */
require 'server/config.php';
error_reporting(0);
if(isset($_POST['content']))
{
		$content = array('status' => true, 'content' => 'Bài viết của bạn sẽ chờ được phê duyệt trước khi xuất hiện trên website', 'error' => false);
		
		if(isset($_FILES))
		{
			$web = new Website;
			$web->sendImage($_FILES);
		}
		
		if($content['status'] == true && $content['error'] == false)
			mysqli_query($con, "INSERT INTO `post`(`id`,  `content`, `approval`, `image`, `time`) VALUES ('', '".base64_encode($_POST['content'])."', '0', '$Filename','".date("Y-m-d H:i:s")."')");
} else
	$content = array('status' => false, 'content' => 'Dữ liệu nhập là trống', 'error' => true);

echo json_encode($content);
