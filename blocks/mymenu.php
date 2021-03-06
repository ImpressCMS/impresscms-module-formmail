<?php
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
function block_FORMMAIL_show()
{
	global $xoopsDB, $xoopsUser;
	$block = array();
	$block['title'] = _MB_FORM_MENU_TITLE;
	$block['content'] = "";
	$result = $xoopsDB->query("SELECT position, indent, itemname, margintop, marginbottom, itemurl, bold, membersonly, mainmenu, status FROM ".$xoopsDB->prefix("formmail_menu")." ORDER BY position");
	while (list($position, $indent, $itemname, $margintop, $marginbottom, $itemurl, $bold, $membersonly, $mainmenu, $status) = $xoopsDB->fetchRow($result)) {
		if ( $status == 1 ) {
			if ($xoopsUser or $membersonly == 0) {
				if ($mainmenu == 0) {
					$block['content'] .= "<table cellspacing='0'><tr><td id='mainmenu'>
						<div style='margin-left: ".$indent."px; margin-right: 0px; margin-top: ".$margintop."px; margin-bottom: ".$marginbottom."px;'>
						<a class='menuMain' href='".$itemurl."'>".$itemname."</a></div></td></tr></table>";
				} else {
					if ($bold == 1) {
						$block['content'] .= "<table cellspacing='0' border='0'><tr><td>
							<div style='margin-left: ".$indent."px; margin-right: 0; margin-top: ".$margintop."px; margin-bottom: ".$marginbottom."px;'>
							<a style='font-weight: bold' href='".$itemurl."'>".$itemname."</a></td></tr></table>";
					} else {
						$block['content'] .= "<table cellspacing='0' border='0'><tr><td>
							<div style='margin-left: ".$indent."px; margin-right: 0; margin-top: ".$margintop."px; margin-bottom: ".$marginbottom."px;'>
							<a style='font-weight: normal' href='".$itemurl."'>".$itemname."</a></td></tr></table>";
					}
				}
			}
		}
	}
	return $block;
}
?>