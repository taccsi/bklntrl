<?php
defined( '_JEXEC' ) or die( '=;)' ); 
class xmlFcsoport extends xmlParser{

	//getWebshop

	function getKategoria($node){
		ob_start();
		?><div id="ajaxContentKategoria"></div><div id="ajaxContentKategoriaKedvezmenyek"></div><?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function getKeszpenzesKedvezmeny($node){
		$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);
		$ret = "";
		$ret.="<input name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" type=\"text\" >";
		/*
		$ret.="<input name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" type=\"hidden\" >";
		$ret.="<span id=\"{$grafikaImgId}\" ></span>";		
		*/
		$kedvezmeny_tipus = $this->getNode("name", "keszpenzes_kedvezmeny_tipus");
		$kt_name = $kedvezmeny_tipus->getAttribute("name");
		$kt_value = $this->getAktVal($kt_name);		
		
		$arr=array();
		foreach($kedvezmeny_tipus->childNodes as $e_){
			if(is_a($e_, "DOMElement")){
				$o="";
				//print_r($e_);
				$o->value = $e_->getAttribute('value');
				$o->option = jtext::_($e_->textContent);
				$arr[]=$o;
			}
		}
		$ret .= JHTML::_('Select.genericlist', $arr, $kt_name, array( "class"=>"{$name}_ alapinput" ), "value", "option", $kt_value);
		return $ret;
	}

	function getKedvezmeny($node){
		$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);
		$ret = "";
		$ret.="<input name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" type=\"text\" >";
		/*
		$ret.="<input name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" type=\"hidden\" >";
		$ret.="<span id=\"{$grafikaImgId}\" ></span>";		
		*/
		$kedvezmeny_tipus = $this->getNode("name", "kedvezmeny_tipus");
		$kt_name = $kedvezmeny_tipus->getAttribute("name");
		$kt_value = $this->getAktVal($kt_name);		
		$arr=array();
		foreach($kedvezmeny_tipus->childNodes as $e_){
			if(is_a($e_, "DOMElement")){
				$o="";
				//print_r($e_);
				$o->value = $e_->getAttribute('value');
				$o->option = jtext::_($e_->textContent);
				$arr[]=$o;
			}
		}
		$ret .= JHTML::_('Select.genericlist', $arr, $kt_name, array( "class"=>"{$name}_ alapinput" ), "value", "option", $kt_value);
		return $ret;
	}

}