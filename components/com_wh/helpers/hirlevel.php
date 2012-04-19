<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlhirlevel extends xmlParser{
	function getDbCim($item){
		$q = "select count(cim_id) as db from #__mer_cim_lista_kapcs where lista_id = {$item->value}";
		$this->_db->setQuery($q); 
		$res = $this->_db->loadResult();
		$item->option.=" ({$res} db email)";
		return $item;
	}	 

	function getFolyamat($node){
		$hirlevel_id = $this->getAktVal("id");
		$q = "select * from #__wh_hirlevel where id = {$hirlevel_id}";
		$this->_db->setQuery($q);
		$h = $this->_db->loadObject();
		//print_r( $h );
		//die;
		$ret = "";
		//$this->document->addScriptDeclaration("\$j(document).ready(function(){getFolyamat();})");
		$ret = JText::_("UTOLSO_KULDES_DATUMA").": ".$h->kuldes_datuma."<br>";
		$ret .= "<a id=\"a_hirlevel_kuldes\" href=\"javascript:;\" onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){kuldHirlevel('{$hirlevel_id}', '0' )}\" >".jtext::_("KULDES_MOST")."</a>"; 
		$ret .= "<br><span id=\"ajaxContentFolyamat\" ></span>";
		$ret .= "<span id=\"ajaxContentCsiga\" >&nbsp;</span>";
		$ret .= "<div id=\"progressbar\"></div>";
		return $ret;
	}

	function getObj($id){
		$q = "select * from #__mer_hirlevel where id = {$id}";
		$db = JFactory::getDBO();
		$db->setQuery($q);
		return $db->loadObject();
	}

	function getUserId(){
		$user = JFactory::getUser();
		return "<input name=\"userid\" value=\" {$user->id}\" type=\"hidden\" >";
	}

	function getAktDatum($node){
		$name = $node->getAttribute('name');
		$value=$this->getAktVal($name);
		if(!$value || $value = '0000-00-00 00:00:00' ){
			$value = date("Y-m-d h:i", time());
		}
		ob_start();
		?>
        <input name="<?php echo $name ?>" value="<?php echo $value ?>" type="hidden" /><?php echo $value ?>
        <?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
}