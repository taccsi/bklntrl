<?php
defined('_JEXEC') or die('=;)');
class xmlmsablon extends kep {
	var $images = 1;
	var $uploaded = "media/msablonok";
	var $table = "#__wh_msablon";
	var $kepPrefix = "";
	function getMezoId($node) {
		return "-";
	}//functon

	function getMsablonMezok($node) {
		$name = $node -> getAttribute('name');
		$value = $this -> getAktVal($name);

		$ajaxContentId = "ajaxContentmsablonMezok";

		$document = jfactory::getDocument();
		$document -> addScriptDeclaration("\$j(document).ready(function(){ getMsablonMezok( '{$ajaxContentId}' ) });");
		$ret = "";
		$q = "select id as `value`, nev as `option` from #__wh_msablonmezo as msablon_mezo order by nev";
		$this -> _db -> setQuery($q);
		$rows = $this -> _db -> loadObjectList();
		$arr = array();
		foreach ($rows as $r) {
			$arr[] = $r -> option . " ({$r->value}) ";
		}
		$arr = implode("','", $arr);
		$this -> document -> addScriptDeclaration("var msablonMezoArr = new Array ('{$arr}');");

		$o = "";
		$o -> option = $o -> value = "";
		array_unshift($rows, $o);
		$ret = "";
		//$ajaxContentId
		$ret .= "<input type=\"text\" id=\"msablon_mezo\" />";
		//$ret .= JHTML::_( 'Select.genericlist', $rows, "jogtulajdonos_id", array("class"=>"alapinput cim" ), "value", "option", "" );
		$ret .= "<input type=\"button\" onclick=\"hozzaadMsablonMezo('{$ajaxContentId}'); \" value=\"" . jtext::_("HOZZAAD") . "\" /> ";
		$ret .= "<div id=\"{$ajaxContentId}\"></div>";
		return $ret;
	}

	function getDbCheckbox_($name, $node, $value, $sw = 0) {
		$name = $node -> getAttribute("name");

		if ($msablon_id = $this -> getAktVal("id")) {
			$q = "select msablonmezo_id from #__wh_msablonmezo_kapcsolo as kapcsolo
			where msablon_id = {$msablon_id}";
			$this -> _db -> setQuery($q);
			$value = $this -> _db -> loadResultArray();
			//die($this->_db->getErrorMsg() );
		} else {
			$value = array();
		}
		$table = $node -> getAttribute("table");
		$v_ = "value";
		$o_ = "option";
		$q = "select id as `value`, nev as `option` from #__wh_msablonmezo as msablonmezo ";
		#__wh_msablonmezo_kapcsolo
		$this -> _db -> setQuery($q);
		$rows = $this -> _db -> loadObjectList();
		//print_r($rows);
		//die($this->_db->getErrorMsg() );
		ob_start();
		foreach ($rows as $r) {
			if (in_array($r -> $v_, $value)) {
				$checked = "checked=\"checked\"";
				$hiddenValue = $r -> $v_;
			} else {
				$checked = "";
				$hiddenValue = "";
			}

			$idHidden = "{$name}_{$r->$v_}";
			$idCheck = "check_{$name}_{$r->$v_}";
			$js = "onclick=\"kapcsolHiddenByCheck({$idCheck},{$idHidden})\"";
			$class = "class=\"{$name}\"";
			echo "<span {$class}><input {$class} id=\"{$idCheck}\" {$checked} type=\"checkbox\" {$js} value=\"{$r->$v_}\" />{$r->$o_}</span>";
			echo "<input type=\"hidden\" value=\"{$hiddenValue}\" name=\"{$name}[]\" id=\"{$idHidden}\"  />";
		}
		echo '<span style="clear:both"></span>';
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

}