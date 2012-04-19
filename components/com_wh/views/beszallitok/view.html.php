<?php
defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.view');

class whViewbeszallitok extends JView 
{

	function display($tpl = null)
	{
		//jimport('joomla.html.toolbar');
	 	$items =& $this->get('Data');
	 	//print_r($items); die;
		$pagination =& $this->get('Pagination');
		// push data into the template
		$this->assignRef('search', $this->get("search"));
		$this->assignRef('items', $items);
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}//function


}// class
?>