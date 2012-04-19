<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelFcsoport extends modelbase
{
	var $xmlFile = "fcsoport.xml";
	var $uploaded = "";
	var $tmpname = "";
	var $table = "#__wh_fcsoport";
	//var $table ="wh_kategoria";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");
		$this->getData();
	 	$this->xmlParser = new xmlfcsoport($this->xmlFile, $this->_data);
	}//function
	
}// class
?>