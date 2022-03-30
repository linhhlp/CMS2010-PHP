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

?>
<!-- 
<link href="<?php echo _URL.ADM.'/'.COM.'/menumanager/';?>/css.css" type=text/css rel="stylesheet">
 -->
 <style type="text/css">
.menu_INPUT_NAME {
	width:100px;
	float:left;
	border: 1px solid #333;
}
.menu_WARNING {
	color:red;
}
.menu_H3 {
	color:yellow;
}
</style>
<?php
list($type , $menuid) = explode('-',$url[1]);
$menu 	= new menu();
// If there are posted datas, so create new
if($_POST['title'] != '' && empty($_POST['id'])) {
	$menu->Createmenu($_POST['title'],$_POST['link'],$_POST['public'],$_POST['image'],$_POST['order'],$_POST['descr'],$_POST['parent'],$_POST['type']);
	echo '<div class="menu_WARNING">Created. </div>';
}
// Or Update
elseif($_POST['title'] != '' && !empty($_POST['id'])) {
	$menu->Updatemenu($_POST['id'],$_POST['title'],$_POST['link'],$_POST['alias'],$_POST['public'],$_POST['image'],$_POST['order'],$_POST['descr'],$_POST['parent'],$_POST['type']);
	echo '<div class="menu_WARNING">Updated. </div>';
}
if($type == 'delete' ) {
	$menu->Deletemenu ($menuid);
	echo '<div class="menu_WARNING">DELETED. </div>';
}

$menu_all   = $menu->listAll();
global $_URL;
$url = $_URL->getNewUrl();
$url_full = $_URL->getOldUrl();

// Form Update 

if($type == 'edit' ) {
	$amenu = $menu->menu_info ($menuid);
	echo "<h3 class=menu_H3 > Update a menu </h3>";
	echo "<form action='".$url_full."' method=POST >
	<input type=hidden name='id' value=".$menuid." >
		<div class='menu_INPUT_NAME' > Title </div><input type=text name='title' value='".$amenu['title']."'> <br />
		<div class='menu_INPUT_NAME' > Link </div><input type=text name='link'  value='".$amenu['link']."'><br />
		<div class='menu_INPUT_NAME' > Image  </div><input type=text name='image'  value='".$amenu['image']."'><br />
		<div class='menu_INPUT_NAME' > Alias </div><input type=text name='alias'  value='".$amenu['alias']."'><br />
		<div class='menu_INPUT_NAME' > Type </div><input type=text name='type'  value='".$amenu['type']."'><br />
		<div class='menu_INPUT_NAME' > Order </div><input type=text name='order'  value='".$amenu['order']."'><br />
		<div class='menu_INPUT_NAME' > Description </div><textarea name='descr'>".$amenu['descr']."</textarea><br />
		";
	if($menu_all == FALSE) {
	 	echo '<div class="menu_WARNING">You have no parent menu. </div>';
	}
	 else {
	 	echo "<div class='menu_INPUT_NAME' > Parent menu </div><select name=parent>";
	 	echo "<option value='0' ".( $amenu['parent']=='0'?'selected':'').">No parent</option>";
	 	foreach ($menu_all as $key=>$value) {
	 		echo "<option value='".$value['id']."' ".( $amenu['parent']==$value['id']?'selected':'').">".$value['title']."</option>";
	 	}
	 	echo "</select><br /><br />";
	}
	echo "<div class='menu_INPUT_NAME' > Public </div><select name=public>
	 		<option value='1'  ".( $amenu['public']== '1'?'selected':'').">Enable</option>
	 		<option value='0'  ".( $amenu['public']== '0'?'selected':'')." >Disable</option>
			</select><br />";
	echo "<input type=submit value='Cập nhật' ><br />";
	echo "</form>";
}
else {
	// Form create new
	echo "<h3 class=menu_H3 > Create new menu </h3>";
	echo "<form action='".$url_full."' method=POST >
			<div class='menu_INPUT_NAME' > Title </div><input type=text name='title' > <br />
			<div class='menu_INPUT_NAME' > Link </div><input type=text name='link'><br />
			<div class='menu_INPUT_NAME' > Image </div><input type=text name='image' ><br />
			<div class='menu_INPUT_NAME' > Order </div><input type=text name='order' ><br />
			<div class='menu_INPUT_NAME' > Type </div><input type=text name='type' ><br />
			<div class='menu_INPUT_NAME' > Description </div><textarea name='descr'></textarea><br />
			";
	if($menu_all == FALSE) {
	 	echo '<div class="menu_WARNING">You have no parent menu. </div>';
	}
	 else {
	 	echo "<div class='menu_INPUT_NAME' > Parent menu </div><select name=parent>";
	 	echo "<option value='0' selected>No parent</option>";
	 	foreach ($menu_all as $key=>$value) {
	 		echo "<option value='".$value['id']."'>".$value['title']."</option>";
	 	}
	 	echo "</select><br /><br />";
	}
	echo "<div class='menu_INPUT_NAME' > Public </div><select name=public>
	 		<option value='1' selected>Enable</option>
	 		<option value='0'>Disable</option>
			</select><br />";
	echo "<input type=submit value='Tạo mới' ><br />";
	echo "</form>";
}
// List all menu
echo "<h3 class=menu_H3 > List menu </h3>";
if($menu_all == FALSE) {
 	echo '<div class="menu_WARNING">You have no menu.</div>';
}
else {
	$i=1;
	echo "<table border=1 width=100%><tr><td width=5%>&nbsp;</td><td width=25%>Title</td><td>Link</td><td>Alias</td><td width=20%>Description</td><td>Order</td><td width=10%>Image</td><td>Public</td><td>Parent</td><td>Type</td><td> Delete </td></tr>";
	$child_array = array();
	foreach ($menu_all as $key=>$value) {
		$child_array[$value['id']] = $value['title'];
	}
 	foreach ($menu_all as $key=>$value) {
 		if($value['parent'] =='0') $parent_name = 'No parent';
 		else  $parent_name = $child_array[$value['parent']];
 		echo "<tr><td>&nbsp;".$i."</td><td>&nbsp;<a href='".urlmanager::makeLink($url[0],'edit-'.$value['id'],$url[2])."'> ".$value['title']."</td>
 		<td>&nbsp;".$value['link']."</td><td>&nbsp;".$value['alias']."</td><td>&nbsp;".$value['descr']."</td><td>&nbsp;".$value['order']."</td><td>&nbsp;".$value['image']."</td><td>&nbsp;".$value['public']."</td>
 		<td>".$parent_name."</td><td>".$value['type']."</td>
 		<td><a href='".urlmanager::makeLink($url[0],'delete-'.$value['id'],$url[2])."'><img border=0 align=right valign=bottom src='"._URL.IMA."/del.png' title='Xóa'></a></td>
 		</tr>";
 		$i++;
 	}
 	echo "</table>";
}