<?php
defined('_JEXEC') or die('=;)');

class whModelhirlevel extends modelbase {
	var $xmlFile = "hirlevel.xml";
	var $uploaded = "components/com_wh/uploaded";
	var $tmpname = "";
	var $table = "#__wh_hirlevel";
	var $adag = 50;
	//var $table ="wh_hirlevel";

	function __construct() {
		parent::__construct();
		//die;
		$this -> value = JRequest::getVar("value", "");
		$this -> getData();
		$this -> xmlParser = new xmlhirlevel($this -> xmlFile, $this -> _data);
		//$this->document->addScriptDeclaration("\$j(document).ready(function(){ initDateField()})");
		//die('valami'); 
	}//function

	function kuldHirlevel(){
		ob_start();
		global $mainframe;
		$hirlevel_id = jrequest::getVar("hirlevel_id", "" );
		$lista_id = JRequest::getVar( "lista_id", "" );
		$tol = jrequest::getVar("tol", "" );
		$h = $this->getObj( "#__wh_hirlevel", $hirlevel_id );
		//print_r($h);
		//die;
		$h->kuldes_datuma = date("Y-m-d H:i:s", time() );
		$this->_db->updateObject( "#__wh_hirlevel", $h, "id" );

		$q = "select count(cim.id) from #__wh_hirlevel_cim as cim
		left join #__wh_hirlevel_cim_lista_kapcs as kapcs on cim.id = kapcs.cim_id
		where kapcs.lista_id = '{$lista_id}'
		and cim.aktiv = 'igen'
		";
		
		$this->_db->setQuery($q);
		$osszes = $this->_db->loadResult( );

		$q = "select cim.* from #__wh_hirlevel_cim as cim
		left join #__wh_hirlevel_cim_lista_kapcs as kapcs on cim.id = kapcs.cim_id
		where kapcs.lista_id = '{$lista_id}'
		and cim.aktiv = 'igen'
		limit {$tol}, {$this->adag}
		"; 
		//echo $q;
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		foreach($rows as $r){
			$email = $r->email;
			$body = "";
			$body .= "<h1>{$h->nev}</h1>";
			$body .= "{$h->leiras}";
			$body .= "<br />-------------------------------------------------------------------------<br />";
			$body .= jtext::_("UDVOZLETTEL")."<br />";		
			$link = "http://valamidomain.hu";
			$body .= "<a href=\"{$link}\">{$link}</a>";
			$from = "info@valamidomain.hu";
			$fromname = "info@valamidomain.hu";
			$subject= "{$h->nev} - valamidomain.hu";
			$mode = 1;
			$recipient=array();
			$recipient[]="szabolcs@trifid.hu";
			$recipient[]= $email;
			//$recipient[]= "mariomedia@mediacenter.hu";
			JUtility::sendMail( $from, $fromname, $recipient, $subject, $body, $mode );
		}
		echo $this->_db->getQuery();
		echo $this->_db->getErrorMsg();
		$ob = ob_get_contents();
		ob_end_clean();
		$r = "";
		$r->html = "";
		$r->error = "";
		$r->osszes = $osszes;
		$r->tol = ( $tol + $this->adag < $osszes ) ? $tol + $this->adag : 0;
		$r->kuldes_datuma = $h->kuldes_datuma;
		return $this->getJsonRet( $r );
	}
	//index.php?option=com_wh&controller=hirlevel&task=getSzallitasiDijtetelek&format=raw&hirlevel_id=1
	function delete(&$jTable){

		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable($jTable);
		//print_r($row);exit;
		if (count( $cids ))
		{
			foreach($cids as $cid) {

				$this->delFajlok( $cid );
				if (!$row->delete( $cid )) {
					$this->setError( $row->getError() );
					return false;
				}
			}//foreach
		}
		return true;
	}//function

	function delFajlok($id){
		$q = "select id from #__wh_hirlevel_fajl as f where kapcsolo_id = {$id}";
		$this->_db->setQuery($q);
		$this->_db->Query();
		$rows = $this->_db->loadResultArray();
		foreach($rows as $id_){
			//echo $id_."<br />";
			$this->torolFajl( $id_ );
		}
		//die;
		//echo $this->_db->getQuery();
		echo $this->_db->getErrorMsg();
	}

	function getOlvasottsag(){
		ob_start();
		$hirlevel_id = jrequest::getVar( "hirlevel_id", 0 );
		if($hirlevel_id){
			$q = "select cim.*, stat.megtekintes_datum as olvasott_datum from #__wh_hirlevel_cim as cim
			inner join #__wh_hirlevel_statisztika as stat on stat.cim_id = cim.id
			where stat.hirlevel_id = {$hirlevel_id}
			group by cim.id
			";
			$this->_db->setQuery($q);
			//echo $this->_db->getquery();
			$rows = $this->_db->loadObjectList();
			//print_r($rows);
			$arr = array();
			$osszes = $olvasott = 0;
			foreach($rows as $r){
				$o = "";
				$o->NEV_EMAIL = $r->nev." / ".$r->email;
				//$o->CIM_ID = $r->id;
				$datum = $this->getOlvasott( $r->id, $hirlevel_id );
				$o->OLVASOTTSAG = ( $datum ) ? "<img title=\"{$datum}\" alt=\"{$datum}\" src=\"images/tick.png\" > " :"<img src=\"images/publish_x.png\" > " ;
				$arr[]=$o;
				( $datum ) ? $olvasott++ : $olvasott;
				$osszes++;
			}
			$listazo = new listazo( $arr, "olvasott" );
			echo jtext::_("OLV_OSSZ")." {$olvasott} / {$osszes}";
			echo $listazo->getLista();
			echo $this->_db->getErrorMsg( );
		}else{
			echo "&nbsp;";
		}
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function getOlvasott( $cim_id, $hirlevel_id ){
		$q = "select megtekintes_datum from #__wh_hirlevel_statisztika as olvasott
		where hirlevel_id = {$hirlevel_id} and cim_id = {$cim_id} ";
		$this->_db->setQuery($q);
		$datum = $this->_db->loadResult();
		if ($datum>0){
			return $datum;
		} else {
			return 0;
		}
	
	}

	function store(){
		$row =& $this->getTable("wh_hirlevel"); 
		foreach($this->getFormFieldArray() as $parName){//ha t�mb�t kell menteni
			$val = JRequest::getVar($parName,"", "",2,2,2);
			//echo $val."---<br />";
			if( $val ){
				if(is_array($val)){
					$data[$parName] = ",".implode(",", $val).",";
					//echo $data[$parName]."<br />";
				}else{
					$data[$parName] = $val;
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
			 //$this->saveOneletrajzok($id);
		  }
		  //die("-{$id}");

		$this->torolFajlok();			
		 $this->mentFajlok( $id );			
		  
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