<?php
defined( '_JEXEC' ) or die( '=;)' );

class whpModelatvevohely extends whpPublic
{
	var $xmlFile = "atvevohely.xml";
	var $tmpname = "";
	var $table = "#__whp_atvevohely";
	var $w = 110;
	var $h = 155;
	var $w_kapcsolodo = 80;
	var $h_kapcsolodo = 112;

	var $mode = "resize";
	
	//var $table ="whp_kategoria";
	
	function __construct()
	{
		parent::__construct(); 
		$this->value = JRequest::getVar("value", "");
		$this->atvevohely_id = JRequest::getVar('atvevohely_id',0);
		$this->limitstart=0;
		$this->limit=1;
		//$this->getatvevohely();
	 	$this->xmlParser = new xmlatvevohely($this->xmlFile, "" /*$this->_data*/);
	}//function
	
	function _buildQuery()
		{
		$q = "SELECT atvevohely.*, ar.ar, kategoria.nev as kategorianev, afa.ertek as afaErtek FROM #__wh_atvevohely as atvevohely 
		inner join #__wh_kategoria as kategoria on atvevohely.kategoria_id = kategoria.id	
		left join #__wh_ar as ar on atvevohely.id = ar.atvevohely_id
		inner join #__wh_afa as afa on ar.afa_id = afa.id		
		where atvevohely.id = {$this->atvevohely_id} ";
		//echo $q;
		//$q = "select * from #__wh_product";
		return $q;
	}

	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->limitstart, $this->limit );
			//print_r($this->_data);
			echo $this->_db->getErrorMsg();
			//array_map ( array($this, "setListakep"), $this->_data) ;
			array_map ( array($this, "setBontasKep_ideiglenes"), $this->_data) ;
			array_map ( array($this, "setSzerzo"), $this->_data);
			array_map ( array($this, "setAr"), $this->_data);
			array_map ( array($this, "setKosar"), $this->_data );
			array_map ( array($this, "setTabok"), $this->_data );
			//array_map ( array($this, "setTermVar"), $this->_data );
			//array_map ( array($this, "setListaNev"), $this->_data );			
			//echo $this->_db->getErrorMsg();
		}
		//$this->_data = array_map(array($this,"propValue"), $rows);
		//print_r($this->_data);exit;
		return $this->_data;
	}//function
	
	function setTabok($item){
		ob_start();
			//print_r($item);
			jimport('joomla.html.pane');
			$pane =& JPane::getInstance('tabs', array('startOffset'=>1));
			$title = 'pane';
			$type = 'pane';
			echo $pane->startPane("content-pane");
				  
			// Render a param pane
				echo $pane->startPanel( JTEXT::_('FULSZOVEG'), 'panel_fulszoveg' );
				?>
					<div class="fulszoveg_content">
                    	<?php echo $item->leiras; ?>
                    </div>
				<?php 
				echo $pane->endPanel();
				echo $pane->startPanel( JTEXT::_('TARTALOM'), 'panel_tartalom' );
				?>
					<div class="fulszoveg_tartalom">
                    	<?php echo $item->tartalom; ?>
                    </div>
				<?php 
				echo $pane->endPanel();
				echo $pane->startPanel( JTEXT::_('SZERZO_EGYEB'), 'panel_kapcsolodo' );
				?>
					<div class="fulszoveg_tartalom">
                    	<?php echo $this->getKapcsolodo($item); ?>
                    </div>
				<?php 
			/*	echo $pane->endPanel();
				echo $pane->startPanel( JTEXT::_('KOMMENTAR'), 'panel__kommentar' );
				echo "This is panel3";
			echo $pane->endPanel();    */ 
			
			echo $pane->endPane();
		$tmp = ob_get_contents();
		ob_end_clean();
		$item->tabok = $tmp;
		return $item;
	}
	
	function getKapcsolodo($item){
		
			
			//echo ($idk); 
				$q = "SELECT atvevohely.*, ar.ar, kategoria.nev as kategorianev, afa.ertek as afaErtek FROM #__wh_atvevohely as atvevohely 
		inner join #__wh_kategoria as kategoria on atvevohely.kategoria_id = kategoria.id
		inner join #__wh_ar as ar on ar.atvevohely_id = atvevohely.id
		inner join #__wh_afa as afa on ar.afa_id = afa.id WHERE atvevohely.szerzo_id = {$item->szerzo_id} group by atvevohely.id";
				$this->_db->setQuery($q);
				//echo ($this->_db->getquery());
				$rows = $this->_db->loadObjectList();
			if (count($rows)){			
				//print_r($rows); die();
				array_map ( array($this, "setListaKep_kapcsolodo_ideiglenes"), $rows) ;
				array_map ( array($this, "setAr"), $rows);
				array_map ( array($this, "setKosar"), $rows );
				array_map ( array($this, "setSzerzo"), $rows);
				array_map ( array($this, "setListaNev"), $rows );			
				jimport("unitemplate.unitemplate");
				$uniparams->cols = 1;
				$uniparams->cellspacing = 0;
				$uniparams->templatePath = "components/com_whp/unitpl";
				$uniparams->pair = true;
				$ut = new unitemplate("list", $rows, "div", "kapcsolodo_atvevohelyek", $uniparams);
				$ret = $ut -> getContents(); 	
			} else {$ret = '';}
		return $ret;
	}
	
	
	function getatvevohely(){
		$rows=$this->getData();
		//print_r($rows);
		if(count($rows)>0){ // vannak sorok
			jimport("unitemplate.unitemplate");
			$uniparams->cols = 1;
			$uniparams->cellspacing = 0;
			$uniparams->templatePath = "components/com_whp/unitpl";
			$uniparams->pair = false;
			$ut = new unitemplate("bontas", $rows, "div", "atvevohely", $uniparams);
			$ret = $ut -> getContents(); 
		}else{
			$ret = "<div align=center>".JText::_("NINCS TALALAT")."</div>";			
		}
		return $ret;
	}
}// class
?>