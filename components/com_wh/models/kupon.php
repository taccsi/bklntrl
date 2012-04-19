<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelkupon extends modelbase
{
	var $xmlFile = "kupon.xml";
	var $uploaded = "";
	var $tmpname = "";
	var $table = "#__wh_kupon";
	//var $table ="wh_kategoria";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");
		$this->getData();
	 	$this->xmlParser = new xmlkupon($this->xmlFile, $this->_data);
	}//function

}// class
?>