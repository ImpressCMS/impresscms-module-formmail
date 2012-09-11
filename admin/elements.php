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
/**
 *
 * XOOPS module 'FormMail'  by Tom (Malaika System)
 *
 **/

include("admin_header.php");

//extract($_GET);
$id_form = isset( $_GET['id_form'] ) ? intval($_GET['id_form']) : '' ;
$op = isset( $_GET['op'] ) ? $_GET['op'] : '' ;
$ele_type = isset( $_GET['ele_type'] ) ? $_GET['ele_type'] : '' ;
$ele_id = isset( $_GET['ele_id'] ) ? $_GET['ele_id'] : '' ;
$clone = isset( $_GET['clone'] ) ? $_GET['clone'] : '' ;
extract($_POST);

$sql=sprintf("SELECT desc_form FROM ".$xoopsDB->prefix("formmail_id")." WHERE id_form=$id_form");
$res = $xoopsDB->query ( $sql ) or die('Erreur SQL !<br>'.$requete.'<br>'.$xoopsDB->error());
if ( $res ) {
	while ( $row = $xoopsDB->fetchRow ( $res ) ) {
		$title = $row[0];
	}
}
if( $_POST['submit'] == _AM_FORM_ELE_ADD_OPT_SUBMIT && intval($_POST['addopt']) > 0 ){
	$op = 'edit';
}

$ele_id = !empty($ele_id) ? intval($ele_id) : 0;
$myts =& MyTextSanitizer::getInstance();

switch($op){
	case 'edit':
		xoops_cp_header();
		// admin menu
		formmail_admin_menu ();
		if( !empty($ele_id) ){
			$element =& $formmail_mgr->get($ele_id);
			$ele_type = $element->getVar('ele_type');
			$form_title = $clone ? _AM_FORM_ELE_CREATE : sprintf(_AM_FORM_ELE_EDIT, $element->getVar('ele_caption'));
		}else{
			$element =& $formmail_mgr->create();
			$form_title = _AM_FORM_ELE_CREATE;
		}
		$form = new XoopsThemeForm($form_title, 'form_ele', 'elements.php?id_form='.$id_form.'');
		if( empty($addopt) ){
			$ele_caption = $clone ? sprintf(_AM_FORM_COPIED, $element->getVar('ele_caption', 'f')) : $element->getVar('ele_caption', 'f');
			$ele_caption = new XoopsFormText(_AM_FORM_ELE_CAPTION, 'ele_caption', 50, 255, $ele_caption);
			$value = $element->getVar('ele_value', 'f');
		}else{
			$ele_caption = $myts->makeTboxData4PreviewInForm($ele_caption);
			$ele_caption = new XoopsFormText(_AM_FORM_ELE_CAPTION, 'ele_caption', 50, 255, $ele_caption);
		}
		$form->addElement($ele_caption, 1);
		switch($ele_type){
			case 'text':
				include 'ele_text.php';
				$req = true;
				break;
			case 'textarea':
				include 'ele_tarea.php';
				$req = true;
				break;
			case 'select':
				include 'ele_select.php';
				break;
			case 'checkbox':
				include 'ele_check.php';
				break;
			case 'radio':
				include 'ele_radio.php';
				break;
			case 'yn':
				include 'ele_yn.php';
				break;
		}
		if( !empty($req) ){
			$ele_req = new XoopsFormCheckBox(_AM_FORM_ELE_REQ, 'ele_req', $element->getVar('ele_req'));
			$ele_req->addOption(1, ' ');
		}
		$form->addElement($ele_req);
		$display = !empty($ele_id) ? $element->getVar('ele_display') : 1;
		$ele_display = new XoopsFormCheckBox(_AM_FORM_ELE_DISPLAY, 'ele_display', $display);
		$ele_display->addOption(1, ' ');
		$form->addElement($ele_display);
		$order = !empty($ele_id) ? $element->getVar('ele_order') : 0;
		$ele_order = new XoopsFormText(_AM_FORM_ELE_ORDER, 'ele_order', 3, 2, $order);
		$form->addElement($ele_order);
		$submit = new XoopsFormButton('', 'submit', _AM_FORM_SAVE, 'submit');
		$cancel = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
		$cancel->setExtra('onclick="javascript:history.go(-1);"');
		$tray = new XoopsFormElementTray('');
		$tray->addElement($submit);
		$tray->addElement($cancel);
		$form->addElement($tray);
		$hidden_op = new XoopsFormHidden('op', 'save');
		$hidden_type = new XoopsFormHidden('ele_type', $ele_type);
		$form->addElement($hidden_op);
		$form->addElement($hidden_type);
		if( !empty($ele_id) && !$clone ){
			$hidden_id = new XoopsFormHidden('ele_id', $ele_id);
			$form->addElement($hidden_id);
		}
		$form->display();
		break;
	case 'delete':
		if( empty($ele_id) ){
			redirect_header("ele_index.php?id_form=$id_form", 0, _AM_FORM_ELE_SELECT_NONE);
			exit();
		}
		if( empty($_POST['ok']) ){
			xoops_cp_header();
			xoops_confirm(array('op' => 'delete', 'ele_id' => $ele_id, 'ok' => 1), 'elements.php?id_form='.$id_form.'', _AM_FORM_ELE_CONFIRM_DELETE);
		}else{
			$element =& $formmail_mgr->get($ele_id);
			$formmail_mgr->delete($element);
			redirect_header("ele_index.php?id_form=$id_form", 0, _AM_FORM_DBUPDATED);
			exit();
		}
		break;
	case 'save':
		if( !empty($ele_id) ){
			$element =& $formmail_mgr->get($ele_id);
		}else{
			$element =& $formmail_mgr->create();
		}
		$element->setVar('ele_caption', $ele_caption);
		$req = !empty($ele_req) ? 1 : 0;
		$element->setVar('ele_req', $req);
		$order = empty($ele_order) ? 0 : intval($ele_order);
		$element->setVar('ele_order', $order);
		$display = !empty($ele_display) ? 1 : 0;
		$element->setVar('ele_display', $display);
		$element->setVar('ele_type', $ele_type);
		switch($ele_type){
			case 'text':
				$value = array();
				$value[] = !empty($ele_value[0]) ? intval($ele_value[0]) : $xoopsModuleConfig['t_width'];
				$value[] = !empty($ele_value[1]) ? intval($ele_value[1]) : $xoopsModuleConfig['t_max'];
				$value[] = $ele_value[2];
				break;
			case 'textarea':
				$value = array();
				$value[] = $ele_value[0];
				if( intval($ele_value[1]) != 0 ){
					$value[] = intval($ele_value[1]);
				}else{
					$value[] = $xoopsModuleConfig['ta_rows'];
				}
				if( intval($ele_value[2]) != 0 ){
					$value[] = intval($ele_value[2]);
				}else{
					$value[] = $xoopsModuleConfig['ta_cols'];
				}
				break;
			case 'select':
				$value = array();
				$value[0] = $ele_value[0]>1 ? intval($ele_value[0]) : 1;
				$value[1] = !empty($ele_value[1]) ? 1 : 0;
				$v2 = array();
				$multi_flag = 1;
				while( $v = each($ele_value[2]) ){
					if( !empty($v['value']) ){
						if( $value[1] == 1 || $multi_flag ){
							if( $checked[$v['key']] == 1 ){
								$check = 1;
								$multi_flag = 0;
							}else{
								$check = 0;
							}
						}else{
							$check = 0;
						}
						$v2[$v['value']] = $check;
					}
				}
				$value[2] = $v2;
				break;
			case 'checkbox':
				$value = array();
				while( $v = each($ele_value) ){
					if( !empty($v['value']) ){
						if( $checked[$v['key']] == 1 ){
							$check = 1;
						}else{
							$check = 0;
						}
						$value[$v['value']] = $check;
					}
				}
				break;
			case 'radio':
				$value = array();
				while( $v = each($ele_value) ){
					if( !empty($v['value']) ){
						if( $checked == $v['key'] ){
							$value[$v['value']] = 1;
						}else{
							$value[$v['value']] = 0;
						}
					}
				}
				break;
			case 'yn':
				$value = array();
				if( $ele_value == '_NO' ){
					$value = array('_YES'=>0,'_NO'=>1);
				}else{
					$value = array('_YES'=>1,'_NO'=>0);
				}
				break;
		}
		$element->setVar('ele_value', $value);
		$element->setVar('id_form', $id_form);
		if( !$formmail_mgr->insert($element) ){
			xoops_cp_header();
			// admin menu
			formmail_admin_menu ();
			echo $element->getHtmlErrors();
		}else{
			redirect_header("ele_index.php?id_form=$id_form", 0, _AM_FORM_DBUPDATED);
			exit();
		}
	break;
/*	default:
		xoops_cp_header();
		// admin menu
		formmail_admin_menu ();


		echo "<h4>"._AM_FORM_ELE_CREATE."</h4>
		<ul>
		<li><a href='elements.php?op=edit&amp;ele_type=text'>"._AM_FORM_ELE_TEXT."</a></li>
		<li><a href='elements.php?op=edit&amp;ele_type=textarea'>"._AM_FORM_ELE_TAREA."</a></li>
		<li><a href='elements.php?op=edit&amp;ele_type=select'>"._AM_FORM_ELE_SELECT."</a></li>
		<li><a href='elements.php?op=edit&amp;ele_type=checkbox'>"._AM_FORM_ELE_CHECK."</a></li>
		<li><a href='elements.php?op=edit&amp;ele_type=radio'>"._AM_FORM_ELE_RADIO."</a></li>
		<li><a href='elements.php?op=edit&amp;ele_type=yn'>"._AM_FORM_ELE_YN."</a></li>
		</ul>"
		;
	break;*/
}
include 'footer.php';
xoops_cp_footer();


function addOption($id1, $id2, $text, $type='check', $checked=null){
	$d = new XoopsFormText('', $id1, 40, 255, $text);
	if( $type == 'check' ){
		$c = new XoopsFormCheckBox('', $id2, $checked);
		$c->addOption(1, ' ');
	}else{
		$c = new XoopsFormRadio('', 'checked', $checked);
		$c->addOption($id2, ' ');
	}
	$t = new XoopsFormElementTray('');
	$t->addElement($c);
	$t->addElement($d);
	return $t;
}

function addOptionsTray(){
	$t = new XoopsFormText('', 'addopt', 3, 2);
	$l = new XoopsFormLabel('', sprintf(_AM_FORM_ELE_ADD_OPT, $t->render()));
	$b = new XoopsFormButton('', 'submit', _AM_FORM_ELE_ADD_OPT_SUBMIT, 'submit');
	$r = new XoopsFormElementTray('');
	$r->addElement($l);
	$r->addElement($b);
	return $r;
}
?>