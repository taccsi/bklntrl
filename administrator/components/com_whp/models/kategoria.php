<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpModelkategoria extends whpAdmin
{
	var $xmlFile = "kategoria.xml";
	var $tmpname = "";
	var $table = "#__whp_kategoria";
	var $images = 1;
	var $uploaded = "media/whp/kategoriak/";
	
	
	//var $table ="whp_kategoria";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");
		$this->getData();
	 	$this->xmlParser = new xmlkategoria($this->xmlFile, $this->_data);
	}//function
	
}// class
?>