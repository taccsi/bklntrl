<?php

defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.model');



class whModelkommentek extends modelBase

{

	function __construct()

	{

	 	parent::__construct();

		global $mainframe, $option;

		// Get pagination request variables

		$limit = $this->limit;

		$this->limitstart = JRequest::getVar( "limitstart", 0 ); 

		$this->xmlParser = new xmlParser("komment.xml");	

	}//function



	function _buildQuery()

	{

		$cond = $this->getCond();	

		$query = "SELECT k.*, t.nev as termeknev, t.cikkszam as isbn FROM #__wh_ertekeles as k 

		inner join #__wh_termek as t on k.termek_id = t.id

		{$cond} order by k.datum desc";

		//echo $query;

		return $query;

	}



	function getData()

	{

		// Lets load the data if it doesn't already exist

		if (empty( $this->_data ))

		{

			$query = $this->_buildQuery();

			$this->_data = $this->_getList( $query, $this->limitstart, $this->limit );

		}

		//$this->_data = array_map(array($this,"propValue"), $rows);

		//print_r($this->_data);exit;

		return $this->_data;

	}//function

	

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

 	    $this->_pagination = new JPagination($this->getTotal(),  $this->limitstart, $this->limit );

 	}

 	return $this->_pagination;

  }//function

function getSearchArr(){

		$arr = array();

		$obj = "";

		$name = "cond_nev";

		$value = JRequest::getVar($name);

		$obj->HOZZASZOLAS_CIM = "<input name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" />";

		$arr[] = $obj;

		
		$obj = "";

		$name = "cond_aktiv";

		$value = JRequest::getVar($name);
		
		$arr_ = array();
		
		$o='';
		$o->option = '';
		$o->value = '';
		$arr_[]=$o;

		$o='';
		$o->option = Jtext::_('AKTIV');
		$o->value = 'igen';
		$arr_[]=$o;
		
		$o='';
		$o->option = Jtext::_('NEM_AKTIV');
		$o->value = 'nem';
		$arr_[]=$o;

		
		$obj->AKTIV = JHTML::_('Select.genericlist', $arr_, $name, array( "class"=>"alapinput" ), "value", "option", $value);

		$arr[] = $obj;


/*

		$obj = "";	

		$name = "cond_spec2";

		$value = JRequest::getVar($name, array(), "request", "array");

		$obj->SPEC_SZURES2 = $this->getSearchCheckboxes($name, $this->xmlParser->getxmlarr('cond_spec2'), $value );

		$arr[] = $obj;

*/

	/*	

		$obj = "";		

		$q = "select id as `value`, nev as `option` from #__wh_beszallito order by nev";

		$this->_db->setQuery($q);

		$name = "cond_beszallito_id";

		$value = JRequest::getVar($name);

		$rows = $this->_db->loadObjectList();		

		$o="";

		$o->value = $o->option = "";

		array_unshift($rows, $o);		

		$obj->BESZALLITOS = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);

		$arr[] = $obj;

*/





		$obj = "";		

		$q = "select t.id as `value`, t.nev as `option` from #__wh_ertekeles as k

		inner join #__wh_termek as t on k.termek_id = t.id

		group by t.id order by t.nev";

		

		$this->_db->setQuery($q);

		$name = "cond_termek_id";

		$value = JRequest::getVar($name);

		$rows = $this->_db->loadObjectList();		

		

		$o="";

		$o->value = $o->option = "";

		array_unshift($rows, $o);		

		$obj->TERMEKNEV = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);

		$arr[] = $obj;

		return 	$arr;

	}



}// class

?>