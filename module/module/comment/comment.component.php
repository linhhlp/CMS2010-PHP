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
 * 
 * Manage comment.
 * 
 *  	There are 2 type of comment:
 *  		Blog comment : each content blog will have its comments.
 *  		Front page comment: Comment for blog.
 */
global $PARAS,$ACTION,$CONTROL;
list($type,$id) = explode('-',$CONTROL);
$comment = new comment();
echo '<h4>Manage comment.</h4>';

if($type == 'delete')			// Xóa
{
	$suc = $comment->deleteComment($id);
	if($suc != FALSE) echo 'Delete successfully.';
	else echo 'Delete Fail.';	
}
elseif($type == 'edit')			// Edit
{
	if(!empty($_POST)) {
		$suc = $comment->updateComment($_POST['id'],$_POST['title'],$_POST['comment'],$_POST['belong'],$_POST['user'],$_POST['date'],$_POST['publish']);
		if($suc != FALSE) echo 'Edit successfully.';
		else echo 'Edit fail.';	
	}
	else {
		$comments = $comment->getAcomment($id);
		echo textEdittor('admin');
		echo "
			<form action='".urlmanager::makeLink($PARAS,$ACTION,$CONTROL)."' method=POST>
			<table border=0>
			<tr><td colspan=2><input type=hidden value='".$id."' name=id></td></tr>
			<tr><td width=200px>Tên người gửi</td><td><input name=user type=text value='".$comments['user']."'></td></tr>
			<tr><td>Tiêu đề</td><td><input type=text name=title value='".$comments['title']."'></td></tr>
			<tr><td>Date</td><td><input type=text name=date value='".$comments['date']."'></td></tr>
			<tr><td>Bình luận</td><td><textarea rows=5 name=comment cols=70>".$comments['comment']."</textarea></td></tr>
			<tr><td>Của bài viết id (ko nên thay đổi)</td><td><input name=belong type=text  value='".$comments['belong']."'></td></tr>
			<tr><td>Đồng ý Cho mọi người xem*</td><td><select name=publish><option value=1 ".($comments['publish']==1?'selected=selected':'').">Everybody</option><option value=2 ".($comments['publish']==2?'selected=selected':'').">Private</option><option value=0 ".($comments['publish']==0?'selected=selected':'').">No publish</option></select></td></tr>
			<tr><td colspan=2><input value='Gửi lên' type=submit></td></tr>
			</table>
			</form>
			";
		
	}
}
else{
	$user_name	= $comment->getUserName();
	
	$blog_comments 	= $comment->getComments('blog');
	$blog_title = $comment->getBlogTitle();
	echo 'Blog comment<br />';
	echo 	"<table border=1>";
	echo 	'<tr>	<td width=3%>#</td>	<td width=10%>Title</td>	<td width=8%>User</td>
				<td>Comment</td> 	<td>Publish</td>	<td width=5%>Edit</td>		<td width=4%>Delete</td></tr>';
	if($blog_comments != FALSE ) {
	 	foreach($blog_comments as $key=>$value)
		{
			if($value['publish'] == 1) $publish = 'Everybody';
				elseif($value['publish'] == 2) $publish = 'Friend';
				else $publish = 'Private';
			echo	"<tr><td>".($key + 1)."</td>";
			echo "<td>".$value['title']."</td><td>".$user_name[$value['user']]."</td>";
			echo "<font color=yellow><div id=com_blogENT_HLP>".$value['comment']."</div></font>";
			echo "</td>
				<td  width=20%>Của bài viết ". $blog_title[$value['belog']]['title']."</td>
				<td>". $publish."</td>
				<td><a href='".urlmanager::makeLink($PARAS,$ACTION,'edit-'.$value['id'])."'><img border=0 align=right valign=bottom src='hinh_anh/edit.png' title='Sửa bài viết này'></a></td>
				<td><a href='".urlmanager::makeLink($PARAS,$ACTION,'delete-'.$value['id'])."'><img border=0 align=right valign=bottom src='hinh_anh/del.png' title='Xóa bài viết này'></a></td>
				</tr>";
		}
	}
	else echo '<tr><td colspan=7 >No comment</td></tr>';
	echo	"</table>";
	
	echo '<br />';
	echo 'Front-page comment<br />';
	$blog_comments 	= $comment->getComments('Front page');
	echo 	"<table border=1>";
	echo 	'<tr>	<td width=3%>#</td>	<td width=10%>Title</td>	<td width=8%>User</td>
				<td>Comment</td> <td width=4%>Publish</td>	<td width=5%>Edit</td>		<td width=4%>Delete</td></tr>';
	if($blog_comments != FALSE ) {
	 	foreach($blog_comments as $key=>$value)
		{
			if($value['publish'] == 1) $publish = 'Everybody';
				elseif($value['publish'] == 2) $publish = 'Friend';
				else $publish = 'Private';
			echo	"<tr><td>".($key + 1)."</td>";
			echo "<td>".$value['title']."</td><td>".$user_name[$value['user']]."</td>";
			echo "<td><font color=yellow>".$value['comment']."</font></td>";
			echo "</td>
				<td>". $publish."</td>
				<td><a href='".urlmanager::makeLink($PARAS,$ACTION,'edit-'.$value['id'])."'><img border=0 align=right valign=bottom src='"._UURL.IMA."/edit.png' title='Sửa bài viết này'></a></td>
				<td><a href='".urlmanager::makeLink($PARAS,$ACTION,'delete-'.$value['id'])."'><img border=0 align=right valign=bottom src='"._UURL.IMA."/del.png' title='Xóa bài viết này'></a></td>
				</tr>";
		}
	}
	else echo '<tr><td colspan=7 >No comment</td></tr>';
	echo	"</table>";
}

?>