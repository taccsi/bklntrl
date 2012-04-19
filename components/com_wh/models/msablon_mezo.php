<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelmsablon_mezo extends modelbase
{
	var $xmlFile = "msablon_mezo.xml";
	var $uploaded = "components/com_wh/uploaded";
	var $tmpname = "";
	var $table = "#__wh_msablonmezo";
	//var $table ="wh_msablon_mezo";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	$this->xmlParser = new xmlmsablon_mezo($this->xmlFile, $this->_data);
	}//function

	function store()
	   {
		$row =& $this->getTable(str_replace("#__", "" , $this->table) );
		//$row =& $this->getTable("vcmr_msablon_mezo");		
		//die("----");
		foreach($this->getFormFieldArray() as $parName){//ha tömböt kell menteni
			$val = JRequest::getVar($parName,"", "",2,2,2);
			//echo $val."---<br />";
			if(is_array($val)){
				$data[$parName] = ",".implode(",", $val).",";
				//echo $data[$parName]."<br />";
			}else{
				$data[$parName] = $val;
			}
		}
//die;
		  // Bind the form fields to the hello table
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
		   //die("hiba");
		   return false;
		  }else{
			 //echo "--------------".;
		   $id = $this->_db->insertId();
			 if(!$id){
			 $id = $this->getSessionVar("id");
		   }
			 //$this->saveOneletrajzok($id);
		  }
		  //die("-{$id}");
		  return $id;
	  }   	

   function getBelsoMenu(){
      ob_start();
		$msablon_mezo_id = $this->xmlParser->getAktVal("id");
		$msablon_mezo_nev = urlencode($this->xmlParser->getAktVal("nev"));
		?>
		
        <?php
      $ret = ob_get_contents();
      ob_end_clean();
      return $ret;   
   }
   
   function getmsablon_mezo($id)
   {
   	$this->_db->setQuery("SELECT * FROM #__wh_msablonmezo WHERE id = {$id}");
	return $this->_db->loadObject();
   }

	  
}// class
?>