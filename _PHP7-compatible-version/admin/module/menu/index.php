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
 * This module manage Menu for our website
 * Get, set, create Menu, such as:
 * 	top menu, left menu ,...
 * This is controller file
 */
//*************Setting**************//
$new_cate = new menu();
global $_URL;
if(!isset($mod_params)) global $mod_params; 
if(!isset($mod_path)) global $mod_path;
$config = explode ( ',', $mod_params);
// Note: order of config array:
// 0 : (Name) Type menu : top menu, left menu ,...
// 1 : Style of menu.
// Default Setting
	if (empty ( $config [0] ))	$config [0] = 'leftmenu';
	if (empty ( $config [1] ))	$config [1] = 'default';
//*************End Setting**************//

// include file : css and js
if(is_file($mod_path.'/menutype/'.$config [1].'/css.css')) echo '<link rel="stylesheet" type="text/css"	href="'._URL.$mod_path.'/menutype/'.$config [1].'/css.css" />';
if(is_file($mod_path.'/menutype/'.$config [1].'/js.js')) echo '<link rel="stylesheet" type="text/css"	href="'._URL.$mod_path.'/menutype/'.$config [1].'/js.js" />';
	?>


		<?php
		$menu_here = $new_cate->listAll($config [0]);
		if(!empty($menu_here)) {
				$display_menu ='';
				foreach ($new_cate->listAll($config [0]) as $key=>$value) {
					$display_menu  .= '<li>'.urlmanager::makeLink($value['link'],'','',$value['alias'],$value['title']).'</li>';
				}
				if(is_file($mod_path.'/menutype/'.$config [1].'/index.htm')) {
					$display_file = file_get_contents($mod_path.'/menutype/'.$config [1].'/index.htm');
					$menu_compare = '<!--display_menu_here_module_hlp-->';
					$display_file = str_replace ($menu_compare,$display_menu,$display_file);
					echo $display_file;
				}
				else {
					echo $display_menu;
				}
			
	    }
		else {
			echo 'No menu here';
		}
		?>
