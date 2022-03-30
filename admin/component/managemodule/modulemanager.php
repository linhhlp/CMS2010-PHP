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

echo  '<br />';
if($ACTION == 'module') {
	echo '<h3>Sửa module.</h3>';
	if(isset($_POST['title']) && $CONTROL != '') {
		foreach($permission_list as $key=>$value) {
			if($_POST['permission'.$key] == 'on')
				$permission_query .= $key.',';
		}
		$success = $mana->setModule ($CONTROL,$_POST['title'],$_POST['name'],$_POST['id_pos'],$_POST['descr'],$_POST['order'],$_POST['path'],$_POST['type'],$permission_query,$_POST['enable']);
		if($success != FALSE) {
			echo 'Update successfully.';
		}
		else echo '<font color=red>Update Fail.</font>';
	}
	$module = $mana->getModule($CONTROL);
	echo '<form action="'.urlmanager::makeLink($PARAS,$ACTION,$CONTROL).'" method=POST><table border=1 width=50%  align=left>';
	echo '<tr><td width=50%>Title</td><td><input type=text name=title value="'.$module['title'].'"></td></tr>';
	echo '<tr><td width=50%>Name (advanced)</td><td><input type=text name=name value="'.$module['name'].'"></td></tr>';
	echo '<tr><td width=50%>Enable</td><td>
			<select name="enable">
			<option value="1" '.(($module['enable']==1)?'selected="selected"':'').'>Enable</option>
			<option value="0" '.(($module['enable']==0)?'selected="selected"':'').'>Disable</option>
			</select></td></tr>';
	echo '<tr><td width=50%>Type</td><td>
			<select name="type">
			<option value="admin" '.(($module['type']=='admin')?'selected="selected"':'').'>Admin</option>
			<option value="blog" '.(($module['type']!='admin')?'selected="selected"':'').'>Normal</option>
			</select> 
			</td></tr>';
	echo '<tr><td width=50%>Order</td><td><input type=text name=order value="'.$module['order'].'"></td></tr>';
	echo '<tr><td width=50%>Position</td><td>
			<select name="id_pos">';
			$positions = $mana->getPosition();
			foreach ($positions as $key=>$value) {
				echo '<option value="'.$value['id'].'" '.(($value['id']==$module['id_pos'])?'selected="selected"':'').'>'.$value['title'].'</option>';
			}
	echo '	
			</select>
		</td></tr>';
	echo '<tr><td width=50%>Path</td><td><input type=text name=path value="'.$module['path'].'"></td></tr>';
	echo '<tr><td width=50%>Description</td><td><input type=text name=descr value="'.$module['descr'].'"></td></tr>';
	if($module['type'] == 'admin') $fullpath = ADM.'/'.MOD.'/'.$module['path'].'/'.$module['name'].'.admin.php';
	else $fullpath = MOD.'/'.$module['path'].'/'.$module['name'].'.admin.php';
	if(is_file($fullpath)) {
		$mod_params = $module;
		include($fullpath);
	}
	else echo '<tr><td colspan=2><font color=red>Can not find admin file.</font></td></tr>';
	echo '<tr><td>Permission</td><td>';
	$permission_module = explode(",",$module['permission']);
	foreach($permission_list as $key=>$value) {
		echo '<input type="checkbox" name="permission'.$key.'" '.(in_array("$key",$permission_module,TRUE)?'checked="checked"':'').' />'.$value.'<br />';
	}
	echo '</td></tr>';
	echo '<tr><td width=50%>Ok</td><td><input type=submit value="Submit"><input type=reset value="Reset"></td></tr>';
	echo '</table></form>';	
	echo '<br /> <font color=red>Note: If you change Type, so make sure that It is in Exact Folder (Admin module or Normal Module).</font>';
}
elseif($ACTION == 'install_module') {
if(in_array($user_info['user_type'],$per_install_global,TRUE)  || $per_check == FALSE ) {
	list($type,$path) = explode('-',$CONTROL);
	if($CONTROL != '0') {
		if(!empty($_POST)) {
			foreach($permission_list as $key=>$value) {
				if($_POST['permission'.$key] == 'on')
					$permission_query .= $key.',';
			}
			$success = $mana->createModule ($_POST['title'],$_POST['name'],$_POST['id_pos'],$_POST['descr'],$_POST['order'],$_POST['path'],$_POST['type'],$permission_query,$_POST['enable']);
			if($success != FALSE) {
				echo 'Create successfully.';
				// Load admin file config of each module.
				if($type == 'admin') $fullpath = ADM.'/'.MOD.'/'.$_POST['path'].'/'.$_POST['name'].'.admin.php';
				else $fullpath = MOD.'/'.$_POST['path'].'/'.$_POST['name'].'.admin.php';
				if(is_file($fullpath)) {
					$mod_params = array();
					$mod_params['id'] = $success;
					include($fullpath);
				}
				redirect(urlmanager::makeLink($PARAS,'',''));
			}
			else echo '<font color=red>Create Fail.</font>';
		}
		else {
			$installModule = $mana->getInstallModule($type,$path);
			echo '<form action="'.urlmanager::makeLink($PARAS,$ACTION,$CONTROL).'" method=POST>
					<table border=0 width=50%>';
			echo '<tr><td width=50%>Title</td><td><input type=text name=title value="'.$installModule['title'].'"></td></tr>';
			echo '<tr><td width=50%>Name (advanced)</td><td><input type=text name=name value="'.$installModule['name'].'"></td></tr>';
			echo '<tr><td width=50%>Enable</td><td>
					<select name="enable">
					<option value="1" selected="selected" >Enable</option>
					<option value="0"  >Disable</option>
					</select></td></tr>';
			echo '<input type="hidden" name="type" value="'.$type.'">';
			echo '<input type="hidden" name="params" value="'.$installModule['params'].'">';
			echo '<tr><td width=50%>Order</td><td><input type=text name=order value="'.$installModule['order'].'"></td></tr>';
			echo '<tr><td width=50%>Position</td><td>
					<select name="id_pos">';
					$positions = $mana->getPosition();
					foreach ($positions as $key=>$value) {
						//if($value['type'] == $type) 
						{
							echo '<option value="'.$value['id'].'" >'.$value['name'].'</option>';
						}
					}
			echo '	
					</select>
				</td></tr>';
			echo '<tr><td width=50%>Path</td><td><input type=text name=path value="'.$installModule['path'].'"></td></tr>';
			echo '<tr><td width=50%>Description</td><td><input type=text name=descr value="'.$installModule['descr'].'"></td></tr>';
			// Load admin file config of each module.
			
			if($type == 'admin') $fullpath = ADM.'/'.MOD.'/'.$installModule['path'].'/'.$installModule['name'].'.admin.php';
			else $fullpath = MOD.'/'.$installModule['path'].'/'.$installModule['name'].'.admin.php';
			if(is_file($fullpath)) {
				$mod_params = $installModule;
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
		$installModule = $mana->getInstallModule();
		?>
		<h3>Install new module.</h3>
		<font color=red >Hint</font>: copy folder module into MODULE directory.<br />
		After that, go to control panel and install it.
		<table id="managemodule">
		<tr class="title">
			<td>#</td><td width=30%>Path</td><td>Title</td><td>Name</td><td>Author</td><td>Version</td><td>Type</td><td>Install</td>
		</tr>
		<?php
			$i = 1;
			foreach ($installModule as $key=>$value) {
				if($value['type'] != 'admin') $value['type'] = 'blog';
				echo "<tr>
					<td>$i</td><td>".$value['path']."</td><td>".$value['title']."</td><td>".$value['name']."</td><td>".$value['author']."</td><td>".$value['version']."</td>
					<td>".$value['type']."</td><td>".urlmanager::makeLink($PARAS,'install_module',$value['type'].'-'.$value['path'],'make new module','install')."</td>
					</tr>";
				$i++;	
				}
			
		?>
		</table>
<?php
	}
} else {
	echo '<div class="warning"> Bạn không được phép sử dụng tính năng này </div>';
}
}
?>