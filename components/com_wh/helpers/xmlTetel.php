<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmltetel extends xmlParser{
	function getKiszallitasAr( $node ){
		ob_start();
		$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);
		if(!$value || 1){
			$kalkulaltAr = $this->getKalkulaltAr($this->getOsszesTomeg());
			$value = $kalkulaltAr;
		}
		?>
		<input name="<?php echo $name ?>" value="<?php echo $value ?>" type="text" />
		<?php echo jtext::_("OSSZES_TOMEG").": ".$this->getOsszesTomeg() ?> kg
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;   			
	}
	
}