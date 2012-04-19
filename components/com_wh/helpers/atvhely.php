<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlatvhely extends xmlParser{
	function getTelepulesId( $node ){
		$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);
		$tObj = $this->getObj("#__wh_telepules", $value);
		$q = "select megye as `value`, megye as `option` from #__wh_telepules where megye <> '' group by megye order by megye asc ";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		$o="";
		$o->option = $o->value = "";
		array_unshift( $rows, $o );
		$ret = "";
		$ret .= "<span class=\"span_cim\">".jtext::_("MEGYE")."</span>".JHTML::_( 'Select.genericlist', $rows, "megye", array("class"=>"alapinput cim", "onchange"=>"getTelepules()" ), "value", "option", @$tObj->megye );
		$this->document->addscriptdeclaration("window.addEvent(\"domready\", function(){getTelepules('{$value}');})");
		$ret .= "<div id=\"ajaxContentTelepules\"></div>";		
		return $ret;
	}
}