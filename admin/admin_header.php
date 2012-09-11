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
/**
 *
 * XOOPS module 'FormMail'  by Tom (Malaika System)
 *
 **/

include_once '../../../include/cp_header.php';
include_once '../include/common.php';
// error_reporting(E_ERROR | E_WARNING | E_PARSE); 

function formmail_admin_menu ()
{
	global $xoopsConfig, $xoopsModule;

	// language files
	$language = $xoopsConfig['language'] ;
	if( ! file_exists( XOOPS_ROOT_PATH . '/modules/system/language/'.$language.'/admin.php') ) $language = 'english' ;
	include_once( XOOPS_ROOT_PATH . '/modules/system/language/'.$language.'/admin.php' ) ;
	include_once( XOOPS_ROOT_PATH . '/modules/formmail/language/'.$language.'/modinfo.php' ) ;

	// upper menu
	echo "<h3 style='text-align:left;'>".$xoopsModule->name()."</h3>\n" ;
	echo "<table width='100%' border='0' cellspacing='1' cellpadding='3' class='outer'><tr>";
	echo "<td class='even'><A HREF=\"../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=".$xoopsModule->getVar('mid')."\">"._MD_AM_PREF."</A></td>";
	echo "<td class='even'><A HREF=\"index.php\">"._MI_FORM_ADMENU0."</A></td>";
	echo "<td class='even'><A HREF=\"menu_index.php\">"._MI_FORM_ADMENU1."</A></td>";
	echo "<td class='even'><A HREF=\"configset.php\">"._AM_FORM_ADMENU."</A></td>";
	echo "</tr></table>";
	echo "<br />";
}

?>