<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');
class whModelkimutatas extends modelBase
{
	var $paneArr = array("TOP_10_TERMEK", "TOP_KATEGORIAK", "TOP_10_BESZALLITO", "MEGRENDELESEK_STAT", "HASZON_SZAMOLAS");
	var $honapok = array("JANUARY","FEBRUARY", "MARCH","APRIL","MAY","JUNE","JULY","AUGUST","SEPTEMBER","OCTOBER","NOVEMBER","DECEMBER");
	var $specArr = array("SIKERES_RENDELES", "HIBAS_RENDELES_NINCS_VISSZAIGAZOLVA", "VISSZAIGAZOLVA_SZALLITAS_NEM_LEHETSEGES", "SZALLITAS_VEVO_HIBAJABOL_SIKERTELEN", "TOROLT" );
	var $osszObj = "";
	function __construct()
	{
	 	parent::__construct();
		global $mainframe, $option;
		// Get pagination request variables
		$limit = $this->limit;
		$this->limitstart = JRequest::getVar( "limitstart", 0 );
		$this->xmlParser = new xmlParser("kimutatas.xml");
	}//function
	
	function getData(){
		$task = jrequest::getvar("task", "TOP10_TERMEKEK" );
		return $this->$task();
	}

	function getDebug($rows, $cond_){
		ob_start();
		//echo $cond_." ------------<br />";
		$q = "select rendeles.id
		from #__wh_tetel as tetel
		left join #__wh_rendeles as rendeles on tetel.rendeles_id = rendeles.id
		left join #__wh_webshop as w on rendeles.webshop_id = w.id 
		left join #__wh_termek as termek on tetel.cikkszam = termek.cikkszam
		{$cond_}
		and date_format (rendeles.datum, '%Y-%m-%d') = '{$rows[0]->nap}'
		group by rendeles.id
		";		
		$this->_db->setQuery($q);
		//print_r(  );
		$arr = array();
		$rows = $this->_db->loadResultArray();
		echo $this->_db->getQuery()."<br /><br /><br />";
		echo "<br /><br /><br /><br />".$this->_db->getErrorMsg();
		echo implode("<br />", $rows );
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function MEGRENDELESEK_STAT(){
		ob_start();
		$cond = $this->getCond();
		echo $this->getSearch( "kimutatas", "getMegrendelesekSearchArr" );
		$q = "select sum( tetel.quantity ) as darab, 
		date(rendeles.datum) as nap, 
		sum(tetel.netto_ar * tetel.quantity * (tetel.afa/100+1) ) as osszertek,
		sum(tetel.netto_ar * tetel.quantity  ) as netto_osszertek		
		
		from #__wh_tetel as tetel
		inner join #__wh_rendeles as rendeles on tetel.rendeles_id = rendeles.id
		left join #__wh_webshop as w on rendeles.webshop_id = w.id 
		left join #__wh_termek as termek on tetel.termek_id = termek.id
		{$cond} 
		group by day(rendeles.datum)
		";
		//left join #__wh_termek as termek on replace(tetel.cikkszam, '-', '') = replace(termek.isbn, '-', '')
		$this->_db->setQuery($q);
		
		//print_r(  );
		$arr = array();
		$rows = $this->_db->loadObjectList();
		$this->osszObj="";
		foreach($rows as $r){
			$this->_db->setQuery($q);
			$o="";
			$o->NAP = $r->nap;
			$o->DB = $r->darab;
			$o->NETTO_OSSZERTEK = ar::_($r->netto_osszertek);			
			$o->AFA_ERTEK = ar::_($r->osszertek-$r->netto_osszertek);			
			$o->BRUTTO_OSSZERTEK = ar::_($r->osszertek);
			//$o->DEBUG = $this->getDebug($rows, $cond);
			$this->setOsszObj( $o, $r );
			$arr[] = $o;
		}
		$this->setOsszObjAr(  );
		$arr[]=	$this->osszObj;
		echo $this->_db->getErrorMsg();
		$listazo2 = new listazo($arr, "");
		?>
        <table class="table_kimutatas">
			<tr>
                <td>
                <fieldset>
                <legend><?php echo jtext::_("RENDELESEK") ?></legend>
                <?php echo $listazo2->getLista(); ?>
                </fieldset>
                </td>
          </tr>
        </table>
        <?php

		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	}

	function setOsszObjAr(  ){
		foreach( (array)$this->osszObj as $k=>$v ){
			//echo $k."<br />";
			if( !in_array($k, array("NAP", "DEBUG", "DB") ) ){
				$this->osszObj->$k = ar::_($v);
			}
		}
	}

	function setOsszObj( $o ){
		foreach( $o as $k=>$v ){
			//echo $k."<br />";
			if( !in_array($k, array("NAP", "DEBUG") ) ){
				$this->osszObj->$k += (int)(str_replace(array("Ft", " "),"", $v ));
			}else{
				$this->osszObj->$k = "-";
			}
		}
		$this->osszObj->NAP = jtext::_("MINDOSSZESEN").": ";
	}

	function getRendelesIdk($rows){
		$rendelesIdArr = array();
		//print_r($rows[0]);
		//die;
		foreach($rows as $r){
			if(!in_array( $r->rendeles_id, $rendelesIdArr ) ){
				$rendelesIdArr[]=$r->rendeles_id;
			}
		}
		sort($rendelesIdArr);
		return implode("<br />", $rendelesIdArr );
	}

	function getMegrendelesekSearchArr(){
		$arr = array();
		$obj = "";		
		$name = "cond_ev";
		for($i = 2010; $i <= 2020; $i++ ){
			$o = "";
			$o->value = $i;
			$o->option = $i;			
			$arr_[] = $o;
		}
		$value = jrequest::getVar($name, date("Y", time() ) );		
		$js = "";
		$js = "";
		$obj->EV = JHTML::_('Select.genericlist', $arr_, $name, array( "class"=>"alapinput", "onchange"=> $js), "value", "option", $value );
		$arr[] = $obj;				
		
		$obj = "";		
		$name = "cond_honap";
		$value = jrequest::getVar($name, date("m", time() ) );		
		$arr_ = array();
		foreach($this->honapok as $h){
			$i = array_search($h, $this->honapok)+1;
			$o="";
			$o->option=jtext::_($h);
			$o->value = $i;
			$arr_[] = $o;
		}
		//$js = "$('cond_honap_haszon').value=this.value";
		$obj->HONAP = JHTML::_('Select.genericlist', $arr_, $name, array("class"=>"alapinput", "onchange"=> ""), "value", "option", $value);
		$arr[] = $obj;

		$obj = "";		
		$q = "select id as `value`, nev as `option` from #__wh_webshop";
		$this->_db->setQuery($q);
		$name = "cond_webshop_id";
		$value = JRequest::getVar($name);
		$rows = $this->_db->loadObjectList();		
		$o="";
		$o->value = $o->option = "";
		array_unshift($rows, $o);
		$obj->WEBSHOP = JHTML::_( 'Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value );
		$arr[] = $obj;

		$obj = "";		
		$q = "select id as `value`, nev as `option` from #__wh_atvhely";
		$this->_db->setQuery($q);
		$name = "cond_atvhely_id";
		$value = JRequest::getVar($name);
		$rows = $this->_db->loadObjectList();		
		$o="";
		$o->value = $o->option = "";
		array_unshift($rows, $o);		
		$obj->ATVEVOHELY = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);
		$arr[] = $obj;
		
		return $arr;	
	}

	function getRendelesOsszeg( $rendeles_id ){
		//die("---");
		$q = "select (sum(netto_ar * quantity) * (afa/100+1)) as osszertek 
		from #__wh_tetel where rendeles_id = {$rendeles_id}";
		$this->_db->setQuery($q);
		return $this->_db->loadResult();
	}
	
	function TOP10_TERMEKEK(){
		$cond = $this->getCond();
		($cond) ? $cond.=" and tetel.termek_id <> 0 " : $cond = "where tetel.termek_id <> 0 ";
		$q = "select 
		sum( tetel.quantity ) as darab, termek.nev
		from #__wh_tetel as tetel 
		left join #__wh_rendeles as rendeles on tetel.rendeles_id = rendeles.id
		left join #__wh_termek as termek on tetel.termek_id = termek.id		
		{$cond}
		group by tetel.termek_id order by darab desc limit 10";
		
		//echo $q."<br />";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		echo $this->_db->geterrorMsg();		
		$arr = array();		
		foreach($rows as $r){
			$i = array_search($r, $rows);
			$o = "";
			$o->SORSZAM= ($i+1).".";
			$o->DB= $r->darab;
			//$o->CIKKSZAM = $r->cikkszam;			
			$o->TERMEK = $r->nev;
			//$o->KATEGORIA = $r->kategoria_nev;
			$arr[]=$o;
		}

		echo $this->_db->getErrorMsg();
		ob_start();
		echo $this->getSearch( "HaszonSearchArr", "getHaszonSearchArr" );		
		$listazo = new listazo( $arr, "");
		?>
        <table class="table_kimutatas">
          <tr>
            <td>
            <fieldset>
            <legend><?php echo jtext::_("LEGTOBBET_RENDELTEK_TERMEK") ?></legend>
            <?php echo $listazo->getLista(); ?>
            </fieldset>
            </td></td>
        </table>
        <?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	}

	function getHaszonSearchArr(){
		$arr = array();

		$obj = "";		
		$name = "cond_ev";
		for($i = 2010; $i <= 2020; $i++ ){
			$o = "";
			$o->value = $i;
			$o->option = $i;			
			$arr_[] = $o;
		}
		$value = jrequest::getVar($name, date("Y", time() ) );		
		$js = "";
		$js = "";
		$obj->EV = JHTML::_('Select.genericlist', $arr_, $name, array( "class"=>"alapinput", "onchange"=> $js), "value", "option", $value );
		$arr[] = $obj;				
				
		$obj = "";		
		$name = "cond_honap_haszon";
		$value = jrequest::getVar($name, date("m", time() ) );		
		$arr_ = array();
		foreach($this->honapok as $h){
			$i = array_search($h, $this->honapok)+1;
			$o="";
			$o->option=jtext::_($h);
			$o->value = $i;
			$arr_[] = $o;
		}
		$js = "$('cond_spec_kimutatas').value='';$('cond_honap').value=this.value; $('cond_sikeres_haszon').value=1";
		$js = "";
		$cond_sikeres_haszon = "<input name=\"cond_sikeres_haszon\" value=\"\" id=\"cond_sikeres_haszon\" type=\"hidden\" >";
		$obj->HONAP = JHTML::_('Select.genericlist', $arr_, $name, array("class"=>"alapinput", "onchange"=> $js), "value", "option", $value).$cond_sikeres_haszon;
		$arr[] = $obj;
		
		$obj = "";		
		$q = "select id as `value`, nev as `option` from #__wh_webshop";
		$this->_db->setQuery($q);
		$name = "cond_webshop_id";
		$value = JRequest::getVar($name);
		$rows = $this->_db->loadObjectList();		
		$o="";
		$o->value = $o->option = "";
		array_unshift($rows, $o);		
		$obj->WEBSHOP = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);
		$arr[] = $obj;

		
		return $arr;	
	}

	function getTop10SearchArr(){
		$arr = array();
		$obj = "";		
		$name = "cond_kiszallitas_datum";
		$value = jrequest::getVar($name, date("m", time() ) );		
		$arr_ = array();
		foreach($this->honapok as $h){
			$i = array_search($h, $this->honapok)+1;
			$o="";
			$o->option=jtext::_($h);
			$o->value = $i;
			$arr_[] = $o;
		}
		$js = "$('cond_spec_kimutatas').value='';$('cond_honap').value=this.value; $('cond_sikeres_haszon').value=1";
		$js = "";
		$cond_sikeres_haszon = "<input name=\"cond_sikeres_haszon\" value=\"\" id=\"cond_sikeres_haszon\" type=\"hidden\" >";
		$obj->HONAP = JHTML::_('Select.genericlist', $arr_, $name, array("class"=>"alapinput", "onchange"=> $js), "value", "option", $value).$cond_sikeres_haszon;
		$arr[] = $obj;
		
		$obj = "";		
		$q = "select id as `value`, nev as `option` from #__wh_webshop";
		$this->_db->setQuery($q);
		$name = "cond_webshop_id";
		$value = JRequest::getVar($name);
		$rows = $this->_db->loadObjectList();		
		$o="";
		$o->value = $o->option = "";
		array_unshift($rows, $o);		
		$obj->WEBSHOP = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);
		$arr[] = $obj;

		$obj = "";		
		$name = "cond_kategoria_id";
		$value = JRequest::getVar($name);
		$kategoriafa = new kategoriafa( );
		$o="";
		$o->value = $o->option = "";
		array_unshift($kategoriafa ->catTree, $o);
		$obj->KATEGORIAS = JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name, array( "class"=>"alapinput" ), "value", "option", $value);
		//$arr[] = $obj;
		return $arr;	
	}
	
	function HASZON_STAT(){
		ob_start();
		echo $this->_db->getErrorMsg();
		echo $this->getSearch( "HaszonSearchArr", "getHaszonSearchArr" );
		$cond = $this->getCond(  );
		if($cond){
			$cond.="and rendeles.allapot = 'SIKERES_RENDELES'";
		}else{
			$cond = "where rendeles.allapot = 'SIKERES_RENDELES'";
		}
		//echo $cond;
		$q = "select distinct(afaT.ertek) as beszallito_afa
		from #__wh_tetel as tetel
		inner join #__wh_rendeles as rendeles on tetel.rendeles_id = rendeles.id
		inner join #__wh_beszallito as bsz on tetel.beszallito_id = bsz.id
		inner join #__wh_afa as afaT on bsz.afa_id = afaT.id
		{$cond} ";
		
		$this->_db->setQuery($q);
		//print_r(  );
		//echo $cond;

		$szO="";
		$varArr = array(
			"beszallitoi_arak_netto_",
			"beszallitoi_arak_brutto_",
			"eladasi_arak_netto_",
			"eladasi_arak_brutto_",

			"beszallitoi_arak_netto_ossz",
			"beszallitoi_arak_brutto_ossz",
			"eladasi_arak_netto_ossz",
			"eladasi_arak_brutto_ossz",

		);
		$afaArr = $this->_db->loadResultArray();
		foreach( $afaArr as $a){
			foreach($varArr as $v){
				$t = "{$v}{$a}";
				$szO->$t = 0;
			}
		}
		
		//print_r($afaArr);
		
		$q = "select tetel.*, afaT.ertek as beszallito_afa, rendeles.egyeb_koltseg
		from #__wh_tetel as tetel
		inner join #__wh_rendeles as rendeles on tetel.rendeles_id = rendeles.id
		inner join #__wh_beszallito as bsz on tetel.beszallito_id = bsz.id
		inner join #__wh_afa as afaT on bsz.afa_id = afaT.id
		{$cond} ";
		$this->_db->setQuery($q);
		//print_r(  );
		$rows = $this->_db->loadObjectList();
		//print_r($rows);
		$egyeb_koltseg = 0;
		foreach( $afaArr as $afa ){
			foreach( $rows as $r ){
				if( $r->beszallito_afa == $afa ){
					foreach( $varArr as $v ){
						$var = "{$v}{$afa}";
						switch($v){
							case "beszallitoi_arak_netto_" : 
								$ertek = $r->netto_ar_beszallito*$r->quantity;
								$szO->$var += $ertek;
								$szO->beszallitoi_arak_netto_ossz+= $ertek;
							break;
							case "beszallitoi_arak_brutto_" : 
								$ertek = $r->netto_ar_beszallito*(1+$r->beszallito_afa / 100)*$r->quantity;
								$szO->$var += $ertek;
								$szO->beszallitoi_arak_brutto_ossz += $ertek;
							break;							
							case "eladasi_arak_netto_" : 							
								$ertek = $r->netto_ar*$r->quantity;
								$szO->$var += $ertek;
								$szO->eladasi_arak_netto_ossz += $ertek;
							break;
							case "eladasi_arak_brutto_" : 
								$ertek = $r->netto_ar*(1+25 / 100)*$r->quantity;
								$szO->$var += $ertek;
								$szO->eladasi_arak_brutto_ossz += $ertek;
							break;							
							//case "egyebkoltsegek_" : $szO->$var +=$r->egyeb_koltseg; break;							
						}
					}
				}
			}
			//print_r($szO);
			echo $this->haszonhtml($afa, $szO);
		}
		$q = "select sum(rendeles.egyeb_koltseg) as egyeb_koltseg
		from #__wh_rendeles as rendeles
		{$cond} group by rendeles.id";
		
		$q = "select sum(rendeles.egyeb_koltseg) as egyeb_koltseg
		from #__wh_rendeles as rendeles
		{$cond} ";
		//echo $cond;
		$this->_db->setQuery($q);
		$egyeb_koltseg = $this->_db->loadResult();		
		echo $this->_db->getErrorMsg();
		echo $this->osszesites($szO, $egyeb_koltseg);
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	
	}
	
	function TOP10_BESZALLITTO(){
		$cond = $this->getCond();
		if($cond){
			$cond .= "and tetel.beszallito_id > 0";
		}else{
			$cond = "where tetel.beszallito_id > 0";			
		}
		$q = "select 
		sum( tetel.quantity ) as darab, tetel.*, termek.kategoria_id, kategoria.id as kategoria_id, kategoria.nev as kategoria_nev, beszallito.nev as beszallito_nev
		from #__wh_tetel as tetel
		inner join #__wh_termek as termek on tetel.termek_id = termek.id
		inner join #__wh_beszallito as beszallito on tetel.beszallito_id = beszallito.id
		inner join #__wh_kategoria as kategoria on termek.kategoria_id = kategoria.id
		left join #__wh_rendeles as rendeles on tetel.rendeles_id = rendeles.id		
		{$cond}
		group by tetel.beszallito_id
		order by darab desc limit 10
		";
		/*
		left join #__wh_rendeles as rendeles on tetel.rendeles_id = rendeles.id		
		$this->_db->loadObjectList();
		echo $this->_db->getErrorMsg();		
		*/
		$this->_db->setQuery($q);
		$arr = array();
		foreach($rows = $this->_db->loadObjectList() as $r){
			$i = array_search($r, $rows);
			$o = "";
			
			$o->SORSZAM= ($i+1).".";
			$o->DB= $r->darab;
			$o->BESZALLITO = $r->beszallito_nev;
			$arr[]=$o;
		}
		echo $this->_db->getErrorMsg();

		$q = "select 
		sum( tetel.quantity * (tetel.netto_ar-tetel.netto_ar_beszallito ) ) as haszon,
		tetel.*, termek.kategoria_id, kategoria.id as kategoria_id, kategoria.nev as kategoria_nev, beszallito.nev as beszallito_nev
		from #__wh_tetel as tetel
		inner join #__wh_termek as termek on tetel.termek_id = termek.id
		inner join #__wh_beszallito as beszallito on tetel.beszallito_id = beszallito.id
		inner join #__wh_kategoria as kategoria on termek.kategoria_id = kategoria.id
		left join #__wh_rendeles as rendeles on tetel.rendeles_id = rendeles.id		
		{$cond}
		group by tetel.beszallito_id
		order by haszon desc limit 10
		";
		$this->_db->setQuery($q);
		$arr2 = array();
		foreach($rows = $this->_db->loadObjectList() as $r){
			$i = array_search($r, $rows);
			$o = "";
			
			$o->SORSZAM= ($i+1).".";
			$o->HASZON= ar::_($r->haszon);
			$o->BESZALLITO = $r->beszallito_nev;
			$arr2[]=$o;
		}
		echo $this->_db->getErrorMsg();

		
		ob_start();
		echo $this->getSearch( "HaszonSearchArr", "getHaszonSearchArr" );		
		$listazo = new listazo( $arr, "");
		$listazo2 = new listazo( $arr2, "");		
		?>
        <table class="table_kimutatas">
          <tr>
            <td>
            <fieldset>
            <legend><?php echo jtext::_("LEGTOBBET_RENDELTEK_TERMEK") ?></legend>
            <?php echo $listazo->getLista(); ?>
            </fieldset>
            </td></tr><tr>
            <td>
            <fieldset>
            <legend><?php echo jtext::_("LEGTOBB_HASZNOT_HOZO_TERMEK") ?></legend>
            <?php echo $listazo2->getLista(); ?>
            </fieldset>
            </td>
          </tr>
        </table>
        <?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	}
	
	function TOP10_TERMEKCSOPORTOK(){
		$cond = $this->getCond();	
		$q = "select 
		sum( tetel.quantity ) as darab, tetel.*, termek.kategoria_id, kategoria.id as kategoria_id, kategoria.nev as kategoria_nev
		from #__wh_tetel as tetel
		inner join #__wh_termek as termek on tetel.termek_id = termek.id
		inner join #__wh_kategoria as kategoria on termek.kategoria_id = kategoria.id
		left join #__wh_rendeles as rendeles on tetel.rendeles_id = rendeles.id
		{$cond}
		group by kategoria.id
		order by darab desc limit 10
		";
		//echo $cond."<br /><br /><br />";
		//left join #__wh_rendeles as rendeles on tetel.rendeles_id = rendeles.id
		$this->_db->setQuery($q);
		$this->_db->loadObjectList();
		echo $this->_db->getErrorMsg();
		$arr = array();
		foreach($rows = $this->_db->loadObjectList() as $r){
			$i = array_search($r, $rows);
			$o = "";
			
			$o->SORSZAM= ($i+1).".";
			$o->DB= $r->darab;
			$o->KATEGORIA = $r->kategoria_nev;			
			
			$arr[]=$o;
		}
		$q = "select 
		kategoria.id as kategoria_id, kategoria.nev as kategoria_nev,
		sum( tetel.quantity * (tetel.netto_ar-tetel.netto_ar_beszallito ) ) as haszon, tetel.*		
	
		from #__wh_tetel as tetel
		inner join #__wh_termek as termek on tetel.termek_id = termek.id
		inner join #__wh_kategoria as kategoria on termek.kategoria_id = kategoria.id
		left join #__wh_rendeles as rendeles on tetel.rendeles_id = rendeles.id		
		{$cond}
		group by kategoria.id
		order by haszon desc limit 10
		";
		//echo $cond."<br /><br /><br />";
		//left join #__wh_rendeles as rendeles on tetel.rendeles_id = rendeles.id
		$this->_db->setQuery($q);
		
		$this->_db->setQuery($q);
		$arr2 = array();
		foreach($rows = $this->_db->loadObjectList() as $r){
			$i = array_search($r, $rows);
			$o = "";
			$o->SORSZAM= ($i+1).".";
			$o->HASZON= ar::_($r->haszon);
			$o->KATEGORIA = $r->kategoria_nev;
			$arr2[]=$o;
		}				
		echo $this->_db->getErrorMsg();
		ob_start();
		echo $this->getSearch( "HaszonSearchArr", "getHaszonSearchArr" );		
		$listazo = new listazo( $arr, "");		
		$listazo2 = new listazo( $arr2, "");
		?>
        <table class="table_kimutatas">
          <tr>
            <td>
            <fieldset>
            <legend><?php echo jtext::_("LEGTOBBET_RENDELTEK_TERMEK") ?></legend>
            <?php echo $listazo->getLista(); ?>
            </fieldset>
            </td></tr><tr>
            <td>
            <fieldset>
            <legend><?php echo jtext::_("LEGTOBB_HASZNOT_HOZO_TERMEKCSOPORT") ?></legend>
            <?php echo $listazo2->getLista(); ?>
            </fieldset>
            </td>
          </tr>
        </table>
        <?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	}

	function haszonhtml($afa, $szO){
		ob_start();
		$t = "beszallitoi_arak_netto_{$afa}";
		$beszallitoi_arak_netto = $szO->$t;
		
		$t = "beszallitoi_arak_brutto_{$afa}";
		$beszallitoi_arak_brutto = $szO->$t;
		
		$t = "eladasi_arak_netto_{$afa}";
		$eladasi_arak_netto = $szO->$t;		

		$t = "eladasi_arak_brutto_{$afa}";
		$eladasi_arak_brutto = $szO->$t;				
		
		?>
        <table class="table_kimutatas">
          <tr>
            <td>
            <fieldset>
            <legend><?php echo jtext::_("HASZON_STAT")." {$afa} %" ?></legend>
	          <table>
              <tr>
                <th scope="col">&nbsp;</th>
                <th scope="col"><?php echo @jtext::_("NETTO") ?></th>
                <th scope="col"><?php echo @jtext::_("BRUTTO") ?></th>
              </tr>
              <tr>
                <th scope="row"><?php echo jtext::_("BESZALLITO_ARAK") ?></th>
                <td><?php echo @ar::_($beszallitoi_arak_netto) ?></td>
                <td><?php echo @ar::_($beszallitoi_arak_brutto) ?></td>
              </tr>
              <tr>
                <th scope="row"><?php echo jtext::_("ELADASI_ARAK") ?></th>
                <td><?php echo @ar::_($eladasi_arak_netto) ?></td>
                <td><?php echo @ar::_($eladasi_arak_brutto) ?></td>                
              </tr>
              <tr>
                <th scope="row"><?php echo jtext::_("NYERESEG") ?></th>
                <td><?php echo @ar::_($eladasi_arak_netto-$beszallitoi_arak_netto)  ?></td>
                <td><?php echo @ar::_($eladasi_arak_brutto-$beszallitoi_arak_brutto)  ?></td>
              </tr>
            </table>
            </fieldset>
            </td>
          </tr>
        </table>
        <?php

		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	}

	function osszesites( $szO, $egyeb_koltseg ){
		ob_start();
		?>
        <table class="table_kimutatas">
          <tr>
            <td>
            <fieldset>
            <legend><?php echo jtext::_("OSSZESITES") ?></legend>
	          <table>
              <tr>
                <th scope="col">&nbsp;</th>
                <th scope="col"><?php echo @jtext::_("NETTO") ?></th>
                <th scope="col"><?php echo @jtext::_("BRUTTO") ?></th>
              </tr>
              <tr>
                <th scope="row"><?php echo jtext::_("BESZALLITO_ARAK") ?></th>
                <td><?php echo @ar::_($szO->beszallitoi_arak_netto_ossz) ?></td>
                <td><?php echo @ar::_($szO->beszallitoi_arak_brutto_ossz) ?></td>
              </tr>
              <tr>
                <th scope="row"><?php echo jtext::_("ELADASI_ARAK") ?></th>
                <td><?php echo @ar::_($szO->eladasi_arak_netto_ossz) ?></td>
                <td><?php echo @ar::_($szO->eladasi_arak_brutto_ossz) ?></td>                
              </tr>
              <tr>
                <th scope="row"><?php echo jtext::_("KOLTSEG") ?></th>
                <td>&nbsp;</td>
                <td><?php echo @ar::_( $egyeb_koltseg ) ?></td>                
              </tr>
              <tr>
                <th scope="row"><?php echo jtext::_("NYERESEG") ?></th>
                <td>&nbsp;</td>
                <td><?php echo @ar::_( $szO->eladasi_arak_brutto_ossz - $szO->beszallitoi_arak_brutto_ossz - $egyeb_koltseg ) ?></td>
              </tr>
            </table>
            </fieldset>
            </td>
          </tr>
        </table>
        <?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	}

}// class
?>