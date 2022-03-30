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
global $_CONFIG;
if(!isset($com_params)) global $com_params; 
$config = explode ( ',', $com_params);
if (empty ( $config [0] ))
	$config [0] = 200;
if (empty ( $config [1] ))
	$config [1] = 20;
$userbonus	= new userbonus();
?>

<?php 

echo	"Quản lí bài viết<br>
<a href=".urlmanager::makeLink($url[0],'new','')."><img src="._URL.IMA."/new.png title='Viết bài mới'>Viết bài mới</a><br><br>";

if($url[1] == 'new') {
	if(empty($_POST)) {
		echo textEdittor('exact','content','advanced');
		$homnay=date(dmYHis);
		$date_viet=substr($homnay,4,4)."-".substr($homnay,2,2)."-".substr($homnay,0,2)." ".(substr($homnay,8,2)-1)."-".substr($homnay,10,2)."-".substr($homnay,12,2);
		echo "
			<form action='".urlmanager::makeLink($url[0],$url[1],'')."' method=POST>
			<table border=0>
			<tr><td>Title*</td><td><input name=title type=text></td></tr>
			<tr><td>Content*</td><td><textarea rows=15 name=content cols=100 id=fulltext></textarea></td></tr>
			<tr><td>Send to*</td><td>";
		$all_user = user::getUserName('','id');
		echo "<select name=belong>";
	 	foreach ($all_user as $key=>$value) {
	 		echo "<option value='".$key."'>".$value."</option>";
	 	}
	 	
	 	echo "</select>";
		echo "</td></tr>";
		echo "<tr><td>Create date*</td><td><input name=created type=text value='$date_viet'></td></tr>
			<tr><td>Public</td><td><select name=public><option value=0 >No</option><option value=1 selected=selected>Yes</option></select></td></tr>
			<tr><td>Send</td><td><input value=submit type=submit></td></tr>
			</table>
			</form>
		";
	}
	else {
		$suc = $userbonus->createBounus($_POST['title'],$_POST['content'],$_POST['created'],$_POST['belong'],$_POST['public']);
		if($suc == FALSE) {
			echo 'Create new content Fail';
		}
		else {
			echo 'Create new content successfull.';
		}
	}
}
elseif ($url[1] == 'edit') {
	if(empty($_POST)) {
		echo textEdittor('exact','content','advanced');
				$contents = $userbonus->getBonus($url[2],'',0,1,'','');
				$content  = $contents[0];
				$homnay=date(dmYHis);
				$date_viet=substr($homnay,4,4)."-".substr($homnay,2,2)."-".substr($homnay,0,2)." ".(substr($homnay,8,2)-1)."-".substr($homnay,10,2)."-".substr($homnay,12,2);
				echo "
					<form action='".urlmanager::makeLink($url[0],$url[1],$url[2])."' method=POST>
					<table border=0>
					<tr><td>Title*</td><td><input name=title type=text value='".$content['title']."'></td></tr>
					<tr><td>Content*</td><td><textarea rows=15 name=content cols=100 id=fulltext>".$content['content']."</textarea></td></tr>
					<tr><td>Send to*</td><td>";
		$all_user = user::getUserName('','id');
		echo "<select name=belong>";
	 	foreach ($all_user as $key=>$value) {
	 		echo "<option value='".$key."' ".($key==$content['belong']?'selected=selected':'').">".$value."</option>";
	 	}
	 	
	 	echo "</select>";
		echo "</td></tr>
			
			<tr><td>Public</td><td><select name=public><option value=1 ".((1==$content['public'])?"selected=selected":"").">Everybody</option><option value=0 ".((0==$content['public'])?"selected=selected":"").">Private</option></select></td></tr>
			<tr><td>Create date*</td><td><input name=created type=text  value='".$content['created']."'></td></tr>
			<tr><td>Modify date*</td><td><input name=modified type=text  value='".$date_viet."'></td></tr>
			<tr><td>Status</td><td><select name=status><option value=1 ".((1==$content['status'])?"selected=selected":"").">Read</option><option value=0 ".((0==$content['status'])?"selected=selected":"").">UnRead</option></select></td></tr>
			<tr><td>Send</td><td><input value=submit type=submit></td></tr>
			</table>
			</form>
				";
	}
	else {
		$suc = $userbonus->updateBonus($url[2],$_POST['title'],$_POST['content'],$_POST['created'],$_POST['modified'],$_POST['belong'],$_POST['status'],$_POST['public']);
		if($suc == FALSE) {
			echo 'Update content Fail';
		}
		else {
			echo 'Update content successfull.';
		}
	}
}
elseif ($url[1] == 'delete') {
	$suc = $userbonus->deleteBonus($url[2]);
if($suc == FALSE) {
			echo 'Delete content Fail';
		}
		else {
			echo 'Delete content successfull.';
		}
}
else {
	
	list($user_id,$page_current) = explode ( '-', $url[2]);
	if(empty($page_current)) {
		$page_current = 0;
	}
	if($page_current >1) $page_limit = $page_current -1;
	else $page_limit = 0;
	$limit_start = $config[1]*$page_limit;
	//if($page_start[0]=='blog' && !empty($page_start[1]))    $limit_start = $page_start[1];
	list($type_get,$type_get_id) = explode ( '-', $url[1]);
	if($user_id > 0) {

	}
	$content = $userbonus->getBonus('','',$limit_start,$config [1],'','');
	$num_com  = $userbonus->getNumMessage();
	if($content != FALSE) {
		echo "<table border=1 width=100% style='border:2px dashed #008000; border-collapse:collapse' bordercolorlight='#00FF00' bordercolordark='#008000'>
				<tr><td width=5%>#</td><td width=5%>Title</td><td>Content</td><td  width=10%>From</td><td  width=10%>To</td><td  width=5%>Status</td><td  width=5%>Publish</td><td  width=5%>Edit</td><td  width=5%>Delete</td></tr>";
		$i = 1;
		foreach ($content as $key=>$value) {
			$extract = wrap_content($value['content'],0,$config [0]);
			if($value['public'] == 0) 		$public = 'Private';
			elseif($value['public'] == 1)  $public = 'Everybody';
			if($value['status'] == 0) 		$status = 'unread';
			elseif($value['status'] == 1)  $status = 'read';
			$all_user = user::getUserName('','id');
			echo "<tr>
				<td>$i</td><td>".urlmanager::makeLink($url[0],'edit',$value['id'],'read more',$value['title'])."</td><td>".$extract."</td><td>".$all_user[$value['created_by']]."</td><td>".$all_user[$value['belong']]."</td>
				<td><img src="._URL.IMA."/".$status.".gif title='".$status."' alt='".$status."'></td>
				<td>".$public."</td><td>".urlmanager::makeLink($url[0],'edit',$value['id'],'edit','Edit')."</td><td>".urlmanager::makeLink($url[0],'delete',$value['id'],'delete','Delete')."</td>";
			echo "";
			$i++;
		}
		echo "</table>";
		if($user_id <= 0) $user_id = 0;
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
                echo urlmanager::makeLink($url[0],$url[1],$user_id.'-'.$value,'bai viet trang thu '.$value,'<span class="CONTENT_HLP_PAGE">'.$value.'</span>');
            }
        }
        echo '</div>';
	}
	else {
		echo 'You have no content here. Create new one.';
	}
}
?>

