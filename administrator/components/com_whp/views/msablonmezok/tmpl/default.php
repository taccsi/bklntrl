<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<form action="index.php" method="get" name="adminForm" id="adminForm" class="adminForm" >
<?php
	echo $this->search;
	$arr = array();
	if(count( $this->items )){
		$k = 0;
		$Itemid = $this->Itemid;
		$u_id=array();
		$u_name=array();
		$ws_id=array();
		$ws_name=array();
		
		for ($i=0, $n=count( $this->items ); $i < $n; $i++)
		{
			//array_search();
			$row = &$this->items[$i];
			$checked = JHTML::_('grid.id',   $i, $row->id );
			$tmpl = JRequest::getVar('tmpl');
			$tmpl = ($tmpl) ? '&tmpl='.$tmpl : '';
			$link = JRoute::_( 'index.php?option=com_whp&controller=msablonmezo&task=edit&fromlist=1&Itemid='.$Itemid.$tmpl.'&cid[]='. $row->id );
			$o = "";
			$o->CHECKED = $checked;
			$o->NEV = "<a href=\"{$link}\" >{$row->nev}</a>";
			$arr[] = $o; 
			$k = 1 - $k;
		}
		$lista = new listazo($arr, "adminlist", "", $this->pagination->getListFooter() );
		echo $lista->getLista();
	}else{
		echo jtext::_("NINCS TALALAT");
	}
?>
<input type="hidden" name="option" value="com_whp" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="msablonmezok" />
</form>