<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelkomment extends modelbase
{
	var $xmlFile = "komment.xml";
	var $uploaded = "media/wh/images/kommentek/";
	var $images = 1;
	var $tmpname = "";
	var $table = "#__wh_ertekeles";
	//var $table ="wh_komment";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	$this->xmlParser = new xmlkomment($this->xmlFile, $this->_data);
	}//function

}// class
?>