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
 * This is index file
 * search content in blog Database 
 */
if(!isset($mod_params)) global $mod_params;
if(!isset($_URL)) global $_URL;
$url = $_URL->getNewUrl ();
$config = explode ( ',', $mod_params );
	// Config Note
	// 0 : Cache ON of OFF
	// 1 : Number results per page
	// 0 : Cache ON of OFF
	// 0 : Cache ON of OFF
if(empty($config[0])) $config[0] = 'OFF'; 
if($url[1] == 'advanced') {
	?>
	<h4>Tìm kiếm chi tiết </h4>
	<form action=index.php method=GET><input type=hidden name=hlp value=search_adv>
	<table border=0>
	<tr><td align=right>Từ khóa tìm kiếm</td><td><input type=text name=search_adv size=30 value=".$tim_kiem."></td></tr>
	<tr><td align=right>Hiển thị số kết quả trên 1 trang</td><td><input type=text value=10 size=2 name=search_num></td></tr>
	<tr><td rowspan=2 align=right>Trong khoảng thời gian</td>			<td><input type=radio value='cach_day' name='loai_ngay' checked>Cách đây <input type=text name='bao_nhieu' size=4 value=60> 
			ngày</td>
		</tr>
		<tr>
			<td><input type=radio value='khoang' name='loai_ngay'>Từ ngày<input type=text name=khoang1_ngay size=4>tháng<input type=text name=khoang1_thang size=4>năm<input type=text name=khoang1_nam size=4><br> Đến 
			 ngày          : <input type=text name=khoang2_ngay size=4>tháng<input type=text name=khoang2_thang size=4>năm<input type=text name=khoang2_nam size=4></td>
		</tr>	
	<tr><td>Chỉ tìm theo tiêu đề bài viết <input type=checkbox name=tieu_de value=tieu_de_on></td><td>Tìm chính xác cụm từ<input type=checkbox name=cum_tu value=cum_tu_on></td></tr>
	<tr><td align=right>Sắp xếp theo</td><td><select name=sapxep><option value= ASC>Ngày tăng dần</option><option value= DESC>Ngày giảm dần</option></select></td></tr>
	<td align=right><input type=submit value='Tìm kiếm'></td><td><input type=reset value='Nhập lại'></td>
	</tr>
	</table>
	</form>
<?php
}
else {
	?>
	
<div class=search>
		<form action=<?php echo urlmanager::makeLink('search','','')?> method=POST>
<input type=text name=search size=15 value="<?php echo $_POST['search'];?>" >
		<?php echo urlmanager::makeLink('search','advanced','','tim kiem nang cao','Chuyên sâu','class="search_adv_link"')?>
		</form>
</div> 	
<br />
<?php
	if($_POST['search'] != '') {
		$search = new search();
		$search->search($_POST['search'],'',$config[0]);
		$search_error = $search->getError();
		$search_result= $search->getResult();
		$search_count = $search->getCount();
		if($search_error == FALSE) {
			foreach($search_result as $key=>$value) {
				$i = 0;
				echo "<div class=CONTENT_TITLE>".urlmanager::makeLink('blog',$value ['id'],$url[2],$value ['alias_title'],$value ['title'])." </div> <div class=CONTENT_DATE>(" . $value ['created_by'] . "  lúc  " . $value ['created'] . ")</div>";
				if($value ['introtext'] == '') $introtext = wrap_content($value ['fulltext'],0,$config [1]);
				else $introtext = $value ['introtext'];
				echo "<div class=CONTENT_TEXT>" . $introtext . "</div>";
				echo "<div class='CONTENT_HR'></div>";
				$i++;
			}
			// Make page 
	        echo '<div class="CONTENT_HLP_PAGE_TITLE">Sang trang:<br />';
			$page_array = paging($page_current,$num_com/$config [0]);
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
			echo "<div class=CONTENT_TITLE>";
			switch ($search_error) {
				case 1: echo ' Năm bắt đầu lớn hơn năm kết thúc'; break;
				case 2: echo ' Tháng bắt đầu lớn hơn Tháng kết thúc'; break;
				case 3: echo ' Ngày bắt đầu lớn hơn ngày kết thúc'; break;
				case 4: echo ' Ngày bắt đầu không có thật (nhập sai)'; break;
				case 5: echo ' Ngày kết thúc không có thật (nhập sai)'; break;
				case 6: echo ' No result (not match)'; break; 				
			}
			echo "</div>";
		}
	}

}



?>

