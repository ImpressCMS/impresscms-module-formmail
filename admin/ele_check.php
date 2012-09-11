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
if( !preg_match("/elements.php/", $_SERVER['PHP_SELF']) ){
	exit("Access Denied");
}
$options = array();
$opt_count = 0;
if( empty($addopt) && !empty($ele_id) ){
	$keys = array_keys($value);
	for( $i=0; $i<count($keys); $i++ ){
		$v = $myts->makeTboxData4PreviewInForm($keys[$i]);
		$options[] = addOption('ele_value['.$opt_count.']', 'checked['.$opt_count.']', $v, 'check', $value[$keys[$i]]);
		$opt_count++;
	}
/*	while( $var = each($value) ){
		$v = $myts->makeTboxData4PreviewInForm($var['key']);
		$t1 = new XoopsFormCheckBox('', 'checked['.$opt_count.']', $var['value']);
		$t1->addOption(1, ' ');
		$t2 = new XoopsFormText('', 'ele_value['.$opt_count.']', 40, 255, $v);
		$t3 = new XoopsFormElementTray('');
		$t3->addElement($t1);
		$t3->addElement($t2);
		$options[] = $t3;
		$opt_count++;
	}	*/
}else{
	while( $v = each($ele_value) ){
		$v['value'] = $myts->makeTboxData4PreviewInForm($v['value']);
		if( !empty($v['value']) ){
	/*		$t1 = new XoopsFormCheckBox('', 'checked['.$opt_count.']', $checked[$v['key']]);
			$t1->addOption(1, ' ');
			$t2 = new XoopsFormText('', 'ele_value['.$opt_count.']', 40, 255, $v['value']);
			$t3 = new XoopsFormElementTray('');
			$t3->addElement($t1);
			$t3->addElement($t2);
			$options[] = $t3;	*/
			$options[] = addOption('ele_value['.$opt_count.']', 'checked['.$opt_count.']', $v['value'], 'check', $checked[$v['key']]);
			$opt_count++;
		}
	}
	$addopt = empty($addopt) ? 2 : $addopt;
	for( $i=0; $i<$addopt; $i++ ){
		$options[] = addOption('ele_value['.$opt_count.']', 'checked['.$opt_count.']');
		$opt_count++;
	}
}
$add_opt = addOptionsTray();
$options[] = $add_opt;
$opt_tray = new XoopsFormElementTray(_AM_FORM_ELE_OPT, '<br />');
$opt_tray->setDescription(_AM_FORM_ELE_OPT_DESC);
for( $i=0; $i<count($options); $i++ ){
	$opt_tray->addElement($options[$i]);
}
$form->addElement($opt_tray);
?>