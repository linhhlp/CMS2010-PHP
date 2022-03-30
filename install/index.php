<?php
// No direct access
defined('INSTALL') or die;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Install</title>
<link href="install/css.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h2 class="welcome">Chào mừng bạn đến với phần cài đặt!</h2>
<?php 
if($_POST['host'] == '') {
?>
<h2 class="welcome">Hãy nhập các thông tin cần thiết để cài đặt. </h2>
<h3 class="setting">Database</h3>
<form action="" method="post" >
<label> Host </label> <input type="text" name="host" /> example : localhost<br />
<label> Database Type </label> <input type="text" name="typedb" value="MySQL"/><br />
<label> User name </label> <input type="text" name="user" />example : root<br />
<label> Password </label> <input type="password" name="pass" />example : 123456<br />
<label> Database name </label> <input type="text" name="name" />example : myblog<br />
<label> Prefix </label> <input type="text" name="prefix" value="blog2_" /><br />
<label> Auto check </label> <input name="autocheck" type="radio" value="FALSE" checked />OFF <span class=recommended>recommended </span><br />
<label> &nbsp; </label> <input name="autocheck" type="radio" value="TRUE"  /> ON <br />
<h3 class="setting">URL</h3>
<label> Base url </label> <input type="text" name="baseurl" value="<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>" />example : http://hlp7.com/blog/<br />
<label> Friendly url </label> <input name="friendly" type="radio" value="NORMAL"  />OFF <br />
<label> &nbsp; </label> <input name="friendly" type="radio" value="FRIENDLY"  checked/> ON <span class=recommended>recommended </span><br />
<label> Type of separation </label> <input type="text" name="urltype" value="urlslash" /><span class=recommended>recommended </span><br />
<h3 class="setting">Other</h3>
<label> Notify by </label> <input type="text" name="notify" value="mailphp" /><span class=recommended>recommended </span><br />
<label> Title website</label> <input type="text" name="titlewebsite" value="Welcome to website" /><br />
<label> Admin email </label> <input type="text" name="adminemail" value="" />example : abc@gmail.com<br />
<label> Timezone </label> <input type="text" name="settimezone" value="Asia/Ho_Chi_Minh" /> <span class=recommended>recommended </span><br />
<h3 class="setting">Create an Admin Account</h3>
<label> Username </label> <input type="text" name="username" />example : admin<br />
<label> Password </label> <input type="password" name="pass1" />example : 123456<br />
<label> Re-Password </label> <input type="password" name="pass2" />example : 123456<br />
<h3 class="setting">Install</h3>
<label> Install </label> <input type="submit"  value="Ok" /><input type="reset"  value="Reset" /> <br />
</form>
<?php 
}
else {
	// Create Database
	$conn = mysql_connect($_POST['host'],$_POST['user'],$_POST['pass']);
	define('PREFIX',$_POST['prefix']);
	define('NAMEDB',$_POST['name']);
	include('install/create_db.php');
	// Import necessary records
	include('install/import_db.php');
	// Create Username
	if($_POST['username'] != '' && $_POST['pass1'] == $_POST['pass2']) {
		$create_user_query = "INSERT INTO `".PREFIX."user` (`id` , `user`, `pass`, `name_user`, `birthday`, `home`, `mobile`, `tel`, `address`, `type`, `activation`, `enable`, `note`) VALUES ('1', '".$_POST['username']."', '".md5($_POST['pass1'])."', '', '', '', '', '', '', '4', '1', '1', '');";
		mysql_query($create_user_query);
	}
	else {
		echo '<h2 class="unsuccessful">Không thành công</h2> Sai mật khẩu';
		return;
	}
	// Check success or not
	$check = 'SELECT * FROM `'.PREFIX.'user` WHERE id="1"';
	$check_result = mysql_query($check);
	if($check_result != FALSE) {
		//create config file
		$configfile = file_get_contents('install/config.php.hlp4ever');
		foreach ($_POST as $key=>$value) {
			$configfile = str_replace ('_'.$key.'_',$value,$configfile);
		}
		file_put_contents('config.php',$configfile);
	}
	else {
		echo '<h2 class="unsuccessful">Không thành công</h2>Không tạo được CSDL';
		return;
	}
	if(is_file('config.php')) {
		echo '<h2 class="successful">Thành công</h2>';
	}
	else {
		echo '<h2 class="unsuccessful">Không thành công</h2>Không tạo được file config.php';
	}
}
?>
</body>
</html>