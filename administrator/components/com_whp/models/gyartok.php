<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');
class whpModelgyartok extends whpAdmin
{
	function __construct()
	{
	 	parent::__construct();
		global $mainframe, $option;
        $application = JFactory::getApplication('administrator');		
        $limit = $application->getUserStateFromRequest('global.list.limit', 'limit', $application->getCfg('list_limit'), 'int');
        $limitstart = $application->getUserStateFromRequest('com_whp.limitstart', 'limitstart', 0, 'int');
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
		$this->xmlParser = new xmlParser("gyarto.xml");
		//$this->minta();
	}//function

	function minta(){
		for($i = 0; $i<100 ; $i++ ){
			$o = "";
			$o->nev = "dkushkdhadkshadkls";
			$o->aktiv = "igen";			
			$this->_db->insertObject("#__whp_gyarto", $o, "id");
		}
	}

	function _buildQuery()
	{
		$cond = $this->getCond();	
		$query = "SELECT gyarto.* FROM #__whp_gyarto as gyarto {$cond}";
		return $query;
	}
	
	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit') );
			//$this->_data = array_map ( array($this, "setgyartok"), $this->_data) ;			
			echo $this->_db->getErrorMsg();
		}
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
		//die($this->_total);
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