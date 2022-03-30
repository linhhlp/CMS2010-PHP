<?php
// No direct access
defined('START') or die;
if($DB_info['TYPE'] == "MySQL") {
$setting_charset = " SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';";
mysql_query($setting_charset);
$setting_charset = "SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT;";
mysql_query($setting_charset);
$setting_charset = "SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS;";
mysql_query($setting_charset);
$setting_charset = "SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION;";
mysql_query($setting_charset);
$setting_charset = " SET NAMES utf8 ;";
mysql_query($setting_charset);

$category_insert = "INSERT INTO `".ACCPREFIX."category`  (`id`, `title`, `alias`, `name`, `descr`, `image`, `order`, `parent`, `public`) VALUES
(1, 'CÃ¡c bÃ¡o cÃ¡o', 'baocao', 'baocao', 'moi', '2', '2', '0', '1'),
(3, 'CÃ¡c dá»± Ã¡n', 'DuAn', 'duan', 'CÃ¡c dá»± Ã¡n', '', '1', '0', '1');" ;

$frontpage_insert = "INSERT INTO `".ACCPREFIX."frontpage` (`id`, `title`, `tl`, `link`, `descr`, `date`, `publish`) VALUES
(1, 'Giá»›i thiá»‡u vá» trang web', 'baiviet', '', '<p>Ä&acirc;y l&agrave; trang Ä‘Æ°a tin c&aacute;c b&agrave;i b&aacute;o c&aacute;o khoa há»c!</p>', '2009-05-05 00:49:36', '1');
";

$component_insert = "
INSERT INTO `".ACCPREFIX."component` (`id`, `title`, `path`, `descr`, `params`, `order`, `type`, `default`, `permission`, `enable`) VALUES
(1, 'trang chu', 'frontpage', 'front page', '', '2', 'blog', '1', '', '1'),
(3, 'Comment for blog', 'comment', 'Comment for blog', 'Front page,40,20', '1', 'blog', '0', '0,1,2,3,4,', '1'),
(5, 'Content manager', 'contentmanager', 'Content manager', '300,15', '1', 'admin', '0', '3,4,', '1'),
(6, 'file manager', 'filemanager', 'filemanager', '', '1', 'admin', '0', '3,4,', '1'),
(7, 'frontpage manager', 'frontpagemanager', 'frontpage manager', '', '1', 'admin', '0', '3,4,', '1'),
(8, 'Manage modules', 'managemodule', 'Manage modules', '', '1', 'admin', '0', '3,4,', '1'),
(9, 'temp manager', 'tempmanager', 'temp manager', '', '1', 'admin', '0', '3,4,', '1'),
(10, 'comment manager', 'commentmanager', 'comment manager', '200,20', '1', 'admin', '0', '3,4,', '1'),
(11, 'Category manager', 'categorymanager', 'Category manager', '', '', 'admin', '0', '3,4,', '1'),
(12, 'Menu manager', 'menumanager', 'Menu manager', '', '', 'admin', '0', '3,4,', '1'),
(13, 'Display blog content', 'blog', 'Display blog content', '', '', 'blog', '0', '0,1,2,3,4,', '1'),
(14, 'ThÃ´ng tin cÃ¡ nhÃ¢n', 'aboutme', '', '1', '', 'blog', '0', '0,1,2,3,4,', '1'),
(15, 'Manage your information', 'aboutme', '', '', '', 'admin', '0', '0,1,2,3,4,', '1'),
(16, 'Community management', 'communitymanagement', 'Manage all installed componet ', '', '', 'admin', '0', '3,4,', '1'),
(17, 'Create community account', 'communitycreator', 'create a blog (commuinty) account', '', '', 'admin', '0', '4,', '1'),
(18, 'Make friend', 'communitymakefriend', '', '', '', 'admin', '0', '0,1,2,3,4,', '1');";


$menu_insert = "
INSERT INTO `".ACCPREFIX."menu` (`id`, `title`, `alias`, `link`, `descr`, `image`, `order`, `parent`, `type`, `public`) VALUES
(1, 'CÃ¡c bÃ¡o cÃ¡o', 'Blog', 'blog/0/1-0/', 'moi', '', '1', '0', 'leftmenu', '1'),
(2, 'CÃ¡c dá»± Ã¡n', '3-music', 'blog/0/3-0/', 'music', '', '3', '0', 'leftmenu', '1'),
(3, 'Giao diá»‡n', '24-Giao-dien', 'tempmanager', '', '', '2', '0', 'adminmenu', '1'),
(4, 'Trang nháº¥t', '25-Trang-nhat', 'frontpagemanager', '', '', '3', '0', 'adminmenu', '1'),
(5, 'Trang chá»§', 'Trang-chu', '', 'leftmenu', '', '0', '0', 'leftmenu', '1'),
(6, 'BÃ i viáº¿t', '27-Bai-viet', 'contentmanager', '', '', '4', '0', 'adminmenu', '1'),
(7, 'Category', '28-Category', 'categorymanager', '', '', '4', '0', 'adminmenu', '1'),
(8, 'Comment', '29-Comment', 'commentmanager', '', '', '5', '0', 'adminmenu', '1'),
(9, 'Module', '30-Module', 'managemodule', '', '', '6', '0', 'adminmenu', '1'),
(10, 'Menu', '31-Menu', 'menumanager', '', '', '7', '0', 'adminmenu', '1'),
(11, 'CÃ¡ nhÃ¢n', '33-Ca-nhan', 'aboutme', 'CÃ¡ nhÃ¢n', '', '8', '0', 'adminmenu', '1'),
(12, 'ThÃ´ng tin', '35-Thong-tin-ve-Linh', 'aboutme', '', '', '5', '0', 'leftmenu', '1'),
(13, 'Community', '36-Community', 'communitymanagement', '', '', '11', '0', 'adminmenu', '1');";

$module_insert = "
INSERT INTO `".ACCPREFIX."module` (`id`, `title`, `name`, `id_pos`, `descr`, `params`, `order`, `path`, `type`, `permission`, `enable`) VALUES
(1, 'Admin Top Menu', 'menu', 23, 'Admin Top Menu', 'adminmenu,admin', '0', 'menu', 'admin', '3,4,', '1'),
(2, 'ThÃ´ng tin', 'visitor', 24, 'Thong tin', 'Tháº§y Nguyá»…n VÄƒn A', '3', 'visitor', 'blog', '0,1,2,3,4,', '1'),
(3, 'Thá»‘ng kÃª', 'statistics', 24, '1', '1,OFF,ON,ON', '3', 'statistics', 'blog', '0,1,2,3,4,', '1'),
(4, 'ÄÄƒng nháº­p', 'login', 10, 'login', 'login', '1', 'login', 'admin', '0,1,2,3,4,', '1'),
(5, 'Comment for blog', 'comment', 19, '', 'Front page,26,3', '2', 'comment', '0', '', '1'),
(6, 'menu', 'menu', 19, 'menu', 'leftmenu,default', '1', 'menu', 'blog', '', '1'),
(7, 'yahoo', 'yahoo', 24, 'yahoo', 'kim_le_duc_89', '3', 'yahoo', 'blog', '0,1,2,3,4,', '1'),
(8, 'new blog Comment', 'commentnew', 8, '', 'blog,5,70', '3', 'commentnew', 'blog', '0,1,2,3,4,', '1'),
(9, 'new Content', 'blognew', 8, '', '0,3', '5', 'blognew', 'blog', '0,1,2,3,4,', '1'),
(10, 'welcome', 'welcome', 24, '', 'Tháº§y Nguyá»…n VÄƒn A', '', 'welcome', 'blog', '0,1,2,3,4,', '1'),
(11, 'Proccess login modules', 'login', 24, '', '', '2', 'login', 'blog', '0,1,2,3,4,', '1');";

$position_insert = "
INSERT INTO `".ACCPREFIX."position` (`id`, `title`, `name`, `descr`, `type`, `order`, `permission`, `enable`) VALUES
(1, 'TrÃªn cÃ¹ng', 'top', 'TrÃªn cÃ¹ng cá»§a trang web', 'blog', '0', '0,1,2,3,4,', '1'),
(2, 'cuoi cung', 'bottom', '1', '1', '1', '', '1'),
(3, 'main content', 'main', 'main content', '1', '1', '', '1'),
(19, 'TrÃ¡i', 'left', '', '0', '', '', '1'),
(24, 'BÃªn pháº£i', 'user_right', 'bÃªn pháº£i', 'blog', '0', '0,1,2,3,4,', '1'),
(9, 'title', 'main_admin', '1', 'admin', '1', '', '1'),
(10, 'Log-in for admin', 'login_admin', 'Log-in for admin', 'admin', '1', '', '1'),
(21, 'First Load', 'first_load', 'For Module which need load first.', '0', '', '', '1'),
(23, 'menu_admin', 'menu_admin', 'menu_admin', 'admin', '1', '', '1');";

$plugin_insert = "
INSERT INTO `".ACCPREFIX."plugin` (`id`, `title`, `name`, `path`, `descr`, `params`, `order`, `type`, `permission`, `enable`) VALUES
(1, 'validate for blog', 'captcha', 'captcha', '', '', '', 'blog', '0,1,2,3,4,', '1'),
(2, 'Comment for blog', 'comment', 'comment', '', 'blog,15', '', 'blog', '0,1,2,3,4,', '1');";

$community_insert = "
INSERT INTO `".ACCPREFIX."community` (`id`, `name`, `type`, `value1`, `value2`, `value3`, `value4`, `settings`) VALUES
(1, 'blog', 'rss', '', '', '', '', ''),
(2, 'makefriend', 'community', '', '', '', '', 'unread'),
(3, 'menu', 'adminmenu', 'communitycreator', 'create', '', 'Create an account', ''),
(4, 'menu', 'usermenu', 'communitymakefriend', '', '', 'Manage and make friend', '');";

mysql_query($setting_charset);
echo mysql_error();
mysql_query($category_insert);
mysql_query($frontpage_insert);
mysql_query($component_insert);
mysql_query($menu_insert);
mysql_query($module_insert);
mysql_query($position_insert);
mysql_query($plugin_insert);
mysql_query($community_insert);
}
?>