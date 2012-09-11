<?php
include("../../../mainfile.php");
$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;
	global $xoopsConfig, $xoopsModule;
	include("admin_header.php");
	xoops_cp_header();
	if ($_POST['save'] == "countsave") {
		global $_POST;
		$content  = "";
		$content .= "<?php\n";
		$content .= "\$formmail_spam = '".$_POST['formmail_spam']."';\n";
		$content .= "?>";

		$filename = XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/config.php";
		if ( $file = fopen($filename, "w") ) {
			fwrite($file, $content);
			fclose($file);
		} else {
			redirect_header("index.php?op=newsConfig", 1, _NOTUPDATED);
			exit();
		}
	}

	include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/config.php");

	OpenTable();
?>
<form name="form1" method="post" action="configset.php">
<table>
<tr>
<td nowrap><?php echo _AM_FORM_TITLE0; ?></td>
<td>
<select name=formmail_spam>
<option value="1" <?php if($formmail_spam == "1"){echo "selected";}?> ><?php echo _AM_FORM_ON; ?></option>
<option value="0" <?php if($formmail_spam == "0"){echo "selected";}?> ><?php echo _AM_FORM_OFF; ?></option>
</select>
</td>
</tr>
<td align="right">
<input type="hidden" name="save" value="countsave" />
<input type="submit" value="<?php echo _AM_FORM_BTN1; ?>" />
<input type="reset" value="<?php echo _AM_FORM_BTN2; ?>">
</form></td></tr>
</table>
<?php
CloseTable();

xoops_cp_footer();
?>
