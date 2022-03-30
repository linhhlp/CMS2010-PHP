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
 * This is default template admin page 
 * wich I designed in 2009.
 * You can make new template by following this default.
 */

/*
 * Firstly, This is setting.
 * 
 */

global $THEME_PATH; //This is path of theme
global $admin_HLP; // This is name of admin file , default is index.php
global $PARAS; // This is $_GET[] - controller of our web.
if($_CONFIG == false) global $_CONFIG;
$type_user = $_CONFIG->getPermission();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<title>admin site for HLP blog</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">		
<?php
echo '<link href="' . $THEME_PATH . 'hlp.css" type=text/css rel="stylesheet"> 	 ';
echo '<base href="'._URL.'"	/>';
?>

</head>

<body>
<br>
<h2 align=center><a href="<?php
echo $admin_HLP;
?>">TRANG QUẢN LÝ DÀNH CHO ADMIN</a></h2>
<table border=1 align=center width=911px>
	<tr>
		<td><!--hlp_position_login_admin-->
<?php
global $login;
if ($login->getLogged () == TRUE) {
	
	?>
		<!--hlp_position_main_admin-->



<br />
<!--hlp_position_menu_admin-->

<br />

    <!--hlp_position_main_content-->
<?php
}
?>
</td>
	</tr>
	<tr>
		<td id=FOOTER_HLP>HLPwebsite @ 2009<br>
		Designed and copyright reserved by HLP</td>
	</tr>
</table>

</body>
</html>
