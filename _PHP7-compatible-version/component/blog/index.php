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
 * This module manage Article (blog content) for our website
 * This is controller file
 */
//*************Setting**************//
$blog = new contentmanager();
if(!isset($_URL)) global $_URL;
$url = $_URL->getNewUrl ();
if ( !isset($login)) global $login;
$user_info = $login->getInfo();

$blogid = $url[1];
list($category_id,$page_current) = explode ( '-', $url[2]);
	// NOTE: URL will have format:
	//    /blog/blog_id/categoryID-PageNumber/...
	//        example:   blog/10/3-0/....
	//		so that, $url[0] :  blog id
if(!isset($com_params)) global $com_params; 
$config = explode ( ',', $com_params);
// Note: order of config array:
// 0 : Number of articles per page
// 1 : Number of letter to be extracted from full content
// Default Setting
	if (empty ( $config [0] ))	$config [0] = 10;
	if (empty ( $config [1] ))	$config [1] = 200;
//*************End Setting**************//

if(empty($page_current)) {
		$page_current = 0;
}
	
if($page_current >1) $page_limit = $page_current -1;
else $page_limit = 0;
$limit_start = $config[0]*$page_limit;
//if($page_start[0]=='blog' && !empty($page_start[1]))    $limit_start = $page_start[1];
list($type_get,$type_get_id) = explode ( '-', $url[1]);
$user_id = user::getUserName('','id');
global $title_meta_data_temp;
$comment = new comment ();
if (! empty ( $_POST ['comment'] ) && !empty ( $_POST ['belong'] ) ) {
	if( !isset($captcha_code_valid ) ) global $captcha_code_valid;
	if($config [7] != FALSE || $captcha_code_valid != FALSE) {
		$arr = array( 'title'=>$_POST ['title'], 'comment'=>$_POST ['comment'], 'type'=>$_POST ['type'],'belong'=>$_POST ['belong'],'parent'=>$_POST ['parent']);
		//pre($arr);
		$comment->createComment ( $arr);
		//echo $comment->getError();
	}
}

// For admin
if ($user_info ['type'] == 3 || $user_info ['type'] == 4 )
echo	"<br><a href=".urlmanager::makeLink(ADM,'contentmanager','new')." target='_blank' ><img src="._URL.IMA."/new.png title='Viết bài mới'>Viết bài mới</a><br><br>";

echo '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>';
echo '<script src="'.LIB.'/galleria/galleria-1.2.9.min.js"></script>';
echo "<script>Galleria.loadTheme('".LIB."/galleria/themes/classic/galleria.classic.min.js');</script>";



if($blogid > 0 && !empty($blogid)) {
	$ablog = $blog->getContent($blogid);
	if(!empty($ablog)) {
	echo "<div class=\"CONTENT_TITLE\">".urlmanager::makeLink($url[0],$ablog[0] ['id'],$url [2],$ablog[0]  ['alias_title'],$ablog[0]  ['title'])." </div> <div class=\"CONTENT_DATE\">(" ;
	if($ablog[0] ['publish'] == '0') echo '<span class=\"CONTENT_SPECIAL\">Cá nhân</span> - ';
		elseif($ablog [0]['publish'] == '2') echo '<span class=\"CONTENT_SPECIAL\">Bạn bè</span> - ';
		elseif($ablog [0]['publish'] == '3') echo '<span class=\"CONTENT_SPECIAL\">Special</span> - ';
		echo   'bởi: '.$user_id[$ablog[0] ['created_by']] . "  lúc  " . $ablog[0] ['created'] . ")</div>";
	$fulltext = player::replacePlayer($ablog[0]  ['fulltext']); // Make player for media content
	$fulltext = picslide::replacePicslide($fulltext); // Make player for media content
	if($ablog [0] ['introtext'] != '') $fulltext = $ablog [0] ['introtext'].'<br />--------------<br />'.$fulltext;
	echo "<div class=\"CONTENT_TEXT\">" ;
	$path 	= 'picture/';
		$folder = $path.$ablog [0] ['id'];
		$arr = @scandir($folder);
			if( count($arr) > 2) {
			// Blog có ảnh
			echo '<div id="galleria_'.$ablog [0] ['id'].'">';
            foreach( $arr as $imgfile ) {
				if( $imgfile != '.' && $imgfile != '..' ) echo  '<img src="'.$folder.'/'.$imgfile.'"  data-big="'.$folder.'/'.$imgfile.'">';
			}
			echo '</div>';
			//echo "<script>Galleria.run('#galleria_".$value ['id']."', {popupLinks:true});</script>";
			?>
			<script>$('#galleria_<?php echo $ablog [0] ['id'];?>').galleria({

			extend: function() {
			this.bind(Galleria.IMAGE, function(e) {
			this.bindTooltip( e.imageTarget, "&nbsp;" );
			// bind a click event to the active image
			$(e.imageTarget).css('cursor','pointer').click(this.proxy(function() {
			// open the image in a lightbox
			this.openLightbox();
			}));
			});
			}

});</script>
<?php
			echo '<style>    #galleria_'.$ablog [0] ['id'].'{ width: 400px; height: 300px; background: #000 }</style>';
		}
	
	echo $fulltext . "</div>";
	// Plug-in here
	echo "<!--hlp_plugin_end_content-->";
	echo "<div class='CONTENT_HR'></div>";
	echo "<br /><br /><br />";
	}
	else echo "<div class=\"CONTENT_TITLE\">Không có bài viết nào</div>";
	$title_meta_data_temp = $ablog[0]  ['title'];
}
else {
if(!isset($title_meta_data_temp)) $title_meta_data_temp = "Thống kê bài viết";
//echo "<h3 class='CONTENT_THONGKE'>Thống kê bài viết:</h3>";
if($category_id <= 0 || empty($category_id))  $category_id = '';
$arr = array( 'limit_from'=>$limit_start,'limit_to'=> $config[0] , 'category'=> $category_id, 'order'=>'last_change','orderby'=>'' );
$blogs = $blog->getContent($arr);
$num_con  = $blog->getNumContents();
if($category_id > 0) {
	// Get name of the category
	$categoy = new category();
	$categoy_info = $categoy->cate_info($category_id);
	echo "<h3 class='CONTENT_THONGKE'>".$categoy_info['title']."</h3>";
	//echo "<div class='CONTENT_THONGKE_THEO'>Thống kê theo: ".$categoy_info['title']."</div>";
}
$i = 0;
if(!empty($blogs)) {
	// get all ID of content
	$ids = array();
	foreach ($blogs as $key=>$value) {
		$ids[] = $value['id'];
	}
	//getComments($type, $limit_start =0, $limit = 3,$belong ='',$publish ='', $ip ='',$user = '',$orderby='DESC')
	$arr = array( 'type'=>'blog','limit_start'=>0,'limit'=>100, 'belong'=>implode(',',$ids),'orderby'=>'ASC' );
	$comment_arr = $comment->getComments ( $arr );
	$num_com  = $comment_arr ['total'];//$comment->getNumCom();
	$comments = $comment_arr['parent'];
	$comments_child = $comment_arr['children'];
	// Order content by BELONG ID
	foreach( $comments as $value) {
		$cmt_belong[ $value['belong'] ][] = $value;
	}
	//pre($cmt_belong);
	$path 	= 'picture/';
	foreach ($blogs as $key=>$value) {
		
		$folder = $path.$value ['id'];
		$arr = @scandir($folder);
		
		$fulltext  = symbol2pic($value ['fulltext']);
		echo "<div class=\"CONTENT_TITLE\">".urlmanager::makeLink($url[0],$value ['id'],$url[2],$value ['alias_title'],$value ['title'])." </div> <div class=\"CONTENT_DATE\">(";
		if($value ['publish'] == '0') echo '<span class=\"CONTENT_SPECIAL\">Cá nhân</span> - ';
		elseif($value ['publish'] == '2') echo '<span class=\"CONTENT_SPECIAL\">Bạn bè</span> - ';
		elseif($value ['publish'] == '3') echo '<span class=\"CONTENT_SPECIAL\">Special</span> - ';
		echo   'bởi: '.$user_id[$value ['created_by']] . ", " . time2TiengViet($value ['created']) . ")</div>";
		if($value ['introtext'] == '') $introtext = wrap_content( $fulltext ,0,$config [1]);
		else $introtext = $value ['introtext'].'<br />--------------'.wrap_content($fulltext ,0,$config [1]);
		$introtext = player::replacePlayer($introtext); // Make player for media content
		//$introtext = picslide::replacePicslide($introtext); // Make player for media content
		echo "<div class=\"CONTENT_TEXT\">";
		if( count($arr) > 2) {
			// Blog có ảnh
			echo '<div id="galleria_'.$value ['id'].'">';
            foreach( $arr as $imgfile ) {
				if( $imgfile != '.' && $imgfile != '..' ) echo  '<img src="'.$folder.'/'.$imgfile.'"  data-big="'.$folder.'/'.$imgfile.'">';
			}
			echo '</div>';
			//echo "<script>Galleria.run('#galleria_".$value ['id']."', {popupLinks:true});</script>";
			?>
			<script>$('#galleria_<?php echo $value ['id'];?>').galleria({

			extend: function() {
			this.bind(Galleria.IMAGE, function(e) {
			this.bindTooltip( e.imageTarget, "&nbsp;" );
			// bind a click event to the active image
			$(e.imageTarget).css('cursor','pointer').click(this.proxy(function() {
			// open the image in a lightbox
			this.openLightbox();
			}));
			});
			}

});</script>
<?php
			echo '<style>    #galleria_'.$value ['id'].'{ width: 400px; height: 300px; background: #000 }</style>';
		}
		
		echo $introtext . "</div>";
		// Display comment for a item
		$n_cmt = count($cmt_belong[ $value['id']]);
		if( $n_cmt > 0 ) {
			$j=0;
			foreach ( $cmt_belong[ $value['id']] as $key2=>$value2) {
				$acomment = symbol2pic($value2 ['comment']);
				echo '<div class="cmt_for_items">';//var_dump(each($cmt_belong[ $value['id']] ));
				if( $n_cmt == $j+1 ) $cmt_name = "name='".$value['id']."-lastcmt'";
				else $cmt_name = "";
				echo "<a class=\"COMMENT_HLP_USER\" ".$cmt_name." >".$blog_title[$value2['belong']]['title']."" . $value2 ['user'] . "</a>  (" . time2TiengViet($value2 ['date']) . ")";
				echo "<div class=\"COMMENT_HLP_COMMENT\">";
				if( $value2 ['title'] != 'Bình luận' ) echo "<span class=\"COMMENT_HLP_TITLE\" > " .$value2 ['title'].'</span><br />';
				echo wrap_content($acomment,0,200) . "</div>";
				
					// Children comment (rely comment)
					$child_n_com = count($comments_child[ $value2['id']]);
					if( $child_n_com > 0 ) {
						$jj = 0;
						foreach ( $comments_child[ $value2['id']] as $key3=>$value3) {
							$acomment = symbol2pic($value3 ['comment']);
							echo '<div class="cmt_for_items">';
							echo "<div class='cmt_HR'></div>";
							if( $child_n_com == $jj+1 ) $cmt_name = "name='".$value2['id']."-child-lastcmt'";
							else $cmt_name = "";
							echo "<a class=\"COMMENT_HLP_USER\" ".$cmt_name." >".$blog_title[$value3['belong']]['title']."" . $value3 ['user'] . "</a>  (" . time2TiengViet($value3 ['date']) . ")";
							echo "<div class=\"COMMENT_HLP_COMMENT\">";
							if( $value3 ['title'] != 'Bình luận' ) echo "<span class=\"COMMENT_HLP_TITLE\" > " .$value3 ['title'].'</span><br />';
							echo wrap_content($acomment,0,200) . "</div>";
							
							echo '</div>';
							$jj++;
						}
						
					}
					// Display form creates cmt for each item
					echo '<div class="cmt_for_items">';
					echo "<div class='cmt_HR'></div>";
					echo '<form  action="'.$_URL->getOldUrl ().'#'.$value2['id'].'-child-lastcmt" method="post" ><input class="child_cmt_input" type="text" size="55" value="Trả lời..." name="comment" onblur="if(this.value==\'\') this.value=\'Trả lời...\';" onfocus="if(this.value==\'Trả lời...\') this.value=\'\';" ><input  type="submit" value="Gửi" ><input type=hidden name="parent" value="'.$value2['id'].'"><input type=hidden name="belong" value="'.$value['id'].'"><input type="hidden" name="type" value="blog"><input type="hidden" name="title" size="30" value="Bình luận" /> </form>';
					echo '</div>';
				
				
				echo "<div class='cmt_HR'></div>";
				echo '</div>';
				$j++;
			}
			
		}
		// Display form creates cmt for each item
		echo '<div class="cmt_for_items">';
		echo '<form  action="'.$_URL->getOldUrl ().'#'.$value['id'].'-lastcmt" method="post" ><input type="text" size="68" value="Bình luận" name="comment" onblur="if(this.value==\'\') this.value=\'Bình luận\';" onfocus="if(this.value==\'Bình luận\') this.value=\'\';" class="cmt_blog_input"><input  type="submit" value="Gửi"  ><input type=hidden name=belong value="'.$value['id'].'"><input type="hidden" name="type" value="blog"><input type="hidden" name="title" size="30" value="Bình luận" /> </form>';
		echo '</div>';
		
		echo "<div class='CONTENT_HR'></div>";
		$i++;
	}
	// Make page 
		if($category_id <= 0) $category_id = 0;
        echo '<div class="CONTENT_HLP_PAGE_TITLE">Sang trang:<br />';
		$page_array = paging($page_current,$num_con/$config [0]);
        foreach($page_array as $key=>$value) {
            if($key =='current') {
                echo '<span class="CONTENT_HLP_PAGE_CURRENT">'.$value.'</span>';
            }
            elseif($value =='...') {
                echo $value;
            }
            else {
                echo urlmanager::makeLink($url[0],$url[1],$category_id.'-'.$value,'bai viet trang thu '.$value,'<span class="CONTENT_HLP_PAGE">'.$value.'</span>');
            }
        }
        echo '</div>';
}
else {
	echo "<div class=\"CONTENT_TITLE\">Không có bài viết nào</div>";
}
}
        
?>
<div class="clr"></div>