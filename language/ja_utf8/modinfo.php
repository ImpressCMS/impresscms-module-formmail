<?php
// Module Info

// The name of this module
define("_MI_FORM_NAME","メールフォーム");

// A brief description of this module
define("_MI_FORM_DESC","For sending messages to the webmaster & Displays an individually configurable menu in a block");

// admin/menu.php
define("_MI_FORM_ADMENU0","フォーム管理");
define("_MI_FORM_ADMENU1","メニュー管理");

//	preferences
define("_MI_FORM_TEXT_WIDTH","テキストボックス横幅の初期値");
define("_MI_FORM_TEXT_MAX","テキストボックス最大文字数の初期値");
define("_MI_FORM_TAREA_ROWS","テキストエリア列数の初期値");
define("_MI_FORM_TAREA_COLS","テキストエリア行数の初期値");

define("_MI_FORM_DELIMETER","チェックボックスとラジオボタンの並び方");
define("_MI_FORM_DELIMETER_SPACE","空白スペース(横並び)");
define("_MI_FORM_DELIMETER_BR","改行(縦並び)");

define("_MI_FORM_SEND_METHOD","送信方法");
define("_MI_FORM_SEND_METHOD_DESC","Note: プライベートメッセージを選択すると、ゲストユーザーは送信する事が出来なくなります。");
define("_MI_FORM_SEND_METHOD_MAIL","Eメール");
define("_MI_FORM_SEND_METHOD_PM","プラーベートメッセージ");

define("_MI_FORM_SEND_GROUP","送信先グループ");

define("_MI_FORM_SEND_ADMIN","サイト管理者にのみ送信");
define("_MI_FORM_SEND_ADMIN_DESC","\"送信先グループ\"の設定は無視します");

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
//define("_MI_FORM_MENU_NAME","マイメニュー");

// A brief description of this module
//define("_MI_FORM_MENU_DESC","Displays an individually configurable menu in a block");

// Names of blocks for this module (Not all module has blocks)
define("_MI_FORM_MENU_BNAME","メールフォーム");

?>