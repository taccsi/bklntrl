<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpModelgyarto extends whpAdmin
{
	var $xmlFile = "gyarto.xml";
	var $uploaded = "media/wh/images/gyarto/";
	var $images = 1;
	var $tmpname = "";
	var $table = "#__whp_gyarto";
	//var $table ="whp_gyarto";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	$this->xmlParser = new xmlGyarto($this->xmlFile, $this->_data);
	}//function

}// class
?>