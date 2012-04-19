<?php

defined( '_JEXEC' ) or die( '=;)' );

class xmlRendeles extends xmlParser{

	var $arTomeg = array(

		"0.1-1" => "1610",

		"1.1-3" => "1950",

		"3.1-6" => "2290",

		"6.1-9" => "2460",						

		"9.1-12" => "2540",

		"12.1-17" => "2590",

		"17.1-20" => "2630",

		"20.1-30" => "3560",						

		"30.1-40" => "4160",

		"40.1-50" => "4670",

		"50.1-60" => "5350",

		"60.1-70" => "5600",						

		"70.1-80" => "5940",

		"80.1-90" => "6370",

		"90.1-100" => "6620",

	);

	

	function getAllapot($node){

		$ret = "";		

		$name = $node->getAttribute('name');

		$r = $this->getObj("#__wh_rendeles", $this->getAktVal('id') );

		$value = $this->getAktVal($name);

		$arr = array();

		foreach($node->childNodes as $e_){

			if(is_a($e_, "DOMElement")){

				$obj="";

				//print_r($e_);

				$obj->value = $e_->getAttribute('value');

				$obj->option = jtext::_($e_->textContent);

				$arr[]=$obj;

			}

		}
		$ret .= JHTML::_('Select.genericlist', $arr, $name, array( "class"=>"{$name}_"), "value", "option", $value)."<br />";
		//$ret .= "<textarea id=\"allapot_megjegyzes\" ></textarea>".jtext::_("ROVID_MEGJEGYZES")."<br />";
		//$ret .= "<input type=\"button\" onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){ kuldAllapotvaltozas(); }\" value=\"".jtext::_("KULD_EMAIL")."\" >";
		//$ret .= jtext::_("LEGUTOBBI_KULDES").": <span id=\"allapotv_email_datum\" >{$r->allapotv_email_datum}</span>";
		return $ret;
	}

	function getSzamlaSzam( $node ){

		ob_start();

		$name = $node->getAttribute('name');

		$value = $this->getAktVal($name);

		if (@$this->visszaigazolas_datum != '0000-00-00 00:00:00' and @$this->visszaigazolas_datum != '') {$readonly='readonly="readonly"';} else {$readonly='';}

		?>

<input name="<?php echo $name ?>" <?php echo $readonly ?> value="<?php echo $value ?>" type="text" />
<?php

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;   			

	}

	

	function getCsomagId($node){

		ob_start();

		$name = $node->getAttribute('name');

		$description = $node->getAttribute('description');

		$value = $this->getAktVal($name);

		$rend_id = Jrequest::getvar('rendeles_id');

		$prefix = "PC";

		$partner_azon = '010';

		$cel_kod = '123456';

		$datum = date('Ymd',time());

		if ($value == ''){$value = $prefix+$partner_azon+$cel_kod+date('Ymd',time())+$rend_id;}

		

		?>
<input name="<?php echo $name ?>" value="<?php echo $value ?>" type="text" />
<?php echo $description ?>
<?php

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;   

	

	}

	

	function getSzallitmanyId($node){

		ob_start();

		$name = $node->getAttribute('name');

		$description = $node->getAttribute('description');

		$value = $this->getAktVal($name);

		$rend_id = Jrequest::getvar('rendeles_id');

		$prefix = "SP";

		

		if ($value == ''){$value = $prefix+date('Ymd',time())+$rend_id;}

		

		

		?>
<input name="<?php echo $name ?>" value="<?php echo $value ?>" type="text" />
<?php echo $description ?>
<?php

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;   

	

	}

	

	function getSzallitasAdmin( $node ){

		$szallitas = $this->getAktVal('szallitas');

		

		$name = $node->getAttribute('name');

		$value = $this->getAktVal($name);

		//die($value);

		if ($value == ''){$value = $szallitas;}

		foreach($node->childNodes as $e_){

			if(is_a($e_, "DOMElement")){

				$obj="";

				//print_r($e_);

				$obj->value = $e_->getAttribute('value');

				$obj->option = $e_->textContent;

				$roic[]=$obj;

			}

		}

		//$f_=

		if($roic[0]->value){

			$o="";

			$o->value=$o->option="";

			//array_unshift($roic,$o);

		}

		$js = "setClientnr('{$name}')";

		

		$this->document->addScriptDeclaration("window.addEvent(\"domready\", function(){ setClientnr('{$name}');})");

		$ret = JHTML::_('Select.genericlist', $roic, $name, array( "onchange"=>$js, "class"=>"{$name}_ alapinput"), "value", "option", $value);

		$ret .= "<div id=\"idClientnr\"></div>";

		

		return $ret;

	}





	function getMunkatars( $node ){

		ob_start();

		$name = $node->getAttribute('name');

		$value = (int)$this->getAktVal($name);

		//echo $value." {$name}-----------";

		($value) ? $value : $value = $this->user->id;

		echo $this->getDbList($name, $node, $value, 1 );

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;   			

	}



	function getKiszallitasAr( $node ){

		ob_start();

		$name = $node->getAttribute('name');

		$value = $this->getAktVal($name);

		?>
<input name="<?php echo $name ?>" value="<?php echo $value ?>" type="text" />
<?php

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;   			

	}

	

	function getKalkulaltAr( $tomeg ){

		foreach($this->arTomeg as $t => $ft){

			$arr = explode("-", $t);

			if( $arr[0]<=$tomeg && $tomeg<=end($arr) ){

				return $ft;		

			}

		}

		return false;

	}

	

	function getOsszesTomeg(){

		$id = $this->getAktVal("id");

		$q = "select sum(tomeg*quantity) as tomeg from #__wh_tetel where rendeles_id = {$id}";

		$this->_db->setQuery($q);

		return $this->_db->loadResult();		

	}

	

	function getUjTetel( $node ){

		ob_start();

		$id = $this->getAktVal("id");

		$link="index.php?option=com_wh&controller=termekek&kapcsolodo_id={$id}&tmpl=component&layout=rendeleshez";

		?>
<a rev="width: 800px height:500px" rel="lightbox[x]" href="<?php echo $link ?>" ><?php echo jtext::_("UJTETEL_HOZZAADASA") ?></a>
<?php

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;  

	}

	

	function getBeszallito( $t ){

		//print_r($t);	

		$q = "select CONCAT(bsz.nev, ' - netto:', bszar.netto_ar, ' Ft') as `option`, bsz.afa_id, bsz.id as value, bszar.netto_ar as netto_ar from #__wh_termek_beszallito_ar as bszar 

		inner join #__wh_termek as t on bszar.termek_id = t.id

		inner join #__wh_beszallito as bsz on bszar.beszallito_id = bsz.id

		where t.id = {$t->termek_id} order by bszar.netto_ar";

		$this->_db->setQuery($q);

		$rows = $this->_db->loadObjectList();

		echo $this->_db->geterrorMsg();

		$o="";

		$o->option="";

		$o->value=-1;

		array_unshift( $rows, $o );

		(!$rows) ? (array)$rows[]= $o : $rows;

		ob_start();	

		//print_r($rows);

		$besz_id = "beszallito_id_{$t->id}";

		$ajaxContainerId="ajaxContainer_{$t->id}";

		$js = "beszAr('{$ajaxContainerId}', this.value, {$t->termek_id} )";

		echo JHTML::_('Select.genericlist', $rows, "beszallito_id[]", array( "class"=>"multiple_search", "id"=>$besz_id, "onchange"=>$js), "value", "option", $t->beszallito_id)."<br />";		

		?>
<?php

		@$besz = $this->getObj("#__wh_beszallito", $t->beszallito_id);

		@$afa = $this->getObj("#__wh_afa", $besz->afa_id, "id" )->ertek;

		(isset($afa) ) ? $afa = $afa: $afa=25;

		@$id = "id_besz_{$t->beszallito_id}";

		//echo "afa: {$afa}";

		@$q = "select * from #__wh_termek_beszallito_ar where beszallito_id = {$besz->id} and termek_id = {$t->termek_id}";

		$this->_db->setQuery($q);

		$arO = $this->_db->loadObject();

		//print_r($arO);	

		//( $t->netto_ar_beszallito ) ? @$arO->netto_ar = $t->netto_ar_beszallito : @$arO->netto_ar;

		//echo $this->_db->getQuery();		

		//print_r( $arO );

		//echo $arO->netto_ar."***********<br />";

		//die;

		$beszallito_id = $t->beszallito_id;

		$termek_id = $t->termek_id;		

		@$q = "select tetelT.*, afaT.ertek as afa from #__wh_tetel as tetelT

		inner join #__wh_beszallito as bszT on tetelT.beszallito_id = bszT.id

		inner join #__wh_afa as afaT on bszT.afa_id = afaT.id

		where tetelT.id= {$t->id}";

		$this->_db->setQuery($q);

		$arO = $this->_db->loadObject();

		//print_r($arO);

		//die;

		$js = "onclick=\"this.value='';\"";

		?>
<div id="<?php echo $ajaxContainerId ?>">
  <?php

		echo @$this->getNettoBruttoInput("netto_ar_beszallito", "brutto_ar_beszallito", $arO->netto_ar_beszallito, $arO->afa, $id, "[]", "<br>", $js );		

		?>
</div>
<?php

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;

	}


	function getTetelek($node){
		$rendeles_id = $this->getAktVal("id");
		$r = $this->getObj("#__wh_rendeles", $rendeles_id );
		$ret = "";
		$ret .= $r->email_tartalom;
		return $ret;
	}

	function getTetelek___($node){
		ob_start();
		$name = $node->getAttribute('name');
		$value=$this->getAktVal($name);
		$rendeles_id = $this->getAktVal("id");
		$q = "select * from #__wh_tetel where rendeles_id = {$rendeles_id}";
		$this->_db->setQuery($q);
		$tetelek = $this->_db->loadObjectList();
		$id = $this->getAktVal("id");
		//print_r($tetelek);
		//die;
		$arr = array();
		//@$tetelek = array_map ( array($this, "setParams"), $tetelek) ;
		//@$tetelek = array_map ( array($this, "setKategoriak"), $tetelek) ;
		foreach($tetelek as $t){
			$ind = array_search($t, $tetelek);
			$o="";
			//$o->KIJELOLES = "<input type=\"checkbox\" name=\"cidTetel[]\" value=\"{$t->id}\" ><input name=\"tetel_id[]\" type=\"hidden\" value=\"{$t->id}\" >";		
			$o->CIKKSZAM = $t->cikkszam;
			$o->TERMEK = $t->nev;
			//$o->KISKER_AR = $this->getNettoBruttoInput("netto_ar", "brutto_ar", $t->netto_ar, $t->afa, $t->id, "[]", "<br>" );
			//$o->DB = "<input name=\"quantity[]\" type=\"text\" value=\"{$t->quantity}\" >";
			//$o->BESZALLITO = $this->getBeszallito($t);
			//$o->GYARISZAM = "<textarea name=\"gyariszam[]\" >{$t->gyariszam}</textarea>";
			//$o->TOMEG_KG = "<input name=\"tomeg[]\" type=\"text\" value=\"{$t->tomeg}\" >";	
			//$o->RAKTARON = $this->raktaronIkon( $t->cikkszam );

			$arr[] = $o;

		}

		if(count($arr)){

			$listazo = new listazo($arr, "rendeles_tetelek");

			echo $listazo->getLista();

		}else{

			echo jtext::_("NINCS TALALAT");

		}

		?>
<input name="<?php echo $name ?>" id="<?php echo $name ?>" value='<?php echo $value ?>' type="hidden"  />
<?php

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;

	}

		

	function raktaronIkon( $cikkszam, $termek_id = 0 ){

		ob_start();

		if( $this->ellenorizRaktar( $cikkszam ) ){

			echo "van";

		}else{

			echo "nincs";

		}

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;

	}

	

	function ellenorizRaktar( $cikkszam, $termek_id = 0 ){

		$q= "";

		if( rand( 0, 1 ) ){

			return 1;

		}else{

			return 0;

		}

	}

	

	function getRendelesiAdatok($node){

		

		ob_start();
		$rendeles_id=$this->getAktVal("id");
		$user_id=$this->getAktVal("user_id");
		$webshop_id=$this->getAktVal("webshop_id");
		$r = $this->getObj("#__wh_rendeles", $rendeles_id );
		$v = $this->getVasarlo($user_id, $webshop_id);
		$w = $this->getObj("#__wh_webshop", $webshop_id );
		//print_r($r); echo'<br /><br />';
		//print_r($v); echo'<br /><br />';
		//print_r($w); echo'<br /><br />';
		//die();
		parse_str($r->user_adatok);
		//print_r( $v );
		//die;
		parse_str($r->szamlazasi_cim);
		parse_str($r->atvevohely);
		echo ( isset($v->user->id) ) ? jtext::_("REGISZTRALT_VASARLO_AZON").": ". $v->user->id : jtext::_("NEM_REGISZTRALT_VASARLO") ;
		?>
<!--<table>
  <tr>
    <td><?php echo JText::_("MEGRENDELO");?></td>
    <td><?php echo @$NEV;?></td>
  </tr>
  <tr>
    <td><?php echo JText::_("FELHASZNALO_NEV");?></td>
    <td><?php echo @$FELHASZNALONEV;?></td>
  </tr>
  <tr>
    <td><?php echo JText::_("TELEFON");?></td>
    <td><?php echo @$TELEFON;?></td>
  </tr>
  <tr>
    <td><?php echo JText::_("EMAIL");?></td>
    <td><?php echo @$EMAIL ?></td>
  </tr>
</table>
--><table>
  <tr>
    <td><?php echo JText::_("RENDEL_WS");?></td>
    <td><?php echo @$w->nev;?></td>
  </tr>
  <tr>
    <td><?php echo JText::_("RENDEL_DATUM");?></td>
    <td><?php echo @$r->datum;?></td>
  </tr>
<!--  <tr>
    <td><?php echo JText::_("TELEFON");?></td>
    <td><?php echo $r->telefon;?></td>
  </tr>
  <tr>
    <td><?php echo JText::_("EMAIL");?></td>
    <td><?php echo $r->email;?></td>
  </tr>
  <tr>
    <td><?php echo JText::_("MEGJEGYZES");?></td>
    <td><?php echo $r->megjegyzes;?></td>
  </tr>
  <tr>
    <td><?php echo JText::_("RENDEL_FIZETES");?></td>
    <td><?php echo @$r->fizetes;?></td>
  </tr>
  <tr>
    <td><?php echo JText::_("RENDEL_SZALLITAS");?></td>
    <td><?php echo JText::_($r->szallitas);?></td>
  </tr>
  <tr>
    <td><?php echo JText::_("SZAMLAZASI NEV");?></td>
    <td><?php echo @$SZAMLAZASI_NEV;?></td>
  </tr>
  <tr>
    <td><?php echo JText::_("SZAMLAZASI CIM");?></td>
    <td><?php echo @$IRANYITOSZAM.', '.@$VAROS.', '.@$UTCA;?></td>
  </tr>
  <tr>
    <td><?php echo JText::_("SZALLITASI CIM");?></td>
    <td><?php echo @$IRANYITOSZAM.', '.@$VAROS.', '.@$UTCA;?></td>
  </tr>
--></table>
<?php

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;  

	}//function
}