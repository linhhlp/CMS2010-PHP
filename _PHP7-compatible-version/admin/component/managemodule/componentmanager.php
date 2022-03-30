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

if($ACTION == 'install_component') {
if(in_array($user_info['user_type'],$per_install_global,TRUE) || $per_check == FALSE) {
	list($type,$path) = explode('-',$CONTROL);
	if($CONTROL != '0') {
		if(!empty($_POST)) {
			foreach($permission_list as $key=>$value) {
				if($_POST['permission'.$key] == 'on')
					$permission_query .= $key.',';
			}
			$success = $mana->createComponent ($_POST['title'],$_POST['path'],$_POST['descr'],$_POST['order'],$_POST['type'],$_POST['default'],$permission_query,$_POST['enable']);
			if($success != FALSE) {
				echo 'Create successfully.';
				// Load admin file config of each module.
				if($type == 'admin') $fullpath = ADM.'/'.COM.'/'.$_POST['path'].'/'.$_POST['path'].'.admin.php';
				else $fullpath = COM.'/'.$_POST['path'].'/'.$_POST['path'].'.admin.php';
				if(is_file($fullpath)) {
					$com_params = array();
					$com_params['id'] = $success;
					include($fullpath);
				}
				redirect(urlmanager::makeLink($PARAS,'',''));
			}
			else echo '<font color=red>Create Fail.</font>';
		}
		else {
			$installComponent = $mana->getInstallComponent($type,$path);
			echo '<form action="'.urlmanager::makeLink($PARAS,$ACTION,$CONTROL).'" method=POST>
					<table border=0 width=50%>';
			echo '<tr><td width=50%>Title</td><td><input type=text name=title value="'.$installComponent['title'].'"></td></tr>';
			echo '<tr><td width=50%>Path (advanced)</td><td><input type=text name=path value="'.$installComponent['path'].'"></td></tr>';
			echo '<tr><td width=50%>Enable</td><td>
					<select name="enable">
					<option value="1" selected="selected" >Enable</option>
					<option value="0"  >Disable</option>
					</select></td></tr>';
            echo '<tr><td width=50%>Default</td><td>
					<select name="default">
					<option value="1" >Default</option>
					<option value="0" selected="selected"  >No</option>
					</select></td></tr>';
			echo '<input type="hidden" name="type" value="'.$type.'">';
			echo '<input type="hidden" name="params" value="'.$installComponent['params'].'">';
			echo '<tr><td width=50%>Order</td><td><input type=text name=order value="'.$installComponent['order'].'"></td></tr>';
			echo '<tr><td width=50%>Description</td><td><input type=text name=descr value="'.$installComponent['descr'].'"></td></tr>';
			// Load admin file config of each module.
			
			if($type == 'admin') $fullpath = ADM.'/'.COM.'/'.$installComponent['path'].'/'.$installComponent['path'].'.admin.php';
			else $fullpath = COM.'/'.$installComponent['path'].'/'.$installComponent['path'].'.admin.php';
			if(is_file($fullpath)) {
				$com_params = $installComponent;
				include($fullpath);
			}
			else echo '<tr><td colspan=2><font color=red>Can not find admin file.</font></td></tr>';
			echo '<tr><td>Permission</td><td>';
			foreach($permission_list as $key=>$value) {
				echo '<input type="checkbox" name="permission'.$key.'" checked="checked" />'.$value.'<br />';
			}
			echo '</td></tr>';
			// Submit
			echo '<tr><td width=50%>Ok</td><td><input type=submit value="Submit"><input type=reset value="Reset"></td></tr>';
			echo '</table></form>';
		}
	}
	else {
		$installComponent = $mana->getInstallComponent();
		?>
		<h3>Install new Component.</h3>
		<font color=red >Hint</font>: copy folder module into COMPONENT directory.<br />
		After that, go to control panel and install it.
		<table id="managemodule">
		<tr class="title">
			<td>#</td><td width=30%>Path</td><td>Title</td><td>Author</td><td>Version</td><td>Type</td><td>Install</td>
		</tr>
		<?php
			$i = 1;
			foreach ($installComponent as $key=>$value) {
				if($value['type'] != 'admin') $value['type'] = 'blog';
				echo "<tr>
					<td>$i</td><td>".$value['path']."</td><td>".$value['title']."</td><td>".$value['author']."</td><td>".$value['version']."</td>
					<td>".$value['type']."</td><td>".urlmanager::makeLink($PARAS,$ACTION,$value['type'].'-'.$value['path'],'make new component','install')."</td>
					</tr>";
				$i++;	
				}
			
		?>
		</table>
<?php
	}
	}
else {
	echo '<div class="warning"> Bạn không được phép sử dụng tính năng này </div>';
}
}
elseif($ACTION == 'component') {
	echo '<h3>Sửa component.</h3>';
	if(isset($_POST['title']) && $CONTROL != '') {
		foreach($permission_list as $key=>$value) {
			if($_POST['permission'.$key] == 'on')
				$permission_query .= $key.',';
		}
		$success = $mana->setComponent ($CONTROL,$_POST['title'],$_POST['path'],$_POST['descr'],$_POST['order'],$_POST['type'],$_POST['default'],$permission_query,$_POST['enable']);
		if($success != FALSE) {
			echo 'Update successfully.';
		}
		else echo '<font color=red>Update Fail.</font>';
	}
	$component = $mana->getComponent($CONTROL);
	echo '<form action="'.urlmanager::makeLink($PARAS,$ACTION,$CONTROL).'" method=POST><table border=1 width=50%  align=left>';
	echo '<tr><td width=5%>Title</td><td><input type=text name=title value="'.$component['title'].'"></td></tr>';
	echo '<tr><td width=5%>Path (advanced)</td><td><input type=text name=path value="'.$component['path'].'"></td></tr>';
	echo '<tr><td width=50%>Enable</td><td>
			<select name="enable">
			<option value="1" '.(($component['enable']==1)?'selected="selected"':'').'>Enable</option>
			<option value="0" '.(($component['enable']==0)?'selected="selected"':'').'>Disable</option>
			</select></td></tr>';
    echo '<tr><td width=50%>Default</td><td>
	<select name="default">
	<option value="1" '.(($component['default']==1)?'selected="selected"':'').'>Default</option>
	<option value="0" '.(($component['default']==0)?'selected="selected"':'').'>No</option>
	</select></td></tr>';
	echo '<tr><td width=50%>Type</td><td>
			<select name="type">
			<option value="admin" '.(($component['type']=='admin')?'selected="selected"':'').'>Admin</option>
			<option value="blog" '.(($component['type']!='admin')?'selected="selected"':'').'>Normal</option>
			</select> 
			</td></tr>';
	echo '<tr><td width=50%>Order</td><td><input type=text name=order value="'.$component['order'].'"></td></tr>';
	echo '<tr><td width=50%>Description</td><td><input type=text name=descr value="'.$component['descr'].'"></td></tr>';
	if($component['type'] == 'admin') $fullpath = ADM.'/'.COM.'/'.$component['path'].'/'.$component['path'].'.admin.php';
	else $fullpath = COM.'/'.$component['path'].'/'.$component['path'].'.admin.php';
	if(is_file($fullpath)) {
		$com_params = $component;
		include($fullpath);
	}
	else echo '<tr><td colspan=2><font color=red>Can not find admin file: '.$fullpath.'</font></td></tr>';
	echo '<tr><td>Permission</td><td>';
	$permission_component = explode(",",$component['permission']);
	foreach($permission_list as $key=>$value) {
		echo '<input type="checkbox" name="permission'.$key.'" '.(in_array("$key",$permission_component,TRUE)?'checked="checked"':'').' />'.$value.'<br />';
	}
	echo '</td></tr>';
	echo '<tr><td width=50%>Ok</td><td><input type=submit value="Submit"><input type=reset value="Reset"></td></tr>';
	echo '</table></form>';	
	echo '<br /> <font color=red>Note: If you change Type, so make sure that It is in Exact Folder (Admin Component or Normal Component).</font>';
}

?>