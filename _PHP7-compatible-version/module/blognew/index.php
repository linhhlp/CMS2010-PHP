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
// 0 : Type content : blog(frontpage), acticle (content) ,...
// 1 : Number of content per page (main content)
// 2 : Number of letters extract
// 3 : Auto hide content.
if (empty ( $config [0] )) $config [0] = '0';
if (empty ( $config [1] )) $config [1] = 5;
if (empty ( $config [2] )) $config [2] = 70;
$limit = 0;

$blog = new contentmanager();
if( $config [0] == '0')  $config [0] = '';
$blogs   = $blog->getContent('',$limit,$config[1],$config [0]);
$num_com = $blog->getNumContents();

?>

<div class="box_top"></div>
<br />
<div class="box_body">

<?php
if ( $config [3] == "on") {
	echo "<span href='".$_URL->getOldUrl ()."#Showcontent3' onClick='Showcontent3();' name='Showcontent3' style='color:blue;text-decoration:underline;cursor: hand; cursor: pointer; '>Hiện thị các bài viết mới</span>";
	echo "<div id='CONTENT_HLP_SHOWHERE3' >";
}
	echo '<h3 class="content_HLP_h3">Bài viết mới</h3>';
	if($num_com == 0) echo 'Bạn chưa có bình luận nào.';
	$i = 0;
	if(!empty($blogs)) {
		foreach ($blogs as $key=>$value) {
			echo "<div class=\"CONTENT_TITLE\">".urlmanager::makeLink('blog',$value ['id'],$url[2],$value ['alias_title'],$value ['title'])." </div> <div class=\"CONTENT_DATE\">(";
			if($value ['publish'] == '0') echo '<span class=\"CONTENT_SPECIAL\">Cá nhân</span> - ';
			elseif($value ['publish'] == '2') echo '<span class=\"CONTENT_SPECIAL\">Bạn bè</span> - ';
			elseif($value ['publish'] == '3') echo '<span class=\"CONTENT_SPECIAL\">Special</span> - ';
			echo   'bởi: '.$user_id[$value ['created_by']] . "  lúc  " . time2TiengViet($value ['created']) . ")</div>";
			if($value ['introtext'] == '') $introtext = wrap_content($value ['fulltext'],0,$config [2]);
			else $introtext = $value ['introtext'];
			echo "<div class=\"CONTENT_TEXT\">" . $introtext . "</div>";
			echo "<div class='CONTENT_HR'></div>";
			$i++;
		}
	}
	else echo 'Bạn chưa có bài viết nào.';
	
if ( $config [3] == "on") {
	echo "</div>";
}
?>
</div>

<div class="box_bottom"></div>
<script type="text/javascript">
	function check(form) {
		with(form){
		if (title.value=="" ||title.value=="Tiêu đề") {
			alert("Xin đặt tiêu đề!");
			title.focus();
			return false;
		}
		return true;
		}
	}
	var showcontenton3 = "off";
	function Showcontent3() {
		if(showcontenton3 == "on") {
			showcontenton3 = "off";
			document.getElementById("CONTENT_HLP_SHOWHERE3").style.visibility="visible";
			document.getElementById("CONTENT_HLP_SHOWHERE3").style.height="100%";
		}
		else {
			document.getElementById("CONTENT_HLP_SHOWHERE3").style.visibility="hidden";
			document.getElementById("CONTENT_HLP_SHOWHERE3").style.height="0";
			showcontenton3 = "on";
		}
	}
	Showcontent3();
</script>
