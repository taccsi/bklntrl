<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');

class modelBase extends JModel
{
	var $tableFields =null;
	var $images = 3;  
	var $uploaded = "components/com_wh/__________________";
	
	var $tmpname = ""; 
  	var $limit = 20;
	var	$from = "";
	var	$fromname = "";
	var $ar_intervallum = array(
		"0-1000" 	 => "0",
		"1001-5000"	 => "0",
		"5000-20000" => "0",
		"20001-100000" => "0",	
		"100001-1000000" => "0",
		"1000001-100000000" => "0",
	);

	var $langCurrencyArr = array(
		"hu-HU" => "HUF",
		"en-GB" => "USD",
		"de-DE" => "EUR",
	); 
	var $CurrencySign = array(
		"HUF" => "Ft",
		"USD" => "$",
		"EUR" => "€",
	);
	var $default_language = "hu-HU";
	var $arrLang = array("termek"=>array("nev","leiras_rovid","leiras"),"kategoria"=>array("nev","leiras"));
	
	function getDijTetelInput( $obj ){
		$ret ="";
		foreach( $obj as $k=>$v ){
			switch($k){
				case "id":
					$ret .= "<input type=\"hidden\" value=\"{$v}\" name=\"dij_{$k}\" class=\"{$k}\" />";
				break;
				case "nev":
					$ret .= jtext::_("DIJTETEL_NEV").": <input class=\"{$k}\" type=\"text\" value=\"{$v}\" name=\"dij_{$k}\" />";
				break;
				case "tol":
					$ret .= jtext::_("ERTEK_TOL_IG").": <input class=\"{$k}\" type=\"text\" value=\"{$v}\" name=\"dij_{$k}\" />";
				break;
				case "ig":
					$ret .= " - <input class=\"{$k}\" type=\"text\" value=\"{$v}\" name=\"dij_{$k}\" />";
				break;
				case "dij":
					$ret .= jtext::_("DIJ").": <input class=\"{$k}\" type=\"text\" value=\"{$v}\" name=\"dij_{$k}\" />";
				break;
			}
		}
		if( $obj->id ){
			$divId="div_tetel{$obj->id}";			
			$input = "";
			$input .= "<input type=\"button\" onclick=\"mentSzallitasiDijtetel('{$divId}');\" value=\"".jtext::_("MENT")."\" >";
			$input .= "<input type=\"button\" onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){torolSzallitasiDijtetel('{$divId}')}\" value=\"".jtext::_("TOROL")."\" >";
			$ret = "<div id=\"{$divId}\" >{$ret}{$input}</div>";
			$sorId = "";
		}else{
			$ret .= $this->getAfaSelect2( "afa_id", 1, "", "" );		
		}
		return $ret."<br />";
	}

	function savePrices( $product_id ){
		$q = "select webshop.nev as webshop_nev, webshop.id as webshop_id from #__wh_webshop as webshop";
		$this->_db->setQuery( $q );
		$rows = $this->_db->loadObjectList();
		foreach($rows as $r){
			$o = $this->getPriceObj( $product_id, $r->webshop_id );
			$o->ar = jrequest::getVar( "productNettoPrice{$product_id}_{$r->webshop_id}" );
			$o->discount_price = jrequest::getVar( "productNettoDiscountPrice{$product_id}_{$r->webshop_id}" );	

			$o->b2b_price = jrequest::getVar( "productB2BNettoPrice{$product_id}_{$r->webshop_id}" );
			$o->b2b_price_discount = jrequest::getVar( "productB2BNettoPriceDiscount{$product_id}_{$r->webshop_id}" );
			 
			$o->afa_id = jrequest::getVar( "afa{$product_id}" );
			unset( $o->afaErtek );
			$this->_db->updateObject("#__wh_ar", $o, "id");
			//print_r($o);
		}
		//die;
	}

	function getPriceObj( $product_id, $webshop_id ){
		$q = "select arT.*, afaT.ertek as afaErtek from #__wh_ar as arT 
		inner join #__wh_afa as afaT on arT.afa_id = afaT.id
		where termek_id = {$product_id} and webshop_id = {$webshop_id} limit 1";
		$this->_db->setQuery( $q );
		$ret = $this->_db->loadObject();
		if( !$ret ){
			$o="";
			$o->termek_id = $product_id;
			$o->webshop_id = $webshop_id;
			$o->afa_id = 1;
			$this->_db->insertObject( "#__wh_ar", $o, "id" );
			$ret = $this->getObj("#__wh_ar", $this->_db->insertId( ) );
		}
		return $ret;
	}

	function getProductPriceList( $product_id = 0 ){
		$ret ="";
		ob_start();
		$product_id = ( $product_id ) ? $product_id : jrequest::getInt( "product_id" );
		$wrapId= "divPriceWrapper{$product_id}";
		
		
		$q = "select webshop.nev as webshop_nev, webshop.id as webshop_id from #__wh_webshop as webshop";
		$this->_db->setQuery( $q );
		$rows = $this->_db->loadObjectList();
		foreach($rows as $r){
			$ind = array_search($r, $rows);
			if( !$ind ){
				$afa_id = $this->getPriceObj( $product_id, $r->webshop_id )->afa_id;
				echo $this->getProductAfaSelect( "afa{$product_id}", $wrapId, $afa_id );				
			}
			
			$pObj = $this->getPriceObj( $product_id, $r->webshop_id );
			$o="";
			$o->HIDDEN1 = $r->webshop_nev;
			$class = "netto";
			$name = "productNettoPrice{$product_id}_{$r->webshop_id}";
			$value = $pObj->ar;
			$o->NETTO_PRICE = "<input name=\"{$name}\" type=\"text\" value=\"{$value}\" class=\"{$class}\" onblur=\"setProductPriceNettoBrutto('{$wrapId}', this )\" />";
			$class = "brutto";
			$value = $pObj->ar * ( $pObj->afaErtek /100 + 1 );
			$o->BRUTTO_PRICE = "<input  type=\"text\" value=\"{$value}\" onblur=\"setProductPriceNettoBrutto('{$wrapId}', this )\" class=\"{$class}\" />";

			$class = "netto";
			$name = "productNettoDiscountPrice{$product_id}_{$r->webshop_id}";
			$value = $pObj->discount_price;
			$o->NETTO_DISCOUNT_PRICE = "<input  name=\"{$name}\" type=\"text\" value=\"{$value}\" class=\"{$class}\" onblur=\"setProductPriceNettoBrutto('{$wrapId}', this )\" />";
			$class = "brutto";
			$value = $pObj->discount_price * ( $pObj->afaErtek /100 +1 );						
			$o->BRUTTO_DISCOUNT_PRICE = "<input type=\"text\" value=\"{$value}\" onblur=\"setProductPriceNettoBrutto('{$wrapId}', this )\" class=\"{$class}\" />";



			$arr[]=$o;
		}
		if( count($arr) ){
			$listazo = new listazo($arr, "", "", "", array(), 1);
			echo $listazo->getLista(); //az arlista lekapcsolva
		}
		//print_r($rows);
			
		$ret->html = ob_get_contents();
		
		ob_end_clean();	
		
		
		if (Jrequest::getvar("controller") == "termekek"){
			$ret_ = "<a href=\"javascript:;\" onclick=\"showHideArazolista('{$wrapId}')\" >[Árazó lista]</a>";
			$ret_ .= "<div id=\"{$wrapId}\" style=\"display: none;\">";
			$ret_ .= $ret->html;
			$ret_ .= "</div>";
			$ret->html = $ret_;
		} else {
			$ret_ = "<div id=\"{$wrapId}\">";
			$ret_ .= $ret->html;
			$ret_ .= "</div>";
			$ret->html = $ret_;
		}
		$ret->error = "";
		return $this->getJsonRet( $ret );
	}

	function getProductAfaSelect( $afaSelectId= "", $wrapId, $value = 1 ){
		$q = "select id as `value`, ertek as `option` from #__wh_afa";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
        return JHTML::_('Select.genericlist', $rows, $afaSelectId, array( "class"=>"afaSelect", "onchange"=>"setProductPriceNettoBrutto('{$wrapId}', this )" ), "value", "option", $value);
	}
 
	function getJsonRet( $obj ){
		$response=array();
		foreach($obj as $k=>$v){
			$response[$k]=$v;
		}
		$json = new Services_JSON();
		return $json->encode( $response );
	}	

	function setCurrency($item){
		$item->currency = $this->langCurrencyArr[ $item->code ];
		return $item;
	}
	
	function getLangForms(){
		$ret = array();	
		foreach( $this->getLanguagesArr() as $a ){
				//die($this->default_language );	
				if($a->lang_code != $this->default_language ){
					$ind = array_search( $a, $this->getLanguagesArr() )+2;
					$o = new stdClass;
					$o->title = jtext::_($a->lang_code);
					$o->type = $a->lang_code;
					$o->tabContent = $this->getLangform( $a->lang_code );
					$ret[]= $o;
				}
			}
		return $ret;
	}
	
	function saveLang(){
		$ret ="";
		$ret->html = "";
		$ret->error = "";
		$ret->debug = "";		
		$productId = jrequest::getVar("productId", "");
		$lang_code = jrequest::getVar("lang_code", "");
		//die(" **********************");
		//print_r($this->arrLang[$this->controller]); die();
		foreach( $this->arrLang[$this->controller] as $a ){
			//$name, $default = null, $hash = 'default', $type = 'none', $mask = 0
			$v = jrequest::getVar( "{$a}_{$lang_code}", "", "", "", 2 );
			//die($v);
			//$v = urldecode( $v );
			$o_ = $this->getLangObj( $this->table, $productId, $a, $lang_code );
			//die($v);
			if( @$o_->id ){
				$o_->value = $v;
				$this->_db->updateObject( "#__wh_lang", $o_, "id" );
				//die;
			}else{
				$o_ = new stdClass();
				$o_->table = $this->table;
				$o_->itemId = $productId;
				$o_->field = $a;
				$o_->lang_code = $lang_code;
				$o_->value = $v;
				$this->_db->insertObject( "#__wh_lang", $o_, "id" );
				//die;
			}
			//$ret->error .= $v."*******\n";
		}
		//print_r( $o_ );
		//die( "- - -" );
		$ret ->error = "";
		return $this->getJsonRet( $ret );
	}


	function initArrLang(){
		if(!isset($this->arrLang)){
			$this->arrLang = $this->xmlParser->getGroupElementNames( "maindata" );
		}
	}

	function getLangObj( $table, $itemId, $field, $code, $origValue="" ){
		$q = "select * from #__wh_lang 
		where `table` = '{$table}'
		and `itemId` = '{$itemId}'
		and `field` = '{$field}'
		and `lang_code` = '{$code}'
		limit 1";
		//die($q);
		$this->_db->setQuery( $q );
		echo $this->_db->getErrorMsg(  );
		if( $ret = $this->_db->loadObject() ){
			//$ret = $this->_db->loadObject();
		}else{
			$ret = "";
			$ret->value = $origValue;
		}
		//echo $this->_db->getQuery( );
		return $ret;
	}
	
	
	function getLangForm( $code = "" ){
		$ret = "";
		$formId = "productForm{$code}"; 
		$ret .= "<fieldset><legend>".jtext::_($this->controller)."</legend>";
		$ret .= "<div id=\"{$formId}\" >";
		//$arr = (isset($this->arrLang)) ? $this->arrLang : $this->initArrLang();
		$arr = $this->arrLang;
		$arr_ = array();
		$productId = $this->xmlParser->getAktVal("id");
		//die( $productId." -----" );
		
		if($productId){
			foreach( $arr[$this->controller] as $a ){
				//print_r($a);
				//die;
				$class = "alapinput";
				$name = $a."_{$code}";
				$o = new stdClass();
				$e = $this->xmlParser->getNode( "name", "{$a}" );
				
				$type = $e->getAttribute( "type" );
				//echo "#__xvs_termek , $productId, $a, $code <br />";
				$o_ = $this->getLangObj( $this->table, $productId, $a, $code );
				@$value = $o_->value;
				//echo " {$type} <br />";
				$o->HIDDEN = jtext::_($e->getAttribute( "label" ) );
				//echo $o->HIDDEN."<br />";
				
				switch( $type ){
					case "editor" : 
						//die($name);
						//echo $name."<br />";						
						
						$editor = JFactory::getEditor();
						
						$o->HIDDEN2 = $editor->display($name, $value, "500", 300, 300, 20, 0 );
						//die($editor->getContent($name)); 
					break;
					case "textarea" : 
					//die($name);
						$o->HIDDEN2 = "<textarea class=\"{$class}\" name=\"{$name}\" id=\"{$name}\" type=\"textarea\" >{$value}</textarea>";
					break;
					case "spec" : 
					//die($name);
					$func = $e->getAttribute("function")."Lang";
					//echo $func;
					if( method_exists( $this, $func ) ){
						$o->HIDDEN2 = $this->$func( $code );
					}
					break;
					default: 
						$o->HIDDEN2 = "<input class=\"{$class}\" name=\"{$name}\" id=\"{$name}\" type=\"text\" value=\"{$value}\"> ";				
				}
				//echo $o->HIDDEN2."<br />";
				$arr_[]=$o;			
			}
			$o = new stdClass();		
			$o->HIDDEN = "&nbsp;";
			$o->HIDDEN2 = "<input class=\"{$class}\" onclick=\"saveLang('{$formId}', '{$code}', '{$this->controller}' )\" id=\"{$name}\" type=\"button\" value=\"".jtext::_("SAVE")."\"> ";
			$arr_[]=$o;
			$lister = new listazo( $arr_, "formLang" );
			$ret .= $lister->getLista();		
			$ret .= "</div>";
			$ret .= "</fieldset>";
		}else{
			$ret .= jtext::_("A FUNKCIO ELERESEHEZ MENTES SZUKSEGES");
		}
		return $ret;
	}	
	function getLanguagesArr(){
		$q = "select * from #__languages where 1 order by ordering";
		$this->_db->setQuery( $q );
		$rows = $this->_db->loadObjectList(  );
		//print_r($rows); die();
		echo  $this->_db->getErrorMsg(  );
		
		//echo  $this->_db->getQuery(  );
		//array_map(array($this, "setCurrency"), $rows );
		return $rows;		
	}
	
	function getTermvarErtekById( $ertek, $id, $arr=array() ){
		foreach( $arr as $a ){
			$vN_ = "mezoid_{$a->id}";
			$$vN_ = "";
		}
		parse_str($ertek);
		$vN_ = "mezoid_{$id}";
		//echo $$vN_;
		return ( isset( $$vN_ ) ) ? $$vN_ : false;
	}

	function getHaszonObjDinamikus( $termek_id, $brutto_ar ){
		$kategoria_id = $this->getObj("#__wh_termek", $termek_id)->kategoria_id;
		$webshopId = implode(",", $this->getWebshopIdArrByKategiriaId($kategoria_id) );
		$arO_a = "";
		$arO_a->brutto_ar = $brutto_ar;

		$q = "select bszar.*, afa.ertek from 
		#__wh_termek_beszallito_ar as bszar
		inner join #__wh_beszallito as bsz on bszar.beszallito_id = bsz.id
		inner join #__wh_afa as afa on bsz.afa_id = afa.id
		where bszar.termek_id = {$termek_id}
		group by bszar.netto_ar having min(netto_ar)
		";
		$this->_db->setQuery($q);
		$arO_b = $this->_db->loadObject();
		//echo $q;

		if($arO_a && $arO_b ){
			$obj = $this->getObj( "#__wh_termek", $termek_id );
			//@$arSzazalek = $this->getSzazalekByAr($arO_a->ar);
			@$koltseg = $this->getKoltsegByKategoriaId($obj->kategoria_id)+	$arO_b->netto_ar*(1+$arO_b->ertek/100);
			//@$koltseg = $arO_b->netto_ar*(1+$arO_b->ertek/100);
			//echo $arO_b->netto_ar*(1+$arO_b->ertek/100)." ------";
			//print_r($arO_b);
			//die;
			@$haszon = $arO_a->brutto_ar-$koltseg;
			//echo $arO_a->ar*(1+$arO_a->ertek/100)." ------";
			@$haszonSzazalek=($arO_a->brutto_ar/$koltseg-1)*100;
			$o="";
			$o->koltseg = $koltseg;
			$o->haszon = $haszon;
			$o->haszonSzazalek = $haszonSzazalek;
			//return 0;
			return $o;
		}else{
			return 0;
		}
	}

	function getHaszonObj($termek_id){

		$kategoria_id = $this->getObj("#__wh_termek", $termek_id)->kategoria_id;
		$webshopId = implode(",", $this->getWebshopIdArrByKategiriaId($kategoria_id) );
		//die($kategoria_id."-------");
		$webshoIdArr = array();
		$q = "select (war.ar*(afa.ertek/100+1) ) as brutto_ar, w.nev as HIDDEN, war.ar as HIDDEN2, war.id as arid, afa.ertek as afaertek 
		from #__wh_webshop as w inner join #__wh_ar as war on w.id = war.webshop_id
		inner join #__wh_afa as afa on war.afa_id = afa.id
		where war.termek_id = {$termek_id} and w.id in ({$webshopId})
		order by brutto_ar asc
		limit 1
		";
		$this->_db->setQuery($q);
		$arO_a = $this->_db->loadObject();

		$q = "select bszar.*, afa.ertek from 
		#__wh_termek_beszallito_ar as bszar
		inner join #__wh_beszallito as bsz on bszar.beszallito_id = bsz.id
		inner join #__wh_afa as afa on bsz.afa_id = afa.id
		where bszar.termek_id = {$termek_id}
		group by bszar.netto_ar having min(netto_ar)
		";
		$this->_db->setQuery($q);
		$arO_b = $this->_db->loadObject();
		

		if($arO_a && $arO_b){
			$obj = $this->getObj("#__wh_termek", $termek_id);
			//@$arSzazalek = $this->getSzazalekByAr($arO_a->ar);
			@$koltseg = $this->getKoltsegByKategoriaId($obj->kategoria_id)+	$arO_b->netto_ar*(1+$arO_b->ertek/100);
			//@$koltseg = $arO_b->netto_ar*(1+$arO_b->ertek/100);
			//echo $arO_b->netto_ar*(1+$arO_b->ertek/100)." ------";
			//print_r($arO_b);
			//die;
			@$haszon = $arO_a->brutto_ar-$koltseg;
			//echo $arO_a->ar*(1+$arO_a->ertek/100)." ------";
			@$haszonSzazalek=($arO_a->brutto_ar/$koltseg-1)*100;
			$o="";
			$o->koltseg = $koltseg;
			$o->haszon = $haszon;
			$o->haszonSzazalek = $haszonSzazalek;
			//return 0;
			return $o;
		}else{
			return 0;
		}
	}
	
	function getSzuloKategoria( $nev = "admin" ){
		$q = "select * from #__wh_kategoria where nev = '{$nev}' and szulo = 0 limit 1 ";
		$this->_db->setQuery( $q );
		return $this->_db->loadObject();
	}
	
	function torolFajlok( ){
		$torolFajlokArr = jrequest::getVar("torolFajlokArr", array() );
		foreach($torolFajlokArr as $id){
			$this->torolFajl( $id );
		}
	}
	
	function mentFajlok( $id ){
		//die($id);   
		foreach ($this->xmlParser->dom->getElementsByTagname('params') as $element ){
			foreach($element->childNodes as $node){
				if( is_a( $node, "DOMElement" ) ){ 
					if( $node->getAttribute("type") == "file" ){
						$name = $node->getAttribute( "name" );
						$kapcsoloNev = $node->getAttribute( "kapcsoloNev" );
						$dir = "media/".$node->getAttribute( "dir" );
						if ( !file_exists( $dir ) ) mkdir($dir,0777);
						//die( $dir );
						$megengedettFajlok = $node->getAttribute( "megengedettFajlok" );
						//echo $name."- -****- -<br />";
						$tmp_name=$_FILES[$name]["tmp_name"];
						if( $tmp_name ){
							//echo rand()."<br />";
							$fajlnev = md5( rand() );
							//print_r($_FILES);
							$arr_ = explode(".", $_FILES[$name]["name"] );
							$ext = end( $arr_ );
							$o="";
							$o->kapcsolo_id = $id;
							$o->kapcsoloNev = $kapcsoloNev;
							$o->xmlNev = $name;
							$o->fajlnev = $fajlnev;
							$o->eredetiNev = $arr_[0];
							$o->ext = $ext;
							$this->torolFajl( $this->letezikFajl( $o->kapcsolo_id, $o->kapcsoloNev, $name )->id );
							$this->_db->insertObject( "#__wh_fajl", $o, "id" );
							$teljesNev = $dir."/".$o->fajlnev.".".$o->ext;
							move_uploaded_file( $tmp_name, $teljesNev );
							chmod( $teljesNev, 0777 );
							//$this->torolFajl( $this->_db->insertId() );							
						}
					}
				}
			}
		}
		//die;
	}
	
	function letezikFajl( $kapcsolo_id, $kapcsoloNev, $xmlNev ){
		$q = "select * from #__wh_fajl where kapcsolo_id = '{$kapcsolo_id}' and kapcsoloNev = '{$kapcsoloNev}' and xmlNev = '{$xmlNev}'";
		$this->_db->setQuery($q);
		return $this->_db->loadObject();
	}

	function torolFajl( $id ){
		if($id){
			$o_ = $this->getObj( "#__wh_fajl", $id );
			$node = $this->xmlParser->getNode("name", $o_->xmlNev);
			$fajlNev = "media/".$node->getAttribute("dir")."/{$o_->fajlnev}.{$o_->ext}";
			unlink($fajlNev);
			$q = "delete from #__wh_fajl where id = '{$id}' ";
			$this->_db->setQuery($q);
			$this->_db->Query($q);
		}
	}
	
	

	function setKiskerAr( $item ){
		$ajaxContentId = "ajaxContentProductPriceList{$item->id}";
		//$this->document->addScriptDeclaration("\$j(document).ready(function(){ getProductPriceList( '{$item->id}', '{$ajaxContentId}' ); })");		
		$o = json_decode( $this->getProductPriceList( $item->id ) );
		$item->kiskerAr = $o->html;
		return $item;
	}
	
	function setKiskerAr__($item){
		//print_r($item);
		//die;
		$arr = array();
		$webshopId = implode(",", $this->getWebshopIdArrByKategiriaId($item->kategoria_id) );
		$webshoIdArr = array();
		$q = "select w.nev as HIDDEN, war.ar as HIDDEN2, war.id as arid, afa.ertek as afaertek 
		from #__wh_webshop as w inner join #__wh_ar as war on w.id = war.webshop_id
		inner join #__wh_afa as afa on war.afa_id = afa.id
		where war.termek_id = {$item->id} and w.id in ({$webshopId})";
		$this->_db->setQuery($q);
		$arr = $this->_db->loadObjectList();
		//echo $this->_db->getErrorMsg();
		//print_r($arr);
		//die;
		$item->kiskerArArrArazas = $this->_db->loadObjectList();		
		//print_r($arr_);
		//print_r($item);
		//die;
		
		if(count($arr)){
			foreach($arr as $a){
				$ind=array_search($a, $arr);
				//print_r($a);
				$afa = $a->afaertek;
				if( is_numeric( @$item->minimumKonkurenciaAr ) ){
					if($a->HIDDEN2 < round($item->minimumKonkurenciaAr/($afa/100+1)) ){
						$class = "class=\"ar_zold\"";
					}elseif($a->HIDDEN2 == round($item->minimumKonkurenciaAr/($afa/100+1)) ){
						$class = "class=\"ar_sarga\"";					
					}elseif($a->HIDDEN2 > round($item->minimumKonkurenciaAr/($afa/100+1)) ){
						$class = "class=\"ar_piros\"";
					}
				}else{
					$class = "";
				}
				//unset($arr[$ind]->afaertek);
				//$js = "onblur=\"szamol\"";
				
				$arr[$ind]->HIDDEN2 = "<span {$class} >".$this->getNettoBruttoInput("ar", "bruttoar", $a->HIDDEN2, $a->afaertek, "{$a->arid}", "[]", "<br>", "", "{$item->id}" )."</span>";
				$arr[$ind]->HIDDEN2.="<input type=\"hidden\" name=\"arid[]\" value=\"{}\" />";
				$q = "select (arT.ar_atadasi * (1+afaT.ertek / 100) ) as brutto_atadasi_ar, 
				arT.ar_atadasi as netto_atadasi_ar from #__wh_ar as arT 
				inner join #__wh_afa as afaT on arT.afa_id_atadasi = afaT.id
				where arT.id = {$a->arid}";
				$this->_db->setQuery($q);
				$atArO = $this->_db->loadObject();
				/*
				$arr[$ind]->HIDDEN3="";
				@$arr[$ind]->HIDDEN3.=jtext::_("NETTO_ATADASI_AR").": ".ar::_( $atArO->netto_atadasi_ar )."<br />";
				@$arr[$ind]->HIDDEN3.=jtext::_("BRUTTO_ATADASI_AR").": ".ar::_( $atArO->brutto_atadasi_ar );				
				*/
					unset($arr[$ind]->afaertek);
				}
			$listazo = new listazo( $arr );
			$item->kiskerAr = $listazo->getLista();
		}else{
			$item->kiskerAr = "";		
		}
		return $item;
	}

	function getSearchTemplate($i=""){
		ob_start();
		switch($i){
			case "kategoria" :
			?>
            <table class="table_search1" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                <table>
                    <tr>
                      <td class="td_search">{0}</td>
                      <td class="td_search">{1}</td>
                    </tr>
                </table>
                </td>
              </tr>
            </table>
			<?php
		break;
		case "kimutatas" :
			?>
<table class="table_search1" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table>
        <tr>
          <td class="td_search">{0}
            <div class="clr"></div></td>
          <td class="td_search">{1}
            <div class="clr"></div></td>
          <td class="td_search">{2}
            <div class="clr"></div></td>
          <td class="td_search">{3}
            <div class="clr"></div></td>
          <td rowspan="2" class="td_serach_sub" colspan="3"><a class="btn_search_big" onclick="document.getElementById('adminForm').submit();return false;" href="#"><?php echo JText::_('KERES'); ?> </a></td>
        </tr>
        <tr>
          <td class="td_search">{7}
            <div class="clr"></div></td>
          <td class="td_search">{8}
            <div class="clr"></div></td>
          <td class="td_search">{9}
            <div class="clr"></div></td>
          <td class="td_search">{10}
            <div class="clr"></div></td>
          <td class="td_search">{11}
            <div class="clr"></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php
		break;
			
		case 'felhasznalok' : 
			?>
<table class="table_search1" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table>
        <tr>
          <td class="td_search">{0}</td>
          <td class="td_search">{1}</td>
          <td class="td_search">{2}</td>
          <td class="td_search">{3}</td>
          <td class="td_serach_sub" ><a class="btn_search_big" onclick="$('adminForm').submit()" href="#"><?php echo JText::_('KERES'); ?> </a></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php
		break;
		case 'HaszonSearchArr' :		
			?>
<table class="table_search1" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table>
        <tr>
          <td class="td_search inputok">{0}</td>
          <td class="td_search ">{1}</td>
          <td class="td_search">{2}</td>
          <td class="td_serach_sub" ><a class="btn_search_big" onclick="$('cond_sikeres_haszon').value='1';$('adminForm').submit()" href="#"><?php echo JText::_('KERES'); ?> </a></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php
		break;		
		case 'tetelek' :
			?>
<table class="table_search1 table_tetelsearch" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table>
        <tr>
          <td class="td_search inputok"><div style="margin-bottom:5px;">{0}</div>
            <div style="margin-bottom:5px;">{3}</div>
            <div style="margin-bottom:5px;">{5}</div>
            <div class="clr"></div></td>
          <td class="td_search inputok"><div style="margin-bottom:5px;">{1}</div>
            <div style="margin-bottom:5px;">{2}</div></td>
          <td class="td_search"><div class="clr"></div></td>
          <td class="td_search inputok"><div style="margin-bottom:5px; margin-right:5px;">{4}</div>
            <div style="margin-bottom:5px; margin-right:5px;">{6}</div></td>
          <td class="td_search"><div class="clr"></div></td>
          <td class="td_search">{7}
            <div class="clr"></div></td>
          <td rowspan="2" class="td_serach_sub" colspan="3"><a class="btn_search_big" onclick="document.getElementById('adminForm').submit();return false;" href="#"><?php echo JText::_('KERES'); ?> </a></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php
		break;
		
		case 'rendelesek':
			?>
<div class="search1">
  <div class="search_box">
    <div class="inside">
      <div class="div_input">{0}</div>
      <div class="div_input">{1}</div>
      <div class="div_input">{2}</div>
      <div class="div_input">{3}</div>
      <div class="clr"></div>
    </div>
    <div class="inside">
      <div class="div_input">{4}</div>
      <div class="div_input">{5}</div>
      <div class="div_input">{6}</div>
      <div class="div_input">{7}</div>
      <div class="clr"></div>
    </div>
    <div class="inside">
      <div class="div_input">{8}</div>
      <div class="div_input">{9}</div>
      <div class="div_input">{10}</div>
      <div class="clr"></div>
    </div>
    <div class="inside">
      <div class="div_input">{12}</div>
      <div class="div_input">{13}</div>
      <div class="div_input">{14}</div>
      <div class="clr"></div>
    </div>
  </div>
  <div class="div_serach_sub" colspan="3">
    <div class="div_serach_submit"> <a class="btn_search_big" onclick="document.getElementById('adminForm').submit();return false;" href="#"><?php echo JText::_('KERES'); ?> </a> </div>
  </div>
  <div class="clr"></div>
</div>
<?php
		break;
		
		case 1 :
			?>
<table class="table_search1" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table>
        <tr>
          <td class="td_search">{0}
            <div class="clr"></div></td>
          <td class="td_search">{1}
            <div class="clr"></div>
          <td class="td_search">{2}
            <div class="clr"></div></td>
          <td class="td_search">{3}
            <div class="clr"></div></td>
          <td class="td_search">{4}
            <div class="clr"></div></td>
          <td class="td_search">{5}
            <div class="clr"></div></td>
          <td class="td_search">{6}{7}{8}{9}{10}{11}{12}
            <div class="clr"></div></td>
          <td rowspan="2" class="td_serach_sub" colspan="3"><a class="btn_search_big" onclick="document.getElementById('adminForm').submit();return false;" href="#"><?php echo JText::_('KERES'); ?> </a></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php
		break;
		case 2 :
			?>
<table class="table_search1" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table>
        <tr>
          <td class="td_search">{0}</td>
          <td class="td_serach_sub">{start}</td>
          <td class="td_serach_sub">{stop}</td>
        </tr>
      </table></td>
  </tr>
</table>
<?php
		break;
		default:
			?>
<table class="table_search1" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table>
        <tr>
          <td class="td_search">{0}</td>
          <td class="td_search">{1}</td>
           <td class="td_search">{2}</td>
          <td class="td_serach_sub" colspan="3"><a class="btn_search_big" onclick="document.getElementById('adminForm').submit();return false;" href="#"> <?php echo JText::_('KERES'); ?> </a></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php
		}
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	}

	function getCond(){
		$cond ="";
		$group = $this->xmlParser->getGroup("condFields" );
		$mezok = array();
		$db = JFactory::getDBO();
		foreach ( $group->childNodes as $element ){
			if(is_a($element, "DOMElement") ){
				$field= $element->getAttribute('name');
				$q= $element->getAttribute('q');
				$val = JRequest::getVar($field, "");
				//echo $field."-----{$val}";
				//$val = $this->getSessionVar($field);
				//$val=$db->getEscaped($val);
				//print_r($val);

				if( $val ){
					switch($field){
						case "cond_termeknev" :
							$cond .= " t.id in (".$this->getTermNevCikkszam($val).") and ";
							//die($cond);
							//$cond .= " ( t.nev like '%{$val}%' or t.cikkszam like '%{$val}%' ) and ";
						break;
						case "cond_kategoria_tipus" :
						break;
						case "cond_ev" :
							$cond .= "year( {$q} ) = {$val} and ";
							//echo $cond;
						break; 
						case "cond_spec2":
							//print_r($val);
							if(in_array(8,$val)){
								$cond .= "t.megvasarolhato = 'igen' and ";
							}
							if(in_array(9,$val)){
								$cond .= "t.megvasarolhato <> 'igen' and ";
							}

							if(in_array(1,$val)){
								$cond .= "t.aktiv = 'igen' and ";
							}
							if(in_array(2,$val)){
								$cond .= "t.aktiv = 'nem' and ";
							}
							if(in_array(3,$val)){
								$q = "select id from #__wh_termek as termek where xmlbol = 'igen' ";
								$this->_db->setQuery($q);
								$rows = $this->_db->loadResultArray( );
								$rows[]=0;
								$idk = $cond .= "t.id in (".implode(", ", $rows).") and ";
							}
							
							if(in_array(4,$val)){//
								$q = "select id from #__wh_termek as termek where xmlbol <> 'igen' ";
								$this->_db->setQuery($q);
								$rows = $this->_db->loadResultArray( );
								$rows[]=0;
								$idk = $cond .= "t.id in (".implode(", ", $rows).") and ";
							}

							if( in_array( 5, $val ) ){//Cikkszám egyezések
								$q = "select cikkszam 
								from #__wh_termek as termek 
								where cikkszam <> ''
								group by cikkszam having count(cikkszam) > 1 ";
								$this->_db->setQuery($q);
								$rows = $this->_db->loadResultArray( );
								$rows[]=' ';
								$q = "select id from #__wh_termek as termek where id in ('".implode("','",$rows)."') ";
								$this->_db->setQuery($q);
								$tIdArr = $this->_db->loadResultArray( );

								$q = "select cikkszam 
								from #__wh_termekvariacio as tv
								where cikkszam <> ''
								group by cikkszam having count( cikkszam ) > 1 ";
								
								$this->_db->setQuery($q);
								$rows = $this->_db->loadResultArray( );
								$rows[]=' ';
								$q = "select termek_id from #__wh_termekvariacio as tv where cikkszam in ('".implode("','",$rows)."') ";
								$this->_db->setQuery($q);
								$tIdArr2 = $this->_db->loadResultArray( );
								
								
								$q = "select * from #__wh_termekvariacio as termekvariacio";
								$this->_db->setQuery($q);
								$rows = $this->_db->loadObjectList( );
								//echo $this->_db->getQuery( );
								echo $this->_db->getErrorMsg( );	
								$tIdArr3 = array();	
								foreach($rows as $r){
									$q = "select id from #__wh_termek as termek where cikkszam='{$r->cikkszam}' and id <> '{$r->termek_id}' ";
									$this->_db->setQuery($q);
									$tid_ = $this->_db->loadResult( );
									if($tid_){
										$tIdArr3[]= $tid_;
										$tIdArr3[]= $r->termek_id;
									}
								}
								$tIdArr = array_merge($tIdArr, $tIdArr2);
								$tIdArr = array_merge($tIdArr, $tIdArr3);								
								//print_r( $tIdArr2 );
								$cond .= "t.id in (".implode(", ", $tIdArr).") and ";
								//echo $cond;
							}
							//die($cond);
							break;
						
						case "cond_varos" :
						$cond .= "(felhasznalo.varos like '%{$val}%' or felhasznalo.sz_varos like '%{$val}%')  and ";
						break;
						case "cond_statusz_beszerzes" :
						case "cond_statusz_beerkezett" :
						case "cond_beszallito_fizetve" :
						case "cond_megrendelo_fizetve" :
							foreach ($val as $v) {
								if ($v == 'nincs') {
									//die('hello');
									$cond .= "{$q} = '0000-00-00 00:00:00' and ";
								} else {
									$cond .= "{$q} != '0000-00-00 00:00:00' and ";
								}	
							}
						break;
						case "cond_kampany_id":
							//cond_kampany_id
							//$this->getObj("#__wh_webshop", $val);
							//echo $val;
							//print_r($this->getObj("#__wh_webshop", $this->getObj("#__wh_kampany", $val)->webshop_id  )); die();
							//$kampanyTabla = $this->getObj("#__wh_webshop", $this->getObj("#__wh_kampany", $val)->webshop_id  )->kampanytabla;
							//die("{$kampanyTabla}");
							$q = "select termek.id from #__wh_termek as termek 
							inner join #__wh_kampany_kapcsolo as kampanytabla on termek.id = kampanytabla.termek_id
							where kampanytabla.kampany_id = {$val}
							";
							$this->_db->setQuery($q);
							$termekIdArr = $this->_db->loadResultArray();
							$termekIdArr = ($termekIdArr) ? implode( "," , $termekIdArr ) : "0" ;
							$cond .= "t.id in ( {$termekIdArr} ) and ";
							//die($cond);
						break;

						case "cond_sikeres_haszon" : 
							//$cond .= "rendeles.allapot = 'SIKERES_RENDELES' and ";
							break;
						
						case "cond_spec_kimutatas" : 
							foreach($this->specArr as $a){
								if($val == $a){ $cond .= "rendeles.allapot = '{$a}' and "; break;}
							}
							break;
						case "cond_honap_haszon" :
							$cond .= "month( {$q} ) = {$val} and ";						
						break;
						case "cond_honap" : 
							if( !in_array(jrequest::getvar("cond_spec_kimutatas",""), $this->specArr ) ){
								$cond .= "month( {$q} ) = {$val} and ";							
							}else{
								$cond .= "month( rendeles.allapotvaltozas_datum ) = {$val} and ";									
							}
						break;
						case "cond_megrendeles_tol" :
						case "cond_kiszallitas_tol" :$cond .= "{$q} >= '{$val} 00:00:00' and ";  break;
						case "cond_megrendeles_ig" :
						case "cond_kiszallitas_ig" : $cond .= "{$q} <= '{$val} 00:00:00' and ";  break;
						case "cond_vasarlo": 
							
							$txt =" ( "	;						
							$vasarlok = $this->getVasarloFromName($val);
							
							if (count($vasarlok)){
									
								foreach ($vasarlok as $vasarlo) {
									
									$txt .= "(r.user_id = {$vasarlo -> felhasznalo -> user_id} and r.webshop_id = {$vasarlo -> felhasznalo -> webshop_id}) or ";
								}
								$txt .= substr($txt, 0, strlen($txt)-3);
								$txt .= " )) and ";
								$cond .= $txt;
							} else { $cond.= " r.id is null and "; }
							//echo $cond;
						
						break;
						
						case "cond_allapot_beszerzes":
						case "cond_allapot_fizetve":
						case "cond_limitstart":
							break;						
						case "cond_specialis_szures":
							$cond .= $this->getSpecialisSzuresCond($val); break; 
						case "cond_szallitas_admin":
						case "cond_atvhely_id" :
						case "webshop_id" :
						case "cond_gyarto_id" : $cond .= "{$q} = '{$val}' and "; 
						//echo $cond;
						break;
						case "cond_kategoria_id": 
							//die("id: {$val}");
							if ($val != 'tiltva'){
								$kArr = implode(",", $this->getLftRgtOsszes($val, "#__wh_kategoria" ));
								$cond .= "{$q} in ({$kArr}) and "; 
							}
							break;
						case "cond_allapot":
						case "cond_kiszallito_id":
						case "cond_beszallito_id":
							$cond .= "{$q} = '{$val}' and "; break;
						case "datum": $cond .= "DATE_FORMAT( datum, '%Y') = {$val} and "; break;
						default : $cond .= "{$q} like '%{$val}%' and ";
					}
				}
			} 
		//$element->textContent;
		}
		//echo $cond."<br />";
		//die;
		if($cond){
			$cond = "where ".substr($cond, 0, strlen($cond)-4);
		}

      //echo $cond;
      return $cond;
   }
	function getTermNevCikkszam($val){
		$q = "select t.id from #__wh_termek as t left join #__wh_termekvariacio as tv on t.id = tv.termek_id where t.nev like '%{$val}%' or t.cikkszam like '%{$val}%' or tv.cikkszam like '%{$val}%'";
		$this->_db->setquery($q);
		//echo $this->_db->getquery().' - Taccsi query <br />';
		$arr = $this->_db->loadresultarray();
		$arr[] = 0;
		$idk = implode(',',$arr);
		return $idk;
	}
	function getTermekIdVanKiskerAr(){
		/*
		($v) ? $op = ">" : $op = " = ";
		$q = "select t.id from #__wh_termek as t right join #__wh_ar as a on t.id = a.termek_id
		where a.ar {$op} 0
		";
		*/
		$q = "select termek_id from #__wh_ar as arT
		where arT.ar > 0 ";		
		//echo $q;
		$this->_db->setQuery($q);
		$arr = $this->_db->loadResultArray();
		//print_r($arr);
		echo $this->_db->getErrorMsg();
		return implode(",", $arr );
	}

	function cleanTomb($arr){
		$ret = array();
		foreach( (array)$arr as $a){
			if(trim($a)){
				$ret[]=trim($a);
			}
		}
		return $ret;
	}	
	
	function getSearchCheckboxes($name, $arr, $value){
		ob_start();
		foreach ($arr as $a) {
			$i = array_search($a, $arr);
			//print_r($value);
			( $this->benneVan($value, $a) ) ? $checked = "checked=\"checked\"" : $checked = "";
				$class = "class=\"kereso_check sCheck_{$i}\"";
				echo "<div class=\"checker\"><input {$class} type=\"checkbox\"  name=\"{$name}[]\" value=\"{$a->value}\" {$checked} /><span class=\"label\">{$a->option}</span></div>";
			}
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function benneVan($value, $a){
		//print_r($value);
		//print_r($arr);
		//die;
		foreach($value as $v){
			if($a->value == $v ) return true;
		}
		return false;
	}
	
	function getSearchTextBox($name,$value, $label){
		ob_start();
		
				echo "<div class=\"textbox\"><input class=\"input\" type=\"input\" id=\"{$name}\" name=\"{$name}\" value=\"{$value}\" /></div>";

		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
		
	function getNettoBruttoInput($nettoName, $bruttoName, $netto_ar, $afa, $id, $name_ext = "[]", $br="</br>", $jsPar="", $termek_id=0){
		ob_start();		
		$N = "{$nettoName}{$name_ext}";
		$B = "{$bruttoName}{$name_ext}";
		$idN = "{$nettoName}{$id}";
		$idB = "{$bruttoName}{$id}";		
		$js = "onblur=\"arNettoBrutto('{$idN}', '{$idB}', {$afa}, 'nettoBol', '{$termek_id}' )\"";
		?>
        <?php echo jtext::_("NETTO_AR").$br; ?>
        <?php echo "<input {$jsPar} name=\"{$N}\" {$js} id=\"{$idN}\" type=\"text\" value=\"{$netto_ar}\" >{$br}"; ?>
        <?php echo jtext::_("BRUTTO_AR").$br; ?>
		<?php $js = "onblur=\"arNettoBrutto('{$idN}', '{$idB}', {$afa}, 'bruttoBol', '{$termek_id}' )\"";?>
        <?php 
		$brutto_ar = $netto_ar*($afa /100 +1);
		echo "<input {$jsPar} type=\"text\" id=\"{$idB}\" name=\"{$B}\" {$js} value=\"{$brutto_ar}\" />{$br}" ?>
        <?php
		echo jtext::_("AFA").": {$afa}";
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;		
	}
	
	function getNettoBruttoInput_($nettoName, $bruttoName, $netto_ar, $afa, $id, $name_ext = "[]", $br="" ){
		ob_start();	
		$br = "";	
		$N = "{$nettoName}{$name_ext}";
		$B = "{$bruttoName}{$name_ext}";
		$idN = "{$nettoName}{$id}";
		$idB = "{$bruttoName}{$id}";		
		$js = "onblur=\"arNettoBrutto('{$idN}', '{$idB}', {$afa}, 'nettoBol' )\"";
		?>
<table class="table_nettobrutto">
  <tr>
    <td><?php echo jtext::_("NETTO_AR"); ?></td>
    <td><?php echo "<input name=\"{$N}\" {$js} id=\"{$idN}\" type=\"text\" value=\"{$netto_ar}\" ><br />"; ?></td>
    <?php 
		$js = "onblur=\"arNettoBrutto('{$idN}', '{$idB}', {$afa}, 'bruttoBol' )\"";
		$brutto_ar = $netto_ar*($afa /100 +1);
		?>
  <tr>
    <td><?php echo jtext::_("BRUTTO_AR"); ?></td>
    <td><?php echo " <input type=\"text\" id=\"{$idB}\" name=\"{$B}\" {$js} value=\"{$brutto_ar}\" /><br />" ?></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo jtext::_("AFA").": {$afa}%"; ?></td>
  </tr>
</table>
<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;		
	}

	
	

	function getBeallitas(){
		$this->_db->setQuery("select * from #__wh_beallitas where id = 1");
		$obj = $this->_db->loadObject();
		@$arr = unserialize($obj->webshop_kat);
		if(count($arr) && is_array($arr) ){
			foreach($arr as $webshop_id => $kakegoria_idTxt){
				$ind = array_search( $webshop_id, $arr);
				$arr_=array();
				foreach(explode(",", $kakegoria_idTxt) as $kategoria_id){
					$katids = $this->getLftRgtOsszes($kategoria_id, "#__wh_kategoria", "id");
					@$arr_ = array_merge($arr_, $katids);
				}
				$arr[$webshop_id] = $arr_;
			}
				  }
		$obj->webshop_kat = $arr;
		@(array)$arr = unserialize($obj->szazalek_kat);
		if(count($arr) && is_array($arr)){
			foreach(@$arr as $koltseg => $kakegoria_idTxt){
				$ind = array_search( $koltseg, $arr);
				$arr_=array();
				foreach(explode(",", $kakegoria_idTxt) as $kategoria_id){
					$katids = $this->getLftRgtOsszes($kategoria_id, "#__wh_kategoria", "id");
					@$arr_ = array_merge($arr_, $katids);
				}
				$arr[$koltseg] = $arr_;
			}
		}
		$obj->koltseg_kat = $arr;
		//print_r($obj);
		//die;
		return $obj;
	}	

	function getWebshopIdArrByKategiriaId($kategoria_id){
		$arr = array(0);
		foreach($this->beallitas->webshop_kat as $webshop_id => $kategoria_idArr){
			if(in_array($kategoria_id, (array)$kategoria_idArr) ) {
				$arr[] = $webshop_id;
			}
		}
		return $arr;
	}
	function setKonkurenciaAr($item){
		return $item;
	}
	
	function setKonkurenciaAr__($item){
		$arr = array();
		$arrMin=array();
		foreach($this->webContent->konkurenciaArr as $k => $func){
			$o="";
			$arO = @$this->webContent->getMentettAr( $item->id, $k );
			if(@$arO->ar){
				$o->HIDDEN = $this->getKonkurenciaArakBuborek($k, $arO);
				$o->HIDDEN2 = @ar::_($arO->ar);
				$arrMin[]=$arO->ar;
			}else{
				$o->HIDDEN = $k;
				$o->HIDDEN2 = jtext::_("NINCS ADAT");
			}
			$arr[] = $o;
		}
		if(count($arr)){
			$listazo = new listazo($arr, "egyszeru", "", "", array(), 2);
			$item->konkurenciaArak = $listazo->getLista();
			sort($arrMin);
			//print_r($arrMin);
			//die;
			
			@$item->minimumKonkurenciaAr = $arrMin[0];
		}else{
			$item->konkurenciaArak = "";
			$item->minimumKonkurenciaAr = "";
		}
		return $item;
	}

	function getParamValue($paramTxt, $mezo_id){
		$minta = "/###{$mezo_id}===.*?###/";
		preg_match_all($minta, $paramTxt, $matches);
		//print_r($matches);
		@$ret = trim(str_replace(array("###{$mezo_id}===", "###"), "", $matches[0][0]));
		//die($ret."***");
		return $ret;
	}
	
	function __construct(){
		$this->document = jfactory::getDocument();
		$this->base_template = new base_template;
		$this->webContent = new webContent;
		$this->_db = JFactory::getDBO();
		$this->user=JFactory::getUser();
		$this->controller=jrequest::getvar('controller');
		$tmp = array_keys( $this->user->groups );
		$usertype = $tmp[0];
		$this->user->usertype = $usertype;
		//print_r($this->user );
		//die;
		$this->beallitas = $this->getBeallitas();
		$this->user->jog = $this->getJog();
		
		//print_r($this->user->jog->kategoriak);
		//die;
		$array = JRequest::getVar('cid',  0, '', 'array');
		//print_r($array[0]); exit;
		$this->setId((int)$array[0]);
		parent::__construct();
		$this->setMandatoryFields();
	}//function

	function setVasarlo($item){
		$v = $this->getVasarlo($item->user_id,$item->webshop_id);
		if($v){
			$item->vasarlo = "<a href=\"{$link}\">{$v->vasarlo->user->name}<br />tel.: {$v->vasarlo->felhasznalo->telefon}</a>";		}else{
			parse_str($item->szamlazasi_cim);
			//&SZAMLAZASI_NEV=TESZT&IRANYITOSZAM=1234&VAROS=TESZT&UTCA=TESZT			
			//die( $item->szamlazasi_cim );
			$item->vasarlo ="";
			$item->vasarlo .= jtext::_("SZAMLAZASI_NEV").": {$SZAMLAZASI_NEV}<br />";
			$item->vasarlo .= jtext::_("IRANYITOSZAM").": {$IRANYITOSZAM}<br />";
			$item->vasarlo .= jtext::_("VAROS").": {$VAROS}<br />";
			$item->vasarlo .= jtext::_("UTCA").": {$UTCA}<br />";
			$item->vasarlo .= jtext::_("TELEFON").": {$TELEFON}<br />";			
			$item->vasarlo .= jtext::_("EMAIL").": {$EMAIL}<br />";				
		}
		
		return $item;
	}
	
	function setFelhasznalo($item){
		$v = $this->getVasarlo($item->user_id, $item->webshop_id);
		if($v){
			foreach($v->user as $n=>$e){
				$item->adatok->$n = $e;
			}
			foreach($v->felhasznalo as $n=>$e){
				$item->adatok->$n = $e;
			}
		}else{
			$item->adatok = jtext::_("NEM_REGISZTRALT_VASARLO");
		}
		return $item;
	}
	
	function getVasarlo($user_id, $webshop_id){
		if( $user_id ){
			$webshop = $this->getObj("#__wh_webshop", $webshop_id );
			$db = dbConnect::getDb($webshop->host_, $webshop->user_, $webshop->jelszo_, $webshop->database_, $webshop->prefix_);
			//print_r($webshop);
			//print_r($db);
			if( !$db->get("code") ){
				$q = "select * from #__users where id = {$user_id}";
				$db->setQuery($q);
				$o="";
				$o->user = $db->loadObject();
				@$q = "select * from #__wh_felhasznalo where user_id = {$o->user->id}";
				$this->_db->setQuery($q);
				$o->felhasznalo = $this->_db->loadObject();
			}else{
				$o->user = "";
				$o->felhasznalo = "";
			}
			return $o;
		}else{
			return false;
		}
	}
	
	function getVasarloFromName($vasarlo){
		//print_r($vasarlo); die();
		
		$q = "select id from #__wh_webshop";
		$this->_db->setQuery($q);
		$whpk = $this->_db->loadObjectList();
		$o = '';
		
		 
		foreach ($whpk as $whp) {
			
			$webshop = $this->getObj("#__wh_webshop", $whp->id );
			
			
			$db = dbConnect::getDb($webshop->host_, $webshop->user_, $webshop->jelszo_, $webshop->database_, $webshop->prefix_);
			
			$q = "select * from #__users where name like '%{$vasarlo}%' ";
			//echo('<br>');echo('<br>');echo('<br>');echo($vasarlo); echo('<br>');
			
			$db->setQuery($q);
			//echo $db->getQuery();echo('<br>');
			//echo $db->geterrormsg();echo('<br>');echo('<br>');
			
			$rows = $db->loadObjectList();
			
			//print_r($rows);echo('<br>'); die();
			if (count($rows)){
				$i = 0;
				foreach ($rows as $row) {
					//print_r($row);
					$q = "select * from #__wh_felhasznalo where user_id = {$row->id}";
					$this->_db->setQuery($q);
			//		if (count($this->_db->loadObject())){
						$o[$i]->user = $row;
						$o[$i]->felhasznalo = $this->_db->loadObject();
				//	}
					$i++;
				}
			}
		}
	
//	print_r($o); die();
		
		//print_r($o); die();
		return $o;
	}
	
		
	function getSzazalekByAr($termek_ar){
		foreach($this->ar_intervallum as $kulcs => $ertek){
			$intervallum = explode("-",$kulcs);
			if($termek_ar>$intervallum[0] && $termek_ar<$intervallum[1]){
				return $ertek;
			}
		}
	}

	function getKoltsegByKategoriaId($kategoria_id){
		foreach($this->beallitas->koltseg_kat as $koltseg => $kategoriaIdArr){
			if(in_array($kategoria_id, $kategoriaIdArr) ){
				return $koltseg;
			}
		}
		return 0;
	}
	function minimalisBeszallitoAr(){
		$besz_ar_tomb = JRequest::getVar("beszallito_netto_ar","");
		return min($besz_ar_tomb);
	}
	
	function ellenorizAr($name){
		if(JRequest::getVar("beszallito_netto_ar","")){

		$besz_ar_tomb = array();
		$webshopId = JRequest::getVar("webshopId","");
		$ar_tomb = array();
		$ar_tomb = JRequest::getVar("ar","");
		$besz_ar_tomb = JRequest::getVar("beszallito_netto_ar","");
		$min_besz_ar = $this->minimalisBeszallitoAr();

		$vizsgalando_szazalek = $this->getKoltsegByKategoriaId(JRequest::getVar("kategoria_id",""));

		$ar_feigyelmezteto = array();
		$db=0;
		foreach($ar_tomb as $ar){
			$webshopId_ = $webshopId[$db];
			$db++;
			$ertek = $this->getKoltsegByKategoriaId($ar);
			if((abs($min_besz_ar-$ar)/$min_besz_ar*100)<($ertek+$vizsgalando_szazalek) || $ar < $min_besz_ar){
				$o = "";
				$o -> WS_ID = $webshopId_;
				$o -> AR = $ar;
				$o -> JAVASOLT_AR = $this->getJavasoltAr($min_besz_ar, $ertek+$vizsgalando_szazalek);
				$ar_feigyelmezteto[] = $o;
			}
		}
		$obj = "";
		$obj -> SZAZALEK = $ertek + $vizsgalando_szazalek;
		$obj -> MIN_BESZ_AR = $min_besz_ar;
		$ertekek[] = $obj;
		
		$this->setSessionVar("ar_feigyelmezteto", $ar_feigyelmezteto);
		$this->setSessionVar("ertekek", $ertekek);
		$this->setSessionVar("ar_tomb", $ar_tomb);
		$this->setSessionVar("nincs_beszallito", "");
		if(count($ar_feigyelmezteto)){
			return 0;
		}else{
			return 1;		
		}
		}
		else{
			$this->setSessionVar("ar_feigyelmezteto", "");
			$this->setSessionVar("ar_tomb", "");
			$this->setSessionVar("nincs_beszallito", JText::_("NINCS BESZALLITO"));
			return 1;
		}
	}
	
	function getJavasoltAr($min_besz_ar=0, $szazalek=0){
		if($min_besz_ar!=0 && $szazalek!=0){
			return floor(($min_besz_ar*(($szazalek)/100))+$min_besz_ar+1);}
	}
	
	function setSessionVar($var, $value){
		@$sess =& JSession::getInstance();
		$sess->set( $var, $value );
	}
	
	function termekekCsopArazasa(){
		$arid=JRequest::getVar("arid","");
		$ar=JRequest::getVar("ar","");
		foreach($arid as $id){
			$ind = array_search($id, $arid);
			$q = "update #__wh_ar set ar = '{$ar[$ind]}' where id = {$id}";
			$this->_db->setQuery($q);
			$this->_db->Query();
		}
	}

	function getKonkurenciaArakBuborek($k, $arO){
	  @$arak = (array)unserialize($arO->arak);
	  $arr_ = array();
	  if(count($arak)){
		  $ind = 1;
		  foreach($arak as $ar_){
			  $o_ = "";
			  $o_->SORSZAM = $ind++;
			  $o_->AR = @ar::_($ar_);
			  $arr_[] = $o_;
		  }
		  $listazo = new listazo($arr_);
		  $arakLista = $listazo->getLista();
		  return  "<span class=\"zoomTip\" title='{$arakLista}'>{$k}</span>";
	  }else{
		  return $k;
	  }
	}

	function setElsokep($item){
		$q = "select id from #__wh_kep where termek_id = {$item->id} and listakep = 'igen' order by id limit 1";
		$this->_db->setquery($q);
		$kep_id = $this->_db->loadresult();
		if (!isset($kep_id)){
			$q = "select id from #__wh_kep where termek_id = {$item->id} order by sorrend asc limit 1";
			$this->_db->setquery($q);
			$kep_id = $this->_db->loadresult();
		}
		@$src = "{$this->uploaded}{$kep_id}.jpg";
		//echo $src; 
		if( is_file( $src ) && $kep_id ){
			$forras_kep = $src;
			$w = 50; $h = 50; $mode = "resize"; $link="javascript:void(0)"; $class=""; $buborek_kep=""; $alt=$item->nev;
			$cel_kep = "{$this->base_template->dir_cel}listakep_{$kep_id}_{$w}_{$h}_{$mode}.jpg";
			$item->elsokep = $this->base_template->image($forras_kep, $cel_kep, $w, $h, $mode, $link, $class, $buborek_kep="", $alt);
		}else{
			$item->elsokep = "";
		}
		return $item;		
	}

	function getSearchArr(){
		$arr = array();
		$obj = "";
		$obj->NEV = '<input name="cond_nev" id="cond_nev" value="'.JRequest::getVar("cond_nev") .'" />';
		$arr[] = $obj;
		$obj = "";		
		return 	$arr;
	}

	function getSearch($serachTemplate="", $funcArr = "getSearchArr"){
		global $Itemid;
		$arr = $this->$funcArr();
		//print_r($arr); //die();
		ob_start();
		echo ($serachTemplate) ? $serachTemplate=$this->getSearchTemplate($serachTemplate) : $serachTemplate=$this->getSearchTemplate();
		//if( jrequest::getvar("limitstart") ){
			$limitStart = jrequest::getvar("limitstart", $this->getSessionVar("cond_limitstart") );
		//}else{
			//$limitStart=0;
		//}	
		$this->setSessionVar("cond_limitstart", $limitStart );
		?>
<input type="hidden" name="orderField" id="orderField" value="" />
<input type="hidden" name="Itemid" id="" value="<?php echo $Itemid ?>" />
<input type="hidden" name="cond_limitstart" id="cond_limitstart" value="<?php echo $limitStart ?>" />
<?php
		echo $this->xmlParser->getOrderHiddenFields();	
		$ret = ob_get_contents();
		ob_end_clean();
		foreach($arr as $a){
			$ind = array_search($a, $arr);
			foreach($a as $oszlnev => $ertek){
				$e = "<span class=\"search_nev\">".JText::_("$oszlnev")."</span>{$ertek}";
				$ret = str_replace("{{$ind}}",$e,$ret);
			}
			  //echo ($ujsor) ? $h = "</tr>" : $h="</tr>";					
		}
		$ret = str_replace("{keres}", "<input type=\"submit\" value=\"".JText::_("KERESES")."\" />", $ret);
		$ret = str_replace("{start}", "<input type=\"submit\" onclick=\"$('limitstart').value=0\" value=\"".JText::_("START_")."\" />", $ret);
		ob_start();
		?>
<input type="submit" onclick="$('cond_kategoria_id').value=''" value="<?php echo jtext::_("STOP") ?>" />
<?php
		$stop = ob_get_contents();
		ob_end_clean();
		$ret = str_replace("{stop}", $stop, $ret);		
		preg_match_all("/{[0-9]*}/", $ret, $matches);
		$ret = str_replace($matches[0], "", $ret);
		return $ret;	
	}
	
	function delObj($table, $id, $pk ="id" ){
		$q = "delete from {$table} where {$pk} = $id limit 1";
		$this->_db->setQuery($q);
		return $this->_db->query();
	}

	function getObj($table, $id, $pk ="id" ){
		@$q = "select * from {$table} where {$pk} = '{$id}' limit 1";
		//die("$table");
		$this->_db->setQuery($q);
		return $this->_db->loadObject();
	}	
	
	function getLftRgtOsszes($id, $table, $idF="id"){
		$q = "select lft, rgt from {$table} where {$idF} = {$id} limit 1";
		$this->_db->setQuery($q);
		$obj = $this->_db->loadObject();
		@$q = "select {$idF} from {$table} where lft >= {$obj->lft} and rgt <= {$obj->rgt}";
		$this->_db->setQuery($q);
		return $this->_db->loadResultArray();	
	}
	
	function getJog(){
		$jog = "";
		switch( $this->user->usertype ){
			case "termekmenedzser" : 
					//echo $this->user->usertype;
					$felh_kat = unserialize($this->beallitas->felh_kat);
					$kategoriak = array();
					foreach( explode(",", $felh_kat[$this->user->id]) as $kat_id ){
						//echo $kat_id."<br />";
						$q = "select lft, rgt from #__wh_kategoria where id = {$kat_id} limit 1";
						$this->_db->setQuery($q);
						$kat = $this->_db->loadObject();
						$q = "select id from #__wh_kategoria where lft >= {$kat->lft} and rgt <= {$kat->rgt}";
						$this->_db->setQuery($q);
						$kategoriak = array_merge( $kategoriak, $this->_db->loadResultArray() );
					}
					$jog->kategoriak = $kategoriak;
				break;
			default :
				$q = "select id from #__wh_kategoria ";
				$this->_db->setQuery($q);
				$jog->kategoriak = $this->_db->loadResultArray() ;
		}
		return $jog;
	}
	
	function kivalaszt($fName="", $table=""){
		$cid = JREquest::getVar("cid", array() );
		//print_r($cid);
		$kapcsolodo_id = JRequest::getVar("kapcsolodo_id", $this->getSessionVar("kapcsolodo_id") );
		$q = "select * from {$table} where id = {$kapcsolodo_id} ";
		$this->_db->setQuery( $q );
		$obj = $this->_db->loadObject();		
		//die($q);
		@$db_kapcsolodo_arr = explode(",", $obj->$fName);
		if( is_array( $db_kapcsolodo_arr ) ){
			$obj->$fName = array_merge( $cid , explode(",", $obj->$fName) );
		}else{
			$obj->$fName = $cid;
		}
		$obj->$fName = array_unique( $obj->$fName );
		$obj->$fName = ",".implode(",", $obj->$fName ).",";
		$obj->$fName = str_replace(",,", ",",$obj->$fName );
		
		$this->_db->updateObject($table, $obj, "id" );
		//print_r($obj);
		//die;
		//echo $sablon_id;
	}

	function getSearchSelect($name, $table, $o=""){
		$v = $this->getSessionVar($name);
		$this->_db->setQuery("select `name` as `option`, id as `value` from {$table} order by `name` asc");
		$rows = $this->_db->loadObjectList();
		if(!$o){
			$o="";
			$o->option="";
			$o->value="";
		}
		array_unshift($rows, $o);
		return JHTML::_('Select.genericlist', $rows, $name, array(), "value", "option", $v);
	}

	function saveImages($id){
		global $mainframe;	
		$db = JFactory::getDBO();
		if(!file_exists($this->uploaded)){
			mkdir($this->uploaded);
		}
		for($n=1; $n<=$this->images; $n++){
			$imgname ="{$this->uploaded}/{$id}_{$n}.jpg";
			//$imgname_th ="{$this->uploaded}/{$id}_{$n}.jpg";
			//echo $imgname; exit;
			if(JRequest::getVar("torol_img_{$n}")){
				unlink($imgname);
				//die($imgname);//exit;
				//$mainframe->redirect("index.php?option=com_ingatlan&task=edit_ingatlan&id={$id}");
			}else{
				$tmp_name = $_FILES["img_{$n}"]["tmp_name"];
				if($tmp_name){
					$filename = "{$this->uploaded}/{$id}_{$n}.jpg";
					move_uploaded_file($tmp_name, $filename);
					chmod($filename, 0777);
				}
			}
		}
	}	

	function getImages(){
		ob_start();
		//die($this->images."----");
		echo '<table>';
		for($n=1; $n<=$this->images; $n++){
			(!@$this->_data->id) ? $id= $this->getSessionVar("kapcsolodo_id") : $id = $this->_data->id;
			$imgname ="{$this->uploaded}/{$id}_{$n}.jpg";
			//echo file_exists($imgname)." -- - - - -<br />";
			if(file_exists($imgname)){
				?>
<tr>
  <td class="key"><?php echo "{$n}. ".JText::_("image"); ?></td>
  <td><img src="<?php echo $imgname ?>" style="width:80px"  />
    <input type="checkbox" name="<?php echo "torol_img_{$n}" ?>" value="1" />
    <?php echo JText::_("delete") ?></td>
</tr>
<?php
			}else{
				?>
<tr>
  <td class="key"><?php echo "{$n}. ".JText::_("image"); ?></td>
  <td><input type="file" name="<?php echo "img_{$n}" ?>" /></td>
</tr>
<?php
			}
		}
		echo '</table>';	
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	}

	function store(){
	   //die(str_replace("#__", "", $this->table)." *********");
		$row =& $this->getTable( str_replace( "#__", "", $this->table ) );
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
			//die("--{$id}");
         //$this->saveImages($id);
         $this->torolFajlok($id);
		 $this->mentFajlok( $id );
 			//die("{$id} - -");
		  return $id;
	  }   	

	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}//function
	
	function deleteSession(){
		$this->xmlParser->deleteSession();
	}
	
	function getData()
	{
		// Load the datadie;
		//exit;
		if (empty( $this->_data )) {
			$query = "SELECT * FROM {$this->table} WHERE id = {$this->_id}";
			
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
			//die($this->table);
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

	function getFormByGroup($group){
		return $this->xmlParser->getFormByGroup($group);
	}
	
	function getAllFormGroups(){
		return  $this->xmlParser->getAllFormGroups();
	}
	
	function rendelesAktiv($rendeles_id){
		//$rendeles_id = JRequest::getInt("rendeles_id", 0);
		$db = JFactory::getDBO();
		$q = "select id from #__st_rendeles where id = {$rendeles_id} and datum >= now()";
		$db->setQuery($q);
		if($db->loadResult()){
			return true;
		}else{
			return false;
		}
	}
	
	function getOrd(){
      $order = "";
      $orderField = JRequest::getWord("orderField");
      $orderField_ = substr($orderField, 0, strpos($orderField, "_order"));
      if($orderField){
         $val = JRequest::getVar($orderField);
         ($val == 1) ? $ordStr = "asc" : $ordStr = "desc";
         //$arr = 
         //echo $this->order_fields[$orderField_]["q"];
         $order.=" {$this->order_fields[$orderField_]['q']} {$ordStr} ,";
         if($order){
            //$order = "order by t.akcio desc, ".substr($order, 0, strlen($order)-1);
            $order = "order by ".substr($order, 0, strlen($order)-1);
            //$order = substr($order, 0, strlen($order)-1);
         }
      }
	  //die;
      //if(!$order) $order = "order by t.akcio desc";
      //echo $order;
      //$order = "";
      return $order;
   }

	function getNyelvCond($cond, $val){
		//foreach(array("nyelv1","nyelv2","nyelv3") as $ny){
			$cond.="(";
			foreach($val as $v){
				if($v){
					if(array_search($v,$val)==(count($val)-1)){
						"(u.nyelv1 like '%{$v}%' or u.nyelv2 like '%{$v}%' or u.nyelv3 like '%{$v}%' ) and ";
					}else{
						//$cond.="$q like '%{$v}%' or ";
						"(u.nyelv1 like '%{$v}%' or u.nyelv2 like '%{$v}%' or u.nyelv3 like '%{$v}%' ) and ";
					}
				}
			}
			$cond.=") and ";
		//}
		return $cond;
		
	}

	function getTermekIds($id){
		$q="select termek_id from #__st_rendeles where id = {$id}";
		$this->_db->setQuery($q);
		$str = $this->_db->loadResult();
		$str = substr($str,1,strlen($str)-2);
		return $str;
	}
	  
	function getOrderHtml($field, $arr){
      ob_start();
      $val = Jrequest::getInt($field."_order",1);
      //echo $val;
      $orderField = Jrequest::getWord("orderField");
      if($orderField == $field."_order"){
         if($val == 1){
            $val=2;
            $title = $this->order_fields[$field]["title"][1];
            $img="<img src=\"components/com_pvm/images/nyilak2.gif\" >";
         }else{
            $val = 1;
            $title = $this->order_fields[$field]["title"][0];  
            $img="<img src=\"components/com_pvm/images/nyilak1.gif\" >";
         }
      }else{
         $title = $this->order_fields[$field]["title"][2];
         $img="<img src=\"components/com_pvm/images/nyilak3.gif\" >";
      }
      //echo $title." ********";
      $java =
         "javascript:document.getElementById(\"keresoForm\").{$field}_order.value={$val};    document.getElementById(\"keresoForm\").orderField.value=\"{$field}_order\"; document.getElementById(\"keresoForm\").submit()";
      ?>
<a title="<?php echo $title ?>" href='<?php echo $java; ?>'><?php echo $img ?> <?php echo $arr["nev"] ?></a>
<?php
      $ret = ob_get_contents();
      ob_end_clean();
      return $ret;
   }
  
   function getOrderBlock(){
      ob_start();
      ?>
<table class="table_rendezo" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <?php
            foreach($this->order_fields as $f =>$arr){
                ?>
    <td><?php echo $rendMezo = $this->getOrderHtml($f, $arr); ?></td>
    <?php
            }
            ?>
  </tr>
</table>
<?php
      $ret = ob_get_contents();
      ob_end_clean();   
      return $ret;
   }

	function getFormFieldArray(){
		$formFieldArray=array();
		foreach ($this->xmlParser->dom->getElementsByTagname('param') as $element ){
			if(is_a($element, "DOMElement")){
				$name = $element->getAttribute('name');
				//echo $name."<br />";
				$formFieldArray[]=$name;
			}
		}
		//print_r($formFieldArray); exit;
		return $formFieldArray;
	}	
	
	function getItemObjectFromXml( &$item, $a, $var){
		//print_r($item);
		//exit;
		foreach($var as $v){
			$obj="";
			$node = $this->getNode("name", $v );
			$obj ->label = $node->getAttribute("label");
			$obj ->short = $item->$v;
			$obj ->description = $node->getAttribute("description");
			$obj ->name = $v;

			//echo $node->getAttribute("type");
			switch($node->getAttribute("type")){
				case "list" :
					foreach($node->childNodes as $e_){
						if(is_a($e_, "DOMElement")){
							if($e_->getAttribute('value') == $item->$v){
								$obj ->textContent = $e_->textContent;
								$short = $e_->getAttribute("short");
								($short) ? $obj ->short = $short : $obj ->short = " ----";
							}
						}
					}
					break;
				default:
				$obj ->textContent = $item->$v;
			}
			$item->$v=$obj;
		}
	}

	function getNode($attribute, $value ){
		foreach ($this->dom->getElementsByTagname('param') as $element ){
			//echo $element->getAttribute($attribute);
			if($element->getAttribute($attribute)==$value){
				return $element;
			}
		}
	}
	
	function cron(){
		$cronfile = dirname(__FILE__)."/cron.php";
		if (file_exists( $cronfile ))
			include_once( $cronfile );
		if ($last_cron_date != date("Ymd")  /* || 1 */ ){
			//echo "hello cronfile";
			$this->manageRecallMails();
			$last_cron_date = date("Ymd");
		}
		$Fnm = $cronfile;
		$inF = fopen($Fnm,"w"); 
		fwrite($inF,'<?php $last_cron_date='.$last_cron_date.';?>');
		fclose($inF); 
	}
	
	function getUserByAdId($id){
		$q="select u.* from #__pad as p inner join #__users as u on p.user_id = u.id 
		where p.id = {$id} ";
		$this->_db->setQuery($q);
		$row = $this->_db->loadObject();
		return $row;
	}

	function getPropsById($o_){
		$q="select x.name as value, prop.name, prop.extension from #__pad_prop_xref as x 
		inner join #__pad as p on x.pad_id = p.id
		inner join #__pad_prop as prop on x.prop_id = prop.id 
		where p.id = {$o_->id} ";
		$this->_db->setQuery($q);
		$o_->props = $this->_db->loadObjectList();
		//echo $this->_db->getQuery();
		//echo $this->_db->getErrorMsg();
		//print_r($o_->props); exit;
		return $o_;
	}

	function getFileName($id, $n){
		return "{$this->uploaded}/{$id}_{$n}.jpg";
	}

	function getFileName2($id, $n){
		return "administrator/components/com_pad/images/{$id}_{$n}.jpg";
	}

	function checkMandatoryFields(){
		$errors = array();
		//print_r($this->_data->mandatory_fields);
		//exit;
		foreach($this->_data->mandatory_fields as $f){
			$index = array_search($f,$this->_data->mandatory_fields);
			$func = $this->_data->mandatory_functions[$index];
			if( !$this->$func($f) ){
				$errors[]=$f;
			}
		}
		//print_r($errors); exit;
		return $errors;
	}
	
	function mandatoryCheck($name){
		//exit;
		$val =JRequest::getVar($name, 0);
		if(is_array($val)) return $val[0]; else return $val;
	}
	
	function mandatoryCond($name){
		if(JRequest::getVar($name, 0)<>1) return 0;
		return 1;
	}
	
	function getMandatoryHidden($name, $mandatory_function, $mandatory_text){
		ob_start();
		?>
<input type="hidden" name="mandatory_fields[]" value="<?php echo $name ?>" />
<input type="hidden" name="mandatory_functions[]" value="<?php echo $mandatory_function ?>" />
<input type="hidden" name="mandatory_texts[]" value="<?php echo $mandatory_text ?>" />
<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function setMandatoryFields(){
		$arr=array("mandatory_fields","mandatory_functions","mandatory_texts");
		$mandatory_fields = JRequest::getVar("mandatory_fields");
		$mandatory_functions = JRequest::getVar("mandatory_functions", "");	
		$mandatory_texts = JRequest::getVar("mandatory_texts", "");			
		if(is_array($mandatory_fields)){
			foreach($arr as $a ){
				foreach($$a as $v){
					//print_r($$a);
					$this->_data->$a = $$a;
				}
			}
		}
		//print_r($this->_data);//exit;
	}

	function delete(&$jTable)
	{
		//die($jTable);
		$cids = JRequest::getVar( 'cid', array(0), '', 'array' );
		$row =& $this->getTable($jTable);
		//print_r($cids);
		//die();
		//print_r($row);exit;
		if (count( $cids ))
		{
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getError() );
					return false;
				}
			}//foreach
		}
		return true;
	}//function

	function getCatSelect(){
		$this->catTree(0);
		$this->_data->kategoria_id = explode(",", $this->_data->kategoria_id);
		return JHTML::_('Select.genericlist', $this->catTree, "kategoria_id[]", 
		array("multiple"=>"multiple", "class"=>"button"), "kategoria_id", "kategoria_name", $this->_data->kategoria_id);
	}
	
	function catDepth($kategoria_child_id){
		$q = "select kategoria_parent_id from #__vm_kategoria_xref where kategoria_child_id = {$kategoria_child_id}";
		$this->_db->setQuery($q);
		$res = $this->_db->loadResult();
		if($res){
			$this->depth++;
			$this->margo.="&nbsp;&nbsp;&nbsp;&nbsp;";
			$this->catDepth($res);
		}
	}
	
	function catTree($parent){
		$q = "select * from #__vm_kategoria as c inner join #__vm_kategoria_xref as x 
		on c.kategoria_id=x.kategoria_child_id
		where x.kategoria_parent_id = {$parent} and c.kategoria_publish = 'Y' order by c.list_order";
		$this->_db->setQuery($q);
		$roic = $this->_db->loadObjectList();
		if(count($roic)){
			foreach($roic as $r){
				$this->depth=1;
				$this->margo="";
				$this->catDepth($r->kategoria_child_id);
				//echo "{$this->margo}[{$this->depth}]&nbsp;{$r->kategoria_name}<br />";
				$o_="";
				$o_->kategoria_name = "{$this->margo}[{$this->depth}]&nbsp;{$r->kategoria_name}";
				$o_->kategoria_id = $r->kategoria_child_id;
				$this->catTree[]=$o_;
				$this->catTree($r->kategoria_child_id);
			}
		}
	}

	function setFields($table){
		$db=JFactory::getDBO();
		$fields_ = $db->getTableFields($table, 1);
		$fields="";
		foreach($fields_[$table] as $f => $v){
			//echo $f."<br />";
			$this->tableFields->$f=null;
		}
	}
	
	function getSessionVar($var){
		@$sess =& JSession::getInstance();
		//$o_ = $sess->get("padData");
		return $sess->get($var);
		//print_r($o_); exit;
		//return $o_->$var;
	}
}