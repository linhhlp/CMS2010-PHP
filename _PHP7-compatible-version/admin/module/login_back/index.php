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
 * This is module manages and proccess your log-in
 */
/**
 * 
 * Setting:
 */
global $admin_HLP;
global $_URL,$PARAS,$ACTION;
$allow_log = 4; // allow number of times log-in fail.
?>
<?php
global $login;
if(!isset($login)) $login = new login();

if($ACTION == 'logout') {
	$login->logout();
	echo 'Log-out successfully.';
	driect_url($admin_HLP);
}
else {
	if( $login->getLogged() == FALSE) {
		if(!empty($_POST)) $_SESSION['POST'] = $_POST;
		$remain = $allow_log - $_SESSION['loi'];
		if($remain ==0) {
			echo "You was locked because You failed to log-in ".$allow_log." times. ";
		}
		elseif($remain > 0) {
			echo 'Please login:';
			echo "
			<form action=" . $_URL->getOldUrl() . " method=POST>
			<table>
			<tr><td>Nickname</td><td><input type=text name=user></td></tr>
			<tr><td>Password</td><td><input type=password name=pass></td></tr>
			<tr><td colspan=2><input type=submit  value=Submit></td></tr></table>
			";
			if($remain < 4) echo "<br /><font color=red> You fail to log-in ".$_SESSION['loi']." per ".$allow_log." times allowance </font>";
		}
	}
	elseif(!isset($_SESSION['1st'])) {	// For first times log-in
		$_SESSION['1st'] = 1;
		echo "Đăng nhập thành công<br>\n";
					echo "<a href=''>Đang vào trang quản lí, xin chờ hoặc nhấn vào đây</a><br>\n";
					driect_url($_URL->getOldUrl());
	}

}
?>
