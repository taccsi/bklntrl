<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlmsablon extends kep{
	var $images = 1;
	var $uploaded = "media/msablonok";
	var $table = "#__whp_msablon";	
	var $kepPrefix = "";
	function getMezoId($node){
		return "-";
	}//functon

	function getDbCheckbox($name, $node, $value, $sw=0 ){
		$name = $node->getAttribute("name");
		
		if($msablon_id = $this->getAktVal("id")){
			$q = "select msablonmezo_id from #__whp_msablonmezo_kapcsolo as kapcsolo
			where msablon_id = {$msablon_id}";
			$this->_db->setQuery($q);
			$value = $this->_db->loadResultArray();	
			//die($this->_db->getErrorMsg() );
		}else{
			$value = array();
		}
		$table = $node->getAttribute("table");
		$v_ = "value";
		$o_ = "option";
		$q = "select id as `value`, nev as `option` from #__whp_msablonmezo as msablonmezo ";
		#__whp_msablonmezo_kapcsolo
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		//print_r($rows);
		//die($this->_db->getErrorMsg() );
		ob_start();
		foreach($rows as $r){
			if(in_array( $r->$v_, $value ) ){
				$checked = "checked=\"checked\"";
				$hiddenValue = $r->$v_;
			}else{
				$checked="";
				$hiddenValue = "";				
			}
				
			$idHidden = "{$name}_{$r->$v_}";
			$idCheck = "check_{$name}_{$r->$v_}";			
			$js = "onclick=\"kapcsolHiddenByCheck({$idCheck},{$idHidden})\"";
			$class = "class=\"alapinput {$name}\"";
			echo "<span {$class}><input {$class} id=\"{$idCheck}\" {$checked} type=\"checkbox\" {$js} value=\"{$r->$v_}\" />{$r->$o_}</span>";
			echo "<input type=\"hidden\" value=\"{$hiddenValue}\" name=\"{$name}[]\" id=\"{$idHidden}\"  />";
		}
		echo '<span style="clear:both"></span>';
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

}
?>
