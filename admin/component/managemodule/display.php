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

	if($ACTION == 'delete') {
		$delete_arr = explode('-',$CONTROL);
		if($delete_arr[0] == 'module') {
			$del_re = $mana->deleteModule($delete_arr[1]);
		}
		elseif($delete_arr[0] == 'position') {
			$del_re = $mana->deletePosition($delete_arr[1]);
		}
        elseif($delete_arr[0] == 'component') {
        	$del_re = $mana->deleteComponent($delete_arr[1]);
		}
		elseif($delete_arr[0] == 'plugin') {
        	$del_re = $mana->deletePlugin($delete_arr[1]);
		}
		if($del_re == FALSE ) {
			echo 'DELETE FAIL.<br />';
		}
		else echo 'DELETE SUCCESSFULLY.<br />';
	}
    
    // Load Module, Component, Position
    
	$modules	 = $mana->getModule();
	$positions	 = $mana->getPosition();
    $components	 = $mana->getComponent();
    $plugins	 = $mana->getPlugin();
?>
<br />
<h3 align="right"><?php echo urlmanager::makeLink($PARAS,'install_module','','cai dat','Install new module.')?></h3>
<h3>Quản lý module</h3>
<table id="managemodule">
<tr class="title">
	<td>#</td><td width=30%>Title</td><td>Enable</td><td>Type</td><td>Order</td><td>Position</td><td>Path</td><td>Exist</td><td>ID</td><td>Delete</td>
</tr>
<?php
$i = 1;
if(!empty($modules)) {
	foreach ($modules as $key=>$value) {
		if($value['title'] == '') $title = 'No title'; 
		else $title = $value['title'];
		$pos = $mana->getPosition($value['id_pos']);
		if($value['exist']) $exist = 'Yes';
		else $exist = 'No';
		if($value['type'] != 'admin') {
			$value['type'] = 'blog';
		}
		if($value['enable'] == '1') {
			$value['enable'] = 'Yes';
		}
	    else $value['enable'] = 'No';
		echo "<tr>
			<td>$i</td>
			<td>".urlmanager::makeLink($PARAS,'module',$value['id'],'editModule',$title)."</td>
			<td>".$value['enable']."</td><td>".$value['type']."</td><td>".$value['order']."</td><td>".$pos['title']."</td><td>".$value['path']."</td><td>$exist</td><td>".$value['id']."</td><td>".urlmanager::makeLink($PARAS,'delete','module-'.$value['id'],'delete','Delete')."</td>
			</tr>";
	$i++;	
	}
}
else {
	echo 'No module installed';
}
?>
</table>

                                                                                                             
<h3>Quản lý Component</h3>
<h3 align="right"><?php echo urlmanager::makeLink($PARAS,'install_component','','cai dat','Install new Component.')?></h3>
<table id="managemodule">
<tr class="title">
	<td>#</td><td width=30%>Title</td><td>Enable</td><td>Default</td><td>Type</td><td>Order</td><td>Path</td><td>Exist</td><td>ID</td><td>Delete</td>
</tr>
<?php
$i = 1;
if(!empty($components)) {
	foreach ($components as $key=>$value) {
		if($value['title'] == '') $title = 'No title'; 
		else $title = $value['title'];
		if($value['exist']) $exist = 'Yes';
		else $exist = 'No';
		if($value['type'] != 'admin') {
			$value['type'] = 'blog';
		}
		if($value['enable'] == '1') {
			$value['enable'] = 'Yes';
		}
	    else $value['enable'] = 'No';
	    if($value['default'] == '1') {
			$value['default'] = 'Default';
		}
	    else $value['default'] = 'No';
		echo "<tr>
			<td>$i</td>
			<td>".urlmanager::makeLink($PARAS,'component',$value['id'],'editComponent',$title)."</td>
			<td>".$value['enable']."</td><td>".$value['default']."</td><td>".$value['type']."</td><td>".$value['order']."</td><td>".$value['path']."</td><td>$exist</td><td>".$value['id']."</td><td>".urlmanager::makeLink($PARAS,'delete','component-'.$value['id'],'delete','Delete')."</td>
			</tr>";
	$i++;	
	}
}
else {
	echo 'No component installed';
}
?>
</table>

<h3>Quản lý position</h3>
<h3 align="right"><?php echo urlmanager::makeLink($PARAS,'create_position','','cai dat','Create new position.')?></h3>
<table id="managemodule">
<tr class="title">
	<td>#</td><td width=30%>Title</td><td>Name</td><td>Enable</td><td>Type</td><td>Order</td><td>ID</td><td>Delete</td>
</tr>
<?php
$i = 1;
if(!empty($positions)) {
	foreach ($positions as $key=>$value) {
		if($value['title'] == '') $title = 'No title'; 
		else $title = $value['title'];
		if($value['type'] != 'admin') {
			$value['type'] = '&nbsp;';
		}
		if($value['enable'] == '1') {
			$value['enable'] = 'Yes';
		}
		
		echo "<tr>
			<td>$i</td>
			<td>".urlmanager::makeLink($PARAS,'position',$value['id'],'editModule',$title)."</td><td>".$value['name']."</td>
			<td>".$value['enable']."</td><td>".$value['type']."</td><td>".$value['order']."</td><td>".$value['id']."</td><td>".urlmanager::makeLink($PARAS,'delete','position-'.$value['id'],'delete','Delete')."</td>
			</tr>";
	$i++;	
	}
}
else {
	echo 'No position installed';
}

?>
</table>
<h3>Quản lý Plug-in</h3>
<h3 align="right"><?php echo urlmanager::makeLink($PARAS,'create_plugin','','cai dat','Create new plugin.')?></h3>
<table id="managemodule">
<tr class="title">
	<td>#</td><td width=30%>Title</td><td>Enable</td><td>Name</td><td>Type</td><td>Order</td><td>Path</td><td>Exist</td><td>ID</td><td>Delete</td>
</tr>
<?php
$i = 1;
if(!empty($plugins)) {
	foreach ($plugins as $key=>$value) {
		if($value['title'] == '') $title = 'No title'; 
		else $title = $value['title'];
		if($value['exist']) $exist = 'Yes';
		else $exist = 'No';
		if($value['type'] != 'admin') {
			$value['type'] = 'blog';
		}
		if($value['enable'] == '1') {
			$value['enable'] = 'Yes';
		}
	    else $value['enable'] = 'No';
	
		echo "<tr>
			<td>$i</td>
			<td>".urlmanager::makeLink($PARAS,'plugin',$value['id'],'editplugin',$title)."</td>
			<td>".$value['enable']."</td><td>".$value['name']."</td><td>".$value['type']."</td><td>".$value['order']."</td><td>".$value['path']."</td><td>$exist</td><td>".$value['id']."</td><td>".urlmanager::makeLink($PARAS,'delete','plugin-'.$value['id'],'delete','Delete')."</td>
			</tr>";
	$i++;	
	}
	
}
else {
	echo 'No plugin installed';
}
?>
</table>