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
global $PARAS,$ACTION,$CONTROL;
global $_CONFIG;
if(!isset($com_params)) global $com_params; 
$config = explode ( ',', $com_params);
if (empty ( $config [0] ))
	$config [0] = 200;
if (empty ( $config [1] ))
	$config [1] = 20;
$category 		= new category();
$contentmanage	= new contentmanager();

?>

<?php 

echo	"Quản lí bài viết<br><a href=".urlmanager::makeLink($PARAS,'new','')."><img src="._URL.IMA."/new.png title='Viết bài mới'>Viết bài mới</a><br><br>";

if($ACTION == 'new') {
	if(empty($_POST)) {
		echo textEdittor('exact','fulltext','advanced');
		$homnay=date(dmYHis);
		$date_viet=substr($homnay,4,4)."-".substr($homnay,2,2)."-".substr($homnay,0,2)." ".(substr($homnay,8,2)-1)."-".substr($homnay,10,2)."-".substr($homnay,12,2);
		echo "
			<form action='".urlmanager::makeLink($PARAS,$ACTION,'')."' method=POST>
			<table border=0>
			<tr><td>Title*</td><td><input name=title type=text></td></tr>
			<tr><td>Category*</td><td>";
		$cate_menu = $category->listAll();
		 if($cate_menu == FALSE) {
		 	echo '<font color=red>You have no category. Please create new one before write new content.</font>';
		 }
		 else {
		 	echo "<select name=category>";
		 	foreach ($cate_menu as $key=>$value) {
		 		echo "<option value='".$value['id']."'>".$value['title']."</option>";
		 	}
		 	
		 	echo "</select>";
		 }
		echo "</td></tr>
			<tr><td>Introduction</td><td><textarea rows=5 name=introtext cols=70></textarea></td></tr>
			<tr><td>Content*</td><td><textarea rows=15 name=fulltext cols=100 id=fulltext></textarea></td></tr>
			<tr><td>Publish*</td><td><select name=publish><option value=1 selected=selected>Everybody</option><option value=2>Friend</option><option value=0 >Private</option></select></td></tr>
			<tr><td>Create date*</td><td><input name=created type=text value='$date_viet'></td></tr>
			<tr><td>Hit*</td><td><input name=hit type=text value=1></td></tr>
			<tr><td>frontpage</td><td><select name=frontpage><option value=0 selected=selected>No</option><option value=1>Yes</option></select></td></tr>
			<tr><td>Send</td><td><input value=submit type=submit></td></tr>
			</table>
			</form>
		";
	}
	else {
		$suc = $contentmanage->createContent($_POST['title'],$_POST['category'],$_POST['introtext'],$_POST['fulltext'],$_POST['publish'],$_POST['created'],$_POST['modified'],$_POST['hit'],$_POST['frontpage']);
		if($suc == FALSE) {
			echo 'Create new content Fail';
		}
		else {
			echo 'Create new content successfull.';
		}
	}
}
elseif ($ACTION == 'edit') {
	if(empty($_POST)) {
		echo textEdittor('exact','fulltext','advanced');
				$contents = $contentmanage->getContent($CONTROL,0,1,'','');
				$content  = $contents[0];
				$homnay=date(dmYHis);
				$date_viet=substr($homnay,4,4)."-".substr($homnay,2,2)."-".substr($homnay,0,2)." ".(substr($homnay,8,2)-1)."-".substr($homnay,10,2)."-".substr($homnay,12,2);
				echo "
					<form action='".urlmanager::makeLink($PARAS,$ACTION,$CONTROL)."' method=POST>
					<table border=0>
					<tr><td>Title*</td><td><input name=title type=text value='".$content['title']."'></td></tr>
					<tr><td>Category*</td><td>";
				$cate_menu = $category->listAll();
				 if($cate_menu == FALSE) {
				 	echo '<font color=red>You have no category. Please create new one before write new content.</font>';
				 }
				 else {
				 	echo "<select name=category>";
				 	foreach ($cate_menu as $key=>$value) {
				 		echo "<option value='".$value['id']."' ".(($value['id']==$content['category'])?"selected=selected":"").">".$value['title']."</option>";
				 	}
				 	
				 	echo "</select>";
				 }
				echo "</td></tr>
					<tr><td>Introduction</td><td><textarea rows=5 name=introtext cols=70>".$content['introtext']."</textarea></td></tr>
					<tr><td>Content*</td><td><textarea rows=15 name=fulltext cols=100 id=fulltext>".$content['fulltext']."</textarea></td></tr>
					<tr><td>Publish*</td><td><select name=publish><option value=1 ".((1==$content['publish'])?"selected=selected":"").">Everybody</option><option value=2 ".((2==$content['publish'])?"selected=selected":"").">Friend</option><option value=0 ".((0==$content['publish'])?"selected=selected":"").">Private</option></select></td></tr>
					<tr><td>Create date*</td><td><input name=created type=text  value='".$content['created']."'></td></tr>
					<tr><td>Modify date*</td><td><input name=modified type=text  value='".$date_viet."'></td></tr>
					<tr><td>Hit*</td><td><input name=hit type=text  value='".$content['hit']."'></td></tr>
					<tr><td>frontpage</td><td><select name=frontpage><option value=0 ".((0==$content['frontpage'])?"selected=selected":"").">No</option><option value=1 ".((1==$content['frontpage'])?"selected=selected":"").">Yes</option></select></td></tr>
					<tr><td>Send</td><td><input value=submit type=submit></td></tr>
					</table>
					</form>
				";
	}
	else {
		$suc = $contentmanage->updateContent($CONTROL,$_POST['title'],$_POST['category'],$_POST['introtext'],$_POST['fulltext'],$_POST['publish'],$_POST['created'],$_POST['modified'],$_POST['hit'],$_POST['frontpage']);
		if($suc == FALSE) {
			echo 'Update content Fail';
		}
		else {
			echo 'Update content successfull.';
		}
	}
}
elseif ($ACTION == 'delete') {
	$suc = $contentmanage->deleteContent($CONTROL);
if($suc == FALSE) {
			echo 'Delete content Fail';
		}
		else {
			echo 'Delete content successfull.';
		}
}
else {
	
	list($category_id,$page_current) = explode ( '-', $url[2]);
	if(empty($page_current)) {
		$page_current = 0;
	}
	if($page_current >1) $page_limit = $page_current -1;
	else $page_limit = 0;
	$limit_start = $config[1]*$page_limit;
	//if($page_start[0]=='blog' && !empty($page_start[1]))    $limit_start = $page_start[1];
	list($type_get,$type_get_id) = explode ( '-', $url[1]);
	if($category_id > 0) {
		// Get name of the category
		$categoy = new category();
		$categoy_info = $categoy->cate_info($category_id);
		echo "<div class='CONTENT_THONGKE_THEO'>Thống kê theo: ".$categoy_info['title']."</div>";
	}
	$content = $contentmanage->getContent('',$limit_start,$config [1],'','');
	$num_com  = $contentmanage->getNumContents();
	if($content != FALSE) {
		echo "<table border=1 width=100% style='border:2px dashed #008000; border-collapse:collapse' bordercolorlight='#00FF00' bordercolordark='#008000'>
				<tr><td width=5%>#</td><td width=5%>Title</td><td>Content</td><td  width=10%>Category</td><td  width=5%>Publish</td><td  width=5%>Edit</td><td  width=5%>Delete</td></tr>";
		$i = 1;
		foreach ($content as $key=>$value) {
			$extract = wrap_content($value['fulltext'],0,$config [0]);
			if($value['publish'] == 0) 		$publish = 'Private';
			elseif($value['publish'] == 1)  $publish = 'Everybody';
			elseif ($value['publish'] == 2) $publish = 'Friend';
			$cate_info= $category->cate_info($value['category']);
			
		echo "<tr>
				<td>$i</td><td>".urlmanager::makeLink($url[0],'edit',$value['id'],'read more',$value['title'])."</td><td>".$extract."</td><td>".$cate_info['title']."<td>".$publish."</td><td>".urlmanager::makeLink($PARAS,'edit',$value['id'],'edit','Edit')."</td><td>".urlmanager::makeLink($PARAS,'delete',$value['id'],'delete','Delete')."</td>";
			echo "";
			$i++;
		}
		echo "</table>";
		if($category_id <= 0) $category_id = 0;
        echo '<div class="CONTENT_HLP_PAGE_TITLE">Sang trang:<br />';
		$page_array = paging($page_current,$num_com/$config [1]);
        foreach($page_array as $key=>$value) {
            if($key =='current') {
                echo '<span class="CONTENT_HLP_PAGE_CURRENT">'.$value.'</span>';
            }
            elseif($value =='...') {
                echo $value;
            }
            else {
                echo urlmanager::makeLink($url[0],$url[1],$category_id.'-'.$value,'bai viet trang thu '.$value,'<span class="CONTENT_HLP_PAGE">'.$value.'</span>');
            }
        }
        echo '</div>';
	}
	else {
		echo 'You have no content here. Create new one.';
	}
}
?>

