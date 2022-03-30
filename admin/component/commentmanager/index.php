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
 *  		Blog comment (Front page) : Comments for blog.
 *  		Article comment: each content (article)  will have its comments.
 */
if(!isset($com_params)) global $com_params;
//print_r($com_params);
if(!isset($_URL)) global $_URL;
$url = $_URL->getNewUrl ();
$config = explode ( ',', $com_params );
if (empty ( $config [0] ))
	$config [0] = 200;
if (empty ( $config [1] ))
	$config [1] = 15;
global $PARAS,$ACTION,$CONTROL;
list($type,$id) = explode('-',$ACTION);
list($type_page,$page_start) = explode('-',$CONTROL);
	//*********** End stting ******** //

// Caculate the start page and number comment per page.

if($page_start >1) $page_limit = $page_start -1;
else $page_limit = 0;
$limit_start = $config[1]*$page_limit;
?>

<link href="<?php echo _URL.ADM.'/'.COM.'/commentmanager/'?>css.css" rel="stylesheet" type="text/css" />

<?php

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
		echo textEdittor('');
		echo "
			<form action='".urlmanager::makeLink($PARAS,$ACTION,$CONTROL)."' method=POST>
			<table border=0  width=100%>
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
	if($type_page == 'Blog') $limit_blog_start = $limit_start;
	else $limit_blog_start = 0;
	$blog_comments 	= $comment->getComments('blog',$limit_blog_start,$config [1]);
	$blog_title = $comment->getBlogTitle();
	echo 'Blog comment<br />';
	echo 	"<table border=1>";
	echo 	'<tr>	<td width=3%>#</td>	<td width=10%>Title</td>	<td width=8%>User</td>
				<td>Của bài viết </td> <td>Comment</td> 	<td>Publish</td>	<td width=5%>Edit</td>		<td width=4%>Delete</td></tr>';
	if($blog_comments != FALSE ) {
	 	foreach($blog_comments as $key=>$value)
		{
			if(empty($user_name[$value['user']])) $own_comment =  $value['user'];
			else $own_comment =  $user_name[$value['user']];
			if($value['publish'] == 1) $publish = 'Everybody';
				elseif($value['publish'] == 2) $publish = 'Friend';
				else $publish = 'Private';
			echo	"<tr><td>".($key + 1)."</td>";
			echo "<td>".$value['title']."</td><td>".$own_comment."</td>";
			echo "</td>
				<td  width=10%>". $blog_title[$value['belong']]['title']."</td>
				<td width=50%><font color=yellow><div id=com_blogENT_HLP>".$value['comment']."</div></font></td>
				<td>". $publish."</td>
				<td><a href='".urlmanager::makeLink($PARAS,'edit-'.$value['id'],$CONTROL)."'><img border=0 align=right valign=bottom src='"._URL.IMA."/edit.png' title='Sửa bài viết này'></a></td>
				<td><a href='".urlmanager::makeLink($PARAS,'delete-'.$value['id'],$CONTROL)."'><img border=0 align=right valign=bottom src='"._URL.IMA."/del.png' title='Xóa bài viết này'></a></td>
				</tr>";
			
		}
		echo	"</table>";
		echo '<div class="COMMENT_HLP_PAGE_TITLE">Sang trang:<br />';
		$num_com = $comment->getNumCom();
		$page_array = paging($page_start,$num_com/$config [1]);
        foreach($page_array as $key=>$value) {
            if($key =='current') {
                echo '<span class="COMMENT_HLP_PAGE_CURRENT">'.$value.'</span>';
            }
            elseif($value =='...') {
                echo $value;
            }
            else {
                echo urlmanager::makeLink($PARAS,$ACTION,'Blog-'.$value,'comment trang thu '.$value,'<span class="COMMENT_HLP_PAGE">'.$value.'</span>');
            }
        }
        echo '</div>';
	}
	else echo '<tr><td colspan=7 >No comment</td></tr></table>';
	
	echo '<br />Front-page comment<br />';
	if($type_page == 'Frontpage') $limit_Frontpage_start = $limit_start;
	else $limit_Frontpage_start = 0;
	$blog_comments 	= $comment->getComments('Front page',$limit_Frontpage_start,$config [1]);
	echo 	"<table border=1 width=100%>";
	echo 	'<tr>	<td width=3%>#</td>	<td width=10%>Title</td>	<td width=8%>User</td>
				<td>Comment</td> <td width=4%>Publish</td>	<td width=5%>Edit</td>		<td width=4%>Delete</td></tr>';
	if($blog_comments != FALSE ) {
	 	foreach($blog_comments as $key=>$value)
		{
			if(empty($user_name[$value['user']])) $own_comment =  $value['user'];
			else $own_comment =  $user_name[$value['user']];
			if($value['publish'] == 1) $publish = 'Everybody';
				elseif($value['publish'] == 2) $publish = 'Friend';
				else $publish = 'Private';
			echo	"<tr><td>".($key + 1)."</td>";
			echo "<td>".$value['title']."</td><td>".$own_comment."</td>";
			echo "<td><font color=yellow>".$value['comment']."</font></td>";
			echo "</td>
				<td>". $publish."</td>
				<td><a href='".urlmanager::makeLink($PARAS,'edit-'.$value['id'],$CONTROL)."'><img border=0 align=right valign=bottom src='"._URL.IMA."/edit.png' title='Sửa bài viết này'></a></td>
				<td><a href='".urlmanager::makeLink($PARAS,'delete-'.$value['id'],$CONTROL)."'><img border=0 align=right valign=bottom src='"._URL.IMA."/del.png' title='Xóa bài viết này'></a></td>
				</tr>";
		}
		echo	"</table>";
		//Sang trang  : Paging
		echo '<div class="COMMENT_HLP_PAGE_TITLE">Sang trang:<br />';
		$num_com = $comment->getNumCom();
		$page_array = paging($page_start,$num_com/$config [1]);
        foreach($page_array as $key=>$value) {
            if($key =='current') {
                echo '<span class="COMMENT_HLP_PAGE_CURRENT">'.$value.'</span>';
            }
            elseif($value =='...') {
                echo $value;
            }
            else {
                echo urlmanager::makeLink($PARAS,$ACTION,'Frontpage-'.$value,'comment trang thu '.$value,'<span class="COMMENT_HLP_PAGE">'.$value.'</span>');
            }
        }
        echo '</div>';
	}
	else echo '<tr><td colspan=7 >No comment</td></tr></table>';
	
}

?>