<?php
defined( '_JEXEC' ) or die( '=;)' );

$document =& JFactory::getDocument();
$document->addStyleSheet('components/com_whp/assets/css/style.css');
$document->addStyleSheet('components/com_whp/assets/js/fileuploader/fileUploader.css');
//$document->addScript( 'components/com_whp/assets/js/fileuploader/fileuploader.html' );

//$document->addScript('libraries/unitemplate/floatbox/floatbox.js.php');
//$document->addStylesheet('libraries/unitemplate/floatbox/floatbox.css');
$document->addStylesheet('templates/whp/css/general.css');	

JHTML::_('behavior.modal');
jimport('joomla.html.pane'); 
$document->addScript( 'components/com_whp/assets/js/js.js' ); 
$document->addScript( '../components/com_whp/assets/js/demo_tooltip.js' ); 

//jquery

$document->addScript( 'components/com_whp/assets/js/jquery-1.4.1.min.js' ); 
$document->addScriptDeclaration('var $j = jQuery.noConflict();');
//sortable
$document->addScript('components/com_whp/assets/js/jquery-ui-1.8.4.custom.min.js');
//uploader
$document->addScript( 'components/com_whp/assets/js/fileuploader/jquery.fileUploader.js' ); 

$document->addScript('../libraries/unitemplate/fancybox/jquery.fancybox-1.2.5.pack.js');
$document->addStylesheet('../libraries/unitemplate/fancybox/jquery.fancybox-1.2.5.css');
$document->addScriptDeclaration('$j(document).ready(function() { $j("a.zoom").fancybox({"zoomSpeedIn":300,"zoomSpeedOut":300,"overlayShow":false});});');

require_once( JPATH_COMPONENT.DS.'helpers/baseModel.php' );
require_once( JPATH_COMPONENT.DS.'helpers/xComponent.php' );
require_once( JPATH_COMPONENT.DS.'helpers/xml.php' );
require_once( JPATH_COMPONENT.DS.'helpers/kep.php' );
require_once( JPATH_COMPONENT.DS.'helpers/email.php' );
//require_once( JPATH_COMPONENT.DS.'tables/whp_termek.php' );
require_once( JPATH_COMPONENT.DS.'helpers/msablon.php' );
require_once( JPATH_COMPONENT.DS.'helpers/msablonmezo.php' );


require_once( JPATH_COMPONENT.DS.'helpers/baseController.php' );
require_once( JPATH_COMPONENT.DS.'helpers/google.php' );
require_once( JPATH_COMPONENT.DS.'helpers/termek.php' );
require_once( JPATH_COMPONENT.DS.'helpers/kategoria.php' );
require_once( JPATH_COMPONENT.DS.'helpers/gyarto.php' );
require_once( JPATH_COMPONENT.DS.'helpers/rendeles.php' );



require_once( JPATH_COMPONENT.DS.'helpers/kategoriafa.php' );

require_once( JPATH_COMPONENT.DS.'helpers/webContent.php' );
//require_once( JPATH_COMPONENT.DS.'tables/whp_termek.php' );
//require_once( JPATH_COMPONENT.DS.'tables/whp_kategoria.php' );
jimport( "unitemplate.simpleimage.simpleimage");
require_once( JPATH_COMPONENT.DS.'helpers/listazo.php' );

if( $controller = JRequest::getWord('controller', "termekek" ))
{
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if( file_exists($path))
	{
		require_once $path;
	}
}
// Create the controller

$classname = 'whpController'.$controller;
$controller = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar('task') );

// Redirect if set by the controller
//die(JRequest::getVar("task"));


$controller->redirect();
?>