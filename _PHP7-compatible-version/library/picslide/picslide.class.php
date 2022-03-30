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

class picslide {

	static function makePicslide($link,$type,$width='',$height='',$setting='') {
	/*
		 * link: link of songs or playlist
		 * type: music,video,playlist
		 * width/height: of player.
		 * autostart: auto play or not
		 */
		// check link, convert to format : http://abc.com/music.mp3
		if( substr($link,0,7) != 'http://') {
			$link =  'http://'.$_SERVER['SERVER_NAME'].str_replace('/index.php','',$_SERVER['SCRIPT_NAME']).'/'.$link;
		}
	//Include setting:
		$mod_path= LIB.'/picslide';
		if($setting=='' || $width =='') {
			include($mod_path.'/config.php');
				$width 	= $config[0];
				$height = $config[1];
		}
		if($type == 'FLASH') {
			$player = "
						<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0' width='500' height='450'>
						  <param name='movie' value='".$link."' />
						  <param name='quality' value='high' />
						  <param name='allowScriptAccess' value='always' value='sameDomain'/>
						  <param name='wmode' value='transparent'>
						     <embed src='".$link."'
						      quality='high'
						      type='application/x-shockwave-flash'
						      WMODE='transparent'
						width='".$width."' height='".$height."'
						      pluginspage='http://www.macromedia.com/go/getflashplayer'
						      allowScriptAccess='always' />
						</object>
							";
		}
		elseif($type == 'PIC') {
			$player = "<img src='".$link."' alt=''>";
		}
		return $player;
	}
	static function replacePicslide ($string,$compare='') {
		while(is_int ( stripos($string,'[FLASH]') ) || is_int ( stripos($string,'[PIC]'))   ) {
			if(is_int ( stripos($string,'[FLASH]') )) {
				$name_file = substr($string,stripos($string,'[FLASH]')+7 ,stripos($string,'[/FLASH]') - stripos($string,'[FLASH]') - 7);
				$player = picslide::makePicslide($name_file,'FLASH');
				$compare = substr($string,stripos($string,'[FLASH]') ,stripos($string,'[/FLASH]') - stripos($string,'[FLASH]') + 8);
			}
			elseif(is_int ( stripos($string,'[PIC]') )) {
				
				$name_file = substr($string,stripos($string,'[PIC]')+ 5 ,stripos($string,'[/PIC]') - stripos($string,'[PIC]') - 5);
				$player = picslide::makePicslide($name_file,'PIC');
				$compare = substr($string,stripos($string,'[PIC]') ,stripos($string,'[/PIC]') - stripos($string,'[PIC]') + 6);
			}
			
			$string = str_replace($compare,$player,$string);
		}
		return $string;
	}
	
}

?>