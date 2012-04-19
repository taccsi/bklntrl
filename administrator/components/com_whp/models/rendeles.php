<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpModelrendeles extends whpAdmin
{
	var $xmlFile = "rendeles.xml";
	var $uploaded = "media/wh/images/rendeles/";
	var $images = 1;
	var $tmpname = "";
	var $table = "#__whp_rendeles";
	//var $table ="whp_rendeles";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	$this->xmlParser = new xmlrendeles($this->xmlFile, $this->_data);
	}//function

	function store()
	   {
	   //die(str_replace("#__", "", $this->table)." *********");
		$row =& $this->getTable( str_replace("#__", "", $this->table) );
		//print_r($row);
		//die;
		foreach($this->getFormFieldArray() as $parName){//ha tömböt kell menteni
			$val = JRequest::getVar($parName,"", "",2,2,2);
			//echo $parName."---<br />";
			if(!in_array($parName, array("user_id", "datum" ))){
				if(is_array($val)){
					$data[$parName] = ",".implode(",", $val).",";
				}else{
					$data[$parName] = $val;
				}
			}
		}

		  if (!$row->bind($data)) {
			 $this->setError($this->_db->stderr());
			 return false;
		  }
			//print_r($data);
			//die;
			
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
			//die("--{$id}");
			if( method_exists( $this->xmlParser, "saveImages") ){
				$this->xmlParser->saveImages($id);
			}
			if( method_exists( $this->xmlParser, "saveFiles") ){
				$this->xmlParser->saveFiles($id);
			}
			if( method_exists( $this->xmlParser, "saveSpecifikacio") ){
				$this->xmlParser->saveSpecifikacio($id);
			}
			if( method_exists( $this->xmlParser, "saveGoogleKoord") ){
				$this->xmlParser->saveGoogleKoord($id);
			}

 			//die("{$id} - -");	
		  return $id;
	  }   	

}// class
?>