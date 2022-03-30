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
if(!isset($com_params)) global $com_params;
//print_r($com_params);
if(!isset($_URL)) global $_URL;
$url = $_URL->getNewUrl ();
$config = explode ( ',', $com_params );
// Note: order of config array:
// 0 : Type comment : blog(frontpage), acticle (content) ,...
// 1 : Width of Textarea.
// 2 : Number of comment per page (main content)
// 3 : (Optional)   :id: Identification of blog content. ( Belong)
// 4 : Disable captcha.
if (empty ( $config [2] ))
	$config [2] = 20;
if (empty ( $config [1] ))
	$config [1] = 50;
if ($url [0] == 'blog') {
	$limit = '';
} else {
	$limit = $config [2];
}
if($url [1] >1) $page_limit = $url [1] -1;
else $page_limit = 0;
$limit_start = $config[2]*$page_limit;
if (empty ( $config [2] ))
	$config [2] = 0;
global $title_meta_data_temp;
$title_meta_data_temp = "Bình luận";
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



<div class="COMMENT_HLP_FULL">
<form action="<?php echo $_URL->getOldUrl (); ?>" method="post" onsubmit="return check(this)">
<div style="text-align:center" ><h3>Bình luận</h3>

		<input type="hidden" name="type" value="<?php echo $config[0];?>"></input>
		<input type="hidden" name="title" size="30"value="Bình luận" > 
		Nội dung bình luận <br /><textarea cols="<?php echo $config [1];?>" rows=4 name="comment"><?php echo $_POST ['comment']; ?></textarea>
	<br />
	<?php	//if( $config [4] == FALSE ) echo "<!--hlp_plugin_captcha-->"; 
	?>
<input type="submit" value="Gửi bình luận">
</div>
</form>
<?php

if (! empty ( $_POST ['comment'] )) {
if( !isset($captcha_code_valid ) ) global $captcha_code_valid;
	if($config [4] != FALSE || $captcha_code_valid != FALSE) {
		$comment->createComment ( $_POST );
		//echo $comment->getError();
	}
}

// Find comments by type"
//$comments = $comment->getComments ( $config [0],$limit_start,$limit,$config [3] );
//$num_com  = $comment->getNumCom();
$arr = array( 'type'=>$config [0], 'limit_start'=>$limit_start, 'limit'=> $limit ,'belong'=>$config [3],'order'=>'last_change' );
$comment_arr = $comment->getComments ( $arr );
$num_com  = $comment_arr ['total'];//$comment->getNumCom();
$comments = $comment_arr['parent'];
$comments_child = $comment_arr['children'];


?>
<br />
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
			echo "<div class=\"COMMENT_HLP_COMMENT\">" . $content  . "</div>";
			echo "</div>";
			
			if( !empty($comments_child[ $value['id']])) {
			
				foreach ( $comments_child[ $value['id']] as $key2=>$value2) {
					$acomment = symbol2pic($value2 ['comment']);
					echo '<div class="cmt_for_items">';
					if( each($comments_child[ $value['id']] ) == false ) $cmt_name = "name='".$value['id']."-lastcmt'";
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
			
			$i++;
		}
        // Make page 
        echo '<div class="COMMENT_HLP_PAGE_TITLE">Sang trang:<br />';
		$page_array = paging($url [1],$num_com/$config [2]);
        foreach($page_array as $key=>$value) {
            if($key =='current') {
                echo '<span class="COMMENT_HLP_PAGE_CURRENT">'.$value.'</span>';
            }
            elseif($value =='...') {
                echo $value;
            }
            else {
                echo urlmanager::makeLink($url[0],$value,'','comment trang thu '.$value,'<span class="COMMENT_HLP_PAGE">'.$value.'</span>');
            }
        }
        echo '</div>';

	}
	?>
    
</div>




<br />