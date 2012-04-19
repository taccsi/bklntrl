<?php
defined( '_JEXEC' ) or die( '=;)' );
require_once( 'administrator/components/com_whp/helpers/termek.php' );
require_once( 'administrator/components/com_whp/helpers/rendeles.php' );
require_once( 'components/com_whp/models/rendeles.php' );

class whpModelkosar extends whpPublic{
	var $mindosszesen_netto = 0;
	var $mindosszesen_brutto = 0;

	function __construct(){
	 	parent::__construct();
		global $mainframe, $option;
		$this->xmlParser = new xmlTermek("termek.xml");
		$this->id = JRequest::getVar("id", "");
		$this->kosar = $this->getSessionVar("kosar");
		//print_r($this->kosar);		die;
		//$this->teszt();
		//die;
	}//function

	function teszt(){
		foreach($this->kosar as $k){
			print_r($k);
		}
	}

	function tetel_torol(){
		ob_start();	
		$error ='';
		//echo 'sdfs';
		try {
		   $tetel_id = urldecode(JREquest::getVar("tetel_id",""));
			//$error = $tetel_id;
			//echo $tetel_id; die('sdf');
			//print_r($this->kosar); die();
			//die($tetel_id.' --x');
			if( count($this->kosar) == 1 || @$this->kosar[$tetel_id]->szulo_termek_id === 0){
				$this->kosar = array();
			}else{
				unset($this->kosar[$tetel_id]);
			}
			//sort($this->kosar);
			//die($tetel_id." ******");
			$this->kosar = $this->getKosarSzallitasiKoltsegggel( $this->kosar );		
			$this->setSessionVar("kosar", $this->kosar ); 
		   
		   
		} catch (Exception $e) {
		    $error .= $e->getMessage();
		   
		}	
		
		$html = ob_get_contents();
		ob_end_clean();
		$ret ="";
		$ret->html = '';
		$ret->msg= jtext::_('TETEL_TOROLVE');
		$ret->error = $error;
		return $this->getJsonRet($ret);				
	}

	function tetel_modosit(){
		$tetel_id = urldecode(JREquest::getVar("tetel_id", ""));
		$mennyisegArr = JREquest::getVar("mennyisegArr", array() );
		$kosarKulcsArr = JREquest::getVar("kosarKulcsArr", array() );
		$ind = array_search( $tetel_id, $kosarKulcsArr );
		$mennyiseg = $mennyisegArr[$ind];
		if($mennyiseg <= 0){
			unset($this->kosar[$tetel_id]);
		}else{
			//die("* {$mennyiseg} - tetelid: {$tetel_id}");
			if ($this->kosar[$tetel_id]->me != 'm2'){
				$mennyiseg = (int)$mennyiseg;
			}
			$this->kosar[$tetel_id]->mennyiseg = $mennyiseg;
		}
		$this->kosar = $this->getKosarSzallitasiKoltsegggel( $this->kosar );
		$this->setSessionVar( "kosar", $this->kosar );
	}

	function getKosarKulcs( $termek_id, $termVarId=0, $kosar_index=0, $termek, $userEdit ){
		$termVarIdParam = $termVarId;
		if( $kosar_index ){
			$i=1;
			foreach( $this->kosar as $k => $v ){
				$kosarkulcs = $k;
				if( $i == $kosar_index ){
					parse_str( $k, $arr );
					if( $arr["termVarId"] != $termVarIdParam ){
						$str = "";
						foreach( $arr as $k => $v ){
							$v = ( $k=="termVarId" ) ? $termVarIdParam : $v;
							$str .= "{$k}={$v}&";
						}
						if ($userEdit){$str .= "useredit={$userEdit}&"; }	
						unset( $this->kosar[$kosarkulcs] );
						/*
						echo $kosarkulcs."<br />";
						echo $k."<br />";
						echo $str."<br />";						
						die;
						*/
						return $str;
					}
					return $k;
				}
				$i++;
			}
			//return "&termek_id={$termek_id}&termVarId={$termVarId}&mennyiseg_kosarba={$mennyiseg_kosarba}&kk={$kosar_index}&";
		} else{
			return "&termek_id={$termek_id}&termVarId={$termVarId}&useredit={$userEdit}&";
		}
	}

	function setFoglalasIdopont(){
		$msg = "";
		//die(" -----add-");
		$idopont_ertek = jrequest::getVar( "idopont" );
		$idopont = '';
		$idopont->nev =jtext::_('IDOPONT');
		$idopont->ertek = $idopont_ertek;
		$this->kosar['IDOPONT']=$idopont;
		
		//print_r($this->kosar); die();
	
		$this->setSessionVar("kosar", $this->kosar );
		
		$ret ="";
		$ret->html = '';
		$ret->message = jtext::_('KIVALASZTOTT_IDOPONT').$idopont->ertek.jtext::_('MODOSITASHOZ_VALASSZON_MASIKAT');
		$ret->error = "";
		return $this->getJsonRet($ret);
	}

	function addToBasket(){
		$msg = "";
		//die(" -----add-");
		$termek_id = jrequest::getInt( "termek_id" );
		$mennyiseg_kosarba = jrequest::getVar( "mennyiseg" );
		$userEdit = jrequest::getVar( "userEdit",'' );
		//die( "{$mennyiseg_kosarba} *****" );		
		$termek = $this->getTermek( $termek_id );
		
		if ($termek->basket_unique == 'igen'){
			foreach ($this->kosar as $key => $k){
				if (@$k->kategoria_id == $termek->kategoria_id){
					
					unset($this->kosar[$key]);
				}
			}
		}
		$termVarId = jrequest::getInt( "termVarId", 0);
		//die(''.$termek_id.' -- '.$termVarId);
		$kosar_index = jrequest::getVar( "kosar_index", "" );
		//die( "{$kosar_index} ** " );
		if($mennyiseg_kosarba > 0 ){
			if ($termek->szulo_termek_id == 0){
				$kosarKulcs = 'FOTERMEK';
			} else {
				$kosarKulcs = $this->getKosarKulcs($termek_id, $termVarId, $kosar_index, $termek, $userEdit );
			}
			
			//print_r($this->kosar); die($kosarKulcs.'xxxxx');
			if( count($this->kosar) ){
				if( array_key_exists($kosarKulcs, $this->kosar ) ){
						
					$this->kosar[$kosarKulcs]->mennyiseg = $mennyiseg_kosarba;			
				}else{
					$termek = $this->getModositottKosarTermek($termek, $kosarKulcs, $termVarId);					
					$termek->mennyiseg = $mennyiseg_kosarba;
					if ($userEdit){$termek->userEdit = $userEdit;$termek->nev .= ' ('.$userEdit.')';}	
					
					$this->kosar[$kosarKulcs] = $termek;
					
				}
			}else{
				$termek = $this->getModositottKosarTermek($termek, $kosarKulcs, $termVarId);				
				$termek->mennyiseg = $mennyiseg_kosarba;
				if ($userEdit){$termek->userEdit = $userEdit;$termek->nev .= ' ('.$userEdit.')';}	
				
				$this->kosar[$kosarKulcs] = $termek;
			}
		}else{
			//mennyiseg = 0
		}
		//print_r($this->kosar); die();
		
		array_map( array( $this, "setKosarKep" ), $this->kosar );  
		$this->kosar = $this->getKosarSzallitasiKoltsegggel( $this->kosar );
	
		$this->setSessionVar("kosar", $this->kosar );
		
		$ret ="";
		$ret->html = '';
		$ret->error = "";
		return $this->getJsonRet($ret);
	}
	function add(){
		$msg = "";
		//die(" -----add-");
		$termek_id = jrequest::getInt( "kosarba_id" );
		$mennyiseg_kosarba = jrequest::getVar( "mennyiseg_kosarba" );
		//die( "{$mennyiseg_kosarba} *****" );
		$kalkulaciosInformaciok = jrequest::getVar("kalkulaciosInformaciok");		
		$termek = $this->getTermek( $termek_id );
		$termVarId = jrequest::getInt( "termVarId", 0);
		//die(''.$termek_id.' -- '.$termVarId);
		$kosar_index = jrequest::getVar( "kosar_index", "" );
		//die( "{$kosar_index} ** " );
		
		if( $mennyiseg_kosarba > 0 ){
			$kosarKulcs = $this->getKosarKulcs( $termek_id, $termVarId, $kosar_index, $termek );
			if( count($this->kosar) ){
				if( array_key_exists( $kosarKulcs, $this->kosar ) ){
					$termek = $this->getModositottKosarTermek( $termek, $kosarKulcs, $termVarId, $kalkulaciosInformaciok );
					$termek->mennyiseg = $mennyiseg_kosarba;
					$this->kosar[$kosarKulcs] = $termek;
				}else{
					$termek = $this->getModositottKosarTermek($termek, $kosarKulcs, $termVarId, $kalkulaciosInformaciok);					
					$termek->mennyiseg = $mennyiseg_kosarba;
					$this->kosar[$kosarKulcs] = $termek;
				}
			}else{
				$termek = $this->getModositottKosarTermek($termek, $kosarKulcs, $termVarId, $kalkulaciosInformaciok);				
				$termek->mennyiseg = $mennyiseg_kosarba;
				$this->kosar[$kosarKulcs] = $termek;
			}
		}else{
			//mennyiseg = 0
		}
		//print_r($this->kosar); die();
		array_map( array( $this, "setKosarKep" ), $this->kosar );  
		$this->kosar = $this->getKosarSzallitasiKoltsegggel( $this->kosar );
	
		$this->setSessionVar("kosar", $this->kosar );
		return $msg;
	} 

	

	function setKosarKep( $item ){

		//print_r($item); 
		if (isset($item->id)){
			$w = 76;

			$h= 110;
	
			$mode = "crop";
	
			
	
			$q = "select id from #__wh_kep where termek_id = {$item->id} and listakep = 'igen' order by id limit 1";
	
			$this->_db->setquery($q);
	
			$kep_id = $this->_db->loadresult();
	
			
	
			
	
			if (!isset($kep_id)){
	
				$q = "select id from #__wh_kep where termek_id = {$item->id} order by sorrend asc limit 1";
	
				$this->_db->setquery($q);
	
				$kep_id = $this->_db->loadresult();
	
	
	
			}
	
			if (isset($kep_id)){
	
				$forras_kep = "admin/media/termekek/{$kep_id}.jpg";
	
			}
	
			
	
			//die($forras_kep);
	
			$class="zoom";
	
			$buborek_kep="";
	
			$alt=$item->nev;
	
			//$link = $forras_kep;
	
			$link ="javascript:;";
	
	
	
			$cel_kep=$this->getCelKepNev( $kep_id, $w, $h, $mode );	 
	
			
			if (@$forras_kep){
				$item->kosarKep = "<div> ".$this->xmlParser->image((string)$forras_kep, $cel_kep, $link, $w, $h, $mode, "class=\"\"", "", "") . "</div>";	
			} else {
				$item->kosarKep = "";				
			}
			
	
			
	
			return $item;
		}
	}

	

	function getCelKepNev($id, $w="", $h="", $mode="" ){

		$kepPrefix = "termek_";

		$dir_cel = "images/resized";



		($w)? $w : $w = $this->w;

		($h)? $h : $h = $this->h;

		($mode)? $mode : $mode = $this->mode;		

		( strstr( realpath("."), "administrator" ) ) ?  $x="../" : $x="";

		$dir = "{$x}{$dir_cel}/";

		return "{$dir}{$kepPrefix}{$id}_{$w}_{$h}_{$mode}.jpg";

   }

	function getKosarSzallitasiKoltsegggel($kosar){

		//print_r($kosar); die();

		$i=0;

		foreach($kosar as $k => $ko){

			if(@$ko->cikkszam == "SZALLITASI_KOLTSEG"){

				unset($kosar[ $k ]);

			}else{

				$i++;	

			}

		}

		//print_r();

		$o_ = $this->getSzallitasiDijObj($kosar);

		

			$o="";

			$o->TERMEK = jtext::_("SZALLITASI_KOLTSEG");

			//$o->OSSZESEN_NETTO = ar::_( $k->ar*$k->mennyiseg );

			$o->MENNYISEG = 1;

			$o->AR_NETTO = ar::_($o_->ar);

			$o->AR_BRUTTO = ar::_( ar::getBrutto( $o_->ar , $o_->afaErtek ) );

			$o->OSSZESEN_BRUTTO = ar::_(ar::getBrutto( $o_->ar ,$o_->afaErtek)*1 );

			

			//$kosar["SZALLITASI_KOLTSEG"] = $o_;

			//$this->mindosszesen_netto+=$o_->ar * 1;

			//$this->mindosszesen_brutto+=ar::getBrutto( $o_->ar*1 ,$o_->afaErtek);

		

		if(!$i){ //ha már csak a száll költség van a kosárban, ürítjük

			$this->kosar = $kosar=array();

		}

		

		return $kosar;

		//$arr = array_merge( $arr, $this->szallitasiKoltsegArr() ); 

	}



	function getKiskosar(){

		ob_start();

		$Itemid = $this->Itemid;

		$mindosszesen_netto=0;

		$mindosszesen_brutto=0;

		$osszmennyiseg = 0;

		$termekek_brutto = 0;

		$ossztermekfajta = 0;

		//print_r($this->kosar); 
		if( count($this->kosar) ){
			$ossztermeklista=array();
			foreach((array)$this->kosar as $kulcs => $k){
					//echo $kulcs.'<br>';
				if($kulcs !="SZALLITASI_KOLTSEG" && $kulcs != "KEDVEZMENY" && $kulcs != "IDOPONT" && $kulcs != "FOTERMEK"){
					//$netto_ar = ar::getKerekitettAr( $k->ar );
					///print_r($k);
					$netto_ar = $k->ar;					
					//$brutto_ar = ar::getKerekitettAr( ar::getBrutto( $k->ar ,$k->afaErtek));
					$brutto_ar = ar::getBrutto( $k->ar, $k->afaErtek);
					$mindosszesen_netto += $netto_ar * $k->mennyiseg;
					$termekek_brutto += $brutto_ar * $k->mennyiseg;
					$mindosszesen_brutto += $brutto_ar * $k->mennyiseg;
					$osszmennyiseg += $k->mennyiseg;
					$o = '';
					$o->nev = $k->nev;
					$o->brutto_ar = $brutto_ar;
					$o->mennyiseg = $k->mennyiseg;
					$o->kulcs = $kulcs;
					$o->termek_id = $k->id;
					$ossztermeklista[] = $o;
					++$ossztermekfajta;
				}elseif($kulcs == "KEDVEZMENY" ){
					$kedvezmeny = $k;
					$brutto_ar =  ar::getBrutto( $k->ar ,$k->afaErtek) ;
					$mindosszesen_brutto += $brutto_ar * $k->mennyiseg;
				}elseif($kulcs == "IDOPONT" ){
					$idopont = $k;
					//$brutto_ar =  ar::getBrutto( $k->ar ,$k->afaErtek) ;
					//$mindosszesen_brutto += $brutto_ar * $k->mennyiseg;
				}elseif($kulcs == "FOTERMEK" ){
					$netto_ar = $k->ar;					
					//$brutto_ar = ar::getKerekitettAr( ar::getBrutto( $k->ar ,$k->afaErtek));
					$brutto_ar = ar::getBrutto( $k->ar, $k->afaErtek);
					$mindosszesen_netto += $netto_ar * $k->mennyiseg;
					$termekek_brutto += $brutto_ar * $k->mennyiseg;
					$mindosszesen_brutto += $brutto_ar * $k->mennyiseg;
					$osszmennyiseg += $k->mennyiseg;
					$o = '';
					$o->nev = $k->nev;
					$o->brutto_ar = $brutto_ar;
					$o->mennyiseg = $k->mennyiseg;
					$o->kulcs = $kulcs;
					$o->termek_id = $k->id;
					//$ossztermeklista[] = $o;
					++$ossztermekfajta;
					
					$fotermek = $o;
					//$brutto_ar =  ar::getBrutto( $k->ar ,$k->afaErtek) ;
					//$mindosszesen_brutto += $brutto_ar * $k->mennyiseg;
				}else{
					$netto_ar = ar::getKerekitettAr( $k->ar );
					//$brutto_ar = ar::getKerekitettAr(ar::getBrutto( $k->ar ,$k->afaErtek));
					$szallitasiKoltseg = $k;
					$mindosszesen_brutto += ar::getBrutto( $netto_ar * $k->mennyiseg ,$k->afaErtek );
				}
			}
			?>
			<?php
			
			//echo $this->Itemid;
			?>
			<div class="inner">	
				<table class="A_KOSAR_TARTALMA">
  					
					<?php if(@$idopont){	?>
  					<tr>
    					<td class="key"><?php echo jtext::_("IDOPONT");?>:</td>
    					<td class="value"><? echo $idopont->ertek;  ?></td>
    					<td class="remove">&nbsp;</td>
  					</tr>
  					<?php } ?>
  					<?php if(@$fotermek){	?>
  					<tr>
    					<tr>
							<td class="key"><?php echo $fotermek->nev;?>:</td>
							<td class="value"><? echo ar::_($fotermek->brutto_ar*$fotermek->mennyiseg, "€", 1) ?></td>
							<td class="remove"><a href="javascript:;" onclick="removeBasket('<?php echo ($fotermek->kulcs); ?>');clearBasketField('<?php echo $fotermek->termek_id ?>')"><img src="templates/bikelinetravel/images/button-x.png"/></a></td>
						</tr>
  					</tr>
  					<?php } ?>
					<?php  
					foreach ($ossztermeklista as $termek){
					?>	
						<tr>
							<td class="key"><?php echo $termek->nev;?>:</td>
							<td class="value"><? echo ar::_($termek->brutto_ar*$termek->mennyiseg, "€", 1) ?></td>
							<td class="remove"><a href="javascript:;" onclick="removeBasket('<?php echo ($termek->kulcs); ?>');clearBasketField('<?php echo $termek->termek_id ?>')"><img src="templates/bikelinetravel/images/button-x.png"/></a></td>
						</tr>
					<?php
					}
  					?>
  					
  					<?php if(@$kedvezmeny){	?>
  					<tr>
    					<td class="key"><?php echo jtext::_("TERMEKEK_ARA");?>:</td>
    					<td class="value"><? echo ar::_($termekek_brutto, "€", 1) ?></td>
    					<td class="remove">&nbsp;</td>
  					</tr>
  					<?php } ?>
  					<?php if(@$szallitasiKoltseg ){	?>
					<tr>
					    <td class="key"><?php echo jtext::_("SZALLITASI_KOLTSEG");?>:</td>
					    <td class="value"><? echo ( @$szallitasiKoltseg->ar ) ? ar::_(ar::getBrutto( $szallitasiKoltseg->ar*$szallitasiKoltseg->mennyiseg ,$szallitasiKoltseg->afaErtek)): jtext::_("INGYENES") ?></td>
						<td class="remove">&nbsp;</td>
					</tr>
  					<?php } ?>
  					
  					<?php if(@$kedvezmeny){	?>
				  	<tr>
				  		<td class="key"><?php echo jtext::_("AJANDEK_UTALVANY");?>:</td>
				  	  	<td class="value"><? echo ar::_(ar::getBrutto( $kedvezmeny->ar*$kedvezmeny->mennyiseg ,$kedvezmeny->afaErtek)) ?></td>
				  	  	<td class="remove">&nbsp;</td>
				  	</tr>
  					<?php } ?>
					
					<?php $mindosszesen_brutto = ($mindosszesen_brutto >= 0) ? $mindosszesen_brutto : 0;?>
					<tr class="osszesen">
					    <td class="key"><?php echo jtext::_("OSSZESEN");?>:</td>
					    <td class="value"><span class="ar"><?php echo ar::_( $mindosszesen_brutto, "€", 1 ); ?></span></td>
					    <td class="remove">&nbsp;</td>
					</tr>
				</table>
				<?php
				$Itemid = $this->getsessionvar('Itemid');
			 	
			 	$link = jroute::_("index.php?option=com_whp&controller=rendeles&Itemid={$Itemid}"); 
				 if (@$fotermek){echo "<a class=\"button UGRAS_A_KOSARHOZ\" href=\"{$link}\">".jtext::_("MEGRENDELEM")."</a>"; } else {
				 	echo jtext::_('KEREM_ADJA_MEG_A_RESZTVEVOK_LETSZAMAT');
				 }
				 
				 ?>
			</div>
			<?php 
			/*
			$link = jroute::_("index.php?option=com_whp&controller=kosar&Itemid={$Itemid}");	
			echo "<a class=\"\" href=\"{$link}\">". $osszmennyiseg . " " .jtext::_("TERMEK") ."</a> </div>";
			*/	
		}else{
			
			/*
            <table class="kosar_head">
				<tbody>
                	<tr>
    					<td><span class="span_kosar_icon">&nbsp;</span></td>
        				<td style="vertical-align: middle!important;"><strong><?php echo Jtext::_("A_KOSAR_URES"); ?></strong></td>
    				</tr>
				</tbody>
            </table>
			*/
			echo "<div class=\"kosar_ures\">" . Jtext::_("A_KOSAR_URES") . "</div>";
		}

		$html = ob_get_contents();
		ob_end_clean();
		$ret ="";
		$ret->html = $html;
		$ret->error = "";
		return $this->getJsonRet($ret);				
	}



	function getSzallitasiDijObj($kosar){

		$q = "select tetel.*, afaT.ertek as afaErtek from #__wh_szallitasi_tetel as tetel 

		inner join #__wh_afa as afaT on tetel.afa_id = afaT.id

		where tetel.webshop_id = {$GLOBALS['whp_id']}";

		$this->_db->setQuery($q);

		$rows = $this->_db->loadObjectList( );		

		echo $this->_db->getErrorMsg( );	

		$ossz = $this->getKosarOszzAr($kosar);

		$o="";

		$o->ar = "";

		$o->afaErtek = "";

		$o->cikkszam = "SZALLITASI_KOLTSEG";

		$o->mennyiseg =1;

		$o->nev = jtext::_("SZALLITASI_KOLTSEG");

		$o->option = "SZALLITASI_KOLTSEG";

		$o->cikkszam = 0;

		$o->kategorianev="";

		$o->gyartonev="";

		$ret->netto_ar = 0;

		$ret->afaErtek = 0;		

		foreach($rows as $r){

			if( $ossz >= $r->tol && $ossz <= $r->ig ){

				$o->ar = $r->dij;

				$o->afaErtek = $r->afaErtek;

			}

		}

		

		return $o;		

	}
	
	function getSzallitasiInterval($kosar){

		$q = "select tetel.*, afaT.ertek as afaErtek from #__wh_szallitasi_tetel as tetel 

		inner join #__wh_afa as afaT on tetel.afa_id = afaT.id

		where tetel.webshop_id = {$GLOBALS['whp_id']}";

		$this->_db->setQuery($q);

		$rows = $this->_db->loadObjectList( );		

		echo $this->_db->getErrorMsg( );	

		$ossz = $this->getKosarOszzAr($kosar);

		$o="";

		foreach($rows as $r){

			if( $ossz >= $r->tol && $ossz <= $r->ig ){

				$o->dij = $r->dij;

				$o->afa = $r->afaErtek;
				
				$o->tol = $r->tol;
				
				$o->ig = $r->ig;

			}

		}

		return $o;		

	}



	function szallitasiKoltsegArr(){

		$arr=array();

		if($k = $this->getSessionVar( "szallitasiKoltseg" ) ){

			$o="";

			$o->TERMEK = $k->nev;

			//$o->OSSZESEN_NETTO = ar::_( $k->ar*$k->mennyiseg );

			$o->MENNYISEG = "{$k->mennyiseg}";

			$o->AR_NETTO_NAGYKER = ar::_($k->ar);

			$o->AR_BRUTTO_NAGYKER = ar::_(ar::getBrutto( $k->ar ,$k->afaErtek));

			$o->OSSZESEN_BRUTTO = ar::_(ar::getBrutto( $k->ar ,$k->afaErtek)*$k->mennyiseg );

			$arr[] = $o;

			$this->mindosszesen_netto+=$k->ar*$k->mennyiseg;

			$this->mindosszesen_brutto+=ar::getBrutto( $k->ar*$k->mennyiseg ,$k->afaErtek);

		};

		return $arr;

	}

	

	function getModositottKosarTermek($k, $kosarKulcs, $termVarId){
		//parse_str($kosarKulcs);
		//print_r($k);
		//die( $kalkulaciosInformaciok );
		//die($termVarId." ----");
		if( $termVarId ){
			$o = $this->getObj("#__wh_termekvariacio", $termVarId );
			( trim( $o->cikkszam ) ) ? $k->cikkszam = $o->cikkszam : $k->cikkszam;
			//( trim( $o->ar ) ) ? $k->ar = $o->ar : $k->ar;
			//print_r($k);
			//die;
			//$k->ar = $this->getTvAr( $termVarId, $k->afaErtek, $k->kampany)->netto_ar;
			//$k->ar = $this->setAr($k);
			
			$k->suly = $o->suly;
			//$k->nev .= "<br />".$this->getVariacioNev( $termVarId );	
			$cikkszam = ($o->cikkszam) ? " ( {$o->cikkszam} ) " : "";				
			parse_str( $o->ertek, $arr_ );
			//$termVarNev = $arr_[ "mezoid_3" ];
			//$k->nev = "<span class=\"nev\">" . $k->nev . " - ".$termVarNev . "</span><span class=\"cikkszam\">" . $cikkszam . "</span>"; 
			$k->nev = "<span class=\"nev\">" . $k->nev. "</span><span class=\"cikkszam\">" . $cikkszam . "</span>"; 
			//print_r($k);
			//die;
		}else{
			//print_r($k);
			//die('sdf');
				
			$cikkszam = ($k->cikkszam) ? " ( {$k->cikkszam} ) " : "";
			$k->nev = "<span class=\"nev\">" . $k->nev . "</span><span class=\"cikkszam\">" . $cikkszam . "</span>"; 			
		}
		$kieg_adatok = '';
		//$kieg_adatok = ($kalkulaciosInformaciok) ? "".jtext::_("KALKULACIOS_ADATOK").": {$kalkulaciosInformaciok}" : "";
		$k->nev .= "<span class=\"kieg_adatok\">{$kieg_adatok}</span>";
		//print_r( $k );
		//die;
		return $k;
	}

	function setTermVarKosarban__($termVarId){

		foreach( (array)$this->kosar as $kosarKulcs => $k){

			parse_str($kosarKulcs);

			if($termVarId){

				$k->nev .= "<br />".$this->getVariacioNev( $termVarId );				

				$o = $this->getObj("#__whp_termekvariacio", $termVarId );

				(trim($o->cikkszam)) ? $k->cikkszam = $o->cikkszam : $k->cikkszam;

				(trim($o->ar)) ? $k->ar = $o->ar : $k->ar;

				$this->kosar[$kosarKulcs] = $k;

				//die;

				//$k->ar = 

			}else{

			}

			

		}

	}



	function getTermek($termek_id){
		/*$q = "SELECT termek.*, kategoria.nev as kategorianev, afa.ertek as afaErtek, gyarto.nev as gyarto,  
		kampany.id as kampany_id_		
		FROM #__wh_termek as termek 
		inner join #__wh_kategoria as kategoria on termek.kategoria_id = kategoria.id	
		inner join #__wh_ar as ar on ar.termek_id = termek.id
		inner join #__wh_afa as afa on ar.afa_id = afa.id	
		left join #__wh_kampany_kapcsolo as kampany_kapcsolo on kampany_kapcsolo.termek_id = termek.id
		left join #__wh_kampany as kampany on kampany_kapcsolo.kampany_id = kampany.id	
		left join #__wh_gyarto as gyarto on termek.gyarto_id = gyarto.id		
		where termek.id = {$termek_id} limit 1";*/
		$q = "SELECT termek.*, 
		ar.ar, kategoria.nev as kategorianev,kategoria.id as kategoria_id, kategoria.basket_unique as basket_unique, 
		afa.ertek as afaErtek, gyarto.id as gyarto_id, gyarto.nev as gyarto, gyarto.url as gyarto_url,
		kampany_kapcsolo.kampany_prioritas, 
		kampany.id as kampany_id_
		FROM #__wh_termek as termek 
		inner join #__wh_kategoria as kategoria on termek.kategoria_id = kategoria.id
		inner join #__wh_ar as ar on ar.termek_id = termek.id
		inner join #__wh_afa as afa on ar.afa_id = afa.id
		left join #__wh_kampany_kapcsolo as kampany_kapcsolo on kampany_kapcsolo.termek_id = termek.id
		left join #__wh_kampany as kampany on kampany_kapcsolo.kampany_id = kampany.id	
		left join #__wh_gyarto as gyarto on gyarto.id = termek.gyarto_id	
		where termek.id = {$termek_id} limit 1"; 
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		array_map (array($this, "setKampany"), $rows );					
		array_map(array($this, "setAr"), $rows);
		array_map(array($this, "setKosarTermeknev"), $rows);
		//print_r( $rows[0] );
		//die();
		return $rows[0];
	}

	function setKosarTermeknev($item){
		if ($item->szulo_termek_id == 0){$item->nev = $item->nev.' '.jtext::_('TURA');}
		return $item;		
	}

	function getkosarLista_( $nezet = "form", $cellspacing='' ){

		if($nezet == "form") {

			$arr = $this->getKosarArrForm(); 

			$listazo = new listazo($arr, "table_kosar", '','','','',$cellspacing);		

			$ret = $listazo->getLista();

		} else {

			$ret = $this->getKosarArrNormal();

		}

		return $ret;

	}

	

	function getkosarLista( $nezet = "form", $cellspacing='' ){

		switch($nezet){

			case "form":

				$arr = $this->getKosarArrForm();

				$listazo = new listazo($arr, "table_kosar", '','','','',$cellspacing);		

				$ret = $listazo->getLista();

				break;

			case "email":

				$ret = $this->getKosarEmail();

				break;

			default:

				$arr = $this->getKosarArrNormal();

				$listazo = new listazo($arr, "table_kosar", '','','','',$cellspacing);		

				$ret = $listazo->getLista();

		}



		return $ret;

	}

	function getKosarEmail(){	//módósított

		$arr = array();

		$this->mindosszesen_netto = 0;

		$this->mindosszesen_brutto = 0;
		
		$this->osszsuly = 0;
		//echo count($this->kosar);

		$i = 0;

		//($szallitasiKoltseg = $this->getSessionVar("szallitasiKoltseg") ) ? $arr[]=$szallitasiKoltseg : $arr;		
		//print_r($this->kosar); die();
		foreach((array)$this->kosar as $k){
			//print_r($k); 
			$alapJs = "$('tetel_id').value='{$i}'; $('adminForm').submit();";
			$o = "";
			//@$o->HIDDEN = $k->kosarKep;
			$o->TERMEK = $k->nev;
			//$o->OSSZESEN_NETTO = ar::_( $k->ar*$k->mennyiseg );
			$o->MENNYISEG = "{$k->mennyiseg}";
			@$o->MENNYISEGI_EGYSEG = ($k->me) ? $k->me : jtext::_( "DB" );						
			if (@$k->option!="SZALLITASI_KOLTSEG" && @$k->cikkszam != 'KEDVEZMENY'){$o->SULY = $this->kerekit_suly($k->suly * $k->mennyiseg).' kg';} else {$o->SULY='';}
			//echo $k['option'].'-';
			//echo($o->SULY);
			$this->osszsuly = $this->osszsuly + $this->kerekit_suly($k->suly * $k->mennyiseg);
			$netto_ar =  $k->ar;
			//$brutto_ar = ar::getKerekitettAr( ar::getBrutto( $k->ar ,$k->afaErtek ) );
			$brutto_ar = ar::getKerekitettAr( ar::getBrutto( $k->ar ,$k->afaErtek ) );
			//$brutto_ar = ;
			$o->AR_NETTO_EGYSEGAR = ar::_( $netto_ar );
			
			$o->AR_BRUTTO_EGYSEGAR = ar::_( $brutto_ar );
			$o->OSSZESEN_BRUTTO = "<div style=\"width:150px; text-align:right; white-space:nowrap;\">".ar::_( $brutto_ar * $k->mennyiseg, "€", 0, true )."</div>"; 
			$arr[] = $o;
			$this->mindosszesen_netto += $netto_ar * $k->mennyiseg;
			$this->mindosszesen_brutto+= $brutto_ar* $k->mennyiseg;
			$i++;
		}
		//die();
		$this->mindosszesen_brutto = ($this->mindosszesen_brutto >= 0) ? $this->mindosszesen_brutto : 0;

		$arr = array_merge($arr, $this->szallitasiKoltsegArr()); 

		$o="";
		
		$o->EXTRA_HTML="<td class=\"td_total\" >"."<span class=\"total\"><strong>".jtext::_("OSSZSULY").":</strong></span></td>";

		$o->EXTRA_HTML.="<td class=\"td_total td_total_value\" colspan=\"2\" ><div class=\"total brutto\"><strong>".number_format($this->osszsuly,2)." kg</strong></span>"."</td>";
		
		$o->EXTRA_HTML.="<td class=\"td_total\" >"."<span class=\"total\"><strong>".jtext::_("MINDOSSZESSEN_BRUTTO").":</strong></span></td>";

		$o->EXTRA_HTML.="<td></td><td></td><td class=\"td_total td_total_value\" ><div style=\"width:150px; text-align:right; white-space:nowrap;\"><strong>".ar::_($this->mindosszesen_brutto,"€",1)."</strong></div>"."</td>";		

		$arr[] = $o;

		$this->setOsszObj();
		
		//$listazo = new listazo($arr, "table_kosar", '','','','','10');		
		//$ret = $listazo->getLista();
		
		//die($ret);
		return $arr;
	}

	function getKosarEmail__(){

		$arr = array();

		$this->mindosszesen_netto = 0;

		$this->mindosszesen_brutto = 0;

		//echo count($this->kosar);

		$i = 0;

		

		$separator = " | ";

		$line = "-------------------------------------------------------------<br/>";

		ob_start();

			//($szallitasiKoltseg = $this->getSessionVar("szallitasiKoltseg") ) ? $arr[]=$szallitasiKoltseg : $arr;		

			//print_r($this->kosar);

			//die;

			$suly = 0;

			foreach((array)$this->kosar as $k){

				//print_r($k);

				@$suly += $k->suly * $k->mennyiseg;

				echo "<b>{$k->nev}</b>{$separator}";

				$me = ( isset($k->me) ) ? $k->me : "db";

				echo @jtext::_("MENNYISEGI_EGYSEG").": ".$me."" . "{$separator}";				

				echo "{$k->mennyiseg}" . "{$separator}";

				echo Jtext::_("EGYSEGAR"). ": " . ar::_(ar::getBrutto( $k->ar ,$k->afaErtek)) . "{$separator}";

				echo "<b>" . Jtext::_("OSSZESEN"). ": " . ar::_(ar::getBrutto( $k->ar ,$k->afaErtek)*$k->mennyiseg ) . "</b><br />";

				//$o->OSSZESEN_NETTO = ar::_( $k->ar*$k->mennyiseg );

				$this->mindosszesen_netto+=$k->ar*$k->mennyiseg;

				$this->mindosszesen_brutto+=ar::getBrutto( $k->ar*$k->mennyiseg ,$k->afaErtek);

				$i++;

			}

			$this->mindosszesen_brutto = ($this->mindosszesen_brutto >= 0) ? $this->mindosszesen_brutto : 0;

			$arr = array_merge($arr, $this->szallitasiKoltsegArr()); 

			echo $line;

			echo "<b>" . jtext::_("MINDOSSZESSEN_BRUTTO").": " . ar::_($this->mindosszesen_brutto)."</b><br />";

			echo "<b>" . jtext::_("TOMEG_OSSZESEN").": " . $suly." kg</b>";			

		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;

	}



	function getKosarArrNormal__(){	//eredeti

		$arr = array();

		$this->mindosszesen_netto = 0;

		$this->mindosszesen_brutto = 0;

		//echo count($this->kosar);

		$i = 0;

		//($szallitasiKoltseg = $this->getSessionVar("szallitasiKoltseg") ) ? $arr[]=$szallitasiKoltseg : $arr;		

		foreach((array)$this->kosar as $k){

			

			$alapJs = "$('tetel_id').value='{$i}'; $('adminForm').submit();";

			$o = "";

			@$o->HIDDEN = $k->kosarKep;

			$o->TERMEK = $k->nev;

			//$o->OSSZESEN_NETTO = ar::_( $k->ar*$k->mennyiseg );

			$o->MENNYISEG = "{$k->mennyiseg}";

			@$o->MENNYISEGI_EGYSEG = ($k->me) ? $k->me : jtext::_( "DB" );						

			$o->AR_NETTO = ar::_($k->ar);

			$o->AR_BRUTTO = ar::_(ar::getBrutto( $k->ar ,$k->afaErtek));

			$o->OSSZESEN_BRUTTO = ar::_(ar::getBrutto( $k->ar ,$k->afaErtek)*$k->mennyiseg );

			

			$arr[] = $o;

			$this->mindosszesen_netto+=$k->ar*$k->mennyiseg;

			$this->mindosszesen_brutto+=ar::getBrutto( $k->ar*$k->mennyiseg ,$k->afaErtek);

			$i++;

		}

		$this->mindosszesen_brutto = ($this->mindosszesen_brutto >= 0) ? $this->mindosszesen_brutto : 0;

		$arr = array_merge($arr, $this->szallitasiKoltsegArr()); 

		$o="";

		$o->EXTRA_HTML="<td class=\"td_total\" colspan=\"6\" >"."<span class=\"total\">".jtext::_("MINDOSSZESSEN_BRUTTO").":</span></td>";

		$o->EXTRA_HTML.="<td class=\"td_total td_total_value\" ><span class=\"total brutto\">".ar::_($this->mindosszesen_brutto, "€", 5)."</span>"."</td>";		

		$arr[] = $o;

		$this->setOsszObj();

		return $arr;

	}

	

	

	function getKosarArrNormal(){	//módósított
		$arr = array();
		$this->mindosszesen_netto = 0;
		$this->mindosszesen_brutto = 0;
		$this->osszsuly = 0;
		//echo count($this->kosar);
		$i = 0;
		//($szallitasiKoltseg = $this->getSessionVar("szallitasiKoltseg") ) ? $arr[]=$szallitasiKoltseg : $arr;		
		$IDOPONT = $this->kosar['IDOPONT'];
		unset($this->kosar['IDOPONT']);
		foreach((array)$this->kosar as $kosarKulcs => $k){
			$alapJs = "$('tetel_id').value='{$i}'; $('adminForm').submit();";
			$o = "";
			if($k->cikkszam != 'KEDVEZMENY') {
				$o->TERMEK = @$k->kosarKep  /*."<br />{$kosarKulcs}"*/;
			} else {$o->TERMEK = '';}
			
			$o->TERMEK = $k->nev;
			//$o->HIDDEN = $k->nev;
			 
			//$o->OSSZESEN_NETTO = ar::_( $k->ar*$k->mennyiseg );
			$o->MENNYISEG = "{$k->mennyiseg}";
			@$o->MENNYISEG .= ($k->me) ? $k->me : jtext::_( "DB" );
			//$o->SULY = $this->kerekit_suly($k->suly*$k->mennyiseg).' kg'; 				
			//$o->AR_NETTO = ar::_($k->ar);
			//$netto_ar = ar::getKerekitettAr( $k->ar );
			$netto_ar =  $k->ar;
			$brutto_ar = ar::getKerekitettAr( ar::getBrutto( $k->ar ,$k->afaErtek ) );
			//$brutto_ar = ar::getBrutto( $k->ar, $k->afaErtek );
			//echo $brutto_ar." **";
			//$brutto_ar = ;
			$o->AR_NETTO_EGYSEGAR = ar::_( $netto_ar ) /*. " :: {$k->ar} " */;
			$o->AR_BRUTTO_EGYSEGAR = ar::_( $brutto_ar ) /*. " :: {$brutto_ar} $k->mennyiseg"*/;
			$o->OSSZESEN_BRUTTO = ar::_( $brutto_ar * $k->mennyiseg, "€", 0, true );
			if ($kosarKulcs == 'FOTERMEK'){
				$$kosarKulcs = $o;
			} elseif ($k->cikkszam == 'KEDVEZMENY' && $k->cikkszam != ''){
				$kedv_ = $o;
				
			} else {
				$arr[] = $o;
			}
			
			
			$this->mindosszesen_netto += $netto_ar * $k->mennyiseg;
			$this->mindosszesen_brutto+= $brutto_ar* $k->mennyiseg;
			/*
			$netto_ar = ar::getKerekitettAr( $k->ar );
			$brutto_ar = ar::getKerekitettAr( ar::getBrutto( $k->ar ,$k->afaErtek));
			$o->AR_NETTO_EGYSEGAR = ar::_($netto_ar);
			$o->AR_BRUTTO_EGYSEGAR = ar::_( $brutto_ar );
			$o->OSSZESEN_BRUTTO = ar::_( $brutto_ar * $k->mennyiseg ); 
			$arr[] = $o;
			@$this->osszsuly += $this->kerekit_suly($k->suly*$k->mennyiseg); 
			$this->mindosszesen_netto += $netto_ar * $k->mennyiseg;
			$this->mindosszesen_brutto+= $brutto_ar* $k->mennyiseg;
			*/
			$i++;
		}
		$this->mindosszesen_brutto = ($this->mindosszesen_brutto >= 0) ? $this->mindosszesen_brutto : 0;
		
		$arr = array_merge($arr, $this->szallitasiKoltsegArr()); 
		if (isset($kedv_)){
				
			$arr[] = $kedv_;
		}
		if (isset($FOTERMEK)){ array_unshift($arr, $FOTERMEK);}
		$o="";
		$o->EXTRA_HTML="<td class=\"td_total\" >".jtext::_('VALASZTOTT_IDOPONT')."<span class=\"total\"></td>";
		$o->EXTRA_HTML.="<td class=\"td_total td_total_value\" colspan=\"2\" >{$IDOPONT->ertek}</td>";
		$o->EXTRA_HTML.="<td class=\"td_total\"  >"."<span class=\"total\">".jtext::_("MINDOSSZESSEN_BRUTTO").":</span></td>";		$o->EXTRA_HTML.="<td class=\"td_total td_total_value\" ><span class=\"total brutto\">".ar::_($this->mindosszesen_brutto, "€", 1)."</span>"."</td>";
		$arr[] = $o;
		$this->setOsszObj();
		return $arr;
	}

	function setOsszObj(){

		$osszObj = "";		

		$xml = new xmlParser("rendeles.xml");

		foreach($xml->getGroupElementNames( "osszesen_valtozok" ) as $v ){

			@$osszObj->$v=$this->$v;

		}

		$this->setSessionVar("osszObj", $osszObj);

	}



	function getKosarGombok(){
		$Itemid = $this->Itemid;
		ob_start();

		
		$i = 0;
		
		//print_r($this->kosar[0]); die();
		$temp = array();
		foreach ($this->kosar as $k){		
			if (@$k->id){
				$temp[] = $k->id;
				//print_r($this->kosar[$i]); die('dsf');
				//break;
			}
		}
		//print_r($this->kosar); die();
		$termek_id = end( $temp );
		//die($termek_id);
		//print_r($this->kosar); die();
		
	//	@$t = $this->getObj( "#__wh_termek", $termek_id );

		//@$link = "index.php?option=com_whp&controller=termekek&cond_kategoria_id={$t->kategoria_id}";

		//echo $link;

		?>

        <div id="kasszagombok">

          <!--<input id="tovabb_vasarol" type="button" onclick="window.location='<?php echo $link ?>'" value="<?php echo jtext::_("TOVABB_VASAROLOK") ?>">-->

        <?php /*  

          <input id="tovabb_vasarol" type="button" onclick="javascript:history.back()" value="<?php echo jtext::_("TOVABB_VASAROLOK") ?>">

          <input id="kasszahoz" type="button" onclick="$j('#adminForm > #controller').val('rendeles'); $j('#adminForm').submit()" value="<?php echo jtext::_("TOVABB_A_MEGRENDELESHEZ") ?>">*/
		
		//$vissza_link = "index.php?option=com_whp&controller=termek&termek_id={$termek_id}&kosar_index=&termVarId=&mennyiseg=&";
		$vissza_link = "index.php?option=com_whp&controller=termekek&task=listCategories&cond_kategoria_id=118&Itemid={$Itemid}";
		?>
        <table>
        	<tr>
            	<td>
        			<a href="<?php echo $vissza_link ?>" id="tovabb_vasarol" ><?php echo jtext::_("TOVABB_VASAROLOK") ?></a>
                </td>
                <td>
                <?php 
				if( !$this->user->id ){
					//$this->document->addScriptDeclaration( "\$j( document ).ready( function(){ setConfirm(); } )" );
					$js = "\$j('#adminForm > #controller').val('rendeles'); \$j('#adminForm').submit()";
					/*
					$reglink = "index.php?option=com_whp&controller=felhasznalo&Itemid=35";
					$js = "if( confirm('".jtext::_("ON_REGISZTRALT_FELHASZNALO")."') ){ window.location='{$regLink}'; }else{ \$j('#adminForm > #controller').val('rendeles'); $j('#adminForm').submit()}; ";
					*/
				}else{
					$js = "\$j('#adminForm > #controller').val('rendeles'); \$j('#adminForm').submit()";
				}
				?><a class="confirm" href="javascript:;" id="kasszahoz" onclick="<?php echo $js ?>"><?php echo jtext::_("TOVABB_A_MEGRENDELESHEZ") ?></a>
                </td> 
           </tr>
        </table>
        </div>
		<?php
		$ret = ob_get_contents();

		ob_end_clean();

		return $ret;

	}

	function getKosarArrForm(){
		$Itemid = $this->Itemid;
		$arr = array();
		$this->mindosszesen_netto = 0;
		$this->mindosszesen_brutto = 0;
		$this->osszsuly = 0;
		$i = 0;
		//print_r($this->kosar); die();
		$IDOPONT = $this->kosar['IDOPONT'];
		unset($this->kosar['IDOPONT']);
		//print_r($this->kosar); die();
		foreach( (array)$this->kosar as $kosarKulcs => $k){
			//print_r($k);
			
			//die($kosarKulcs);
			//echo $kosarKulcs."<br />";
			parse_str($kosarKulcs);
			
			//$termVarErtek = $this->getObj("#__whp_termekvariacio", $termVarId)->ertek;
			//echo $termVarErtek."- - - - - ";
			$kk = urlencode($kosarKulcs); 
			$alapJs = "\$j('#tetel_id').val('{$kk}'); \$j('#adminForm').submit();";
			$o = "";
			/*if($k->cikkszam != 'KEDVEZMENY') {
				$o->TERMEK = @$k->kosarKep/*."<br />{$kosarKulcs}";
			} else {$o->TERMEK = '';}*/
			$o->HIDDEN = "<div class=\"wr_termeknev\">";
			$o->HIDDEN .= $k->nev;
			//print_r($k);
			
			$o->HIDDEN .= "</div>";
			//$o->OSSZESEN_NETTO = ar::_( $k->ar*$k->mennyiseg );
			//print_r($this->kosar); die();
			//$o->MENNYISEG = $k->mennyiseg . " ";
			$o -> MENNYISEG = "<input name=\"mennyisegArr[]\" class=\"input_mennyiseg\" type=\"text\" maxlength=\"2\" value=\"{$k->mennyiseg}\">";
			$o->HIDDEN2 = '';			
			if($k->cikkszam <> 'KEDVEZMENY') {
				//$t = $this->getObj("#__wh_termek", $termek_id);
				//$link = "index.php?option=com_whp&controller=termek&cond_kategoria_id={$t->kategoria_id}&Itemid={$Itemid}&termek_id={$termek_id}&termVarId={$termVarId}&mennyiseg={$k->mennyiseg}";
				//print_r($k);
				//die;
				//$link .= ( $k->termek_tipus != "DARABARU" ) ? "&kosar_index=".( $i+1 ) : "";
			//	$link .= "&kosar_index=".( $i+1 );
				//$link = "index.php?option=com_whp&controller=termek&cond_kategoria_id=22&Itemid={$Itemid}&termek_id={$termek_id}&termVarId={$termVarId}";
				$o->HIDDEN2 .= "<div class=\"div_tetel_gomb\">";
				$o -> HIDDEN2 .= "<a class=\"tetel_gomb tetel_gomb_torol\" href=\"javascript:;\" onclick=\"\$j('#task').val('tetel_modosit');{$alapJs}\" >".jtext::_("MODOSIT")."</a>";
				
				$o->HIDDEN2 .= "<div class=\"\"><a class=\"tetel_gomb tetel_gomb_torol\" href=\"javascript:;\" onclick=\"\$j('#task').val('tetel_torol');{$alapJs}\" >".jtext::_("TOROL")."</a></div>";		
				$o->HIDDEN2 .= "</div>";
				$o->HIDDEN2 .= "<input type=\"hidden\" name=\"kosarKulcsArr[]\" value=\"{$kosarKulcs}\" >";
			}
			//$o->SULY = $this->kerekit_suly($k->mennyiseg*$k->suly)." kg";
			//@$o->MENNY_EGYS = ($k->me) ? $k->me : jtext::_( "DB" );	
			@$o->MENNYISEG .= ( $k->me ) ? $k->me : jtext::_( "DB" );			
			//$netto_ar = ar::getKerekitettAr( $k->ar );
			$netto_ar =  $k->ar;
			//$brutto_ar = ar::getKerekitettAr( ar::getBrutto( $k->ar ,$k->afaErtek ) );
			$brutto_ar = ar::getBrutto( $k->ar, $k->afaErtek );
			//$brutto_ar = $netto_ar * 1.25;
			$o->AR_NETTO_EGYSEGAR = ar::_( $netto_ar )/*. " - {$netto_ar}"*/;
			$o->AR_BRUTTO_EGYSEGAR = ar::_( $brutto_ar );
			//$osszesen_brutto
			$o->OSSZESEN_BRUTTO = ar::_( $brutto_ar * $k->mennyiseg, "€", 0, true ) ; 
			if ($kosarKulcs == 'FOTERMEK'){
				$$kosarKulcs = $o;
			} elseif ($k->cikkszam == 'KEDVEZMENY' && $k->cikkszam != ''){
				$kedv_ = $o;
				
			} else {
				$arr[] = $o;
			}
			
			$this->mindosszesen_netto += $netto_ar * $k->mennyiseg;
			
			$this->mindosszesen_brutto+= $brutto_ar * $k->mennyiseg;
			
			@$this->osszsuly += $this->kerekit_suly($k->mennyiseg*$k->suly);
			$i++;
		}//die("-----");
		//die();
		if (isset($FOTERMEK)){ array_unshift($arr, $FOTERMEK);}
		//if (isset($IDOPONT)){array_unshift($arr, $IDOPONT);array_shift();}
		if (isset($kedv_)){	$arr[] = $kedv_;}
		//print_r($IDOPONT); die();
		$o="";
		$o->EXTRA_HTML="<td class=\"td_total\" >".jtext::_('VALASZTOTT_IDOPONT')."<span class=\"total\"></td>";
		$o->EXTRA_HTML.="<td class=\"td_total td_total_value\" colspan=\"3\" >{$IDOPONT->ertek}</td>";
		$o->EXTRA_HTML.="<td class=\"td_total\"  >"."<span class=\"total\">".jtext::_("MINDOSSZESSEN_BRUTTO").":</span></td>";
		$o->EXTRA_HTML.="<td class=\"td_total td_total_value\" ><span class=\"total brutto\">".ar::_($this->mindosszesen_brutto, "€", 1)."</span>"."</td>";		
		$arr[] = $o;
		$o="";
		//print_r($this->getSzallitasiDijObj($this->kosar)); die();//if () 
		//print_r($this->getSzallitasiInterval($this->kosar)); die();
		//print_r($this->kosar['SZALLITASI_KOLTSEG']); die();
		
		/*if ($this->getSzallitasiInterval($this->kosar)->dij){
			$o->EXTRA_HTML="<td class=\"td_total\" >"."<span class=\"total\"></td>";
			$o->EXTRA_HTML.="<td class=\"td_total\" colspan=\"4\" >".jtext::_("INGYENES_KISZALLITASHOZ").":</span></td>";
			$o->EXTRA_HTML.="<td class=\"td_total td_total_value\" ><span class=\"total brutto\">".ar::_($this->getSzallitasiInterval($this->kosar)->ig-$this->mindosszesen_brutto, "€", 1)."</span>"."</td>";		
			$arr[] = $o;
		}*/
		
		$this->setOsszObj();		
		return $arr;
	}
}// class

?>

