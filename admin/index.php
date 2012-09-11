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

include_once("admin_header.php");
include_once '../../../include/cp_header.php';
//include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
//include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
$myts =& MyTextSanitizer::getInstance();
//include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
//$eh = new ErrorHandler;

xoops_cp_header();

// admin menu
formmail_admin_menu ();

// form list

echo '<form action="form_index.php" method="post">';
echo '<table class="outer" cellspacing="1" width="100%">
<tr>
  <th colspan="9">'._AM_FORM_LIST_FORM.'</th>
</tr>
<tr>
  <td class="head" rowspan="2" align="center">'._AM_FORM_ID.'</td>
  <td class="head" rowspan="2" align="center">'._AM_FORM_ELE_ORDER.'</td>
  <td class="head" rowspan="2" align="center">'._AM_FORM_ELE_DISPLAY.'</td>
  <td class="head" rowspan="2" align="center">'._AM_FORM_TITLE_FORM.'</td>
  <td class="head" rowspan="2" align="center">'._AM_FORM_SENDTO.'</td>
  <td class="head" rowspan="2" align="center">'._AM_FORM_EXPE2.'</td>
  <td class="head" colspan="2" align="center">'._AM_FORM_EDIT.'</td>
  <td class="head" rowspan="2" align="center">'._AM_FORM_DELETE.'</td>
</tr>
<tr>
  <td class="head" align="center">'._AM_FORM_EDIT_FORM.'</td>
  <td class="head" align="center">'._AM_FORM_EDIT_ELE.'</td>
</tr>';

$sql="SELECT distinct id_form, desc_form, admin, groupe, email, expe, form_order, form_req FROM ".$xoopsDB->prefix("formmail_id")." ORDER by form_order";
$res = $xoopsDB->query ( $sql );
if ( $res ) {
	while ( list ($id_form, $desc_form, $admin, $groupe, $email, $expe, $form_order, $form_req) = $xoopsDB->fetchRow ( $res ) ) {
		if ($admin == 'on') {
			$sendto = _AM_FORM_ADMIN ;
		}else {
			$sendto = $email;
			$sql2 = "SELECT name FROM ".$xoopsDB->prefix("groups") ." WHERE groupid=$groupe";
			$res2 = $xoopsDB->query($sql2);
			list($group_name) = $xoopsDB->fetchRow($res2);
			$sendto .= " ".$group_name;
		}
/*		if ($form_req == 'on') {
			$form_req = _AM_FORM_ELE_DISPLAY;
		}else {
			$form_req = "--";
		}
		if ($expe == 'on') {
			$expe = _AM_FORM_SEND;
		}else {
			$expe = "--";
		}
		echo '<tr>
			<td class="odd">'.$id_form.'</td>
			<td class="odd" align="center">'.$form_order.'</td>
			<td class="odd" align="center">'.$form_req.'</td>
			<td class="odd"><a href="'.XOOPS_URL.'/modules/formmail/index.php?id_form='.$id_form.'" target="_blank">'.$desc_form.'</a></td>
			<td class="odd" align="center">'.$sendto.'</td>
			<td class="odd" align="center"> '.$expe.' </td>
			<td class="odd" align="center"><a href="mail_index.php?id_form='.$id_form.'">'._AM_FORM_EDIT.'</a></td>
			<td class="odd" align="center"><a href="ele_index.php?op=modform&id_form='.$id_form.'">'._AM_FORM_EDIT.'</a></td>
			<td class="odd" align="center"><a href="form_index.php?op=delform&id_form='.$id_form.'">'._AM_FORM_DELETE.'</a></td>
			</tr>';
*/
		echo '<tr>
			<td class="odd">'.$id_form.'</td>';

		echo '<td class="odd" align="center"><input type="hidden" name="id[]" value="'.$id_form.'" />
				<input type="text" name="form_order['.$id_form.']" size="2" maxlength="2" value="'.$form_order.'"/></td>';

		echo '<td class="odd" align="center">';
		if ($form_req == 'on') {
			echo '<input type="checkbox" name="form_req['.$id_form.']" checked>';
		}else {
			echo '<input type="checkbox" name="form_req['.$id_form.']">';
		}
		echo '</td>';

		echo '<td class="odd"><a href="'.XOOPS_URL.'/modules/formmail/index.php?id_form='.$id_form.'" target="_blank">'.$desc_form.'</a></td>
			<td class="odd" align="center">'.$sendto.'</td>';

		echo '<td class="odd" align="center">';
		if ($expe == 'on') {
			echo '<input type="checkbox" name="expe['.$id_form.']" checked>';
		}else {
			echo '<input type="checkbox" name="expe['.$id_form.']">';
		}
		echo '</td>';

		echo '<td class="odd" align="center"><a href="mail_index.php?id_form='.$id_form.'">'._AM_FORM_EDIT.'</a></td>
			<td class="odd" align="center"><a href="ele_index.php?op=modform&id_form='.$id_form.'">'._AM_FORM_EDIT.'</a></td>
			<td class="odd" align="center"><a href="form_index.php?op=delform&id_form='.$id_form.'">'._AM_FORM_DELETE.'</a></td>
			</tr>';
	}
}
//echo '</table><br />';
// submit
$submit = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
echo '<tr><td class="foot" colspan="9">'.$submit->render().'</td></tr></table>';
$hidden_op = new XoopsFormHidden('op', 'update');
echo $hidden_op->render();
echo '</form>';

	
// add new form
echo '<form action="form_index.php" method="post">
<table class="outer" cellspacing="1" width="100%">
<tr>
  <th colspan="4">'._AM_FORM_CREAT_FORM.'</th>
</tr>';

// send to
echo '<tr>
  <td class="head" rowspan="2">'._AM_FORM_SENDTO.'</td>
  <td class="head">'._AM_FORM_ADMIN.'</td>
  <td class="odd" colspan="2"> <input name="admin" type="checkbox" id="admin"> ('._AM_FORM_ADMIN_DESC.') </td>
</tr>
<tr>
  <td class="head">'._AM_FORM_OTHER.'</td>
  <td class="odd" align="center">'._AM_FORM_EMAIL.'<br /><input maxlength="255" size="30" id="email" name="email" type="text"></td>';
// group list
$m = 0;
$sql2 = "SELECT groupid,name FROM ".$xoopsDB->prefix("groups");
$res2 = $xoopsDB->query ( $sql2 );
if ( $res2 ) {
	$tab[$m] = 0;
	$tab2[$m] = " ----- ";
	$m++;
	while ( $row = $xoopsDB->fetchArray ( $res2 ) ) {
	    $tab[$m] = $row['groupid'];
	    $tab2[$m] = $row['name'];
	    $m++;
	}
}
echo '<td class="odd" align="center">'._AM_FORM_GROUP.'<br /><select name="groupe" size="1">';
for($i=0; $i<$m; $i++) {
	echo '<option value='.$tab[$i].'>';
	echo $tab2[$i];
	echo '</option>';
}
echo '</select></td></tr>';

// expe
echo '<tr>
	<td class="head" colspan="2">'._AM_FORM_EXPE.'</td>
	<td class="odd" colspan="2"> <input name="expe" type="checkbox" id="expe"> ('._AM_FORM_EXPE_DESC.')</td>
	</tr>';

// desc_form
echo '<tr>
	<td class="head" colspan="2">'._AM_FORM_TITLE_FORM.' </td>
	<td class="odd" colspan="2"><input maxlength="255" size="50" id="desc_form" name="desc_form" type="text"></td>
	</tr>';

// form_order
echo '<tr>
	  <td class="head" colspan="2">'._AM_FORM_ELE_ORDER.'</td>
	  <td class="odd" colspan="2"><input maxlength="4" size="4" id="form_order" name="form_order" type="text"></td>
	</tr>';

// text_index
echo '<tr>
	<td class="head" colspan="2">'._AM_FORM_TEXTINDEX.'</td>
	<td class="odd" colspan="2">';
$description ="";
ob_start();
$GLOBALS["text_index"] = $description;
xoopsCodeTarea("text_index",50,10);
$xoops_codes_tarea = ob_get_contents();
ob_end_clean();
echo $xoops_codes_tarea; 
echo '</td></tr>';

// text_form
echo '<tr>
	<td class="head" colspan="2">'._AM_FORM_TEXTFORM.'</td>
	<td class="odd" colspan="2">';
$description ="";
ob_start();
$GLOBALS["text_form"] = $description;
xoopsCodeTarea("text_form",50,15);
$xoops_codes_tarea2 = ob_get_contents();
ob_end_clean();
echo $xoops_codes_tarea2; 
echo '</td></tr>';

// form_req
echo '<tr>
	<td class="head" colspan="2">'._AM_FORM_ELE_DISPLAY.'</td>
	<td class="odd" colspan="2"> <input name="form_req" type="checkbox" id="form_req"></td>
	</tr>';

// submit
$submit = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
echo '<tr><td class="foot" colspan="4">'.$submit->render().'</td></tr></table>';
$hidden_op = new XoopsFormHidden('op', 'addform');
echo $hidden_op->render();
echo '</form>';

include 'footer.php';
xoops_cp_footer();

?>