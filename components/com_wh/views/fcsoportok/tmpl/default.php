<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">		
    <h3 class="h3_contentheading"><?php echo JText::_("Felhasználói csoportok") ?></h3>
	<div class="div_wh_topmenu">
	 <?php new whMenu; ?>  
	</div>    
</div>
<div class="div_wh_clear"></div>
<div id="editcell">
<form action="index.php" method="get" name="adminForm" id="adminForm" >

<?php
	//echo $this->search;
	  $arr = array();
	  if(count( $this->items )){
		  $k = 0;
		  global $Itemid;
		  for ($i=0, $n=count( $this->items ); $i < $n; $i++)
		  {
			  $row = &$this->items[$i];
			  $link = "index.php?option=com_wh&controller=fcsoport&task=edit&fromlist=1&Itemid={$Itemid}&cid[]={$row->id}";
			  $o = "";
			$checked = JHTML::_('grid.id',   $i, $row->id );			 
			$o->CHECKED = $checked;			  
			  //$o->ID = "{$row->id}";
			  $o->NEV = "<a href=\"{$link}\">{$row->nev}</a>";
			  //$o->RESZLETEK = "<a href=\"{$link}\">".jtext::_("RESZLETEK")."</a>";
			  $arr[] = $o; 
			  $k = 1 - $k;
		  }
		  $lista = new listazo($arr, "adminlist", $this->pagination->getPagesLinks(), $this->pagination->getPagesLinks() );
		  echo $lista->getLista();
	  }

?>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="fcsoportok" />
</form>
</div>