<?php
defined( '_JEXEC' ) or die( '=;)' );
//ini_set("display_errors", 0);
//error_reporting(0);
global $mainframe;

//create($archive, $files, $compress = 'tar', $addPath = '', $removePath = '', $autoExt = false, $cleanUp = false)		
/*
jimport('joomla.filesystem.file');
jimport( 'joomla.filesystem.archive' );	
$dir = "components/com_whp/helpers";
JArchive::create( "x", $dir, "zip", "modules/mod_kepvalto", "{$dir}", true ); 
*/


require_once( 'components/com_whp/helpers/wg3api.php' );
require_once( 'components/com_whp/helpers/bigfish/paymentgateway.php' );
require_once( 'components/com_whp/helpers/whpBeallitasok.php' );
require_once( 'administrator/components/com_whp/helpers/baseModel.php' );
require_once( 'administrator/components/com_whp/helpers/xComponent.php' );
require_once( 'components/com_whp/helpers/xComponent.php' );
require_once( 'administrator/components/com_whp/helpers/ar.php' );
require_once( 'administrator/components/com_whp/helpers/email.php' );
require_once( 'administrator/components/com_whp/helpers/baseController.php' );
require_once( 'administrator/components/com_whp/helpers/xml.php' );
require_once( 'administrator/components/com_whp/helpers/kep.php' );
require_once( 'administrator/components/com_whp/helpers/termek.php' );
require_once( 'administrator/components/com_whp/helpers/kategoria.php' );
require_once( 'administrator/components/com_whp/helpers/felhasznalo.php' );
require_once( 'administrator/components/com_whp/helpers/rendeles.php' );
require_once( 'administrator/components/com_whp/helpers/atvevohely.php' );
require_once( 'administrator/components/com_whp/helpers/atvevohely.php' );
require_once( 'administrator/components/com_whp/helpers/uzenet.php' );
require_once( 'administrator/components/com_whp/helpers/recommend.php' );

require_once( 'components/com_whp/helpers/initJsKonstansok.php' );


require_once( 'administrator/components/com_whp/helpers/kategoriafa.php' );
require_once( 'administrator/components/com_whp/helpers/webContent.php' );
require_once("administrator/components/com_whp/helpers/json.php");
/*
require_once( 'administrator/components/com_whp/tables/whp_termek.php' );
require_once( 'administrator/components/com_whp/tables/whp_rendeles.php' );
require_once( 'administrator/components/com_whp/tables/whp_kategoria.php' );
*/
jimport( "unitemplate.simpleimage.simpleimage");
require_once( 'administrator/components/com_whp/helpers/listazo.php' );

$controller = JRequest::getWord('controller', "termekek" ); 
$path = "components/com_whp/controllers/{$controller}.php";
require_once ($path);
$classname = 'whpController'.$controller;
$controller = new $classname( );
$controller->execute( JRequest::getVar('task') );
$controller->redirect();
?>