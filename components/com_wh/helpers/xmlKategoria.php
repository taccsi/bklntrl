<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlKategoria extends xmlParser{
	
 
	function getSorrend($node){
		ob_start();
		$name = $node->getAttribute('name');
		$value=$this->getAktVal($name);
		$szulo = $this->getAktVal("szulo");
		$id = $this->getAktVal("id");		
		$q = "select count(id) as sorrend from #__wh_kategoria where szulo = {$szulo} and id <> {$id}";
		$this->db->setQuery($q);
		if( !$value || 1 ) $value = $this->db->loadResult();
		//echo JHTML::_('Select.genericlist', $rows, $name, array("class"=>"alapinput"), "value", "option", $value);
		?>
        <input name="<?php echo $name ?>" id="<?php echo $name ?>" value="<?php echo $value ?>" type="hidden_"  />
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

   function getSzuloKategoria($node) {
		$cond_kategoria_szulo = $this->getSessionVar( "cond_kategoria_szulo" );      	
		$name = $node->getAttribute('name');
      	$value=$this->getAktVal($name);
		//( $value ) ? $value : $value = $cond_kategoria_szulo;
	  	$id = $this->getAktVal("id");
	  	if(!$id) $id = 0;
	  	$kategoriafa = new kategoriafa( array($id), 5000, $cond_kategoria_szulo );
	  	$arr = array();
		 $o->option = $o-> value = "";
	     array_unshift($kategoriafa ->catTree, $o);		
      	return JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name, array("class"=>"alapinput"), "value", "option", $value);
   }
   
   function getSablon($node){
		$cond_kategoria_szulo = $this->getSessionVar( "cond_kategoria_szulo" );
		
		//$this->
		if( $this->getObj("#__wh_kategoria", $cond_kategoria_szulo )->nev == "admin"){
			$name = $node->getAttribute('name');
			$value=$this->getAktVal($name);
			$q = "select id as `value`, nev as `option` from #__wh_msablon";
			$this->db->setQuery($q);
			$rows = $this->db->loadObjectList();
			return JHTML::_('Select.genericlist', $rows, $name, array("class"=>"alapinput"), "value", "option", $value);
		}else{
			return "-";
		}
   }
   function getKepek($node){
	   return "lefut";
   }
}