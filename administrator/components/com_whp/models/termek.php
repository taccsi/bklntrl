<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpModeltermek extends whpAdmin
{
	var $xmlFile = "termek.xml";
	var $tmpname = "";
	var $table = "#__whp_termek";
	var $w = 110;
	var $h = 155;
	var $mode = "resize";
	
	//var $table ="whp_termek";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");
		$this->getData();
	 	$this->xmlParser = new xmltermek($this->xmlFile, $this->_data);
	}//function

	function letrehozUjTermekVariacio(){
		$termek_id = jrequest::getVar("termek_id", 0);
		$o="";
		$o->termek_id = $termek_id;
		$this->_db->insertObject("#__whp_termekvariacio", $o);
		return $this->getTermekVariaciok();		
	}
	
	function torolTermekVariacio(){
		$tvar_id = jrequest::getVar("tvar_id", 0);
		$q = "delete from #__whp_termekvariacio where id = '{$tvar_id}'";
		$this->_db->setQuery($q);
		$this->_db->Query();		
		return $this->getTermekVariaciok();
	}

	function getNyilHTML( $arr, $obj ){
		foreach($arr as $a){
			$ind = array_search($a, $arr);
			if($a->id == $obj->id) break;
		}
		$le = "<a href=\"javascript:void(0)\" onclick=\"termvarIrany('le', '{$obj->id}')\" ><img src=\"components/com_whp/assets/images/downarrow.png\" /></a>";
		$fel = "<a href=\"javascript:void(0)\" onclick=\"termvarIrany('fel', '{$obj->id}')\" ><img src=\"components/com_whp/assets/images/uparrow.png\" /></a>";
		if($ind == 0){
			//csak le
			$html = $le;
		}elseif( end($arr) == $arr[$ind] ){
			//csak fel
			$html = $fel;
		}else{
			//fel és le
			$html=$le;
			$html.=$fel;
		}
		$js = "";
		return $html;
	}

	function termvarIrany(){
		ob_start();
		$termek_id = jrequest::getVar("termek_id", 0);
		$termvar_id = jrequest::getVar("termvar_id", 0);
		$irany = jrequest::getVar("irany", '');	
		$q = "select * from #__whp_termekvariacio where termek_id = {$termek_id} order by sorrend, id";
		$this->_db->setQuery($q);
		$arr = $this->_db->LoadObjectList();
		//print_r($arr);
		foreach($arr as $a){
			$ind = array_search($a, $arr);
			if($a->id == $termvar_id){
				$akt = $arr[$ind];
				($irany == "le" ) ? $ind_ = $ind+1 : $ind_ = $ind-1;
				$temp = $arr[$ind_];
				$arr[$ind_] = $akt;
				$arr[$ind] = $temp;
			}
		}
		foreach($arr as $o){
			$o->sorrend= array_search($o, $arr);
			$this->_db->updateObject("#__whp_termekvariacio", $o, "id");
		}
		$ret = ob_get_contents();
		ob_end_clean();
		
		return $ret.$this->getTermekVariaciok();
	}
	
	function getTermekVariaciok(){
		//$xmlParser = new xmlParser("termek.xml", "");
		ob_start();
		$termek_id = jrequest::getVar("termek_id", 0);
		$termek = $this->getObj("#__whp_termek", $termek_id);
		$kategoriaObj = $this->getObj("#__whp_kategoria", $termek->kategoria_id );
		$sablonObj = $this->getObj("#__whp_msablon", $kategoriaObj->msablon_id);
		$q = "select msablonmezo_id from #__whp_msablonmezo_kapcsolo where msablon_id = {$sablonObj->id}" ;
		$this->_db->setQuery($q) ;
		$mezo_idArr = $this->_db->loadResultArray();		
		$arr=array();
		$value = "";
		$o="";	
		$mezo_idArr = $this->cleanTomb($mezo_idArr);
		foreach($mezo_idArr as $mezo_id ){
			$ind = array_search($mezo_id, $mezo_idArr);
			$obj = $this->getObj("#__whp_msablonmezo", $mezo_id );
			$o->TORLES = "";
			$o->CIKKSZAM = jtext::_("CIKKSZAM");
			$o->AR = jtext::_("AR");
			$vPN = "PAR_{$ind}";
			$o->$vPN = $obj->nev."<input type=\"hidden\" value=\"{$mezo_id}\" name=\"mezo_id_arr[]\" />";
			$o->SORREND = jtext::_("SORREND");			
		}
		$arr[]=$o;
		$q = "select * from #__whp_termekvariacio where termek_id = {$termek_id} order by sorrend asc, id asc ";
		$this->_db->setQuery($q);		
		$termekVariaciok = $this->_db->loadObjectList();
		foreach($termekVariaciok as $p_ ){
			$o="";
			foreach($mezo_idArr as $mezo_id ){
				$mNev = "mezoid_{$mezo_id}";
				$$mNev ="";
			}
			parse_str($p_->ertek);			
			foreach($mezo_idArr as $mezo_id ){
				$ind = array_search($mezo_id, $mezo_idArr);
				$obj = $this->getObj("#__whp_msablonmezo", $mezo_id );
				$o->TORLES = "<input onclick=\"torolTermekVariacio('{$p_->id}', '{$termek_id}')\" value=\"".jtext::_("TORLES")."\" type=\"button\" />";
				$cikkszam = $p_->cikkszam;
				$ar = $p_->ar;
				$o->CIKKSZAM = "<input type=\"text\" value=\"{$cikkszam}\" name=\"cikkszam_arr[]\" /><input type=\"hidden\" value=\"{$p_->id}\" name=\"tvar_id_arr[]\" />";
				$o->AR = "<input type=\"text\" value=\"{$ar}\" name=\"ar_arr[]\" />";;
				$vPN="";
				$vPN = "PAR_{$ind}";
				$o->$vPN = "";
				$mezo_value_input_name = "mezo_value_arr_{$p_->id}[]";
				$v__="mezoid_{$mezo_id}";
				@$value = $$v__;
				switch($obj->tipus){
					case "pipa" :
						$id_check = "check_{$mezo_id}_{$p_->id}";
						$js = "changeVal( $('{$id_check}'),this )";
						( $value== 1 ) ? $checked = "checked=\"checked\"" : $checked="";
						$o->$vPN .= "<input id=\"{$id_check}\" type=\"hidden\" 
						value=\"{$value}\" name=\"{$mezo_value_input_name}\" />
						<input {$checked} onclick=\"".$js."\" type=\"checkbox\" value=\"1\" name=\"ch____[]\" />{$obj->suffix}";
						break;
				
					case "lista": 
						$arr_ = array();
						foreach(explode("\n", $obj->leiras) as $p){
							$o_="";
							$o_->value = trim(str_replace(array("\\", "\""),"",$p));
							$o_->option = trim(str_replace(array("\\"),"",$p));
							//echo "-".$o_->value." <br />";							
							$arr_[]=$o_;
						}
						$o__="";
						$o__->value=$o__->option="";
						array_unshift($arr_, $o__);
						$o->$vPN .= JHTML::_('Select.genericlist', $arr_, $mezo_value_input_name, array(), "value", "option", $value ).$obj->suffix;
						break;
					default:
						$o->$vPN .= "<input type=\"text\" value=\"{$value}\" name=\"{$mezo_value_input_name}\" />{$obj->suffix}";
				}
			}
			$o->SORREND = $this->getNyilHTML($termekVariaciok, $p_);			
			$arr[]=$o;
		}
		$listazo = new listazo( $arr, "", "", "", array(), "3");
		echo "<input type=\"button\" value=\"".jtext::_("UJ_LETREHOZASA")."\" onclick=\"letrehozUjTermekVariacio('{$termek_id}')\" />";
		echo "<div id=\"div_parameterek_\">".$listazo->getLista()."</div>";
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function torolKep(){
		$kep_id = jrequest::getVar("kep_id", 0);
		if(file_exists($this->xmlParser->getKepNev($kep_id))){
			unlink( $this->xmlParser->getKepNev($kep_id) );
		}
		$q = "delete from #__whp_kep where id = {$kep_id} limit 1";
		$this->_db->setQuery($q);
		$this->_db->Query($q);			
		return $this->getKepLista();
	}
	
	function getKepLista(){
		$termek_id = jrequest::getVar("termek_id", 0);
		if( $termek_id ){
			$q = "select * from #__whp_kep where termek_id = {$termek_id} order by sorrend asc ";
			$this->_db->setQuery($q);
			$arr = array();
			foreach( $this->_db->loadObjectList() as $k){
				$o = "";
				$o->KEP = $this->getListaKep( $k->id );
				//$o->KEPALAIRAS = "<input name=\"kepalairas[]\" type=\"text\" value=\"{$k->nev}\" >";
				//$o->SORREND = "<input name=\"idArr[]\" type=\"hidden\" value=\"{$k->id}\" ><input class=\"sorrend\" name=\"sorrend[]\" type=\"text\" value=\"{$k->sorrend}\" >";
				$o->TORLES = "<input onclick=\"if(confirm('".jtext::_("BIZTOS_HOGY_TORLOD")."')){torolKep('{$k->id}')}\" type=\"button\" value=\"".jtext::_("TORLES")."\" >";
				
				$arr[]=$o;
			}
			if(count($arr)){
				$listazo = new listazo($arr, "", "", "" /*, array(), "sortable"*/ );
				$ret = $listazo->getLista();
			}else{
				$ret = "&nbsp;";
			}
		}else{
			$ret = "&nbsp;";
		}
		return $ret;
	}
	
	function store()
	   {
	   //die(str_replace("#__", "", $this->table)." *********");
		$row =& $this->getTable( str_replace("#__", "", $this->table) );
		//print_r($row);
		//die;
		foreach($this->getFormFieldArray() as $parName){//ha tömböt kell menteni
			$val = JRequest::getVar($parName,"", "",2,2,2);
			//echo $parName."---<br />";
			if(is_array($val)){
				$data[$parName] = ",".implode(",", $val).",";
			}else{
				$data[$parName] = $val;
			}
		}

		  if (!$row->bind($data)) {
			 $this->setError($this->_db->stderr());
			 return false;
		  }
			//print_r($data);
			//die;
			
		  // Make sure the record is valid
		  if (!$row->check()) {
			 $this->setError($this->_db->stderr());
			 return false;
		  }
	
		  // Store the table to the database
		  //print_r($row); exit;
		  if (!$row->store()) {
			 $this->setError( $row->getError() );
		   return false;
		  }else{
			//die($this->_db->getQuery()); 
			//echo "--------------".;
		   		$id = $this->_db->insertId();
			 if(!$id){
			 $id = $this->getSessionVar("id");
		   }
		  }
		  $this->saveTermekVariaciok();
		  return $id;
	  }   	

	function saveTermekVariaciok(){
		$cikkszam_arr = jrequest::getVar("cikkszam_arr", array(), "request", "array");
		$tvar_id_arr = jrequest::getVar("tvar_id_arr", array(), "request", "array");
		$mezo_id_arr = jrequest::getVar("mezo_id_arr", array(), "request", "array");
		$ar_arr = jrequest::getVar("ar_arr", array(), "request", "array");		
		//print_r($tvar_id_arr);
		//die;
		foreach($tvar_id_arr as $p_){
			$ind = array_search($p_, $tvar_id_arr);
			$mezo_value_arr = jrequest::getVar("mezo_value_arr_{$p_}", array(), "request", "array");
			$o="";
			$o->id = $p_;
			$o->termek_id = jrequest::getvar("id");
			$o->cikkszam = $cikkszam_arr[$ind];
			$o->ar = $ar_arr[$ind];			
			$o->ertek = "&";
			foreach($mezo_id_arr as $mezo_id){
				$ind2 = array_search($mezo_id, $mezo_id_arr);
				$o->ertek .= "mezoid_{$mezo_id}={$mezo_value_arr[$ind2]}&";
			}
			$this->_db->updateObject("#__whp_termekvariacio", $o, "id");
			//print_r($o);
			//die("lhnfsldfjsdlfj");
		}
		//die;
	}

}// class
?>