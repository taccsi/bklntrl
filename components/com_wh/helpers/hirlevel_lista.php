<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlhirlevel_lista extends xmlParser{
	
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

	function getObj($id){
		$q = "select * from #__ic_lista where id = {$id}";
		$db = JFactory::getDBO();
		$db->setQuery($q);
		return $db->loadObject();
	}
}