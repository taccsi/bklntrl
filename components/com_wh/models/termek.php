<?php
defined( '_JEXEC' ) or die( '=;)' );
ini_set("suhosin.post.max_vars", 1000);
class whModeltermek extends whAdmin{
	var $xmlFile = "termek.xml";
	var $uploaded = "";
	var $tmpname = "";
	var $table = "#__wh_termek";
	var $w = 100;
	var $h = 60;	
	var $mode = "resize";		
	//var $table ="wh_kategoria";

	function getTermekTipus(){
		$ret = "";
		$termek_tipus = jrequest::getVar( "termek_tipus", "" );
		$termek_id = jrequest::getVar( "termek_id", "" );
		$o="";
		$o->id = $termek_id;
		$o->termek_tipus = $termek_tipus;
		$this->_db->updateObject( "#__wh_termek", $o, "id" );
		$ret->html = "";
		$ret->debug = "";		
		$ret->error = "";		
		return $this->getJsonRet( $ret );
	}

	function getKalkulatorMezokFotermek(){
		$ret = "";
		$ret->html = "";
		$ret->debug = "";		
		$ret->error = "";		
		$termek_tipus = jrequest::getVar( "termek_tipus", "" );
		$termek_id = jrequest::getVar( "termek_id", "" );
		if( $termek_tipus ) {
			$ret->html.=$this->$termek_tipus();
		}
		return $this->getJsonRet( $ret );
	}

	function DARABARU( $o = "" ){
		$ret = "";
		$ret .= "";
		return $ret;
	}

	function TEKERCSES_ARU( $o = "" ){
		$ret = "";
		$name = "szelesseg";
		$value = (isset($o->$name) ) ? $o->$name : "";
		$js = "onblur =\"mentTermekTipusTv( '{$name}', this );\" ";		
		$ret .= "<input {$js} type=\"text\" value=\"{$value}\" class=\"termek_tipus_input\" name=\"{$name}\" ><br />" . jtext::_($name). "(m)";
		/*
		$name = "egysegar";
		$value = (isset($o->$name) ) ? $o->$name : "";
		$js = "onblur =\"mentTermekTipusTv( '{$name}', this );\" ";
		$ret .= "<input {$js} type=\"text\" value=\"{$value}\" class=\"termek_tipus_input\" name=\"{$name}\" ><br />" . jtext::_($name).jtext::_("netto_");
		*/
		return $ret;
	}

	function METERES_TERMEKEK( $o = "" ){
		$ret = "";
		$name = "szelesseg";
		$value = (isset($o->$name) ) ? $o->$name : "";
		$js = "onblur =\"mentTermekTipusTv( '{$name}', this );\" ";		
		//$ret .= "<input {$js} type=\"text\" value=\"{$value}\" class=\"termek_tipus_input\" name=\"{$name}\" ><br />" . jtext::_($name). "(m)";
		/*
		$name = "egysegar";
		$value = (isset($o->$name) ) ? $o->$name : "";
		$js = "onblur =\"mentTermekTipusTv( '{$name}', this );\" ";
		$ret .= "<input {$js} type=\"text\" value=\"{$value}\" class=\"termek_tipus_input\" name=\"{$name}\" ><br />" . jtext::_($name).jtext::_("netto_");
		*/
		return $ret;
	}

	function CSOMAGOLT_ARU( $o = "" ){
		$ret = "";
		
		/*
		$name = "egysegar";
		$value = (isset($o->$name) ) ? $o->$name : "";
		$js = "onblur =\"mentTermekTipusTv( '{$name}', this );\" ";
		$ret .= "<input {$js} type=\"text\" value=\"{$value}\" class=\"termek_tipus_input\" name=\"{$name}\" ><br />" . jtext::_($name).jtext::_("netto_");
		$ret .= "<br />";
		*/
		
		/*
		$name = "csomagolasi_ar";
		$value = (isset($o->$name) ) ? $o->$name : "";
		$js = "onblur =\"mentTermekTipusTv( '{$name}', this );\" ";		
		$ret .= "<input {$js} type=\"text\" value=\"{$value}\" class=\"termek_tipus_input\" name=\"{$name}\" ><br />" . jtext::_($name).jtext::_("netto_");
		$ret .= "<br />";
		*/
		
		$name = "csomagolasi_egyseg";
		$value = (isset($o->$name) ) ? $o->$name : "";
		$js = "onblur =\"mentTermekTipusTv( '{$name}', this );\" ";		
		$ret .= "<input {$js} type=\"text\" value=\"{$value}\" class=\"termek_tipus_input\" name=\"{$name}\" ><br />" . jtext::_($name);
		$ret .= "<br />";		
		return $ret;
	}
	
	function mentTermekTipusTv(){
		$tvId = jrequest::getVar( "tvId", "0" );
		$parArr = jrequest::getVar( "parArr", array() );
		$o="";
		$o->id=$tvId;
		$tmp="";
		foreach( $parArr as $a ){
			$tmp->$a = jrequest::getVar( $a, "" );
		}
		$o->termek_tipus_arr = serialize($tmp);
		//print_r($tmp);
		$this->_db->updateObject( "#__wh_termekvariacio", $o, "id" );
		$ret="";
		$ret->html = "";
		$ret->errors = "";		
		return $this->getJsonRet( $ret );
	}

	function getTermekTipusInputokFromTv( $tvO = "", $termek = ""){
		$f = $termek->termek_tipus;
		if(method_exists($this, "$f" ) ){
			$o = unserialize($tvO->termek_tipus_arr );
			$ret = $this->$f( $o );
		}
		return (isset($ret) ) ? $ret : "";
	}
	
	function getTermekVariaciok(){ 
		ob_start();
		$termek_id = jrequest::getVar("termek_id", 0);
		$termek = $this->getObj("#__wh_termek", $termek_id);
		$kategoriaObj = $this->getObj("#__wh_kategoria", $termek->kategoria_id );
		$sablonObj = $this->getObj("#__wh_msablon", $kategoriaObj->msablon_id);
		//print_r( $kategoriaObj );
		@$q = "select msablonmezo_id from #__wh_msablonmezo_kapcsolo as kapcsolo 
		inner join #__wh_msablonmezo as mezo on kapcsolo.msablonmezo_id = mezo.id
		where msablon_id = {$sablonObj->id} and  order by sorrend" ;

		@$q = "select mezo.id from #__wh_msablonmezo as mezo
		inner join #__wh_msablonmezo_kapcsolo as kapcsolo on mezo.id = kapcsolo.msablonmezo_id
		inner join #__wh_msablon as msablon on msablon.id = kapcsolo.msablon_id		
		where mezo.nev <> ''
		and msablon.id = {$kategoriaObj->msablon_id}
		group by mezo.id
		order by kapcsolo.sorrend" ;
		
		$this->_db->setQuery($q) ;
		$mezo_idArr = $this->_db->loadResultArray();		
		$arr=array();
		$value = "";
		$o="";	
		$mezo_idArr = $this->cleanTomb($mezo_idArr);
		$o->TORLES = "";
		$o->CIKKSZAM = "<div class=\"cimke\">".jtext::_("CIKKSZAM")."</div>";
		/*
		$o->KESZLET = "<div class=\"cimke\">".jtext::_("KESZLET")."</div>";		
		$o->BRUTTO_WEBES_AR = "<div class=\"cimke\">".jtext::_("BRUTTO_WEBES_AR")."</div>";
		$o->BRUTTO_WEBES_AKCIOS_AR = "<div class=\"cimke\">".jtext::_("BRUTTO_WEBES_AKCIOS_AR")."</div>";
		$o->NETTO_PRICE_B2B = "<div class=\"cimke\">".jtext::_("NETTO_PRICE_B2B")."</div>";
		$o->NETTO_DISCOUNT_PRICE_B2B = "<div class=\"cimke\">".jtext::_("NETTO_DISCOUNT_PRICE_B2B")."</div>";						
		$o->SULY_TV = "<div class=\"cimke\">".jtext::_("SULY_TV")."</div>";
		$o->TERMEK_TIPUS = "<div class=\"cimke\">".jtext::_("TERMEK_TIPUS")."</div>";
		*/		
		//suly
		//$o->NETTO_NAGYKER_AR = "<div class=\"cimke\">".jtext::_("NETTO_NAGYKER_AR")."</div>";			
		$o->PARAM_SORREND = "<div class=\"cimke\">".jtext::_("SORREND")."</div>";
			foreach($mezo_idArr as $mezo_id ){
				$ind = array_search($mezo_id, $mezo_idArr);
				$obj = $this->getObj("#__wh_msablonmezo", $mezo_id );
				$vPN = "PAR_{$ind}";
				@$o->$vPN = "<div class=\"cimke\">".$obj->nev."<input type=\"hidden\" value=\"{$mezo_id}\" name=\"mezo_id_arr[]\" />"."</div>";
			}
			$arr[]=$o;
			
			$q = "select * from #__wh_termekvariacio where termek_id = {$termek_id} order by sorrend asc, id asc ";
			$this->_db->setQuery($q);		
			$termekVariaciok = $this->_db->loadObjectList();
			//$termekVariaciok = array();
			foreach((array)$termekVariaciok as $p_ ){
				$o="";
				foreach($mezo_idArr as $mezo_id ){
					$mNev = "mezoid_{$mezo_id}";
					$$mNev ="";
				}
				parse_str($p_->ertek);			
				foreach((array)$mezo_idArr as $mezo_id ){
				$ind = array_search($mezo_id, $mezo_idArr);

				$o->TORLES = "<input onclick=\"torolTermekVariacio('{$p_->id}', '{$termek_id}', '".jtext::_("BIZTOS_HOGY_TORLOD")."')\" value=\"".jtext::_("TORLES")."\" type=\"button\" />";
				$o->CIKKSZAM = "<input type=\"text\" value=\"{$p_->cikkszam}\" name=\"cikkszam_arr[]\" />";
				$o->KESZLET = "<input type=\"text\" value=\"{$p_->keszlet}\" name=\"keszlet_arr[]\" />";				
				/*
				$o->BRUTTO_WEBES_AR = "<input type=\"text\" value=\"{$p_->ar}\" name=\"ar_arr[]\" />";
				$o->BRUTTO_WEBES_AKCIOS_AR = "<input type=\"text\" value=\"{$p_->discount_price}\" name=\"discount_price_arr[]\" />";
				$o->NETTO_PRICE_B2B = "<input type=\"text\" value=\"{$p_->b2b_price}\" name=\"b2b_price_arr[]\" />";
				$o->NETTO_DISCOUNT_PRICE_B2B = "<input type=\"text\" value=\"{$p_->b2b_price_discount}\" name=\"b2b_price_discount_arr[]\" />";
				$o->SULY_TV = "<input type=\"text\" value=\"{$p_->suly}\" name=\"suly_arr[]\" />";				
				$o->TERMEK_TIPUS = "";
				$o->TERMEK_TIPUS .= "<div rel=\"{$p_->id}\" class=\"ajaxContentTermekTipusTv\" id=\"ajaxContentTermekTipusTv{$p_->id}\" >";						
				$o->TERMEK_TIPUS .= $this->getTermekTipusInputokFromTv( $p_, $termek );
				$o->TERMEK_TIPUS .= "</div><div id=\"ajaxContentCsiga\"></div>";
				 */										
				//$o->NETTO_NAGYKER_AR = "<input type=\"text\" value=\"{$p_->netto_nagyker_ar}\" name=\"netto_nagyker_ar_arr[]\" />";
				$o->PARAM_SORREND = $this->getNyilHTML($termekVariaciok, $p_)."<input type=\"hidden\" value=\"{$p_->id}\" name=\"tvar_id_arr[]\" />";			
					$obj = $this->getObj("#__wh_msablonmezo", $mezo_id );
					$o->TORLES = "<input onclick=\"torolTermekVariacio('{$p_->id}', '{$termek_id}', '".jtext::_("BIZTOS_HOGY_TORLOD")."')\" value=\"".jtext::_("TORLES")."\" type=\"button\" />";
	
					$vPN="";
					$vPN = "PAR_{$ind}";
					$o->$vPN = "";
					$mezo_value_input_name = "mezo_value_arr_{$p_->id}[]";
					$v__="mezoid_{$mezo_id}";
					@$value = $$v__;
					$class = "class=\"termvarinput\"";
					print_r($obj);
					switch(@$obj->tipus){
						case "pipa" :
							$id_check = "check_{$mezo_id}_{$p_->id}";
							$js = "changeVal( $('{$id_check}'),this )";
							( $value== 1 ) ? $checked = "checked=\"checked\"" : $checked="";
							$o->$vPN .= "<input id=\"{$id_check}\" type=\"hidden\" 
							value=\"{$value}\" name=\"{$mezo_value_input_name}\" />
							<input {$checked} onclick=\"".$js."\" type=\"checkbox\" value=\"1\" name=\"ch____[]\" />{$obj->suffix}";
							break;
					
						case "list": 
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
						case "usr_select":
							$o->$vPN .= "<textarea {$class} value=\"{$value}\" name=\"{$mezo_value_input_name}\"></textarea>".Jtext::_('SORONKENT_EGY_ERTEKET');
						default:
							@$o->$vPN .= "<input {$class} type=\"text\" value=\"{$value}\" name=\"{$mezo_value_input_name}\" />";
					}
				}
				$arr[]=$o;
			}
			$listazo = new listazo( $arr, "", "", "", array(), "1");
		
			echo "<input type=\"button\" value=\"".jtext::_("UJ_LETREHOZASA")."\" onclick=\"letrehozUjTermekVariacio('{$termek_id}')\" />";
			echo "<div id=\"div_parameterek_\">".$listazo->getLista()."</div>";
		
		$ret = ob_get_contents();
		ob_end_clean();
		$ret_="";
		$ret_->html = $ret;
		$ret_->error = "";
		return $this->getJsonRet( $ret_);
	}
	
	function kiegTermekIrany(){
		$ret = "";
		$ret->html = "";
		$ret->debug = "";		
		$ret->error = "";				
		$termek_id = jrequest::getVar("termek_id", 0);
		$kapcsolo_id = jrequest::getVar('kapcsolo_id');	
		$irany = jrequest::getVar("irany", '');	
		
		$q = "select kapcsolo.* from #__wh_kiegtermek_kapcsolo as kapcsolo 
		inner join #__wh_termek as termek on kapcsolo.kieg_termek_id = termek.id
		where kapcsolo.termek_id = {$termek_id} 
		order by kapcsolo.sorrend, kapcsolo.id desc ";

		$this->_db->setQuery($q);
		$arr = $this->_db->LoadObjectList();
		echo $this->_db->getErrorMsg();		
		//print_r($arr);
		//die();
		foreach($arr as $a){
			$ind = array_search($a, $arr);
			if( $a->kieg_termek_id == $kapcsolo_id ){
				$akt = $arr[$ind];
				($irany == "le" ) ? $ind_ = $ind+1 : $ind_ = $ind-1;
				$temp = $arr[$ind_];
				$arr[$ind_] = $akt;
				$arr[$ind] = $temp;
			}
		}
		foreach($arr as $o){
			$o->sorrend= array_search($o, $arr);
			$this->_db->updateObject("#__wh_kiegtermek_kapcsolo", $o, "id");
			//echo $this->_db->getErrorMsg()."<br />";
		}
		return $this->getJsonRet( $ret );
	}

	function getkiegTermekek(){
		$ret = "";
		$ret->html = "";
		$ret->debug = "";		
		$ret->error = "";		
		$termek_id = jrequest::getVar( "termek_id", 0 );
		$q = "select * from #__wh_kiegtermek_kapcsolo as kapcsolo 
		inner join #__wh_termek as termek on kapcsolo.kieg_termek_id = termek.id
		where kapcsolo.termek_id = {$termek_id} 
		order by kapcsolo.sorrend, kapcsolo.id desc ";
		$this->_db->setQuery( $q );
		$rows = $this->_db->loadObjectList(  );
		echo $this->_db->getErrorMsg();
		//echo  $this->_db->getQuery(  );
		$arr = array();
		if( count($rows) ){
			foreach($rows as $r ){
				$o = "";  
				//@$kapcsolo_id = $this->getObj("#__wh_msablonmezo",$msablon_id, "msablon_id" )->id;
				$o->HIDDEN = $r->nev;
				$o->HIDDEN2 = $this->getNyilHTML($rows, $r, "kiegTermekIrany" )."<input type=\"hidden\" value=\"{$r->id}\" name=\"kieg_termek_id_arr[]\" />";			
				$o->HIDDEN3 = "<input type=\"button\" onclick=\"if(confirm('".jtext::_("BIZTOS_HOGY_TORLOD")."')){torolKiegTermek('{$r->id}')}\" value=\"".jtext::_("TOROL")."\" >";						
				$arr[]=$o;
			}
		
			if( count($arr) ){
				$listazo = new listazo($arr, "msablon_lista");
				$ret->html .= $listazo->getLista();
			}else{
				$ret->html .= "&nbsp;";
			}
		}
		return $this->getJsonRet( $ret );
	}

	function torolkiegTermek(){
		$ret = "";
		//index.php?option=com_xvs&controller=termek&task=hozzaadkiegTermek&format=raw&termek_id=5&kieg_termek_id=Bi-Drive Albion (7)
		$termek_id = jrequest::getVar( "termek_id", 0 );
		$kieg_termek_id = jrequest::getVar( "kieg_termek_id", "" );
		$q = "delete from #__wh_kiegtermek_kapcsolo 
		where termek_id = {$termek_id}
		and kieg_termek_id = {$kieg_termek_id} ";
		$this->_db->setQuery( $q );
		$this->_db->Query( );		
		
		$ret->html = "";
		$ret->error = "";
		$ret->debug = "";		
		return $this->getJsonRet( $ret );
	}

	function hozzaadkiegTermek(){
		$ret = "";
		//index.php?option=com_xvs&controller=termek&task=hozzaadkiegTermek&format=raw&termek_id=5&kieg_termek_id=Bi-Drive Albion (7)
		$termek_id = jrequest::getVar( "termek_id", 0 );
		$kieg_termek_id = jrequest::getVar( "kieg_termek_id", "" );
		preg_match("/\(.*\)/", $kieg_termek_id, $matches );
		$kieg_termek_id  = str_replace(array("(", ")"), "", $matches[0] );
		$q = "select id from #__wh_kiegtermek_kapcsolo as kapcsolo 
		where termek_id = {$termek_id}
		and kieg_termek_id = {$kieg_termek_id} ";
		$this->_db->setQuery( $q );
		if( $this->_db->loadResult() ){
			$ret->error = jtext::_("MAR_HOZZA_LETT_ADVA");
		}else{
			$o="";
			$o->termek_id = $termek_id;
			$o->kieg_termek_id = $kieg_termek_id;			
			$this->_db->insertObject("#__wh_kiegtermek_kapcsolo", $o, "id" );
		}
		$ret->html = "";
		$ret->debug = "";	
		return $this->getJsonRet( $ret );
		//echo  $this->_db->getQuery(  );
	}
// ***********************************************************************************************************

	function kapcsolodoTermekIrany(){
		$ret = "";
		$ret->html = "";
		$ret->debug = "";		
		$termek_id = jrequest::getVar("termek_id", 0);
		$kapcsolo_id = jrequest::getVar('kapcsolo_id');	
		$irany = jrequest::getVar("irany", '');	
		
		$q = "select kapcsolo.* from #__wh_ktermek_kapcsolo as kapcsolo 
		inner join #__wh_termek as termek on kapcsolo.kapcsolodo_termek_id = termek.id
		where kapcsolo.kapcsolodo_termek_id = {$kapcsolo_id} 
		order by kapcsolo.sorrend, kapcsolo.id desc ";

		$this->_db->setQuery($q);
		$arr = $this->_db->LoadObjectList();
		
		
		foreach($arr as $a){
			$ind = array_search($a, $arr);
			//print_r($a);
			//die();
			if($a->termek_id == $termek_id){
				$akt = $arr[$ind];
				($irany == "le" ) ? $ind_ = $ind+1 : $ind_ = $ind-1;
				$temp = $arr[$ind_];
				$arr[$ind_] = $akt;
				$arr[$ind] = $temp;
			}
		}
		foreach($arr as $o){
			$o->sorrend= array_search($o, $arr);
			$this->_db->updateObject("#__wh_ktermek_kapcsolo", $o, "id");
			//echo $this->_db->getErrorMsg()."<br />";
		}
		return $this->getJsonRet( $ret );
	}

	function getKapcsolodoTermekek(){
		$ret = "";
		$ret->html = "";
		$ret->debug = "";		
		$termek_id = jrequest::getVar( "termek_id", 0 );
		$q = "select * from #__wh_ktermek_kapcsolo as kapcsolo 
		inner join #__wh_termek as termek on kapcsolo.termek_id = termek.id
		where kapcsolo.kapcsolodo_termek_id = {$termek_id} 
		order by kapcsolo.sorrend asc, kapcsolo.id desc ";
		$this->_db->setQuery( $q );
		$rows = $this->_db->loadObjectList(  );
		echo  $this->_db->getErrorMsg(  );
		//echo  $this->_db->getQuery(  );
		$arr = array();

		if( count($rows) ){
			foreach($rows as $r ){
				$o = "";  
				//@$kapcsolo_id = $this->getObj("#__wh_msablonmezo",$msablon_id, "msablon_id" )->id;
				$o->HIDDEN = "<a onclick = \"window.open('index.php?option=com_wh&controller=termek&task=edit&fromlist=1&cid[0]={$r->id}&layout=kapcsolodo','Új kapcsolódó szolgáltatás','width=800,height=600')\" href=\"javascript:;\">".$r->nev."<a/>";
				$o->HIDDEN2 = $this->getNyilHTML($rows, $r, "kapcsolodoTermekIrany" )."<input type=\"hidden\" value=\"{$r->id}\" name=\"kapcsolodo_termek_id_arr[]\" />";			
				$o->HIDDEN3 = "<input type=\"button\" onclick=\"if(confirm('".jtext::_("BIZTOS_HOGY_TORLOD")."')){torolKapcsolodoTermek('{$r->id}')}\" value=\"".jtext::_("TOROL")."\" >";						
				$arr[]=$o;
			}
		
			if( count($arr) ){
				$listazo = new listazo($arr, "msablon_lista");
				$ret->html .= $listazo->getLista();
			}else{
				$ret->html .= "&nbsp;";
			}
		}
		return $this->getJsonRet( $ret );
	}

	function torolKapcsolodoTermek(){
		$ret = "";
		//index.php?option=com_xvs&controller=termek&task=hozzaadKapcsolodoTermek&format=raw&termek_id=5&kapcsolodo_termek_id=Bi-Drive Albion (7)
		$termek_id = jrequest::getVar( "termek_id", 0 );
		$kapcsolodo_termek_id = jrequest::getVar( "kapcsolodo_termek_id", "" );
		$q = "delete from #__wh_ktermek_kapcsolo 
		where termek_id = {$termek_id}
		and kapcsolodo_termek_id = {$kapcsolodo_termek_id} ";
		$this->_db->setQuery( $q );
		$this->_db->Query( );		
		
		$ret->html = "";
		$ret->error = "";
		$ret->debug = "";		
		return $this->getJsonRet( $ret );
	}

	function hozzaadKapcsolodoTermek(){
		$ret = "";
		//index.php?option=com_xvs&controller=termek&task=hozzaadKapcsolodoTermek&format=raw&termek_id=5&kapcsolodo_termek_id=Bi-Drive Albion (7)
		$termek_id = jrequest::getVar( "termek_id", 0 );
		$kapcsolodo_termek_id = jrequest::getVar( "kapcsolodo_termek_id", "" );
		preg_match("/\(.*\)/", $kapcsolodo_termek_id, $matches );
		$kapcsolodo_termek_id  = str_replace(array("(", ")"), "", $matches[0] );
		$q = "select id from #__wh_ktermek_kapcsolo as kapcsolo 
		where termek_id = {$termek_id}
		and kapcsolodo_termek_id = {$kapcsolodo_termek_id} ";
		$this->_db->setQuery( $q );
		if( $this->_db->loadResult() ){
			$ret->error = jtext::_("MAR_HOZZA_LETT_ADVA");
		}else{
			$o="";
			$o->termek_id = $termek_id;
			$o->kapcsolodo_termek_id = $kapcsolodo_termek_id;			
			$this->_db->insertObject("#__wh_ktermek_kapcsolo", $o, "id" );
		}
		$ret->html = "";
		$ret->debug = "";		
		return $this->getJsonRet( $ret );
		//echo  $this->_db->getQuery(  );
	}

	function store(){
		$row =& $this->getTable(str_replace("#__", "" , $this->table) );
		//$row =& $this->getTable("vcmr_beszallito");		
		//die("----");
		foreach($this->getFormFieldArray() as $parName){//ha tömböt kell menteni
			$val = JRequest::getVar($parName,"", "",2,2,2);
			//echo $parName."---<br />";
			if(is_array($val)){
					$data[$parName] = ",".implode(",", $val).",";
				//echo $data[$parName]."<br />";
				}else{
					$data[$parName] = $val;			
			}
			$data['besorolatlan'] = 'nem';
				
		}
		//int_r($this->getFormFieldArray()); die();
		//die ($txt);
		// Bind the form fields to the hello table
		if (!$row->bind($data)) {
			$this->setError($this->_db->stderr());
			return false;
		}
		
		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->stderr());
			return false;
		}
		
		// Store the table to the database
		//print_r($row); exit;
		if (!$row->store()) {
			$this->setError( $row->getError() );
				//die("hiba");
				return false;
		}else{
			//echo "--------------".;
			$id = $this->_db->insertId();
			if(!$id){
				$id = $this->getSessionVar("id");
			}
		//$this->saveOneletrajzok($id);
		}
		//die("-{$id}");
		//$this->saveArak($id);
		$this->savePrices($id);
		$this->mentParhKategoria($id);
		//die;
		if ($data['kapcsolodo_termek_id']){
			$this->mentKapcsTermek($data['kapcsolodo_termek_id'],$id);
		}
		
		$this->mentBeszallitoAr();
		$this->saveTermekVariaciok();
		$this->torolFajlok();	
		$this->mentFajlok( $id ); 
		$this->mentKategoriaWebshop( $id );
		return $id;
	}   	
	
	function mentKapcsTermek($kapcsolodo_termek_id, $id){
		//$kapcsolodo_termek_id = jrequest::getVar( "kapcsolodo_termek_id", "" );
		//preg_match("/\(.*\)/", $kapcsolodo_termek_id, $matches );
		//$kapcsolodo_termek_id  = str_replace(array("(", ")"), "", $matches[0] );
		$q = "select id from #__wh_ktermek_kapcsolo as kapcsolo 
		where termek_id = {$id}
		and kapcsolodo_termek_id = {$kapcsolodo_termek_id} ";
		$this->_db->setQuery( $q );
		if( $this->_db->loadResult() ){
			
		}else{
			$o="";
			$o->termek_id = $id;
			$o->kapcsolodo_termek_id = $kapcsolodo_termek_id;			
			//print_r($o);
			$this->_db->insertObject("#__wh_ktermek_kapcsolo", $o, "id" );
		}
//die('fut');
	}
	
	function mentParhKategoria($id){
				
		$kategoriak = jrequest::getvar('parh_kategoria_id');
		//print_r($kategoriak); die();
		
		if (count($kategoriak)){
			$q = "delete from #__wh_parh_kat_kapcs where termek_id = {$id}";
			$this->_db->setquery($q);
			$this->_db->query();

			foreach ($kategoriak as $k){
				$o = '';
				$o->kategoria_id = $k;
				$o->termek_id = $id;
				$this->_db->insertobject('#__wh_parh_kat_kapcs',$o);
			}
		}
		//print_r($webshop_idk);
		//print_r($arak);
		//die;
		
	}
	
	
	
	function __construct(){
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");
		if( !$this->_data ) $this->getData();
	 	$this->xmlParser = new xmlTermek($this->xmlFile, $this->_data);
		if($this->xmlParser->getaktVal("id")){
			$this->setSessionVar("aktTermek", $this->xmlParser->getaktVal("id"));
		}
		//echo "***<br />";
		$termek_id = jrequest::getVar("cid", 0);
	}//function



	function mentKep(){
		ob_start();
		$termek_id = jrequest::getVar("termek_id", 0);
		$kepalairas = jrequest::getVar("kepalairas", '');
		$kepId = jrequest::getVar("kepId", '');
		if($kepId){	
			$o = "";
			$o->id = $kepId;
			$o->kepalairas = $kepalairas;
			$this->_db->updateObject("#__wh_kep", $o, "id");
			//print_r($o);
		}
		$r_ = ob_get_contents();
		ob_end_clean();
		return $this->getKepLista().$r_;
	}

	function mentKepek(){
		ob_start();
		$termek_id = jrequest::getVar("termek_id", 0);
		$kepId = jrequest::getVar("kepId", 0);
		$aktiv = jrequest::getVar("aktiv", 0);
		$o="";
		$o->id = $kepId;
		$o->aktiv = $aktiv;
		$this->_db->updateObject("#__wh_kep", $o, "id");
		//print_r($o);
		$ret = ob_get_contents();
		ob_end_clean();
		$ret->html="";
		return $this->getJsonRet( $ret );
	}

	function kepIrany(){
		ob_start();
		$termek_id = jrequest::getVar("termek_id", 0);
		$kep_id = jrequest::getVar("kep_id", 0);
		$irany = jrequest::getVar("irany", '');	
		$q = "select * from #__wh_kep where termek_id = {$termek_id} order by sorrend, id";
		$this->_db->setQuery($q);
		$arr = $this->_db->LoadObjectList();
		//print_r($arr);
		if( count( $arr ) > 1){
			foreach($arr as $a){
				$ind = array_search($a, $arr);
				if($a->id == $kep_id){
					$akt = $arr[$ind];
					($irany == "le" ) ? $ind_ = $ind+1 : $ind_ = $ind-1;
					$temp = $arr[$ind_];
					$arr[$ind_] = $akt;
					$arr[$ind] = $temp;
				}
			}
			foreach($arr as $o){
				$o->sorrend= array_search($o, $arr);
				$this->_db->updateObject("#__wh_kep", $o, "id");
			}
		}
		$ret = ob_get_contents();
		ob_end_clean();
		
		return $ret.$this->getKepLista();
	}
	
	function letrehozUjTermekVariacio(){
		$termek_id = jrequest::getVar("termek_id", 0);		
		$q = "select * from #__wh_termekvariacio where termek_id = {$termek_id} order by sorrend desc, id desc limit 1 ";
		$this->_db->setQuery( $q );
		$o = $this->_db->loadObject( );		
		unset($o->id); 
		//$o="";
		$o->termek_id = $termek_id;
		$this->_db->insertObject("#__wh_termekvariacio", $o);
		return $this->getTermekVariaciok();		
	}
	
	function torolTermekVariacio(){
		$tvar_id = jrequest::getVar("tvar_id", 0);
		$q = "delete from #__wh_termekvariacio where id = '{$tvar_id}'";
		$this->_db->setQuery($q);
		$this->_db->Query();		
		return $this->getTermekVariaciok();
	}

	function termvarIrany(){
		ob_start();
		$termek_id = jrequest::getVar("termek_id", 0);
		$termvar_id = jrequest::getVar("termvar_id", 0);
		$irany = jrequest::getVar("irany", '');	
		$q = "select * from #__wh_termekvariacio where termek_id = {$termek_id} order by sorrend, id";
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
			$this->_db->updateObject("#__wh_termekvariacio", $o, "id");
		}
		$ret = ob_get_contents();
		ob_end_clean();
		
		return $ret.$this->getTermekVariaciok();
	}

	function saveTermekVariaciok(){
		$cikkszam_arr = jrequest::getVar("cikkszam_arr", array(), "request", "array");
		$tvar_id_arr = jrequest::getVar("tvar_id_arr", array(), "request", "array");
		$mezo_id_arr = jrequest::getVar("mezo_id_arr", array(), "request", "array");
		$ar_arr = jrequest::getVar("ar_arr", array(), "request", "array");
		$keszlet_arr= jrequest::getVar("keszlet_arr", array(), "request", "array");
		$discount_price_arr = jrequest::getVar("discount_price_arr", array(), "request", "array");
		$b2b_price_arr = jrequest::getVar("b2b_price_arr", array(), "request", "array");
		$b2b_price_discount_arr = jrequest::getVar("b2b_price_discount_arr", array(), "request", "array");
		$suly_arr = jrequest::getVar("suly_arr", array(), "request", "array");
		
		$netto_nagyker_ar_arr = jrequest::getVar("netto_nagyker_ar_arr", array(), "request", "array");				
		//print_r($tvar_id_arr);
		//die;
		foreach($tvar_id_arr as $p_){
			$ind = array_search($p_, $tvar_id_arr);
			$mezo_value_arr = jrequest::getVar("mezo_value_arr_{$p_}", array(), "request", "array");
			$o="";
			$o->id = $p_;
			$o->termek_id = jrequest::getvar("id");
			$o->cikkszam = $cikkszam_arr[$ind];
			$o->keszlet = $keszlet_arr[$ind];			
			
			$o->ar = $ar_arr[$ind];

			$o->discount_price = $discount_price_arr[$ind];
			$o->b2b_price = $b2b_price_arr[$ind];
			$o->b2b_price_discount = $b2b_price_discount_arr[$ind];									
			$o->suly = $suly_arr[$ind];
			//$o->netto_nagyker_ar = $netto_nagyker_ar_arr[$ind];					
			$o->ertek = "&";
			foreach($mezo_id_arr as $mezo_id){
				$ind2 = array_search($mezo_id, $mezo_id_arr);
				$o->ertek .= "mezoid_{$mezo_id}={$mezo_value_arr[$ind2]}&";
			}
			$this->_db->updateObject("#__wh_termekvariacio", $o, "id");
			//print_r($o);
			//die("lhnfsldfjsdlfj");
		}
		//die;
	}
   
	function torolKep(){
		$kep_id = jrequest::getVar("kep_id", 0);
		if(file_exists($this->xmlParser->getKepNev($kep_id))){
			unlink( $this->xmlParser->getKepNev($kep_id) );
		}
		$q = "delete from #__wh_kep where id = {$kep_id} limit 1";
		$this->_db->setQuery($q);
		$this->_db->Query($q);			
		return $this->getKepLista();
	}
	
	function setListaKep(){
		$termek_id = jrequest::getVar( "termek_id", "" );
		$listakep_id = jrequest::getVar( "listakep_id", "" );
		$q = "update #__wh_kep set listakep = 'nem' where termek_id = '{$termek_id}' ";
		$this->_db->setQuery($q);
		$this->_db->Query();
		if( $listakep_id ){
			$q = "update #__wh_kep set listakep = 'igen' where id = '{$listakep_id}' ";
			$this->_db->setQuery($q);
			$this->_db->Query();
		}
		echo $this->_db->getErrorMsg( );
		$ret_ ="";
		$ret_->html = "";
		$ret_->error = "";		
		return $this->getjsonRet($ret_);
	}
	
	function getKepLista(){
		ob_start();
		$termek_id = jrequest::getVar("termek_id", 0);
		if( $termek_id ){
			$q = "select * from #__wh_kep where termek_id = {$termek_id} order by sorrend asc ";
			$this->_db->setQuery($q);
			$arr = array();
			$kepek = $this->_db->loadObjectList();
			foreach( $kepek as $k){
				$o = "";
				$o->KEP = $this->getListaKep( $k->id );
				$kepalId = "kepalId_{$k->id}";
				/*
				$o->KEPALAIRAS = "<input id=\"{$kepalId}\" name=\"kepalairas[]\" type=\"text\" value=\"{$k->kepalairas}\" >";
				$o->KEPALAIRAS .= "<a onclick=\"mentKep( '{$kepalId}', '{$k->id}')\" href=\"javascript:void(0)\"><img src=\"administrator/images/filesave.png\" /></a>";
				*/
				//$o->PUBLIKUS_KEP = $this->getCheckboxKep( "aktiv", $k->aktiv, $k->id );
				$idCheck = "listakepCh{$k->id}";
				$checked = ( $k->listakep == "igen" ) ? "checked=\"checked\"" : "" ;
				$listakep = "<input class=\"listakepCh\" id=\"{$idCheck}\" {$checked} type=\"checkbox\" onclick=\"setListaKep('{$idCheck}')\" value=\"{$k->id}\" />";
				$o->LISTAKEP = $listakep;
				$o->SORREND_HIDDEN = $this->getNyilHTML( $kepek, $k, "kepIrany" );
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
		$r_ = ob_get_contents();
		ob_end_clean();
		$ret_ ="";
		$ret_->html = $ret;
		return $this->getjsonRet($ret_);
	}

	function getCheckboxKep($name, $value, $kepId ){
		//$name = $node->getAttribute();
		ob_start();
		
		$idHidden = "{$name}_{$kepId}";
		$idCheck = "{$kepId}check_{$name}_";			
		$js = "onclick=\"kapcsolHiddenByCheck( '{$idCheck}', '{$idHidden}' ); mentKepek( '{$idHidden}', '{$kepId}' )\"";
		$class = "class=\"alapinput {$name}\"";
		//echo $value." ----";
		//$label = $node->getAttribute("label");
		($value == 'igen') ? $checked = "checked=\"checked\"" : $checked = "";
		echo "<span {$class} ><input {$class} id=\"{$idCheck}\" {$checked} type=\"checkbox\" {$js} value=\"igen\" /></span>";
		echo "<input type=\"hidden\" value=\"{$value}\" name=\"{$name}[]\" id=\"{$idHidden}\"  />";
		$ret = ob_get_contents();
		
		ob_end_clean();
		return $ret;
	}

	function getData(){
		// Load the datadie;
		//exit;
		if (empty( $this->_data )) {
			$query = "SELECT * FROM {$this->table} WHERE id = {$this->_id}";
			
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
			$arr=array();
			$arr[]=$this->_data;
			$arr = array_map ( array($this, "setKonkurenciaAr"), $arr);
			$this->_data = $arr[0];
		}
		if (!$this->_data) {
			$this->_data = new stdClass();

			$data = JRequest::get( 'post' );
			foreach($data  as $v => $n){
				$this->_data->$v = $n;
			}
			//print_r($this->_data);
		}
		
		$this->setMandatoryFields();
		//print_r($_REQUEST);exit;
		//print_r($this->_data);exit;
		return $this->_data;
	}//function

	function sorrendKep(){
		$irany = JRequest::getVar( "lepetetes_iranya" );
		$termek_id = JRequest::getVar( "termek_id" );
		$sorrend = JRequest::getVar("sorrend");	
		$this->_db->setQuery("select id, sorrend from #__wh_kep where termek_id={$termek_id} order by sorrend");
		$rows = $this->_db->loadObjectList();
		$sorrend_tomb = array();
		foreach($rows as $row){
			$id_tomb[] = $row->id;
			$sorrend_tomb[] = $row->sorrend;
		}

		$ind = array_search($sorrend, $sorrend_tomb);
		if($irany=="le"){
			$temp = $sorrend_tomb[$ind+1];
			$sorrend_tomb[$ind+1] = $sorrend;
			$sorrend_tomb[$ind] = $temp;
		}
		
		else{
			$temp = $sorrend_tomb[$ind-1];
			$sorrend_tomb[$ind-1] = $sorrend;
			$sorrend_tomb[$ind] = $temp;
		}
		for($i=0; $i<count($id_tomb); $i++){
			echo $sorrend_tomb[$i];
			
			$q = "update #__wh_kep set sorrend = '{$sorrend_tomb[$i]}' where id = {$id_tomb[$i]}";
			$this->_db->setQuery($q);
			$this->_db->Query();
			//die( $this->_db->getErrorMsg() );
		};
	}

	function mentBeszallitoAr(){
		$beszallito_ar_id = JREquest::getVaR("beszallito_ar_id", array(), "array");
		$beszallito_netto_ar = JREquest::getVaR("beszallito_netto_ar", array(), "array");		
		$afa_id_beszallito_ar = JREquest::getVaR("afa_id_beszallito_ar", array(), "array");		
		foreach($beszallito_ar_id as $ar_id){
			$ind = array_search($ar_id, $beszallito_ar_id);
			$ar = $beszallito_netto_ar[$ind];
			$afa_id = $afa_id_beszallito_ar[$ind];		
			$o = "";
			$o->id = $ar_id;
			$o->netto_ar = $ar;
			$o->afa_id = $afa_id;
			$this->_db->updateObject("#__wh_termek_beszallito_ar", $o, "id");
		}
	}
	
	function torolBeszallitoAr(){
		$torol_id = JREquest::getVaR("torol_id_", "");
		$q = "delete from #__wh_termek_beszallito_ar where id = {$torol_id}";
		$this->_db->setQuery($q);
		$this->_db->Query();
	}
	
	function torolTermekKep(){
		$torol_id = JREquest::getVaR("torolKepId", "");
		$q = "delete from #__wh_kep where id = {$torol_id}";
		$kep = "{$this->uploaded}/{$torol_id}_1.jpg";
		unlink($kep);
		$this->_db->setQuery($q);
		$this->_db->Query();
	}
	
	function duplikalTermek( $termek_id ){
		$o = $this->getObj( "#__wh_termek", $termek_id );
		unset($o->id);
		$o->szulo_termek_id = $termek_id; 
		$this->_db->insertObject("#__wh_termek", $o, "id");
		return $this->_db->insertId( );
	}
	
	function saveMezok(){
		$termek_id = $this->getSessionVar("id");
		$msablon_ertek = implode(",",JRequest::getVar("msablon_ertek", " "));
		$msablon_ertek = ",".$msablon_ertek.",";
		//$mezoid_k = JRequest::getVar("mezoid_k", "");
			/*$q = "update #__wh_termek set msablon_ertek = '{$msablon_ertek}' where id = {$termek_id} ";
			$this->_db->setQuery($q);
			$this->_db->Query();*/
	}

	function mentKategoriaWebshop( $termek_id ){
		if($termek_id){
			$kategoria_id_webshop = jrequest::getVar("kategoria_id_webshop", array() );
			$webshop_id = jrequest::getVar("webshop_id", array(0) );
			$q = "delete from #__wh_kategoria_kapcsolo where termek_id = {$termek_id} and webshop_id in (".implode(",",$webshop_id).") ";
			$this->_db->setQuery($q);
			$this->_db->Query();
			foreach( $webshop_id as $wId ){
				$ind = array_search($wId, $webshop_id);
				if($kategoria_id = $kategoria_id_webshop[$ind]){
					$o = "";
					$o->termek_id = $termek_id;
					$o->webshop_id = $wId;
					$o->kategoria_id = $kategoria_id;
					$this->_db->insertObject("#__wh_kategoria_kapcsolo", $o);
					//echo $this->_db->getErrorMsg()."<br />";
					//print_r($o);
				}
			}
			//die;
		}
	}

   function saveArak($termek_id){
		$webshop_idk = JRequest::getVar("webshopId", array(), "array" );
		$arak = JRequest::getVar("ar", array(), "array" );
		$bruttoArArr = JRequest::getVar("bruttoAr", array(), "array" );
				
		$arakafa_id = JRequest::getVar("arakafa_id", array(), "array" );
		//print_r($webshop_idk);
		//print_r($arak);
		//die;
		$afa_id = JRequest::getVar("afa_id", "");	
		foreach($webshop_idk as $webshop_id){
			$o = "";
			$ind = array_search( $webshop_id, $webshop_idk );
			$afaObj = $this->getObj("#__wh_afa", $arakafa_id[$ind] );			
			$o->termek_id = $termek_id; 
			$ar = ( $arak[$ind] ) ? $arak[$ind] : $bruttoArArr[$ind] / ( $afaObj->ertek / 100 + 1 ) ;
			$o->ar= $ar;
			$o->webshop_id = $webshop_id;
			$o->afa_id = $arakafa_id[$ind];
			//print_r( $o );
			//die;
			if( $id = $this->arLetezik($o) ){
				$o->id = $id;
				$this->_db->updateObject("#__wh_ar", $o, "id");
			}else{
				$this->_db->insertObject("#__wh_ar", $o, "id");
			}
		}
		$q = "";
   }
   
   function arLetezik($o){
   	$q = "select id from #__wh_ar where termek_id = {$o->termek_id} and webshop_id = {$o->webshop_id}";
	$this->_db->setQuery($q);
	return $this->_db->loadResult();
   }
   
   function getbeszallito($id)
   {
   	$this->_db->setQuery("SELECT * FROM #__wh_teremek WHERE id = {$id}");
	return $this->_db->loadObject();
   }
  
   function delete($cid)
	{
		//print_r($)
		//die("----");
		$db = &JFactory::getDBO();
		$idk = JRequest::getVar("cid");
		//print_r($idk); die();
		
		$idk = implode(',',$idk);
		//die($idk);		
		$db->setQuery("DELETE from #__wh_termek WHERE id in ({$idk})");
		
		//die($db->getQuery());
		return $db->query();
	}
	
	function torolCimke(){
		$termek_id = Jrequest::getvar('termek_id');
		$cimke_id = jrequest::getVar("cimke_id","0");		
		$q="delete from #__wh_cimke where id = {$cimke_id} ";
		$this->_db->setQuery($q);		
		$this->_db->Query();	
		$q="delete from #__wh_cimke_kapcsolo where cimke_id = {$cimke_id} ";
		$this->_db->setQuery($q);		
		$this->_db->Query();				
		$ret->html = '';
		return $this->getjsonret($ret);	
	}
	
	function addCimke(){
		ob_start();
		$termek_id = Jrequest::getvar('termek_id');
		$o="";
		$o->nev = urldecode(JRequest::getVar('text',"", "",2,2,2));
		$o->kategoria_id = '24';
		$this->_db->setquery("select id from #__wh_cimke where nev like '{$o->nev}' ");
		$check_db = $this->_db->loadresultarray();
		/*if (count($check_db)){
			echo Jtext::_('MAR_LETEZIK');
		} else {
			$this->_db->insertObject("#__wh_cimke", $o);
		}*/
		$this->_db->insertObject("#__wh_cimke", $o);
		echo $this->_db->getErrorMsg();
		//$o = '';
		//$o->cimke_id = ;
		
		$x = ob_get_contents();
		ob_end_clean();
		$ret->error = $x;
		return $this->getjsonret($ret);
	}

	function getCheckbox($name, $value, $cimkeId, $termek_id ){
		//$name = $node->getAttribute();
		ob_start();
		
		$idHidden = "{$name}_{$cimkeId}";
		$idCheck = "{$cimkeId}check_{$name}_";			
		$js = "onclick=\"kapcsolHiddenByCheck( '{$idCheck}', '{$idHidden}' ); mentCimkeKapcsolo( '{$termek_id}', '{$cimkeId}', '{$idHidden}' )\"";
		$class = "class=\"alapinput {$name}\"";
		//echo $value." ----";
		//$label = $node->getAttribute("label");
		($value == 'igen') ? $checked = "checked=\"checked\"" : $checked = "";
		echo "<span {$class} ><input {$class} id=\"{$idCheck}\" {$checked} type=\"checkbox\" {$js} value=\"igen\" /></span>";
		echo "<input type=\"hidden\" value=\"{$value}\" name=\"{$name}[]\" id=\"{$idHidden}\"  />";
		$ret = ob_get_contents();
		
		ob_end_clean();
		return $ret;
	} 

	function getCimkeKategoriaArr( $kategoria_id = 0 ){
		$k = $this->getObj( "#__wh_kategoria", $kategoria_id );
		//print_r($k);
		//die;
		$q = "select kategoria.cimke_kategoria_id from #__wh_kategoria as kategoria
		where kategoria.lft <= {$k->lft} and kategoria.rgt >= {$k->rgt} and kategoria.cimke_kategoria_id <> '' ";
		$this->_db->setQuery($q);
		$arr_ = explode(",", $this->_db->loadResult( ) );
		$arr_ = $this->cleanTomb( $arr_ );
		//print_r($ret);
		$q = "select id from #__wh_cimke_kategoria as kategoria where kategoria.id in (".implode(", ", $arr_ ).")";
		$this->_db->setQuery($q);
		$ret = $this->_db->loadResultArray( );
		//print_r($ret);
		//echo $q;
		return ( is_array($ret) ) ? $ret : array(0); 
	}
	
	function getCimkeLista($termek_id=0){ 
		$ret ="";
		if (!$termek_id){
			$termek_id = jrequest::getvar("termek_id",0);
		}
		if(!$termek_id){
			$ret->html = jtext::_("A FUNKCIO ELERESEHEZ MENTES SZUKSEGES");			
			return $this->getjsonret($ret);
		};
		ob_start();
		$q = "select cimke_id from #__wh_cimke_kapcsolo where termek_id = {$termek_id}  ";
		$this->_db->setquery($q);
		$termek_cimkek = $this->_db->loadresultarray();
		//$user_id = $this->user->id;
		//$webshop_id = $this->getSessionVar("webshop_id");		
		$cimkeKategoriaIdArr = $this->getCimkeKategoriaArr( $this->getObj("#__wh_termek", $termek_id)->kategoria_id );
		//print_r($cimkeKategoriaArr);
		//die;
		
		$q = "select cimke.*, kategoria.id as kategoria_id, kategoria.nev as kategoria_nev
		from #__wh_cimke as cimke
		left join #__wh_cimke_kategoria as kategoria on cimke.kategoria_id = kategoria.id
		where cimke.kategoria_id in ( 24," . implode(", ", $cimkeKategoriaIdArr ) . " )
		order by kategoria_nev, cimke.sorrend, nev asc ";
		$this->_db->setQuery($q);
		$obj = $this->_db->loadobjectList();
		//echo $this->_db->getErrorMsg();
		echo $this->_db->getquery(true);
		//print_r($obj);
		//die();
		$arr = array();
		if ( count($obj) && $termek_id ){
			$k_=-1;
			foreach($obj as $a ){
				if( $a->kategoria_id != $k_ ){
					$k_ = $a->kategoria_id;
					$o="";
					$o->CIMKE_KAPCSOLO = "<span class=\"span_cimke_kategoria\" >" . $a->kategoria_nev . "</span>";
					$o->NEV = "";
					$o->CIMKE_KATEGORIA = "";
					$o->SORREND = "";
					$o->TORLES = "";
					$arr[] = $o;
				}
				if (in_array($a->id,$termek_cimkek)){$cimke_kapcsolo = 'igen';}else {$cimke_kapcsolo = '';} // antika
				$o="";
				$o->CIMKE_KAPCSOLO = $this->getCheckbox("cimke_kapcsolo", $cimke_kapcsolo, $a->id, $termek_id );
				$o->NEV = $a->nev;
				$o->CIMKE_KATEGORIA = $this->getCimkeKategoriaSzelekt( $a->kategoria_id, $a->id );				
				$o->SORREND = $this->getNyilHTML($obj, $a, "cimkeIrany" )."<input type=\"hidden\" value=\"{$a->id}\" name=\"cimke_id_arr[]\" />";
				
				$js = "if (confirm('".Jtext::_('BIZTOS_TORLI_A_CIMKET')."')){torolCimke('{$a->id}','{$termek_id}') }";
				$o->TORLES = "<input onclick=\"{$js}\" type=\"button\" value=\"".jtext::_("TORLES")."\" >";
				$arr[] = $o;
			}
		} 
		//print_r($arr); die();
		
		//UNITEMPLATE
		if( $termek_id ){
			if ( count($arr) ) {
				$listazo = new listazo($arr);
				echo $listazo->getLista();
			} else {
				echo "<div class=\"NINCS_CIMKE\">"."</div>";
			}
		}else{
			echo jtext::_("A FUNKCIO ELERESEHEZ MENTES SZUKSEGES");		
		}
		//
		$x = ob_get_contents();
		ob_end_clean();
		$ret->html = $x;
		return $this->getjsonret($ret);
		//return "------";
	}

	function cimkeIrany(){
		$ret = "";
		$ret->html = "";
		$ret->debug = "";		
		$ret->error = "";				
		$termek_id = jrequest::getVar("termek_id", 0);
		$kapcsolo_id = jrequest::getVar('kapcsolo_id');	
		$irany = jrequest::getVar("irany", '');	

		$q = "select cimke_id from #__wh_cimke_kapcsolo where termek_id = {$termek_id}  ";
		$this->_db->setquery($q);
		$termek_cimkek = $this->_db->loadresultarray();
		//$user_id = $this->user->id;
		//$webshop_id = $this->getSessionVar("webshop_id");		
		$cimkeKategoriaIdArr = $this->getCimkeKategoriaArr( $this->getObj("#__wh_termek", $termek_id)->kategoria_id );
		//print_r($cimkeKategoriaArr);
		//die;
		$q = "select cimke.*, kategoria.id as kategoria_id, kategoria.nev as kategoria_nev
		from #__wh_cimke as cimke
		left join #__wh_cimke_kategoria as kategoria on cimke.kategoria_id = kategoria.id
		where kategoria.id in ( 23," . implode(", ", $cimkeKategoriaIdArr ) . " )
		order by kategoria_nev, cimke.sorrend, nev asc ";

		$q = "select cimke.*, kategoria.id as kategoria_id, kategoria.nev as kategoria_nev
		from #__wh_cimke as cimke
		left join #__wh_cimke_kategoria as kategoria on cimke.kategoria_id = kategoria.id
		where kategoria.id in ( 23," . implode(", ", $cimkeKategoriaIdArr ) . " )
		order by kategoria_nev, cimke.sorrend, nev asc ";

		$this->_db->setQuery($q);
		$arr = $this->_db->LoadObjectList();
		echo $this->_db->getErrorMsg();		
		//print_r($arr);
		//die();
		foreach($arr as $a){
			$ind = array_search($a, $arr);
			if( $a->id == $kapcsolo_id ){
				$akt = $arr[$ind];
				($irany == "le" ) ? $ind_ = $ind+1 : $ind_ = $ind-1;
				$temp = $arr[$ind_];
				$arr[$ind_] = $akt;
				$arr[$ind] = $temp;
			}
		}
		foreach($arr as $o){
			$o_= "";
			$o_->id = $o->id;
			$o_->sorrend = array_search($o, $arr);
			$this->_db->updateObject("#__wh_cimke", $o_, "id");
			//print_r($o);
			//echo $this->_db->getErrorMsg()."<br />";
		}
		return $this->getJsonRet( $ret );
	}

	function getNyilHTML( $arr, $obj, $jFunc="termvarIrany" ){
		foreach($arr as $a){
			$ind = array_search($a, $arr);
			if($a->id == $obj->id) break;
		}
		$le = "<a href=\"javascript:void(0)\" onclick=\"{$jFunc}('le', '{$obj->id}')\" ><img src=\"components/com_wh/assets/images/downarrow.png\" /></a>";
		$fel = "<a href=\"javascript:void(0)\" onclick=\"{$jFunc}('fel', '{$obj->id}')\" ><img src=\"components/com_wh/assets/images/uparrow.png\" /></a>";
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

	function mentCimkeKategoria(){
		$kategoria_id = jrequest::getVar( "kategoria_id", "" );
		$cimke_id = jrequest::getVar( "cimke_id", "" );	
		$o ="";
		$o->id = $cimke_id;
		$o->kategoria_id = $kategoria_id;	
		$this->_db->updateObject( "#__wh_cimke", $o, "id" );	 	
		$ret = "";
		$ret->error="";
		return $this->getJsonRet($ret);
	}
	
	function getCimkeKategoriaSzelekt( $kategoria_id, $cimke_id = 0 ){
		$value = $kategoria_id;
		$q = "select id as `value`, nev as `option`
		from #__wh_cimke_kategoria order by nev";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList( );		
		//echo $this->_db->getQuery( );
		echo $this->_db->getErrorMsg( );		
		foreach($rows as $r){
		}
		/*
		$o="";
		$o->value = $o->option = "";
		array_unshift( $rows, $o );
		*/
		$name = "cimkeKat{$cimke_id}";
		return JHTML::_( 'Select.genericlist', $rows, $name, array( "class"=>"multiple_search", "onchange" => "setCimkeKategoria(this.value, '{$cimke_id}')" ), "value", "option", $value );

	}
	
	function getCimkeForm(){
		$cimke_xml = new xmlCimke("cimke.xml", '');
		$allFormGroups = $cimke_xml->getAllFormGroups();
		if($this->_id){
			return html_entity_decode($allFormGroups["maindata"]);
		}else{
			return "&nbsp;";
		}

	}
	
	function mentCimkeKapcsolo(){
		ob_start();
		$termek_id = jrequest::getVar("termek_id", 0);
		$cimkeId = jrequest::getVar("cimkeId", 0);
		$cimke_kapcsolo = jrequest::getVar("cimke_kapcsolo", 0);
		
		switch ($cimke_kapcsolo){
		case 'igen': 
			$o = '';
			$o->termek_id = $termek_id;
			$o->cimke_id = $cimkeId;
			$this->_db->insertObject("#__wh_cimke_kapcsolo", $o);
			break;
		
			
		default:
			
			$o = '';
			$o->termek_id = $termek_id;
			$o->cimke_id = $cimkeId;
			$q = "delete from #__wh_cimke_kapcsolo where termek_id = {$o->termek_id} and cimke_id = {$o->cimke_id}";
			$this->_db->setquery($q);
			$this->_db->query();
		}
		
		
		
		
		//print_r($o);
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret.$this->getCimkeLista();
	}


	
}// class
?>