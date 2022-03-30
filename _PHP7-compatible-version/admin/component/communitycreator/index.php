<?php

/**
 * @author HLP4ever .
 * @version		2010
 * @package		HLP4ever blog.
 * @copyright	Copyright (C) 2010 by HLP.
 * @license		Open Source and free for non-conmecial
  * Installation KIT for web
 */
// No direct access
defined('START') or die;
if(!isset($com_path)) global $com_path;
if(!isset($_URL)) global $_URL;
$url = $_URL->getNewUrl ();
?>
<!-- CSS style -->

<style>
@charset "utf-8";

.welcome {
	text-align:center;
}
label {
	width:100px;
	display:block;
	float:left;
}
.recommended {
	color:red;
}
.sucessful {
	color:red;
}
.unsuccessful {
	color:red;
	text-decoration:blink;
}

</style>
<?php 
if($_POST['account'] == '') {
?>
<h2 class="welcome">Tạo tài khoản mới. </h2>
<form action="<?php echo $_URL->getOldUrl(); ?>" method="post" >
<label> tên tài khoản </label> <input type="text" name="account" /><br />

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
	define('ACCPREFIX',$_POST['account']."_");
	global $_CONFIG;
	$DB_info = $_CONFIG->getDB();
	define('NAMEDB',$DB_info['NAME']);
	include($com_path.'/create_db.php');
	// Import necessary records
	include($com_path.'/import_db.php');
	// Create Username
	if($_POST['username'] != '' && $_POST['pass1'] == $_POST['pass2']) {
		$create_user_query = "INSERT INTO `".ACCPREFIX."user` (`id` , `user`, `pass`, `name_user`, `birthday`, `home`, `mobile`, `tel`, `address`, `type`, `activation`, `enable`, `note`) VALUES ('1', '".$_POST['username']."', '".md5($_POST['pass1'])."', '', '', '', '', '', '', '3', '1', '1', '');";
		mysqli_query($create_user_query);
	}
	else {
		echo '<h2 class="unsuccessful">Không thành công</h2> Sai mật khẩu';
		return;
	}
	
	// Check success or not
	$check = 'SELECT * FROM `'.ACCPREFIX.'user` WHERE id="1"';
	$check_result = mysqli_query($check);
	if($check_result != FALSE) {
		// copy file
		// function 
		function recurse_copy($src,$dst) {
		    $dir = opendir($src);
		    @mkdir($dst);
		    while(false !== ( $file = readdir($dir)) ) {
		        if (( $file != '.' ) && ( $file != '..' )) {
		            if ( is_dir($src . '/' . $file) ) {
		                recurse_copy($src . '/' . $file,$dst . '/' . $file);
		            }
		            else {
		                copy($src . '/' . $file,$dst . '/' . $file);
		            }
		        }
		    }
		    closedir($dir);
		} 
	// start copying 
		recurse_copy($com_path."/_installfiles", "../".$_POST['account']);
	}
	else {
		echo '<h2 class="unsuccessful">Không thành công</h2>Không tạo được CSDL';
		return;
	}
	if(is_file("../".$_POST['account'].'/index.php')) {
		echo '<h2 class="successful">Thành công</h2>';
	}
	else {
		echo '<h2 class="unsuccessful">Không thành công</h2>Không copy được file ';
	}
}

?>
