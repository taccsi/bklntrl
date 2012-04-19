<?php
defined( '_JEXEC' ) or die( '=;)' );
jimport('joomla.application.component.model');
class whModelrendelesek extends modelBase
{
	var $limit = 20;
	function __construct(){
	 	parent::__construct();
		global $mainframe, $option;
		// Get pagination request variables
		$this->limitstart = JRequest::getVar( "limitstart", 0 ); 
		$this->xmlParser = new xmlParser("rendeles.xml");
	}//function

	function delete($jTable){
		//die("{$jTable}---");
		$cid = implode(",", jrequest::getvar("cid", array(), "request", "array"));
		$q = "delete from #__wh_rendeles where id in({$cid})";
		$this->_db->setQuery($q);
		$this->_db->Query();
		$q = "delete from #__wh_tetel where rendeles_id in ({$cid})";
		$this->_db->setQuery($q);
		$this->_db->Query();		
	}

	function getRendelesek(){
		ob_start();
		$arr = array();
		$this->items = $this->getData();
		$pagination = $this->getPagination();
		
		if(count( $this->items )){
			$k = 0;
			global $Itemid;
			$u_id=array();
			$u_name=array();
			$ws_id=array();
			$ws_name=array();
			for ($i=0, $n=count( $this->items ); $i < $n; $i++){
				//array_search();
				$row = &$this->items[$i];
				$checked = JHTML::_('grid.id',   $i, $row->id );
				$tmpl = JRequest::getVar('tmpl');
				$tmpl = ($tmpl) ? '&tmpl='.$tmpl : '';
				$link = JRoute::_( 'index.php?option=com_wh&controller=rendeles&task=edit&fromlist=1&Itemid='.$Itemid.$tmpl.'&cid[]='. $row->id );
				$o = "";
				$o->CHECKED = $checked;
				$o->IDSITE = "<a href=\"{$link}\">{$row->id}<br />{$row->ws_nev}</a>";
				$o->STATUSZ = $row->statusz;	
				@$o->VASARLO = $row->vasarlo;
				$o->TETELEK = $row->tetelek;			
				$o->RENDEL_ALLAPOT = "<span class=\"{$row->allapot}\">" . JText::_($row->allapot) . "</span>";
				$o->OSSZERTEK_SZALL_DIJ = ar::_($row->osszertek)."<br />(".ar::_($row->kiszallitas_ar).")";
				$pickpack_arr = $this->getPickPackAdatok($row);
				$pickpack_lista = new listazo($pickpack_arr, "pickpacklist","","","","1"); 
				$o->PICKPACK_ADATOK = $pickpack_lista->getlista();
				//print_r($row); die();
				//$o->FIKTIV_RENDELES = $row->fiktivRendeles;
				//$o->ALLAPOT_BESZERZES = "**";			
				$arr[] = $o; 
				$k = 1 - $k;
			}
			$lista = new listazo($arr, "adminlist", $pagination->getPagesLinks(), $pagination->getPagesLinks() );
			echo $lista->getLista();
		}else{
			echo jtext::_("NINCS TALALAT");
		}
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function getPickPackAdatok($row){
		$o='';
		if ($row->pickpack_atvhely_id) {$o->PICKPACK_ATVHELY_ID= $row->pickpack_atvhely_id;}
		if ($row->pickpack_atvhely_nev) {$o->PICKPACK_ATVHELY_NEV= $row->pickpack_atvhely_nev;}
		if ($row->pickpack_csomag_id) {$o->PICKPACK_CSOMAG_ID= $row->pickpack_csomag_id;}
		if ($row->pickpack_szallitmany_id) {$o->PICKPACK_SZALLITMANY_ID= $row->pickpack_szallitmany_id;}
		
		
		$arr = array();
		$arr[]=$o;
		return $arr;
		
	}
	
	function save(){
		//$this->hozzaadFiktivRendeles();
	}
	
	function masol($cid){
		foreach($cid as $id){
			$o = $this->getObj("#__wh_rendeles", $id);
			unset($o->id);
			$o->allapot = jtext::_("MASOLAT");
			$this->_db->insertObject("#__wh_rendeles", $o, "id");
			$uj_rendeles_id = $this->_db->insertId();
			$q = "select * from #__wh_tetel where rendeles_id = {$id}";
			$this->_db->setQuery($q);
			$tetelek = $this->_db->loadObjectList();
			//print_r($tetelek);
			//die;
			foreach($tetelek as $t){
				unset($t->id);
				$t->rendeles_id = $uj_rendeles_id;
				$this->_db->insertObject("#__wh_tetel", $t, "id");
			}
		}
		//$q = "select * from 
	}

	function getSearchArr(){
		$arr = array();
		$o = "";	
		$obj = "";	
		$name = "cond_vasarlo";
		$value = JRequest::getVar($name,'');
		$caption = 'VASARLO';
		$obj->VASARLO = $this->getSearchTextBox($name, $value, $caption);	
		//$arr[] = $obj;
		
		$obj = "";		
		$name = "cond_szamlaszam";
		$value = JRequest::getVar($name);
		$obj->SZAMLASZAM = $this->getSearchTextBox($name, $value, "SZAMLASZAM");	
		//$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_rendeles_id";
		$value = JRequest::getVar($name,'');
		$caption = 'RENDELES_ID';
		$obj->RENDELES_ID = $this->getSearchTextBox($name, $value, $caption);	
		$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_tetel_nev";
		$value = JRequest::getVar($name,'');
		$caption = 'TERMEKNEV';
		$obj->TERMEKNEV = $this->getSearchTextBox($name, $value, $caption);	
		$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_megrendeles_tol";
		$value = JRequest::getVar($name,'');
		$caption = 'MEGRENDELESTOL';
		$obj->MEGRENDELESTOL = JHTML::_('calendar', $value, $name, $name, "%Y-%m-%d", "");	
		$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_megrendeles_ig";
		$value = JRequest::getVar($name,'');
		$caption = 'MEGRENDELESIG';
		$obj->MEGRENDELESIG  = JHTML::_('calendar', $value, $name, $name, "%Y-%m-%d", "");
		$arr[] = $obj;
		
		/*
		$obj = "";	
		$name = "cond_kiszallitas_tol";
		$value = JRequest::getVar($name,'');
		$caption = 'KISZALLITASTOL';
		$obj->KISZALLITASTOL = JHTML::_('calendar', $value, $name, $name, "%Y-%m-%d", "");	
		$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_kiszallitas_ig";
		$value = JRequest::getVar($name,'');
		$caption = 'KISZALLITASIG';
		$obj->KISZALLITASIG  = JHTML::_('calendar', $value, $name, $name, "%Y-%m-%d", "");
		$arr[] = $obj;
		*/
		
		/*
		$obj = "";	
		$name = "cond_szallitas_admin";
		$value = JRequest::getVar($name, array(), "request", "array");
		//$obj->SZALLITAS_ADMIN = $this->getSearchCheckboxes($name, $this->xmlParser->getxmlarr('szallitas_admin'), $value );
	
		$rows=$this->xmlParser->getxmlarr('szallitas_admin');
		$o="";
		$o->value = "";
		$o->option = "";			
		array_unshift($rows, $o);
		$obj->SZALLITAS_ADMIN = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);		
		$arr[] = $obj;
		*/

		/*
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
		//$arr[] = $obj;
		*/
		/*
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
		//$arr[] = $obj;
		*/
		/*
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
		*/
		/*
		$obj = "";		
		$q = "select id as `value`, nev as `option` from #__wh_kiszallito";
		$this->_db->setQuery($q);
		$name = "cond_kiszallito_id";
		$value = JRequest::getVar($name);
		$rows = $this->_db->loadObjectList();		
		$o="";
		$o->value = $o->option = "";
		array_unshift($rows, $o);		
		$obj->KISZALLITO = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);
		$arr[] = $obj;
		*/
		$obj = "";	
		$name = "cond_allapot";
		$value = JRequest::getVar($name, array(), "request", "array");
		//$obj->SZALLITAS_ADMIN = $this->getSearchCheckboxes($name, $this->xmlParser->getxmlarr('szallitas_admin'), $value );
		$rows=$this->xmlParser->getxmlarr('allapot');	
		//print_r($rows);
		$o="";
		$o->value = $o->option = "";
		array_unshift($rows, $o);		
		$obj->ALLAPOT = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);
		$arr[] = $obj;
		//die( $obj->ALLAPOT );
		//$arr[] = $obj;
		return 	$arr;
	}
	function getSearchArr_(){
		$arr = array();
		$o = "";	
		$obj = "";	
		$name = "cond_vasarlo";
		$value = JRequest::getVar($name,'');
		$caption = 'VASARLO';
		$obj->VASARLO = $this->getSearchTextBox($name, $value, $caption);	
		$arr[] = $obj;
		
		$obj = "";		
		$name = "cond_gyariszam";
		$value = JRequest::getVar($name);
		$obj->GYARISZAM = $this->getSearchTextBox($name, $value, "GYARISZAM");	
		$arr[] = $obj;
		
		$obj = "";		
		$name = "cond_szamlaszam";
		$value = JRequest::getVar($name);
		$obj->SZAMLASZAM = $this->getSearchTextBox($name, $value, "SZAMLASZAM");	
		$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_rendeles_id";
		$value = JRequest::getVar($name,'');
		$caption = 'RENDELES_ID';
		$obj->RENDELES_ID = $this->getSearchTextBox($name, $value, $caption);	
		$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_csomagszam";
		$value = JRequest::getVar($name,'');
		$caption = 'CSOMAGSZAM';
		$obj->CSOMAGSZAM = $this->getSearchTextBox($name, $value, $caption);	
		$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_tetel_nev";
		$value = JRequest::getVar($name,'');
		$caption = 'TERMEKNEV';
		$obj->TERMEKNEV = $this->getSearchTextBox($name, $value, $caption);	
		$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_megrendeles_tol";
		$value = JRequest::getVar($name,'');
		$caption = 'MEGRENDELESTOL';
		$obj->MEGRENDELESTOL = JHTML::_('calendar', $value, $name, $name, "%Y-%m-%d", "");	
		$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_megrendeles_ig";
		$value = JRequest::getVar($name,'');
		$caption = 'MEGRENDELESIG';
		$obj->MEGRENDELESIG  = JHTML::_('calendar', $value, $name, $name, "%Y-%m-%d", "");
		$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_kiszallitas_tol";
		$value = JRequest::getVar($name,'');
		$caption = 'KISZALLITASTOL';
		$obj->KISZALLITASTOL = JHTML::_('calendar', $value, $name, $name, "%Y-%m-%d", "");	
		$arr[] = $obj;
		
		$obj = "";	
		$name = "cond_kiszallitas_ig";
		$value = JRequest::getVar($name,'');
		$caption = 'KISZALLITASIG';
		$obj->KISZALLITASIG  = JHTML::_('calendar', $value, $name, $name, "%Y-%m-%d", "");
		$arr[] = $obj;
		
		/*
		$obj = "";	
		$name = "cond_szallitas_admin";
		$value = JRequest::getVar($name, array(), "request", "array");
		$obj->SZALLITAS_ADMIN = $this->getSearchCheckboxes($name, $this->xmlParser->getxmlarr('szallitas_admin'), $value );
		$arr[] = $obj;
		*/
		$obj = "";	
		$name = "cond_atvetel_modja";
		$value = JRequest::getVar($name, array(), "request", "array");
		$obj->ATVETEL_MODJA = $this->getSearchCheckboxes($name, $this->xmlParser->getxmlarr('atvetel_modja'), $value );
		$arr[] = $obj;

		$obj = "";		
		$name = "cond_allapot";
		$value = JRequest::getVar($name, array(), "request", "array");
		//print_r($this->xmlParser->getxmlarr('allapot'));
		//die;
		$obj->ALLAPOT = $this->getSearchCheckboxes($name, $this->xmlParser->getxmlarr('allapot'), $value );
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
		$q = "select id as `value`, nev as `option` from #__wh_beszallito";
		$this->_db->setQuery($q);
		$name = "cond_beszallito_id";
		$value = JRequest::getVar($name);
		$rows = $this->_db->loadObjectList();		
		$o="";
		$o->value = $o->option = "";
		array_unshift($rows, $o);		
		$obj->BESZALLITO = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);
		$arr[] = $obj;

		$obj = "";		
		$q = "select id as `value`, nev as `option` from #__wh_kiszallito";
		$this->_db->setQuery($q);
		$name = "cond_kiszallito_id";
		$value = JRequest::getVar($name);
		$rows = $this->_db->loadObjectList();		
		$o="";
		$o->value = $o->option = "";
		array_unshift($rows, $o);		
		$obj->KISZALLITO = JHTML::_('Select.genericlist', $rows, $name, array( "class"=>"alapinput" ), "value", "option", $value);
		$arr[] = $obj;
		
				
		//die( $obj->ALLAPOT );
		$arr[] = $obj;
		return 	$arr;
	}

	function _buildQuery()
	{
		$cond = $this->getCond();	
		$query = "SELECT r.*, ws.nev as ws_nev FROM #__wh_rendeles as r 
		left join #__wh_webshop as ws on r.webshop_id = ws.id 
		left join #__wh_tetel as tetel on r.id  = tetel.rendeles_id
		{$cond} group by r.id order by r.id desc";
		//echo $cond;
		//echo $query;;
		//die;
		return $query;
	}
	function getData()
	{
		
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->limitstart, $this->limit );
			//echo $this->_db->getQuery();			
			@$this->_data = array_map ( array($this, "setVasarlo"), $this->_data) ;
			@$this->_data = array_map ( array($this, "setMegrendelesIdeje"), $this->_data) ;			
			@$this->_data = array_map ( array($this, "setTetelek"), $this->_data) ;
			@$this->_data = array_map ( array($this, "setOsszertek"), $this->_data) ;
			//@$this->_data = array_map ( array($this, "setFiktivRendeles"), $this->_data) ;			
			echo $this->_db->getErrorMsg();
		}

		//$this->_data = array_map(array($this,"propValue"), $rows);
		//print_r($this->_data);exit;
		return $this->_data;
	}//function
	
	function setOsszertek($item){
		//die("---");
		$q = "select  sum( netto_ar * quantity *( afa/100 + 1 )  ) as osszertek from #__wh_tetel where rendeles_id = {$item->id}";
		//kiszallitas_ar
		$this->_db->setQuery($q);
		$o = $this->_db->loadObject();
		$item->osszertek =  $o->osszertek ;
		return $item;
	}
	
	function setTetelek($item){
		$q = "select nev as HIDDEN from #__wh_tetel where rendeles_id = {$item->id}";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();
		$listazo = new listazo($rows, "");
		$item->tetelek = $listazo->getlista();
		return $item;
	}
	
	function setMegrendelesIdeje($item){
		$item->statusz= "<div class=\"div_beerkezett\" >{$item->datum}</div>";
		$k = time()-strtotime($item->datum);
		$nap = floor($k/60/60/24);
		$ora = floor( ($k-$nap*60*60*24)/60/60 );
		$perc = floor ( ($k-($nap*60*60*24+$ora*60*60))/60);
		$item->statusz.="<div class=\"div_oraperc\" >{$nap} ".jtext::_("NAP")." {$ora} ".jtext::_("ORA")." {$perc} ".jtext::_("PERC")."</div>";
		return $item;
	}
	
	function getTotal()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_total))
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