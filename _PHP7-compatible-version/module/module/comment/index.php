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
// 0 : Type comment : blog(frontpage), acticle (content) ,...
// 1 : Width of Textarea.
// 2 : Row of Textarea.
// 3 : Text editor support or not.
// 4 : Number of comment per page (main content)
// 5 : Number of character extract
// 6 : (Optional)   :id: Identification of blog content. ( Belong)
// 7 : Disable captcha.
// 8 : Auto hide comment.
if (empty ( $config [4] ))
	$config [4] = 20;
if (empty ( $config [5] ))
	$config [5] = 200;
if (empty ( $config [1] ))
	$config [1] = 50;
if ( $config [7] == "on") $config [7] = TRUE;
else $config [7] = FALSE;
$limit = $config [4];
$limit_start = $config[2]*$config[3];
if (empty ( $config [2] ))
	$config [2] = 0;
$comment = new comment ();

if($_SESSION['mobile_type'] == FALSE) { 
	// This is normal display : full capability browser (in windows or Linux ...)
	if ( $config [3] == "on") echo textEdittor('');
}

if ( $config [8] == "on") {
	echo "<a href='".$_URL->getOldUrl ()."#Showcomment' onClick='Showcomment();' name='Showcomment'>Hiện thị comment blog</a>";
	echo "<div id='COMMENT_HLP_SHOWHERE' >";
}
?>


<form action="<?php echo $_URL->getOldUrl (); ?>" method="post" onsubmit="return check(this)">
<div class="box_top"></div>
<h3 class="COMMENT_HLP_h3">Bình luận</h3>
<div class="box_body">



<?php 
if($config[6] != '') echo '<input type=hidden name=belong value="'.$config[6].'">';
?>
<div class="COMMENT_HLP">
		Bình luận mới
		<br />
		<input type="hidden" name="type" value="<?php echo $config[0];?>"></input>
		<input type="text" name="title" size="30"
			value='Tiêu đề' onblur="if(this.value=='') this.value='Tiêu đề';" onfocus="if(this.value=='Tiêu đề') this.value='';" /> <br />
		Nội dung bình luận <br /><textarea cols="<?php echo $config [1];?>" rows="<?php echo $config [2];?>" name="comment"><?php echo $_POST ['comment']; ?></textarea>
	<br />
	<?php	if( $config [7] == FALSE ) echo "<!--hlp_plugin_captcha-->"; ?>
<input type="submit" value="Gửi bình luận" />
<br />
<?php
if (! empty ( $_POST ['comment'] ) && empty ( $_POST ['belong'] ) ) {
	
	if( !isset($captcha_code_valid ) ) global $captcha_code_valid;
	if($config [7] != FALSE || $captcha_code_valid != FALSE) {
		$comment->createComment ( $_POST ['title'], $_POST ['comment'], $_POST ['type'],$_POST ['belong'] );
		//echo $comment->getError();
	}
}

// Find comments by type"
$comments = $comment->getComments ( $config [0],$limit_start,$limit,$config [6] );
$num_com  = $comment->getNumCom();


?>

Bình luận của mọi người. Có <?php		echo $num_com;		?> bình luận.
<br />
	
		
<?php
	if($num_com == 0) echo 'Bạn chưa có bình luận nào.';
	elseif($num_com > 0) {
		$i = 0;
		foreach ($comments as $key=>$value) {
			echo "<div class='COMMENT_HLP_A_ITEM'>";
			echo "<span class=\"COMMENT_HLP_TITLE\"> " . $value ['title'] . "</span> <span class=\"COMMENT_HLP_USER\">(" . $value ['user'] . "  lúc  " . $value ['date'] . ")</span>";
			echo "<div class=\"COMMENT_HLP_COMMENT\">" . wrap_content($value ['comment'],0,$config [5]) . "</div>";
			echo "</div>";
			$i++;
		}
		echo '<a href="'.urlmanager::makeLink('comment','','').'" class="COMMENT_HLP_TATCA"> Tất cả bình luận </a>';

	}
	?>
</div>

</div>
<div class="box_bottom"></div>
</form>
<?php
if ( $config [8] == "on") {
	echo "</div>";
}
?>
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
	var showcommenton = "off";
	function Showcomment() {
		if(showcommenton == "on") {
			showcommenton = "off";
			document.getElementById("COMMENT_HLP_SHOWHERE").style.visibility="visible";
			document.getElementById("COMMENT_HLP_SHOWHERE").style.height="100%";
		}
		else {
			document.getElementById("COMMENT_HLP_SHOWHERE").style.visibility="hidden";
			document.getElementById("COMMENT_HLP_SHOWHERE").style.height="0";
			showcommenton = "on";
		}
	}
	Showcomment();
</script>