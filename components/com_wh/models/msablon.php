<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelmsablon extends whAdmin
{
	var $xmlFile = "msablon.xml";
	var $uploaded = "media/wh/images/msablon/";
	var $images = 1;
	var $tmpname = "";
	var $table = "#__wh_msablon";
	//var $table ="wh_msablon";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");	
		$this->getData();
	 	$this->xmlParser = new xmlmsablon($this->xmlFile, $this->_data);
	}//function

	function mezoIrany(){
		ob_start();
		$msablon_id = jrequest::getVar("msablon_id", 0);
		$kapcsolo_id = jrequest::getVar('kapcsolo_id');	
		$irany = jrequest::getVar("irany", '');	
		
		$q = "select * from #__wh_msablonmezo_kapcsolo where msablon_id = {$msablon_id} order by sorrend, id";
		$this->_db->setQuery($q);
		$arr = $this->_db->LoadObjectList();
		//print_r($arr);
		foreach($arr as $a){
			$ind = array_search($a, $arr);
			if($a->id == $kapcsolo_id){
				$akt = $arr[$ind];
				($irany == "le" ) ? $ind_ = $ind+1 : $ind_ = $ind-1;
				$temp = $arr[$ind_];
				$arr[$ind_] = $akt;
				$arr[$ind] = $temp;
			}
		}
		foreach($arr as $o){
			$o->sorrend= array_search($o, $arr);
			$this->_db->updateObject("#__wh_msablonmezo_kapcsolo", $o, "id");
		}
		$ret = ob_get_contents();
		ob_end_clean();
		
		return $ret.$this->getMsablonMezok();
	}

	function torolMsablonMezo(){
		$kapcsolo_id = jrequest::getVar('kapcsolo_id');
		if( $kapcsolo_id ){
			$q = "delete from #__wh_msablonmezo_kapcsolo where id = {$kapcsolo_id} ";
			$this->_db->setQuery($q);
			$this->_db->query();
		}
		return $this->getMsablonMezok();
	}

	function hozzaadMsablonMezo(){
		ob_start();
		$msablon_mezo = jrequest::getVar('msablon_mezo');
		$msablon_id = jrequest::getVar('msablon_id');
		$o = "";
		$o->msablon_id = $msablon_id;
		preg_match_all("/\(.*\)/", $msablon_mezo, $matches);
		//print_r($matches[0]);
		@$o->msablonmezo_id = str_replace( array("(", ")"), "", $matches[0][0] );
		//print_r( $o->jogtulajdonos_id);
		$q = "select id from #__wh_msablonmezo_kapcsolo as kapcsolo 
		where msablon_id = {$o->msablon_id}
		and msablonmezo_id = {$o->msablonmezo_id}";
		$this->_db->setQuery($q);
		if($o->msablon_id && $o->msablonmezo_id && !$this->_db->loadResult() ){
			$q = "select sorrend from #__wh_msablonmezo_kapcsolo where msablon_id = {$msablon_id} order by sorrend desc limit 1";
			$this->_db->setQuery($q);
			$sorrend = $this->_db->loadResult();
			($sorrend) ? $sorrend += 1 : $sorrend = 1;
			$o->sorrend = $sorrend;						
			$this->_db->insertObject("#__wh_msablonmezo_kapcsolo", $o, "id" );
		}
		$ret = ob_get_contents();
		ob_end_clean();		
		//return $ret.$this->getJogtulajdonosok();
		return $this->getMsablonMezok();
	}

	function getMsablonMezok(){
		ob_start();
		$msablon_id = jrequest::getVar("msablon_id");
		$q = "select mezo.nev as mezo_nev, mezo.id as mezo_id, msablon.id as msablon_id, kapcsolo.id as kapcsolo_id
		from #__wh_msablonmezo  as mezo
		left join #__wh_msablonmezo_kapcsolo as kapcsolo on kapcsolo.msablonmezo_id = mezo.id				
		left join #__wh_msablon as msablon on kapcsolo.msablon_id = msablon.id
		where msablon.id = {$msablon_id}		
		order by kapcsolo.sorrend asc, mezo_nev asc
		";
		//$q = "select * from #__wh_msablonmezo ";
		$this->_db->setQuery($q);
		$arr = array();
		$rows = $this->_db->loadObjectList();
		if(count($rows)) {
			foreach($rows as $r ){
				$o = "";  
				//@$kapcsolo_id = $this->getObj("#__wh_msablonmezo",$msablon_id, "msablon_id" )->id;
				$o->MEZO = $r->mezo_nev;
				
				$o->SORREND = $this->getNyilHTML($rows, $r)."<input type=\"hidden\" value=\"{$r->kapcsolo_id}\" name=\"tvar_id_arr[]\" />";			
	
				$o->TOROL = "<input type=\"button\" onclick=\"if(confirm('".jtext::_("BIZTOS_HOGY_TORLOD")."')){torolMsablonMezo('{$r->kapcsolo_id}')}\" value=\"".jtext::_("TOROL")."\" >";						
					
				$arr[]=$o;
			}
		
			if( count($arr) ){
				$listazo = new listazo($arr, "msablon_lista");
				echo $listazo->getLista();
			}else{
				echo "&nbsp;";
			}
		}
		echo $this->_db->getErrorMsg();		
		$ret =  ob_get_contents();
		ob_end_clean();
		$r="";
		$r->html = $ret;
		$r->error = "";
		return $this->getJsonret($r);
		
	}

	function store()
	   {
	   //die(str_replace("#__", "", $this->table)." *********");
		$row =& $this->getTable( str_replace("#__", "", $this->table) );
		//print_r($row);
		//die;
		$msablon_id = JRequest::getVar("id", 0);
		foreach($this->getFormFieldArray() as $parName){//ha t�mb�t kell menteni
			$val = JRequest::getVar($parName,"", "",2,2,2);
			if( $parName == "mezo_id" ){
				if( count($val) ){
					$q = "delete from #__wh_msablonmezo_kapcsolo where msablon_id = {$msablon_id}";
					$this->_db->setQuery($q);
					$this->_db->Query();
					foreach($val as $msablonmezo_id){
						if($msablonmezo_id){
							$o="";
							$o->msablon_id = $msablon_id;
							$o->msablonmezo_id = $msablonmezo_id;
							$this->_db->insertObject("#__wh_msablonmezo_kapcsolo", $o, "");
						}
					}
				}
			}else{
				if(is_array($val)){
					$data[$parName] = ",".implode(",", $val).",";
				}else{
					$data[$parName] = $val;
				}
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
			if( method_exists( $this->xmlParser, "saveImages") ){
				$this->xmlParser->saveImages($id);
			}
			if( method_exists( $this->xmlParser, "saveFiles") ){
				$this->xmlParser->saveFiles($id);
			}
			if( method_exists( $this->xmlParser, "saveSpecifikacio") ){
				$this->xmlParser->saveSpecifikacio($id);
			}
			if( method_exists( $this->xmlParser, "saveGoogleKoord") ){
				$this->xmlParser->saveGoogleKoord($id);
			}

 			//die("{$id} - -");	
		  return $id;
	  }   	

	function getNyilHTML( $arr, $obj, $jFunc="mezoIrany" ){
		foreach($arr as $a){
			$ind = array_search($a, $arr);
			if($a->kapcsolo_id == $obj->kapcsolo_id) break;
		}
		$le = "<a href=\"javascript:void(0)\" onclick=\"{$jFunc}('le', '{$obj->kapcsolo_id}')\" ><img src=\"components/com_wh/assets/images/downarrow.png\" /></a>";
		$fel = "<a href=\"javascript:void(0)\" onclick=\"{$jFunc}('fel', '{$obj->kapcsolo_id}')\" ><img src=\"components/com_wh/assets/images/uparrow.png\" /></a>";
		if($ind == 0){
			//csak le
			$html = $le;
		}elseif( end($arr) == $arr[$ind] ){
			//csak fel
			$html = $fel;
		}else{
			//fel �s le
			$html=$le;
			$html.=$fel;
		}
		$js = "";
		return $html;
	}

}// class
?>