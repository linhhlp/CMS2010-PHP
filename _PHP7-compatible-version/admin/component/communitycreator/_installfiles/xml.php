<?php

/**
 * @author HLP4ever 2010;
 * @copyright 2010;
 * 
 * This is the website port which sends information
 * to another website
 */
// Mapping xml.php file
//  Load config for community
include_once '../communityconfig.php';
$_COMMUNITY_CONFIG = new CommunityConfig();
global $_COMMUNITY_CONFIG;
$comm_url   = $_COMMUNITY_CONFIG->getURL();
//loadsetting
include_once '../'.$comm_url['site'].'/xml.php';

// start load HLP4ever framework.
include_once '../_root/xml.php';

?>