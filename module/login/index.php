<?php
/**
 * @author HLP4ever .
 * @version		2010
 * @package		HLP4ever blog.
 * @copyright	Copyright (C) 2010 by HLP.
 * @license		Open Source and free for non-conmecial
 */
// No direct access
defined('START') or die;

/**
 * This is special feature I develop for new Blog.
 * It manages Guest who visit blog, group users: registerd user and guest.
 * Beside that, Some users will have special bonus which was sent from Admin :)
 */
global $_URL; $url = $_URL->getNewUrl(); $ACTION = $url[1];
$old_url = $_URL->getOldUrl();
if($mod_path == false) global $mod_path;
if($_CONFIG == false) global $_CONFIG;
$type_user = $_CONFIG->getPermission();

$welcome = ""	; 	// Set default to display login form.
	// login
$login = new login();
if( $url[0] == "logout") {
	$login->logout();
	driect_url(_URL);
}
else {
	if( isset($_POST['name']) && $_POST['name'] != "" && $_POST['pass'] != "" ) {
		$name = $_POST['name'];$pass = md5($_POST['pass']);
	}
	else {
		$name  = '';
		$pass  = '';
	}
	$login->checkLogin($name,$pass);
    if($login->getLogged() == TRUE) {
    	$user_info = $login->getInfo(); 
        $welcome = $type_user[$user_info['user_type']].': '.$user_info['user_name'];

    }
    elseif(isset($_POST['name']) && isset($_POST['pass'])) {
        $error = 'code_return_1';
    }
    	
}

if($error != "" || $login->getLogged() == FALSE) {
	if($error != "") {
		switch($error) {
			case 'code_return_1';
			echo "Đăng nhập sai tên/mật khẩu";
			break;
			default;
			echo $error;
			break;
		}
	}
?>
<br />
<form method="post" id="form_userbonus" name="form_userbonus" action="<?php echo $old_url; ?>">
<table border=0>
<tr><td>Tên tài khoản</td><td><input name="name" value="" type="text" style='align:right'/></td></tr>
<tr><td>Mật khẩu:</td><td><input name="pass" value="" type="password" /></td></tr>
<tr><td></td><td><input type="submit" value="Đăng nhập" name="submit" /></td></tr>
</table>
</form>

<?php
	}
	elseif($login->getLogged() == true) {
		if(isset($_POST['pass'])&&isset($_POST['name'])) {	// For first times log-in
			echo "Đăng nhập thành công<br>\n";
						echo "<a href=''>Đang vào trang quản lí, xin chờ hoặc nhấn vào đây</a><br>\n";
						driect_url($_URL->getOldUrl());
		}
		else {
		echo 'Xin chào '.$welcome;
		echo "<br />" . urlmanager::makeLink("logout","logout","logout","logout" ," Nhấn vào đây để thoát (log-out)");
		}
	}
if(isset($_SESSION['POST']))  $_POST = $_SESSION['POST'];
	unset($_SESSION['POST']);
	
?>
   </div>