<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelkep extends modelbase
{
	var $xmlFile = "kep.xml";
	var $uploaded = "media/wh/termekek/";
	var $tmpname = "";
	var $table = "#__wh_kep";
	var $images = 1;
	//var $table ="wh_kategoria";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");
		$this->getData();
	 	$this->xmlParser = new xmlkep($this->xmlFile, $this->_data);
		//print_r($this->xmlParser);
	}//function
	
	function torolKep( $kep_id ){
		return $this->delObj("#__wh_kep", $kep_id);
	}
	
	function hozzaadKep( $termek_id ){
		$this->_db->setQuery("select max(sorrend) as utolso_kep from #__wh_kep where termek_id={$termek_id}");
		$max = $this->_db->loadObject();
		$utolso_kep=$max->utolso_kep;
		if($utolso_kep){
			$utolso_kep++;
		}else{ $utolso_kep=1;}
		
		$o="";
		$o->termek_id = $termek_id;
		$o->sorrend = $utolso_kep;
		
		$this->_db->insertObject("#__wh_kep", $o, "id");
		return $this->_db->insertId( );
	}
	
}// class
?>