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
		<div class="moduletable_usermenu">
		  <ul class="menu">
			<li class="item7"><a href="/index.php?option=com_wh&amp;controller=napilatogatasok&amp;Itemid=7&beszallito_id=<?php echo $beszallito_id ?>&tmpl=component" rel="floatbox" rev="width:716 height:550px scrolling:no"><span>Látogatásaim</span></a></li>
			<li class="item9"><span class="separator"><span>Separator</span></span></li>
			<li class="item8"><a href="/index.php?option=com_wh&amp;controller=ajanlatok&amp;Itemid=8&tmpl=component&beszallito_id=<?php echo $beszallito_id ?>" rel="floatbox" rev="width:716 height:550px scrolling:no"><span>Ajánlatok</span></a></li>
			<li class="item10"><span class="separator"><span>Separator</span></span></li>
			<li class="item12"><a href="/index.php?option=com_wh&amp;controller=reklamaciok&amp;Itemid=12&tmpl=component&beszallito_id=<?php echo $beszallito_id ?>" rel="floatbox" rev="width:716 height:550px scrolling:no"><span>Reklamáció</span></a></li>
			<li class="item13"><span class="separator"><span>Separator</span></span></li>
			<li class="item14"><a href="<?php echo JRoute::_('index.php?option=com_wh&task=showall&boxchecked=0&controller=koveteles&tmpl=component&Itemid=14&nev='.$beszallito_nev); ?>" rel="floatbox" rev="width:716 height:550px scrolling:no"><span>Követelések</span></a></li>
			<li class="item16"><span class="separator"><span>Separator</span></span></li>
			<li class="item15"><a href="<?php echo JRoute::_('index.php?option=com_wh&task=showall&boxchecked=0&controller=forgalom&tmpl=component&Itemid=14&nev='.$beszallito_nev); ?>" rel="floatbox" rev="width:716 height:550px scrolling:no"><span>Forgalom</span></a></li>
		  </ul>
		</div>
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
   
   
	function getForgalomItems()
	{
		require_once('components/com_wh/models/forgalom.php');
		$m = new whModelforgalom;
		return $m->getData();	
	}
	
	function getForgalom()
	{
		require_once('components/com_wh/models/forgalom.php');
		$m = new whModelforgalom;
		return $m->getForgalom();	
	}
	
	function getKovetelesekItems()
	{
		require_once('components/com_wh/models/koveteles.php');
		$m = new whModelkoveteles;
		return $m->getData();	
	}
	
	function getKovetelesek()
	{
		require_once('components/com_wh/models/koveteles.php');
		$m = new whModelkoveteles;
		return $m->getForgalom();	
	}
	  
}// class
?>