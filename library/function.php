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

function wrap_content($content, $i, $length, $total ='',$maxline=5) {
	/**
	 * $content  : String need to be cut.
	 * $i 		 : Start point
	 * $lenth	 : Length of extract
	 */
	if($total == '' ) $total = strlen($content);
	$count = 0; // count times enter new line.	--		--		Only display 5 lines even if a lot of words.
	
	/*  old code : very slow
	$n = $i;
	while ( ($n <= ($i + $length)) && ($count < $maxline) ) {
		if (substr ( $content, $n, 4 ) == "<br>") {
			$count ++;
			$j = $n + 4;
		}
		if (substr ( $content, $n, 6 ) == "<br />") {
			$count ++;
			$j = $n + 6;
		}
		if (substr ( $content, $n, 4 ) == "</p>") {
			$count ++;
			$j = $n + 4;
		}
		$n ++;
	}
	*/
	// new code check line
	$content2 = substr ($content,$i);
	if(substr_count($content2, '<br>') >$maxline) {
		for($count =0;$count <$maxline;$count++) {
			$j = $j + strpos($content2,  '<br>')+4;
			$content2 = substr ($content,$j);
		}
	}
	if(substr_count($content2, '<br />') >$maxline) {
		for($count =0;$count <$maxline;$count++) {
			$j = $j + strpos($content2, '<br />')+6;
			$content2 = substr ($content,$j);
		}
	}
	if(substr_count($content2, '</p>') >$maxline) {
		for($count =0;$count <$maxline;$count++) {
			$j = $j + strpos($content2, '</p>')+4;
			$content2 = substr ($content,$j);
		}
	}
	$j = $j + $i;	
	// END new code check line
	if ($count == $maxline) //	Only display 3 lines even if a lot of words.
		$return_string = substr ( $content, $i, $j - $i );
	else {
		if ($total <= $length) {
			
			$return_string =  $content;
		} else {
			// Find exactly position, until not cut tags < >
			$j = $i + $length;
			while ( ($j > $i) && ($content [$j] != " ") && ($content [$j] != ".") ) {
				$j --;
			}
			$p = $j;
			while ( ($p > $i) && ($content [$p] != "<") ) {
				$p --;
			}
			if ($p != $i) {
				$j = $p;
				while ( ($j < $total) && ($content [$j] != ">") ) {
					$j ++;
				}
			}
			$return_string =  substr ( $content, $i, $j - $i +1 );
		}
	}
	// fillter data : close all opened tags in extract!
	$l =  strlen($return_string);
	$last = $l;
	//test
	while($l > 0 && $kiemtra <100) {
		// prevent function from loop-ing endless, variable $kiemtra allow max loop-around is 100 times
		$l  = strripos(substr ( $return_string,0,$l),"<");	// find open of a tag
		$ll = strripos(substr ( $return_string,0,$l),">");	// find close of a tag
		if(is_int($l)) {
			if(($return_string[$l + 1] != "/") && ($return_string[$ll - 1] != "/")) {
				$last_end_gt = stripos(substr ( $return_string,$l) ,">");
				$last_end_sp = stripos(substr ( $return_string,$l) ," ");
				if( ($last_end_sp < $last_end_gt) && is_int($last_end_sp)) $last_end = $last_end_sp;
				else $last_end = $last_end_gt;
				$compare_tag = substr ( $return_string,$l + 1, $last_end -1);
				$appear_open = 1 + substr_count( substr ( $return_string,$l + $last_end), '<'.$compare_tag.">"); 
				$appear_open = $appear_open  + substr_count(substr ( $return_string,$l + $last_end),'<'.$compare_tag." "); 
				$appear_close= substr_count(substr ( $return_string,$l + $last_end), '</'.$compare_tag.">");
				$appear_close= $appear_close  + substr_count(substr ( $return_string,$l + $last_end),'</'.$compare_tag." "); 
				while($appear_close < $appear_open) {
					$return_string = $return_string."</".$compare_tag.">";
					$appear_close++;
				}
				
			}
			$l--;
		}
		$kiemtra++;
	}
	if($return_string != $content) $return_string .= "<font size=\"2\" color=\"red\"> -- còn nữa...</font><br />";
	
	return $return_string;
	// 
}

function filter_str($str) {
	/**
	 * This function will help you correct string. 
	 * It will be useful if you create alias for friendly link.
	 */
	$str = preg_replace ( "/(Ã |Ã¡|áº¡|áº£|Ã£|Ã¢|áº§|áº¥|áº­|áº©|áº«|Äƒ|áº±|áº¯|áº·|áº³|áºµ)/", 'a', $str );
	$str = preg_replace ( "/(Ã€|Ã�|áº |áº¢|Ãƒ|Ã‚|áº¦|áº¤|áº¬|áº¨|áºª|Ä‚|áº°|áº®|áº¶|áº²|áº´)/", 'A', $str );
	$str = preg_replace ( "/(Ã¨|Ã©|áº¹|áº»|áº½|Ãª|á»�|áº¿|á»‡|á»ƒ|á»…)/", 'e', $str );
	$str = preg_replace ( "/(Ãˆ|Ã‰|áº¸|áºº|áº¼|ÃŠ|á»€|áº¾|á»†|á»‚|á»„)/", 'E', $str );
	$str = preg_replace ( "/(Ä‘)/", 'd', $str );
	$str = preg_replace ( "/(Ä�)/", 'D', $str );
	$str = preg_replace ( "/(Ã¬|Ã­|á»‹|á»‰|Ä©)/", 'i', $str );
	$str = preg_replace ( "/(ÃŒ|Ã�|á»Š|á»ˆ|Ä¨)/", 'I', $str );
	$str = preg_replace ( "/(Ã²|Ã³|á»�|á»�|Ãµ|Ã´|á»“|á»‘|á»™|á»•|á»—|Æ¡|á»�|á»›|á»£|á»Ÿ|á»¡)/", 'o', $str );
	$str = preg_replace ( "/(Ã’|Ã“|á»Œ|á»Ž|Ã•|Ã”|á»’|á»�|á»˜|á»”|á»–|Æ |á»œ|á»š|á»¢|á»ž|á» )/", 'O', $str );
	$str = preg_replace ( "/(Ã¹|Ãº|á»¥|á»§|Å©|Æ°|á»«|á»©|á»±|á»­|á»¯)/", 'u', $str );
	$str = preg_replace ( "/(Ã™|Ãš|á»¤|á»¦|Å¨|Æ¯|á»ª|á»¨|á»°|á»¬|á»®)/", 'U', $str );
	$str = preg_replace ( "/(á»³|Ã½|á»µ|á»·|á»¹)/", 'y', $str );
	$str = preg_replace ( "/(á»²|Ã�|á»´|á»¶|á»¸)/", 'Y', $str );
	$str = str_replace ( " ", "-", $str );
	return $str;
}
global $_timeloadfile; $_timeloadfile = "";
function __autoload($object) {
	/**
	 * Use for load class file automically
	 */
	 global $_timeloadfile;
	 $start_time = microtime(true);
	$object = strtolower ( $object );
    if (is_file ( LIB . '/' . $object . '.class.php' )) {
		include (LIB . '/' . $object . '.class.php');
		//echo 'Include file: '.LIB.'/'.$object.'.class.php';
	} elseif (is_file ( LIB . '/' . $object . '.abstract.php' )) {
		include_once (LIB . '/' . $object . '.abstract.php');
		//echo 'Include file: '.LIB.'/'.$object.'.class.php';
	} elseif (is_file ( LIB . '/' . $object . '.interface.php' )) {
		include_once (LIB . '/' . $object . '.interface.php');
		//echo 'Include file: '.LIB.'/'.$object.'.class.php';
	} // find parent directory
	else {
		echo 'Can not find object file: ' . $object;
	}
	$end_time = microtime(true); 
	$_timeloadfile .= "Time loading for: <strong>".$object."</strong> need: ".($end_time - $start_time)."<br />";
}

function check_type($file_type, $file_name, $type_allow = "") {
	/**
	 * This function help you check file upload.
	 * You can extent these allow types.
	 * 
	 */
	$file_name = substr ( $file_name, strrpos ( $file_name, "." ) + 1 );
	if ($type_allow == "") {
		$type_allow = array ('jpeg' => 'image/jpeg:jpeg', 'jpg' => 'image/jpg:jpg', 'gif' => 'image/gif:gif', 'png' => 'image/png:png', 'bmp' => 'image/bmp:bmp', 'doc' => 'application/msword:doc', 'mp3' => 'audio/mpeg:mp3', 'mpg' => 'video/mpeg:mpg', 'wmv' => 'video/x-ms-wmv:wmv', 'mpeg' => 'video/mpeg:mpeg', 'flv' => 'video/flv:flv', 'rar' => 'application/x-rar-compressed:rar', 'pdf' => 'application/pdf:pdf', 'xml' => 'text/xml:xml', 'zip' => 'application/x-zip-compressed:zip', 'zip1' => 'application/zip:zip' );
	} elseif ($type_allow == "hinh_anh") {
		$type_allow = array ('jpeg' => 'image/jpeg:jpeg', 'jpg' => 'image/jpg:jpg', 'gif' => 'image/gif:gif', 'png' => 'image/png:png', 'bmp' => 'image/bmp:bmp' )

		;
	} elseif ($type_allow == "media") {
		$type_allow = array ('mp3' => 'audio/mpeg:mp3', 'flv' => 'video/flv:flv' )

		;
	}
	/*      
	elseif ($type_allow == "XXX") {
		$type_allow = array(
									'XX'=>'XXXo/XXX:XXX',
					example:		'flv'=>'video/flv:flv',
		
							);
	}
	 */
	$true = 0;
	foreach ( $type_allow as $key => $image_type ) {
		$type = explode ( ":", $image_type );
		if (($type [0] == $file_type) && ($file_name == $type [1])) {
			$true ++;
		}
	}
	if ($true == 1) {
		return TRUE;
	} else {
		return FALSE;
	}

}

/**
 * 
 * Create text area with editor feature support.
 * @param string $mode 		:all area or exact textarea
 * @param string $element	:name of the exact textarea
 * @param string $theme		:simple or advanced mode (simple or full feature)
 * @return string
 */
function textEdittor($mode='',$element='',$theme='') {
	$url = _URL . LIB;
	if($mode == '') 	$mode	= 'textareas';
	if($mode == 'exact')$mode	.= '",elements : "'.$element.''; 
	if($theme == '') 	$theme	= 'simple';
	if($theme	== 'simple') {
		$tinyMCE = '<!-- TinyMCE -->
				<script type="text/javascript" src="' . $url . '/tiny_mce/jscripts/tiny_mce/tiny_mce.js"></script>
				<script type="text/javascript">
					tinyMCE.init({
						mode : "' . $mode . '",
						theme : "' . $theme . '"
					});
				</script>
				<!-- /TinyMCE -->
				';
	}
	else {
		$tinyMCE = '
				<script type="text/javascript" src="'.$url.'/tiny_mce/jscripts/tiny_mce/tiny_mce.js"></script>
				<script type="text/javascript">
					tinyMCE.init({
						// General options
						mode : "' . $mode . '",
						theme : "' . $theme . '",
						skin : "o2k7",
						plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
						document_base_url : "'._URL.'",
						// Theme options
						theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
						theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
						theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
						theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
						theme_advanced_toolbar_location : "top",
						theme_advanced_toolbar_align : "left",
						theme_advanced_statusbar_location : "bottom",
						theme_advanced_resizing : true,
				
						// Example content CSS (should be your site CSS)
						content_css : "css/content.css",
				
						// Drop lists for link/image/media/template dialogs
						template_external_list_url : "lists/template_list.js",
						external_link_list_url : "lists/link_list.js",
						external_image_list_url : "lists/image_list.js",
						media_external_list_url : "lists/media_list.js",
				
						// Replace values for the template plugin
						template_replace_values : {
							username : "Some User",
							staffid : "991234"
						}
					});
				</script>';
	}
	return $tinyMCE;
}

/**
 * 
 * Direct to a URL using javascript code...
 * @param $url 		: address
 * @param $timeout	: after time
 */
function driect_url($url,$timeout=1) {
	// Using JavaScript
	echo "<script type='text/javascript'>
					<!--
					function exec_refresh()
					{
						window.status = 'Đang chuyển tới  ...' + myvar;
						myvar = myvar + ' .';
						var timerID = setTimeout('exec_refresh();', 100);
						if (timeout > 0)
						{
							timeout -= ".$timeout.";
						}
						else
						{
							clearTimeout(timerID);
							window.status = '';
							window.location = '" .$url . "';
						}
					}
					
					var myvar = '';
					var timeout = 20;
					exec_refresh();
					//-->
				</script>
				";
}

/**
 * 
 * Direct to a URL using META tag
 * @param $url		: address
 * @param $message	: the notice before directing 
 * @param $delay	: time before  directing
 */
function redirect($url, $message="", $delay=1) { 
	// Using meta tags 
	echo "<meta http-equiv='refresh' content='$delay;URL=$url'>"; 
	if (!empty($message)) echo "<div style='font-size:14px;' align=center>$message</div>"; 
	exit; 
} 

/**
 * 
 * This function to log information (such as error code) in log.php file
 * but it still rarely is used. You can use orther one more useful is myexception class
 */
function log_inf() {
	$site_name = $_SERVER ['HTTP_HOST'];
	$url_dir = "http://" . $_SERVER ['HTTP_HOST'] . dirname ( $_SERVER ['PHP_SELF'] );
	$url_this = "http://" . $_SERVER ['HTTP_HOST'] . $_SERVER ['PHP_SELF'];
	$upload_dir = "files/";
	$upload_url = $url_dir . "/files/";
	$message = "";
	if (! is_dir ( "files" )) {
		if (! mkdir ( $upload_dir ))
			die ( "upload_files directory doesn't exist and creation failed" );
		if (! chmod ( $upload_dir, 0755 ))
			die ( "change permission to 755 failed." );
	}
	$resource = fopen ( "log.txt", "a" );
	fwrite ( $resource, date ( "Ymd h:i:s" ) . "DELETE - $_SERVER[REMOTE_ADDR]" . "$_REQUEST[del]\n" );
	fclose ( $resource );

}
/**
 * 
 * Calculate number pages for paging ...
 * @param number $current	: number of current page
 * @param number $total		: number of total pages
 * return : This function returns array contain : number (the order of pages) and string "..." to separate. and the key of present page is "current"
 */
function paging($current, $total, $config = "") {
	// This function returns array contain : number (the order of pages) and string "..." to separate. and the key of present page is "current"
    // Exmaple:    1...5.6.7.8.9...100
    // Key of page   ['current'] = 7
    $result = array();
    //check inputs:
    $current = ceil($current);
    if($current <= 0) $current = 1;
    $total   = ceil($total);
    //echo $total;
    if($total <=0 ) return FALSE;
    if($total <= 7) {
        for($i =1;$i <= $total;$i++) {
            if($current == $i) {
                $result['current'] = $current;
            }
            else {
                $result[$i] = $i;
            }
        }
    }
    else {
        switch($current) {
            // Header cases:
            case 1:
                $result['current'] = 1;
                $result[1] = 2;
                $result[2] = 3;
                $result[3] = 4;
                $result[4] = '...';
                $result[5] = $total; break;
            case 2:
                $result[1] = 1;
                $result['current'] = 2;
                $result[2] = 3;
                $result[3] = 4;
                $result[4] = '...';
                $result[5] = $total; break;
            case 3:
                $result[1] = 1;
                $result[2] = 2;
                $result['current'] = 3;
                $result[3] = 4;
                $result[4] = 5;
                $result[5] = '...';
                $result[6] = $total; break;
            case 4:
                $result[1] = 1;
                $result[2] = 2;
                $result[3] = 3;
                $result['current'] = 4;
                $result[4] = 5;
                $result[5] = 6;
                $result[6] = '...';
                $result[7] = $total; break;
             // Ending cases 
            case $total:
                $result[1] = 1;
                $result[2] = '...';
                $result[3] = $total -2;
                $result[4] = $total -1;
                $result['current'] = $total; break;
            case $total -1:
                $result[1] = 1;
                $result[2] = '...';
                $result[3] = $total -3;
                $result[4] = $total -2;
                $result['current'] = $total -1;
                $result[5] = $total; break;
            case $total -2:
                $result[1] = 1;
                $result[2] = '...';
                $result[3] = $total -4;
                $result[4] = $total -3;
                $result['current'] = $total -2;
                $result[5] = $total -1;
                $result[6] = $total; break;
            case $total - 3:
                $result[1] = 1;
                $result[2] = '...';
                $result[3] = $total -5;
                $result[4] = $total -4;
                $result['current'] = $total -3;
                $result[5] = $total -2;
                $result[6] = $total -1;
                $result[7] = $total; break;
            // Genaral cases:
            default:
                $result[1] = 1;
                $result[2] = '...';
                $result[3] = $current -2;
                $result[4] = $current -1;
                $result['current'] = $current;
                $result[5] = $current +1;
                $result[6] = $current +2;
                $result[7] = '...';
                $result[8] = $total;
            break;
        }
    }
    return $result;
}

/**
 * 
 * This function help to remove Escape "\" in a string
 * Specially, It removes data in POST and GET when magic_quotes_gpc con not bet set to 0
 * @param $string
 */
function removeEscape($string = '') {
	if(!empty($string) ) {
		return stripslashes($string);
	}
	else {
		// Use special function : remove in POST and GET data
		$_GET = removeEscapeLoop($_GET);
		$_POST = removeEscapeLoop($_POST);
	}

}
// This is the loop of upper function ( removeEscape)
function removeEscapeLoop($array) {
	foreach($array as $key=>$value) {
		if(!is_array($value)) {
			$array[$key] = stripslashes($value);
			//echo $array[$key];
		}
		else {
			removeEscapeLoop($value);
		}
	}
	return $array;
}


/*

  This code is from http://detectmobilebrowsers.mobi/ - please do not republish it without due credit and hyperlink to http://detectmobilebrowsers.mobi/ really, i'd prefer it if it wasn't republished in full as that way it's main source is it's homepage and it's always kept up to date
  For Usage, please read the source at help file

*/
function mobile_device_detect($iphone=true,$ipad=true,$android=true,$opera=true,$blackberry=true,$palm=true,$windows=true,$mobileredirect=false,$desktopredirect=false){

  $mobile_browser   = false; 
  $user_agent       = $_SERVER['HTTP_USER_AGENT'];
  $accept           = $_SERVER['HTTP_ACCEPT'];

  switch(true){

    case (preg_match('/ipad/i',$user_agent)); 
      $mobile_browser = $ipad;
      $status = 'Apple iPad';
      if(substr($ipad,0,4)=='http'){ 
        $mobileredirect = $ipad;
      } 
    break; 

    case (preg_match('/ipod/i',$user_agent)||preg_match('/iphone/i',$user_agent)); 
      $mobile_browser = $iphone;
      $status = 'Apple';
      if(substr($iphone,0,4)=='http'){
        $mobileredirect = $iphone;
      }
    break; 

    case (preg_match('/android/i',$user_agent));   
      $mobile_browser = $android; 
      $status = 'Android';
      if(substr($android,0,4)=='http'){ 
        $mobileredirect = $android;  
      } 
    break;  

    case (preg_match('/opera mini/i',$user_agent));  
      $mobile_browser = $opera; 
      $status = 'Opera';
      if(substr($opera,0,4)=='http'){  
        $mobileredirect = $opera; 
      } 
    break; 

    case (preg_match('/blackberry/i',$user_agent)); 
      $mobile_browser = $blackberry; 
      $status = 'Blackberry';
      if(substr($blackberry,0,4)=='http'){ 
        $mobileredirect = $blackberry; 
      }   
    break;  

    case (preg_match('/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i',$user_agent));  
      $mobile_browser = $palm; 
      $status = 'Palm';
      if(substr($palm,0,4)=='http'){ 
        $mobileredirect = $palm;  
      }  
    break;  

    case (preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i',$user_agent)); 
      $mobile_browser = $windows; 
      $status = 'Windows Smartphone';
      if(substr($windows,0,4)=='http'){ 
        $mobileredirect = $windows; 
      }  
    break;  

    case (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i',$user_agent)); 
      $mobile_browser = true; 
      $status = 'Mobile matched on piped preg_match';
    break; 

    case ((strpos($accept,'text/vnd.wap.wml')>0)||(strpos($accept,'application/vnd.wap.xhtml+xml')>0)); 
      $mobile_browser = true;  
      $status = 'Mobile matched on content accept header';
    break;  

    case (isset($_SERVER['HTTP_X_WAP_PROFILE'])||isset($_SERVER['HTTP_PROFILE'])); 
      $mobile_browser = true; 
      $status = 'Mobile matched on profile headers being set';
    break; 

    case (in_array(strtolower(substr($user_agent,0,4)),array('1207'=>'1207','3gso'=>'3gso','4thp'=>'4thp','501i'=>'501i','502i'=>'502i','503i'=>'503i','504i'=>'504i','505i'=>'505i','506i'=>'506i','6310'=>'6310','6590'=>'6590','770s'=>'770s','802s'=>'802s','a wa'=>'a wa','acer'=>'acer','acs-'=>'acs-','airn'=>'airn','alav'=>'alav','asus'=>'asus','attw'=>'attw','au-m'=>'au-m','aur '=>'aur ','aus '=>'aus ','abac'=>'abac','acoo'=>'acoo','aiko'=>'aiko','alco'=>'alco','alca'=>'alca','amoi'=>'amoi','anex'=>'anex','anny'=>'anny','anyw'=>'anyw','aptu'=>'aptu','arch'=>'arch','argo'=>'argo','bell'=>'bell','bird'=>'bird','bw-n'=>'bw-n','bw-u'=>'bw-u','beck'=>'beck','benq'=>'benq','bilb'=>'bilb','blac'=>'blac','c55/'=>'c55/','cdm-'=>'cdm-','chtm'=>'chtm','capi'=>'capi','cond'=>'cond','craw'=>'craw','dall'=>'dall','dbte'=>'dbte','dc-s'=>'dc-s','dica'=>'dica','ds-d'=>'ds-d','ds12'=>'ds12','dait'=>'dait','devi'=>'devi','dmob'=>'dmob','doco'=>'doco','dopo'=>'dopo','el49'=>'el49','erk0'=>'erk0','esl8'=>'esl8','ez40'=>'ez40','ez60'=>'ez60','ez70'=>'ez70','ezos'=>'ezos','ezze'=>'ezze','elai'=>'elai','emul'=>'emul','eric'=>'eric','ezwa'=>'ezwa','fake'=>'fake','fly-'=>'fly-','fly_'=>'fly_','g-mo'=>'g-mo','g1 u'=>'g1 u','g560'=>'g560','gf-5'=>'gf-5','grun'=>'grun','gene'=>'gene','go.w'=>'go.w','good'=>'good','grad'=>'grad','hcit'=>'hcit','hd-m'=>'hd-m','hd-p'=>'hd-p','hd-t'=>'hd-t','hei-'=>'hei-','hp i'=>'hp i','hpip'=>'hpip','hs-c'=>'hs-c','htc '=>'htc ','htc-'=>'htc-','htca'=>'htca','htcg'=>'htcg','htcp'=>'htcp','htcs'=>'htcs','htct'=>'htct','htc_'=>'htc_','haie'=>'haie','hita'=>'hita','huaw'=>'huaw','hutc'=>'hutc','i-20'=>'i-20','i-go'=>'i-go','i-ma'=>'i-ma','i230'=>'i230','iac'=>'iac','iac-'=>'iac-','iac/'=>'iac/','ig01'=>'ig01','im1k'=>'im1k','inno'=>'inno','iris'=>'iris','jata'=>'jata','java'=>'java','kddi'=>'kddi','kgt'=>'kgt','kgt/'=>'kgt/','kpt '=>'kpt ','kwc-'=>'kwc-','klon'=>'klon','lexi'=>'lexi','lg g'=>'lg g','lg-a'=>'lg-a','lg-b'=>'lg-b','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-f'=>'lg-f','lg-g'=>'lg-g','lg-k'=>'lg-k','lg-l'=>'lg-l','lg-m'=>'lg-m','lg-o'=>'lg-o','lg-p'=>'lg-p','lg-s'=>'lg-s','lg-t'=>'lg-t','lg-u'=>'lg-u','lg-w'=>'lg-w','lg/k'=>'lg/k','lg/l'=>'lg/l','lg/u'=>'lg/u','lg50'=>'lg50','lg54'=>'lg54','lge-'=>'lge-','lge/'=>'lge/','lynx'=>'lynx','leno'=>'leno','m1-w'=>'m1-w','m3ga'=>'m3ga','m50/'=>'m50/','maui'=>'maui','mc01'=>'mc01','mc21'=>'mc21','mcca'=>'mcca','medi'=>'medi','meri'=>'meri','mio8'=>'mio8','mioa'=>'mioa','mo01'=>'mo01','mo02'=>'mo02','mode'=>'mode','modo'=>'modo','mot '=>'mot ','mot-'=>'mot-','mt50'=>'mt50','mtp1'=>'mtp1','mtv '=>'mtv ','mate'=>'mate','maxo'=>'maxo','merc'=>'merc','mits'=>'mits','mobi'=>'mobi','motv'=>'motv','mozz'=>'mozz','n100'=>'n100','n101'=>'n101','n102'=>'n102','n202'=>'n202','n203'=>'n203','n300'=>'n300','n302'=>'n302','n500'=>'n500','n502'=>'n502','n505'=>'n505','n700'=>'n700','n701'=>'n701','n710'=>'n710','nec-'=>'nec-','nem-'=>'nem-','newg'=>'newg','neon'=>'neon','netf'=>'netf','noki'=>'noki','nzph'=>'nzph','o2 x'=>'o2 x','o2-x'=>'o2-x','opwv'=>'opwv','owg1'=>'owg1','opti'=>'opti','oran'=>'oran','p800'=>'p800','pand'=>'pand','pg-1'=>'pg-1','pg-2'=>'pg-2','pg-3'=>'pg-3','pg-6'=>'pg-6','pg-8'=>'pg-8','pg-c'=>'pg-c','pg13'=>'pg13','phil'=>'phil','pn-2'=>'pn-2','pt-g'=>'pt-g','palm'=>'palm','pana'=>'pana','pire'=>'pire','pock'=>'pock','pose'=>'pose','psio'=>'psio','qa-a'=>'qa-a','qc-2'=>'qc-2','qc-3'=>'qc-3','qc-5'=>'qc-5','qc-7'=>'qc-7','qc07'=>'qc07','qc12'=>'qc12','qc21'=>'qc21','qc32'=>'qc32','qc60'=>'qc60','qci-'=>'qci-','qwap'=>'qwap','qtek'=>'qtek','r380'=>'r380','r600'=>'r600','raks'=>'raks','rim9'=>'rim9','rove'=>'rove','s55/'=>'s55/','sage'=>'sage','sams'=>'sams','sc01'=>'sc01','sch-'=>'sch-','scp-'=>'scp-','sdk/'=>'sdk/','se47'=>'se47','sec-'=>'sec-','sec0'=>'sec0','sec1'=>'sec1','semc'=>'semc','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','sk-0'=>'sk-0','sl45'=>'sl45','slid'=>'slid','smb3'=>'smb3','smt5'=>'smt5','sp01'=>'sp01','sph-'=>'sph-','spv '=>'spv ','spv-'=>'spv-','sy01'=>'sy01','samm'=>'samm','sany'=>'sany','sava'=>'sava','scoo'=>'scoo','send'=>'send','siem'=>'siem','smar'=>'smar','smit'=>'smit','soft'=>'soft','sony'=>'sony','t-mo'=>'t-mo','t218'=>'t218','t250'=>'t250','t600'=>'t600','t610'=>'t610','t618'=>'t618','tcl-'=>'tcl-','tdg-'=>'tdg-','telm'=>'telm','tim-'=>'tim-','ts70'=>'ts70','tsm-'=>'tsm-','tsm3'=>'tsm3','tsm5'=>'tsm5','tx-9'=>'tx-9','tagt'=>'tagt','talk'=>'talk','teli'=>'teli','topl'=>'topl','hiba'=>'hiba','up.b'=>'up.b','upg1'=>'upg1','utst'=>'utst','v400'=>'v400','v750'=>'v750','veri'=>'veri','vk-v'=>'vk-v','vk40'=>'vk40','vk50'=>'vk50','vk52'=>'vk52','vk53'=>'vk53','vm40'=>'vm40','vx98'=>'vx98','virg'=>'virg','vite'=>'vite','voda'=>'voda','vulc'=>'vulc','w3c '=>'w3c ','w3c-'=>'w3c-','wapj'=>'wapj','wapp'=>'wapp','wapu'=>'wapu','wapm'=>'wapm','wig '=>'wig ','wapi'=>'wapi','wapr'=>'wapr','wapv'=>'wapv','wapy'=>'wapy','wapa'=>'wapa','waps'=>'waps','wapt'=>'wapt','winc'=>'winc','winw'=>'winw','wonu'=>'wonu','x700'=>'x700','xda2'=>'xda2','xdag'=>'xdag','yas-'=>'yas-','your'=>'your','zte-'=>'zte-','zeto'=>'zeto','acs-'=>'acs-','alav'=>'alav','alca'=>'alca','amoi'=>'amoi','aste'=>'aste','audi'=>'audi','avan'=>'avan','benq'=>'benq','bird'=>'bird','blac'=>'blac','blaz'=>'blaz','brew'=>'brew','brvw'=>'brvw','bumb'=>'bumb','ccwa'=>'ccwa','cell'=>'cell','cldc'=>'cldc','cmd-'=>'cmd-','dang'=>'dang','doco'=>'doco','eml2'=>'eml2','eric'=>'eric','fetc'=>'fetc','hipt'=>'hipt','http'=>'http','ibro'=>'ibro','idea'=>'idea','ikom'=>'ikom','inno'=>'inno','ipaq'=>'ipaq','jbro'=>'jbro','jemu'=>'jemu','java'=>'java','jigs'=>'jigs','kddi'=>'kddi','keji'=>'keji','kyoc'=>'kyoc','kyok'=>'kyok','leno'=>'leno','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-g'=>'lg-g','lge-'=>'lge-','libw'=>'libw','m-cr'=>'m-cr','maui'=>'maui','maxo'=>'maxo','midp'=>'midp','mits'=>'mits','mmef'=>'mmef','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','mwbp'=>'mwbp','mywa'=>'mywa','nec-'=>'nec-','newt'=>'newt','nok6'=>'nok6','noki'=>'noki','o2im'=>'o2im','opwv'=>'opwv','palm'=>'palm','pana'=>'pana','pant'=>'pant','pdxg'=>'pdxg','phil'=>'phil','play'=>'play','pluc'=>'pluc','port'=>'port','prox'=>'prox','qtek'=>'qtek','qwap'=>'qwap','rozo'=>'rozo','sage'=>'sage','sama'=>'sama','sams'=>'sams','sany'=>'sany','sch-'=>'sch-','sec-'=>'sec-','send'=>'send','seri'=>'seri','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','siem'=>'siem','smal'=>'smal','smar'=>'smar','sony'=>'sony','sph-'=>'sph-','symb'=>'symb','t-mo'=>'t-mo','teli'=>'teli','tim-'=>'tim-','tosh'=>'tosh','treo'=>'treo','tsm-'=>'tsm-','upg1'=>'upg1','upsi'=>'upsi','vk-v'=>'vk-v','voda'=>'voda','vx52'=>'vx52','vx53'=>'vx53','vx60'=>'vx60','vx61'=>'vx61','vx70'=>'vx70','vx80'=>'vx80','vx81'=>'vx81','vx83'=>'vx83','vx85'=>'vx85','wap-'=>'wap-','wapa'=>'wapa','wapi'=>'wapi','wapp'=>'wapp','wapr'=>'wapr','webc'=>'webc','whit'=>'whit','winw'=>'winw','wmlb'=>'wmlb','xda-'=>'xda-',)));  
      $mobile_browser = true; 
      $status = 'Mobile matched on in_array';
    break;  

    default;
      $mobile_browser = false; 
      $status = 'Desktop / full capability browser';
    break; 

  }  
 	// Set type of mobile --- HLP4ever support
 	 $mobile_browser == true?$_SESSION['mobile_type'] = $status:$_SESSION['mobile_type'] = FALSE;
  // if redirect (either the value of the mobile or desktop redirect depending on the value of $mobile_browser) is true redirect else we return the status of $mobile_browser
  if($redirect = ($mobile_browser==true) ? $mobileredirect : $desktopredirect){
    header('Location: '.$redirect); // redirect to the right url for this device
    exit;
  }else{ 
	 
		if($mobile_browser==''){
			return $mobile_browser; // will return either true or false 
		}else{
			return array($mobile_browser,$status); // is a mobile so we are returning an array ['0'] is true ['1'] is the $status value
		}
	}

} // ends function mobile_device_detect


/**
 * 
 *  Function send data for communicating with other site
 * @param string $url
 * @param array $params	: data to send
 * @param string $verb	: sending type: POST or GET
 * @param string $format: type of receive data
 * @throws Exception
 */
function sendData($url, $params = null, $verb = 'GET', $format = '')
{
	
	/** demo
	$data = array("hello"=>"world");
	$value = rest_helper('http://127.0.0.1/test2.php',$data,"POST") ;
	echo $value;
	*/
  $cparams = array(
    'http' => array(
      'method' => $verb,
      'ignore_errors' => true,
   	  'header' => "Content-Type: application/x-www-form-urlencoded\r\n" 
    )
  );
  if ($params !== null) {
  	//foreach ($params as $key=>$value) $data .= $key."=".$value."&";
    $data = http_build_query($params);
    //echo $params;
    if (strtoupper($verb) == 'POST') {
      $cparams['http']['content'] = $data;
    } else {
      $url .= '?' . $data;
    }
  }
  $context = stream_context_create($cparams);
  if(ini_get("allow_url_fopen") != 1 ) ini_set("allow_url_fopen",1);
  $fp = @fopen($url, 'rb', false, $context);
  //print_r( error_get_last() );
  if (!$fp) {
    $res = false;
  } else {
    // If you're trying to troubleshoot problems, try uncommenting the
    // next two lines; it will show you the HTTP response headers across
    // all the redirects:
    // $meta = stream_get_meta_data($fp);
    // var_dump($meta['wrapper_data']);
    $res = stream_get_contents($fp);
  }
  /* if ($res === false) {
    throw new Exception("$verb $url failed: $php_errormsg");
  } */
	// PROCESS receive data
	
  switch ($format) {
    case 'xml':
      $r = @simplexml_load_string($res);
      if ($r === null) {
        throw new Exception("failed to decode $res as xml");
      }
      
      return $r;
  }
  return $res;
}

// end SendData function.

/*
function restrictAccess($time_restrict = 0.1) {
	if($_SESSION['restrict_access_1'] == '') {
		$_SESSION['restrict_access_1'] =  microtime(true);
		return TRUE;
	}
	else {
		$_SESSION['restrict_access_2'] =  microtime(true);
		if($_SESSION['restrict_access_2']- $_SESSION['restrict_access_1'] > $time_restrict) {
			$_SESSION['restrict_access_1'] =  microtime(true);
			return TRUE;
		}
		else {
			$_SESSION['restrict_access_1'] =  microtime(true);
			return FALSE;
		}
		
	}
}
*/
?>