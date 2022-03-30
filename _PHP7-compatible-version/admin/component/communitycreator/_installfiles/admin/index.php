<?php
//  Load config for community
include_once '../../communityconfig.php';
$_COMMUNITY_CONFIG = new CommunityConfig(TRUE);
global $_COMMUNITY_CONFIG;
$comm_url   = $_COMMUNITY_CONFIG->getURL();
//loadsetting
include_once '../../'.$comm_url['site'].'/adminindex.php';

// start load HLP4ever framework.
include_once '../../_root/admin/index.php';

?>