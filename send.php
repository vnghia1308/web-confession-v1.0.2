<?php
/* >_ Developed by Vy Nghia */
require 'server/config.php';
error_reporting(0);
if(isset($_POST['content']))
{
		//default
		$content = array('status' => true, 'content' => 'Bài viết của bạn sẽ chờ được phê duyệt trước khi xuất hiện trên website', 'error' => false);
		
		if(isset($_FILES))
		{
			$web = new Website($con);
			$web->sendImage($_FILES);
			/** sendImage()
			@param FILE $_FILES;
			
			@return [1] array $content
			@return [2] string $Filename
			**/
		}
		
		if($content['status'] == true && $content['error'] == false){
			mysqli_query($con, "INSERT INTO `post`(`id`,  `content`, `approval`, `posted_page`, `image`, `time`) VALUES (NULL, '".base64_encode($_POST['content'])."', '0' , '0', '$Filename','".date("Y-m-d H:i:s")."')");
			$content = array('status' => true, 'content' => 'Bài viết của bạn sẽ chờ được phê duyệt trước khi xuất hiện trên website', 'error' => false);
		}
		else
			$content = array('status' => false, 'content' => 'Lỗi không xác định? Không thể đăng bài viết của bạn lên website!', 'error' => true);
} else
	$content = array('status' => false, 'content' => 'Dữ liệu nhập là trống', 'error' => true);

echo json_encode($content);
