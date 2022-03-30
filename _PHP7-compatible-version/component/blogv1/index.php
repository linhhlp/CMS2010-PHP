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
 * This module manage Article (blog content) for our website
 * This is controller file
 */
//*************Setting**************//
$blog = new contentmanager();
if(!isset($_URL)) global $_URL;
$url = $_URL->getNewUrl ();
$blogid = $url[1];
list($category_id,$page_current) = explode ( '-', $url[2]);
	// NOTE: URL will have format:
	//    /blog/blog_id/categoryID-PageNumber/...
	//        example:   blog/10/3-0/....
	//		so that, $url[0] :  blog id
if(!isset($com_params)) global $com_params; 
$config = explode ( ',', $com_params);
// Note: order of config array:
// 0 : Number of articles per page
// 1 : Number of letter to be extracted from full content
// Default Setting
	if (empty ( $config [0] ))	$config [0] = 5;
	if (empty ( $config [1] ))	$config [1] = 200;
//*************End Setting**************//

if(empty($page_current)) {
		$page_current = 0;
}
	
if($page_current >1) $page_limit = $page_current -1;
else $page_limit = 0;
$limit_start = $config[0]*$page_limit;
//if($page_start[0]=='blog' && !empty($page_start[1]))    $limit_start = $page_start[1];
list($type_get,$type_get_id) = explode ( '-', $url[1]);
$user_id = user::getUserName('','id');
global $title_meta_data_temp;
if($blogid > 0 && !empty($blogid)) {
	$ablog = $blog->getContent($blogid);
	if(!empty($ablog)) {
	echo "<div class=\"CONTENT_TITLE\">".urlmanager::makeLink($url[0],$ablog[0] ['id'],$url [2],$ablog[0]  ['alias_title'],$ablog[0]  ['title'])." </div> <div class=\"CONTENT_DATE\">(" ;
	if($ablog[0] ['publish'] == '0') echo '<span class=\"CONTENT_SPECIAL\">Cá nhân</span> - ';
		elseif($ablog [0]['publish'] == '2') echo '<span class=\"CONTENT_SPECIAL\">Bạn bè</span> - ';
		elseif($ablog [0]['publish'] == '3') echo '<span class=\"CONTENT_SPECIAL\">Special</span> - ';
		echo   'bởi: '.$user_id[$ablog[0] ['created_by']] . "  lúc  " . $ablog[0] ['created'] . ")</div>";
	$fulltext = player::replacePlayer($ablog[0]  ['fulltext']); // Make player for media content
	$fulltext = picslide::replacePicslide($fulltext); // Make player for media content
	echo "<div class=\"CONTENT_TEXT\">" .$fulltext . "</div>";
	// Plug-in here
	echo "<!--hlp_plugin_end_content-->";
	echo "<div class='CONTENT_HR'></div>";
	echo "<br /><br /><br />";
	}
	else echo "<div class=\"CONTENT_TITLE\">Không có bài viết nào</div>";
	$title_meta_data_temp = $ablog[0]  ['title'];
}
else {
if(!isset($title_meta_data_temp)) $title_meta_data_temp = "Thống kê bài viết";
echo "<h3 class='CONTENT_THONGKE'>Thống kê bài viết:</h3>";
if($category_id <= 0 || empty($category_id))  $category_id = '';
$blogs = $blog->getContent('',$limit_start,$config[0],$category_id);
$num_com  = $blog->getNumContents();
if($category_id > 0) {
	// Get name of the category
	$categoy = new category();
	$categoy_info = $categoy->cate_info($category_id);
	echo "<div class='CONTENT_THONGKE_THEO'>Thống kê theo: ".$categoy_info['title']."</div>";
}
$i = 0;
if(!empty($blogs)) {
	
	foreach ($blogs as $key=>$value) {
		echo "<div class=\"CONTENT_TITLE\">".urlmanager::makeLink($url[0],$value ['id'],$url[2],$value ['alias_title'],$value ['title'])." </div> <div class=\"CONTENT_DATE\">(";
		if($value ['publish'] == '0') echo '<span class=\"CONTENT_SPECIAL\">Cá nhân</span> - ';
		elseif($value ['publish'] == '2') echo '<span class=\"CONTENT_SPECIAL\">Bạn bè</span> - ';
		elseif($value ['publish'] == '3') echo '<span class=\"CONTENT_SPECIAL\">Special</span> - ';
		echo   'bởi: '.$user_id[$value ['created_by']] . "  lúc  " . $value ['created'] . ")</div>";
		if($value ['introtext'] == '') $introtext = wrap_content($value ['fulltext'],0,$config [1]);
		else $introtext = $value ['introtext'];
		echo "<div class=\"CONTENT_TEXT\">" . $introtext . "</div>";
		echo "<div class='CONTENT_HR'></div>";
		$i++;
	}
	// Make page 
		if($category_id <= 0) $category_id = 0;
        echo '<div class="CONTENT_HLP_PAGE_TITLE">Sang trang:<br />';
		$page_array = paging($page_current,$num_com/$config [0]);
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
	echo "<div class=\"CONTENT_TITLE\">Không có bài viết nào</div>";
}
}
        
?>
<div class="clr"></div>