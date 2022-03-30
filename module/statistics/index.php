
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
 * 
 */

/**
 * Setting:
 * 	 global $mod_params 
 */
if(!isset($mod_params)) global $mod_params;
if(!isset($_URL)) global $_URL;
if($_CONFIG == false) global $_CONFIG;
$type_user = $_CONFIG->getPermission();
$url = $_URL->getNewUrl ();
$config = explode ( ',', $mod_params );
// Note: order of config array:
// 0 : User ID : who want to statistics
// 1 : Statistics by User		: ON or OFF
// 2 : Statistics by Comment	: ON or OFF
// 3 : Statistics by Contents each Category: ON or OFF
if (empty ( $config [0] )) $config [0] = '1';
if (empty ( $config [1] )) $config [1] = 'ON';
if (empty ( $config [2] )) $config [2] = 'ON';
if (empty ( $config [3] )) $config [3] = 'ON';

?>

<?php
$statistics = new statistics();
if($config [1] == 'ON') {
	$info = $statistics->getUser($config[0]);
	echo "<table border=\"0\" width=\"270px\">";
	echo "<tr><td colspan=\"2\"><h3>Thông tin nhỏ về chủ blog</h3></td></tr>";
	echo "<tr><td width=\"50%\">Tên đầy đủ </td><td>" . $info ['name_user'] . "</td></tr>";
	echo "<tr><td width=\"50%\">Sinh ngày </td><td>" . $info ['birthday'] . "</td></tr>";
	echo "<tr><td width=\"50%\">Quê quán </td><td>" . $info ['home'] . "</td></tr>";
	echo "<tr><td width=\"50%\">Hiện tại ở </td><td>" . $info ['address'] . "</td></tr>";
	echo "<tr><td width=\"50%\">Điện thoại di động </td><td>" . $info ['mobile'] . "</td></tr>";
	echo "<tr><td width=\"50%\">Điện thoại nhà riêng </td><td>" . $info ['tel'] . "</td></tr>";
	echo "<tr><td width=\"50%\">Loại thành viên </td><td>" . $type_user[$info ['type']] . "</td></tr>";
	echo "<tr><td width=\"50%\">Bật mí nhỏ </td><td>" . $info ['note'] . "</td></tr>";
	echo "</table>";
}
	echo "<table border=\"0\" width=\"270px\">";
	echo "<tr><td colspan=\"2\"><h3>Thống kê về trang web</h3></td></tr>";
if($config [3] == 'ON') {
	$cate_sta= $statistics->getCategory();
	foreach ($cate_sta as $title=>$number) {
		echo "<tr><td width=\"50%\">".$title."</td><td>" . $number . "</td></tr>";
	}
}
if($config [2] == 'ON') {
	list($total,$frontpage) = explode('-',$statistics->getComment());
	echo "<tr><td width=\"50%\">Tổng số comment</td><td>" . $total . "</td></tr>";
	echo "<tr><td width=\"50%\">Front-page comment</td><td>" . $frontpage . "</td></tr>";
	echo "<tr><td width=\"50%\">Article comment</td><td>" .($total - $frontpage)  . "</td></tr>";
}
echo "</table>";