<?php
//die("die valamiért");
//die("Lekapcsolva, itt van atmenetileg az oldal: <a href=\"http://office.trifid.hu/bringa_fusionadmin\">http://office.trifid.hu/bringa_fusionadmin</a>/");
defined( '_JEXEC' ) or die( '=;)' );
ini_set("display_errors", 1);
ini_set("suhosin.post.max_vars", 1000);
error_reporting(E_ALL);
//$document->addScript("components/com_wh/assets/source/jquery.tree.js");
//$document->addScript("components/com_wh/assets/source/jquery.tree.min.js");
//echo JPATH_COMPONENT;

if( $controller = JRequest::getWord('controller', "kategoriak" ))
{
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if( file_exists($path))
	{
		require_once $path;
	}
}
// Create the controller

$classname = 'whController'.$controller;
$controller = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar('task') );

// Redirect if set by the controller
//die(JRequest::getVar("task"));
$controller->redirect();