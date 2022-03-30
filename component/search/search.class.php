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
 * Search class function.
 */

class search {
	private $count  = 0;
	private $result = array();
	private $error  = FALSE;
	// Error number
	// 1 : Năm bắt đầu lớn hơn năm kết thúc 
	// 2 : Tháng bắt đầu lớn hơn Tháng kết thúc
	// 3 : Ngày bắt đầu lớn hơn ngày kết thúc :o
	// 4 : Ngày bắt đầu không có thật (nhập sai)
	// 5 : Ngày kết thúc không có thật (nhập sai)
	// 6 : No result (not match);
	public function __construct() {
		// Before working, we have to check our DB exist or not.
		if(!isset($_CONFIG)) global $_CONFIG;
		$dbsetting = $_CONFIG->getDB();
		if($dbsetting['AUTOCHECK'] == TRUE) $this->createDB();
	}
	public function createDB() {
		$db		= new DBmanager();
		$query	=	"CREATE TABLE IF NOT EXISTS ".PREFIX."search (
				`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`search` VARCHAR( 256 ) NOT NULL ,
				`user` VARCHAR( 256 ) NOT NULL ,
				`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
				`result` MEDIUMTEXT NOT NULL
				) ENGINE = MYISAM ;
				";
		$db->execute($query);
	}
	public function search($search,$type='',$cache="OFF",$limit_from=0,$limit_to=3,$type_date_search='period',$title='OFF',$phrase='ON',$from_day='',$from_month='',$from_year='',$to_day='',$to_month='',$to_year='',$howfar='',$orderby='') {
		$db			= new DBmanager();
		$search 	= $db->escapeString($search);
		$limit_to 	= $db->escapeString($limit_to);
		$limit_from = $db->escapeString($limit_from);
		if($cache == "ON") {
			// Cached it means that: search and result will be saved in database
			// So it is very usefull if there are big database and a  large of query 
		}
		else {
			if($type == 'advanced') {
			// Advanced search	
				$type_date_search= $db->escapeString($type_date_search);				// Tìm kiếm theo loại ngày, có 2 loại bên dưới :
				$title		= $db->escapeString($title);				// Chỉ tìm theo tiêu đề
				$phrase		= $db->escapeString($phrase);					//Tìm chính xác cụm từ
				// Tìm trong khoảng ngày từ ngày bao nhiêu tới ngày bao nhiêu
				$from_day	= $db->escapeString($from_day);						// khoảng Bắt đầu
				$from_month	= $db->escapeString($from_month);
				$from_year	= $db->escapeString($from_year);	
				$to_day		= $db->escapeString($to_day);					// khoảng Kết thúc
				$to_month	= $db->escapeString($to_month);
				$to_year	= $db->escapeString($to_year);	
				$howfar		= $db->escapeString($howfar);			// Cách đây bao nhiêu ngày
				$orderby	= $db->escapeString($orderby);							// Sắp xếp
				
					//	kiểm tra khoảng	Ngày tìm kiếm
					if($type_date_search=="khoang")
						{
							$from_full_time = $from_year."-".$from_month."-".$from_day;
							$to_full_time = $to_day."-".$to_month."-".$to_year;
						// Checking valid time
							if($from_year>$to_year)	{	$this->error = 1;return;}
							elseif($from_year==$to_year)
							{
								if($from_month>$to_month)	{	$this->error = 2;return;}
								elseif($from_month==$to_month)
								{
									if($from_day>$to_day)	{	$this->error = 3;return;}
								}
							}
							if(checkdate($from_month,$from_day,$from_year)	== FALSE)	{ $this->error = 4;	return;}
							if(checkdate($to_month,$to_day,$to_year)		== FALSE)	{ $this->error = 5;	return;}
						// End Checking valid time
						}
				$adv_search_query = "FROM  ".PREFIX."blog WHERE";	// bắt đầu viết
				if($phrase == "ON")				// Cắt chuỗi tìm kiếm thành mảng
				{
					$i=0;
					$j=0;		// số thứ tự đầu tiên của chữ cái 
					$t=0;		// Đếm số từ trong câu
					while(isset($search[$i]))			// Khi mà chuỗi chưa kết thúc
						{
							while(($search[$i] !=" ")	&&($i<strlen($search)))			$i++;			// Lặp lọc ra từ trong câu	
							$t++;
							if(!isset($tu_khoa))	$tu_khoa = array(substr($search,$j,$i-$j));		else array_push($tu_khoa,substr($search,$j,$i-$j));
							$j	=	$i+1;	
							while(($search[$i] ==" ")&&($i<strlen($search)))	$i++;
						}
					if($title=="tieu_de_on"){
						for($k=0;$k<$t;$k++){
								$adv_search_query	.=	"	AND	title LIKE '%".$tu_khoa[$k]."%'	";
							}
					}
					else{
						$adv_search_query	.=	" (( ";
						for($k=0;$k<$t-1;$k++)	
							{
								$adv_search_query	.=	"	title LIKE '%".$tu_khoa[$k]."%'	AND	";
							}
						$adv_search_query	.=	"	title LIKE '%".$tu_khoa[$t-1]."%' ) OR ( ";	
						for($k=0;$k<$t-1;$k++)	
							{
								$adv_search_query	.=	"	fulltext LIKE '%".$tu_khoa[$k]."%' AND	";
							}
						$adv_search_query	.=	" fulltext LIKE '%".$tu_khoa[$t-1]."%' )  OR (";
						for($k=0;$k<$t-1;$k++)	
							{
								$adv_search_query	.=	"	`introtext` LIKE '%".$tu_khoa[$k]."%' AND	";
							}
						$adv_search_query	.=	" `introtext` LIKE '%".$tu_khoa[$t-1]."%' ))";	
					}
				}				// Kết thúc Cắt chuỗi tìm kiếm thành mảng
				else			// Tìm chính xác 1 cụm từ
				{
					$adv_search_query	.="  (title LIKE '%$search%' OR fulltext LIKE '%$search%' OR introtext LIKE '%$search%' ) ";
				}
				$adv_search_query	.=" AND publish=1 ";
				
								//	Xây dựng câu điều kiện theo loại ngày tìm kiếm
				if($type_date_search=="cach_day"){
					$homnay			= date(dmY);
					$nhap_vao		= substr($homnay,4,4)."-".substr($homnay,2,2)."-".substr($homnay,0,2);	// Y-m-d
					$to_full_time	= $nhap_vao." 23-59-59"	;
					$date 			= new DateTime($nhap_vao);	
					$from_full_time	= $date->modify("-$howfar day");
					$from_full_time	= $date->format("Y-m-d");
					$from_full_time	.= " 00-00-00";
				}
				elseif($type_date_search=="khoang")			// Tìm theo khoảng
				{
							$from_full_time .=" 00-00-00";
							$to_full_time 	.=" 23-59-59"	;
				}
				$adv_search_query_qr .= "	HAVING (date_cr BETWEEN  '$from_full_time'  AND '$to_full_time' ) ";
				$count_query = "SELECT count(id) ".$adv_search_query_qr;
				$count_query = "SELECT * ". $adv_search_query_qr . " 	ORDER BY date_cr $orderby  LIMIT $limit_from,$limit_to";
				//	echo	$adv_search_query_qr;
				//	Truy xuất CSDL
				$count_result   = $db->execute($count_query)->getRow();
				$this->count	= $count_result['count(id)'];
				if($this->count > 0) {
					$result =$db->execute($adv_search_query_qr);
					$num = $db->count();
					for($i=0; $i<$num; $i++) {
						$row = $db->FetchResult($result);
						array_push($this->result,$row);
					}
				}
			}
			// END advanced search
			//  Simple Search
			else {
				$count_query	= "SELECT count(id) FROM  ".PREFIX."blog	WHERE (`title` LIKE '%$search%' OR `introtext` LIKE '%$search%'  OR `fulltext` LIKE '%$search%' ) AND `publish`=1";
				$search_query	= "SELECT * FROM  ".PREFIX."blog	WHERE (`title` LIKE '%$search%' OR `introtext` LIKE '%$search%'  OR `fulltext` LIKE '%$search%' ) AND `publish`=1 LIMIT $limit_from,$limit_to";
				$count_result   = $db->execute($count_query);
				$count_result	= $db->getRow();
				$this->count	= $count_result['count(id)'];
				if($this->count > 0) {
					$result =$db->execute($search_query);
					$num = $db->count();
					for($i=0; $i<$num; $i++) {
						$row = $db->FetchResult($result);
						array_push($this->result,$row);
					}
				}
				else $this->error = 6;
			}
			return $this->result;
		}
	}
	public function getResult() {
		return $this->result;
	}
	public function getCount() {
		return $this->count;
	}
	public function getError() {
		return $this->error;
	}
}