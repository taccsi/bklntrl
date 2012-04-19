<?php
/**
 * @version             $Id: jqueryintegrator.php revision date tushev $
 * @package             Joomla
 * @subpackage  System
 * @copyright   Copyright (C) S.A. Tushev, 2010. All rights reserved.
 * @license     GNU GPL v2.0
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
 
 // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
class plgSystemJQueryIntegrator extends JPlugin {
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param object $params  The object that holds the plugin parameters
	 * @since 1.5
	 */
	function plgSystemJQueryIntegrator( &$subject, $params )
	{
			parent::__construct( $subject, $params );
			//$this->params = new JParameter($params['params']);
	}
	
	function onAfterRoute() {
		$document = &JFactory::getDocument();
		$document->setGenerator($document->getGenerator().'; jQuery++ Intergator by tushev.org');
		
		if($this->params->get('notactivateatbackend')==1) {
			$juri = &JFactory::getURI();
			if(strpos($juri->getPath(),'/administrator/')!==false) return;
		}
		
		$version = "1.6.2";
		
		////////////////////////////////////////////////////////////////////////////////////////
		///// IMPORTANT: J!1.6: KEEP IN MIND NEW PATHS /////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////
		if($value=$this->params->get('embedjquery')) {
			if($value==1) $document->addScript(JURI::root( true )."/plugins/system/jqueryintegrator/jqueryintegrator/jquery-$version.min.js");
			elseif($value==2) $document->addScript($this->params->get('jquerycdnpath'));
			elseif($value==3) $document->addScript("http://code.jquery.com/jquery-$version.min.js");
			elseif($value==4) $document->addScript("//ajax.googleapis.com/ajax/libs/jquery/$version/jquery.min.js");
			elseif($value==5) $document->addScript("//ajax.microsoft.com/ajax/jquery/jquery-$version.min.js");
		}
		if ($this->params->get('jquerynoconflict')) {
			$document->addScript(JURI::root( true ).'/plugins/system/jqueryintegrator/jqueryintegrator/jquery.noconflict.js');
		}
		if($value=$this->params->get('embedjqueryui')) {
			if($value==1) $document->addScript(JURI::root( true ).'/plugins/system/jqueryintegrator/jqueryintegrator/jquery-ui-1.8.6.custom.min.js');
			elseif($value==2) $document->addScript($this->params->get('jqueryuicdnpath'));
		}
		if($value=$this->params->get('embedjqueryuicss')) {
			if($value==1) $document->addStyleSheet($this->params->get('jqueryuicsspath'));
		}
		if($value=$this->params->get('embedjquerytools')) {
			if($value==1) $document->addScript(JURI::root( true ).'/plugins/system/jqueryintegrator/jqueryintegrator/jquery.tools.min.js');
			elseif($value==2) $document->addScript($this->params->get('jquerytoolscdnpath'));
		}
		
		//OLD: Direct code embed (not recommended, because of mooTools conflict)
		//if ($this->params->get('jquerynoconflict')) $document->addScriptDeclaration('jQuery.noConflict();');
	}
}
?>