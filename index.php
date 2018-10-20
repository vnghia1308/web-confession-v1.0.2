<?php
/* >_ Developed by Vy Nghia */
require 'server/config.php';
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home</title>
<base href="<?php echo WEBURL ?>/" />
<link href="assets/css/bootstrap3/bootstrap.css" rel="stylesheet">
<link href="assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">
<link href="assets/css/animate.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
<link href="assets/css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.css" rel="stylesheet" type="text/css">
<style>
textarea {
     width: 100%;
	 height: 100px;
	 resize: none;
     -webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
     -moz-box-sizing: border-box;    /* Firefox, other Gecko */
     box-sizing: border-box;         /* Opera/IE 8+ */
}
</style>
</head>
<body class="boxed-layout fixed-sidebar">
<div id="wrapper">
<nav class="navbar-default navbar-static-side" role="navigation">
<div class="sidebar-collapse">
<ul class="nav metismenu" id="side-menu">
<li class="nav-header">
<div class="dropdown profile-element">
<a data-toggle="dropdown" class="dropdown-toggle" href="#">
<span class="clear"> <span class="block m-t-xs"> Chào bạn</strong>
</span> </a>
</div>
</li>
<li class="active">
<a href="/"><i class="fa fa-home"></i> <span class="nav-label">Trang chủ</span></a>
</li>
<?php if(isset($_SESSION['admin'])): ?>
<li>
<a href="admin"><i class="fa fa-user-circle" aria-hidden="true"></i> <span class="nav-label">Trang quản trị viên</span></a>
</li>
<?php endif; ?>
</ul>
</div>
</nav> <div id="page-wrapper" class="gray-bg">
<div class="row border-bottom">
<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
<div class="navbar-header">
<a class="navbar-minimalize minimalize-styl-2 btn btn-primary "><i class="fa fa-bars"></i> </a>
</div>
</nav>
</div>
<div class="row wrapper border-bottom white-bg page-heading">
<div class="col-lg-10">
<h2>Bảng tin</h2>
<ol class="breadcrumb">
<li>
<a href="/">Trang chủ</a>
</li>
<li class="active">
<strong>Bảng tin</strong>
</li>
</ol>
</div>
<div class="col-lg-2">
</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
<div class="row">
<div class="col-lg-12">
<div class="social-feed-separated">
<div class="social-feed-box">
<div class="social-avatar">
<a>
<strong>Bạn muốn nói điều gì?</strong>
</a>
</div>
<!-- POST AREA -->
<div class="social-body">
<form id="post" action="" method="POST">
<textarea name="content"></textarea>
<p></p>
<div class=""><div class="fileinput fileinput-new" data-provides="fileinput">
    <span class="btn btn-default btn-file"><span class="fileinput-new">Chọn hình ảnh</span>
    <span class="fileinput-exists">Thay đổi</span><input type="file" name="image" id="image"/></span>
    <span class="fileinput-filename"></span>
    <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
</div>
<span id="pre"></span>
<br />
<small>Danh tính của bạn sẽ được che dấu, sẽ không ai biết bạn là ai (ngay cả quản trị viên website).</small>
<button type="submit" style="float: right; overflow: hidden" id="postSubmit" class="btn btn-success btn-rounded btn-sm">Đăng bài viết</button>
<div style="clear: both;"></div>
</div>
</form>
</div>
</div>
</div>

<hr>
<div id="post">
<?php
if(empty($_GET['p']))
		$_GET['p'] = 1;
if($_GET['p'] >= 2)
	$pages = ($_GET['p'] - 1) * 10;
else
	$pages = 0;
$postQuery = mysqli_query($con, "SELECT * FROM `post` WHERE `approval` = 1 ORDER BY `time_approval` DESC LIMIT 10 OFFSET {$pages}");
while($post = mysqli_fetch_array($postQuery)):
$pst = new Website; ?>
<div class="social-feed-separated" id="post-id-<?php echo $post['id'] ?>">
<div class="social-feed-box">
<div class="social-avatar">
<small class="text-muted"><?php echo $pst->timeAgo(strtotime($post['time'])) ?> (phê duyệt vào <?php echo $pst->timeAgo(strtotime($post['time_approval'])) ?>)</small>
</div>
<div class="social-body">
<p><?php echo base64_decode($post['content']) ?></p>
<?php if($post['image'] !== ''): ?>
<img src="<?php echo WEBURL ?>/media/image/<?php echo $post['image'] ?>" width="100%" height="100%"/>
<?php endif; ?>
</div>
</div>
</div>
<?php 
endwhile;?>
</div>
</div>

<div class="row" style="text-align: center">
<?php
	$query = mysqli_query($con, 'SELECT * FROM `post` WHERE `approval` = 1');
	$n = mysqli_num_rows($query) / 10;
	if(mysqli_num_rows(mysqli_query($con, $query)) % 10 > 0)
		$n+=1;
	$n = (int) $n; 
	if(mysqli_num_rows(mysqli_query($con, $query)) > 0): ?>
<ul class="pagination pagination-sm">
    <li class="<?php echo ($_GET['p']-1 != 0) ? 'first' : 'first disabled';?>"><a href="/home?p=1">First</a></li>
    <li class="<?php echo ($_GET['p']-1 != 0) ? 'prev' : 'prev disabled';?>"><a href="/home?p=<?php echo ($_GET['p']-1 != 0) ? $_GET['p']-1 : 1;?>">Previous</a></li>
	<?php for($i = 1; $i <= $n; $i++): ?>
    <li class="<?php echo ($_GET['p'] == $i) ? 'page active' : 'page'; ?>"><a href="/home?p=<?php echo $i ?>"><?php echo $i ?></a></li>
	<?php endfor; ?>
    <li class="<?php echo ($_GET['p']+1 > $n) ? 'next disabled' : 'next'; ?>"><a href="/home?p=<?php echo $_GET['p']+1 ?>">Next</a></li>
    <li class="<?php echo ($_GET['p']+1 > $n) ? 'last disabled' : 'last'; ?>"><a href="/home?p=<?php echo $n ?>">Last</a></li>
</ul>
<?php endif; ?>
</div>
</div>
</div>
<div class="footer">
<div>
&copy; 2017 Vy Nghia.
</div>
</div>
</div>

</div>
<script src="assets/js/jquery-2.1.1.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/js/inspinia.js"></script>
<script src="assets/js/plugins/pace/pace.min.js"></script>
<script src="assets/js/jquery.twbsPagination.min.js"></script>
<script src="assets/js/plugins/jasny/jasny-bootstrap.min.js"></script>
<script src="assets/js/plugins/toastr/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.min.js"></script>
<script>
/* IMAGE PREVIEW & UPLOAD*/
function readURL(input) {

	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function(e) {
			//$('#preview').attr('src', e.target.result);
			$('#pre').html('<img id="preview" src="' + e.target.result + '" width="100%" height="100%" />')
		}

		reader.readAsDataURL(input.files[0]);
	}
  
}
  
$("input#image").change(function() {
	var filename = $('input[type=file]').val().split('\\').pop();
	var re = /(?:\.([^.]+))?$/;
	var ext = re.exec(filename)[1];
	if(ext == 'jpeg' || ext == 'jpg' || ext == 'png' || ext == null){
		readURL(this);
		$('#preview').show()
	} else
		toastr.error('Chỉ chấp nhận định dạng ảnh!')
});

$("#post").on('click','.close',(function(e) {
	$("#preview").remove()
}));

/* POST CONFESSION */
$("#post").on('submit',(function(e) {
	e.preventDefault();
	if($('textarea').val() == ''){
		toastr.error('Vui lòng nhập nội dung bài viết')
	} else {
		$.ajax({
			method: 'POST',
			url: 'send.php?action=post',
			dataType: "json",
			data:  new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function () {
				$('#postSubmit').text('Đang xử lý...').prop('disabled', true)
			},
			success: function(data) {
				if(data.status == true){
					$('textarea').val(null)
					$('.close').click()
					toastr.success(data.content)
				} else
					toastr.error(data.content)
			},
			error: function(){
				toastr.error('Đã xảy ra lỗi cục bộ, vui lòng thử lại!')
			},
			complete: function(){
				$('#postSubmit').text('Đăng bài viết').prop('disabled', false)
			}
	   });
	}
}));
</script>
</body>
</html>
