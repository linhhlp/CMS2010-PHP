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
// commnunity/message/xxx
// $url[2] : 
if($url[2] == 'sendnew') {
	$title_meta_data_temp = "Gửi tin nhắn";
	if(empty($_POST)) {
		if($_SESSION['mobile_type'] == FALSE) { 
	// This is normal display : full capability browser (in windows or Linux ...)
		echo textEdittor('');
		}
		$homnay=date(dmYHis);
		$date_viet=substr($homnay,4,4)."-".substr($homnay,2,2)."-".substr($homnay,0,2)." ".(substr($homnay,8,2))."-".substr($homnay,10,2)."-".substr($homnay,12,2);
		echo "
			<form action='".urlmanager::makeLink($url[0],$url[1],'')."' method=POST>
			<table border=0>
			<tr><td>Title*</td><td><input name=title type=text></td></tr>
			<tr><td>Content*</td><td><textarea rows=15 name=content cols=28 id=fulltext></textarea></td></tr>
			<tr><td>Send to*</td><td>";
		$all_user = user::getUserName('','id');
		echo "<select name=belong>";
		if($url[2] > 0) {
			$rely = (int)$url[2]; 
		}
		
	 	foreach ($all_user as $key=>$value) {
	 		echo "<option value='".$key."' ".($rely==$key?'selected=selected':'').">".$value."</option>";
	 	}
	 	
	 	echo "</select>";
		echo "</td></tr>";
		echo "<tr><td>Create date*</td><td><input name=created type=text value='$date_viet'></td></tr>
			<tr><td>Send</td><td><input value=submit type=submit></td></tr>
			</table>
			</form>
		";
		echo '<br /><a href="javascript:history.go(-1)" >Quay lại</a>';
	}
	else {
		$suc = $userbonus->createBounus($_POST['title'],$_POST['content'],$_POST['created'],$_POST['belong']);
		if($suc == FALSE) {
			echo 'Send a new message Fail';
		}
		else {
			echo 'Send a new message successfull.';
		}
		echo '<br /><a href="javascript:history.go(-1)" >Quay lại</a>';
	}
}
elseif ($url[1] == 'read') {
		$title_meta_data_temp = "Đọc tin nhắn";
		$contents = $userbonus->getBonus($url[2],'',0,1,1,'');
		$content  = $contents[0];
		echo "
			<table border=0 width=95%>
			<tr><td>Title</td><td>".$content['title']."</td></tr>
			<tr><td colspan=2>Content:</td></tr>
			<tr><td colspan=2><div class=userbounus_content>".$content['content']."</div></td></tr>
			<tr><td>From </td><td>";
		$all_user = user::getUserName('','id');
		echo $all_user[$content['created_by']];
		echo "</td></tr>
			<tr><td>Create date</td><td>".$content['created']."</td></tr>
			<tr><td>Modify date</td><td>".$content['modified']."</td></tr>
			</table>
				";
		echo urlmanager::makeLink($url[0],'new',$content['created_by'],'tra loi','Trả lời!');
		echo '<br /><a href="javascript:history.go(-1)" >Quay lại</a>';
}
elseif ($url[1] == 'delete') {
	$title_meta_data_temp = "Xóa tin nhắn";
	$suc = $userbonus->deleteBonus($url[2]);
if($suc == FALSE) {
			echo 'Delete content Fail';
		}
		else {
			echo 'Delete content successfull.';
		}
		echo '<br /><a href="javascript:history.go(-1)" >Quay lại</a>';
}
elseif ($url[1] == 'inbox') {
	$title_meta_data_temp = "Inbox";
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
	$content = $userbonus->getBonus('',$_SESSION['info']['id'],$limit_start,$config [1],'','');
	$num_com  = $userbonus->getNumMessage();
	if($content != FALSE) {
		echo "<table border=1 width=95% style='border:2px dashed #008000; border-collapse:collapse' bordercolorlight='#00FF00' bordercolordark='#008000'>
				<tr><td width=5%>#</td><td width=5%>Title</td><td>Content</td><td  width=10%>From</td><td  width=5%>Status</td><td  width=5%>Delete</td></tr>";
		$i = 1;
		foreach ($content as $key=>$value) {
			$extract = wrap_content($value['content'],0,$config [0]);
			if($value['status'] == 0) 		$status = 'unread';
			elseif($value['status'] == 1)  $status = 'read';
			$all_user = user::getUserName('','id');
			echo "<tr>
				<td>$i</td><td>".urlmanager::makeLink($url[0],'read',$value['id'],'read more',$value['title'],'title="Xem chi tiết"')."</td><td>".$extract."</td><td>".$all_user[$value['created_by']]."</td>
				<td><a href='".urlmanager::makeLink($url[0],'read',$value['id'])."' title='Xem chi tiết'><img src="._URL.IMA."/".$status.".gif title='".$status."' alt='".$status."'></a></td>
				<td>".urlmanager::makeLink($url[0],'delete',$value['id'],'delete','Delete')."</td>";
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
		echo 'You have no message!';
	}
	echo '<br /><a href="javascript:history.go(-1)" >Quay lại</a>';
}
elseif ($url[1] == 'sent') {
	$title_meta_data_temp = "Sent";
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
	$content = $userbonus->getBonus('','',$limit_start,$config [1],1,$_SESSION['info']['id']);
	$num_com  = $userbonus->getNumMessage();
	if($content != FALSE) {
		echo "<table border=1 width=95% style='border:2px dashed #008000; border-collapse:collapse' bordercolorlight='#00FF00' bordercolordark='#008000'>
				<tr><td width=5%>#</td><td width=5%>Title</td><td>Content</td><td  width=10%>To</td><td  width=5%>Status</td></tr>";
		$i = 1;
		foreach ($content as $key=>$value) {
			$extract = wrap_content($value['content'],0,$config [0]);
			if($value['status'] == 0) 		$status = 'unread';
			elseif($value['status'] == 1)  $status = 'read';
			$all_user = user::getUserName('','id');
			echo "<tr>
				<td>$i</td><td>".urlmanager::makeLink($url[0],'read',$value['id'],'read more',$value['title'],'title="Xem chi tiết"')."</td><td>".$extract."</td><td>".$all_user[$value['belong']]."</td>
				<td><a href='".urlmanager::makeLink($url[0],'read',$value['id'])."' title='Xem chi tiết'><img src="._URL.IMA."/".$status.".gif title='".$status."' alt='".$status."'></a></td>";
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
		echo 'You have no sent message!';
	}
	echo '<br /><a href="javascript:history.go(-1)" >Quay lại</a>';
}
else {
	$title_meta_data_temp = "Home";
	$new = $userbonus->isNew($_SESSION['info']['id']);
	if($new > 0) echo "Bạn có ".$new." tin nhắn mới. <br/>";
	else echo "Bạn không có tin nhắn mới. <br/>";
	 echo urlmanager::makeLink($url[0],'inbox','','inbox','Vào hòm thư để đọc tin nhắn.');
	 echo '<br />';
	 echo urlmanager::makeLink($url[0],'sent','','sent','Tin nhắn đã gửi.');
}