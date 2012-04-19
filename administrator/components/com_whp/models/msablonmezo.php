<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpModelmsablonmezo extends whpAdmin
{
	var $xmlFile = "msablonmezo.xml";
	var $uploaded = "media/wh/images/msablonmezo/";
	var $images = 1;
	var $tmpname = "";
	var $table = "#__whp_msablonmezo";
	//var $table ="whp_msablonmezo";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	$this->xmlParser = new xmlmsablonmezo($this->xmlFile, $this->_data);
	}//function

}// class
?>