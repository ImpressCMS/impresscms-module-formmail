<?php
// Module Info

// The name of this module
define("_MI_FORM_NAME","Formulare");

// A brief description of this module
define("_MI_FORM_DESC","Zum senden von Nachrichten an dem Webmaster und anzeigen von konfigurierbaren Menüs in einem Block");

// admin/menu.php
define("_MI_FORM_ADMENU0","Formular Management");
define("_MI_FORM_ADMENU1","Mein Menü");

//	preferences
define("_MI_FORM_TEXT_WIDTH","Voreingestellte Breite eines Textfeldes");
define("_MI_FORM_TEXT_MAX","Maximale Länge eines Textfeldes");
define("_MI_FORM_TAREA_ROWS","Std. Reihen im Textbereich");
define("_MI_FORM_TAREA_COLS","Std. Spalten im Textbereich");

define("_MI_FORM_DELIMETER","Begrenzer für Check Boxes und Radio Buttons");
define("_MI_FORM_DELIMETER_SPACE","Leerraum");
define("_MI_FORM_DELIMETER_BR","Zeilenwechsel");

define("_MI_FORM_SEND_METHOD","Formular senden als");
define("_MI_FORM_SEND_METHOD_DESC","Hinweis: Von Gästen übermittelte Formulare können nicht als Private Nachricht empfangen werden.");
define("_MI_FORM_SEND_METHOD_MAIL","Email");
define("_MI_FORM_SEND_METHOD_PM","Private Nachricht");

define("_MI_FORM_SEND_GROUP","An Gruppe senden");

define("_MI_FORM_SEND_ADMIN","Nur an Siteadmin senden");
define("_MI_FORM_SEND_ADMIN_DESC","Die Einstellung von \"An Gruppe Senden\" wird ignoriert");

define("_MI_FORM_SEND_GUESTEXPE","Eine Bestätigungsmail an den Besucher übermitteln.");
define("_MI_FORM_SEND_GUESTEXPE_DESC","Diese Einstellung wirkt sich auf alle Formulare aus.");

define("_MI_FORM_SEND_USERNAME","Wählen ob der Mitgliedsname oder der echte Name des Users im Formular verwendet werden soll");
define("_MI_FORM_SEND_USERNAME_DESC","Der entsprechende Name wird automatisch eingetragen.");
define("_MI_FORM_SEND_USERNAME_UNAME","Mitgliedsname");
define("_MI_FORM_SEND_USERNAME_NAME","Richtiger Name");

define("_MI_FORM_SEND_MAILTITLE","Seitenname in Bestätigungsmail übernehmen");
define("_MI_FORM_SEND_MAILTITLE_DESC","Wenn ja, dann wird [".$icmsConfig['sitename']."]im Mailtitel eingetragen");

define("_MI_FORM_SEND_MAILTITLE2","Mail Untertitel");
define("_MI_FORM_SEND_MAILTITLE_DESC2","Legt fest ob zum Seitennamen auch der Name des Formulars in die Bestätigungsmail übernommen werden soll. Wenn nicht gewünscht, Feld bitte leer lassen.");

// The name of this module
//define("_MI_FORM_MENU_NAME","MyMenu");

// A brief description of this module
//define("_MI_FORM_MENU_DESC","Displays an individually configurable menu in a block");

// Names of blocks for this module (Not all module has blocks)
define("_MI_FORM_MENU_BNAME","FormMail");
?>