<?php
// load permission
if( !is_object($_COMMUNITY_CONFIG) )global $_COMMUNITY_CONFIG;
global $community_permission;
$community_permission = $_COMMUNITY_CONFIG->getPERMISSION();

?>