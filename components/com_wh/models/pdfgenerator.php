<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelPdfGenerator extends modelbase
{
	var $xmlFile = "ajanlat.xml";
	var $images = 1;  
	var $uploaded = "../media/vs/images";
	var $tmpname = "galeria_ideiglenes.jpg";
	var $table = "#__wh_ajanlat";

	function __construct()
	{
		parent::__construct(); 
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	$this->xmlParser = new xmlAjanlat($this->xmlFile, $this->_data);
		//die("***");
		//$this->generatePdf();

	}//function

	function getPdfData(){

		foreach($this->getTableFields("#__wh_ajanlat") as $parName){
			$val = $this->getSessionVar($parName);
			//echo $parName.": {$val}-----<br />";
			if(is_array($val)){
				$val = ",".implode(",", $val).",";
			}
			if($val <> "-"){
				$o->$parName = $val;
			}
		}
		//print_r();
		$q = "select * from #__wh_beszallito where beszallitokod = '{$o->beszallitokod}' ";
		$this->_db->setQuery($q);
		$o->beszallito=$this->_db->loadObject();
		if(@$o->cimzett != @$o->beszallito->nev ){
			unset($o->beszallito);
		}else{
		}
		$q = "select * from #__users where id = {$o->tk_id}";
		$this->_db->setQuery($q);
		$o->tk=$this->_db->loadObject();
		return $o;
	}

}// class
?>