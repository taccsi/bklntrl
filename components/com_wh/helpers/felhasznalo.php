<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlFelhasznalo extends xmlParser{
	function getUserData($node){
		ob_start();
		$name = $node->getAttribute('name');
		if(@$this->user->id && in_array($name, array("username", "name") ) ){
			@$value = $this->user->$name;
			$readonly = "readonly=\"readonly\"";		
		}else{
			$value = $this->getAktVal($name);	
			$readonly = "";		
		}
		?>
		<input name="<?php echo $name ?>" value="<?php echo $value ?>" type="text" <?php echo $readonly ?> />
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;   			
	}
		
	function getJelszo($node){
		ob_start();
		$name = $node->getAttribute('name');
		$value = (array)$this->getSessionVar($name);
		?>
		<input name="<?php echo $name ?>" value="<?php echo $value ?>" type="password" />
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;   			
	}
  
}