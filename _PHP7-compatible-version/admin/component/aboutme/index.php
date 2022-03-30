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
 * This module manages content.
 */


/**
 * Setting
 */
global $admin_HLP;
if(!isset($_URL)) global $_URL;
$url = $_URL->getNewUrl ();
if($_CONFIG == false) global $_CONFIG;
$type_user = $_CONFIG->getPermission();

if(!isset($com_params)) global $com_params; 
$config = explode ( ',', $com_params);
if (empty ( $config [0] ))
	$config [0] = 200;
if (empty ( $config [1] ))
	$config [1] = 20;

$aboutme = new aboutme;

if($_POST['user'] != "") {
	echo "Proccessing";
	$homnay=date(dmYHis);
	$last_update=substr($homnay,4,4)."-".substr($homnay,2,2)."-".substr($homnay,0,2)." ".(substr($homnay,8,2)-1)."-".substr($homnay,10,2)."-".substr($homnay,12,2);
	$pass1	=md5($_POST['password1'])		;
	$pass2	=md5($_POST['password2'])		;
	$pass3	=md5($_POST['password3'])		;
	if(($pass1== $_SESSION['pass'])&&($pass2==$pass3)) {
		$pass = $pass2;
	}
	else $pass = '';
	$update_user = new user();
	$update_user->updateUser('',$_POST['user'],$_POST['name_user'],$pass,$_POST['birthday'],$_POST['home'],$_POST['mobile'],$_POST['tel'],$_POST['address'],$_POST['note']);
	echo 'here';
	$aboutme->updateInfo('',$_POST['info'],	$_POST['picture'],	$_POST['aboutme'],	$_POST['love'] ,	$_POST['biogra'],	$_POST['history'] ,	$_POST['note']);	
	echo '... done';
}
// get infor
$_about_hienthi = $aboutme->getInfo ();
?>

<?php 

echo	"<h3>Quản lí thông tin cá nhân</h3>";
echo textEdittor();
echo	"Phần quản lí thông tin cá nhân<br>Nếu cần thay đổi thì sửa và nhấn cập nhập, nếu ko cần thiết thì giữ nguyên<br>";
		echo	"<form method=post action='".urlmanager::makeLink($url[0],'','')."' >
		<table border=0 align=center width=900px>";
		echo	"<tr><td width=30% align=right>Tên đầy đủ</td><td><input type=text name=name_user value='".$_about_hienthi['name_user']."'></td></tr>";
		echo	"<tr><td width=30% align=right>Tên đăng nhập</td><td><input type=text name=user value='".$_about_hienthi['user']."'></td></tr>";
		echo	"<tr><td width=30% align=right>Sinh nhật</td><td><input type=text name='birthday' value='".$_about_hienthi['birthday']."'></td></tr>";
		echo	"<tr><td width=30% align=right>Địa chỉ nhà</td><td><input type=text name='home' value='".$_about_hienthi['home']."'></td></tr>";
		echo	"<tr><td width=30% align=right>Điện thoại di động</td><td><input type=text name='mobile' value='".$_about_hienthi['mobile']."'></td></tr>";
		echo	"<tr><td width=30% align=right>Điện thoại cố định</td><td><input type=text name='tel' value='".$_about_hienthi['tel']."'></td></tr>";
		echo	"<tr><td width=30% align=right>Địa chỉ đang ở</td><td><input type=text name='address' value='".$_about_hienthi['address']."'></td></tr>";
		echo	"<tr><td width=30% align=right>Ghi chú</td><td><input type=text name='note' value='".$_about_hienthi['note']."'></td></tr>";
		echo	"<tr><td width=30% align=right>Đang là </td><td>".$type_user[$_about_hienthi['type']]."</td></tr>";
		echo	"<tr><td width=30% align=right>Nếu muốn thay pass thì điền pass cũ</td><td><input type=password name=password1 ></td></tr>";
		echo	"<tr><td width=30% align=right>Rồi gõ pass mới</td><td><input type=password name=password2 ></td></tr>";
		echo	"<tr><td width=30% align=right>Gõ lại pass mới</td><td><input type=password name=password3></td></tr>";
		echo	"";
		echo	"<tr><td width=30% align=right>Vài thông tin </td><td><textarea rows=5 name=info cols=50 >".$_about_hienthi ['info']."</textarea></td></tr>";
		echo	"<tr><td width=30% align=right>Ảnh cá nhân<img src='".$_about_hienthi ['picture']."' width=50px hieght=50px></td><td>Link: <input type=text name=picture value='".$_about_hienthi ['picture']."'></td></tr>";
		echo	"<tr><td width=30% align=right>Những lời tâm sự</td><td><textarea rows=5 name=aboutme cols=50 >".$_about_hienthi ['aboutme']."</textarea></td></tr>";
		echo	"<tr><td width=30% align=right>Sở thích</td><td><textarea rows=5 name=love cols=50  >".$_about_hienthi ['love']."</textarea></td></tr>";
		echo	"<tr><td width=30% align=right>Tiểu sử bản thân</td><td><textarea rows=5 name=biogra cols=50>".$_about_hienthi ['biogra']."</textarea></td></tr>";
		echo	"<tr><td width=30% align=right>Những thành tích đã đạt được</td><td><textarea rows=5 name=history cols=50>".$_about_hienthi ['history']."</textarea></td></tr>";

echo	"<tr><td width=30% align=right><input type=submit  value='Cập nhập'></td><td><input type=reset  value='Nhập lại'></td></tr>";
		echo	"</table>
				</form>";
?>

