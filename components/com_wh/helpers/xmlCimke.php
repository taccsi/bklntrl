<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlcimke extends xmlParser{
	function getFajl($node){
		ob_start();
		$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);	
		(is_array($value)) ? $value : $value = explode(",", $value);
		$path = "../images/cimkeok/";
		$dir_handle = @opendir($path) or die("Unable to open $path");
		// Loop through the files
		$arr = array();
		while ($cimke = readdir($dir_handle) ) {
			if($cimke == "." || $cimke == ".." || $cimke == "index.php" ){
			}else{
				$o = "";
				$o->value = "{$path}{$cimke}";
				$o->option = "{$cimke}";
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
		$termek_id = jrequest::getvar("cid",0);
		//$user_id = $this->user->id;
		ob_start();
		$name = $node->getAttribute('name');
		//$js = "$('termek_id').value={$termek_id[0]}; $('id').value={$cimke_id}; $('user_id').value={$user_id};  addcimke('{$controller}')";
		$js = "addcimke({$termek_id[0]})";
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