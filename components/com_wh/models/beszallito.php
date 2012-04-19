<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelbeszallito extends modelbase
{
	var $xmlFile = "beszallito.xml";
	var $uploaded = "components/com_wh/uploaded";
	var $tmpname = "";
	var $table = "#__wh_beszallito";
	//var $table ="wh_beszallito";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	$this->xmlParser = new xmlbeszallito($this->xmlFile, $this->_data);
	}//function

	function store()
	   {
		$row =& $this->getTable(str_replace("#__", "" , $this->table) );
		//$row =& $this->getTable("vcmr_beszallito");		
		//die("----");
		foreach($this->getFormFieldArray() as $parName){//ha t�mb�t kell menteni
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

	function saveOneletrajzok($id){
		global $mainframe;	
		//die($id);
		$db = JFactory::getDBO();
		if(!file_exists($this->uploaded)){
			mkdir($this->uploaded);
		}
		for($n=1; $n<=$this->images; $n++){
            $docname ="{$this->uploaded}/{$id}_{$n}.doc"; 
			//die($docname);
			if(JRequest::getVar("torol_img_{$n}")){
				unlink($docname);
			}else{
				$tmp_name = $_FILES["img_{$n}"]["tmp_name"];
				if($tmp_name){
					$filename = "{$this->uploaded}/{$id}_{$n}.doc";
					move_uploaded_file($tmp_name, $filename);
					chmod($filename, 0777);
				}
			}
		}
	}	

   function getBelsoMenu(){
      ob_start();
		$beszallito_id = $this->xmlParser->getAktVal("id");
		$beszallito_nev = urlencode($this->xmlParser->getAktVal("nev"));
		?>
		
        <?php
      $ret = ob_get_contents();
      ob_end_clean();
      return $ret;   
   }
   
   function getbeszallito($id)
   {
   	$this->_db->setQuery("SELECT * FROM #__wh_beszallito WHERE id = {$id}");
	return $this->_db->loadObject();
   }	
	  
}// class
?>