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
 * This is style I designed months ago. Now I use it as default
 * To make new template, please see the standard I wrote in template/default/index.php
 */

/**
 * Config for template:
 * _URL : Full URL
 */

global $THEME_PATH;
global $_CONFIG;
//$THEME_PATH = $_SESSION['temp']['config'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo _URL;?>" />
<title><?php $_title = $_CONFIG->getSite(); echo $_title['TITLE']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Đây là blog online của Hoàng Trọng Linh, nickname là HLP4ever hay HLP,HLP Blog, HLP 007,Trang web cá nhân của HLP4ever, Hoàng Trọng Linh Blog, Hoàng Linh Blog, HLP4ever blog, HLP4ever 2010" />
<meta name="keywords" content="HLP4ever blog,hlp 007,Hoang Trong Linh,Hoàng Trọng Linh Blog, HLPHLP" />
<meta name="robots" content="INDEX,FOLLOW,ALL" />
<meta name="author" content="HLP4ever" />
<meta name="copyright" content="Copyright (C) 2008-2010 hlp007.com" />

<?php
echo '
	<link href="'.$THEME_PATH.'/hlp.css" type="text/css" rel="stylesheet" />
	<link href="'.$THEME_PATH.'/hlp.ico" rel="shortcut icon" type="image/x-icon" />
  ';
    
?>
    
</head>


<body id="main">

<!-- Header -->

<!-- Content -->
<div class="wrapper">		
	<div class="content_blog_top"></div>
	<div class="content_blog">
	<div id="main_content">
		<br /><!--hlp_position_first_load-->
		<br /><!--hlp_position_left-->
		<br /><!--hlp_position_main_content-->
		<br /><!--hlp_position_user_right--></div>
	</div>
	<div class="content_blog_bottom"></div>
		
	<div class="clr"></div>
</div>
<!-- Footer -->
<div class="clr"></div>
<div class="wrapper">
		<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-553531-7");
pageTracker._trackPageview();
} catch(err) {}</script>


</div>
<div class="clr"></div>
</body>
</html>
