<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpModelszuz extends whpAdmin
{
	var $xmlFile = "szuz.xml";
	var $uploaded = "media/wh/images/szuz/";
	var $images = 1;
	var $tmpname = "";
	var $table = "#__whp_szuz";
	//var $table ="whp_szuz";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	$this->xmlParser = new xmlszuz($this->xmlFile, $this->_data);
	}//function

}// class
?>