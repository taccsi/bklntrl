<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');

class whModelkategoriak extends modelBase
{
	function __construct()
	{
	 	parent::__construct();
		global $mainframe, $option; 
		// Get pagination request variables
		$limit = $this->limit;
		$this->limitstart = JRequest::getVar( "limitstart", 0 ); 
		$this->xmlParser = new xmlParser("kategoria.xml");	
		$this->letrehozWebshopKategoria();
	}//function
	
	function letrehozWebshopKategoria(){
		$q = "select webshop.id, webshop.nev as webshop_nev, kategoria.id as kategoria_id 
		from #__wh_webshop as webshop
		left join #__wh_kategoria as kategoria on webshop.nev = kategoria.nev
		
		";
		$this->_db->setQuery($q);
		$arr = $this->_db->loadObjectList();
		echo $this->_db->geterrorMsg();
		foreach( $arr as $o ){
			if(!$o->kategoria_id){
				$k = "";
				$k->nev = $o->webshop_nev;
				$k->szulo = 0;
				$k->aktiv = "igen";
				$k->webshop_id = $o->id;
				$this->_db->insertObject( "#__wh_kategoria", $k, "id" );
				//print_r($k);
			}
		}
	}
	
	function getSearchArr(){
		$arr = array();
		$obj = "";

		$name = "cond_kategoria_szulo";
		$value = JRequest::getVar($name);
		$kategoriafa = new kategoriafa( array(), 0 );
		$o="";
		$o->value = $o->option = "";
		//array_unshift($kategoriafa ->catTree, $o);
		
		$obj->KATEGORIAFA_SZULO = JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name, array( "class"=>"alapinput", "onchange"=>"getFokatSelect();getKategoriak();" ), "value", "option", $value);
		$arr[] = $obj;
		$obj = "";		
		$this->document->addScriptDeclaration('$j(document).ready(function(){ getFokatSelect(); });');		
		$obj->KATEGORIA = "<span id=\"ajaxContentFokatSelect\"></span>";
		//$arr[] = $obj;	
		return 	$arr;
	}

	function getFokatSelect(){
		ob_start();
		$cond_kategoria_szulo = JRequest::getVar( "cond_kategoria_szulo", "" );
		$this->setSessionVar("cond_kategoria_szulo", $cond_kategoria_szulo);		
		
		$name = "cond_kategoria_id";
		$value = "";
		$this->setSessionVar("cond_kategoria_id", "" );
		$kategoriafa = new kategoriafa( array(), 1, $cond_kategoria_szulo );
		//echo $cond_kategoria_szulo." ----";
		$o="";
		$o->value = $o->option = "";
		array_unshift( $kategoriafa->catTree , $o);
		echo JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name, array( "class"=>"alapinput", "onchange"=>"getKategoriak()" ), "value", "option", $value);
		$ret = ob_get_contents();
		ob_end_clean();
		$r="";
		$r->html=$ret
		->error="";
		return $this->getJsonRet($r);
	}

	function setKategoriaTable(){
		$cond_kategoria_tipus = jrequest::getVar( "cond_kategoria_tipus", "ADMINFA" );
		if( $cond_kategoria_tipus == "ADMINFA" ){
			$this->table = "#__wh_kategoria";
		}else{
			$this->table = "#__wh_kategoria_site";			
		}
	}

	function getKategoriak(){
		ob_start();
		//$cond_kategoria_id = jrequest::getVar( "cond_kategoria_id" );
		//($cond_kategoria_id) ? $this->setSessionVar("cond_kategoria_id", $cond_kategoria_id) : $cond_kategoria_id = $this->getSessionVar("cond_kategoria_id");
		$cond_kategoria_szulo = jrequest::getVar( "cond_kategoria_szulo" );
		$this->kategoriafa = new kategoriafa( array(), 10000000, $cond_kategoria_szulo );
		$db=0;
		if( count( $this->kategoriafa->catTree ) ){
			foreach($this->kategoriafa->catTree as $c){
				$link = "index.php?option=com_wh&controller=kategoria&task=edit&cid[]={$c->value}&fromlist=1";
				( $c->value != $cond_kategoria_szulo ) ? $nyilak = $this->sorrendNyilak( $c ) : $nyilak = "";
				?>
				<input type="checkbox" id="cb<?echo$db;?>" name="kat_check[]" value="<?php echo $c->value; ?>" /><a href="<?php echo $link ?>"><?php echo $c->option; ?> </a> <?php echo $nyilak; ?> <input name="sorrend[]" value="<?php echo $c->value; ?>" type="hidden" /><br />
				<?php
			$db++;
			}
		}else{
			echo jtext::_("NINCS_FELVITT_KATEGORIA");
		}
		$tree = ob_get_contents();
		ob_end_clean();
		$r="";
		$r->html = $tree;
		$r->error="";
		return $this->getJsonRet($r);
	}

	function sorrendNyilak( $obj ){
		ob_start();
		global $Itemid;
		$q = "select count(id) as osszes from #__wh_kategoria where szulo = {$obj->szulo} and id <> {$obj->value}";
		$this->_db->setQuery($q);
		$osszes = $this->_db->loadResult();
		if($osszes){
			if($obj->sorrend < ($osszes) ){
				$link= "javascript:void(0);";
				$js = "javascript:sorrendKategoria('".$obj->value."', 'le');";
				?>
				<a href="<?php echo $link ?>" onclick="<?php echo $js ?>" ><img src="components/com_wh/assets/images/downarrow.png" /></a>
				<?php
			}
			if( $obj->sorrend > 0 ){
				$link= "javascript:void(0);";
				$js = "javascript:sorrendKategoria('".$obj->value."', 'fel');";
				?>
				<a href="<?php echo $link ?>" onclick="<?php echo $js ?>" ><img src="components/com_wh/assets/images/uparrow.png" /></a>
				<?php
			}		
		}
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function sorrendKategoria(){
		ob_start();
		$irany = JRequest::getVar("irany");
		$sorrendOsszes = JRequest::getVar("sorrend", array() );		
		$sorrendId = JRequest::getVar("sorrendId");				
		$obj = $this->getObj($sorrendId);
		$q = "select id from #__wh_kategoria where szulo = {$obj->szulo} order by sorrend ";
		$this->_db->setQuery($q);
		$sorrendOsszes = $rows = $this->_db->loadResultArray();
		$sorrend = array();
		foreach($sorrendOsszes as $s){
			if(in_array($s, $rows)){
				$sorrend[]=$s;
			}
		}
		$ind = array_search($sorrendId, $sorrend);			
		if($irany=="le"){
			$temp = $sorrend[$ind+1];
			$sorrend[$ind+1] = $sorrendId;
			$sorrend[$ind] = $temp;
		}else{
			$temp = $sorrend[$ind-1];
			$sorrend[$ind-1] = $sorrendId;
			$sorrend[$ind] = $temp;
		}
		
		foreach($sorrend as $id){
			$ind = array_search($id, $sorrend);
			$q = "update #__wh_kategoria set sorrend = '{$ind}' where id = {$id} ";
			$this->_db->setQuery($q);
			$this->_db->Query();
			//die( $this->_db->getErrorMsg() );
		}
		$arr = array();
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret.$this->getKategoriak();
	}


	function sorrend(){
		$irany = JRequest::getVar("irany");
		$sorrendOsszes = JRequest::getVar("sorrend", array() );		
		$sorrendId = JRequest::getVar("sorrendId");				
		$obj = $this->getObj($sorrendId);
		$q = "select id from #__wh_kategoria where szulo = {$obj->szulo}";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadResultArray();
		$sorrend = array();
		foreach($sorrendOsszes as $s){
			if(in_array($s, $rows)){
				$sorrend[]=$s;
			}
		}
		$ind = array_search($sorrendId, $sorrend);			
		if($irany=="le"){
			$temp = $sorrend[$ind+1];
			$sorrend[$ind+1] = $sorrendId;
			$sorrend[$ind] = $temp;
		}else{
			$temp = $sorrend[$ind-1];
			$sorrend[$ind-1] = $sorrendId;
			$sorrend[$ind] = $temp;
		}
		
		foreach($sorrend as $id){
			$ind = array_search($id, $sorrend);
			$q = "update #__wh_kategoria set sorrend = '{$ind}' where id = {$id} ";
			$this->_db->setQuery($q);
			$this->_db->Query();
			//die( $this->_db->getErrorMsg() );
		}
		$arr = array();
	}
	
	function getObj($id){
		$q = "select * from #__wh_kategoria where id = {$id}";	
		$this->_db->setQuery($q);
		$obj = $this->_db->loadObject();
		//die( $this->_db->getErrorMsg() );
		return $obj;
	}
	
	function delete(){
		$arrNemTorolheto=array();
		foreach( JREquest::getVar("kat_check", "")  as $id){
			if( $this->torolhetoKat($id) ){
				$q="delete from #__wh_kategoria where id = {$id}";
				$this->_db->setQuery($q);
				$this->_db->query();
			}else{
				$arrNemTorolheto[] = $id;
			}		
		}
		//print_r($arrNemTorolheto);
		return $arrNemTorolheto;
		//die;
	}
	
	function torolhetoKat($id){
		//die;
		$q="select * from #__wh_kategoria where szulo = {$id}";
		$this->_db->setQuery($q);
		if($this->_db->loadResult()){
			return 0;
		}//van gyereke a katnak
		
		$q="select * from #__wh_kategoria where id = {$id}";
		$this->_db->setQuery($q);
		$obj = $this->_db->loadObject();
		
		/*$q="select kategoria_id from #__wh_termek as x inner join #__wh_kategoria as c 
		where c.lft >= {$obj->lft} and c.rgt >= {$obj->rgt}";
		$this->_db->setQuery($q);
		$obj = $this->_db->loadObject();*/
		
		$q="select kategoria_id from #__wh_termek where kategoria_id={$id}";
		$this->_db->setQuery($q);
		$obj = $this->_db->loadObject();
		if($this->_db->loadResult()){
			return 0;
		}
		return 1;
	}


}// class
?>