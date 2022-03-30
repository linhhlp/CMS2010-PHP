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

if($ACTION == 'position') {
	echo '<h3>Sửa position.</h3>';
	if(isset($_POST['title']) && $CONTROL != '') {
		foreach($permission_list as $key=>$value) {
			if($_POST['permission'.$key] == 'on')
				$permission_query .= $key.',';
		}
		$success = $mana->setPosition ($CONTROL,$_POST['title'],$_POST['name'],$_POST['descr'],$_POST['order'],$_POST['type'],$permission_query,$_POST['enable']);
		if($success != FALSE) {
			echo 'Update successfully.';
		}
		else echo '<font color=red>Update Fail.</font>';
	}
	$position = $mana->getPosition($CONTROL);
	echo '<form action="'.urlmanager::makeLink($PARAS,$ACTION,$CONTROL).'" method=POST><table border=0 width=50%>';
	echo '<tr><td width=50%>Title</td><td><input type=text name=title value="'.$position['title'].'"></td></tr>';
	echo '<tr><td width=50%>Name (advanced)</td><td><input type=text name=name value="'.$position['name'].'"></td></tr>';
	echo '<tr><td width=50%>Enable</td><td>
			<select name="enable">
			<option value="1" '.(($position['enable']==1)?'selected="selected"':'').'>Enable</option>
			<option value="0" '.(($position['enable']==0)?'selected="selected"':'').'>Disable</option>
			</select></td></tr>';
	echo '<tr><td width=50%>Type</td><td>
			<select name="type">
			<option value="admin" '.(($position['type']=='admin')?'selected="selected"':'').'>Admin</option>
			<option value="blog" '.(($position['type']!='admin')?'selected="selected"':'').'>Normal</option>
			</select>
			</td></tr>';
	echo '<tr><td width=50%>Order</td><td><input type=text name=order value="'.$position['order'].'"></td></tr>';
	echo '<tr><td width=50%>Description</td><td><input type=text name=descr value="'.$position['descr'].'"></td></tr>';
	echo '<tr><td>Permission</td><td>';
	$permission_position = explode(",",$position['permission']);
	foreach($permission_list as $key=>$value) {
		echo '<input type="checkbox" name="permission'.$key.'" '.(in_array("$key",$permission_position,TRUE)?'checked="checked"':'').' />'.$value.'<br />';
	}
	echo '</td></tr>';
	echo '<tr><td width=50% align=right><input type=submit value="Submit"></td><td><input type=reset value="Reset"></td></tr>';
	echo '</table></form>';
}


elseif($ACTION == 'create_position') {
	echo '<h3>Create new position.</h3>';
	if(isset($_POST['title']) && $CONTROL != '') {
		foreach($permission_list as $key=>$value) {
			if($_POST['permission'.$key] == 'on')
				$permission_query .= $key.',';
		}
		$success = $mana->createPosition ($_POST['title'],$_POST['name'],$_POST['descr'],$_POST['order'],$_POST['type'],$permission_query,$_POST['enable']);
		if($success != FALSE) {
			echo 'Create new position successfully.';
		}
		else echo '<font color=red>Create new position Fail.</font>';
	}
	else {
		echo '<form action="'.urlmanager::makeLink($PARAS,$ACTION,$CONTROL).'" method=POST><table border=0 width=50%>';
		echo '<tr><td width=50%>Title</td><td><input type=text name=title value=""></td></tr>';
		echo '<tr><td width=50%>Name (advanced)</td><td><input type=text name=name value=""></td></tr>';
		echo '<tr><td width=50%>Enable</td><td>
				<select name="enable">
				<option value="1" selected="selected" >Enable</option>
				<option value="0"  >Disable</option>
				</select></td></tr>';
		echo '<tr><td width=50%>Type</td><td>
				<select name="type">
				<option value="admin" >Admin</option>
				<option value="blog" selected="selected">Normal</option>
				</select>
				</td></tr>';
		echo '<tr><td width=50%>Order</td><td><input type=text name=order value=""></td></tr>';
		echo '<tr><td width=50%>Description</td><td><input type=text name=descr value=""></td></tr>';
		echo '<tr><td>Permission</td><td>';
		foreach($permission_list as $key=>$value) {
				echo '<input type="checkbox" name="permission'.$key.'" checked="checked" />'.$value.'<br />';
			}
		echo '<tr><td width=50%><input type=submit value="Submit"></td><td><input type=reset value="Reset"></td></tr>';
		echo '</table></form>';
	}
}
?>