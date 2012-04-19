<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlPickPack extends xmlParser{
	
	function getNode( $attribute, $value ){ 
		$i=0;
		foreach ($this->dom->getElementsByTagname('tours') as $tours ){
			foreach($tours->childNodes as $e_){
				if(is_a($e_, "DOMElement")){
					//echo $e_-> getAttribute("zipcode")." <br />";
					if($e_->getAttribute($attribute)==$value){
						return $e_;
					}
					
				}
			}
		}
		return false;
	}

}
?>