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
if($url[0] != false) return;
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
if (!is_numeric  ( $config [4] ))
	$config [4] = 20;
if (!is_numeric  ( $config [5] ))
	$config [5] = 200;
if (!is_numeric  ( $config [1] ))
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
	echo "<span href='".$_URL->getOldUrl ()."#Showcomment' onClick='Showcomment();' name='Showcomment' style='color:blue;text-decoration:underline;cursor: hand; cursor: pointer; '>Hiện thị comment blog</span>";
	echo "<div id='COMMENT_HLP_SHOWHERE' >";
}
?>



<div class="box_top"></div>
<h3 class="COMMENT_HLP_h3">Bình luận</h3>
<div class="box_body">


<form action="<?php echo $_URL->getOldUrl (); ?>" method="post" onsubmit="return check(this)">
<?php 
if($config[6] != '') echo '<input type=hidden name=belong value="'.$config[6].'">';
?>
<div class="COMMENT_HLP">
		<input type="hidden" name="type" value="<?php echo $config[0];?>"></input>
		<input type="hidden" name="title" size="30" value='Bình luận' /> <br />
		<textarea cols="<?php echo $config [1];?>" rows="<?php echo $config [2];?>" name="comment"><?php echo $_POST ['comment']; ?></textarea>
	<br />
	<div style="text-align:center" >
	<?php	//if( $config [7] == FALSE ) echo "<!--hlp_plugin_captcha-->";
	?>
<input type="submit" value="Gửi bình luận" />
	</div>
</form>
<br />
<?php
if (! empty ( $_POST ['comment'] ) && empty ( $_POST ['belong'] ) ) {
	
	if( !isset($captcha_code_valid ) ) global $captcha_code_valid;
	if($config [7] != FALSE || $captcha_code_valid != FALSE) {
		//  $_POST ['title'], $_POST ['comment'], $_POST ['type'],$_POST ['belong']
		$comment->createComment ( $_POST);
		//echo $comment->getError();
	}
	else {
		echo "<h2>Nhập tên của bạn để bình luận!</h2>";
	}
}

// Find comments by type"
//$types, $limit_start =0, $limit = 3,$belong ='',$publish ='', $ip ='',$user = '',$orderby='DESC'
$arr = array( 'type'=>$config [0], 'limit_start'=>$limit_start, 'limit'=> $limit ,'belong'=>$config [6],'order'=>'last_change' );
$comment_arr = $comment->getComments ( $arr );
$num_com  = $comment_arr ['total'];//$comment->getNumCom();
$comments = $comment_arr['parent'];
$comments_child = $comment_arr['children'];

?>

Bình luận của mọi người. Có <?php		echo $num_com;		?> bình luận.
<br />
	
		
<?php
	if($num_com == 0) echo 'Bạn chưa có bình luận nào.';
	elseif($num_com > 0) {
		$i = 0;
		foreach ($comments as $key=>$value) {
			$content = symbol2pic($value ['comment']);
			echo "<div class='COMMENT_HLP_A_ITEM'>";
			echo "<span class=\"COMMENT_HLP_USER\">" . $value ['user'] . "</span>  (" . time2TiengViet($value ['date']) . ")<br />";
			if( $value ['title'] != 'Bình luận' ) echo "<span class=\"COMMENT_HLP_TITLE\" > " .$value ['title'].'</span>';
			echo "<div class=\"COMMENT_HLP_COMMENT\">" . wrap_content( $content,0,$config [5]) . "</div>";
			echo "</div>";
			
			// Children comment (rely comment)
			if( !empty($comments_child[ $value['id']])) {
			
				foreach ( $comments_child[ $value['id']] as $key2=>$value2) {
					$acomment = symbol2pic($value2 ['comment']);
					echo '<div class="cmt_for_items">';
					if( !is_array($comments_child[ $value['id']] )  ) 
						$cmt_name = "name='".$value['id']."-lastcmt'";
					else $cmt_name = "";
					echo "<a class=\"COMMENT_HLP_USER\" ".$cmt_name." >".$blog_title[$value2['belong']]['title']."" . $value2 ['user'] . "</a>  (" . time2TiengViet($value2 ['date']) . ")";
					echo "<div class=\"COMMENT_HLP_COMMENT\">";
					if( $value2 ['title'] != 'Bình luận' ) echo "<span class=\"COMMENT_HLP_TITLE\" > " .$value2 ['title'].'</span><br />';
					echo wrap_content($acomment,0,200) . "</div>";
					echo "<div class='cmt_HR'></div>";
					echo '</div>';
				}
				
			}
			// Display form creates cmt for each item
			echo '<div class="cmt_for_items">';
			echo '<form  action="'.$_URL->getOldUrl ().'#'.$value['id'].'-lastcmt" method="post" ><input type="text" size="65" value="Trả lời..." name="comment" onblur="if(this.value==\'\') this.value=\'Trả lời...\';" onfocus="if(this.value==\'Trả lời...\') this.value=\'\';" ><input  type="submit" value="Gửi" ><input type=hidden name="parent" value="'.$value['id'].'"><input type="hidden" name="type" value="'.$config[0].'"><input type="hidden" name="title" size="30" value="Bình luận" /> </form>';
			echo '</div>';
			// END
			
			$i++;
		}
		echo '<a href="'.urlmanager::makeLink('comment','','').'" class="COMMENT_HLP_TATCA"> Tất cả bình luận </a>';

	}
	?>
</div>

</div>
<div class="box_bottom"></div>

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