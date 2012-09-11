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
include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
$myts =& MyTextSanitizer::getInstance();
$eh = new ErrorHandler;

if (isset($_GET['id_form'])) {
	$id_form = intval($_GET['id_form']);
} else {
	redirect_header("index.php", 0, "ERROR!!!!!!");
	exit();
}
if (isset($_POST['op'])) {
	$op =$_POST['op'];
}else{
	$op =null;
}

if ($op != 'upform') {
	$sql = "SELECT desc_form, admin, groupe, email,expe, text_index, text_form, form_order, form_req FROM ".$xoopsDB->prefix("formmail_id")." WHERE id_form=$id_form";
	$res = $xoopsDB->query ( $sql ) or die('Erreur SQL !<br>'.$requete.'<br>'.$xoopsDB->error());
	if ( $res ) {
		while ( $row = $xoopsDB->fetchArray ( $res ) ) {
			$title = $row['desc_form'];
			$admin = $row['admin'];
			$groupe = $row['groupe'];
			$email = $row['email'];
			$expe = $row['expe'];
			$text_index = $row['text_index'];
			$text_form = $row['text_form'];
			$form_order = $row['form_order'];
			$form_req = $row['form_req'];
		}
	}
	xoops_cp_header();
	// admin menu
	formmail_admin_menu ();

	echo '<form action="mail_index.php?id_form='.$id_form.'" method="post">
	<table class="outer" cellspacing="1" width="100%">
	<tr>
	  <th align="center" colspan="4">'._AM_FORM_FORM.$title.'</th>
	</tr>';

	// send to
	echo '<tr>
	  <td class="head" rowspan="2">'._AM_FORM_SENDTO.'</td>
	  <td class="head">'._AM_FORM_ADMIN.'</td>
	  <td class="odd" colspan="2"> ';
	if ($admin == 'on') {
		echo '<input name="admin" type="checkbox" id="admin" checked>';
	}else {
		echo '<input name="admin" type="checkbox" id="admin" >';
	}
	echo ' ('._AM_FORM_ADMIN_DESC.') </td></tr>';
	echo '<tr>
	  <td class="head">'._AM_FORM_OTHER.'</td>
	  <td class="odd" align="center">'._AM_FORM_EMAIL.'<br /><input maxlength="255" size="30" id="email" name="email" type="text" value='.$email.'></td>';

	// send group list
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
	for($i=0;$i<$m;$i++) {
		echo '<option value='.$tab[$i].''; 
		if($tab[$i]==$groupe) {
			echo " SELECTED";
		}
		echo '>';
		echo $tab2[$i];
		echo '</option>';
	}
	echo '</select></td></tr>';

	// expe
	echo '<tr>
	  <td class="head" colspan="2">'._AM_FORM_EXPE.'</td>
	  <td class="odd" colspan="2"> ';
	if ($expe == 'on') {
		echo '<input name="expe" type="checkbox" id="expe" checked>';
	}else {
		echo '<input name="expe" type="checkbox" id="expe" >';
	}
	echo ' ('._AM_FORM_EXPE_DESC.')</td></tr>';

	// title
	echo '	<tr>
		  <td class="head" colspan="2">'._AM_FORM_TITLE_FORM.'</td>
		  <td class="odd" colspan="2"><input maxlength="255" size="50" id="title" name="title" type="text" value='.$title.'></td>
		</tr>';

	// form_order
	echo '<tr>
		  <td class="head" colspan="2">'._AM_FORM_ELE_ORDER.'</td>
		  <td class="odd" colspan="2"><input maxlength="4" size="4" id="form_order" name="form_order" type="text" value='.$form_order.'></td>
		</tr>';

	// text_index
	echo '<tr>
		  <td class="head" colspan="2">'._AM_FORM_TEXTINDEX.'</td>
		  <td class="odd" colspan="2">';
	$description ="$text_index";
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
	$description ="$text_form";
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
		<td class="odd" colspan="2">';
	if ($form_req == 'on') {
		echo '<input name="form_req" type="checkbox" id="form_req" checked>';
	}else {
		echo '<input name="form_req" type="checkbox" id="form_req" >';
	}
	echo '</td></tr>';

	// submit
	$submit = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
	echo '<tr><td class="foot" colspan="4">'.$submit->render().'</td></tr></table>';
	$hidden_op = new XoopsFormHidden('op', 'upform');
	echo $hidden_op->render();
	echo '</form>';
}

function upform($id_form)
{
	global $xoopsDB, $_POST, $myts, $eh;

	$title = $myts->makeTboxData4Save($_POST["title"]);
	$admin = $myts->makeTboxData4Save($_POST["admin"]);
	$groupe = $myts->makeTboxData4Save($_POST["groupe"]);
	$email = $myts->makeTboxData4Save($_POST["email"]);
	$expe = $myts->makeTboxData4Save($_POST["expe"]);
	$text_index = $myts->makeTareaData4Save($_POST["text_index"]);
	$text_form = $myts->makeTareaData4Save($_POST["text_form"]);
	$form_order = $myts->makeTareaData4Save($_POST["form_order"]);
	$form_req = $myts->makeTboxData4Save($_POST["form_req"]);

	if((!empty($email)) && (!eregi("^[^@]+@[^.]+\..+", $email))){
		redirect_header("mail_index.php?id_form=$id_form", 2, _AM_FORM_ERROREMAIL);
		exit();
	}
//	if (empty($email) && empty($admin) && $groupe=="0" && empty($expe)) {
	if (empty($email) && empty($admin) && $groupe=="0" ) {
		redirect_header("mail_index.php?id_form=$id_form", 2, _AM_FORM_ERRORMAIL);
		exit();
	}
	$sql = sprintf("UPDATE %s SET desc_form='%s', admin='%s', groupe='%s', email='%s', expe='%s', text_index='%s', text_form='%s', form_order='%s', form_req='%s' WHERE id_form='%s'", $xoopsDB->prefix("formmail_id"), $title, $admin, $groupe, $email, $expe, $text_index, $text_form, $form_order, $form_req, $id_form);
	$xoopsDB->query($sql) or $eh->show("0013");

	// update mymenu
	$menu_url = XOOPS_URL.'/modules/formmail/index.php?id_form='.$id_form;
	if ($form_req == 'on') {
		$sql2 = sprintf("UPDATE %s SET status='%s' WHERE itemurl='%s'", $xoopsDB->prefix("formmail_menu"), '1', $menu_url);
		$xoopsDB->query($sql2) or $eh->show("0013");
	}else {
		$sql2 = sprintf("UPDATE %s SET status='%s' WHERE itemurl='%s'", $xoopsDB->prefix("formmail_menu"), '0', $menu_url);
		$xoopsDB->query($sql2) or $eh->show("0013");
	}

	redirect_header("index.php",1,_AM_FORM_FORMCHARG);
	exit();
}

switch ($op) {
	case "upform":
		upform($id_form);
		break;
}

include 'footer.php';
xoops_cp_footer();

?>