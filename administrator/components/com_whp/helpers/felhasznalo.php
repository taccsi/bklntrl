<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlFelhasznalo extends xmlParser{
	function getObj($table, $id, $pk ="id" ){
		$this->db=jfactory::getdbo();
		$q = "select * from {$table} where {$pk} = $id limit 1";
		$this->db->setQuery($q);
		return $this->db->loadObject();
	}	
	
	function getElfelejtett($node){
		ob_start();
		$name = $node->getAttribute('name');
		$value = $node->getAttribute('value');
		?>
		<a class="" href="<?php echo jroute::_('index.php?option=com_user&view=reset&Itemid={$Itemid}') ?>"><?php echo jtext::_($value) ?></a>
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;   			

	}
	
	function getUserData($node, $ajaxObj=""){
		ob_start();
		$name = $node->getAttribute('name');
		$js = $node->getAttribute('js');
		if(@$this->user->id && in_array($name, array("username", /*"name"*/) ) ){
			@$value = $this->user->$name;
			$readonly = "readonly=\"readonly\"";		
		}elseif( $this->user->id ){
			$obj = $this->getObj("#__users", $this->user->id);
			$value = $obj->$name;
			$readonly = "";		
			//$value = $this->user->$name;			
		}else{
			$value = $this->getAktVal($name);
			$readonly = "";					
		}
		//$ajaxObj->ajaxContent
		echo "<div class=\"inputrow\" style=\"overflow:hidden; width: 100%;\"><input {$ajaxObj->ajaxJs} {$js} id=\"{$name}\" class=\"alapinput\" name=\"{$name}\" value=\"{$value}\" type=\"text\" onfocus=\"this.className='active_input'\" {$readonly} /> {$ajaxObj->ajaxContent}</div>";
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;   			
	}
		
	function getJelszo($node, $ajaxObj=""){
		ob_start();
		$name = $node->getAttribute('name');
		$value = (array)$this->getSessionVar($name);
		?>
		<input class="alapinput" name="<?php echo $name ?>" value="<?php echo $value ?>" type="password" />
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;   			
	}
  
}
?>
