<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpModelmsablon extends whpAdmin
{
	var $xmlFile = "msablon.xml";
	var $uploaded = "media/wh/images/msablon/";
	var $images = 1;
	var $tmpname = "";
	var $table = "#__whp_msablon";
	//var $table ="whp_msablon";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	$this->xmlParser = new xmlmsablon($this->xmlFile, $this->_data);
	}//function

	function store()
	   {
	   //die(str_replace("#__", "", $this->table)." *********");
		$row =& $this->getTable( str_replace("#__", "", $this->table) );
		//print_r($row);
		//die;
		$msablon_id = JRequest::getVar("id", 0);
		foreach($this->getFormFieldArray() as $parName){//ha tömböt kell menteni
			$val = JRequest::getVar($parName,"", "",2,2,2);
			if( $parName == "mezo_id" ){
				if( count($val) ){
					$q = "delete from #__whp_msablonmezo_kapcsolo where msablon_id = {$msablon_id}";
					$this->_db->setQuery($q);
					$this->_db->Query();
					foreach($val as $msablonmezo_id){
						if($msablonmezo_id){
							$o="";
							$o->msablon_id = $msablon_id;
							$o->msablonmezo_id = $msablonmezo_id;
							$this->_db->insertObject("#__whp_msablonmezo_kapcsolo", $o, "");
						}
					}
				}
			}else{
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