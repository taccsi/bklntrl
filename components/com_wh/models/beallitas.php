<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelbeallitas extends modelbase
{
	var $xmlFile = "beallitas.xml";
	var $uploaded = "";
	var $tmpname = "";
	var $table = "#__wh_beallitas";
	//var $table ="wh_beallitas";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	$this->xmlParser = new xmlBeallitas($this->xmlFile, $this->_data);
	}//function
	
	function torolSzazalekKat(){
		$torlendo_haszonk = JRequest::getVar("torolHaszonkulcs");
		
		$q = "select * from #__wh_beallitas";
      	$this->_db->setQuery($q);
      	$rows = $this->_db->loadObjectList();
		foreach($rows as $row){
			$haszonkulcsok = array();
			$haszonkulcsok = $row->szazalek_kat;
			$haszonkulcsok = unserialize($haszonkulcsok);
			unset($haszonkulcsok[$torlendo_haszonk]);
			$ujhaszonk = serialize($haszonkulcsok);
			$q = "update #__wh_beallitas set szazalek_kat = '{$ujhaszonk}'";
			$this->_db->setQuery($q);
			$this->_db->Query();
		}
	}
	
	function torolWs(){
		$torlendo_kulcs = JRequest::getVar("torol_Ws");
		$q = "select webshop_kat from #__wh_beallitas";
      	$this->_db->setQuery($q);
      	$rows = $this->_db->loadObjectList();
		foreach($rows as $row){
			$beallitasok = array();
			$beallitasok = $row->webshop_kat;
			$beallitasok = unserialize($beallitasok);
			unset($beallitasok[$torlendo_kulcs]);
			$ujbeallitasok = serialize($beallitasok);
			$q = "update #__wh_beallitas set webshop_kat = '{$ujbeallitasok}'";
			$this->_db->setQuery($q);
			$this->_db->Query();
		}
	}
	function torolFelh(){
		$torlendo_kulcs = JRequest::getVar("torol_Felh");
		$q = "select felh_kat from #__wh_beallitas";
      	$this->_db->setQuery($q);
      	$rows = $this->_db->loadObjectList();
		foreach($rows as $row){
			$beallitasok = array();
			$beallitasok = $row->felh_kat;
			$beallitasok = unserialize($beallitasok);
			unset($beallitasok[$torlendo_kulcs]);
			$ujbeallitasok = serialize($beallitasok);
			$q = "update #__wh_beallitas set felh_kat = '{$ujbeallitasok}'";
			$this->_db->setQuery($q);
			$this->_db->Query();
		}
	}

	function store()
	   {
	   //die(str_replace("#__", "", $this->table)." *********");
		$row =& $this->getTable( str_replace("#__", "", $this->table) );
		foreach($this->getFormFieldArray() as $parName){//ha tömböt kell menteni
			$val = JRequest::getVar($parName,"", "",2,2,2);
			//echo $val."---<br />";
			if(is_array($val)){
				$data[$parName] = ",".implode(",", $val).",";
			}else{
				$data[$parName] = $val;
			}
		}
		
		if(JREquest::getVar("user_id", "")){
			$user_id = JREquest::getVar("user_id", "");
			$kat_id = JREquest::getVar("kat_id1", "" );
			$felh_kat = unserialize($data["felh_kat"]);
			if($user_id && $kat_id){
				$felh_kat[$user_id]=implode("," ,$kat_id);			
			}	
			$data["felh_kat"]= serialize($felh_kat);
		}

		if(JREquest::getVar("webshop_id", "")){
			$webshop_id = JREquest::getVar("webshop_id", "");
			$kat_id2 = JREquest::getVar("kat_id2", "" );
			$webshop_kat = unserialize($data["webshop_kat"]);	
			if($webshop_id && $kat_id2){
				$webshop_kat[$webshop_id]=implode("," ,$kat_id2);}					
			$data["webshop_kat"]= serialize($webshop_kat);
		}
		if(JREquest::getVar("szazalek_id", "")){
			$szazalek_id = JREquest::getVar("szazalek_id", "");
			$kat_id3 = JREquest::getVar("kat_id3", "" );
			$szazalek_kat = unserialize($data["szazalek_kat"]);
			$egyezes = "hamis";
			foreach($szazalek_kat as $szazalek => $kateg_id){
				$katid=array();
				$katid=explode(",",$kateg_id);
				foreach($katid as $kategoria){
					foreach($kat_id3 as $kteg){
						if($kteg == $kategoria){
							echo $kategoria,'<br/>';
							echo $kteg,'<br/>';
							$egyezes="igaz";
						}
					}	
				}
			}
			if($szazalek_id && $kat_id3 && $egyezes=="hamis"){
				$szazalek_kat[$szazalek_id]=implode("," ,$kat_id3);}
			if($egyezes=="hamis"){					
			$data["szazalek_kat"]= serialize($szazalek_kat);}
		}
	
		  if (!$row->bind($data)) {
			 $this->setError($this->_db->stderr());
			 return false;
		  }
	
		  // Make sure the record is valid
		  if (!$row->check()) {
			 $this->setError($this->_db->stderr());
			 return false;
		  }
	
		  // Store the table to the database
		  //print_r($row); exit;
		  if (!$row->store()) {
			 $this->setError( $row->getError() );
		   return false;
		  }else{
			//die($this->_db->getQuery()); 
			//echo "--------------".;
		   $id = $this->_db->insertId();
			 if(!$id){
			 $id = $this->getSessionVar("id");
		   }
		  }
		  return $id;
	  }   	
	
}// class
?>
