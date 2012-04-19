<?php
defined( '_JEXEC' ) or die( '=;)' );
/*
ini_set("display_errors", 1);
error_reporting(E_ALL);
*/
global $mainframe;
JHTML::_('behavior.modal');
$mainframe->setTemplate("webshop");
$document =& JFactory::getDocument();
$document->addScript( 'components/com_whp/assets/js/js.js' );

$document->addScript( 'components/com_whp/assets/js/demo_tooltip.js' ); 


$document->addScript('components/com_whp/assets/jquery/jquery-autocomplete/lib/jquery.js');
$document->addScriptDeclaration('var $j = jQuery.noConflict();');
$document->addScript('components/com_whp/assets/jquery/jquery-autocomplete/lib/jquery.autocomplete.js');
$document->addScriptDeclaration("initAutoComplete()");	  
//$document->addStylesheet('components/com_whp/assets/jquery/jquery-autocomplete/demo/main.css');
$document->addScript('libraries/unitemplate/fancybox/jquery.fancybox-1.2.5.pack.js');
$document->addStylesheet('libraries/unitemplate/fancybox/jquery.fancybox-1.2.5.css');
$document->addScriptDeclaration('$j(document).ready(function() { $j("a.zoom").fancybox({"zoomSpeedIn":300,"zoomSpeedOut":300,"overlayShow":false});});');


$document->addScript('libraries/unitemplate/floatbox/floatbox.js.php');
$document->addStylesheet('libraries/unitemplate/floatbox/floatbox.css');
$document->addStylesheet('templates/whp/css/general.css');	

//$document->addScript("components/com_whp/assets/source/jquery.tree.js");
//$document->addScript("components/com_whp/assets/source/jquery.tree.min.js");
//echo JPATH_COMPONENT;
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
require_once( 'administrator/components/com_whp/helpers/kategoriafa.php' );
require_once( 'administrator/components/com_whp/helpers/webContent.php' );
require_once( 'administrator/components/com_whp/tables/whp_termek.php' );
require_once( 'administrator/components/com_whp/tables/whp_rendeles.php' );
require_once( 'administrator/components/com_whp/tables/whp_kategoria.php' );

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