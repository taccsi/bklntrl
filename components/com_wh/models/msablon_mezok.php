<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');

class whModelmsablon_mezok extends modelBase
{
	var $limit = 1000;
	function __construct()
	{
	 	parent::__construct();
		global $mainframe, $option;
		// Get pagination request variables
		$this->limitstart = JRequest::getVar( "limitstart", 0 ); 
		$this->xmlParser = new xmlParser("msablon_mezo.xml");
			
	}//function

	function getSearchArr(){
		$arr = array();
		
		$obj = "";
		$name = "cond_nev";
		$value = JRequest::getVar($name);
		$obj->NEV = "<input name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" />";
		$arr[] = $obj;

		$obj = "";
		$name = "cond_leiras";
		$value = JRequest::getVar($name);
		$obj->ERTEK = "<input name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" />";
		$arr[] = $obj;		
		return 	$arr;
	}

	
	function _buildQuery()
	{
		$cond = $this->getCond();	

		$query = "SELECT * FROM #__wh_msablonmezo as m {$cond} order by m.nev, m.id ";
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
 	    $this->_pagination = new JPagination($this->getTotal(), $this->limitstart, $this->limit );
 	}
 	return $this->_pagination;
  }//function


}// class
?>