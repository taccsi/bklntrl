<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');

class whModeltetelek extends modelBase
{
	function __construct()
	{
	 	parent::__construct();
		global $mainframe, $option;
		// Get pagination request variables
		$limit = $this->limit;
		$this->limitstart = JRequest::getVar( "limitstart", 0 ); 
		$this->xmlParser = new xmlParser("tetel.xml");
	}//function
	
	function setDatumok( $item, $key, $name ){
		ob_start();
		$datum = date("Y-m-d", time() );
		$id = "{$name}_{$key}";
		if( $item->$name == "0000-00-00 00:00:00" ){
			$value=""; 
			$checked = "";
		}else{
			$checked = "checked=\"checked\"";
			$value = $item->$name;
		}
		?>
        <input type="checkbox" <?php echo $checked ?> value="1" onclick="setDatum(this, '<?php echo $id ?>', '<?php echo $datum ?>')" /><input name="<?php echo $name."[]" ?>" type="text" id="<?php echo $id ?>" value="<?php echo $value ?>" />
        <?php
		$r = ob_get_contents();
		ob_end_clean();
		$item->$name = $r;
		return $item;
	}	
	
	function save($cid){
		$arr = array("megrendeleve_datum","beerkezett_datum","beszallitonak_fizetve_datum");
		foreach($cid as $id){
			$i = array_search($id, $cid);
			$o = $this->getObj("#__wh_tetel", $id);	
			foreach($arr as $a){
				$v = jrequest::getvar($a, array(), "request", "array");				
				$o->$a = $v[$i];
			}
			$this->_db->updateObject("#__wh_tetel", $o, "id");
		}
		return 1;
	}
	
	function getSearchArr(){
		$arr = array();
		
		$obj = "";	
		$name = "cond_rendeles_id";
		$value = JRequest::getVar($name,'');
		$caption = 'RENDELES_ID';
		$obj->RENDELES_ID = $this->getSearchTextBox($name, $value, $caption);	
		$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_megrendeles_tol";
		$value = JRequest::getVar($name,'');
		$caption = 'MEGRENDELESTOL';
		$obj->MEGRENDELESTOL = JHTML::_('calendar', $value, $name, $name, "%Y-%m-%d", "");	
		$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_megrendeles_ig";
		$value = JRequest::getVar($name,'');
		$caption = 'MEGRENDELESIG';
		$obj->MEGRENDELESIG  = JHTML::_('calendar', $value, $name, $name, "%Y-%m-%d", "");
		$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_tetel_nev";
		$value = JRequest::getVar($name,'');
		$caption = 'TERMEKNEV';
		$obj->TERMEKNEV = $this->getSearchTextBox($name, $value, $caption);	
		$arr[] = $obj;
		
		$obj = "";		
		$q = "select id as `value`, nev as `option` from #__wh_webshop";
		$this->_db->setQuery($q);
		$name = "cond_webshop_id";
		$value = JRequest::getVar($name);
		$rows = $this->_db->loadObjectList();		
		$o="";
		$o->value = $o->option = "";
		array_unshift($rows, $o);		
		$obj->WEBSHOP = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);
		$arr[] = $obj;

		$obj = "";		
		$name = "cond_gyariszam";
		$value = JRequest::getVar($name);
		$obj->GYARISZAM = $this->getSearchTextBox($name, $value, "GYARISZAM");	
		$arr[] = $obj;

		$obj = "";		
		$name = "cond_beszallito_id";
		$q = "select id as `value`, nev as `option` from #__wh_beszallito";
		$this->_db->setQuery($q);
		$value = JRequest::getVar($name);
		$rows = $this->_db->loadObjectList();		
		$o="";
		$o->value = $o->option = "";
		array_unshift($rows, $o);		
		$obj->BESZALLITO = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);
		$arr[] = $obj;
/*
		$obj = "";		
		$name = "cond_allapot_beszerzes";
		$value = JRequest::getVar($name, array(), "request", "array");
		$obj->ALLAPOT_BESZERZES = $this->getSearchCheckboxes($name, $this->xmlParser->getxmlarr('allapot_beszerzes'), $value );
		$arr[] = $obj;
*/
		/*$obj = "";		
		$name = "cond_allapot";
		$value = JRequest::getVar($name, array(), "request", "array");
		$obj->ALLAPOT_FIZETVE = $this->getSearchCheckboxes($name, $this->xmlParser->getxmlarr('allapot'), $value );
		$arr[] = $obj; */
		
		
		
		
		
		
		$obj = "";		
		$name = "cond_statusz_beszerzes";
		$value = JRequest::getVar($name, array(), "request", "array");
		$obj->STATUSZ_BESZERZES = $this->getSearchCheckboxes($name, $this->xmlParser->getxmlarr('statusz_beszerzes'), $value );
		$arr[] = $obj;
		
		$obj = "";		
		$name = "cond_statusz_beerkezett";
		$value = JRequest::getVar($name, array(), "request", "array");
		$obj->STATUSZ_BEERKEZETT = $this->getSearchCheckboxes($name, $this->xmlParser->getxmlarr('statusz_beerkezett'), $value );
		$arr[] = $obj;
		
		$obj = "";		
		$name = "cond_beszallito_fizetve";
		$value = JRequest::getVar($name, array(), "request", "array");
		$obj->BESZALLITO_FIZETVE = $this->getSearchCheckboxes($name, $this->xmlParser->getxmlarr('beszallito_fizetve'), $value );
		$arr[] = $obj;
		
		
		$obj = "";		
		$name = "cond_megrendelo_fizetve";
		$value = JRequest::getVar($name, array(), "request", "array");
		$obj->MEGRENDELO_FIZETVE = $this->getSearchCheckboxes($name, $this->xmlParser->getxmlarr('megrendelo_fizetve'), $value );
		$arr[] = $obj;
		
		return $arr;
	}

	function _buildQuery()
	{
		$cond = $this->getCond();	
		$query = "SELECT tetel.*, bsz.nev as beszallito, 
		r.datum, r.id as rendeles_id, r.datum, r.csomagszam, r.allapot
		FROM #__wh_tetel as tetel
		left join #__wh_rendeles as r on tetel.rendeles_id = r.id
		left join #__wh_beszallito as bsz on tetel.beszallito_id = bsz.id
		{$cond}";
		return $query;
	}
	
	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit') );
			$this->_data = array_map ( array($this, "setBeszallitoAr"), $this->_data) ;
			//$this->_data = array_map ( array($this, "setMegrendelesIdeje"), $this->_data) ;			
			//$this->_data = array_map ( array($this, "setTetelek"), $this->_data) ;			
			array_walk($this->_data, array($this, "setDatumok"), "megrendeleve_datum" );
			array_walk($this->_data, array($this, "setDatumok"), "beerkezett_datum" );
			array_walk($this->_data, array($this, "setDatumok"), "beszallitonak_fizetve_datum" );						
			//megrendeleve_datum
			//beerkezett_datum 
			//beszallitonak_fizetve_datum
			echo $this->_db->getErrorMsg();
		}
		//$this->_data = array_map(array($this,"propValue"), $rows);
		//print_r($this->_data);exit;
		return $this->_data;
	}//function
	
	function setBeszallitoAr($item){
		//$item->beszallitoAr = $this->getNettoBruttoInput("nettoAr{$item->id}", "bruttoAr{$item->id}", $item->netto_ar_beszallito, $item->afa, "nettoAr{$item->id}", "[]", $br="</br>" );
		$item->beszallitoAr = ar::_($item->netto_ar_beszallito*($item->afa+1));
		return $item;
	}
	
	function getTotal()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);	
		}
		return $this->_total;
	}//function
  
	function getPagination()
  {
 	// Load the content if it doesn't already exist
 	if (empty($this->_pagination))
 	{
 	    jimport('joomla.html.pagination');
 	    $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
 	}
 	return $this->_pagination;
  }//function
	
}// class
?>