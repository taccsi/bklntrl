<?php
defined( '_JEXEC' ) or die( '=;)' );
class whpPublic extends whpAdmin{
	var $ind = 0;

	var $kampanyok = array(
		"kampany_1" => array(
			"bontas_class" => "kampany_1_bontas",
			"lista_class" => "kampany_1_lista",
			"kapcsolodo_class" => "kampany_1_kapcsolodo"
		),
		"kampany_2" => array(
			"bontas_class" => "kampany_2_bontas",
			"lista_class" => "kampany_2_lista",
			"kapcsolodo_class" => "kampany_2_kapcsolodo"
		),
		"kampany_3" => array(
			"bontas_class" => "kampany_3_bontas",
			"lista_class" => "kampany_3_lista",
			"kapcsolodo_class" => "kampany_3_kapcsolodo"
		),
		"kampany_4" => array(
			"bontas_class" => "kampany_4_bontas",
			"lista_class" => "kampany_4_lista",
			"kapcsolodo_class" => "kampany_4_kapcsolodo"
		),
	);

	function getKosarOszzAr($kosar){
		$osszar = 0;		
		if( count($kosar) ){
			foreach( $kosar as $k ){
				//print_r($k);
				if($k->cikkszam) $osszar += ( $k->ar*($k->afaErtek/100+1 ) * $k->mennyiseg);
			}
		}
		return $osszar;
	}

	function setBontasEgyebKepek($item){
		$q = "select id from #__wh_kep where termek_id = {$item->id} group by id order by sorrend, id limit 1,20";
		$this->_db->setquery($q);
		$kep_idk = $this->_db->loadresultarray();
		$item->egyebkepek = array();
		foreach ($kep_idk as $kep_id){
			$forras_kep = "admin/media/termekek/{$kep_id}.jpg";
			$class="zoom";
			$buborek_kep="";
			@$alt=$item->nev;
			$cel_kep=$this->xmlParser->getCelKepNev( $kep_id, $this->w_egyeb, $this->h_egyeb, $this->mode );
			//echo $cel_kep;
			$link = $forras_kep;		
			//image($forras_kep, $cel_kep, $link="", $w="", $h="", $mode="", $class="", $buborek_kep="", $alt="")
			$item->egyebkepek[] = $this->xmlParser->image($forras_kep, $cel_kep, $link, $this->w_egyeb, $this->h_egyeb, $this->mode, "class=\"zoom\" rel=\"group\"", "{$alt}", "{$alt}");
		}
		return $item;
	}

	function setBontasKep( $item ){
		$Itemid = $this->Itemid;
		$q = "select id from #__wh_kep where termek_id = {$item->id} order by sorrend limit 1";
		$this->_db->setquery($q);
		$kep_id = $this->_db->loadresult();
		if (isset($kep_id)){
			$forras_kep = "admin/media/termekek/{$kep_id}.jpg";
		}
		//echo $forras_kep;
		$class="zoom";
		$buborek_kep="";
		@$alt=$item->nev;
		@$cel_kep=$this->xmlParser->getCelKepNev( $kep_id, $this->w, $this->h, $this->mode );
		@$link = $forras_kep;		
		@$item->listaKep = $this->xmlParser->image( $forras_kep, $cel_kep, $link, $this->w, $this->h, $this->mode, "class=\"zoom\" rel=\"group\"", "{$alt}", "{$alt}");
		return $item;		
	}
	
	function setKosar($item){
		$item->termekvariacio_id = 0;// furcsaság
		$item->kosar = ($item->netto_ar)? $this->getKosar( $item->id, $item->termekvariacio_id ) : "";
		//$item->kosar = "";
		return $item;
	}


	function getKosar( $termek_id ){
		ob_start();
		$kosarId = "kosarId{$termek_id}";
		?>
        <form enctype="multipart/form-data" method="post" id="<?php echo $kosarId ?>" >
        	<input name="option" value="com_whp" type="hidden" />        
        	<input name="controller" value="kosar" type="hidden" />
        	<input name="task" value="add" type="hidden" />
            <input name="kosarba_id" value="<?php echo $termek_id ?>" type="hidden" />
            <?php echo $this->getVariacioInput( $termek_id, array( 5,10 ) ) ?>
            <input name="mennyiseg_kosarba" type="text" class="mennyiseg_kosarba" value="1" /><?php echo $this->getObj("#__whp_termek", $termek_id )->me ?>
            <?php echo "<input type=\"button\" 
			onclick=\"if(\$j('#termVarId').val()){\$j('#{$kosarId}').submit()}else{alert('".jtext::_("KEREM_VALASSZON_TERMEKVARIACIOT")."')}\" class=\"kosar_submit\" value=\"".jtext::_("KOSARBA")."\" />" ?>
        </form>
        <?php
		//die;
		$ret=ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function getVariacioInput( $termek_id, $mezoIdArr=array() ){
		$ret = "";
		$name = "termVarId";
		$q = "select * from #__wh_termekvariacio where termek_id = {$termek_id}";
		$this->_db->setQuery($q);
		$arr = array();
		$variaciok = $this->_db->loadObjectList();
		//print_r( $variaciok );
		if( count( $variaciok ) > 1 ){
			foreach( $variaciok as $v ){
				//print_r( $this->getVariacioArr( $v->id ) ) ;				
				$o= "";
				$o->value = $v->id;
				$o->option = $this->getVariacioNev( $v->id, $mezoIdArr );
				$arr[]=$o;
			}
			$o= "";
			$o->value = $o->option = "";
			array_unshift($arr, $o);
			$ret = JHTML::_( 'Select.genericlist', $arr, $name, array( "class"=>"alapinput variacioSelect" ), "value", "option", "" );		
		}elseif( count($variaciok) ){
			$ret = "<input type=\"hidden\" id=\"{$name}\" name=\"{$name}\" value=\"{$variaciok[0]->id}\" >";
		}else{
			$ret = "<input type=\"hidden\" id=\"{$name}\" name=\"{$name}\" value=\"0\" >";
		}
		return $ret;
	}

	function getUtvonal( $kategoria_id = 0, $elv = "&gt;"){
		$Itemid = $this->Itemid;
		$arr=array();
		$link = "index.php";
		$a = "<a class=\"a_utvonal\" href=\"{$link}\">".jtext::_("FOOLDAL")."</a>";
		$arr[] = $a;
		if( $kategoria_id ){
			//die("$kategoria_id");
			$kategoria = $this->getObj( "#__wh_kategoria", $kategoria_id );
			$q = "select * from #__wh_kategoria where lft <= {$kategoria->lft} and rgt >= {$kategoria->lft} and aktiv = 'igen' and szulo <> 0 order by lft asc ";
			$this->_db->setQuery($q);
			$ret = "<div class=\"utvonal\">";
			$rows = $this->_db->loadObjectList();
			foreach($rows as $k){
				$link = "index.php?option=com_whp&controller=termekek&cond_kategoria_id={$k->id}&Itemid={$Itemid}";
				$a = "<a class=\"a_utvonal\" href=\"{$link}\">{$k->nev}</a>";
				$arr[] = $a;
			}
			//die($ret);
		}
		$ret = implode("<span class=\"utvonal_elvalaszto\"> {$elv} </span>", $arr);
		//$index = 
		return $ret;
	}


	function setListLink( $item ){
		$Itemid = $this->Itemid;
		$link = jroute::_("index.php?option=com_whp&controller=termek&cond_kategoria_id={$item->kategoria_id}&Itemid={$Itemid}&termek_id={$item->id}");
		$ret ="";
		$ret .="<a href=\"{$link}\" ><span class=\"span_reszletek\">".jtext::_("RESZLETEK")."</span><img src=\"templates/drpadlo/images/icon_kosar.png\" /></a>";
		$item->listLink = $ret;
	}
	
	function setListaKep_ideiglenes($item){
		$Itemid = $this->Itemid;
		$q = "select id from #__wh_kep where termek_id = {$item->id} order by id limit 1";
		$this->_db->setquery($q);
		$kep_id = $this->_db->loadresult();
		if (isset($kep_id)){
			$forras_kep = "admin/media/termekek/{$kep_id}.jpg";
		}
		$class="zoom";
		$buborek_kep="";
		@$alt=$item->nev;
		@$cel_kep=$this->xmlParser->getCelKepNev( $kep_id, $this->w, $this->h, $this->mode );
		$link = jroute::_("index.php?option=com_whp&controller=termek&cond_kategoria_id={$item->kategoria_id}&Itemid={$Itemid}&termek_id={$item->id}");		
		@$item->listaKep = $this->xmlParser->image((string)$forras_kep, $cel_kep, $link, $this->w, $this->h, $this->mode, "", "{$alt}", "{$alt}");
		return $item;
	}
	
	function setListaKep_kapcsolodo_ideiglenes($item){
		$Itemid = $this->Itemid;
		//die("--");
		//echo $item->kep_uri;
		//$url_arr = explode('&i=',$item->kep_uri);
		//$url = $url_arr[0].'&i='.$this->myUrlEncode($url_arr[1]);
		//print_r($url); die();
		$q = "select id from #__wh_kep where termek_id = {$item->id} order by id limit 1";
		$this->_db->setquery($q);
		//echo $this->_db->getquery();
		//echo $this->_db->geterrormsg();
		
		$kep_id = $this->_db->loadresult();
		if ($kep_id){
			$forras_kep = "media/whp/forraskepek/{$kep_id}.jpg";
			if (!file_exists($forras_kep)){
				$url = $GLOBALS["whp_kozp_url"].'media/termekek/'.$kep_id.'.jpg';
				//echo $url;
				$image = imagecreatefromjpeg($url);
				imagejpeg($image,'/var/hosting/ssd/hosting/web/bringa.hu/website/www/'.$forras_kep,'100');
				
			}
		} else {$forras_kep = "components/com_whp/assets/images/nopic.jpg";}
		
		//echo $forras_kep; 
		$class="zoom";
		$buborek_kep="";
		@$alt=$item->nev;
		$cel_kep=$this->xmlParser->getCelKepNev( $item->id, $this->w_kapcsolodo, $this->h_kapcsolodo, $this->mode );
		//echo $cel_kep;
		$link = jroute::_("index.php?option=com_whp&controller=termek&cond_kategoria_id={$item->kategoria_id}&Itemid={$Itemid}&termek_id={$item->id}");		
		//image($forras_kep, $cel_kep, $link="", $w="", $h="", $mode="", $class="", $buborek_kep="", $alt="")
		$item->listaKep = $this->xmlParser->image($forras_kep, $cel_kep, $link, $this->w_kapcsolodo, $this->h_kapcsolodo, $this->mode, "", "{$alt}", "{$alt}");
		return $item;
	}

	function setAr( $item ){
		$kedvezmeny = "";
		//$csoportKedvezmeny = $this->getCsoportKedvezmeny();
		/*if( $csoportKedvezmeny ){
			$kedvezmeny = $csoportKedvezmeny;
		}else*/
		if(@$item->kampany){
			$kedvezmeny = $item->kampany;		
		}
		//print_r($kedvezmeny);
		//die;
		$q = "select * from #__wh_ar as ar
		inner join #__wh_afa as afa
		on ar.afa_id = afa.id
		where ar.termek_id = {$item->id}
		";
		$this->_db->setQuery( $q );
		$arO = $this->_db->loadObject();

		$arr = array(
			"kisker" =>array(
				"netto_ar" => "ar",
				"netto_akcios_ar" => "discount_price",
			),
			"nagyker" =>array(
				"netto_ar" => "b2b_price",
				"netto_akcios_ar" => "b2b_price_discount",
			)
		);
		//print_r($arO);
		$priceFieldArr = ( $this->vasarlo->fcsoport_id ) ? $arr["nagyker"] : $arr["kisker"];
		$netto_ar = $arO->$priceFieldArr["netto_ar"] ;
		$netto_akcios_ar = $arO->$priceFieldArr["netto_akcios_ar"] ;		
		//echo $netto_akcios_ar." ---";
		//print_r( $priceFieldArr );
		//die;
		//die;
		if( $netto_akcios_ar != 0 && $netto_akcios_ar < $netto_ar ){
			//életbe lép a meghatározott akció ár 
			$akcAr = true;
		}else{
			$akcAr = false;
		}
		//echo $akcAr." --------";
		$item->ar = $netto_ar;
	    $item->arHTML = "";		
		if( $kedvezmeny ){//ez a kampánnyal =
			if( $akcAr ){
				$item->arHTML .= "<span class=\"span_regi_ar\">".ar::_(ar::getBrutto( $netto_ar, $item->afaErtek ) )."</span>";
				$item->arHTML .= "<span class=\"span_akcios_ar\">".ar::_(ar::getBrutto( $netto_akcios_ar, $item->afaErtek ) )."</span>";
				$item->akc_belyeg='<div class="akc_belyeg"></div>';
			}else{
				//die;
				if($kedvezmeny->kedvezmeny_tipus == "OSSZEG"){
					$item->regiAr = $netto_ar;
					$item->ar -= $kedvezmeny->kedvezmeny;
				}else{
					$item->regiAr = $netto_ar;
					$item->ar -= ( $netto_ar * $kedvezmeny->kedvezmeny/100 );
				}
				$item->arHTML .= "<span class=\"span_regi_ar\">".ar::_(ar::getBrutto( $netto_ar, $item->afaErtek ) )."</span>";
				$item->arHTML .= "<span class=\"span_akcios_ar\">".ar::_(ar::getBrutto( $item->ar, $item->afaErtek ) )."</span>";
				$item->akc_belyeg='<div class="akc_belyeg"></div>';
			}
		}elseif($akcAr){
			$item->arHTML .= "<span class=\"span_regi_ar\">".ar::_(ar::getBrutto( $netto_ar, $item->afaErtek ) )."</span>";
			$item->arHTML .= "<span class=\"span_akcios_ar\">".ar::_(ar::getBrutto( $netto_akcios_ar, $item->afaErtek ) )."</span>";
			$item->akc_belyeg='<div class="akc_belyeg"></div>';
			$item->ar = $netto_akcios_ar;			
		}else{
			$item->ar = $netto_ar;
			$item->arHTML .= "<span class=\"span_normal_ar\">".ar::_(ar::getBrutto( $netto_ar, $item->afaErtek ) )."</span>";
			$item->akc_belyeg='';
		}
		//$item->netto_ar = 
		return $item;
	}

	function getTvAr( $id, $afaErtek, $kampany){
		$kedvezmeny = "";
		if(@$kampany){
			$kedvezmeny = $kampany;		
		}
		$q = "select * from #__wh_termekvariacio where id = {$id} ";
		$this->_db->setQuery($q);
		$arO = $this->_db->loadObject();
		$arr = array(
			"kisker" =>array(
				"netto_ar" => "ar",
				"netto_akcios_ar" => "discount_price",
			),
			"nagyker" =>array(
				"netto_ar" => "b2b_price",
				"netto_akcios_ar" => "b2b_price_discount",
			)
		);
		
		$priceFieldArr = ( $this->vasarlo->fcsoport_id ) ? $arr["nagyker"] : $arr["kisker"];
		$netto_ar = $arO->$priceFieldArr["netto_ar"] ;
		$netto_akcios_ar = $arO->$priceFieldArr["netto_akcios_ar"] ;		
		if( $netto_akcios_ar != 0 && $netto_akcios_ar < $netto_ar ){
			//életbe lép a meghatározott akció ár 
			$akcAr = true;
		}else{
			$akcAr = false;
		}
		$ar = $netto_ar;
		//echo $akcAr." ------<br />";
		//print_r( $kedvezmeny );
		//die;
	    $arHTML = "";		
		if( $kedvezmeny ){//ez a kampánnyal =
			if( $akcAr ){
				$arHTML .= "<span class=\"span_regi_ar\">".ar::_(ar::getBrutto( $netto_ar, $afaErtek ) )."</span>";
				$arHTML .= "<span class=\"span_akcios_ar\">".ar::_(ar::getBrutto( $netto_akcios_ar, $afaErtek ) )."</span>";
				$ar = $netto_akcios_ar;
			}else{
				if($kedvezmeny->kedvezmeny_tipus == "OSSZEG"){
					$regiAr = $netto_ar;
					$ar -= $kedvezmeny->kedvezmeny;
				}else{
					$regiAr = $netto_ar;
					$ar -= ( $netto_ar * $kedvezmeny->kedvezmeny/100 );
				}
				//die($netto_ar." -");
				$arHTML .= "<span class=\"span_regi_ar\">".ar::_(ar::getBrutto( $netto_ar, $afaErtek ) )."</span>";
				$arHTML .= "<span class=\"span_akcios_ar\">".ar::_(ar::getBrutto( $ar, $afaErtek ) )."</span>";
			}
		}elseif($akcAr){
			$arHTML .= "<span class=\"span_regi_ar\">".ar::_(ar::getBrutto( $netto_ar, $afaErtek ) )."</span>";
			$arHTML .= "<span class=\"span_akcios_ar\">".ar::_(ar::getBrutto( $netto_akcios_ar, $afaErtek ) )."</span>";
			$ar = $netto_akcios_ar;
		}else{
			$arHTML .= "<span class=\"span_normal_ar\">".ar::_(ar::getBrutto( $netto_ar, $afaErtek ) )."</span>";
			$ar = $netto_ar;
		}
		$ret ="";
		$ret->felvitt_brutto = $arO->ar;
		$ret->arHTML = $arHTML;
		$ret->netto_ar = $ar;
		$ret->brutto_ar = ar::getBrutto( $ar, $afaErtek );		
		$ret->netto_regi_ar = $netto_ar;
		//print_r($ret);
		//die;
		return $ret;
	}

	function setLegkisebbAr($item){
		$q = "select * from #__wh_termekvariacio where termek_id = {$item->id}";
		$this->_db->setquery($q);
		
		$arr = $this->_db->loadObjectList();
		
		if ( count($arr) ){ //van-e variáció?
			$ar = 999999999;
			$regi_ar = 0;
			foreach($arr as $a) {
				$ar_ = $this->getTvAr($a->id, $item->afaErtek, $item->kampany)->netto_ar;
				if ( $ar_ < $ar && $ar_ != 0 ) {
					$ar = $ar_;
					$regi_ar = $this->getTvAr($a->id, $item->afaErtek, $item->kampany)->netto_regi_ar;
				}
			}
			if ($ar == 999999999) {$ar = 0;} //ha az összes ár 0, akkor nem íródik felül az $ar
			
			if ($ar != $regi_ar) {
				$item->arHTML = "<span class=\"span_regi_ar\">".ar::_(ar::getBrutto( $regi_ar, $item->afaErtek ) )."</span>";
				$item->arHTML .= "<span class=\"span_akcios_ar\">".ar::_(ar::getBrutto( $ar, $item->afaErtek ) ) .Jtext::_("TOL")."</span>";
			} else {
				$item->arHTML = "<span class=\"span_normal_ar\">".ar::_(ar::getBrutto( $ar, $item->afaErtek ) ) .Jtext::_("TOL")."</span>";
			}
		}
		return $item;
	}

	function getCsoportKedvezmeny(){
		$ret = "";
		if( $this->user->id ){
			$q="select fcsoport.* from #__wh_fcsoport as fcsoport
			inner join #__wh_felhasznalo as felhasznalo on felhasznalo.fcsoport_id = fcsoport.id
			where felhasznalo.useR_id = {$this->user->id}
			limit 1
			"; 	
			$this->_db->setQuery($q);
			//echo $q ;
			return $this->_db->loadObject();
		}else{
			return false;
		}
	}
	
	function setKampany( $item ){
		//echo $item->kampany_id;
		if(@$item->kampany_id_  ){
			$q = "select * from #__wh_kampany where webshop_id = {$GLOBALS['whp_id']} and id = {$item->kampany_id_} ";
			$this->_db->setQuery($q);
			$item->kampany = $this->_db->loadObject();
			//print_r($item->kampany);
		}else{
			$item->kampany = "";
		}
		return $item;
	}

	function getOrd_kampany(){
	//die;
		$order = "order by kampany_kapcsolo.kampany_prioritas desc ";
		
		$group = $this->xmlParser->getGroup("ordFields" );
		$orderField = JRequest::getWord("orderField");
		$orderField_ = substr($orderField, 0, strpos($orderField, "_order"));
		foreach ( $group->childNodes as $element ){
			if( is_a($element, "DOMElement") ){
				$field = $element->getAttribute('name');	
				if($field == $orderField_ ){
				}
			}
		}
		//if(!$order) $order = "order by t.akcio desc";
		//die( $order) ;
		//$order = "";
		return $order;
   }
   
   function getOrd($sorrendezo=''){
		if ($sorrendezo){
			return $this->getOrd_rendezo($sorrendezo);
		} else {
			return $this->getOrd_kampany();
		}
	
	}
   
   	function getOrd_rendezo($sorrendezo){
		$ret = "";
		
		if( count($sorrendezo) ){
			$sorrend_oszlop = $this->getSessionVar("sorrend_oszlop");
			$sorrend_irany = $this->getSessionVar("sorrend_irany");
			if($sorrend_irany && $sorrend_irany ) $ret = "order by {$sorrendezo[$sorrend_oszlop]['Q']} {$sorrend_irany}";
		}
		//echo $ret;
		return $ret;
	}

   function getKersoHTML_____(){
   	return "*";
   }

	function getValasztoSelect(){
		$arr = array("TERMEKNEV");
		$arr_ = array();
		foreach($arr as $a){
			$o = "";
			$o->value = $a;
			$o->option = jtext::_($a);
			$arr_[]=$o;
		}
		$ret = "";
		$value = $this->getSessionVar("cond_valaszto");
		$ret .= JHTML::_('Select.genericlist', $arr_, "cond_valaszto", array("onchange"=>"initAutoCompleteValaszto()", "class"=>"alapinput" ), "value", "option", $value);  
		return $ret;
	}

   function getKersoHTML(){ 
		ob_start();
		//echo $this->xmlParser->getAllFormGroups();	
		$sw_kereso = jrequest::getvar("sw_kereso", 1);
		$this->setSessionVar("sw_kereso", $sw_kereso);
		$Itemid = $this->Itemid;
		$kategoriafa = new kategoriafa( );
		$cond_kategoria_id = $this->getSessionVar("cond_kategoria_id");
		$cond_cimke_id = $this->getSessionVar("cond_cimke_id");
				
		//echo($cond_kategoria_id.' ----');
		$o = "";
		$o->value = "";
		$o->option = jtext::_("VALASSZON_KATEGORIAT");
		array_unshift( $kategoriafa ->catTree, $o);			
		
		$q = "select c.nev as 'option', c.id as 'value' from #__wh_cimke as c inner join #__wh_cimke_kapcsolo as ck group by c.id order by c.nev asc";
		$this->_db->setquery($q);
		$cimkek = $this->_db->loadobjectlist();
		$o = "";
		$o->value = "";
		$o->option = jtext::_("VALASSZON_CIMKET");
		array_unshift( $cimkek, $o);	
		
		$kereso_valaszto = array(
			"1"=>"TERMEKKERESO",
			"2"=>"APROKERESO",
			"3"=>"UZLETKERESO"			
		);
		$arr = array();
		foreach ($kereso_valaszto as $v=>$o){
			$obj='';
			$obj->option = Jtext::_($o);
			$obj->value = $v;
			$arr[] = $obj;
		}
		$js = "getKersoHTML(this.value)";
		
		echo '<table class="table_mitkeresel"><tr><td class="szoveg">
Mit keresel?</td><td class="mezo">';
		echo JHTML::_('Select.genericlist', $arr, "kereso_valaszto", array( "class"=>"alapinput", "onchange"=>$js ), "value", "option", $sw_kereso);
		echo '</td></tr>
</table>';
		?>


  <?php
		

		
		
		switch ($sw_kereso){
			case '1':
				echo $this->getTermekkereso();
				break;
			case '2':
				echo $this->getAprokereso();
				break;
			case '3':
				echo $this->getUzletkereso();
				break;
			default:
		}
		echo $this->xmlParser->getOrderHiddenFields();
		?>
 
<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
   }
	function getUzletKereso(){
   		ob_start();
		 ?>
          <div class="uzletkereso">
         <?php
		jimport( 'joomla.application.module.helper' );
		$module = JModuleHelper::getModule( 'mod_vs_uzletek', 'Key Concepts' );
		$attribs['style'] = 'xhtml';
		echo JModuleHelper::renderModule( $module, $attribs );
		
		?>
  	    <form action="index.php" method="get" id="vsSearchForm_mini" nev="vsSearchForm_mini" class="search">
        
        	<input type="hidden" name="orderField" id="orderField" value="" />
            <input type="hidden" name="option" value="com_whp" />
            <input type="hidden" name="controller" value="atvevohelyek" />
            <input type="hidden" name="Itemid" value="<?php echo $Itemid ?>" />
            <input type="hidden" name="search_sw" value="1" />
			<input type="hidden" name="wh_search" value="1" />
        </form> 
        </div>
		  <?php
		
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	} 
   
   function getAprokereso(){
	  ob_start();
	  ?>
      <div class="aprokereso">
     <?php
		jimport( 'joomla.application.module.helper' );
		$module = JModuleHelper::getModule( 'mod_pad_search', 'Key Concepts' );
		$attribs['style'] = 'xhtml';
		echo JModuleHelper::renderModule( $module, $attribs );
	  ?>
        <form action="index.php" method="get" id="vsSearchForm_mini" nev="vsSearchForm_mini" class="search">
        
        	<input type="hidden" name="orderField" id="orderField" value="" />
            <input type="hidden" name="option" value="com_whp" />
            <input type="hidden" name="controller" value="atvevohelyek" />
            <input type="hidden" name="Itemid" value="<?php echo $Itemid ?>" />
            <input type="hidden" name="search_sw" value="1" />
			<input type="hidden" name="wh_search" value="1" />
        </form>  
      </div>
	  <?php
	  $ret = ob_get_contents();
	  ob_end_clean();
	  return $ret;
   }
   
   function getTermekkereso(){
	$Itemid = $this->Itemid;
	ob_start();
		?>
        <div class="div_termekbox">
        <form action="index.php" method="get" id="vsSearchForm_mini" nev="vsSearchForm_mini" class="search">
        <div class="termekkereso"><table border="0" cellspacing="0" cellpadding="0">
          <tr>
          	<td colspan="3">
            <?php echo $this->getMsablonKereso();?>
          	</td>
           </tr>
		 <tr>
          	
           <?php 
		   $ar_tol = str_replace('undefined','',$this->getSessionVar("ar_tol"));
           ($ar_tol) ? $ar_tol : $ar_tol = '';
		   $ar_ig = str_replace('undefined','',$this->getSessionVar("ar_ig"));
           ($ar_ig) ? $ar_ig : $ar_ig = '';
		    ?>
              
                    <td class="szoveg"><?php echo  Jtext::_('AR') ?></td>
                    <td><input id="ar_tol" name="ar_tol" onclick="this.value=''" size="25" value="<?php echo $ar_tol ?>" /><?php echo  Jtext::_('TOL') ?></td>
                    <td> <input id="ar_ig" name="ar_ig" onclick="this.value=''" size="25" value="<?php echo $ar_ig ?>" /><?php echo  Jtext::_('IG') ?></td>
          </tr>
          <tr>
            <td class="input szoveg">
				<?php 
                // $cond_nev2 = $this->getSessionVar("cond_nev2");
                 //($cond_nev2) ? $cond_nev2 : $cond_nev2 = jtext::_("TERMEKNEV");
				 echo JText::_("TERMEKNEV");
				?>
            </td>
            <td colspan="2">
            <?
                        //$cond_nev2 = jrequest::getVar("cond_nev2");			
                        $value = $this->getSessionVar("cond_nev2");
						echo "<input id=\"cond_nev2\" name=\"cond_nev2\" onclick=\"this.value=''\" size=\"25\" autocomplete=\"off\" class=\"ac_input\" value=\"{$value}\" />"; ?>
           </td>
           </tr>
           
            <tr>
            <td colspan="2"><?php 
                 $onclick = "elokeszitKereses( '".jtext::_("TERMEKNEV")."');";
                            ?>
             <a id="a_mini" class="a_megveszem" onclick="<?php echo $onclick ?>"  /><?php echo JText::_('KERESES') ?></a></td>
           
           
          </tr>
        </table>
        </div> <input type="hidden" id="sorrend_irany" name="sorrend_irany" value="<?php echo $this->getSessionVar("sorrend_irany") ?>" />
  <input type="hidden" id="sorrend_oszlop" name="sorrend_oszlop" value="<?php echo $this->getSessionVar("sorrend_oszlop") ?>" />
  <input type="hidden" name="orderField" id="orderField" value="" />
  <input type="hidden" name="kategoria_id" id="kategoria_id" value="<?php echo Jrequest::getvar('kategoria_id','0'); ?>" />
  <input type="hidden" name="cond_akcios" id="cond_akcios" value="<?php //echo jrequest::getVar("cond_akcios"); ?>" />
  <input type="hidden" name="option" value="com_whp" />
  <input type="hidden" name="controller" value="termekek" />
  <input type="hidden" name="Itemid" value="<?php echo $Itemid ?>" />
  <input type="hidden" name="search_sw" value="1" />
  <input type="hidden" name="wh_search" value="1" />
</form>
</div>
	<?php
	$ret = ob_get_contents();
	ob_end_clean();
	return $ret;  
   }
	
	function getMsablonKereso(){
		ob_start();
		$mezok = array(
			"KINEKLESZ"=>array("FERFINEK"=>"férfi","NONEK"=>"nő","GYEREKNEK"=>"gyerek"),
			"HOLLESZ"=>array("ASZFALTON"=>"aszfalt","TEREPEN"=>"terep","MINDKETTON"=>"aszfalt/terep"),
		);
		foreach ($mezok as $key => $mezo){
			echo '<h4>'.Jtext::_($key).'</h4>';
			$value = $this->getSessionVar( $key );
			?><input type="hidden" id="<?php echo $key ?>" name="<?php echo $key ?>" value="<?php echo $value ?>" /><div id="row_<?php echo $key ?>"><?php
			foreach ($mezo as $elemnev => $elem){
				$selected = ( $value == $elem ) ? "class=\"selected\"" : ""; 
				$js = "setMsablonKeresoMezo('{$key}','{$elem}','{$elemnev}')"
				?><a <?php echo $selected ?> href="javascript:void(0)" id="<?php echo $elemnev ?>" onclick="<?php echo $js ?>"><?php echo Jtext::_($elemnev) ?></a><?php
			}
			?></div> <?php
		}		
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;  

	}
	
	function getSzerzoSelect(){
		ob_start();
		$q = "select id as `value`, nev as `option` from #__wh_szerzo order by nev";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList( );	
		$o = "";
		$o->value = "";
		$o->option = jtext::_("VALASSZON_SZERZOT");
		array_unshift($rows, $o);
		$value = $this->getSessionVar("cond_szerzo_id");	
		echo JHTML::_('Select.genericlist', $rows, "cond_szerzo_id", array( "class"=>"alapinput" ), "value", "option", $value);
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function __construct(){
		parent::__construct();
		//$option = whpBeallitasok::getOption();
		//print_r($option);
		//$this->_db = &JDatabase::getInstance( $option );
		//$this->_db = JDatabase::getInstance( whpBeallitasok::getOption() );
		//print_r( $this->_db );
		
		//die;
		$q = "select * from  #__wh_kategoria";
		$this->_db->setQuery($q);
		//print_r( $this->_db->loadObjectList() );
		//echo $this->_db->getErrorMsg();
		//die;
		
		$this->params = &JComponentHelper::getParams( 'com_whp' );		
	}
	
	function setSzerzo($item){
		$item->szerzo = $this->getobj('#__wh_szerzo',$item->szerzo_id)->nev;
		return $item;
	}
	
	function setListaNev($item){
		$Itemid = $this->Itemid;
		$nev = stripcslashes( $item->nev );
		$nev = preg_replace( '/kerékpár/i', '', $nev);
		$link = jroute::_("index.php?option=com_whp&controller=termek&cond_kategoria_id={$item->kategoria_id}&Itemid={$Itemid}&termek_id={$item->id}");
		//$item->nev = "<a href=\"{$link}\"><span>".stripcslashes( $item->gyarto)."</span> - <strong> ".stripcslashes( $item->nev )."</strong></a>";
		$item->nev = "<a href=\"{$link}\">".$nev."</strong></a>";
		return $item;
	}
	
	function setTermVar( $item ){
		//print_r($item);
		$q = "select * from #__wh_termekvariacio where termek_id = {$item->id} order by sorrend, id"; 
		$this->_db->setQuery($q);
		$arr = array();
		$variaciok = $this->_db->loadObjectList();
		if(count($variaciok)){
			foreach( $variaciok as $v ){
				$o= "";
				$o->CIKKSZAM = $v->cikkszam;
				$o->TERMVARNEV = $this->getVariacioNev( $v->id, false, true, false);
				$o->AR = ar::_( $v->ar * ($item->afaErtek/100+1)  );				
				$arr[]=$o;
			}
			$listazo = new listazo($arr, "termVar");
			$item->termVar = $listazo->getLista();
		}else{
			$item->termVar = "";
		}
		return $item;
	}
	
	function getVariacioNev( $termekvvariacio_id, $mezoIdArr=array() ){
		$termekvvariacio = $this->getObj("#__wh_termekvariacio", $termekvvariacio_id);
		//$termek = $this->getObj("#__wh_termek", $termekvvariacio->termek_id);
		$q = "select termek.*, afa.ertek as afaertek from #__wh_termek as termek 
		inner join #__wh_ar as arT on arT.termek_id = termek.id
		inner join #__wh_afa as afa on arT.afa_id = afa.id
		";
		$this->_db->setQuery($q);
		$termek = $this->_db->loadObject();
		//echo $this->_db->getErrorMsg();
		//print_r($termek);
		( count( $mezoIdArr ) /*&& 0*/ ) ? $cond = "where msablonmezo.id in(".implode(",", $mezoIdArr ).")" : $cond = "where kategoria.id = {$termek->kategoria_id}" ;
		//echo $cond."<br />";
		$q = "select msablonmezo.* from #__wh_msablonmezo_kapcsolo as kapcsolo
		inner join #__wh_msablon as msablon on kapcsolo.msablon_id = msablon.id		
		inner join #__wh_msablonmezo as msablonmezo on kapcsolo.msablonmezo_id = msablonmezo.id
		inner join #__wh_kategoria as kategoria on kategoria.msablon_id = msablon.id
		{$cond}
		group by msablonmezo.id
		";
		$this->_db->setQuery($q);
		$msablonmezoArr = $this->_db->loadObjectList();
		//preg_match_all("/&mezoid_[0-9]*?=/", $v->ertek, $arr);
		//print_r( $mezoIdArr );
		parse_str( $termekvvariacio->ertek );
		$arr = array();
		foreach( $msablonmezoArr as $m ){
			$mezoNev = "mezoid_{$m->id}";
			if(@$value = stripslashes(stripslashes($$mezoNev) ) ){
				$varnev = $m->nev.": " ;
				$arr[]="{$varnev}{$value} ";
			}else{
			}
		}
		$ret = implode(",", $arr);

		$termekvvariacio->ar = $termekvvariacio->ar*(1+$termek->afaertek/100);
		($termekvvariacio->ar) ? $ret.=" - ".ar::_($termekvvariacio->ar) : $ret;
		return $ret;
	}

	function getBrowser(){
		$useragent = $_SERVER["HTTP_USER_AGENT"];
		if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
			$browser_version=$matched[1];
			$browser = 'IE';
		} elseif (preg_match( '|Opera ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
			$browser_version=$matched[1];
			$browser = 'Opera';
		} elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
				$browser_version=$matched[1];
				$browser = 'Firefox';
		} elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
				$browser_version=$matched[1];
				$browser = 'Safari';
		} else {
				// browser not recognized!
			$browser_version = 0;
			$browser= 'other';
		}
		$ret="";
		$ret->browser = $browser;
		$ret->browser_version = $browser_version;		
		return $ret;
	}
	
}
?>
