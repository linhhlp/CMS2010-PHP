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
if($url[0] == 'comment') return;
$config = explode ( ',', $mod_params );
// Note: order of config array:
// 0 : category : 
// 1 : Number of files per page (main content)
// 2 : Title
// 3 : Order by
if (empty ( $config [0] ) || $config [0] == '0' )	$config [0] = "";
if (empty ( $config [1] ))	$config [1] = 3;
if (empty ( $config [2] ))	$config [2] = "Download nhiều nhất:";
if (empty ( $config [3] ))	$config [3] = "download";
echo "<h3>".$config[2]."</h3>";
$databasefile =  new databasefile;
$files = $databasefile->getFiles('',$config [3],'DESC',0,$config[1],'',$config[0]);
foreach ($files as $key=>$value) {
	echo "<div class='filemd_box'>";
	echo "<a href='".urlmanager::makeLink("databasefile", "open", $value['id'])."' >";
	echo "<img src='files.php?databasefile=".$value['id'].':'."image' alt='".$value['title']."'>";
	echo "<br />".$value['title'];
	echo "<br />Download: ".$value['download'];
	echo "</a></div><br />";
}


?>
<style>
.filemd_box {
width:150px;
boder-bottom: 1px solid;
}
.filemd_box img {
width:130px;
}
</style>