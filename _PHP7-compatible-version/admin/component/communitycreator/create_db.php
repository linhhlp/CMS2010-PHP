<?php
// No direct access
defined('START') or die;
if($DB_info['TYPE'] == "MySQL") {
$query_blog	=	"CREATE TABLE IF NOT EXISTS ".ACCPREFIX."blog (
					`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`title` VARCHAR( 256 ) NOT NULL ,
					`alias_title` VARCHAR( 256 ) NOT NULL ,
					`introtext` MEDIUMTEXT NOT NULL ,
					`fulltext` MEDIUMTEXT NOT NULL ,
					`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
					`created_by` VARCHAR( 256 ) NOT NULL ,
					`modified` TIMESTAMP NOT NULL ,
					`modified_by` VARCHAR( 256 ) NOT NULL ,
					`hit` VARCHAR( 256 ) NOT NULL ,
					`category` VARCHAR( 256 ) NOT NULL ,
					`publish` VARCHAR( 256 ) NOT NULL ,
					`frontpage` VARCHAR( 256 ) NOT NULL
					) ENGINE = MYISAM ;
				";
$query_category	=	"CREATE TABLE IF NOT EXISTS ".ACCPREFIX."category (
					`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`title` VARCHAR( 256 ) NOT NULL ,
					`alias` VARCHAR( 256 ) NOT NULL ,
					`name` VARCHAR( 256 ) NOT NULL ,
					`descr` VARCHAR( 256 ) NOT NULL ,
					`image` VARCHAR( 256 ) NOT NULL ,
					`order` int(5) NOT NULL,
					`parent` VARCHAR( 256 ) NOT NULL ,
					`public` VARCHAR( 256 ) NOT NULL  
					) ENGINE = MYISAM ;
				";
$query_comment	=	"CREATE TABLE IF NOT EXISTS `".ACCPREFIX."comment` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `title` varchar(256) NOT NULL,
				  `comment` mediumtext NOT NULL,
				  `type` varchar(256) NOT NULL,
				  `belong` varchar(256) NOT NULL,
				  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `ip` varchar(256) NOT NULL,
				  `user` varchar(256) NOT NULL,
				  `publish` varchar(256) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;
				";
$query_component	=	"CREATE TABLE IF NOT EXISTS `".ACCPREFIX."component` (
					  `id` int(5) NOT NULL AUTO_INCREMENT,
					  `title` varchar(256) NOT NULL,
					  `path` varchar(256) NOT NULL,
					  `descr` varchar(256) NOT NULL,
					  `params` varchar(256) NOT NULL,
					  `order` int(5) NOT NULL,
					  `type` varchar(256) NOT NULL,
					  `default` varchar(256) NOT NULL,
					  `permission` varchar(256) NOT NULL,
					  `enable` varchar(256) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM;
				";
$query_frontpage	=	"CREATE TABLE IF NOT EXISTS ".ACCPREFIX."frontpage (
					`id` int(3) NOT NULL AUTO_INCREMENT,
					  `title` varchar(256) NOT NULL,
					  `tl` varchar(256) NOT NULL,
					  `link` varchar(2000) NOT NULL,
					  `descr` varchar(2000) DEFAULT NULL,
					  `date` datetime NOT NULL,
					  `publish` varchar(20) NOT NULL DEFAULT '',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM;
				";
$query_menu	=	"CREATE TABLE IF NOT EXISTS `".ACCPREFIX."menu` (
				  `id` int(10) NOT NULL AUTO_INCREMENT,
				  `title` varchar(256) NOT NULL,
				  `alias` varchar(256) NOT NULL,
				  `link` varchar(256) NOT NULL,
				  `descr` varchar(256) NOT NULL,
				  `image` varchar(256) NOT NULL,
				  `order` int(5) NOT NULL,
				  `parent` varchar(256) NOT NULL,
				  `type` varchar(256) NOT NULL,
				  `public` varchar(256) NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `alias_title` (`alias`)
				) ENGINE=MyISAM ;
				";
$query_module	=	"CREATE TABLE IF NOT EXISTS `".ACCPREFIX."module` (
				  `id` int(10) NOT NULL AUTO_INCREMENT,
				  `title` varchar(256) NOT NULL,
				  `name` varchar(256) NOT NULL,
				  `id_pos` int(10) NOT NULL,
				  `descr` varchar(256) NOT NULL,
				  `params` varchar(256) NOT NULL,
				  `order` int(5) NOT NULL,
				  `path` varchar(256) NOT NULL,
				  `type` varchar(256) NOT NULL,
				  `permission` varchar(256) NOT NULL,
				  `enable` varchar(256) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM ;
				";
$query_plugin	=	"CREATE TABLE IF NOT EXISTS `".ACCPREFIX."plugin` (
					  `id` int(5) NOT NULL AUTO_INCREMENT,
					  `title` varchar(256) NOT NULL,
					  `name` varchar(256) NOT NULL,
					  `path` varchar(256) NOT NULL,
					  `descr` varchar(256) NOT NULL,
					  `params` varchar(256) NOT NULL,
					  `order` int(5) NOT NULL,
					  `type` varchar(256) NOT NULL,
					  `permission` varchar(256) NOT NULL,
					  `enable` varchar(256) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM;
				";
$query_position	=	"CREATE TABLE IF NOT EXISTS `".ACCPREFIX."position` (
				  `id` int(10) NOT NULL AUTO_INCREMENT,
				  `title` varchar(256) NOT NULL,
				  `name` varchar(256) NOT NULL,
				  `descr` varchar(256) NOT NULL,
				  `type` varchar(256) NOT NULL,
				  `order` int(5) NOT NULL,
				  `permission` varchar(256) NOT NULL,
				  `enable` varchar(256) NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `name` (`name`)
				) ENGINE=MyISAM;
				";
$query_search	=	"CREATE TABLE IF NOT EXISTS ".ACCPREFIX."search (
				`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`search` VARCHAR( 256 ) NOT NULL ,
				`user` VARCHAR( 256 ) NOT NULL ,
				`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
				`result` MEDIUMTEXT NOT NULL
				) ENGINE = MYISAM ;
				";
$query_template	=	"CREATE TABLE IF NOT EXISTS `".ACCPREFIX."template` (
				  `id` int(10) NOT NULL AUTO_INCREMENT,
				  `title` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `author` varchar(256) NOT NULL,
				  `version` varchar(256) NOT NULL,
				  `descr` varchar(255) NOT NULL,
				  `path` varchar(255) NOT NULL,
				  `type` varchar(256) NOT NULL,
				  `setting` varchar(255) NOT NULL,
				  `enable` varchar(255) NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `path` (`path`)
				) ENGINE=MyISAM;
				";
$query_visit	=	"CREATE TABLE IF NOT EXISTS `".ACCPREFIX."visit` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `ip` varchar(256) NOT NULL,
				  `time` varchar(256) NOT NULL,
				  `name` varchar(256) NOT NULL,
				  `info` varchar(1000) NOT NULL,
				  `number` varchar(256) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;
				";
$query_user	=	"CREATE TABLE IF NOT EXISTS ".ACCPREFIX."user (
					`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`user` VARCHAR( 256 ) NOT NULL ,
					`pass` VARCHAR( 256 ) NOT NULL ,
					`name_user` VARCHAR( 256 ) NOT NULL ,
					`birthday` VARCHAR( 256 ) NOT NULL ,
					`home` VARCHAR( 1000 ) NOT NULL ,
					`mobile` VARCHAR( 256 ) NOT NULL ,
					`tel` VARCHAR( 256 ) NOT NULL ,
					`address` VARCHAR( 1000 ) NOT NULL ,
					`type` VARCHAR( 256 ) NOT NULL ,
					`activation` VARCHAR( 256 ) NOT NULL ,
					`enable` VARCHAR( 256 ) NOT NULL ,
					`note` VARCHAR( 1000 ) NOT NULL 
					) ENGINE = MYISAM ;
				";
$query_aboutme	=	"CREATE TABLE IF NOT EXISTS ".ACCPREFIX."aboutme (
				  `id` int(8) NOT NULL AUTO_INCREMENT,
				  `user_id` varchar(8) NOT NULL,
				  `info` varchar(10000) NOT NULL,
				  `picture` varchar(1000) DEFAULT NULL,
				  `aboutme` varchar(10000) DEFAULT NULL,
				  `love` varchar(10000) DEFAULT NULL,
				  `biogra` varchar(10000) DEFAULT '',
				  `history` varchar(10000) DEFAULT '',
				  `lastupdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`)
				)  ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
				";
$query_userbonus	=	"CREATE TABLE IF NOT EXISTS `".ACCPREFIX."userbonus` (
				  `id` int(10) NOT NULL AUTO_INCREMENT,
				  `title` varchar(256) NOT NULL,
				  `content` varchar(1000) NOT NULL,
				  `created_by` varchar(256) NOT NULL,
				  `belong` varchar(256) NOT NULL,
				  `status` varchar(256) NOT NULL,
				  `public` varchar(256) NOT NULL,
				  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM;
				";
$query_community	=	"CREATE TABLE IF NOT EXISTS `".ACCPREFIX."community` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `name` varchar(256) NOT NULL,
			  `type` varchar(256) NOT NULL,
			  `value1` mediumtext NOT NULL,
			  `value2` mediumtext NOT NULL,
			  `value3` mediumtext NOT NULL,
			  `value4` mediumtext NOT NULL,
			  `settings` varchar(256) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;
				";
mysqli_query($query_blog);
mysqli_query($query_category);
mysqli_query($query_comment);
mysqli_query($query_component);
mysqli_query($query_frontpage);
mysqli_query($query_menu);
mysqli_query($query_plugin);
mysqli_query($query_module);
mysqli_query($query_position);
mysqli_query($query_search);
mysqli_query($query_template);
mysqli_query($query_visit);
mysqli_query($query_user);
mysqli_query($query_aboutme);
mysqli_query($query_userbonus);
mysqli_query($query_community);
echo mysqli_error();
}
?>