<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model'); 
//ini_set("display_errors" ,1);
//die("-");
//http://drpadlohu.web.maxer.hu/drpadlo/index.php?option=com_whp&controller=termekek&task=xmlimport

class whpModeltermekek extends whpPublic{
	var $uploaded = "media/whp/termekek/";
	var $w = 150;
	var $h = 100;
	var $mode = "crop";
	//var $xml = 'http://office.trifid.hu/drpadlo/xml/feltolt_eles.xml';
	var $xml = 'szamlazo/feltolt.xml';

	function __construct(){
	 	parent::__construct();
		global $mainframe, $option;
		//Get pagination request variables
		//$jkategoriak = implode(",", $this->getjog()->kategoriak );
		//print_r( $jkategoriak);
		$this->limit = $this->getlimit();
		$cimkekereso = jrequest::getVar( "cimkekereso", 0 );
		$this->task = jrequest::getvar('task','');
		$this->limitstart = JRequest::getVar( "limitstart", 0 ); 
		$this->kategoria_id = Jrequest::getvar('cond_kategoria_id',119);
		if($cimkekereso){
			$this->limit = 100000;
			$this->limitstart = 0;
			//die("---");
		}
		$this->xmlParser = new xmlTermek("termek.xml");
		$this->updateCampaign();
		//$this->xmlimport();
	}//function

	function getlimit(){
		if ($this->getCatImg() == ''){$limit = 15;} else {$limit = 12;}
		return $limit;
	}

	function getCatImg(){
		if ($id = Jrequest::getvar("cond_kategoria_id",119)){
			$q = "select * from #__wh_fajl WHERE kapcsolo_id = {$id} and kapcsoloNev='kategoria_id' ";
			$this->_db->setQuery( $q ); 
			$obj = $this->_db->loadObject( );
			echo $this->_db->getErrorMsg( ); 
			$ret = "";
			if($obj) {
				$ret = "<img src=\"admin/media/{$obj->fajlnev}.{$obj->ext}\" />";
			}
		}

		else $ret = "";

		return $ret;

	}

	function loadXml(){
		//phpinfo();
		$xmlUrl = $this->xml;
		//$xmlUrl = "https://binarit.dyndns.info/Fapadoskonyv/Exp/TrifidBooks.aspx";
		/*
		$f_ = file_get_contents($xmlUrl);
		//die( $f_ );
		$xmlObj = new SimpleXMLElement( $f_ );
		print_r($xmlObj);
		die;
		*/
		$xmlObj = simplexml_load_file( $xmlUrl );
		$arrXml = $this->objectsIntoArray( $xmlObj );
		//print_r($arrXml);
		//die;
		return ($arrXml);		
	}

	function setArRendezoMezo(){
		//die;
		$ws = $this->getobj('#__wh_webshop', $GLOBALS["whp_id"]);
		$interval = 10; //10 pecenként
		@$utolso_ar_sorrend = $ws->utolso_ar_sorrend;
		//$ok = ( jrequest::getVar( "xmlImport", "" ) == 'igen' ) ? true : false;
		//$this->_data = $this->_getList( $query, $this->limitstart, $this->limit );
		//$query = $this->_buildQuery( "where termek.id = '1172' " );
		if ( ( strtotime( $utolso_ar_sorrend ) + $interval * 60 ) < time() /*|| 1*/   ){		
			//die("setArRendezoMezo");
			$ws ->utolso_ar_sorrend = date( "Y-m-d H:i:s" );
			$this->_db->updateObject( "#__wh_webshop", $ws, "id" );
			//$query = $this->_buildQuery( );	
			$q = "SELECT termek.id, termek.megvasarolhato, termek.me, termek.netto_nagyker_ar, termek.cikkszam,
			termek.nev, termek.leiras_rovid,
			termek.kategoria_id, 
			ar.ar, kategoria.nev as kategorianev, 
			afa.ertek as afaErtek, 
			kampany_kapcsolo.kampany_prioritas, 
			gy.nev as gyarto,
			gy.id as gyarto_id,
			kampany.id as kampany_id_
			FROM #__wh_termek as termek 
			inner join #__wh_kategoria as kategoria on termek.kategoria_id = kategoria.id
			inner join #__wh_ar as ar on ar.termek_id = termek.id
			inner join #__wh_afa as afa on ar.afa_id = afa.id
			left join #__wh_gyarto as gy on gy.id = termek.gyarto_id
			left join #__wh_kampany_kapcsolo as kampany_kapcsolo on kampany_kapcsolo.termek_id = termek.id
			left join #__wh_kampany as kampany on kampany_kapcsolo.kampany_id = kampany.id	
			left join #__wh_cimke_kapcsolo as ck on termek.id = ck.termek_id
			left join #__wh_termekvariacio as tv on termek.id = tv.termek_id
			left join #__wh_parh_kat_kapcs as pkk on termek.id = pkk.termek_id	
			group by termek.id
			"; 
			//die( $cond );

			$this->_db->setQuery($q);
			$rows = $this->_db->loadObjectList( );		
			//echo $this->_db->getQuery( );
			//echo $this->_db->getErrorMsg( );		
			//$rows = $this->_db->setdb__getList( $query );
			//print_r( $rows );
			//die($query);
			array_map ( array( $this, "setKampany" ), $rows );
			array_map ( array( $this, "setAr" ), $rows );
			array_map ( array($this, "setLegkisebbAr"), $rows );
			foreach($rows as $r){
				$o="";
				$o->id = $r->id;
				$o->sorrend_ar = $r->ar;
				$this->_db->updateObject("#__wh_termek", $o, "id" );
				//print_r($o);
			}
		}
	}

	function _buildQuery( $cond='',$catid='' ){
		$jkategoriak = implode(",", $this->getjog()->kategoriak );
		//$jkategoriak = array(1,2,3,4,5);
		//print_r( $jkategoriak );die;
		//
		//phpinfo();
		//die;
		if($cond){
			$cond .= "and termek.aktiv = 'igen' and termek.besorolatlan <> 'igen' ";
			$cond .= "and kategoria.id in ({$jkategoriak}) ";
		}else{
			$cond .= "where termek.aktiv = 'igen' and termek.besorolatlan <> 'igen' ";
			//$cond .= "and kategoria.id in ({$jkategoriak}) and ar.webshop_id = {$GLOBALS['whp_id']} ";			
			$cond .= "and kategoria.id in ({$jkategoriak}) ";						
		}
		if ($catid){$cond .= "and kategoria.id = {$catid} ";}
		/*if( Jrequest::getvar('indexpage', "") ){
			$cond .= "and termek.id in (" . implode(", ", $this->getFooldaliTermekekArr() ) ." ) ";
		}*/
		if (Jrequest::getvar('search_sw')){$sorrendezo = $this->getSorrendezo(); } else { $sorrendezo = ''; }
		if ( $ord = $this->getOrd( $sorrendezo ) ) {$ord .= ', termek.nev asc'; } else { $ord = 'order by termek.sorrend_ar asc'; }
		//echo $cond."<br /><br /><br />";
		//die;
		//echo $ord."<br /><br />"; 
		$query = "SELECT termek.id, termek.megvasarolhato, termek.me, termek.netto_nagyker_ar, termek.cikkszam,
		termek.nev, termek.leiras_rovid,
		termek.kategoria_id, 
		ar.ar, kategoria.nev as kategorianev, 
		afa.ertek as afaErtek, 
		kampany_kapcsolo.kampany_prioritas, 
		gy.nev as gyarto,
		gy.id as gyarto_id,
		kampany.id as kampany_id_,
		user_editable
		FROM #__wh_termek as termek 
		inner join #__wh_kategoria as kategoria on termek.kategoria_id = kategoria.id
		inner join #__wh_ar as ar on ar.termek_id = termek.id
		inner join #__wh_afa as afa on ar.afa_id = afa.id
		left join #__wh_gyarto as gy on gy.id = termek.gyarto_id
		left join #__wh_kampany_kapcsolo as kampany_kapcsolo on kampany_kapcsolo.termek_id = termek.id
		left join #__wh_kampany as kampany on kampany_kapcsolo.kampany_id = kampany.id	
		left join #__wh_cimke_kapcsolo as ck on termek.id = ck.termek_id
		left join #__wh_termekvariacio as tv on termek.id = tv.termek_id
		left join #__wh_parh_kat_kapcs as pkk on termek.id = pkk.termek_id	
		{$cond}  
		group by termek.id
		{$ord}
		"; 
		//die( $cond );
		return $query;
	}

	function importPrice( $termek_id, $netto_B2B_ar, $brutto_webes_ar, $brutto_akcios_webes_ar, $afa ){

		$q = "select * from #__wh_ar where termek_id = {$termek_id} and webshop_id = {$GLOBALS['whp_id']} ";

		$this->_db->setQuery($q);

		$obj = $this->_db->loadObject( );

		echo $this->_db->getErrorMsg( );

		if($obj){

			$obj->ar = $brutto_webes_ar;

			$obj->b2b_price = $netto_B2B_ar;			

			$this->_db->updateObject( "#__wh_ar", $obj, "id" );

			return $obj->id;

		}else{

			$obj = '';

			$obj->termek_id = $termek_id;

			$obj->ar = $brutto_webes_ar;

			$obj->b2b_price = $netto_B2B_ar;

			@$obj->afa_id = $this->getobj('#__wh_afa', $afa, 'ertek' )->id;

			$obj->webshop_id = $GLOBALS['whp_id'];

			$this->_db->insertObject("#__wh_ar", $obj, "id");

			return $this->_db->insertid();

		}

	}

	function getTortAr( $ar ){
		$ar = str_replace(",", ".", $ar );
		$egesz = floor( $ar );
		$tort = $ar - floor( $ar );
		$tort = substr( $tort, 0, 4 );
		$tort = ( $tort ) ? $tort : 0;
		//echo $ar."<br />";
		//echo $egesz + $tort;
		//die("-------");
		return ($egesz + $tort);
	
	}

	function xmlimport(){
		$ws = $this->getobj('#__wh_webshop', $GLOBALS["whp_id"]);
		$interval = $ws->import_interval;
		$last_import = $ws->last_import;
		$ok = (jrequest::getVar( "task", "" ) == 'xmlimport' ) ? true : false;		
		if ( $ok ){			
			$termekek = $this->loadXml();
			if( is_array( $termekek["cikk"][0] ) ){
				$termekek = $termekek["cikk"]; //ha sok van
			}
			//print_r( $termekek );
			//die();
			
			$xml_whp_mezok = array(
				'cikkszam'=>'cikkszam',
				'cikknev'=>'nev',
				'ar'=>'ar',
				'ar2'=>'b2b_price',
				//'afa'=>'afa',
				'me'=>'mertekegyseg',
				'leiras'=>'leiras',
				'rovid'=>'leiras_rovid',
			);
			$i=0;
			$cikksz_array = array();
			//print_r($termekek['cikk']);
			//die;
			//$termekek = $termekek;

			foreach ( $termekek as $item ){
				//print_r($item);
				$cikksz_array[] = $item['cikkszam'];
				$cikksz = $item['cikkszam'];
				//die($cikksz);
				$q = "select * from #__wh_termek where cikkszam = trim('{$cikksz}') limit 1 ";
				$this->_db->setquery($q);
				$tObj = $this->_db->loadObject();
				echo $this->_db->geterrormsg();
				$q = "select * from #__wh_termekvariacio where cikkszam = trim( '{$cikksz}' ) limit 1 ";
				$this->_db->setquery($q);
				$tvObj = $this->_db->loadObject();
				$afa = $item['afa'];
				$netto_B2B_ar = $item['ne'];
				
				//$tmp = str_replace(",", ".", $item['ar1'] );
				//$brutto_webes_ar =   round( $tmp / ( $afa / 100 + 1 ), 2 );
				$brutto_webes_ar = $this->getTortAr( $item['ar1'] );
				//$tmp = str_replace(",", ".", $item['ar2'] );				
				//$brutto_akcios_webes_ar = round( $tmp / ( $afa / 100 + 1 ), 2 );
				$brutto_akcios_webes_ar = $this->getTortAr( $item['ar2'] ); //brutto arak!!!
				
				if( $tObj ){
					$tObj->keszlet = $item['keszlet'];
					$tObj->xmlbol = "igen";
					$this->_db->updateObject( "#__wh_termek", $tObj, "id" );
					$this->importPrice( $tObj->id, $netto_B2B_ar, $brutto_webes_ar, $brutto_akcios_webes_ar, $afa );
				}elseif( !$tvObj ) {
					$obj = '';
					$obj->cikkszam=$item['cikkszam'];
					$obj->nev=$item['cikknev'];					
					$obj->besorolatlan='igen';
					$obj->aktiv='nem';
					$obj->xml_adatok=$this->getXmlData($item);
					$obj->xmlbol = "igen";
					$obj->me = $item['me'];					
					$obj->leiras = $item['leiras'];					
					$obj->leiras_rovid = $item['rovid'];
					$obj->termek_tipus = "DARABARU";
					$this->_db->insertObject("#__wh_termek", $obj, "id");
					echo $this->_db->geterrormsg();
					if( $obj->cikkszam == "80joy104bar" ){
						//print_r($obj);
						//die();
					}
					//print_r($obj); echo'<br /><br />-----<br /><br />';
					$id = $this->_db->insertid();
					//$this->setfile($item, $id);
					//$kategoriak[$item['BookID']] = $item['Category'];
					$this->importPrice( $id, $netto_B2B_ar, $brutto_webes_ar, $brutto_akcios_webes_ar, $afa );
				}
				$termek_id = (isset($tObj->id)) ? $tObj->id : $id;
				//if($cikksz == "00091 3 400 007B") die( "00091 3 400 007B" );
				if(!$tvObj){
					$tvObj="";
					$tvObj->termek_id = $termek_id;
					$tvObj->cikkszam = $cikksz;
					$this->_db->insertObject( "#__wh_termekvariacio", $tvObj, "id" );
					$tvObj = $this->getObj( "#__wh_termekvariacio", $this->_db->insertId() );
					//die('------');
				}
				@$tvObj->ar = $brutto_webes_ar;
				@$tvObj->b2b_price = str_replace(",", ".", $netto_B2B_ar );
				@$tvObj->discount_price = $brutto_akcios_webes_ar;					
				@$tvObj->keszlet = $item['keszlet'];					
				$this->_db->updateObject("#__wh_termekvariacio", $tvObj, "id"); 
				echo $this->_db->geterrormsg();
			}
			$q = "update #__wh_webshop set last_import = NOW()";
			$this->_db->setquery($q);
			$this->_db->query();
			//echo $this->_db->getErrorMsg(); 
			//die("-- -- - - - -");
			//die();
		}else{
		}
		//print_r($termekek); die();
	}

	function getXmlData($item){
		$str = "&";
		foreach($item as $key=>$v){
				$str.="{$key}={$v}&";
		}
		return $str;
	}

	function ChkDeletedItems($cikksz_array){

		$xml_cikkszamok='';

		foreach ($cikksz_array as $cikksz){

			$xml_cikkszamok .= "'{$cikksz}',";

		}

		$xml_cikkszamok = trim($xml_cikkszamok,',');

		$q = "update #__wh_termek set aktiv='nem' where cikkszam not in ({$xml_cikkszamok}) and xml_adatok != '' ";

		$this->_db->setquery($q);

		$this->_db->query();

		//echo $this->_db->geterrormsg();

		//echo $this->_db->getquery();

		//die();

	}



	function setTalatiSzamlalo(){

		ob_start();

		$ret = "";

		$cond_cimke_varazslo = jrequest::getVar("cond_cimke_varazslo", array() );

		//print_r($cond_cimke_varazslo);

		$cond_kategoria_id = jrequest::getVar("cond_kategoria_id", 0 );

		//echo $cond_kategoria_id ;

		//die($cond_kategoria_id);

		//print_r( $cond_cimke_varazslo );

		$cond = $this->getcond();

		//die($cond);

		$q = $this->_buildQuery( $this->getcond() );

		$data = $this->_getList( $q, $this->limitstart, 1000000000 );

		//die;

		$c_ = ob_get_contents();

		ob_end_clean();

		if (count( $data ) >16) {

			$ret->html = '<div class="van_talalat">'.jtext::_("TALALATOK_SZAMA").': '.count( $data )." ". $c_.'</div>';

		}else {

			$ret->html = '';

		}

		$ret->debug = "";		

		$ret->error = "";

		//print_r($ret);

		

		return $this->getJsonRet( $ret );

	}

	

	function updateCampaign(){

		$ws_id = $GLOBALS["whp_id"];

		$q = "SELECT id from #__wh_kampany where DATE_ADD(datum, INTERVAL hossz DAY) < NOW() and webshop_id = {$ws_id} ";

		$this->_db->setquery($q);		

		$exp = $this->_db->loadresultarray();

		$exp = implode(',', $exp);

		$q = "delete from #__wh_kampany_kapcsolo where kampany_id in ($exp)";

		$this->_db->setquery($q);

		$this->_db->query();

		//print_r($exp); die();

	}

	

	function objectsIntoArray($arrObjData, $arrSkipIndices = array())

	{

		

		$arrData = array();

	   

		// if input is object, convert into array

		if (is_object($arrObjData)) {

			$arrObjData = get_object_vars($arrObjData);

			

		}

		if (is_array($arrObjData)) {

			foreach ($arrObjData as $index => $value) {

				if (is_object($value) || is_array($value)) {

					$value = $this->objectsIntoArray($value, $arrSkipIndices); // recursive call

				}

				if (in_array($index, $arrSkipIndices)) {

					continue;

				}

				$arrData[$index] = $value;

 			}

		}

		return $arrData;

	}

	

	function getKampanyTermekIdArr( $limit = 8 ){

				

		$q = "select termek.id from #__wh_termek as termek

		inner join #__wh_kampany_kapcsolo as kapcsolo on termek.id = kapcsolo.termek_id

		inner join #__wh_kampany as kampany on kapcsolo.kampany_id = kampany.id

		where kampany.aktiv = 'igen'

		group by termek.id

		order by kapcsolo.kampany_prioritas desc

		limit {$limit}

		";

		$this->_db->setQuery($q);

		$ret = $this->_db->loadResultArray();

		return $ret;

	}



	function getKiskeresoMezo(){

		ob_start();

		$jkategoriak = implode(",", $this->getjog()->kategoriak );

		print_r( $jkategoriak);

		$q = "select nev from #__wh_termek as t where aktiv = 'igen' and kategoria_id in ({$jkategoriak}) order by nev ";

		$this->_db->setQuery($q);		

		$arr = $this->_db->loadResultArray();

		echo $this->_db->getErrorMsg();

		//print_r($arr);

		//$arr2 = array();

		//die;

		$termekek =  implode("','", $arr );

		$this->document =& JFactory::getDocument();		

		$this->document->addScriptDeclaration("var termekek = new Array ('{$termekek}');");	

		$cond_nev2 = jrequest::getvar("cond_nev2", ""); //$this->getSessionVaR("cond_nev2");

		//echo 

		echo "<input id=\"cond_nev2\" name=\"cond_nev2\" onclick=\"this.value=''\" size=\"25\" autocomplete=\"off\" class=\"ac_input\" value=\"{$cond_nev2}\" />";

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;	

	}

	

	function getSorrendezo(){

		$sorrendezo = array(

			"TERMEKNEV" => array(

				"OSZLOP" => $this->getSorrendBlock("TERMEKNEV"),

				"Q"=>"termek.nev",

			),

			"AR" => array(

				"OSZLOP" => $this->getSorrendBlock("AR"),

				"Q"=>"termek.sorrend_ar",

			),

		);

		return $sorrendezo;

	}

	

	function getSorrendezoHTML(){

		$sorrendezo = $this->getSorrendezo();

		$ret = '';

		foreach ($sorrendezo as $mezo){

			$ret .= '<span class="sorrend_link">'.$mezo['OSZLOP'].'</span>';

		}

		return $ret;

	}

	

	function getSorrendBlock( $oszlop ){

		if(jrequest::getvar("sorrend_oszlop") == $oszlop ){

			if(jrequest::getvar("sorrend_irany") == "asc" ){

				$irany = "desc";

			}else{

				$irany = "asc";

			}

			$sorrenyilak = $this->getSorrendNyilak( $irany );			

		}else{

			$irany = "asc";

			$sorrenyilak = $this->getSorrendNyilak(  );

		}
		$onclick= "
			\$j('#sorrend_irany').val('{$irany}');
			\$j('#sorrend_oszlop').val('{$oszlop}'); 
			\$j('#cond_nev').val(''); 
			elokeszitKereses( '".jtext::_("COND_NEV")."' );
			\$j('#vsSearchForm_mini').submit();
		";

		$onclick= "
			\$j('#sorrend_irany').val('{$irany}');
			\$j('#sorrend_oszlop').val('{$oszlop}'); 
			elokeszitKereses( '".jtext::_("COND_NEV")."' );
			\$j('#vsSearchForm_mini').submit();
		";

		//

		$ret = "<a href=\"javascript:void(0)\" onclick=\"{$onclick}\" >".jtext::_($oszlop)." {$sorrenyilak}</a>";

		return $ret;

	}



	function getSorrendNyilak( $sw = false ){

		switch($sw){

			case "asc" :

				$ret = "<img src=\"components/com_whp/assets/images/nyilak1.gif\" >";

			break;

			case "desc" :

				$ret = "<img src=\"components/com_whp/assets/images/nyilak2.gif\" >";			

			break;

			default : 

				$ret = "<img src=\"components/com_whp/assets/images/nyilak3.gif\" >";			

		}

		return $ret;

	}

	function setLeirasLimiter($item){
		$item->leiras_rovid = $this->neat_trim($item->leiras_rovid,200);
		return $item;
	}

	function getData($catid){
		//die("------");
		//if (empty( $this->_data )){
			$query = $this->_buildQuery($this->getCond(),$catid);
			$this->_data = $this->_getList( $query, $this->limitstart, $this->limit );
			//echo $this->_db->getquery();
			//die;
			echo $this->_db->geterrormsg();
			//print_r($this->_data); die();
			array_map ( array( $this, "setListaKep_ideiglenes" ), $this->_data);
			//array_map ( array( $this, "setKampany" ), $this->_data );
			array_map ( array( $this, "setAr" ), $this->_data);
			array_map ( array( $this, "setListaNev" ), $this->_data );
			//array_map ( array( $this, "setListaRovidleiras" ), $this->_data );
			array_map ( array( $this, "setLeirasLimiter" ), $this->_data);
			//array_map ( array( $this, "setListLink" ), $this->_data );
			//array_map ( array( $this, "setKosar" ), $this->_data );
			
			//array_map ( array($this, "setLegkisebbAr"), $this->_data );
		//}
		//print_r($this->_data); die();
		$this->setArRendezoMezo();
		return $this->_data; 
	}//function
	
	
	function getFooldaliTermekekArr(){
		$q = "SELECT termek.id
		FROM #__wh_termek as termek 
		inner join #__wh_kampany_kapcsolo as kampany_kapcsolo on kampany_kapcsolo.termek_id = termek.id
		inner join #__wh_kampany as kampany on kampany_kapcsolo.kampany_id = kampany.id
		where kampany.fooldal = 'igen'
		and kampany.aktiv = 'igen'
		";
		$this->_db->setQuery($q);
		$ret = $this->_db->loadResultArray( );
		$ret[]=0;		
		//echo $this->_db->getQuery( );
		echo $this->_db->getErrorMsg( );		
		return $ret;
	}

	function getOldalcim(){

		//echo $item->kampany_id_; 

		$Itemid = $this->Itemid;

		if( $this->kategoria_ ){

			$q = "select * from #__wh_kategoria where lft <= {$this->kategoria_->lft} and rgt >= {$this->kategoria_->lft} and aktiv = 'igen' order by lft asc ";

			$this->_db->setQuery($q);

			//echo $q;

			$arr=array();

			$ret = "<div class=\"utvonal\">";

			//print_r($this->_db->loadObjectList());

			foreach($this->_db->loadObjectList() as $k){

				$link = "index.php?option=com_whp&controller=termekek&cond_kategoria_id={$k->id}&Itemid={$Itemid}";

				$a = "<a class=\"a_utvonal\" href=\"{$link}\">{$k->nev}</a>";

				$arr[] = $a;

			}

			$ret .= implode("<span class=\"utvonal_elvalaszto\"> / </span>", $arr);

			$ret .= "</div>";

			//echo $ret;

			//cond_kategoria_id

		}else{

			$ret = "";

		}

		return $ret;

	

	}

	function tree($szulo, $display = 'block'){		
		$Itemid = $this->Itemid;
		
		//echo ' x ';
		$idk = implode(',',$this->getjog()->kategoriak);
		//print_r($idk); die();
		$q = "select * from #__wh_kategoria as c 
		where c.szulo = {$szulo} and c.id in ({$idk}) and c.aktiv = 'igen' order by c.sorrend";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		//echo $this->_db->getErrorMsg()."****";
		//echo $this->_db->getQuery();
		//print_r($rows); die();
		if(count($rows)){
			?>
			<ul style="display:<?php echo $display ?>">
			<?php
			foreach($rows as $row){
				$class = ( $this->kategoria_id == $row->id ) ? $aktiv = "li_active " : "";
				$q = "select count(id) from #__wh_kategoria where szulo = '{$row->id}'";
				$this->_db->setquery($q);
				$gyerekek = $this->_db->loadresult();
				if ($gyerekek !=0){ $class .= 'fokat';} else {$class .= 'alkat';}
				?>
				<li class="<?php echo $class; ?>">
				<?php
				$this->getItem($row);
				//echo "{$row->id}<br />";
				//print_r($this->get_kat($this->kategoria_id));
				if(
						$this->ellenoriz_gyerek(
							$this->get_kat($this->kategoria_id),//gyerek
							$this->get_kat($row->id)//szulo
						 ) || 1 
					)
				{
						
					if ($this->kategoria_id != 0  ) {
						//echo ' x ';
						//print_r($row);
						//echo ' y ';
						$this->tree($row->id, 'block'); 
					}
					$this->getTermekek($row->id); 
				} /*else {
					if ($this->kategoria_id != 0  ) { $this->tree($row->id, 'none'); }
				}*/
				?>
				</li>
				<?php
			}
			?>
			</ul>
			<?php
		}
		
	}

	function ellenoriz_gyerek($gyerek, $szulo){
		//print_r($szulo);
		if (@$gyerek->id) {
		@$q="select * from #__wh_kategoria where lft >= {$szulo->lft} and rgt <= {$szulo->rgt} and id = {$gyerek->id}";} else {
		@$q="select * from #__wh_kategoria where lft >= {$szulo->lft} and rgt <= {$szulo->rgt}";}
		$this->_db->setQuery($q);
		$obj = $this->_db->loadObject();
		//echo $this->_db->getQuery();
		return $obj;
	}
	
	function get_kat($id){
		@$q="select * from #__wh_kategoria where id = {$id}";
		$this->_db -> setQuery($q);
		$obj = $this->_db -> loadObject();
		//echo $this->_db->getQuery();
		//print_r($kat);
		return $obj;
	}
	
	function getItem($row){
		$Itemid = $this->Itemid;
		//return "";
		//( $this->getActive($row) ) ? $aktiv = "balmenu_link active_menu" : $aktiv = "balmenu_link";
		//print_r($row);
		($row->szulo) ? $class = "balmenu_link" : $class = "balmenu_fokategoria";
		( $this->kategoria_id == $row->id ) ? $aktiv = "{$class} active_menu" : $aktiv = "{$class}"; 
		$link=JRoute::_("index.php?option=com_whp&controller=termekek&cond_kategoria_id={$row->id}");
		$config =& JFactory::getConfig();
		$sitename = $config->getValue( 'config.sitename' );
		$q = "select count(id) from #__wh_kategoria where szulo = '{$row->id}'";
		$this->_db->setquery($q);
		$gyerekek = $this->_db->loadresult();
		
		$q = "select * from #__wh_fajl where kapcsolo_id = {$row->id} and kapcsoloNev like 'kategoria_id' limit 1";
		$this->_db->setquery($q);
		$kep = $this->_db->loadobject();
		if ($kep){
			?>
			<div class="cat_image"><img src="media/kategoria_kep/<?php echo $kep->fajlnev.'.'.$kep->ext ?>" /></div>
			<?php
		}
		?>
			<div class="cat_data">
				<h2><?php echo $row->nev?></h2>
				<?php if($row->leiras != "") { ?>
					<div class="cat_desc">
						<?php echo $row->leiras ?>
					</div>
				<?php } ?>
			</div>
	     <?php
	}
	function listCategories(){
		ob_start();	
		$catid = jrequest::getvar('cond_kategoria_id');	
		$this->tree($catid);
		$ret = ob_get_contents();
		ob_end_clean();
		$r = "";
		$r->html=$ret;
		$r->error = "";
		return $this->getJsonRet($r);
	}
	
	function getTermekek($catid=''){

		//echo "dklfjdlkjdfklj<br />";
		
		$rows=$this->getData($catid);
		
		//die;
			$ret = '';
			if(count($rows) > 0){ // vannak sorok
				
				//print_r($rows);
				jimport("unitemplate.unitemplate");
				$uniparams->cols = 3;
				$uniparams->cellspacing = 0;
				$uniparams->templatePath = "components/com_whp/unitpl";
				$uniparams->pair = false;
				$ut = new unitemplate("list", $rows, "div", "termek_lista", $uniparams);
				$cimkekereso = jrequest::getVar( "cimkekereso", 0 );
				/*if(!$cimkekereso){
					$p = $this->getPagination();
					$ret .= '<div class="pagenav">'.$p->getpageslinks().'</div>';
				}*/
				$ret .= $ut -> getContents(); 
				/*if(!$cimkekereso){
					$p = $this->getPagination();
					$ret .= '<div class="pagenav">'.$p->getpageslinks().'</div>';
				}*/
				jrequest::setVar( "format", "html" );
			}else{
				//$ret .= "<div class=\"div_nincs_talalat\" align=center>".JText::_("NINCS TALALAT")."</div>";
			}
		
		//return $ret;
		if ($this->task == 'listCategories'){
			echo $ret;
		} else {
			$r = "";
			$r->html=$ret;
			$r->error = "";
			return $this->getJsonRet($r);
		}
		
	}



	function getTotal()

	{

		//echo "-------<br />";

		// Load the content if it doesn't already exist

		if (empty($this->_total))

		{

			$query = $this->_buildQuery( $this->getCond() );

			$this->_total = $this->_getListCount($query);	

		}

		//echo $this->_total;

		return $this->_total;

	}//function

  

	function getPagination(){

 	// Load the content if it doesn't already exist

 	if (empty($this->_pagination))

 	{

		jrequest::setVar( "format", "html" );

 	    jimport('joomla.html.pagination');

 	    $this->_pagination = new JPagination($this->getTotal(), $this->limitstart, $this->limit );

 	}

 	return $this->_pagination;

  }//function





}// class

?>