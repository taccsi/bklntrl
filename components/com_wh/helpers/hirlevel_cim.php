<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlhirlevel_cim extends xmlParser{
	function getKuldesDatuma($node){
		$obj = $this->getObj($this->getAktVal("id"));
		ob_start();
        echo $obj->datum;
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function getObj($id){
		$q = "select * from #__mer_email where id = {$id}";
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
		?><input name="<?php echo $name ?>" value="<?php echo $value ?>" type="hidden" /><?php
		echo $value;
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
}