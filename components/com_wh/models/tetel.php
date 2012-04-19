<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModeltetel extends modelbase
{
	var $xmlFile = "tetel.xml";
	var $uploaded = "media/wh/images/tetel/";
	var $images = 1;
	var $tmpname = "";
	var $table = "#__wh_tetel";
	//var $table ="wh_tetel";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	$this->xmlParser = new xmltetel($this->xmlFile, $this->_data);
	}//function

}// class
?>