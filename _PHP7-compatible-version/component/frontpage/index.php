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
 * This module shows your front-page
 */
global $title_meta_data_temp;
$title_meta_data_temp = "Trang chủ";

$frontpage = new frontpage();
$frontpage_re = $frontpage->getResults();
if(!empty($frontpage_re)) {
	foreach ($frontpage_re as $hienthi_pront) {
		echo	"<h3>".$hienthi_pront['title']."	</h3>";
		echo	"<br />".$hienthi_pront['link'];
		echo	"<br />".$hienthi_pront['descr']."	<br />";
	}
}
else {
	echo 'welcome!';
}
?>	
	