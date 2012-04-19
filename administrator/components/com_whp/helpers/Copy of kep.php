<?php
defined( '_JEXEC' ) or die( '=;)' );
class kep extends xmlParser{
	var $images = 1;
	var $kepPrefix = "";
   function saveImages($id){
      global $mainframe;   
      $db = JFactory::getDBO();
      if(!file_exists($this->uploaded)){
         mkdir($this->uploaded);
      }
      $index=0;
      $obj = $this->getObj( $this->table, $id );
      //$arrKepek = (array)unserialize( $obj->kepek );
      $kepalairas = JRequest::getVar( "kepalairas", array() );
      $delKep = JRequest::getVar( "delKep", array() );
      $sorrendKep = JRequest::getVar( "sorrendKep", array() );
		//die;
      $arrKepek = array();
      foreach($delKep as $index){
         $imgname =$this->getKepNev($id, $index);
         if(file_exists($imgname)){
            unlink($imgname);
         }
      }
      for($index=0; $index < $this->images; $index++){
         $tmp_name=$_FILES["kepInput"]["tmp_name"][$index];
         $imgname =$this->getKepNev($id, $index);
         if($tmp_name){
            move_uploaded_file($tmp_name, $imgname);
            chmod($imgname, 0777);
         }else{

         }
         $o="";
         $o->SORREND = $sorrendKep[$index];
         $o->KEPALAIRAS = $kepalairas[$index];
         $arrKepek[]=$o;
      }
      //print_r($arrKepek);
      //die;
      $o="";
      $o->id=$id;
      $o->kepek=serialize($arrKepek);
	  //die;
      $this->db->updateObject($this->table, $o, "id");
      //die("{$id}");
   }  
   
	function upload(){
		$tmp_name=$_FILES["kepFajl"]["tmp_name"];
		$o = "";
		$o->termek_id = jrequest::getVar("termek_id", 0);
		$q = "select sorrend from #__whp_kep where termek_id = {$termek_id} order by sorrend desc limit 1";
		$this->_db->setQuery($q);
		$o->sorrend = (int)$this->_db->loadResult()+1;
		$this->_db->insertObject("#__whp_kep", $o, "id");
		$kep_id = $this->_db->insertId();
		$imgname =$this->getKepNev( $kep_id, $o->sorrend);
		if($tmp_name){
			move_uploaded_file($tmp_name, $imgname);
			chmod($imgname, 0777);
		}else{
		
		}
		$o="";
		$o->SORREND = $sorrendKep[$index];
		$o->KEPALAIRAS = $kepalairas[$index];
		$arrKepek[]=$o;
		
		if (1){
			//ouput 'success' inside <div id="output">
			echo '<div id="output">success</div>';
			//then output your message (optional)
			//echo '<div id="message">Your file success message</div>';
		}else{
			//ouput 'failed' inside <div id="output">
			echo '<div id="output">failed</div>';
			echo '<div id="message">File Upload error message</div>';
		}
	//die();
	}
   
   function getKepek($node) {
    ob_start();
	$document = jfactory::getDocument();
	$document->addScriptDeclaration("\$(document).ready(function(){\$('#uploader').fileUploader() });");
	$termek_id = $this->getAktval("id");
	$action = "index.php?option=com_whp&controller=termek&task=upload&termek_id={$termek_id}";
	echo $action;
	?>
	<form action="<?php echo $action ?>" method="post" enctype="multipart/form-data">
		<input id="uploader" name="kepFajl" type="file" />
		<input type="submit" value="Upload" id="pxUpload" />
		<input type="reset" value="Clear" id="pxClear" />
	</form>      
      <?php
	  $name = $node->getAttribute('kepek');
      if($id=$this->getAktVal("id")){
         $kepek = unserialize( $this->getAktVal("kepek") );
         //print_r($kepek);
         $arr = array();
         for($index=0; $index < $this->images; $index++){
            $o = "";
            $o->SORSZ = ($index+1)."."; 
            $o->HIDDEN = $this->getKepHTML ( $this->getKepNev($id, $index) ); 
            $o->FELTOLTES = $this->getKepInput ($id, $index ); 
            $o->KEPALAIRAS = $this->getKepAlairasInput( @$kepek[$index]->KEPALAIRAS );
            $o->SORREND = $this->getSorrendSelect($kepek, $index);
            $o->TORLES = "<input class\"TORLES\" type=\"checkbox\" name=\"delKep[]\" value=\"{$index}\" />";
            $arr[] = $o;
         }
         $listazo = new listazo($arr,"kepfeltoltes");
         echo $listazo->getLista();
      }
      $ret = ob_get_contents();    
      ob_end_clean();
      return $ret;
      // die($readonly);
   }
   
   function getSorrendSelect($kepek, $index){
      (@$kepek[$index]->SORREND) ? $sorrend = @$kepek[$index]->SORREND : $sorrend = $index;
      $arr =array();
      for($i=1; $i<=$this->images; $i++){
         $o=new stdClass;
         $o->option=$o->value= $i;
         $arr[]=$o;
      }
      return JHTML::_('Select.genericlist', $arr, "sorrendKep[]", array("class"=>"SORREND"), "value", "option", $sorrend);
   }

   function getKepHTML($kepNev){
      ob_start();
      if(file_exists($kepNev)){
         ?><img style=" width:30px" src="<?php echo $kepNev ?>" />
         <?php
      }
      $ret = ob_get_contents();    
      ob_end_clean();
      return $ret;
   }

   function getKepAlairasInput($v=""){
      ob_start();
      ?><input name="kepalairas[]" type="text" value="<?php echo $v ?>"  /><?php
      $ret = ob_get_contents();
      ob_end_clean();
      return $ret;
   }

   function getKepInput( $id, $index ){
      ob_start();
      $kepNev = $this->getKepNev($id, $index);
      if(file_exists($kepNev)){
         ?>
<input type="file" name="kepInput[]" />
<?php
      }else{
         ?>
<input type="file" name="kepInput[]" />
<?php
      }
      $ret = ob_get_contents();    
      ob_end_clean();
      return $ret;
   }

   function getKepNev($id, $index){
      ( strstr( realpath("."), "administrator" ) ) ?  $x="../" : $x="";
      $dir = "{$x}{$this->uploaded}/";
      if( !file_exists($dir) ){
         mkdir($dir);
      }
	  //echo "{$x}{$dir}{$this->kepPrefix}{$id}_{$index}.jpg";
      return "{$dir}{$this->kepPrefix}{$id}_{$index}.jpg";
   }
}
?>