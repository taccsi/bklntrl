<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlMsablon_mezo extends xmlParser{

	function getCountry(){
		$this->db->setQuery("select `name` as `option`, id as `value` from #__ic_country order by `name` asc");
		$rows = $this->db->loadObjectList();
		$country= modelBase::getSessionVar("country_id");
		if(JRequest::getVar("fromlist","")){
			$cid = JRequest::getVar("cid",array());
			$obj = $this->getObj($cid[0]);
			@$country = explode(",", $obj->country_id);
		}
		return JHTML::_('Select.genericlist', $rows, "country_id[]", array("multiple"=>"multiple"), "value", "option", $country);
	}

	function getObj($id){
		$q = "select * from #__ic_beszallito where id = {$id}";
		$db = JFactory::getDBO();
		$db->setQuery($q);
		return $db->loadObject();
	}
/*	
	function getMsablonId($node){
		ob_start();
		$name = $node->getAttribute('name');
		$value=$this->getSessionVar($name);
		?>
        <input name="<?php echo $name ?>" id="<?php echo $name ?>" value="<?php echo $value ?>" type="hidden"  />
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;		
	}
	*/
}