<?php
defined('_JEXEC') or die('=;)');
jimport('joomla.application.component.model');

class whModelhirlevelek extends modelBase {
	function __construct() {
		parent::__construct();
		global $mainframe, $option;
		// Get pagination request variables
		$limit = $this -> limit;
		$this -> limitstart = JRequest::getVar("limitstart", 0);
		$this -> xmlParser = new xmlParser("hirlevel.xml");
	}//function

	function _buildQuery() {
		$cond = $this -> getCond();
		$query = "SELECT * FROM #__wh_hirlevel as ws {$cond} order by ws.nev";
		//echo $query;
		return $query;
	}

	function getData() {
		$ret = "";
		// Lets load the data if it doesn't already exist
		if (empty($this -> _data)) {
			$query = $this -> _buildQuery();
			$this -> _data = $this -> _getList($query, $this -> getState('limitstart'), $this -> getState('limit'));
			array_map(array($this, "setLink"), $this -> _data);
		}
		//$this->_data = array_map(array($this,"propValue"), $rows);
		//print_r($this->_data);exit;
		$this -> items = $this -> _data;
		if (count($this -> _data)) {
			$k = 0;
			global $Itemid;
			for ($i = 0, $n = count($this -> items); $i < $n; $i++) {
				$row = &$this -> items[$i];
				$checked = JHTML::_('grid.id', $i, $row -> id);
				$o = "";
				$o -> CHECKED = $checked;
				$o -> NEV = $row -> link;
				$o -> DATUM = $row -> datum;
				$arr[] = $o;
				$k = 1 - $k;
			}
			$pagination = $this -> getPagination();
			$lista = new listazo($arr, "adminlist", $pagination -> getPagesLinks(), $pagination -> getPagesLinks());
			$ret .= $lista -> getLista();
		}
		//echo $lista -> getLista();
		return $ret;
	}

	function setLink($item) {
		global $Itemid;
		$ret = "";
		$link = JRoute::_("index.php?option=com_wh&controller=hirlevel&task=show&fromlist=1&Itemid={$Itemid}&cid[]={$item->id}");
		$ret .= "<a href=\"{$link}\">{$item->nev}</a>";
		$item -> link = $ret;
		return $item;
	}

	function getTotal() {
		// Load the hirlevel if it doesn't already exist
		if (empty($this -> _total)) {
			$query = $this -> _buildQuery();
			$this -> _total = $this -> _getListCount($query);
		}
		return $this -> _total;
	}//function

	function getPagination() {
		// Load the hirlevel if it doesn't already exist
		if (empty($this -> _pagination)) {
			jimport('joomla.html.pagination');
			$this -> _pagination = new JPagination($this -> getTotal(), $this -> getState('limitstart'), $this -> getState('limit'));
		}
		return $this -> _pagination;
	}//function

}
