<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmluzlet extends xmlParser{
	var $honapok = array("JANUARY","FEBRUARY", "MARCH","APRIL","MAY","JUNE","JULY","AUGUST","SEPTEMBER","OCTOBER","NOVEMBER","DECEMBER");
	var $negyedevek = array("1" =>"1,2,3", "2"=>"4,5,6", "3"=>"7,8,9", "4"=>"10,11,12" );
	
	function getKimutatas( $node ){
		$name = $node->getAttribute('name');
		$value = $this->getAktVal($name);
		$ret = "";
		$ret .= $this -> getSearch();
		$this->document->addscriptdeclaration("window.addEvent(\"domready\", function(){getuzletKimutatas();})");		
		$this->document->addscriptdeclaration("window.addEvent(\"domready\", function(){getEmailElkuldve();})");		
		$ret .= jtext::_("KULDESDATUMA").": <span id=\"ajaxContentEmailelkuldve\"></span> ";	
		$ret .= "<input type=\"button\" onclick=\"kuldErtesitoEmail()\" value=\"".jtext::_("KULD_EMAIL")."\" />";		
		$ret .= "<br /><br /><input type=\"button\" onclick=\"jutalekNyomtatas();\" value=\"".jtext::_("NYOMTATAS")."\" />";
		$ret .= "<div id=\"ajaxContentuzletKimutatas\"></div>";		
		return $ret;
	}

	function getSearch(){
		ob_start();
		$arr = $this->getSearchArr();
		?>
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><?php echo $arr[0]->EV ?></td>
            <td><?php echo $arr[1]->HONAP ?></td>
            <!--<td><a class="btn_search_big" onclick="document.getElementById('adminForm').submit();return false;" href="#"><?php echo JText::_('KERES'); ?> </a></td>-->
          </tr>
        </table>
        <?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function getSearchArr( $js = "getuzletKimutatas()" ){
		$arr = array();
		$obj = "";		
		$name = "cond_ev";
		$arr_ = array();
		for($i = 2010; $i <= 2020; $i++ ){
			$o = "";
			$o->value = $i;
			$o->option = $i;			
			$arr_[] = $o;
		}
		$value = jrequest::getVar($name, date("Y", time() ) );		
		$obj->EV = JHTML::_('Select.genericlist', $arr_, $name, array("class"=>"alapinput", "onchange"=> $js), "value", "option", $value);
		$arr[] = $obj;		
		
		$obj = "";		
		$name = "cond_honap";
		$value = jrequest::getVar($name, date("m", time() ) );		
		$arr_ = array();
		foreach($this->negyedevek as $k => $h){
			$i = array_search($k, $this->negyedevek)+1;
			$o="";
			$o->option = $k.". ".jtext::_("NEGYEDEV");
			$o->value = $h;
			$arr_[] = $o;
		}

		$obj->HONAP = JHTML::_('Select.genericlist', $arr_, $name, array("class"=>"alapinput", "onchange"=> $js), "value", "option", $value);
		$arr[] = $obj;
		return $arr;	
	}
/*
	function getKimutatas( $node ){
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
		//$this->document->addscriptdeclaration("window.addEvent(\"domready\", function(){getTelepules('{$value}');})");
		$ret .= $this -> getSearch();
		$ret .= "<div id=\"ajaxContentuzletKimutatas\"></div>";		
		return $ret;
	}
*/
}
?>