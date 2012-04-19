<?php
defined( '_JEXEC' ) or die( '=;)' );
class ar{
	function _($ar, $penznem = "Ft"){
		return number_format($ar, 0, "", " ")." {$penznem}";
	}
}