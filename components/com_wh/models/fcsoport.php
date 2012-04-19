<?php
defined( '_JEXEC' ) or die( '=;)' );

class whModelFcsoport extends modelbase
{
	var $xmlFile = "fcsoport.xml";
	var $uploaded = "";
	var $tmpname = "";
	var $table = "#__wh_fcsoport";
	//var $table ="wh_kategoria";
	
	function __construct()
	{
		parent::__construct(); 
		//die; 
		$this->value = JRequest::getVar("value", "");
		$this->getData();
	 	$this->xmlParser = new xmlfcsoport($this->xmlFile, $this->_data);
		$this->document->addscriptdeclaration("window.addEvent(\"domready\", function(){setKategoriak( 'webshop_id' );listazKategoriaKedvezmeny()})");
	}//function
	
	function torolKategoriaKedvezmeny(){
		$kategoria_kedvezmeny_id = jrequest::getVar("kategoria_kedvezmeny_id","0");		
		$q="delete from #__wh_kategoria_kedvezmeny where id = {$kategoria_kedvezmeny_id} ";
		$this->_db->setQuery($q);		
		$this->_db->Query();				
		return $this->listazKategoriaKedvezmeny();	
	}
	
	function mentKategoriaKedvezmeny(){
		$o="";
		$o->fcsoport_id = jrequest::getVar("fcsoport_id","0");
		$o->kategoria_id = jrequest::getVar("kategoria_id","0");
		$o->kategoria_kedvezmeny = jrequest::getVar("kategoria_kedvezmeny","");
		$o->kategoria_kedvezmeny_tipus = jrequest::getVar("kategoria_kedvezmeny_tipus","");
		if( $obj = $this->getObj("#__wh_kategoria_kedvezmeny", $o->kategoria_id, "kategoria_id" ) ){
			$o->id = $obj->id;
			$this->_db->updateObject("#__wh_kategoria_kedvezmeny", $o, "id");
		}else{
			$this->_db->insertObject("#__wh_kategoria_kedvezmeny", $o, "id");
		}
		return $this->listazKategoriaKedvezmeny();
	}
	
	function listazKategoriaKedvezmeny(){
		$fcsoport_id = jrequest::getvar("fcsoport_id",0);
		//$webshop_id = $this->getSessionVar("webshop_id");		
		$q = "select kedvezmeny.*, kategoria.nev as kategoria_nev 
		from #__wh_kategoria_kedvezmeny as kedvezmeny 
		inner join #__wh_kategoria as kategoria	on kedvezmeny.kategoria_id = kategoria.id
		where kedvezmeny.fcsoport_id = {$fcsoport_id}";
		$this->_db->setQuery($q);
		$this->_db->loadobjectList();
		//echo $this->_db->getErrorMsg();
		//echo $this->_db->getquery();
		$arr = array();
		foreach( (array)$this->_db->loadobjectList() as $a ){
			$o="";
			$o->KATEGORIA = $a->kategoria_nev;
			$o->KEDVEZMENY = $a->kategoria_kedvezmeny;
			$o->KEDVEZMENY_TIPUS = jtext::_($a->kategoria_kedvezmeny_tipus);
			$js = "torolKategoriaKedvezmeny( '{$a->id}' )";
			$o->TORLES = "<input onclick=\"{$js}\" type=\"button\" value=\"".jtext::_("TORLES")."\" >";
			$arr[] = $o;
		}
		$listazo = new listazo($arr);
		(count($arr) || 1 ) ? $ret = $listazo->getLista() : $ret = "<div class=\"NINCSENEK_KEDVEZMENYEK\">".jtext::_("NINCSENEK_KEDVEZMENYEK")."</div>";
		$r = "";
		$r->error = "";
		$r->html = $ret;
		return $this->getJsonRet($r);
		//return "------";
	}
	
	function setKategoriak(){
		$name = "kategoria_id";
		$value=$this->xmlParser->getAktVal($name);
		$arr = array();
		ob_start();
		$kategoriafa = new kategoriafa( );
		$o="";
		$o->option = $o-> value = "";
		array_unshift($kategoriafa ->catTree, $o);
		echo JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name, array("class"=>"alapinput", "readonly" => "readonly"), "value", "option", $value);
		?><input name="kategoria_kedvezmeny" id="kategoria_kedvezmeny" type="text" value="<?php echo $this->xmlParser->getAktval("kategoria_kedvezmeny") ?>"  /><?php
        $o="";
		$o->option = "%";
		$o-> value = "%";
		$arr[] =$o;
		$o = "";
		$o->option = jtext::_("OSSZEG");
		$o-> value = "OSSZEG";		
		$arr[] =$o;		
		$value = $this->xmlParser->getAktVal("kategoria_kedvezmeny_tipus");
		echo JHTML::_('Select.genericlist', $arr, "kategoria_kedvezmeny_tipus", array("class"=>"alapinput"), "value", "option", $value);
		?>
        <input type="button" onclick="mentKategoriaKedvezmeny()" value="<?php echo jtext::_("MENT_KEDVEZMENY") ?>" />
        <?php
		$ret = ob_get_contents();  		
		ob_end_clean();
		$r = "";
		$r->html = $ret;
		$r->error = "";
		return $this->getJsonRet( $r );
	}
	
}// class
?>