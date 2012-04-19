<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">		
    <h3 class="h3_contentheading"><?php echo JText::_("BEVASARLO LISTA") ?></h3>
	<div class="div_wh_topmenu">
	 <?php new whMenu; ?>  
	</div>    
</div>

<div class="div_wh_clear"></div>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="adminForm" >
<div id="editcell">
<?php
	echo $this->searchtetelek;
	$arr = array();
	if(count( $this->tetelek )){
		$k = 0;
		global $Itemid;
		$u_id=array();
		$u_name=array();
		$ws_id=array();
		$ws_name=array();
		
		for ($i=0, $n=count( $this->tetelek ); $i < $n; $i++)
		{
			//array_search();
			$row = &$this->tetelek[$i];
			$checked = JHTML::_('grid.id',   $i, $row->id );
			$tmpl = JRequest::getVar('tmpl');
			$tmpl = ($tmpl) ? '&tmpl='.$tmpl : '';
			$link = JRoute::_( 'index.php?option=com_wh&controller=rendeles&task=edit&fromlist=1&Itemid='.$Itemid.$tmpl.'&cid[]='. $row->id );
			$o = "";
			$o->CHECKED = $checked;
			$o->IDSITE = "<a href=\"{$link}\">{$row->id}<br />{$row->ws_nev}</a>";
			//print_r($row);		
			//die;
			@$o->VASARLO = "<a href=\"{$link}\">{$row->vasarlo->user->name}<br />tel.: {$row->vasarlo->felhasznalo->telefon}</a>";
			$o->RENDEL_FIZETES = JText::_($row->fizetes);
			$o->TETELEK = $row->tetelek;			
			$o->RENDEL_SZALLITAS = JText::_($row->szallitas);
			//$o->RENDEL_DATUM = date($row->datum);
			$o->STATUSZ = $row->statusz;
			$o->RENDEL_ALLAPOT = JText::_($row->allapot);
			$arr[] = $o; 
			$k = 1 - $k;
		}
		$lista = new listazo($arr, "adminlist", $this->pagination->getPagesLinks(), $this->pagination->getPagesLinks() );
		echo $lista->getLista();
	}else{
		echo jtext::_("NINCS TALALAT");
	}
?>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="kimutatas" />
<?php if( JRequest::getVar('tmpl') ){
?>
<input type="hidden" name="tmpl" value="<?php echo JRequest::getVar('tmpl'); ?>" />
<?php
}
?>
</div>
</form>