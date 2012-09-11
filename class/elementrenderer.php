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

class FormmailElementRenderer{
	var $_ele;

	function FormmailElementRenderer(&$element){
		$this->_ele =& $element;
	}

	function constructElement($form_ele_id, $admin=false){
		global $xoopsUser, $xoopsModuleConfig;
		$id_form = $this->_ele->getVar('id_form');
		$ele_caption = $this->_ele->getVar('ele_caption', 'e');
		$ele_value = $this->_ele->getVar('ele_value');
		$e = $this->_ele->getVar('ele_type');
		switch ($e){
			case 'text':
				if( !is_object($xoopsUser) ){
					$ele_value[2] = preg_replace('/\{UNAME\}/', '', $ele_value[2]);
					$ele_value[2] = preg_replace('/\{EMAIL\}/', '', $ele_value[2]);
				}elseif( !$admin ){
					$ele_value[2] = preg_replace('/\{UNAME\}/', $xoopsUser->getVar('uname', 'e'), $ele_value[2]);
					$ele_value[2] = preg_replace('/\{EMAIL\}/', $xoopsUser->getVar('email', 'e'), $ele_value[2]);
				}
				$form_ele = new XoopsFormText(
					$ele_caption,
					$form_ele_id,
					$ele_value[0],	//	box width
					$ele_value[1],	//	max width
					$ele_value[2]	//	default value
				);
				break;
			
			case 'textarea':
				$form_ele = new XoopsFormTextArea(
					$ele_caption,
					$form_ele_id,
					$ele_value[0],	//	default value
					$ele_value[1],	//	rows
					$ele_value[2]	//	cols
				);
				break;
			
			case 'select':
				$myts =& MyTextSanitizer::getInstance();
				$selected = array();
				$options = array();
				$opt_count = 1;
				while( $i = each($ele_value[2]) ){
// 					$options[$opt_count] = stripslashes($i['key']);
					$options[$opt_count] = $myts->stripSlashesGPC($i['key']);
					if( $i['value'] > 0 ){
						$selected[] = $opt_count;
					}
					$opt_count++;
				}
				$form_ele = new XoopsFormSelect(
					$ele_caption,
					$form_ele_id,
					$selected,
					$ele_value[0],	//	size
					$ele_value[1]	//	multiple
				);
				$form_ele->addOptionArray($options);
				break;
			
			case 'checkbox':
				$selected = array();
				$options = array();
				$opt_count = 1;
				while( $i = each($ele_value) ){
					$options[$opt_count] = $i['key'];
					if( $i['value'] > 0 ){
						$selected[] = $opt_count;
					}
					$opt_count++;
				}
				switch($xoopsModuleConfig['delimeter']){
					case 'br':
						$form_ele = new XoopsFormElementTray($ele_caption, '<br />');
						while( $o = each($options) ){
							$t =& new XoopsFormCheckBox(
								'',
								$form_ele_id.'[]',
								$selected
							);
							$t->addOption($o['key'], $o['value']);
							$form_ele->addElement($t);
						}
						break;
					default:
						$form_ele = new XoopsFormCheckBox(
							$ele_caption,
							$form_ele_id,
							$selected
						);
						$form_ele->addOptionArray($options);
						break;
				}
				break;
			
			case 'radio':
			case 'yn':
				$selected = '';
				$options = array();
				$opt_count = 1;
				while( $i = each($ele_value) ){
					switch ($e){
						case 'radio':
							$options[$opt_count] = $i['key'];
							break;
						case 'yn':
							$options[$opt_count] = constant($i['key']);
							break;
					}
					if( $i['value'] > 0 ){
						$selected = $opt_count;
					}
					$opt_count++;
				}
				switch($xoopsModuleConfig['delimeter']){
					case 'br':
						$form_ele = new XoopsFormElementTray($ele_caption, '<br />');
						while( $o = each($options) ){
							$t =& new XoopsFormRadio(
								'',
								$form_ele_id,
								$selected
							);
							$t->addOption($o['key'], $o['value']);
							$form_ele->addElement($t);
						}
						break;
					default:
						$form_ele = new XoopsFormRadio(
							$ele_caption,
							$form_ele_id,
							$selected
						);
						$form_ele->addOptionArray($options);
						break;
				}
				break;
			
			default:
				return false;
				break;
		}
		return $form_ele;
	}

}
?>