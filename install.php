<?php

/**
 * @author HLP4ever 2010.
 * Installation KIT for web
 */

if(is_file('config.php')) {
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	Bạn không thể chạy install khi vẫn còn file config. Hãy xóa file config trước khi cài đặt.<br />.
			Please remove or delete config file before installing.<br />';
	exit;
}
else {
	// Run set up.
	define('INSTALL','installing');
	include('install/index.php');
}


?>