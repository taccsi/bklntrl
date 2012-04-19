<?php
defined( '_JEXEC' ) or die( '=;)' );

class Tablewhp_msablonmezo extends JTable
{
	function __construct(& $db) {
		//print_r($db);
		//die();
		$this->setFields("#__whp_msablonmezo");
		parent::__construct('#__whp_msablonmezo', 'id', $db);
	}
	
	function bind($array, $ignore = '')
	{
			if (key_exists( 'params', $array ) && is_array( $array['params'] ) )
			{
					$registry = new JRegistry();
					$registry->loadArray($array['params']);
					$array['params'] = $registry->toString();
			}
			return parent::bind($array, $ignore);
	}


	function setFields($table){
		$db=JFactory::getDBO();
		$fields_ = $db->getTableFields($table, 1);
		$fields="";
		foreach($fields_[$table] as $f => $v){
			//echo $f;
			$this->$f=null;
		}
	}
}
?>