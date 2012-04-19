<?php

defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewKepek extends JView
{

	function display($tpl = null)
	{
		//jimport('joomla.html.toolbar');
	 	$items =& $this->get('Data');
		$pagination =& $this->get('Pagination');
		$this->assignRef('items', $items);
		$this->assignRef('pagination', $pagination);
		parent::display($tpl);	
	}//function
}// class
?>