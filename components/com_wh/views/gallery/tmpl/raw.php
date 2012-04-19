<?php
defined('_JEXEC') or die('=;)'); 
echo $this->ajaxContent;
/* 
if( $this->ajaxContent )
{
	ob_start();
	echo $this->ajaxContent;
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
*/
?>