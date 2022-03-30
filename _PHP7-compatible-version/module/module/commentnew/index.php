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
 * Setting:
 * 	 global $mod_params 
 */
if(!isset($mod_params)) global $mod_params;
if(!isset($_URL)) global $_URL;
$url = $_URL->getNewUrl ();
$config = explode ( ',', $mod_params );
// Note: order of config array:
// 0 : Type comment : blog(frontpage), acticle (content) ,...
// 1 : Number of comment per page (main content)
// 2 : Number of character extracted
// 3 : Auto hide or not
if (empty ( $config [0] ))
	$config [0] = 'blog';
if (empty ( $config [1] ))
	$config [1] = 5;
if (empty ( $config [2] ))
	$config [2] = 70;
$limit = $config [1];
$limit_start = $limit*$config[3];
if (empty ( $limit  ))
	$limit = 0;

$comment = new comment ();


// Find comments by type"
$comments = $comment->getComments ( $config [0],$limit_start,$limit );
$num_com  = $comment->getNumCom();
$blog_title=$comment->getBlogTitle();

?>

<br />
<?php
if ( $config [3] == "on") {
	echo "<a href='".$_URL->getOldUrl ()."#Showcomment2' onClick='Showcomment2();' name='Showcomment2'>Hiện thị comment bài viết mới nhất</a>";
	echo "<div id='COMMENT_HLP_SHOWHERE2' >";
}
?>
<div class="box_top"></div>
<h3 class="COMMENT_HLP_h3">Bình luận mới blog</h3>
<div class="box_body">


<div class="COMMENT_HLP">
<br />
Bình luận của mọi người. Có <?php		echo $num_com;		?> bình luận.
<br />
	
<?php

	if($num_com == 0) echo 'Bạn chưa có bình luận nào.';
	elseif($num_com > 0) {
		$i = 0;
		foreach ($comments as $key=>$value) {
			echo "<a href='".urlmanager::makeLink('blog',$value ['belong'],'')."'><span class=\"COMMENT_HLP_TITLE\"> " . $value ['title'] . "</span> </a> 
			<span class=\"COMMENT_HLP_USER\">-".$blog_title[$value['belong']]['title']." (" . $value ['user'] . "  lúc  " . $value ['date'] . ")</span>";
			echo "<div class=\"COMMENT_HLP_COMMENT\">" . wrap_content($value ['comment'],0,$config [2]) . "</div>";
			$i++;
		}
	}
	?>
</div>

</div>
<div class="box_bottom"></div>
<?php
if ( $config [3] == "on") {
	echo "</div>";
}
?>
<script type="text/javascript">
	var showcommenton2 = "off";
	function Showcomment2() {
		if(showcommenton2 == "on") {
			showcommenton2 = "off";
			document.getElementById("COMMENT_HLP_SHOWHERE2").style.visibility="visible";
			document.getElementById("COMMENT_HLP_SHOWHERE2").style.height="100%";
		}
		else {
			document.getElementById("COMMENT_HLP_SHOWHERE2").style.visibility="hidden";
			document.getElementById("COMMENT_HLP_SHOWHERE2").style.height="0";
			showcommenton2 = "on";
		}
	}
	Showcomment2()
</script>