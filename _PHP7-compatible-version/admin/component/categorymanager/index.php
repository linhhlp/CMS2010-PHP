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
<link href="<?php echo _URL.ADM.'/'.COM.'/categorymanager/';?>/css.css" type=text/css rel="stylesheet">
 -->
 <style type="text/css">
.CATEGORY_INPUT_NAME {
	width:100px;
	float:left;
	border: 1px solid #333;
}
.CATEGORY_WARNING {
	color:red;
}
.CATEGORY_H3 {
	color:yellow;
}
</style>
<?php
list($type , $cateid) = explode('-',$url[1]);
$category 	= new category();
// If there are posted datas, so create new
if($_POST['title'] != '' && empty($_POST['id'])) {
	$category->CreateCate($_POST['title'],$_POST['name'],$_POST['public'],$_POST['image'],$_POST['order'],$_POST['descr'],$_POST['parent']);
	echo '<div class="CATEGORY_WARNING">Created. </div>';
}
// Or Update
elseif($_POST['title'] != '' && !empty($_POST['id'])) {
	$category->UpdateCate($_POST['id'],$_POST['title'],$_POST['name'],$_POST['alias'],$_POST['public'],$_POST['image'],$_POST['order'],$_POST['descr'],$_POST['parent']);
	echo '<div class="CATEGORY_WARNING">Updated. </div>';
}
if($type == 'delete' ) {
	$acate = $category->DeleteCate ($cateid);
	echo '<div class="CATEGORY_WARNING">DELETED. </div>';
}

$cate_all   = $category->listAll();
global $_URL;
$url = $_URL->getNewUrl();
$url_full = $_URL->getOldUrl();

// Form Update 

if($type == 'edit' ) {
	$acate = $category->cate_info ($cateid);
	echo "<h3 class=CATEGORY_H3 > Update a category </h3>";
	echo "<form action='".$url_full."' method=POST >
	<input type=hidden name='id' value=".$cateid." >
		<div class='CATEGORY_INPUT_NAME' > Title </div><input type=text name='title' value='".$acate['title']."'> <br />
		<div class='CATEGORY_INPUT_NAME' > Image Link </div><input type=text name='image'  value='".$acate['image']."'><br />
		<div class='CATEGORY_INPUT_NAME' > Name </div><input type=text name='name'  value='".$acate['name']."'><br />
		<div class='CATEGORY_INPUT_NAME' > Alias </div><input type=text name='alias'  value='".$acate['alias']."'><br />
		<div class='CATEGORY_INPUT_NAME' > Order </div><input type=text name='order'  value='".$acate['order']."'><br />
		<div class='CATEGORY_INPUT_NAME' > Description </div><textarea name='descr'>".$acate['descr']."</textarea><br />
		";
	if($cate_all == FALSE) {
	 	echo '<div class="CATEGORY_WARNING">You have no parent category. </div>';
	}
	 else {
	 	echo "<div class='CATEGORY_INPUT_NAME' > Parent category </div><select name=parent>";
	 	echo "<option value='0' ".( $acate['parent']=='0'?'selected':'').">No parent</option>";
	 	foreach ($cate_all as $key=>$value) {
	 		echo "<option value='".$value['id']."' ".( $acate['parent']==$value['id']?'selected':'').">".$value['title']."</option>";
	 	}
	 	echo "</select><br /><br />";
	}
	echo "<div class='CATEGORY_INPUT_NAME' > Public </div><select name=public>
	 		<option value='1'  ".( $acate['public']== '1'?'selected':'').">Enable</option>
	 		<option value='0'  ".( $acate['public']== '0'?'selected':'')." >Disable</option>
			</select><br />";
	echo "<input type=submit value='Cập nhật' ><br />";
	echo "</form>";
}
else {
	// Form create new
	echo "<h3 class=CATEGORY_H3 > Create new category </h3>";
	echo "<form action='".$url_full."' method=POST >
			<div class='CATEGORY_INPUT_NAME' > Title </div><input type=text name='title' > <br />
			<div class='CATEGORY_INPUT_NAME' > Image Link </div><input type=text name='image' ><br />
			<div class='CATEGORY_INPUT_NAME' > Name </div><input type=text name='name' ><br />
			<div class='CATEGORY_INPUT_NAME' > Order </div><input type=text name='order' ><br />
			<div class='CATEGORY_INPUT_NAME' > Description </div><textarea name='descr'></textarea><br />
			";
	if($cate_all == FALSE) {
	 	echo '<div class="CATEGORY_WARNING">You have no parent category. </div>';
	}
	 else {
	 	echo "<div class='CATEGORY_INPUT_NAME' > Parent category </div><select name=parent>";
	 	echo "<option value='0' selected>No parent</option>";
	 	foreach ($cate_all as $key=>$value) {
	 		echo "<option value='".$value['id']."'>".$value['title']."</option>";
	 	}
	 	echo "</select><br /><br />";
	}
	echo "<div class='CATEGORY_INPUT_NAME' > Public </div><select name=public>
	 		<option value='1' selected>Enable</option>
	 		<option value='0'>Disable</option>
			</select><br />";
	echo "<input type=submit value='Tạo mới' ><br />";
	echo "</form>";
}
// List all category
echo "<h3 class=CATEGORY_H3 > List category </h3>";
if($cate_all == FALSE) {
 	echo '<div class="CATEGORY_WARNING">You have no category.</div>';
}
else {
	$i=1;
	echo "<table border=1 width=100%><tr><td width=5%>&nbsp;</td><td width=25%>Title</td><td>Name</td><td>Alias</td><td width=20%>Description</td><td>Order</td><td width=10%>Image</td><td>Public</td><td>Parent</td><td> Delete </td></tr>";
	$child_array = array();
	foreach ($cate_all as $key=>$value) {
		$child_array[$value['id']] = $value['title'];
	}
 	foreach ($cate_all as $key=>$value) {
 		if($value['parent'] =='0') $parent_name = 'No parent';
 		else  $parent_name = $child_array[$value['parent']];
 		echo "<tr><td>&nbsp;".$i."</td><td>&nbsp;<a href='".urlmanager::makeLink($url[0],'edit-'.$value['id'],$url[2])."'> ".$value['title']."</td>
 		<td>&nbsp;".$value['name']."</td><td>&nbsp;".$value['alias']."</td><td>&nbsp;".$value['descr']."</td><td>&nbsp;".$value['order']."</td><td>&nbsp;".$value['image']."</td><td>&nbsp;".$value['public']."</td>
 		<td>".$parent_name."</td>
 		<td><a href='".urlmanager::makeLink($url[0],'delete-'.$value['id'],$url[2])."'><img border=0 align=right valign=bottom src='"._URL.IMA."/del.png' title='Xóa'></a></td>
 		</tr>";
 		$i++;
 	}
 	echo "</table>";
}