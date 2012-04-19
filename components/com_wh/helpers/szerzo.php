<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlszerzo extends xmlParser{
	function getJogtulajdonosok( $node ){
		$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);
		$document = jfactory::getDocument();
		$document->addScriptDeclaration("\$j(document).ready(function(){ getJogtulajdonosok() });");
		$ret ="";
		$q ="select id as `value`, nev as `option` from #__wh_jogtul as jogtul order by nev";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList( );
		foreach( $rows as $r ){
			$arr[]=$r->option." ({$r->value}) ";
		}
		$jogtulajdonosok =  implode("','", $arr );
		$this->document->addScriptDeclaration("var jogtulajdonosok = new Array ('{$jogtulajdonosok}');");	

		$o="";
		$o->option = $o->value = "";
		array_unshift( $rows, $o );
		$ret = "";
		$ret .= "<input type=\"text\" id=\"jogtulajdonos_id\" />";
		//$ret .= JHTML::_( 'Select.genericlist', $rows, "jogtulajdonos_id", array("class"=>"alapinput cim" ), "value", "option", "" );
		$ret .= "<input class=\"span_tulhanyad\" type=\"text\" id=\"tulhanyad\" name=\"tulhanyad\" > ".jtext::_("TULAJDONHANYAD");
		$ret .= "<input type=\"button\" onclick=\"hozzaadJogtulajdonos(); \" value=\"".jtext::_("HOZZAAD")."\" /> ";
		$ret .= "<div id=\"jogtulajdonosok\"></div>";
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