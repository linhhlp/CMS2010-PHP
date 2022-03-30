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

class player {

	static function makePlayer($link,$type,$div='nhac_nen',$width='',$height='',$autostart='false',$setting='') {
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
		$mod_path= LIB.'/player';
		if($setting=='' || $width =='') {
			include($mod_path.'/config.php');
			if($type    == 'audio') {
				$width 	= $config[0];
				$height = $config[1];
				$setting= $config[2];
			}
			elseif($type== 'video') {
				$width  = $config[3];
				$height = $config[4];
				$setting= $config[5];
			}elseif($type== 'playlist') {
				$width 	= $config[6];
				$height = $config[7];
				$setting= $config[9];
				$size  	= $config[8];
				$setting.="&playlistsize=".$size;
			}
		}
		$player = " <script type='text/javascript' src='"._URL.$mod_path."/swfobject.js'></script>	
			<div id='".$div."'>Rất tiếc bạn hok nghe được nhạc nền, hãy thử Refresh  trang web</div>
			  <script type='text/javascript'>
			  var s1 = new SWFObject('"._URL.$mod_path."/player.swf','ply','".$width."','".$height."','9','#');				// width 	height
			  s1.addParam('allowfullscreen','true');
			  s1.addParam('allowscriptaccess','always');
			  s1.addParam('wmode','opaque');
			  s1.addParam('flashvars','file=".$link."&autostart=".$autostart.$setting."');
			  s1.write('".$div."');
			</script>";
		return $player;
	}
	static function replacePlayer ($string,$compare='') {
		while(is_int ( stripos($string,'[VIDEO]') ) || is_int ( stripos($string,'[AUDIO]')) || is_int ( stripos($string,'[PLAYLIST]') )  ) {
			if(is_int ( stripos($string,'[VIDEO]') )) {
				$name_file = substr($string,stripos($string,'[VIDEO]')+7 ,stripos($string,'[/VIDEO]') - stripos($string,'[VIDEO]') - 7);
				$player = player::makePlayer($name_file,'video');
				$compare = substr($string,stripos($string,'[VIDEO]') ,stripos($string,'[/VIDEO]') - stripos($string,'[VIDEO]') + 8);
			}
			elseif(is_int ( stripos($string,'[PLAYLIST]') )) {
				
				$name_file = substr($string,stripos($string,'[PLAYLIST]')+10 ,stripos($string,'[/PLAYLIST]') - stripos($string,'[PLAYLIST]') - 10);
				$player = player::makePlayer($name_file,'playlist');
				$compare = substr($string,stripos($string,'[PLAYLIST]') ,stripos($string,'[/PLAYLIST]') - stripos($string,'[PLAYLIST]') + 11);
			}
			else {
				$name_file = substr($string,stripos($string,'[AUDIO]')+7 ,stripos($string,'[/AUDIO]') - stripos($string,'[AUDIO]') - 7);
				$player = player::makePlayer($name_file,'audio');
				$compare = substr($string,stripos($string,'[AUDIO]') ,stripos($string,'[/AUDIO]') - stripos($string,'[AUDIO]') + 8);//echo $name_file;echo ' __ '.$compare;die;
			}
			$string = str_replace($compare,$player,$string);
		}
		return $string;
	}
	
}

?>