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
if(!isset($com_path)) global $com_path; 
$config = explode ( ',', $com_params);
if (empty ( $config [0] ))
	$config [0] = 200;
if (empty ( $config [1] ))
	$config [1] = 20;
global $title_meta_data_temp;

$userbonus	= new userbonus();
?>

<?php 

echo	"Quản lí cộng đồng<br>
<a href=".urlmanager::makeLink('userbonus','','')."><img src="._URL.IMA."/new.png title='Tin nhắn'>Tin nhắn</a>
<a href=".urlmanager::makeLink('communitymakefriend','','')."><img src="._URL.IMA."/shakehand.jpg title='Kết bạn'>Bạn bè và kết bạn</a>
<br><br>";

if(is_file($com_path.'/'.$url[1].'.php') ) include_once($com_path.'/'.$url[1].'.php');

?>
<br />