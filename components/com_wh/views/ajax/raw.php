<?php
/**
 * @version SVN: $Id$
 * @package    xxx
 * @subpackage Views
 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author      {@link }
 * @author     Created on 07-May-2010
 */

//-- No direct access
defined('_JEXEC') or die('=;)');


if( $this->form )
{
	ob_start();
	echo $this->form;
	$document =& JFactory::getDocument();
	//$document->addScript( JURI::root(true).'/components/com_ftv/assets/js/demo_ajax.js' );
	?>
    <script src="components/com_ftv/assets/js/demo_ajax.js">
    ajaxHivas();
    </script>
    <?php
	$r = ob_get_contents();
			
	ob_end_clean();
	$response['html'] = $r;
	$response['msg'] = JText::_('RECIEVED_OK');
}
else
{
	$response['html'] = 'ERROR';
	$response['msg'] = JText::_('RECIEVED_ERROR');
}

if( function_exists('json_encode') )
{
   echo ( json_encode( $response ) );
}
else
{
   //--seems we are in PHP < 5.2... or json_encode() is disabled
   require_once( JPATH_COMPONENT.DS.'helpers'.DS.'json.php' );
   $json = new Services_JSON();
   echo $json->encode( $response );
}
?>