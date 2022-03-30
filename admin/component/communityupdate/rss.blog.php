<?php
/**
 * @author HLP4ever .
 * @version		2010
 * @package		HLP4ever community.
 * @copyright	Copyright (C) 2010 by HLP.
 * @license		Open Source and free for non-conmecial
 */
defined('START') or die;
/**
 * process and 
 * Do not change code below, only set up your config if you're sure you've known.
 */
// example url request: xml.php?type=rss&name=blog&rss=2.0
if(empty($_CONFIG) )	global $_CONFIG;
$site_config= $_CONFIG->getSite();
$order 	 	= $_GET['order'];
$limit_from = $_GET['limit_from'];
$limit	 	= $_GET['limit'];
$category_id= $_GET['category_id'];
$rss		= $_GET['rss'];
if($order 	== "") 		$order		 = "DESC"; 
if($limit_from == "") 	$limit_from	 = "0";		 
if($limit 	== "")	 	$limit		 = "10"; 
$blog = new contentmanager();
$results = $blog->getContent('',$limit_from,$limit,$category_id,'','','','','','','','',$order);
if($results != FALSE ) {
	if($rss == '2.0') {
		echo "<rss version=\"2.0\"> 
		<channel>
		<title>".$site_config["TITLE"]."</title>
		<description>This is blog RSS feed</description>
		<link>"._URL."</link>
		<lastBuildDate>".date(r)."</lastBuildDate>
		<pubDate>".date(r)."</pubDate> \n";
		
		foreach ($results as $result) {
			if($result ['introtext'] == '') $introtext = wrap_content($result ['fulltext'],0,200);
			else $introtext = $result ['introtext'];
			$created = date(r, mktime(substr($result ['created'],11,2), substr($result ['created'],14,2), substr($result ['created'],17,2), substr($result ['created'],5,2), substr($result ['created'],8,2), substr($result ['created'],0,4)));
			echo "\t \t \t <item>
				<title>".$result['title']."</title>
				<description><![CDATA[ ".$introtext."  ]]> </description>
				<link>".urlmanager::makeLink('blog',$result ['id'],'')."</link>
				<guid>".$result ['id']."</guid>
				<pubDate>".$created."</pubDate> 
				<author>"._URL."</author>
			</item> \n";
		}
		echo '</channel></rss>';
	}
	else {
		// return by field name
		echo "\t <blog> \n";
		foreach ($results as $result) {
			foreach ($result as $key=>$value) {
				echo "\t \t <".$key."> <![CDATA[ ".$value." ]]> </".$key."> \n";
			}
		}
		echo "\t </blog> \n";
	}
}
			
?>