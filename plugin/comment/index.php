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
if(!isset($plugin_params)) global $plugin_params;
//print_r($com_params);
if(!isset($_URL)) global $_URL;
$url = $_URL->getNewUrl ();
$config = explode ( ',', $plugin_params['params'] );
// Note: order of config array:
// 0 : Type comment : blog(frontpage), acticle (content) ,...
// 1 : Width of Textarea.
// 2 : Disable captcha.

if(empty($config [1])) $config [1] = 50;
$limit 		= 0 ;
$comment = new comment ();
if($_SESSION['mobile_type'] == FALSE) { 
	// This is normal display : full capability browser (in windows or Linux ...)
	echo textEdittor('');
	echo '<script type="text/javascript">
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
</script>';
}
?>


<form action="<?php echo $_URL->getOldUrl (); ?>" method="post" onsubmit="return check(this)">
<div class="COMMENT_HLP_FULL">
<h3>Bình luận</h3>
<br />


		Bình luận mới
		<br />
		<input type="hidden" name="type" value="<?php echo $config[0];?>"></input>
		<input type="hidden" name="belong" value="<?php echo $url[1];?>"></input>
		<input type="text" name="title" size="30"
			value='Tiêu đề' onblur="if(this.value=='') this.value='Tiêu đề';" onfocus="if(this.value=='Tiêu đề') this.value='';" /> <br />
		Nội dung bình luận <br /><textarea cols='<?php echo $config [1];?>' rows='4' name="comment"><?php echo $_POST ['comment']; ?></textarea>
	<br /> 
	<?php 
	
	if( $config [2] == FALSE ) echo "<!--hlp_plugin_captcha-->";
	
	?>
	
<input type="submit" value="Gửi bình luận" />
<br />

<?php
if (! empty ( $_POST ['comment'] )) {
	if(!isset($captcha_code_valid ) ) global $captcha_code_valid;
	if($config [2] != FALSE || $captcha_code_valid != FALSE) {
		$comment->createComment ( $_POST ['title'], $_POST ['comment'], $_POST ['type'],$_POST ['belong'] );
		$error = $comment->getError();
	}
	else {
		$error = 'captcha';
	}
}

// Find comments by type"
$comments = $comment->getComments ( $config [0],$limit_start,$limit,$url[1] );
$num_com  = $comment->getNumCom();


?>

Bình luận của mọi người. Có <?php		echo $num_com;		?> bình luận.
<br />
	
		
<?php
	if($num_com == 0) echo 'Bạn chưa có bình luận nào.';
	elseif($num_com > 0) {
		$i = 0;
		foreach ($comments as $key=>$value) {
			echo "<div class='COMMENT_HLP_A_ITEM>'";
			echo "<span class='COMMENT_HLP_TITLE'> " . $value ['title'] . "</span> <span class='COMMENT_HLP_USER'>(" . $value ['user'] . "  lúc  " . $value ['date'] . ")</span>";
			echo "<div class='COMMENT_HLP_COMMENT'>" . $value ['comment'] . "</div>";
			echo "</div>";
			$i++;
		}
//        // Make page 
//        echo '<div class="COMMENT_HLP_PAGE_TITLE">Sang trang:<br />';
//		$page_array = paging($url [1],$num_com/$config [2]);
//        foreach($page_array as $key=>$value) {
//            if($key =='current') {
//                echo '<span class="COMMENT_HLP_PAGE_CURRENT">'.$value.'</span>';
//            }
//            elseif($value =='...') {
//                echo $value;
//            }
//            else {
//                echo urlmanager::makeLink($url[0],$value,'','comment trang thu '.$value,'<span class="COMMENT_HLP_PAGE">'.$value.'</span>');
//            }
//        }
//        echo '</div>';

	}
	?>
    
</div>



</form>
<br />