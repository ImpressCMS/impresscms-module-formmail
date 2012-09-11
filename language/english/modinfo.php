<?php
// Module Info

// The name of this module
define("_MI_FORM_NAME","Form");

// A brief description of this module
define("_MI_FORM_DESC","For sending messages to the webmaster & Displays an individually configurable menu in a block");

// admin/menu.php
define("_MI_FORM_ADMENU0","Form management");
define("_MI_FORM_ADMENU1","MyMenu");

//	preferences
define("_MI_FORM_TEXT_WIDTH","Default width of text boxes");
define("_MI_FORM_TEXT_MAX","Default maximum length of text boxes");
define("_MI_FORM_TAREA_ROWS","Default rows of text areas");
define("_MI_FORM_TAREA_COLS","Default columns of text areas");

define("_MI_FORM_DELIMETER","Delimeter for check boxes and radio buttons");
define("_MI_FORM_DELIMETER_SPACE","White space");
define("_MI_FORM_DELIMETER_BR","Line break");

define("_MI_FORM_SEND_METHOD","Send method");
define("_MI_FORM_SEND_METHOD_DESC","Note: Form submitted by anonymous users cannot be sent by using private message.");
define("_MI_FORM_SEND_METHOD_MAIL","Email");
define("_MI_FORM_SEND_METHOD_PM","Private message");

define("_MI_FORM_SEND_GROUP","Send to group");

define("_MI_FORM_SEND_ADMIN","Send to site admin only");
define("_MI_FORM_SEND_ADMIN_DESC","Settings of \"Send to group\" will be ignored");

define("_MI_FORM_SEND_GUESTEXPE","ゲストにメールの控えを送信する");
define("_MI_FORM_SEND_GUESTEXPE_DESC","この設定は全てのフォームに適用されます。控えメールの送信の有無は、フォーム毎に設定してください");

define("_MI_FORM_SEND_USERNAME","「お名前」欄に自動挿入する名前の指定");
define("_MI_FORM_SEND_USERNAME_DESC","登録ユーザーの場合、フォームの「お名前」欄に自動で挿入される名前を指定します。");
define("_MI_FORM_SEND_USERNAME_UNAME","ログイン名を挿入");
define("_MI_FORM_SEND_USERNAME_NAME","本名を挿入");

define("_MI_FORM_SEND_MAILTITLE","メールタイトルにサイト名を表示");
define("_MI_FORM_SEND_MAILTITLE_DESC","「はい」とすると、メールタイトルの先頭に、[".$icmsConfig['sitename']."] と表示されます");

define("_MI_FORM_SEND_MAILTITLE2","メールサブタイトル");
define("_MI_FORM_SEND_MAILTITLE_DESC2","メールタイトルの'サイト名'と'フォーム名'の間に、ここで設定した文字を挿入します。必要無い時は、空欄にしてください。");

// The name of this module
//define("_MI_FORM_MENU_NAME","MyMenu");

// A brief description of this module
//define("_MI_FORM_MENU_DESC","Displays an individually configurable menu in a block");

// Names of blocks for this module (Not all module has blocks)
define("_MI_FORM_MENU_BNAME","FormMail");

?>