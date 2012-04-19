<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlszamlap extends xmlParser{
	function getTermekekSzama($node){
		$id = $this->getAktVal("id");
		$q = "select count(szamlap_id) from #__wh_szamlap_kapcsolo where szamlap_id = {$id} ";
		$this->_db->setQuery($q);
		$ret = $this->_db->loadResult();
		( $ret ) ? $ret = ar::_($ret, "") : $ret = 0;
		return $ret." ".jtext::_("DB");
	}
	
   function getKategoriaSelect($node) {
		$name = $node->getAttribute('name');
		$value=$this->getAktVal($name);
		( is_array( $value ) ) ? $value : $value=explode(",", $value);
		$arr = array();
		ob_start();
		$kategoriafa = new kategoriafa( );
		$o="";
		$o->option = $o-> value = "";
		array_unshift($kategoriafa ->catTree, $o);
		echo JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name."[]", array("multiple"=>"multiple","class"=>"alapinput", "readonly" => "readonly"), "value", "option", $value);
		$swO = $this->getNode("name", "kategoria_sw");
		//echo $this->getCheckbox("kategoria_sw", $swO, $this->getAktVal("kategoria_sw") ).jtext::_("KIVESZ_BETESZ");
		$ret = ob_get_contents();	  
		ob_end_clean();
		return $ret;
	 // die($readonly);
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
				$o->option = $e_->textContent;
				$arr[]=$o;
			}
		}
		$ret .= JHTML::_('Select.genericlist', $arr, $kt_name, array( "class"=>"{$name}_ alapinput" ), "value", "option", $kt_value);
		return $ret;
	}
	
	function getGrafika($node){
		$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);
		
		$dir = $node->getattribute("dir");
		
		$arr = jfolder::files($dir);
		$arr2 = array();
		foreach($arr as $a){
			$o="";
			$o->value = $a;
			$o->option = $a;			
			$arr2[] = $o;
		}
		$o="";
		$o->value = $o->option = "";			
		array_unshift($arr2,$o);
		$grafikaImgId = "grafikaImgId{$name}";		
		$this->document->addScriptDeclaration("window.addEvent(\"domready\", function(){setGrafika($('{$name}'), '{$dir}', '{$name}', '{$grafikaImgId}')});");		
		$ret = JHTML::_('Select.genericlist', $arr2, $name."_", array( "class"=>"{$name}_ alapinput", "onchange"=>"setGrafika(this, '{$dir}', '{$name}', '{$grafikaImgId}' )"), "value", "option", $value);
		$ret.="<input name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" type=\"hidden\" >";
		$ret.="<span id=\"{$grafikaImgId}\" ></span>";		
		return $ret;
	}
}