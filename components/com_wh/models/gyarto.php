<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelgyarto extends modelbase
{
	var $xmlFile = "gyarto.xml";
	var $uploaded = "media/wh/images/gyartok/";
	var $images = 1;
	var $tmpname = "";
	var $table = "#__wh_gyarto";
	//var $table ="wh_gyarto";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	$this->xmlParser = new xmlgyarto($this->xmlFile, $this->_data);
	}//function

}// class
?>