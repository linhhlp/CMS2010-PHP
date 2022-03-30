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
 * Setting
 */
global $admin_HLP;
global $PARAS, $ACTION, $CONTROL;
$frontpage = new frontpage();

echo "Quản lí frontpage<br><a href='".urlmanager::makeLink($PARAS,'new','')."'>
		<img src=" . _URL.IMA . "/+.png title='tao moi'>Tạo mới</a>
		<br><br>";

if ($ACTION == '0') {
	//		include_once("thu_vien/cat_van_ban.php");
	$results = $frontpage->getList ();
	if ($results != FALSE) {
		echo '<p align=center> <table border="1" width=100%>';
		echo '<tr>';
		echo '<td width=25%> <font color=blue>Tiêu đề </font></td>';
		echo '<td width=5%><font color=blue>Thể loại</font></td>';
		echo '<td width=30%><font color=blue>Địa chỉ web</font></td>';
		echo '<td width=20%><font color=blue>Chú thích</font></td>';
		echo '<td width=5%><font color=blue>Ngày</font></td>';
		echo '<td width=5%><font color=blue>Cho phép xem</font></td>';
		echo '<td width=5%><font color=blue> Sửa </font></td>';
		echo '<td><font color=blue> Xóa </font></td> 
	  				</tr>';
		foreach ( $results as $key => $hienthi ) {
			echo "<tr>";
			echo "<td>&nbsp;" . $hienthi ['title'] . "</td>";
			echo "<td>&nbsp;" . $hienthi ['tl'] . "</td>";
			echo "<td>&nbsp;" . $hienthi ['link'] . "</td>";
			echo "<td>&nbsp;" . $hienthi ['descr'] . "</td>";
			echo "<td>&nbsp;" . $hienthi ['date'] . "</td>";
			echo "<td>&nbsp;" . $hienthi ['publish'] . "</td>";
			echo "<td>".urlmanager::makeLink($PARAS,'update',$hienthi ['id'],'edit','Edit')."</td>";
			echo "<td>".urlmanager::makeLink($PARAS,'delete',$hienthi ['id'],'delete','Delete')."</td>";
			echo "</tr>";
		}
		echo '</table>';
		echo '</p>';
	} else
		echo "You have not configured your frontpage yet. Please make new one.<br />";
} elseif (($ACTION == "new") && (! isset ( $_POST ['title'] ))) // Hiển thị form để nhập bài mới
{
	$homnay = date ( dmYHis );
	$date_viet = substr ( $homnay, 4, 4 ) . "-" . substr ( $homnay, 2, 2 ) . "-" . substr ( $homnay, 0, 2 ) . " " . (substr ( $homnay, 8, 2 ) - 1) . "-" . substr ( $homnay, 10, 2 ) . "-" . substr ( $homnay, 12, 2 );
	echo textEdittor('exact','descr','advanced');
	echo "Tạo mới";
	echo '<form method=POST action="'.urlmanager::makeLink($PARAS,'new','').'"> <table border="1" width=70% align=center>';
	echo '<tr><td > <font >Tiêu đề </font></td><td ><input  type=text name="title" size=35></td></tr>';
	echo '<td >Thể loại</td><td><select name=tl  size=3><option value=nhac>Nhạc  (chỉ hỗ trợ mp3)</option><option value=video>Video (chỉ hỗ trợ  flv)</option><option value=baiviet selected>Bài viết hoặc mã nhúng </option></select></td>';
	echo '<tr><td ><font >Địa chỉ web</font></td><td >link file hoặc mã nhúng<textarea  type=text name="link" cols=100 rows=15></textarea> </td></tr>';
	echo '<tr><td ><font > Chú thích </font></td><td ><textarea rows=15 name=descr cols=100></textarea></td></tr>';
	echo '<tr><td ><font> Ngày nhập </font></td><td ><input  type=text name="date" size=35 value="' . $date_viet . '"></td></tr>';
	echo '<tr><td ><font >Cho phép xem</font></td><td ><input  type=text name="publish" size=35 value=1></td></tr>';
	echo '<tr >';
	echo '<td ><input  type=submit value="Đồng ý"></td>';
	echo '<td ><input  type=reset value="Nhập lại"></td>';
	echo '</tr ></table>';
} elseif (($ACTION == "new") && (isset ( $_POST ['title'] )) && (! isset ( $_POST ['id'] ))) // Đưa vào CSDL	1 bài mới
{
	$suc_cre = $frontpage->createFront ( $_POST ['title'], $_POST ['tl'], $_POST ['descr'], $_POST ['link'], $_POST ['date'], $_POST ['publish'] );
	if ($suc_cre == TRUE) {
		echo "<br>Success to create new.";
	} else
		echo "<br />Fail to create new.";
} elseif (($ACTION == "update") && ($CONTROL != '0')) // Hiện ra form để sửa 1 bài
{
	echo textEdittor('exact','descr','advanced');
	$id = $CONTROL;
	$hienthi = $frontpage->getList($id);
	echo '<form method=POST action="'.urlmanager::makeLink($PARAS,$ACTION,'').'"> <table border="1" width=70% align=center><input  type=hidden name="id" size=35 value="' . $id . '">';
	echo '<tr><td > <font >Tiêu đề </font></td><td ><input  type=text name="title" size=35 value="' . $hienthi ['title'] . '"></td></tr>';
	echo '<td >Thể loại</td><td><select name=tl  size=3>';
	if ($hienthi ['tl'] == 'nhac')
		echo '<option value=nhac selected>Nhạc  (chỉ hỗ trợ mp3)</option>';
	else
		echo '<option value=nhac >Nhạc (chỉ hỗ trợ mp3)</option>';
	if ($hienthi ['tl'] == 'video')
		echo '<option value=video selected>Video (chỉ hỗ trợ  flv)</option>';
	else
		echo '<option value=video >Video (chỉ hỗ trợ  flv)</option>';
	if ($hienthi ['tl'] == 'baiviet')
		echo '<option value=baiviet selected>Bài viết hoặc mã nhúng </option>';
	else
		echo '<option value=baiviet >Bài viết  hoặc mã nhúng</option>';
	echo '</select></td>';
	echo '<tr><td ><font > Địa chỉ web</font></td><td ><textarea  type=text name="link" cols=100 rows=15>' . $hienthi ['link'] . '</textarea></td></tr>';
	echo '<tr><td ><font > Chú thích</font></td><td ><textarea rows=15 name=descr id=descr cols=100>' . $hienthi ['descr'] . '</textarea></td></tr>';
	echo '<tr><td ><font> Ngày nhập </font></td><td ><input  type=text name="date" size=35  value="' . $hienthi ['date'] . '"></td></tr>';
	echo '<tr><td ><font >Cho phép xem</font></td><td ><input  type=text name="publish" size=35  value="' . $hienthi ['publish'] . '"></td></tr>';
	echo '<tr >';
	echo '<td ><input  type=submit value="Đồng ý"></td>';
	echo '<td ><input  type=reset value="Nhập lại"></td>';
	echo '</tr ></table>';
} elseif (($ACTION == "update") && (isset ( $_POST ['id'] ))) // Sửa  1 bài trong  CSDL
{
	$id = $_POST ['id'];
	$suc = $frontpage->updateFront($id,$_POST ['title'],$_POST ['tl'],$_POST ['descr'],$_POST ['link'],$_POST ['date'],$_POST ['publish']);
	if($suc == TRUE) echo "Update succsessfully.";
	else echo "Can not update.";
} elseif (($ACTION == "delete") && (isset ( $CONTROL ))) // Xóa
{
	$id = $CONTROL;
	$suc = $frontpage->deleteFront($id);
	if($suc == TRUE) echo "Delete successfully";
	else echo "Can not delete.";
}

?>
