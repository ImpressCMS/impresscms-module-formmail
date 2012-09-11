<?php
###############################################################################
##             Formulaire - Information submitting module for XOOPS          ##
##                    Copyright (c) 2003 NS Tai (aka tuff)                   ##
##                       <http://www.brandycoke.com/>                        ##
###############################################################################
##                    XOOPS - PHP Content Management System                  ##
##                       Copyright (c) 2000 XOOPS.org                        ##
##                          <http://www.xoops.org/>                          ##
###############################################################################
##  This program is free software; you can redistribute it and/or modify     ##
##  it under the terms of the GNU General Public License as published by     ##
##  the Free Software Foundation; either version 2 of the License, or        ##
##  (at your option) any later version.                                      ##
##                                                                           ##
##  You may not change or alter any portion of this comment or credits       ##
##  of supporting developers from this source code or any supporting         ##
##  source code which is considered copyrighted (c) material of the          ##
##  original comment or credit authors.                                      ##
##                                                                           ##
##  This program is distributed in the hope that it will be useful,          ##
##  but WITHOUT ANY WARRANTY; without even the implied warranty of           ##
##  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            ##
##  GNU General Public License for more details.                             ##
##                                                                           ##
##  You should have received a copy of the GNU General Public License        ##
##  along with this program; if not, write to the Free Software              ##
##  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA ##
###############################################################################
##  Author of this file: NS Tai (aka tuff)                                   ##
##  URL: http://www.brandycoke.com/                                          ##
##  Project: Formulaire                                                      ##
###############################################################################
include("admin_header.php");
if (isset($_GET['id_form'])) {
	$id_form = $_GET['id_form'];
}else {
	redirect_header("index.php", 1, "ERROR!!!!");
	exit();
}

$sql=sprintf("SELECT desc_form FROM ".$xoopsDB->prefix("formmail_id")." WHERE id_form=$id_form");
$res = $xoopsDB->query ( $sql ) or die('Erreur SQL !<br>'.$requete.'<br>'.$xoopsDB->error());
if ( $res ) {
	while ( $row = $xoopsDB->fetchRow ( $res ) ) {
		$title = $row[0];
	}
}
if(!empty($_POST['op'])){
	$op = $_POST['op'];
}else{
	$op = "";
}

if( $op != 'save' ){
	xoops_cp_header();
	// admin menu
	formmail_admin_menu ();

	echo '<form action="ele_index.php?id_form='.$id_form.'" method="post">
	<table class="outer" cellspacing="1" width="100%">
	<tr><th align="center">'._AM_FORM_FORM.$title.'</th></tr>
	</table>';
	echo '<table class="outer" cellspacing="1" width="100%">
	<tr><th align="center">'._AM_FORM_ELE_CREATE.'</th></tr>
	<tr><td class="even"><li><a href="elements.php?id_form='.$id_form.'&op=edit&amp;ele_type=text">'._AM_FORM_ELE_TEXT.'</a></td></tr>
	<tr><td class="even"><li><a href="elements.php?id_form='.$id_form.'&op=edit&amp;ele_type=textarea">'._AM_FORM_ELE_TAREA.'</a></td></tr>
	<tr><td class="even"><li><a href="elements.php?id_form='.$id_form.'&op=edit&amp;ele_type=select">'._AM_FORM_ELE_SELECT.'</a></td></tr>
	<tr><td class="even"><li><a href="elements.php?id_form='.$id_form.'&op=edit&amp;ele_type=checkbox">'._AM_FORM_ELE_CHECK.'</a></td></tr>
	<tr><td class="even"><li><a href="elements.php?id_form='.$id_form.'&op=edit&amp;ele_type=radio">'._AM_FORM_ELE_RADIO.'</a></td></tr>
	<tr><td class="even"><li><a href="elements.php?id_form='.$id_form.'&op=edit&amp;ele_type=yn">'._AM_FORM_ELE_YN.'</a></td></tr>
	</table>';
	echo ' <table class="outer" cellspacing="1" width="100%">
		<tr><th>'._AM_FORM_ELE_CAPTION.'</th>
			<th>'._AM_FORM_ELE_DEFAULT.'</th>
			<th>'._AM_FORM_ELE_REQ.'</th>
			<th>'._AM_FORM_ELE_ORDER.'</th>
			<th>'._AM_FORM_ELE_DISPLAY.'</th>
			<th colspan="3">&nbsp;</th></tr>';
	echo '<tr><td class="even">'._AM_FORM_LANG_NAME.'</td>
			<td class="even"><input type="text" name="usersName" id="usersName" size="'.$xoopsModuleConfig['t_width'].'" maxlength="'.$xoopsModuleConfig['t_max'].'" value="{UNAME}" /></td>
			<td class="even" align="center"><input type="checkbox" name="usersName_req" value="1" checked="checked" disabled="disabled" /></td>
			<td class="even" align="center">-</td>
			<td class="even" align="center">'._AM_FORM_ELE_DISPLAY.'</td>
			<td class="even" align="center">-</td>
			<td class="even" align="center">-</td>
			<td class="even" align="center">-</td>
			</tr>';
	echo '<tr><td class="even">'._AM_FORM_LANG_EMAIL.'</td>
			<td class="even"><input type="text" name="usersEmail" id="usersEmail" size="'.$xoopsModuleConfig['t_width'].'" maxlength="'.$xoopsModuleConfig['t_max'].'" value="{EMAIL}" /></td>
			<td class="even" align="center"><input type="checkbox" name="usersEmail_req" value="1" checked="checked" disabled="disabled" /></td>
			<td class="even" align="center">-</td>
			<td class="even" align="center">'._AM_FORM_ELE_DISPLAY.'</td>
			<td class="even" align="center">-</td>
			<td class="even" align="center">-</td>
			<td class="even" align="center">-</td>
			</tr>';

	$criteria = new Criteria(1,1);
	$criteria->setSort('ele_order');
	$criteria->setOrder('ASC');
	
	$elements =& $formmail_mgr->getObjects($criteria,$id_form);
	foreach( $elements as $i ){
		$id = $i->getVar('ele_id');
		$renderer =& new FormmailElementRenderer($i);
		$ele_value =& $renderer->constructElement('ele_value['.$id.']', true);
		$ele_type = $i->getVar('ele_type');
		$req = $i->getVar('ele_req');
		$check_req = new XoopsFormCheckBox('', 'ele_req['.$id.']', $req);
		$check_req->addOption(1, ' ');
		if( $ele_type == 'checkbox' || $ele_type == 'radio' || $ele_type == 'yn' || $ele_type == 'select' ){
			$check_req->setExtra('disabled="disabled"');
		}
		$order = $i->getVar('ele_order');
		$text_order = new XoopsFormText('', 'ele_order['.$id.']', 3, 2, $order);
		$display = $i->getVar('ele_display');
		$check_display = new XoopsFormCheckBox('', 'ele_display['.$id.']', $display);
		$check_display->addOption(1, ' ');
		$hidden_id = new XoopsFormHidden('ele_id[]', $id);
		echo '<tr>';
		echo '<td class="even">'.$i->getVar('ele_caption')."</td>\n";
		echo '<td class="even">'.$ele_value->render()."</td>\n";
		echo '<td class="even" align="center">'.$check_req->render()."</td>\n";
		echo '<td class="even" align="center">'.$text_order->render()."</td>\n";
		echo '<td class="even" align="center">'.$check_display->render().$hidden_id->render()."</td>\n";
		echo '<td class="even" align="center"><a href="elements.php?id_form='.$id_form.'&op=edit&amp;ele_id='.$id.'">'._EDIT.'</a></td>';
		echo '<td class="even" align="center"><a href="elements.php?id_form='.$id_form.'&op=edit&amp;ele_id='.$id.'&clone=1">'._CLONE.'</a></td>';
		echo '<td class="even" align="center"><a href="elements.php?id_form='.$id_form.'&op=delete&amp;ele_id='.$id.'">'._DELETE.'</a></td>';
		echo '</tr>';
	}
	$submit = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
	echo '<tr><td class="foot">&nbsp;</td>
			<td class="foot" colspan="7">'.$submit->render().'</tr></table>';
	$hidden_op = new XoopsFormHidden('op', 'save');
	echo $hidden_op->render();
	echo '</form>';

// op = save
}else{
    xoops_cp_header();
	extract($_POST);
	$error = '';
	foreach( $ele_id as $id ){
		$element =& $formmail_mgr->get($id);
		$req = !empty($ele_req[$id]) ? 1 : 0;
		$element->setVar('ele_req', $req);
		$order = !empty($ele_order[$id]) ? intval($ele_order[$id]) : 0;
		$element->setVar('ele_order', $order);
		$display = !empty($ele_display[$id]) ? 1 : 0;
		$element->setVar('ele_display', $display);
		$type = $element->getVar('ele_type');
		$value = $element->getVar('ele_value');
		switch($type){
			case 'text':
				$value[2] = $ele_value[$id];
				break;
			case 'textarea':
				$value[0] = $ele_value[$id];
				break;
			case 'select':
				$new_vars = array();
				$opt_count = 1;
				if( is_array($ele_value[$id]) ){
					while( $j = each($value[2]) ){
						if( in_array($opt_count, $ele_value[$id]) ){
							$new_vars[$j['key']] = 1;
						}else{
							$new_vars[$j['key']] = 0;
						}
						$opt_count++;
					}
				}else{
					if( count($value[2]) > 1 ){
						while( $j = each($value[2]) ){
							if( $opt_count == $ele_value[$id] ){
								$new_vars[$j['key']] = 1;
							}else{
								$new_vars[$j['key']] = 0;
							}
							$opt_count++;
						}
					}else{
						while( $j = each($value[2]) ){
							if( !empty($ele_value[$id]) ){
								$new_vars = array($j['key']=>1);
							}else{
								$new_vars = array($j['key']=>0);
							}
						}
					}
				}
				
				$value[2] = $new_vars;
				break;
			case 'checkbox':
// 				$myts =& MyTextSanitizer::getInstance();
				$new_vars = array();
				$opt_count = 1;
				if( is_array($ele_value[$id]) ){
					while( $j = each($value) ){
						if( in_array($opt_count, $ele_value[$id]) ){
							$new_vars[$j['key']] = 1;
						}else{
							$new_vars[$j['key']] = 0;
						}
						$opt_count++;
					}
				}else{
					if( count($value) > 1 ){
						while( $j = each($value) ){
							$new_vars[$j['key']] = 0;
						}
					}else{
						while( $j = each($value) ){
							if( !empty($ele_value[$id]) ){
								$new_vars = array($j['key']=>1);
							}else{
								$new_vars = array($j['key']=>0);
							}
						}
					}
				}
				$value = $new_vars;
				break;
			case 'radio':
			case 'yn':
				$new_vars = array();
				$i = 1;
				while( $j = each($value) ){
					if( $ele_value[$id] == $i ){
						$new_vars[$j['key']] = 1;
					}else{
						$new_vars[$j['key']] = 0;
					}
					$i++;
				}
				$value = $new_vars;
				break;
			default:
				break;
		}
		$element->setVar('ele_value', $value);
		$element->setVar('id_form', $id_form);
		if( !$formmail_mgr->insert($element) ){
			$error .= $element->getHtmlErrors();
		}
	}
	if( empty($error) ){
		redirect_header("index.php", 0, _AM_FORM_DBUPDATED);
	}else{
		xoops_cp_header();
		echo error;
	}
}

echo '<center><a href="../index.php?id_form='.$id_form.'" target="_blank">'._AM_FORM_FORM . $title.'</a></center>';
include 'footer.php';
xoops_cp_footer();
?>