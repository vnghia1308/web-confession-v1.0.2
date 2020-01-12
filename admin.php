<?php
/* >_ Developed by Vy Nghia */
require 'login.php';

if(!empty($_GET["page"])){
	switch($_GET['page']){
		case 'change': $page = 'Thay đổi thông tin đăng nhập'; break;
		case 'approval': $page = 'Bài viết chờ phê duyệt'; break;
		case 'post': $page = 'Bài viết đã phê duyệt'; break;
		case 'facebook': $page = 'Kết nối với Facebook'; break;
	}
} else {
	$_GET['page'] = null;
	$page = 'Trang chủ';
}

$fQuery = mysqli_query($con, 'SELECT * FROM `facebook` WHERE 1');
$f = mysqli_fetch_array($fQuery);

$a = new Admin($con);

if($f['token'] !== 'null'){
	if($a->checkAccessToken($f['token']))
	{
		$pageList = $a->GetPageList($accessToken);
		$_SESSION['facebook_access_token'] = (string) $f['token'];
	}
	else 
	{
		mysqli_query($con, "UPDATE `facebook` SET `token` = 'null'");
		unset($_SESSION['facebook_access_token']);
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin</title>
<base href="<?php echo WEBURL ?>/" />
<link href="assets/css/bootstrap3/bootstrap.css" rel="stylesheet">
<link href="assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">
<link href="assets/css/animate.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
<link href="assets/css/plugins/iCheck/custom.css" rel="stylesheet">
<link href="assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.css" rel="stylesheet" type="text/css">

<style>
textarea {
     width: 100%;
	 height: 150px;
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
<!-- vertical menu -->
<li>
<a href="/"><i class="fa fa-home"></i> <span class="nav-label">Trang chủ</span></a>
</li>
<?php if(isset($_SESSION['admin'])): ?>
<li>
<a href="install"><i class="fa fa-server" aria-hidden="true"></i> <span class="nav-label">Cấu hình máy chủ</span></a>
</li>
<li <?= (empty($_GET['page'])) ? 'class="active"' : null; ?>>
<a href="admin"><i class="fa fa-user-circle" aria-hidden="true"></i> <span class="nav-label">Trang quản trị viên</span></a>
</li>
<li <?= ($_GET['page'] == 'approval') ? 'class="active"' : null; ?>>
<a href="admin/approval"><i class="fa fa-clock-o" aria-hidden="true"></i> <span class="nav-label">Bài viết chờ phê duyệt</span></a>
</li>
<li <?= ($_GET['page'] == 'post') ? 'class="active"' : null; ?>>
<a href="admin/post"><i class="fa fa-check-square-o" aria-hidden="true"></i> <span class="nav-label">Bài viết đã phê duyệt</span></a>
</li>
<li <?= ($_GET['page'] == 'facebook') ? 'class="active"' : null; ?>>
<a href="admin/facebook"><i class="fa fa-facebook-square" aria-hidden="true"></i> <span class="nav-label">Kết nối với Facebook</span></a>
</li>
<li <?= ($_GET['page'] == 'change') ? 'class="active"' : null; ?>>
<a href="admin/change"><i class="fa fa-address-card" aria-hidden="true"></i> <span class="nav-label">Đổi mật khẩu</span></a>
</li>
<li>
<a href="server/action?type=admin&do=logout"><i class="fa fa-power-off" aria-hidden="true"></i>  <span class="nav-label">Đăng xuất</span></a>
</li>
<?php endif; // --> if(isset($_SESSION['admin'])) ?>
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
<!-- current page -->
<div class="row wrapper border-bottom white-bg page-heading">
<div class="col-lg-10">
<h2><?php echo $page ?></h2>
<ol class="breadcrumb">
<li>
<a href="/">Trang chủ</a>
</li>
<?php if(isset($_SESSION['admin'])): ?>
<li>
<a href="/">Quản trị viên</a>
</li>
<?php endif; ?>
<li class="active">
<strong><?php echo $page ?></strong>
</li>
</ol>
</div>
<div class="col-lg-2">
</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
<div class="row">
<div class="ibox float-e-margins">
	<div class="ibox-title">
		<h5><?php echo $page ?></h5>
	</div>
	<div class="ibox-content">
<?php if(isset($_SESSION['admin'])):
if(!empty($_GET["page"])):
switch($_GET['page']):
/* THAY ĐỔI MẬT KHẨU QUẢN TRỊ VIÊN */
case 'change': ?>
<form id="change" method="POST" action="" class="form-horizontal">
<div class="form-group"><label class="col-sm-2 control-label">Mật khẩu</label>
	<div class="col-sm-10"><input type="password" name="password" value="" class="form-control"></div>
</div>
<div class="form-group">
	<div class="col-sm-12">
		<button style="float: right" id="chgbtn" class="btn btn-primary" value="submit" name="submit" type="submit">Thay đổi đăng nhập</button>
	</div>
</div>
</form>
<?php break;
/* QUẢN LÝ BÀI ĐĂNG CHỜ DUYỆT */
case 'approval':
if(empty($_GET['p']))
		$_GET['p'] = 1;
if($_GET['p'] >= 2)
	$pages = ($_GET['p'] - 1) * 10;
else
	$pages = 0; ?>
<p>Để giảm thiểu số lượng spam những bài viết ở đây đều phải qua phê duyệt mới có thể xuất hiện trên trang web. Các bài viết trên trang web sẽ được sắp xếp theo thời gian phê duyệt!</p>
<hr>
<div id="post">
<?php
foreach($a->GetPost($pages, 0) as $p){
	echo $p;
}
 ?>
</div>


<?php break;
/* QUẢN LÝ BÀI ĐĂNG ĐÃ DUYỆT */
case 'post':
if(empty($_GET['p']))
		$_GET['p'] = 1;

if($_GET['p'] >= 2)
	$pages = ($_GET['p'] - 1) * 10;
else
	$pages = 0; ?>
<div id="post">
<p>Bài viết được hiển thị theo thời gian duyệt</p>
<?php 
foreach($a->GetPost($pages, 1) as $p){
	echo $p;
}
?>
</div>


<?php break;
/* KẾT NỐI VỚI FACEBOOK */
case 'facebook': ?>
<?php if($a->checkAccessToken($f['token'])): ?>
<center><a class="btn btn-success btn-facebook btn-outline" href="<?php echo $loginUrl ?>">
	<i class="fa fa-facebook"> </i> Đăng nhập với Facebook
</a></center>
<?php else: ?>
<center><a class="btn btn-success btn-facebook">
	<i class="fa fa-check"> </i> Đã đăng nhập Facebook
</a></center>
<form id="fb-config" method="POST" action="" class="form-horizontal">
<div class="form-group"><label class="col-sm-2 control-label"></label>
	<div class="col-sm-10"><div class="i-checks checked"><label> <input name="fb-mode" type="checkbox" value="1" <?php if($f['fb_mode'] == 1) echo 'checked'; ?>> Mở chế độ đăng lên Trang</label></div></div>
</div>
<div class="form-group"><label class="col-sm-2 control-label">ID Page</label>
	<!--<div class="col-sm-10"><input type="text" name="page-id" value="<?php echo $f['page_id'] ?>" class="form-control"></div>-->
	<div class="col-sm-10"><select class="form-control m-b" name="page-id">
	<?php foreach($pageList as &$v): ?>
		<option <?= ($f["page_id"] == $v["id"]) ? "selected" : null; ?> value="<?= $v["id"] ?>"><?= $v["name"] ?></option>
	<?php endforeach; ?>
	</select></div>
</div>
<div class="form-group"><label class="col-sm-2 control-label">Nội dung bài viết</label>
	<div class="col-sm-10"><textarea name="fb-content"><?php echo base64_decode($f['content']) ?></textarea>
	<small><strong>{{content}}</strong> đại diện cho nội dung của confession</small></div>
</div>
<div class="form-group">
	<div class="col-sm-12">
		<button style="float: right" id="fb-config-btn" class="btn btn-primary" value="submit" name="submit" type="submit">Cập nhật thay đổi</button>
	</div>
</div>
</form>
<?php endif;
break;
endswitch;
/* TRANG CHỦ */
else: ?>
<div class="alert alert-success" style="color:#1abc9c" role="alert">
<font color="black">Trang web được sáng tạo và phát triển bởi <a href="https://www.facebook.com/100022176820483">Vy Nghĩa</a>. Mọi góp ý và phản hồi xin hãy liên hệ qua Facebook hoặc Email, nếu có chia sẻ mong bạn hãy giữ nguồn cho mình.<br />
<strong>Email:</strong> phamvynghia@gmail.com<br />
<br />
<strong>Cảm ơn đã sử dụng!</strong></font>
</div>
<?php endif;endif;
if(empty($_SESSION['admin'])): ?>
<form id="Login" method="POST" action="" class="form-horizontal">
<div class="form-group"><label class="col-sm-2 control-label">Username</label>
<div class="col-sm-10"><input type="text" name="username" value="" class="form-control" autocomplete="off"></div>
</div>
<div class="form-group"><label class="col-sm-2 control-label">Password</label>
<div class="col-sm-10"><input type="password" name="password" value="" class="form-control"></div>
</div>
<div class="form-group">
<div class="col-sm-4 col-sm-offset-2">
<button id="lgbtn" class="btn btn-primary" value="submit" name="submit" type="submit">Đăng nhập</button>
</div>
</div>
</form>
<?php endif; ?>
</div>
</div> 

<div class="row" style="text-align: center">
<?php
	if($_GET['page'] == "approval" || $_GET['page'] == "post"):
	switch($_GET['page']){
		case 'approval':
			$query = 'SELECT * FROM `post` WHERE `approval` = 0';
			break;
		case 'post':
			$query = 'SELECT * FROM `post` WHERE `approval` = 1';
			break;
	}
	
	$n = mysqli_num_rows(mysqli_query($con,$query)) / 10;
	if(mysqli_num_rows(mysqli_query($con,$query)) % 10 > 0)
		$n+=1;
	$n = (int) $n; 
	if(mysqli_num_rows(mysqli_query($con,$query)) > 0 && isset($_SESSION['admin'])): ?>
<ul class="pagination pagination-sm">
    <li class="<?= ($_GET['p']-1 != 0) ? 'first' : 'first disabled';?>"><a href="/admin/<?= $_GET['page'] ?>?p=1">First</a></li>
    <li class="<?= ($_GET['p']-1 != 0) ? 'prev' : 'prev disabled';?>"><a href="/admin/<?= $_GET['page'] ?><?= $_GET['page'] ?>?p=<?= ($_GET['p']-1 != 0) ? $_GET['p']-1 : 1;?>">Previous</a></li>
	<?php for($i = 1; $i <= $n; $i++): ?>
    <li class="<?= ($_GET['p'] == $i) ? 'page active' : 'page'; ?>"><a href="/admin/<?= $_GET['page'] ?>?p=<?= $i ?>"><?= $i ?></a></li>
	<?php endfor; ?>
    <li class="<?= ($_GET['p']+1 > $n) ? 'next disabled' : 'next'; ?>"><a href="/admin/<?=$_GET['page'] ?>?p=<?= $_GET['p']+1 ?>">Next</a></li>
    <li class="<?= ($_GET['p']+1 > $n) ? 'last disabled' : 'last'; ?>"><a href="/admin/<?= $_GET['page'] ?>?p=<?= $n ?>">Last</a></li>
</ul>
<?php endif;endif; ?>
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
<script src="assets/js/plugins/iCheck/icheck.min.js"></script>
<script src="assets/js/plugins/toastr/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.min.js"></script>
<script>
<?php if(isset($_SESSION['admin'])):
if($f['fb_mode'] == 1){
	if($a->checkAccessToken($f['token'])){
		echo "toastr.error('access_token của bạn đã hết hạn, vui lòng cập nhật lại')";
	}
}
?>
$(document).ready(function () {
	$('.i-checks').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
	});
});
		
/* APPROVAL & RE-APPROVAL POST */
$('#post').on('click', '#approval', function() {
let $this = $(this);
let id = $this.data('id');
let type = $this.data('type');

//Alert content
if (type == 'allow') {
	alert = 'Bài viết này sẽ được phê duyệt và hiển thị trên trang chủ';
	result = 'Bài viết này đã được phê duyệt';
} else if (type == 're-approval') {
	alert = 'Bài viết này sẽ được đưa về trạng thái chờ phê duyệt'; 
	result = 'Tác động lên bài viết đã được thực hiện';
} else {
	alert = 'Bài viết này sẽ bị từ chối và đồng thời xóa khỏi hệ thống'; 
	result = 'Bài viết này đã bị xóa';
}

swal({
	  title: 'Bạn chắc chắn điều này?',
	  text: alert,
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Chấp nhận',
	  cancelButtonText: 'Hủy'
	}).then(function () {
		$.ajax({
		method: 'POST',
		url: 'server/action.php?do=approval',
		dataType: "json",
		data: { id: id, type: type },
		success: function (data) {
				toastr.success(result, "Thành công!")
				$('#post-id-' + id).remove()
				
				if(data.facebook !== null && data.facebook == true){
					$.ajax({
						method: 'POST',
						url: 'server/action.php?do=post&type=facebook',
						data: {id: id},
						beforeSend: function(){
							toastr.info("Đang đăng bài viết lên Trang Facebook")
						},
						success: function(data) {
							toastr.success('Bài đăng này đã được đăng lên Trang')
						},
						error: function(){
							toastr.error('Không thể đăng lên Trang', 'Đã xảy ra lỗi')
						}
				   });
				}
			},
			error: function(){
				toastr.error('Đã xảy ra lỗi cục bộ, vui lòng thử lại!')
			}
		});
	});
});

/* FACEBOOK CONFIG */
$("#fb-config").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: "server/action.php?do=config&type=facebook",
		type: "POST",
		data:  new FormData(this),
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function () {
			$('#fb-config-btn').text('Đang xử lý...').prop('disabled', true)
		},
		success: function(data) {
			toastr.success('Thay đổi của bạn đã được lưu')
		},
		error: function(){
			toastr.error("Đã xảy ra lỗi!", "Đã xảy ra lỗi cục bộ, vui lòng thử lại!")
		},
		complete: function(){
			$('#fb-config-btn').text('Cập nhật thay đổi').prop('disabled', false)
		}
   });
}));

/* CHANGE ADMIN PASSWORD */
$("#change").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: "server/action.php?do=change&type=admin",
		type: "POST",
		data:  new FormData(this),
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function () {
			$('#chgbtn').text('Đang xử lý...').prop('disabled', true)
		},
		success: function(data) {
			$('#chgbtn').text('Thay đổi đăng nhập').prop('disabled', false)
			if(data == true)
				location.reload();
		},
		error: function(){
			toastr.error('Đã xảy ra lỗi cục bộ, vui lòng thử lại!')
			$('#chgbtn').text('Thay đổi đăng nhập').prop('disabled', false)
		}
   });
}));
<?php endif; ?>

$("#Login").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: "server/auth.php?login=admin",
		type: "POST",
		data:  new FormData(this),
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function () {
			$('#lgbtn').text('Đang xử lý...').prop('disabled', true)
		},
		success: function(data) {
			$('#lgbtn').text('Đăng nhập').prop('disabled', false)
			if(data == 'success')
				location.reload();
			else if (data == 'failed')
				toastr.error("Tài khoản hoặc mật khẩu không đúng!")
			else if(data == 'null')
				toastr.error("Vui lòng không để trống thông tin đăng nhập!")
			else
				toastr.errorl("Máy chủ không phản hồi dữ liệu!")
		},
		error: function(){
			toastr.error('Đã xảy ra lỗi cục bộ, vui lòng thử lại!')
			$('#lgbtn').text('Đăng nhập').prop('disabled', false)
		}
   });
}));
</script>
</body>
</html>
