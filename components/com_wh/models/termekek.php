<?php

defined( '_JEXEC' ) or die( '=;)' );

jimport('joomla.application.component.model');

class whModeltermekek extends modelBase{



var $limit = 30;

var $uploaded = "media/termekek/";

var $specMezoIdArr = array(8, 7);

	function __construct(){

	 	parent::__construct( );

		global $mainframe, $option;

		$this->limitstart = JRequest::getVar( "limitstart", 0 );

		$this->xmlParser = new xmlParser("termek.xml");

		

		//$this->kampanyok = $this->getKampanyok();

		//print_r($this->kampanyok);

		$this->initKampanyok();

		//die;

	}//function



	function getKampanyok(){

		$q = "select id as `value`, nev as `option` from #__wh_kampany where aktiv = 'igen'";

		$this->_db->setQuery($q);

		return $this->_db->loadObjectList();		

	}



	function mentKampany(){
		$idArr = JREquest::getVaR( "idArr", array(), "array" );
		foreach($idArr as $termek_id){
			$ind = array_search($termek_id, $idArr);
			$q = "delete from #__wh_kampany_kapcsolo where termek_id = {$termek_id}";
			$this->_db->setQuery($q);
			$this->_db->Query();
			foreach( $this->kampanyok as $kArr ){
				//print_r($this->kampanyok);
				//die;
				foreach( $kArr as $k ){
					$name = "kampanyIdArr_{$k->webshop_id}";
					$kkk = JREquest::getVaR( $name, array(), "array" );
					$o = "";
					$o->termek_id = $termek_id;
					$o->kampany_id = $kkk[$ind];
					$o->direkt = "igen";
					if( $o->kampany_id ){
						$o->kampany_prioritas = $k->kampany_prioritas;
						//$kampanyTabla = $this->getObj( "#__wh_webshop", $k->webshop_id )->kampanytabla;
						$k_ = $this->getObj("#__wh_kampany_kapcsolo", $termek_id, "termek_id" );
						if(!$k_)$this->_db->insertObject( "#__wh_kampany_kapcsolo" , $o, "id" );
					}
					echo $this->_db->getErrorMsg();
				}
			}
		}
	}

	function setKampany($item){
		$item->kampany = "";
		//print_r( $this->kampanyok );
		//echo "****";
		//die;
		foreach( $this->kampanyok as $kArr ){
			//print_r($this->kampanyok);
			//die;
			$arr = array();
			$o="";
			$o->value = $o->option = "";
			$arr[] = $o;
			$value = "";
			$value = "";
			foreach( $kArr as $k){
				$o="";					
				$o->value = $k->id;
				$o->option = $k->nev;
				$arr[]=$o;
				$q = "select kampany_id from #__wh_kampany_kapcsolo where kampany_id = {$k->id} and termek_id = {$item->id}";
				$this->_db->setQuery($q);
				//echo $q."<br />";
				( !$value ) ? $value = $this->_db->loadResult() : $value;
				//echo $value."<br />";
			}
			$name = "kampanyIdArr_{$k->webshop_id}[]";
			$item->kampany .= JHTML::_('Select.genericlist', $arr, $name, array( "class"=>"{$name}_ alapinput"), "value", "option", $value);
		}
		return $item;
	}	

	function saveProductPrices(){

		$idArr = JREquest::getVaR("idArr", array(), "array");	

		foreach($idArr as $id){

			$this->savePrices( $id );

		}

	}

	

	function torolTermekek(){

		$cid = jrequest::getVar( "cid", array() );

		$tableArr = array("#__wh_termekvariacio", "#__wh_cimke_kapcsolo", "#__wh_ar", "#__wh_kampany_kapcsolo", "#__wh_ktermek_kapcsolo", "#__wh_kiegtermek_kapcsolo", "#__wh_kep", "#__wh_fajl" );

		foreach( $cid as $termek_id ){

			foreach($tableArr as $t){

				switch($t){

					case "#__wh_kep" :

						$q = "select * from {$t} where termek_id = {$termek_id} ";

						$this->_db->setQuery($q);

						foreach( $this->_db->loadObjectList( ) as $kepO ){

							unlink("media/termekek/{$kepO->id}.jpg");

							$q = "delete from {$t} where termek_id = {$termek_id}";

							$this->_db->setQuery($q);

							$this->_db->Query();

							echo $this->_db->getErrorMsg( );		

						}

						//echo $this->_db->getQuery( );

						echo $this->_db->getErrorMsg( );		

					break;

					case "#__wh_fajl" :

					//kapcsoloNev fajlnev

						$q = "select * from {$t} where kapcsolo_id = {$termek_id} and kapcsoloNev = 'termek_id' ";

						$this->_db->setQuery($q);

						foreach( $this->_db->loadObjectList( ) as $fO ){

							unlink("media/termekfajlok/{$fO->fajlnev}.{$fO->ext}");

							$q = "delete from {$t} where termek_id = {$termek_id} and kapcsoloNev = 'termek_id'";

							$this->_db->setQuery($q);

							$this->_db->Query();

							echo $this->_db->getErrorMsg( );		

						}

						//echo $this->_db->getQuery( );

						echo $this->_db->getErrorMsg( );		

					break;

					default:

						$q = "delete from {$t} where termek_id = {$termek_id}";

						$this->_db->setQuery($q);

						$this->_db->Query();

						echo $this->_db->getErrorMsg( );		

				}

			}

			$q = "delete from #__wh_termek where id = '{$termek_id}' ";

			$this->_db->setQuery($q);

			$this->_db->Query();

		}

		//die;

	}



	function klonoz( $termek_id ){
		$o = $this->getObj("#__wh_termek", $termek_id);
		unset( $o->id );
		$this->_db->insertObject("#__wh_termek", $o, "id");
		$uj_id = $this->_db->insertId();
		//termvar
		$table = "#__wh_termekvariacio";
		$unset = true;
		$q = "select * from {$table} where termek_id = {$termek_id} ";
		$this->_db->setQuery($q);
		foreach($this->_db->loadObjectList( ) as $a ){
			if($unset)unset($a->id);
			$a->termek_id = $uj_id;
			$this->_db->insertObject( $table, $a, "id" );
		}
		echo $this->_db->getErrorMsg();				
		//cimkek
		$table = "#__wh_cimke_kapcsolo";
		$unset = false;
		$q = "select * from {$table} where termek_id = {$termek_id} ";
		$this->_db->setQuery($q);
		foreach($this->_db->loadObjectList( ) as $a ){
			if($unset)unset($a->id);
			$a->termek_id = $uj_id;
			$this->_db->insertObject( $table, $a, "id" );
		}
		echo $this->_db->getErrorMsg();				
		$table = "#__wh_ar";
		$unset = true;
		$q = "select * from {$table} where termek_id = {$termek_id} ";
		$this->_db->setQuery($q);
		foreach($this->_db->loadObjectList( ) as $a ){
			if($unset)unset($a->id);
			$a->termek_id = $uj_id;
			$this->_db->insertObject( $table, $a, "id" );
		}
		echo $this->_db->getErrorMsg();				
		//kampany
		$table = "#__wh_kampany_kapcsolo";
		$unset = false;
		$q = "select * from {$table} where termek_id = {$termek_id} ";
		$this->_db->setQuery($q);
		foreach($this->_db->loadObjectList( ) as $a ){
			if($unset)unset($a->id);
			$a->termek_id = $uj_id;
			$this->_db->insertObject( $table, $a, "id" );
		}
		echo $this->_db->getErrorMsg();				
		//kapcsolodo termekek
		$table = "#__wh_ktermek_kapcsolo";
		$unset = true;
		$q = "select * from {$table} where termek_id = {$termek_id} ";
		$this->_db->setQuery($q);
		foreach($this->_db->loadObjectList( ) as $a ){
			if($unset)unset($a->id);
			$a->termek_id = $uj_id;
			$this->_db->insertObject( $table, $a, "id" );
		}
		echo $this->_db->getErrorMsg();				
		//kieg termekek
		$table = "#__wh_kiegtermek_kapcsolo";
		$unset = true;
		$q = "select * from {$table} where termek_id = {$termek_id} ";
		$this->_db->setQuery($q);
		foreach($this->_db->loadObjectList( ) as $a ){
			if($unset)unset($a->id);
			$a->termek_id = $uj_id;
			$this->_db->insertObject( $table, $a, "id" );
		}
		echo $this->_db->getErrorMsg();		

		$table = "#__wh_parh_kat_kapcs";
		$unset = true;
		$q = "select * from {$table} where termek_id = {$termek_id} ";
		$this->_db->setQuery($q);
		foreach($this->_db->loadObjectList( ) as $a ){
			if($unset)unset($a->id);
			$a->termek_id = $uj_id;
			$this->_db->insertObject( $table, $a, "id" );
		}
		echo $this->_db->getErrorMsg();		
		
		$table = "#__wh_kep";
		$unset = true;
		$q = "select * from {$table} where termek_id = {$termek_id} order by sorrend, id ";
		$this->_db->setQuery($q);
		foreach($this->_db->loadObjectList( ) as $a ){
			$regiKepId = $a->id;
			if($unset)unset($a->id);
			$a->termek_id = $uj_id;
			$this->_db->insertObject( $table, $a, "id" );
			$ujKepId = $this->_db->insertId( );
			$forraskep = "media/termekek/{$regiKepId}.jpg";
			$celkep = "media/termekek/{$ujKepId}.jpg";			
			copy($forraskep, $celkep );
		}
		$table = "#__wh_fajl";
		$unset = true;
		$q = "select * from {$table} where kapcsolo_id = {$termek_id} and kapcsoloNev = 'termek_id' ";
		$this->_db->setQuery($q);
		foreach($this->_db->loadObjectList( ) as $a ){
			$regiFajlnev = $a->fajlnev.".".$a->ext;
			if($unset)unset($a->id);
			$a->kapcsolo_id = $uj_id;
			$a->fajlnev = md5( time() );
			$this->_db->insertObject( $table, $a, "id" );
			$forrasFajl = "media/termekfajlok/{$regiFajlnev}";
			$celFajl = "media/termekfajlok/{$a->fajlnev}.{$a->ext}";			
			copy($forrasFajl, $celFajl );
		}
		echo $this->_db->getErrorMsg();		
		return $uj_id;
	}

	

	function mentUzletKapcsolo(){

		$idArr = JREquest::getVaR("idArr", array(), "array");	

		$uzletKapcsoloArr = JREquest::getVaR("uzlet_kapcsolo", array(), "array");	

		foreach($idArr as $id){

			$ind = array_search($id, $idArr);

			($uzletKapcsoloArr[$ind]==1) ? $allapot = "igen" :  $allapot = "nem";

			$o = "";

			$o->id = $id;

			$o->uzlet_kapcsolo = $allapot;

			$this->_db->updateObject("#__wh_termek", $o, "id");

		}

	}

	

	function setUzletKapcsolo($item){

		ob_start();		

		//$id = "";"<input id=\"aktiv\" name=\"\"> <input {$cjhecked} name=\"chk_aktiv[]\" value >";

		$ind = array_search($item, $this->_data);

		$idCheck = "uzlet{$ind}";

		$id = "uzletKapcsolo{$item->id}";

		$js = "changeVal( $('{$id}'),this )";

		if( $item->uzlet_kapcsolo == "igen" ) {

			$checked = "checked=\"checked\"";

			$value = 1;

		}else{

			$value = 2;			

			$checked = "";

		}

		?>

        <input name="uzlet_kapcsolo[]" id="<?php echo $id ?>" value="<?php echo $value ?>" type="hidden"  />

        <input name=""  type="checkbox" id="<?php echo $idCheck ?>" onclick="<?php echo $js ?>" <?php echo $checked ?> />

        <?php

		$k = ob_get_contents();

		ob_end_clean();

		$item->uzletKapcsolo = $k;

		return $item;	

	}	



	function setUzlet($item){

		$item->uzlet = "";

		//print_r( $this->kampanyok );

		//echo "****";

		//die;

		$q = "select uzlet_id from #__wh_uzlet_kapcsolo where termek_id = {$item->id}";

		$this->_db->setQuery($q);

		$value = $this->_db->loadResultArray();

		$name = "uzletIdArr_{$item->id}[]";

		$item->uzlet .= $item->uzletKapcsolo; 

		$item->uzlet .= JHTML::_('Select.genericlist', $this->uzletek, $name, array("multiple"=>"multiple", "class"=>"{$name}_ alapinput"), "value", "option", $value);

		

		return $item;

	}	

	

	function mentUzlet(){

		$idArr = JREquest::getVaR( "idArr", array(), "array" );

		//print_r($idArr);

		//die;

		foreach($idArr as $termek_id){

			$ind = array_search($termek_id, $idArr);

			$n__ = "uzletIdArr_{$termek_id}";

			$uzletIdArr = JREquest::getVaR( $n__, array(), "array" );					

			//echo $n__."<br />"; 

			//print_r($uzletIdArr);

			$q = "delete from #__wh_uzlet_kapcsolo where termek_id = {$termek_id}";

			$this->_db->setQuery($q);

			$this->_db->Query();

			foreach($uzletIdArr as $uzlet_id){

				$o = "";

				$o->termek_id = $termek_id;

				$o->uzlet_id = $uzlet_id;

				$this->_db->insertObject("#__wh_uzlet_kapcsolo", $o, "" );				

			}

		}

	}

	

	function initSpecTermVar(){

		//$q = "select ";ű

		$ret = array();

		foreach( $this->specMezoIdArr as $mezo_id ){

			$o = $this->getObj("#__wh_msablonmezo", $mezo_id);

			$ret[]=$o;

		}

		$this->specTermVarArr = $ret;

		$q = "select msablonmezo_id from #__wh_msablonmezo_kapcsolo where msablon_id = 1 ";

		$this->_db->setQuery($q);

		$this->msablonMezoIdArr = $this->_db->loadResultArray();

	}



	function mentSpecTermVar(){

		$idArr = JREquest::getVaR( "idArr", array(), "array" );

		foreach($this->specMezoIdArr as $mezo_id){

			$v = "mezoidArr{$mezo_id}";

			$$v = JREquest::getVaR( "mezoid_{$mezo_id}", array(), "array" );

		}

		

		//$kategoriaArr = JREquest::getVaR( "kategoria_id", array(), "array" );

		foreach( $idArr as $id ){

			$ind = array_search( $id, $idArr );

			//$v = "mezoid_{$a->id}";

			$q = "select * from #__wh_termekvariacio where termek_id = {$id} ";

			$this->_db->setQuery($q);

			$termekvariaciok = $this->_db->loadObjectList();

			foreach($this->specMezoIdArr as $mezo_id){

				//echo $specErtek."<br />";

			}

				foreach($termekvariaciok as $tv ){

					$ertek = "&";

					parse_str( $tv->ertek );

					foreach( $this->msablonMezoIdArr as $mezo_id ){

						if( in_array($mezo_id, $this->specMezoIdArr) ){

							$v = "mezoidArr{$mezo_id}";

							$tmp = $$v;

							@$specErtek = $tmp[$ind];

							$ertek .= "mezoid_{$mezo_id}={$specErtek}&";

						}else{

							$vEredeti = "mezoid_{$mezo_id}";							

							@$ertek .= "mezoid_{$mezo_id}=".$$vEredeti."&";

						}

					}

					//echo $ertek." *****************------------<br />";

					$tv->ertek = $ertek;

					$this->_db->updateObject( "#__wh_termekvariacio", $tv, "id" );

				}

		

		}

		//die;

	}



	function initKategoriafa(){

		$this->kategoriafa = new kategoriafa( array(), 5000, $this->getSzuloKategoria()->id);

		$o="";

		$o->option = $o-> value = "";

		array_unshift($this->kategoriafa->catTree, $o);

	}



	function setSpecTermVar($item){

		$ret = "";

		$q = "select * from #__wh_termekvariacio where termek_id = {$item->id} limit 1 ";

		$this->_db->setQuery($q);

		$tv = $this->_db->loadObject();

		//print_r($tv);

		foreach( $this->specTermVarArr as $a ){

			$v = "mezoid_{$a->id}";

			$$v = "";

		}

		@parse_str( $tv->ertek );

		foreach( $this->specTermVarArr as $a ){

			$ret .= $a->nev.": ";

			$arr_ = array();

			foreach(explode("\n", $a->leiras) as $a_){

				$o="";

				$o->value = $o->option = trim($a_);

				$arr_[]=$o;

			}

			$o="";

			$o->option = $o-> value = "";

			array_unshift($arr_, $o);			

			$name = "mezoid_{$a->id}[]";

			$vv = "mezoid_{$a->id}";

			$value = $$vv;

			$ret .= JHTML::_('Select.genericlist', $arr_, $name, array("class"=>""), "value", "option", $value )."<br />";

		}

		$item->specTermVar = $ret;

		return $item;

	}



	function setKategoria($item){

		$ret = "";

		$ret .= ""; 

		$value = $item->kategoria_id;

		$name = "kategoria_id[]";

		$ret .= JHTML::_('Select.genericlist', $this->kategoriafa->catTree, $name, array("class"=>""), "value", "option", $value);

		$item->kategoria = $ret;

		return $item;

	}



	function initUzletek(){

		$q = "select id as `value`, nev as `option` from #__wh_uzlet order by nev ";

		$this->_db->setQuery($q);

		$this->uzletek = $this->_db->loadObjectList();

		$o = "";

		$o->option = $o->value = "";

		array_unshift($this->uzletek, $o);

	}



	function initKampanyok(){

		$q = "select webshop_id from #__wh_kampany where aktiv = 'igen' group by webshop_id ";

		$this->_db->setQuery($q);

		$this->kampanyok = array();

		foreach($this->_db->loadObjectList() as $a){

			$q = "select * from #__wh_kampany where aktiv = 'igen' and webshop_id = {$a->webshop_id} ";

			$this->_db->setQuery($q);

			$this->kampanyok[] = $this->_db->loadObjectList();			

		}

		//print_r($this->kampanyok);

		//die;

	}

	

	function mentKategoria(){

		/*

		$idArr = JREquest::getVaR("idArr", array(), "array");	

		$kategoriaArr = JREquest::getVaR("kategoria_id", array(), "array");	

		foreach($idArr as $id){

			$ind = array_search($id, $idArr);

			@$kategoria_id  = $kategoriaArr[$ind];

			$o = "";

			$o->id = $id;

			$o->kategoria_id = $kategoria_id;

			$this->_db->updateObject("#__wh_termek", $o, "id");

		}

		*/

	}



	function mentAktivAllapot(){

		$idArr = JREquest::getVaR("idArr", array(), "array");	

		$aktivkapcsoloArr = JREquest::getVaR("aktivkapcsolo", array(), "array");	

		foreach($idArr as $id){

			$ind = array_search($id, $idArr);

			($aktivkapcsoloArr[$ind]==1) ? $allapot = "igen" :  $allapot = "nem";

			$o = "";

			$o->id = $id;

			$o->aktiv = $allapot;

			$this->_db->updateObject("#__wh_termek", $o, "id");

		}

	}

	

	function setAktivKapcsolo($item){

		ob_start();		

		//$id = "";"<input id=\"aktiv\" name=\"\"> <input {$cjhecked} name=\"chk_aktiv[]\" value >";

		$ind = array_search($item, $this->_data);

		$idCheck = "aktiv{$ind}";

		$id = "aktivKapcsolo{$item->id}";

		$js = "changeVal( $('{$id}'),this )";

		if( $item->aktiv == "igen" ) {

			$checked = "checked=\"checked\"";

			$value = 1;

		}else{

			$value = 2;			

			$checked = "";

		}

		//echo $item->aktiv." ***";

		?>

        <input name="idArr[]" value="<?php echo $item->id ?>" type="hidden"  />

        <input name="aktivkapcsolo[]" id="<?php echo $id ?>" value="<?php echo $value ?>" type="hidden"  />

        <input name="" type="checkbox" id="<?php echo $idCheck ?>" onclick="<?php echo $js ?>" <?php echo $checked ?> id="" />

        <?php

		$k = ob_get_contents();

		ob_end_clean();

		$item->aktivKapcsolo = $k;

		return $item;	

	}	

	

	function setBeszarNull(){

		$query = $this->_buildQuery();

		$this->_db->setquery($query); 

		$rows = $this->_db->loadobjectlist();

		$termekidk = array();

		foreach ($rows as $row) {

			$termekidk[] = $row->id;

		}

		$termekidk = implode(',',$termekidk);

		$q = "update #__wh_termek_beszallito_ar as bszar set netto_ar = 0 where termek_id in ({$termekidk})";

		$this->_db->setquery($q);

		$this->_db->query();

		//echo $this->_db->getquery();

		//echo $this->_db->geterrormsg();

		$query = $this->_buildQuery();

		$this->_data = $this->_getList( $query, $this->limitstart, $this->limit );

	}

	

	function setEladArNull(){

		$query = $this->_buildQuery();

		$this->_db->setquery($query); 

		$rows = $this->_db->loadobjectlist();

		$termekidk = array();

		foreach ($rows as $row) {

			$termekidk[] = $row->id;

		}

		$termekidk = implode(',',$termekidk);

		$q = "update #__wh_ar as ar set ar.ar = 0 where termek_id in ({$termekidk})";

		$this->_db->setquery($q);

		$this->_db->query();

		//echo $this->_db->getquery();

		//echo $this->_db->geterrormsg();

	}

	

	function arazas(){

		$arazas_netto_ar = jrequest::getvar("arazas_netto_ar", 0);

		$arazas_szazalek = jrequest::getvar("arazas_szazalek", 0);

		$arazas_sw = jrequest::getvar("arazas_sw", "");

		//die($arazas_sw." ----");

		if($arazas_sw == "KISKER_AR"){//KISKER_AR

			if($arazas_szazalek){

				$this->getData();

				foreach($this->_data as $r){

					$arr = $r->kiskerArArrArazas;

					foreach( $arr as $a){

						$arO="";

						$arO->ar=$a->HIDDEN2*($arazas_szazalek/100+1);

						$arO->id=$a->arid;

						$this->_db->updateObject("#__wh_ar", $arO, "id");

					}

				}		

			}elseif($arazas_netto_ar){

				$this->getData();

				foreach($this->_data as $r){

					$arr = $r->kiskerArArrArazas;

					foreach( $arr as $a){

						$arO="";

						$arO->ar=$a->HIDDEN2+$arazas_netto_ar;

						$arO->id=$a->arid;

						$this->_db->updateObject("#__wh_ar", $arO, "id");

					}

				}		

			}

		}else{//BESZALLITOI_AR

			$beszallitoArIdArr = jrequest::getVar("beszallitoArIdArr", array() );

			$beszallitoArArr = jrequest::getVar("beszallitoArArr", array() );			

			if($arazas_szazalek){

				foreach($beszallitoArIdArr as $beszallitoArId){

					$ind = array_search($beszallitoArId, $beszallitoArIdArr);

					$ar = $beszallitoArArr[$ind];

					$arO="";

					$arO->id = $beszallitoArId;

					//$arO->ar=$a->HIDDEN2*($arazas_szazalek/100+1);

					$arO->netto_ar = $ar*($arazas_szazalek/100+1);

					$this->_db->updateObject("#__wh_termek_beszallito_ar", $arO, "id");					

				}

			}elseif($arazas_netto_ar){

				foreach($beszallitoArIdArr as $beszallitoArId){

					$ind = array_search($beszallitoArId, $beszallitoArIdArr);

					$ar = $beszallitoArArr[$ind];

					$arO="";

					$arO->id = $beszallitoArId;

					//$arO->ar=$a->HIDDEN2*($arazas_szazalek/100+1);

					$arO->netto_ar = $ar+$arazas_netto_ar;

					//echo "{$beszallitoArId}: {$arO->netto_ar}<br /><br />";

					//echo $this->_db->updateObject("#__wh_termek_beszallito_ar", $arO, "id")." * * ** * * * <br />";					

				}

				//die("------");

			}

		}

	}



	function getAtarazas(){

		ob_start();

		?>

        <div class="div_table_search">

            <table class="table_search" border="0" cellpadding="0" cellspacing="0">

                  <tr>

                    <td class="butto_netto">{0}<div class="clr"></div></td>

                    <td class="afa_ertek">{1}<div class="clr"></div></td>

                    <td class="kisker_nagyker">{2}<div class="clr"></div></td>                    

                    <td class="td_serach_submit" colspan="3">{arazas}<div class="clr"></div></td>

                    

                  <tr>

             </table><div class="clr"></div>

         </div>

		<?php

		$ret = ob_get_contents();

		$arr = array();

		$o="";

		$arazas_netto_ar = $this->getSessionVar("arazas_netto_ar");

		$o->ARAZAS_OSSZEGRE = $this->getNettoBruttoInput("arazas_netto_ar", "arazas_brutto_ar", $arazas_netto_ar, 25, "", "", "" );

		$arr[] = $o;

		$o="";

		$arazas_szazalek = $this->getSessionVar("arazas_szazalek");

		$o->ARAZAS_SZAZLEKRA = "<input name=\"arazas_szazalek\" id=\"arazas_szazalek\" value=\"{$arazas_szazalek}\" />";

		$arr[] = $o;



		$o="";

		$arazas_sw = $this->getSessionVar("arazas_sw");

		(!$arazas_sw) ? $arazas_sw="KISKER_AR" : $arazas_sw;

		$html = "";

		foreach(array("KISKER_AR", "BESZALLITOI_AR") as $a){

			($a == $arazas_sw) ? $checked = "checked=\"checked\"" : $checked = "";

			$html.= "<input class=\"arazas_kapcsolo\" type=\"radio\" value=\"{$a}\" name=\"arazas_sw\" {$checked} >".jtext::_($a);

		}

		$o->KAPCSOLO = $html;

		$arr[] = $o;



		foreach($arr as $a){

			$ind = array_search($a, $arr);

			foreach($a as $oszlnev => $ertek){

				$e = "<span class=\"search_nev\">".JText::_("$oszlnev")."</span>{$ertek}";

				$ret = str_replace("{{$ind}}",$e,$ret);

			}

		}

		$arazas_ = "<a class=\"btn_lista_big\" onclick=\"$('task').value='arazas'; document.getElementById('adminForm').submit();return false;\" href=\"#\">".JText::_('ARAK MENTESE')."  </a>";

		$ret = str_replace("{arazas}", $arazas_, $ret);

		ob_end_clean();

		return $ret;	

	}



	function kivalaszt_rendeleshez($cid, $rendeles_id ){

		$idK= implode(",", $cid);

		$rendeles = $this->getObj("#__wh_rendeles", $rendeles_id);

		

		$q = "select * from #__wh_termek where id  in ({$idK})";

		$this->_db->setQuery($q);

		foreach($this->_db->loadObjectList() as $t){

			$tetel = "";

			$q = "select ar, ertek as afa_ from #__wh_ar as ar left join #__wh_afa as afa on ar.afa_id = afa.id

			where ar.termek_id = {$t->id} and webshop_id = {$rendeles->webshop_id}";

			$this->_db->setQuery($q);

			$ar = $this->_db->loadObject();			

			$tetel->rendeles_id = $rendeles_id;

			$tetel->cikkszam = $t->cikkszam;

			$tetel->termek_id = $t->id;

			$tetel->nev = $t->nev;

			$tetel->quantity = 1;

			$tetel->netto_ar = $ar->ar;

			$tetel->afa = $ar->afa_;

			//print_r($tetel);

			$this->_db->insertObject("#__wh_tetel", $tetel, "id");			

			//echo $t->id."<br />";

		}

	}

	

	function mentBeszallitoAr(){

		$beszallitoArIdArr = JREquest::getVaR("beszallitoArIdArr", array(), "array");	

		$beszallitoArArr = JREquest::getVaR("beszallitoArArr", array(), "array");	

		foreach($beszallitoArIdArr as $id){

			$ind = array_search($id, $beszallitoArIdArr);

			$ar = $beszallitoArArr[$ind];

			//$afa_id = $afa_id_beszallito_ar[$ind];		

			$o = "";

			$o->id = $id;

			$o->netto_ar = $ar;

			//$o->afa_id = $afa_id;

			//print_r($o);

			//die;

			$this->_db->updateObject("#__wh_termek_beszallito_ar", $o, "id");

		}

	}



	function setBeszallitoInput($item){

		if( $cond_beszallito_id = jrequest::getVar("cond_beszallito_id", 0) ){

			$cond = "where t.id = {$item->id} and bsz.id = {$cond_beszallito_id} ";

		}else{

			$cond = "where t.id = {$item->id} ";

		}

		$q = "SELECT bszAr.*, bsz.nev, afaT.ertek as bsz_afa_ from #__wh_termek as t 

		inner join #__wh_termek_beszallito_ar as bszAr on t.id = bszAr.termek_id 

		inner join #__wh_beszallito as bsz on bszAr.beszallito_id = bsz.id 

		inner join #__wh_afa as afaT on bsz.afa_id = afaT.id

		{$cond} order by bszAr.netto_ar asc limit 200";

		$this->_db->setQuery($q);

		

		$rows = $this->_db->loadObjectList();

		echo $this->_db->getErrorMsg();

		$arr = array();

		$arr2 = array();		

		foreach($rows as $r){

			if($r->netto_ar <> 0){

				$arr[] = $r;

			}else{

				$arr2[] = $r;

			}

		}

		$rows = array_merge($arr, $arr2);

		echo $this->_db->geterrorMsg();		

		//echo $this->_db->getQuery();				

		//die;

		$arr = array();

		$arrBuborek=array();

		if(count($rows)){

			$i=0;

			foreach($rows as $r){

				if($i < 2){

					$o="";

					$o->HIDDEN = $r->nev;

					$o->HIDDEN2 = $this->getNettoBruttoInput("beszallitoArArr", "beszbruttoar", $r->netto_ar, $r->bsz_afa_, "idbesz{$r->id}", "[]", "<br>" );

					$o->HIDDEN2.="<input type=\"hidden\" name=\"beszallitoArIdArr[]\" value=\"{$r->id}\" />";

					$arr[] = $o;

					$i++;

				}else{

					$o="";					

					$o->BESZALLITTO = $r->nev;

					$o->NETTO = ar::_( $r->netto_ar );

					$o->BRUTTO = ar::_( $r->netto_ar * (1+$r->bsz_afa_/100) );					

					$o->AFA = $r->bsz_afa_;

					$arrBuborek[] = $o;

					$i++;

				}

			}

			$lista = new listazo($arr, "egyszeru", "", "", array(), 2);

			$item->beszallitoInput =  $lista->getLista();

		}else{

			$item->beszallitoInput = "";

		}

		if(count($arrBuborek)){

			$listaB = new listazo($arrBuborek, "table_bub");

			//echo $listaB->getLista()."<br />";

			//die;

			$item->beszallitoBuborek = "<span class=\"zoomTip\" title='".$listaB->getLista()."'>".jtext::_("TOVABBI_BESZALLITOK")."</span>";

		

		}

		return $item;

	}



	function setBeszallitoInput_________($item){

		if( $cond_beszallito_id = jrequest::getVar("cond_beszallito_id", 0) ){

			$cond = "where t.id = {$item->id} and bsz.id = {$cond_beszallito_id} ";

		}else{

			$cond = "where t.id = {$item->id} ";

		}

		$q = "SELECT bszAr.*, bsz.nev from #__wh_termek as t 

		inner join #__wh_termek_beszallito_ar as bszAr on t.id = bszAr.termek_id 

		inner join #__wh_beszallito as bsz on bszAr.beszallito_id = bsz.id 

		{$cond} order by bszAr.netto_ar asc limit 2";

		$this->_db->setQuery($q);

		$rows = $this->_db->loadObjectList();

		echo $this->_db->geterrorMsg();		

		//echo $this->_db->getQuery();				

		//die;

		$arr = array();

		if(count($rows)){

			foreach($rows as $r){

				$o="";

				$o->HIDDEN = $r->nev;

				//$o->HIDDEN2 = "<input name=\"beszallitoArArr[]\" value=\"{$r->netto_ar}\" type=\"text\"><input name=\"beszallitoArIdArr[]\" value=\"{$r->id}\" type=\"hidden\">";



				$o->HIDDEN2 = $this->getNettoBruttoInput("beszallitoArArr", "beszbruttoar", $r->netto_ar, 25, "idbesz{$r->id}", "[]", "<br>" );

				$o->HIDDEN2.="<input type=\"hidden\" name=\"beszallitoArIdArr[]\" value=\"{$r->id}\" />";

				$arr[] = $o;

			}

			$lista = new listazo($arr, "egyszeru", "", "", array(), 2);

			$item->beszallitoInput =  $lista->getLista();

		}else{

			$item->beszallitoInput = "";

		}

		return $item;

	}

	

	function getMinArIdk(){

		$q = "select aT.id from #__wh_ar as aT where aT.ar > 0 group by aT.termek_id having min(aT.ar)";

		$this->_db->setQuery($q);

		$arIdArr = $this->_db->loadResultArray();

		//print_r( $arIdArr );

		$arIdArr = implode(",", $arIdArr);

		return $arIdArr;

	}



	function getSpecialisSzuresCond($val){

		switch ($val){

			case "MAX 50 FT-TAL CSUSZUNK AZ ELSOSEGROL" :

				$minArIdArr = $this->getMinArIdk( );

				//$q = "select kT.termek_id, aT.*, afaT.ertek from #__wh_konkurencia_ar as kT

				$q = "select kT.termek_id from #__wh_konkurencia_ar as kT				

				inner join #__wh_ar as aT on aT.termek_id = kT.termek_id 

				inner join #__wh_afa as afaT on aT.afa_id = afaT.id 

				where (aT.ar*(afaT.ertek/100+1) - kT.ar ) between 0 and 50 

				and aT.id in( {$minArIdArr} )

				";

				/* group by kT.termek_id having min(kT.ar) */

				$this->_db->setQuery($q);

				$arrId = $this->_db->loadResultArray();

				$arrId = implode(",", $arrId );

				($arrId) ? $arrId : $arrId="0";					

				echo $this->_db->geterrorMsg();

				//die;

				$cond = "t.id in ( {$arrId} ) and ";

				break;

			

			case "MAX 100 FT-TAL CSUSZUNK AZ ELSOSEGROL" :

				$minArIdArr = $this->getMinArIdk( );

				//$q = "select kT.termek_id, aT.*, afaT.ertek from #__wh_konkurencia_ar as kT

				$q = "select kT.termek_id from #__wh_konkurencia_ar as kT				

				inner join #__wh_ar as aT on aT.termek_id = kT.termek_id 

				inner join #__wh_afa as afaT on aT.afa_id = afaT.id 

				where (aT.ar*(afaT.ertek/100+1) - kT.ar ) between 0 and 100

				and aT.id in( {$minArIdArr} )

				";

				/* group by kT.termek_id having min(kT.ar) */

				$this->_db->setQuery($q);

				$arrId = $this->_db->loadResultArray();

				$arrId = implode(",", $arrId );

				($arrId) ? $arrId : $arrId="0";					

				echo $this->_db->geterrorMsg();

				//die;

				$cond = "t.id in ( {$arrId} ) and ";

				break;

				

			case "TOBBET KRESHETNENK RAJTA 500FT" :

				$minArIdArr = $this->getMinArIdk( );

				$q = "select kT.* from #__wh_konkurencia_ar as kT				

				inner join #__wh_ar as aT on aT.termek_id = kT.termek_id 

				inner join #__wh_afa as afaT on aT.afa_id = afaT.id 

				where ( aT.ar*(afaT.ertek/100+1) ) = kT.ar

				and aT.ar > 0

				and aT.id in( {$minArIdArr} )

				";

				$this->_db->setQuery($q);

				$arr = $this->_db->loadObjectList();

				$arrId = array();

				foreach( $arr as $a ){

					$konkurenciaArak = unserialize($a->arak);

					$sajatAr = $konkurenciaArak[0];					

					foreach($konkurenciaArak as $kAr){

						if ( $kAr <> $sajatAr ){

							$masodikAr = $kAr;

							break;

						}

					}

					

					if( ($masodikAr - $sajatAr) > 500 ){

						$arrId[] = $a->termek_id;

					}

					

				}

				echo $this->_db->geterrorMsg();

				$arrId = implode(",", $arrId );

				($arrId) ? $arrId : $arrId="0";

				$cond = "t.id in ( {$arrId} ) and ";				

				break;

			case "TOBBET KRESHETNENK RAJTA 1000FT" :

				break;



			case "KONKURENCIANAL_OLCSOBB":

				$q = "select ar, termek_id from #__wh_ar as arT 

				group by arT.termek_id having min(arT.ar)

				";

				$this->_db->setQuery($q);

				$arrId = array();

				//print_r($this->_db->loadObjectList());

				foreach($this->_db->loadObjectList() as $o){

					//echo "----";

					$q = "select termek_id from #__wh_konkurencia_ar where termek_id = {$o->termek_id} and ar < {$o->ar} and ar <> 0 and ar <> '' ";

					$this->_db->setQuery($q);

					if($termek_id = $this->_db->loadResult()){

						$arrId[] = $termek_id;

					}

				}

				echo $this->_db->geterrorMsg();	

				$arrId = implode(",", $arrId );

				($arrId) ? $arrId : $arrId="0";

				$cond = "t.id in ( {$arrId} ) and ";

				//echo $cond;

				break;

			

			case "VESZTESEGES_TERMEKEK" :

				if($kategoria_id = JREquest::getVar("cond_kategoria_id", "")){

					$kArr = implode(",", $this->getLftRgtOsszes( $kategoria_id, "#__wh_kategoria" ) );

					$q = "select id from #__wh_termek where kategoria_id in ({$kArr})";

					$this->_db->setQuery($q);

					$arrId=array();

					foreach($this->_db->loadResultArray() as $id){

						$haszonObj=$this->getHaszonObj($id);

						if( @$haszonObj->haszon<0 ){

							$arrId[] = $id ;

						}

					}

					$arrId = implode(",", $arrId );

					($arrId) ? $arrId : $arrId="0";					

					$cond = "t.id in ( {$arrId} ) and ";

				}else{

					$cond = "";

				}

				break;

			

					

			case "BESZALLITOI AR 0" :

				

				$cond = "bszar.netto_ar = 0 and ";

				

				break;

			

			case "BESZALLITOI AR NEM 0" :

				

				$cond = "bszar.netto_ar != 0 and ";

				

				break;

			

			case "ELADASI AR 0" :

				

				$cond = "ar.ar = 0 and ";

				

				break;

			

			case "ELADASI AR NEM 0" :

				

				$cond = "ar.ar != 0 and ";

				

				break;			

			

			

			case "NINCS_MEG_AZ_ELVART_NYERESEG" :

				if($kategoria_id = JREquest::getVar("cond_kategoria_id", "")){

					$kArr = implode(",", $this->getLftRgtOsszes($kategoria_id, "#__wh_kategoria" ));

					$q = "select id from #__wh_termek where kategoria_id in ({$kArr})";

					$this->_db->setQuery($q);

					$arrId=array();

					foreach($this->_db->loadResultArray() as $id){

						$haszonObj=$this->getHaszonObj($id);

						//print_r($haszonObj);

						if( $haszonObj->tenylegesSzazalek < $haszonObj->elvartSzazalek ){

							$arrId[] = $id ;

							//print_r($haszonObj);

							//die;

						}

					}

					$arrId = implode(",", $arrId );		

					$cond = "t.id in ( {$arrId} ) and ";

					//echo $cond;

				}else{

					$cond = "";

				}

				break;

			default :

				$cond = "";

		}

		return $cond;

	}



	function setHaszon($item){

		$ajaxId = "AjaxContentHaszon_{$item->id}";

		//$this->document->addScriptDeclaration("window.addEvent(\"domready\", function(){setHaszonAjax( '', '', '', '{$item->id}' );});");				

		$item->haszon = "";

		$item->haszon .= "<div id=\"{$ajaxId}\">";

		$item->haszon .= $this->getHaszonHTML( $this->getHaszonObj($item->id) ) ;

		$item->haszon .= "</div>";

		

		//print_r($item->haszonObj);

		return $item;

	}



	function setHaszonAjax(){

		//&format=raw&netto_ar="+netto_ar+"&afa="+afa+"&arId="+arId;

		ob_start();

		$netto_ar = jrequest::getVar("netto_ar","");

		$afa = jrequest::getVar("afa","");

		$arId = jrequest::getVar("arId", "");

		$termek_id = jrequest::getVar("termek_id","");

		//$netto_ar = jrequest::getVar("netto_ar");						

		if( $arId !="" ){

			$brutto_ar = $netto_ar*(1+$afa/100);			

			$arId = str_replace( "ar", "", $arId );

			echo $this->getHaszonHTML( $this->getHaszonObjDinamikus( $termek_id, $brutto_ar ) );

		}else{

			echo $this->getHaszonHTML( $this->getHaszonObj( $termek_id) );

		}

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;

	}



	function getHaszonHTML( $hObj ){

		if($hObj){

			foreach($hObj as $e => $v){

				$e =strtoupper($e); 

				if($e == "HASZONSZAZALEK"){

					$o->$e = number_format($v, 1, ",", " ");

				}else{

					$o->$e = $v;					

				}

			}

			$arr = array();

			$arr[] = $o;

			((int)$o->HASZON > 0) ? $class = "kislista" : $class = "kislista alert";

			$lista = new listazo($arr, $class, "", "" );

			$haszon =  $lista->getLista();

		}else{

			$haszon = jtext::_("NINCS ADAT");

		}

		return $haszon;

	}



	function getSearchArr(){

		$arr = array();



		$obj = "";

		$name = "cond_termeknev";

		$value = JRequest::getVar($name);

		$obj->TERMEKS = "<input name=\"{$name}\" id=\"{$name}\" value=\"{$value}\" />";

		$arr[] = $obj;



		$obj = "";		

		$name = "cond_kategoria_id";

		$value = JRequest::getVar($name);

		$kategoriafa = new kategoriafa(array(0), 5000, $this->getSzuloKategoria()->id );

		$o="";

		$o->value = $o->option = "";

		array_unshift($kategoriafa ->catTree, $o);

		$obj->KATEGORIAS = JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name, array( "class"=>"alapinput" ), "value", "option", $value);

		$arr[] = $obj;

		



		$obj = "";	

		$name = "cond_spec2";

		$value = JRequest::getVar($name, array(), "request", "array");

		$obj->SPEC_SZURES2 = $this->getSearchCheckboxes($name, $this->xmlParser->getxmlarr('cond_spec2'), $value );

		$arr[] = $obj;

		

		$obj = "";		

		$q = "select id as `value`, nev as `option` from #__wh_kampany order by nev";

		$this->_db->setQuery($q);

		$name = "cond_kampany_id";

		$value = JRequest::getVar($name);

		$rows = $this->_db->loadObjectList();		

		$o="";

		$o->value = $o->option = "";

		array_unshift($rows, $o);		

		$obj->KAMPANY_TH = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);

		$arr[] = $obj;



	/*	

		$obj = "";		

		$q = "select id as `value`, nev as `option` from #__wh_beszallito order by nev";

		$this->_db->setQuery($q);

		$name = "cond_beszallito_id";

		$value = JRequest::getVar($name);

		$rows = $this->_db->loadObjectList();		

		$o="";

		$o->value = $o->option = "";

		array_unshift($rows, $o);		

		$obj->BESZALLITOS = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);

		$arr[] = $obj;

*/



		$obj="";

		$name = "cond_specialis_szures";

		$value = JRequest::getVar($name);

		$rows = array();

		//NINCS_MEG_AZ_ELVART_NYERESEG

		foreach(array("VESZTESEGES_TERMEKEK",

					  "KONKURENCIANAL_OLCSOBB",

					  "MAX 50 FT-TAL CSUSZUNK AZ ELSOSEGROL",

					  "MAX 100 FT-TAL CSUSZUNK AZ ELSOSEGROL",

					  "TOBBET KRESHETNENK RAJTA 500FT",

					  "TOBBET KRESHETNENK RAJTA 1000FT",					  

					  "BESZALLITOI AR NEM 0",

					  "BESZALLITOI AR 0",

					  "ELADASI AR NEM 0",

					  "ELADASI AR 0",				  

					  

					  ) as $a){

			$o = "";

			if($a == "NINCS_MEG_AZ_ELVART_NYERESEG"){

				$o->value = $a;

				$o->option = jtext::_($a);

				$o->disabled = "disabled=\"disabled\"";

				

			}else{

				$o->value = $a;

				$o->option = jtext::_($a);

			}

			$rows[] = $o;

		}	

		$o="";

		$o->value = $o->option = "";

		array_unshift($rows, $o);		

		$obj->SPECIALIS_SZURES = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);

		//$arr[] = $obj;

/*

		$obj = "";		

		$q = "select id as `value`, nev as `option` from #__wh_gyarto group by id order by nev";

		

		$this->_db->setQuery($q);

		$name = "cond_gyarto_id";

		$value = JRequest::getVar($name);

		$rows = $this->_db->loadObjectList();		

		$o="";

		$o->value = $o->option = "";

		array_unshift($rows, $o);		

		$obj->GYARTOS = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);

		$arr[] = $obj;

*/

		return 	$arr;

	}





	function _buildQuery()
	{
		$cond = $this->getCond();
		$query = "SELECT t.*, k.nev as kategorianev 
		FROM #__wh_termek as t 
		left join #__wh_kategoria as k on t.kategoria_id = k.id
		{$cond} group by t.id ";
		// left join #__wh_ar as ar on t.id = ar.termek_id
		return $query;
	}

	function getData()
	{
		if (empty( $this->_data )){
			$this->initKampanyok();		
			$this->initKategoriafa();
			$this->initSpecTermVar();		
			$this->initUzletek();
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->limitstart, $this->limit );
			echo $this->_db->getErrorMsg();
			//die( $this->_db->getQuery() );	
			if(count($this->_data)){
				//array_map ( array($this, "setBeszallitoInput"), $this->_data) ;
				array_map ( array($this, "setElsokep"), $this->_data) ;
				//array_map ( array($this, "setKonkurenciaAr"), $this->_data) ;			
				array_map ( array($this, "setKiskerAr"), $this->_data) ;						
				//array_map ( array($this, "setHaszon"), $this->_data) ;			
				array_map ( array($this, "setKampany"), $this->_data) ;
				array_map ( array($this, "setUzletKapcsolo"), $this->_data) ;			
				array_map ( array($this, "setUzlet"), $this->_data) ;
				array_map ( array($this, "setAktivKapcsolo"), $this->_data) ;	
					
				array_map ( array($this, "setAllapotIkonok"), $this->_data) ;		
				array_map ( array($this, "setChecker"), $this->_data) ;	
				array_map ( array($this, "setArazoLite"), $this->_data) ;			
				//array_map ( array($this, "setKategoria"), $this->_data) ;
				//array_map ( array($this, "setSpecTermVar"), $this->_data) ;				
			}
		}
		//$this->_data = array_map(array($this,"propValue"), $rows);
		//print_r($this->_data);exit;
		return $this->_data;

	}//function

	function getTermekBoxok(){
		$rows = $this->getData();
		if (count($rows) > 0){
			//print_r($rows);
			jimport("unitemplate.unitemplate");
			$uniparams->cols = 1;
			$uniparams->cellspacing = 0;
			$uniparams->templatePath = "components/com_wh/unitpl";
			$uniparams->pair = false;
			$ut = new unitemplate("termeklista", $rows, "div", "termek_lista", $uniparams);
			$ret = $ut -> getContents(); 
		}else{
			$ret = "<div align=center>".JText::_("NINCS TALALAT")."</div>";			
		}
		return $ret;
	}
	
	function setAllapotIkonok($item){
		$ret = "";
		if ($item->keszlet > 0) {
			$title = Jtext::_("RAKTARON"). ": " . $item->keszlet." ".Jtext::_("Db");
			$ret .= "<span title=\"".$title."\" title=\"".$title."\" class=\"ikon_raktaron\">R</span>";
		}
		if ($item->aktiv != "nem") {
			$title = Jtext::_("A_TERMEK_AKTIV");
			$ret .= "<span title=\"".$title."\" title=\"".$title."\" class=\"ikon_aktiv\">A</span>";
		}
		$item->allapotIkonok = $ret;
	}
	function setChecker($item){
		$arr = $this->getData();
		$nr = array_search($item, $arr);
		$item->checker = JHTML::_('grid.id', $nr, $item->id );
		return $item;
	}
	function setArazoLite($item){
		$item->arazoLite = "itt lesznek az árak";
		return $item;
	}
	
	function getKategoriafa(){
		$kategoriafa = new kategoriafa( );
		return $kategoriafa;
	}

	function getTotal(){
		// Load the content if it doesn't already exist
		if ( empty( $this->_total ) )
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);	
		}
		return $this->_total;

	}//function

  

	function getPagination()

  {

 	// Load the content if it doesn't already exist

 	if (empty($this->_pagination))

 	{

 	    jimport('joomla.html.pagination');

 	    $this->_pagination = new JPagination($this->getTotal(), $this->limitstart, $this->limit );

 	}

 	return $this->_pagination;

  }//function





}// class

?>

