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
include_once '../../mainfile.php';
include_once( XOOPS_ROOT_PATH . '/modules/formmail/config.php' ) ;


//add for captcha hack
global $xoopsCaptcha;

//if ($SSLSCRIPT != "") {
//	$host = getenv("HTTP_HOST");
//	$myscript = $GLOBALS['HTTP_SERVER_VARS']['PHP_SELF'];
//	$query    = "?".getenv('QUERY_STRING');
//	if (isset($_POST)) {
//		$query .= make_get_str($_POST);
//	}
//	if (isset($_GET['id_form'])) {
//		if (!getenv($SSLPROTENV)) {
//			header("Location:".$SSLPROT.$host.$myscript.$query);
//			exit;
//		}
//	} else {
//		if (getenv($SSLPROTENV)) {
//			header("Location:http://".$host.$myscript.$query);
//			exit;
//		}
//	}
//}
$querystring = getenv("QUERY_STRING");
if (isset($querystring)) {
	$dc = explode("&",urldecode($querystring));
	foreach($dc as $k =>$v) {
		$dcw = explode("=",$v);
		if (isset($dcw[1])) {
			$spdt = split("\[",$dcw[0]);
			if (isset($spdt[1])) {
				$spdtidx = split("\]",$spdt[1]);
				$idx = (int)$spdtidx[0];
				$_POST[$spdt[0]][$idx] = $dcw[1];
			} else {
				$_POST[$dcw[0]] = $dcw[1];
			}
		}
	}
}
/**
 *
 * XOOPS module 'FormMail'  by Tom (Malaika System)
 *
 **/
$language = $xoopsConfig['language'] ;
if( ! file_exists( XOOPS_ROOT_PATH . '/modules/formmail/language/'.$language.'/admin.php') ) $language = 'english' ;
include_once( XOOPS_ROOT_PATH . '/modules/formmail/language/'.$language.'/admin.php' ) ;
if (file_exists(XOOPS_ROOT_PATH . '/include/session.php')) {
	require_once XOOPS_ROOT_PATH . '/include/session.php';
	xoops_session_regenerate();
} else {
	setcookie($xoopsConfig['session_name'], session_id(), time()+(60*$xoopsConfig['session_expire']), '/',  '', 0);
}

//include_once XOOPS_ROOT_PATH.'/modules/captcha/include/api.php';
//$captcha_api =& captcha_api::getInstance();
//$img_input = $captcha_api->make_img_input();

include 'header.php';

// view index
if (!isset($_GET['id_form'])) {
	// count form
	$result = $xoopsDB->query("SELECT id_form FROM ".$xoopsDB->prefix("formmail_id")." WHERE form_req='on'");
	$num_form = $xoopsDB->getRowsNum($result);

	// form == one : go to form
	if ($num_form == 1) {
		list($id_form) = $xoopsDB->fetchRow($result);
//		redirect_header(XOOPS_URL."/modules/formmail/index.php?id_form=".$id_form, 0, _MD_FORM_MSG_CHARG );
		$gotourl = "Location: " . XOOPS_URL."/modules/formmail/index.php?id_form=".$id_form;
		header($gotourl);
		exit();

	// form >1ea : view menu
	} else {
		$xoopsOption['template_main'] = 'formmail_index.html' ;
		include_once XOOPS_ROOT_PATH.'/header.php';

		global $xoopsDB;
		$myts =& MyTextSanitizer::getInstance();

		$i = 1;
		$result2 = $xoopsDB->query("SELECT id_form, desc_form, text_index FROM ".$xoopsDB->prefix("formmail_id")." WHERE form_req='on' ORDER BY form_order");
		while ( $row = $xoopsDB->fetchArray($result2) ) {
			$form_list['id_form'] = $myts->makeTboxData4Show($row['id_form']);
			$form_list['title'] = $myts->makeTboxData4Show($row['desc_form']);
			$form_list['text'] = $myts->makeTareaData4Show($row['text_index']);
			$xoopsTpl->append("form_list", $form_list);
			$i++;
		}
		$xoopsTpl->assign("form_title", _MD_FORM_SITENAME);
		$xoopsTpl->assign("form_subject", _MD_FORM_SUBJECT);
		$xoopsTpl->assign("formmail_credits", credits());
		include_once XOOPS_ROOT_PATH.'/footer.php';
	}

// mail form
}else{
	$id_form = intval($_GET['id_form']);
	$myts =& MyTextSanitizer::getInstance();

	// check id_form
	$sql = "SELECT desc_form, admin, groupe, email, expe, text_index, text_form, form_req FROM ".$xoopsDB->prefix("formmail_id")." WHERE id_form=".$id_form."";
	$result = $xoopsDB->query ( $sql ) or die('Erreur SQL !<br>'.$sql.'<br>'.$xoopsDB->error());
	if ( $result ) {
		while ( $row = $xoopsDB->fetchArray ( $result ) ) {
			$title = $myts->makeTboxData4Show($row['desc_form']);
			$admin = $myts->makeTboxData4Show($row['admin']);
			$groupe = $myts->makeTboxData4Show($row['groupe']);
			$email = $myts->makeTboxData4Show($row['email']);
			$expe = $myts->makeTboxData4Show($row['expe']);
			$text_index = $myts->makeTareaData4Show($row['text_index']);
			$text_form = $myts->makeTareaData4Show($row['text_form']);
			$form_req = $myts->makeTboxData4Show($row['form_req']);
		}
	}

	// no title & $form_req==off : return to index.php
	if (!empty($xoopsUser)) {
		$isAdmin = $xoopsUser->isAdmin($xoopsModule->mid());
		if ( !isset($title) || ( $form_req !== 'on' && ! $isAdmin ) ) {
			redirect_header(XOOPS_URL."/", 1, _MD_FORM_MSG_ERROR );
			exit();
		}
	} else {
		if ( !isset($title) || ( $form_req !== 'on' ) ) {
			redirect_header(XOOPS_URL."/", 1, _MD_FORM_MSG_ERROR );
			exit();
		}
	}
	// view form

	
	if( empty($_POST['submit'])){
		$xoopsOption['template_main'] = 'formmail_form.html';
		include_once XOOPS_ROOT_PATH.'/header.php';

		$xoopsTpl->assign("text_index", $text_index);
		$xoopsTpl->assign("text_form", $text_form);
		$xoopsTpl->assign("formmail_credits", credits());

		$criteria = new Criteria('ele_display', 1);
		$criteria->setSort('ele_order');
		$criteria->setOrder('ASC');
		$elements =& $formmail_mgr->getObjects2($criteria, $id_form);
		
		$form = new XoopsThemeForm(_MD_FORM_LANG_FORMTITLE.$title, 'formmail', XOOPS_URL.'/modules/formmail/index.php?id_form='.$id_form.'&');

		// fix-form : name & email
		if ( !empty($xoopsUser) ) {
			$h_name = $xoopsUser->getVar("name", "E");
			if ( $xoopsModuleConfig['username_sel'] == "name" && !empty($h_name) ) {
				$name_v = $h_name;
			} else {
				$name_v = $xoopsUser->getVar("uname", "E");
			}
		} else {
			$name_v = $_POST['usersName'];
		}
		$email_v = !empty($xoopsUser) ? $xoopsUser->getVar("email", "E") : $_POST['usersEmail'];
		$name_text = new XoopsFormText(_MD_FORM_LANG_NAME, "usersName", $xoopsModuleConfig['t_width'], $xoopsModuleConfig['t_max'], $name_v);
		$email_text = new XoopsFormText(_MD_FORM_LANG_EMAIL, "usersEmail", $xoopsModuleConfig['t_width'], $xoopsModuleConfig['t_max'], $email_v);
		$form->addElement($name_text, true);
		$form->addElement($email_text, true);

		$count = 0;
		foreach( $elements as $i ){
			$renderer =& new FormmailElementRenderer($i);
			$formaddvalue = 'ele_'.$i->getVar('ele_id') ;
			$formvalue = $_POST[$formaddvalue];
			$form_ele =& $renderer->constructElement($formaddvalue);
			if (isset($_POST[$formaddvalue])) {
				$form_ele->setValue($formvalue);
			}
			$req = intval($i->getVar('ele_req'));
			$form->addElement($form_ele, $req);
			$count++;
			unset($form_ele);
			unset($hidden);
		}
	
// rehacked with captcha module
		if ($formmail_spam == 1)
		{
		 	if ( defined('ICMS_VERSION_BUILD') && ICMS_VERSION_BUILD > 27  ) { /* ImpressCMS 1.2+ */
				include_once (ICMS_ROOT_PATH ."/class/captcha/captcha.php");
				include_once ICMS_ROOT_PATH."/class/xoopsformloader.php";
				$form -> addElement(new IcmsFormCaptcha(_SECURITYIMAGE_GETCODE, "scode"), true);
				$form -> addElement(new XoopsFormHidden("op", "finish"));
			} else {
				if ( class_exists( 'XoopsFormCaptcha' ) ) { 
					$form -> addElement( new XoopsFormCaptcha() ); 
				} elseif ( class_exists( 'IcmsFormCaptcha' ) ) { 
					$form -> addElement( new IcmsFormCaptcha() ); 
				} else{
					$server   = XOOPS_URL."/modules/formmail/server.php";
					$onclick  = "javasript:this.src='". $server ."?'+Math.random();";
					$captcha  = _AM_FORM_CAPTCH_DESC ."<br />\n";
					$captcha .= '<img src="'. $server .'" onclick="'. $onclick .'" alt="CAPTCHA image" style="padding: 3px" />'."<br />\n";
					$captcha .= '<input name="captcha" type="text" />';
					$form->addElement( new XoopsFormLabel(_AM_FORM_CAPTCHA_TITLE, $captcha) );
				}
			}
		}
// rehack end
		$form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
		$form->assign($xoopsTpl);

		include_once XOOPS_ROOT_PATH.'/footer.php';

	// send mail
	}else{

// rechange by tac for captcha	
		if($formmail_spam == 1)
		{
		 	if ( defined('ICMS_VERSION_BUILD') && ICMS_VERSION_BUILD > 27  )
		 	{ /* ImpressCMS 1.2+ */
				include_once (ICMS_ROOT_PATH ."/class/captcha/captcha.php");
				include_once(ICMS_ROOT_PATH ."/class/xoopsformloader.php");
          		$icmsCaptcha = IcmsCaptcha::instance();
	        	if(! $icmsCaptcha->verify() ) {
					redirect_header( 'index.php', 2, $icmsCaptcha -> getMessage() ); 
  	    		}
			}
			else
			{
				if ( class_exists( 'XoopsFormCaptcha' ) ) 
				{ 
					if ( @include_once ICMS_ROOT_PATH . '/class/captcha/captcha.php' )
					{
						$xoopsCaptcha = XoopsCaptcha::instance(); 
						if ( ! $xoopsCaptcha -> verify( true ) )
						{ 
							redirect_header( 'index.php', 2, $xoopsCaptcha -> getMessage() ); 
						} 
					} 
				} elseif ( class_exists( 'IcmsFormCaptcha' ) )
				{ 
					if ( @include_once ICMS_ROOT_PATH . '/class/captcha/captcha.php' )
					{ 
						$icmsCaptcha = IcmsCaptcha::instance(); 
						if ( ! $icmsCaptcha -> verify( true ) ) { 
							redirect_header( 'index.php', 2, $icmsCaptcha -> getMessage() ); 
						} 
					} 
				} else
				{
					include_once XOOPS_ROOT_PATH.'/modules/formmail/class/captcha_x/class.captcha_x.php';
					$captcha = &new captcha_x();
					if ( !isset($_POST['captcha']) || !$captcha->validate($_POST['captcha']) )
					{
						include_once XOOPS_ROOT_PATH.'/header.php';
						echo '<br /><br /><font size=3>' . _AM_FORM_ATTESTATION_ERROR . '</font><br />';
						echo '<br /><br /><font size=3>&nbsp; &nbsp; <a href="javascript:history.back();">Back to the form</a></font>';
						include_once XOOPS_ROOT_PATH.'/footer.php';
						exit;
					}
				}
			}
		}

//		$myts =& MyTextSanitizer::getInstance();
		$msg = '';
		unset($_POST['submit']);
		foreach( $_POST as $k => $v ){
			if( preg_match('/ele_/', $k) ){
				$n = explode("_", $k);
				$ele[$n[1]] = $v;
				$id[$n[1]] = $n[1];
			}
		}
		foreach( $id as $i ){
			$element =& $formmail_mgr->get($i);
			if( !empty($ele[$i]) ){
				$ele_type = $element->getVar('ele_type');
				$ele_value = $element->getVar('ele_value');
				$ele_caption = $element->getVar('ele_caption');
				$ele_caption = stripslashes($ele_caption);
				$ele_caption = eregi_replace ("&#039;", "`", $ele_caption);
				$ele_caption = eregi_replace ("&quot;", "`", $ele_caption);
				$msg.= "\n".$ele_caption."\n";
				switch($ele_type){
					case 'text':
						$msg.= $myts->stripSlashesGPC($ele[$i])."\n";
						break;
					case 'textarea':
						$msg.= $myts->stripSlashesGPC($ele[$i])."\n";
						break;
					case 'radio':
						$opt_count = 1;
						while( $v = each($ele_value) ){
							if( $opt_count == $ele[$i] ){
								$msg.= $myts->stripSlashesGPC($v['key'])."\n";
							}
							$opt_count++;
						}
						break;
					case 'yn':
						$v = ($ele[$i]==2) ? _NO : _YES;
						$msg.= $myts->stripSlashesGPC($v)."\n";
						break;
					case 'checkbox':
						$opt_count = 1;
						while( $v = each($ele_value) ){
							if( is_array($ele[$i]) ){
								if( in_array($opt_count, $ele[$i]) ){
									$msg.= $myts->stripSlashesGPC($v['key'])."\n";
								}
								$opt_count++;
							}else{
								if( !empty($ele[$i]) ){
									$msg.= $myts->stripSlashesGPC($v['key'])."\n";
								}
							}
						}
						break;
					case 'select':
						$opt_count = 1;
						if( is_array($ele[$i]) ){
							while( $v = each($ele_value[2]) ){
								if( in_array($opt_count, $ele[$i]) ){
									$msg.= $myts->stripSlashesGPC($v['key'])."\n";
								}
								$opt_count++;
							}
						}else{
							while( $j = each($ele_value[2]) ){
								if( $opt_count == $ele[$i] ){
									$msg.= $myts->stripSlashesGPC($j['key'])."\n";
								}
								$opt_count++;
							}
						}
						break;
					default:
						break;
				}
			}
		}
	// 	echo nl2br($msg);

		if( is_dir(FORMMAIL_ROOT_PATH."/language/".$xoopsConfig['language']."/mail_template") ){
			$template_dir = FORMMAIL_ROOT_PATH."/language/".$xoopsConfig['language']."/mail_template";
		}else{
			$template_dir = FORMMAIL_ROOT_PATH."/language/english/mail_template";
		}

		$xoopsMailer =&getMailer();
		$xoopsMailer->setTemplateDir($template_dir);
		$xoopsMailer->setTemplate('formmail.tpl');
		
		// mail title
		$sendmail_title = "";
		if ( $xoopsModuleConfig['mail_title'] == '1' ) $sendmail_title .= '['.$xoopsConfig['sitename'].'] ';
		if ( !empty($xoopsModuleConfig['mail_title2']) ) $sendmail_title .= $xoopsModuleConfig['mail_title2'].' ';
		$sendmail_title .= $title;

		$xoopsMailer->setSubject( $sendmail_title );
		$usersEmail = $myts->stripSlashesGPC($_POST['usersEmail']);
		$usersName = $myts->stripSlashesGPC($_POST['usersName']);

		if( is_object($xoopsUser) ){
			$xoopsMailer->assign("UNAME", "\"".$usersName."\" <".$usersEmail."> : ".$xoopsUser->getVar("uname"));
			$xoopsMailer->assign("UID", $xoopsUser->getVar("uid"));
		}else{
			$xoopsMailer->assign("UNAME", "\"".$usersName."\" <".$usersEmail."> : ".$xoopsConfig['anonymous']);
			$xoopsMailer->assign("UID", '-');
		}
		$xoopsMailer->assign("IP", xoops_getenv('REMOTE_ADDR'));
		$xoopsMailer->assign("AGENT", xoops_getenv('HTTP_USER_AGENT'));
		$xoopsMailer->assign("MSG", $msg);
		$xoopsMailer->assign("TITLE", $title);

		// send PM && xoopsuser
		if( $xoopsModuleConfig['method'] == 'pm' && is_object($xoopsUser) ){
			$xoopsMailer->usePM();
		  	$sqlstr = "SELECT $xoopsDB->prefix" . "_users.uname AS UserName, $xoopsDB->prefix" . "_users.email AS UserEmail, $xoopsDB->prefix" . "_users.uid AS UserID FROM 
				 ".$xoopsDB->prefix("groups").", ".$xoopsDB->prefix("groups_users_link").", ".$xoopsDB->prefix("users")." WHERE $xoopsDB->prefix" . "_users.uid = $xoopsDB->prefix" . "_groups_users_link.uid 
				 AND $xoopsDB->prefix" . "_groups_users_link.groupid = $xoopsDB->prefix" . "_groups.groupid AND $xoopsDB->prefix" . "_groups.groupid = $groupe";

			$res = $xoopsDB->query($sqlstr);
	        while (list($UserName,$UserEmail,$UserID) = $xoopsDB->fetchRow($res)) {
				$xoopsMailer->setToEmails($UserEmail);
			//	mail($UserEmail,$subject,$msg,"From: $sender\nReply-To: $replyto\nX-Mailer: PHP/");
			}

		// send E-mail
		}else{
			$xoopsMailer->useMail();
			if( $expe == "on" ) {
				if (!empty($xoopsUser)) {
					$email_expe = $xoopsUser->getVar("email");
					$xoopsMailer->setToEmails($email_expe);
					$xoopsMailer->assign("EMAIL_EXPE", $email_expe);
				} elseif ($xoopsModuleConfig['guest_expe']) {
					$email_expe = $usersEmail;
					$xoopsMailer->setToEmails($email_expe);
					$xoopsMailer->assign("EMAIL_EXPE", $email_expe);
				} else {
					$xoopsMailer->assign("EMAIL_EXPE", " -- ");
				}
			} else {
				$xoopsMailer->assign("EMAIL_EXPE", " -- ");
			}

			if( $admin == "on" ){
				$xoopsMailer->setToEmails($xoopsConfig['adminmail']);
				$xoopsMailer->assign("ADMINEMAIL", $xoopsConfig['adminmail']);
				$xoopsMailer->assign("EMAIL", " -- ");
				$xoopsMailer->assign("GROUPE", " -- ");
			}else{
				$xoopsMailer->assign("ADMINEMAIL", " -- ");
				$xoopsMailer->setToEmails($email);
			  	$xoopsMailer->assign("EMAIL", $email);

				if (!empty($groupe) && ($groupe != "0")){
					$sql=sprintf("SELECT name FROM ".$xoopsDB->prefix("groups")." WHERE groupid='%s'",$groupe);
					$res = $xoopsDB->query ( $sql ) or die('Erreur SQL !<br>'.$sql.'<br>'.$xoopsDB->error());
					if ( $res ) {
						while ( $row = $xoopsDB->fetchRow ( $res ) ) {
							$gr = $row[0];
	  					}
					}
					$xoopsMailer->assign("GROUPE", $gr);
				}else {
					$xoopsMailer->assign("GROUPE", " -- ");
				}

			  	$sqlstr = "SELECT $xoopsDB->prefix" . "_users.uname AS UserName, $xoopsDB->prefix" . "_users.email AS UserEmail, $xoopsDB->prefix" . "_users.uid AS UserID FROM
		            ".$xoopsDB->prefix("groups").", ".$xoopsDB->prefix("groups_users_link").", ".$xoopsDB->prefix("users")." WHERE $xoopsDB->prefix" . "_users.uid = $xoopsDB->prefix" . "_groups_users_link.uid
		            AND $xoopsDB->prefix" . "_groups_users_link.groupid = $xoopsDB->prefix" . "_groups.groupid AND $xoopsDB->prefix" . "_groups.groupid = $groupe";

				$res = $xoopsDB->query($sqlstr);
				while (list($UserName,$UserEmail,$UserID) = $xoopsDB->fetchRow($res)) {
					$xoopsMailer->setToEmails($UserEmail);
				//	mail($UserEmail,$subject,$msg,"From: $sender\nReply-To: $replyto\nX-Mailer: PHP/");
				}
			}
		}

		$xoopsMailer->send();
		$sent = sprintf(_MD_FORM_MSG_SENT, $xoopsConfig['sitename'])._MD_FORM_MSG_THANK;
		redirect_header(XOOPS_URL."/", 2, $sent);
		exit();
	
	// 	if( !$xoopsMailer->send(true) ){
	// 		echo $xoopsMailer->getErrors();
	// 	}else{
	// 		echo $xoopsMailer->getSuccess();
	// 	}
	}
}

function credits () 
{
$credits = "<div style='text-align: right; font-size: x-small; font-style: italic;'>Formmail 1.3 hacked by <a href='http://www.chushokigyo.net/' target='_blank'>Chushokigyo.net</a></div><div style='text-align: right; font-size: 6pt; font-style: italic;'>
	Powered by FormMail 1.0beta by Tom <a href='http://malaika.s31.xrea.com/' target='_blank'>Malaika System</a>Based on Formulaire 1.0 &copy; 2003 <a href='http://www.xoops-themes.com/' target='_blank'>xoops-themes&middot;com</a> / Liaise 1.0b5 by NS Tai (aka tuff) <a href='http://www.brandycoke.com/'>BRANDYCOKE&middot;COM</a>
	/ MyMenu by Marcel Widmer(http://www.coaching-forum.net) </div>";
	return $credits;
}
#==========================================================================
# �������(GETʸ�������)
#-------------------------------------------------------------------------
function make_get_str($post,$flg=true) {
	$str = "";
	foreach($post as $key =>$val) {
		if (is_array($val)) {
			foreach($val as $k =>$v) {
				$str .= $key."[".$k."]=".$v."&";
			}
		} else {
			$str .= $key."=".$val."&";
		}
	}
	if ($str != "") {
		$str = substr($str, 0, strlen($str)-1);
	}
	if ($flg) {
		return htmlentities(urlencode($str),ENT_COMPAT,"EUC-JP");
	} else {
		return $str;
	}
}
?>