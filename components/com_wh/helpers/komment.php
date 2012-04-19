<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlkomment extends xmlParser{
	function getTermIdHidden($node){
		$termek_id = jrequest::getvar("termek_id",0);
		$name = $node->getAttribute('name');
		?>
        <input name="<?php echo $name ?>" id="<?php echo $name ?>" value="<?php echo $termek_id; ?>" type="hidden" /><?php
	}
	
	function getFajl($node){
		ob_start();
		$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);	
		(is_array($value)) ? $value : $value = explode(",", $value);
		$path = "../images/kommentok/";
		$dir_handle = @opendir($path) or die("Unable to open $path");
		// Loop through the files
		$arr = array();
		while ($komment = readdir($dir_handle) ) {
			if($komment == "." || $komment == ".." || $komment == "index.php" ){ 
			}else{
				$o = "";
				$o->value = "{$path}{$komment}";
				$o->option = "{$komment}";
				$arr[] = $o;
			}
		}
		closedir($dir_handle);
		echo JHTML::_('Select.genericlist', $arr, $name, array( "class"=>"button"), "value", "option", $value);
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;   				
	}

	function getFeladButton($node){
		$termek_id = jrequest::getvar("termek_id",0);
		$user_id = $this->user->id;
		ob_start();
		$name = $node->getAttribute('name');
		$controller = Jrequest::getvar('controller');
		//$js = "$('ugy_id').value={$ugy_id[0]}; $('id').value={$komment_id}; $('user_id').value={$user_id};  addkomment('{$controller}')";
		$js = "$('termek_id').value={$termek_id};   addkomment('{$controller}')";
		?>
        <input onclick="<?php echo $js ?>" name="<?php echo $name ?>" value="<?php echo Jtext::_($name); ?>" type="button" /><?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;   				
	}

	function getDatum($node){
		ob_start();
		$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);
		($value) ? $value : $value = date("Y-m-d H:i:s", time() );
		if($this->getaktVal ("id" ) ) echo $value;
		?><input id="<?php echo $name ?>" name="<?php echo $name ?>" value="<?php echo $value ?>" type="hidden" /><?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;   				
	}
}