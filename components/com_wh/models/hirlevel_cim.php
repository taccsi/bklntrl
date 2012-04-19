<?php
defined('_JEXEC') or die('=;)');

class whModelhirlevel_cim extends modelbase {
	var $xmlFile = "hirlevel_cim.xml";
	var $uploaded = "components/com_wh/uploaded";
	var $tmpname = "";
	var $table = "#__wh_hirlevel_cim";
	//var $table ="wh_hirlevel_cim";

	function __construct() {
		parent::__construct();
		//die;
		$this -> value = JRequest::getVar("value", "");
		$this -> getData();
		$this -> xmlParser = new xmlhirlevel_cim($this -> xmlFile, $this -> _data);
		//$this->document->addScriptDeclaration("\$j(document).ready(function(){ initDateField()})");
	}//function

	//index.php?option=com_wh&controller=hirlevel_cim&task=getSzallitasiDijtetelek&format=raw&hirlevel_cim_id=1


	function getVideokList(){
		return $this->getdirContents("../images/videok/");
	}

	function getdirContents($slashdir) {
		ob_start();
		$dh = opendir($slashdir);
		while (($file = readdir ($dh))) {
			if (is_dir($slashdir . $file) && $file != "." && $file != "..") {
				getdirContents ($slashdir . $file . "/");
			} else if ($file != "." && $file != "..") {
				echo $file . "<br>";
			}
		}
		closedir($dh);
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function store()
	   {
		$row =& $this->getTable("wh_hirlevel_cim");
		foreach($this->getFormFieldArray() as $parName){//ha t�mb�t kell menteni
			$val = JRequest::getVar($parName,"", "",2,2,2);
			//echo $val."---<br />";
			if( $val ){
				if(is_array($val)){
					$data[$parName] = ",".implode(",", $val).",";
					//echo $data[$parName]."<br />";
				}else{
					$data[$parName] = $val;
					//echo $data[$parName]."<br />";
				}
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
			 $this->saveOneletrajzok($id);
		  }
		  $this->mentListaCimKapcs($id);
		  //die("-{$id}");
		  return $id;
	  }   	
	
	function mentListaCimKapcs($id){
		$lista_idk = Jrequest::getvar('lista_id');
		
		//betesz
		foreach ($lista_idk as $l_id){
			$q = "select * from #__wh_hirlevel_cim_lista_kapcs where cim_id = {$id} and lista_id = {$l_id}";
			$this->_db->setquery($q);
			
			if (!$this->_db->loadobject()){
				$o = '';
				$o->cim_id = $id;
				$o->lista_id = $l_id;
				$o->datum = date('Y-m-d H:i:s');
				$this->_db->insertobject("#__wh_hirlevel_cim_lista_kapcs",$o);
				
			}
		}
		//ami nincs pipalva, kivesz
		$lista_idk = implode(',',$lista_idk);
		$q = "delete from #__wh_hirlevel_cim_lista_kapcs where cim_id = {$id} and lista_id not in ({$lista_idk})";
		$this->_db->setquery($q);
		$this->_db->query();
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

   function getOneletrajzok(){
      ob_start();
      echo '<table class="table_letolt" >';
      for($n=1; $n<=$this->images; $n++){
         if(@$this->_data->id){
            $docname ="{$this->uploaded}/{$this->_data->id}_{$n}.doc"; 
            //echo $docname." -- - - - -<br />";
            if(file_exists($docname)){
               //$link = "";
			   ?><tr><td class="key"><?php echo "{$n}. ".JText::_("ONELETRAJZ"); ?></td>
               <td>
               	<a href="<?php echo $docname ?>">&gt;&gt;<?php echo JText::_("DOWNLOAD"); ?>&lt;&lt;</a>
               </td>
               <td>
               	<input type="checkbox" name="<?php echo "torol_img_{$n}" ?>" value="1" /> </td>
                <td><?php echo JText::_("delete") ?></td>
               </tr>
               <?php
            }else{
               ?> 
               <tr><td class="key"><?php echo "{$n}. ".JText::_("ONELETRAJZ"); ?></td>
               <td><input type="file" name="<?php echo "img_{$n}" ?>" /></td></tr>
               <?php
            }
         }else{
            //echo JText::_("UPLOAD AFTER APPLY");
         }
      }
      echo '</table>';  
      $ret = ob_get_contents();
      ob_end_clean();
      return $ret;   
   }
	  

}