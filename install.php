<?php
/* >_ Developed by Vy Nghia */
require 'server/config.php';
session_start();

if(isset($_SESSION['install'])){
	if(!empty($_GET["do"]) && $_GET['do'] == 'update')
	{
		switch($_GET['type'])
		{
			case 'web':
				$e = file_get_contents('server/lib/example/config.example.php');
				
				$c 	= ["{1}", "{2}", "{3}", "{4}", "{5}"];
				$n  = [$_POST['weburl'], $_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname']];
				
				$nC = str_replace($c, $n, $e);
				$nF = fopen("server/config.php", "w") or die("Không thể thay đổi file này!");
				fwrite($nF, $nC);
				fclose($nF);
				
				echo (true);
				break;
			case 'sdk':
				$e = file_get_contents('server/lib/example/app.fb.example.php');
				
				$c 	= ["{FB_APP_ID}", "{FB_APP_SR}"];
				$n  = [$_POST['fb-app-id'], $_POST['fb-app-secret']];
				
				$nC = str_replace($c, $n, $e);
				$nF = fopen("server/app.fb.php", "w") or die("Không thể thay đổi file này!");
				fwrite($nF, $nC);
				fclose($nF);
				
				echo (true);
				break;
			case 'psw':
				$PasswordFile = fopen("server/lib/data/auth/password/install.pass", "w") or die("Không thể thay đổi file này!");
				fwrite($PasswordFile, md5($_POST['install-password']));
				fclose($PasswordFile);
				
				echo (true);
				break;
		}
		
		exit;
	}
} else {
	$_SESSION['install'] = null;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Server / Install</title>
<link href="assets/css/bootstrap3/bootstrap.css" rel="stylesheet">
<link href="assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">
<link href="assets/css/animate.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
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
<!-- vertical menu -->
<li>
<a href="/"><i class="fa fa-home"></i> <span class="nav-label">Trang chủ</span></a>
</li>
<?php if(isset($_SESSION['install'])): ?>
<li class="active">
<a href="install"><i class="fa fa-server" aria-hidden="true"></i> <span class="nav-label">Cấu hình máy chủ</span></a>
</li>
<li>
<a href="admin"><i class="fa fa-user-circle" aria-hidden="true"></i> <span class="nav-label">Trang quản trị viên</span></a>
</li>
<li>
<a href="server/action?type=install&do=logout"><i class="fa fa-power-off" aria-hidden="true"></i>  <span class="nav-label">Đăng xuất</span></a>
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
<!-- current page -->
<div class="row wrapper border-bottom white-bg page-heading">
<div class="col-lg-10">
<h2><?php echo !$_SESSION['install'] ? 'Đăng nhập' : 'Quản lý cấu hình'; ?></h2>
<ol class="breadcrumb">
<li>
<a href="/">Trang chủ</a>
</li>
<?php if(isset($_SESSION['install'])): ?>
<li class="active">
<strong>Quản lý cấu hình website</strong>
</li>
<?php else: ?>
<li class="active">
<strong>Xác thực truy cập</strong>
<?php endif; ?>
</li>
</ol>
</div>
<div class="col-lg-2">
</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
<div class="row">
<div class="col-lg-12">
<div class="ibox float-e-margins">
	<div class="ibox-title">
		<h5><?php echo !$_SESSION['install'] ? 'Đăng nhập' : 'Quản lý cấu hình'; ?></h5>
	</div>
	<div class="ibox-content">
	   <?php if(isset($_SESSION['install'])): 
		define('NO', '<i class="fa fa-times">');
		define('YES', '<i class="fa fa-check">');
		
		function _chPHPVersion(){
			$v = substr(phpversion(), 0, -(strlen(phpversion()) - 1));
			
			if($v >= 5){
				return true;
			} else {
				return false;
			}
		}
		?>
		<div class="alert alert-success" style="color:#1abc9c" role="alert">
		<font color="black">Trang web được sáng tạo và phát triển bởi <a href="https://www.facebook.com/100022176820483">Vy Nghĩa</a>. Mọi góp ý và phản hồi xin hãy liên hệ qua Facebook hoặc Email, nếu có chia sẻ mong bạn hãy giữ nguồn cho mình.<br />
		<strong>Email:</strong> phamvynghia@gmail.com<br />
		<br />
		<strong>Cảm ơn đã sử dụng!</strong></font>
		</div>
		 <table class="table table-bordered">
			<thead>
			  <tr>
				<th colspan="2"><center>Kiểm tra tiêu chuẩn hệ thống</center></th>
			  </tr>
			  <tr>
				<th>Điều kiện</th>
				<th width="180px">Trạng thái</th>
			  </tr>
			</thead>
			<tbody>
			  <tr>
				<td>Kiểm tra phiên bản PHP</td>
				<td><?= (_chPHPVersion()) ? YES :NO ?></td>
			  </tr>
			  <tr>
				<td>Kết nối với cơ sở dữ liệu (mysql)</td>
				<td><?= ($con) ? YES : NO ?></td>
			  </tr>
			  <tr>
				<td>Phiên bản PHP trên server của bạn</td>
				<td><?= phpversion() ?></td>
			  </tr>
			</tbody>
		  </table>
		<?php else: ?>
		<form id="Login" method="POST" action="" class="form-horizontal">
		<div class="form-group">
			<label class="col-sm-2 control-label">Password</label>
		<div class="col-sm-10">
			<input type="password" name="password" placeholder="Mật khẩu truy cập" value="" class="form-control">
		</div>
		</div>
		<div class="form-group">
		<div class="col-sm-4 col-sm-offset-2">
			<button id="lgbtn" class="btn btn-primary" value="submit" name="submit" type="submit">Đăng nhập</button>
		</div>
		</div>
		</form>
		<?php
		endif;
		?>
	</div>
</div>

<hr>

<?php if(isset($_SESSION['install'])): ?>
<div class="ibox float-e-margins">
	<div class="ibox-title">
		<h5>Cấu hình website</h5>
	</div>
	<div class="ibox-content">
	   <form id="install-web" method="POST" action="" class="form-horizontal">
			<div class="form-group"><label class="col-sm-2 control-label">Website</label>
				<div class="col-sm-10"><input type="text" name="weburl" value="<?php echo WEBURL ?>" placeholder="Địa chỉ trang Confession" class="form-control"></div>
			</div>
			
			<div class="form-group"><label class="col-sm-2 control-label">DB Host</label>
				<div class="col-sm-10"><input type="text" name="dbhost" value="<?php $db->dbinfo('dbhost') ?>" placeholder="localhost" class="form-control"></div>
			</div>
			
			<div class="form-group"><label class="col-sm-2 control-label">DB Username</label>
				<div class="col-sm-10"><input type="text" name="dbuser" value="<?php $db->dbinfo('dbuser') ?>" placeholder="db username" class="form-control"></div>
			</div>
			
			<div class="form-group"><label class="col-sm-2 control-label">DB Password</label>
				<div class="col-sm-10"><input type="text" name="dbpass" value="<?php $db->dbinfo('dbpass') ?>" placeholder="db password" class="form-control"></div>
			</div>
			
			<div class="form-group"><label class="col-sm-2 control-label">DB Name</label>
				<div class="col-sm-10"><input type="text" name="dbname" value="<?php $db->dbinfo('dbname') ?>" placeholder="select db name" class="form-control"></div>
			</div>
			<div class="form-group">
			<div class="col-sm-4 col-sm-offset-2">
				<button id="isnbtn-web" class="btn btn-primary" value="submit" name="submit" type="submit">Lưu thay đổi</button>
			</div>
			</div>
		</form>
	</div>
</div>

<div class="ibox float-e-margins">
	<div class="ibox-title">
		<h5>Cấu hình SDK Facebook</h5>
	</div>
	<div class="ibox-content">
	   <form id="install-sdk" method="POST" action="" class="form-horizontal">
			<div class="form-group"><label class="col-sm-2 control-label">App ID</label>
				<div class="col-sm-10"><input type="text" name="fb-app-id" value="<?php echo FB_APP_ID ?>" placeholder="Facebook App ID" class="form-control"></div>
			</div>
			
			<div class="form-group"><label class="col-sm-2 control-label">App Secret</label>
				<div class="col-sm-10"><input type="text" name="fb-app-secret" value="<?php echo FB_APP_SR ?>" placeholder="Facebook App Secret" class="form-control"></div>
			</div>
			
			<div class="form-group">
			<div class="col-sm-4 col-sm-offset-2">
				<button id="isnbtn-sdk" class="btn btn-primary" value="submit" name="submit" type="submit">Lưu thay đổi</button>
			</div>
			</div>
		</form>
	</div>
</div>


<div class="ibox float-e-margins">
	<div class="ibox-title">
		<h5>Mật khẩu Install Panel</h5>
	</div>
	<div class="ibox-content">
	   <form id="install-psw" method="POST" action="" class="form-horizontal">
			<div class="form-group"><label class="col-sm-2 control-label">Password</label>
				<div class="col-sm-10"><input type="password" name="install-password" value="" placeholder="Mật khẩu trang Cấu hình" class="form-control"></div>
			</div>
			
			<div class="form-group">
			<div class="col-sm-4 col-sm-offset-2">
				<button id="isnbtn-psw" class="btn btn-primary" value="submit" name="submit" type="submit">Cập nhật</button>
			</div>
			</div>
		</form>
	</div>
</div>
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
<script src="assets/js/jquery-2.1.1.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/js/inspinia.js"></script>
<script src="assets/js/plugins/pace/pace.min.js"></script>
<script src="assets/js/jquery.twbsPagination.min.js"></script>
<script src="assets/js/plugins/toastr/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.min.js"></script>
<script>
<?php if(isset($_SESSION['install'])): ?>
/* INSTALL WEB & DATABASE */
$("#install-web").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: "install.php?do=update&type=web",
		type: "POST",
		data:  new FormData(this),
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function () {
			$('#isnbtn-web').text('Đang xử lý...').prop('disabled', true)
		},
		success: function(data) {
			toastr.success('Các thay đổi về cấu hình đã được lưu')
			if(data == true){
				swal({
				  title: 'Updated!',
				  text: 'Cấu hình đã được thay đổi! Bạn muốn nạp dữ liệu vào database ngay bây giờ không?',
				  type: 'success',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Chấp nhận',
				  cancelButtonText: 'Hủy'
				}).then(function () {
					$.ajax({
						url: "server/action?do=install&type=mysql",
						type: "POST",
						contentType: false,
						cache: false,
						processData:false,
						success: function(data) {
							if(data == true)
								toastr.success('Đã nạp dữ liệu (databse) vào cơ sở dữ liệu của bạn', 'Hoàn thành!')
							else
								toastr.error('Cú pháp lỗi: ' + data, 'Không thể database')
						}
					})
				});
			}
		},
		error: function(){
			toastr.error("Đã xảy ra lỗi cục bộ, vui lòng thử lại!")
		},
		complete: function(){
			$('#isnbtn-web').text('Lưu thay đổi').prop('disabled', false)
		}
   });
}));

/* INSTALL APP SDK */
$("#install-sdk").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: "install.php?do=update&type=sdk",
		type: "POST",
		data:  new FormData(this),
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function () {
			$('#isnbtn-sdk').text('Đang xử lý...').prop('disabled', true)
		},
		success: function(data) {
			$('#isnbtn-sdk').text('Lưu thay đổi').prop('disabled', false)
			if(data == true)
				toastr.success('Các thay đổi về cấu hình đã được lưu')
			else
				toastr.error('Đã có lỗi xảy ra, vui lòng thử lại')
		},
		error: function(){
			toastr.error("Đã xảy ra lỗi cục bộ, vui lòng thử lại!")
		},
		complete: function(){
			$('#isnbtn-sdk').text('Lưu thay đổi').prop('disabled', false)
		}
   });
}));

/* CHANGE PASSWORD INSTALL */
$("#install-psw").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: "install.php?do=update&type=psw",
		type: "POST",
		data:  new FormData(this),
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function () {
			$('#isnbtn-psw').text('Đang xử lý...').prop('disabled', true)
		},
		success: function(data) {
			if(data == true)
				toastr.success('Các thay đổi về cấu hình đã được lưu')
			else
				toastr.error('Đã có lỗi xảy ra, vui lòng thử lại')
		},
		error: function(){
			toastr.error("Đã xảy ra lỗi cục bộ, vui lòng thử lại!")	
		},
		complete: function(){
			$('#isnbtn-sdk').text('Cập nhật').prop('disabled', false)
		}
   });
}));
<?php endif; ?>

$("#Login").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: "server/auth.php?login=install",
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
				toastr.error("Mật khẩu truy cập không đúng, vui lòng gắng thử lại!")
			else
				toastr.error("Máy chủ không phản hồi dữ liệu!", "error")
			console.log(data);
		},
		error: function(){
			swal("Đã xảy ra lỗi!", "Đã xảy ra lỗi cục bộ, vui lòng thử lại!", "error")
			$('#lgbtn').text('Đăng nhập').prop('disabled', false)
		}
   });
}));
</script>
</body>
</html>
