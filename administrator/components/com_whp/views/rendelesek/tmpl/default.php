<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<form action="index.php" method="get" name="adminForm" id="adminForm" class="adminForm" >
<?php
	//echo $this->search;
	$arr = array();
	if(count( $this->items )){
		for ($i=0, $n=count( $this->items ); $i < $n; $i++)
		{
			//array_search();
			$row = &$this->items[$i];
			$checked = JHTML::_('grid.id',   $i, $row->id );
			$link = JRoute::_( "index.php?option=com_whp&controller=rendeles&task=edit&fromlist=1&cid[]={$row->id}" );
			$o = "";
			$o->CHECKED = $checked;
			$o->RENDELES_AZON = "<a href=\"{$link}\" >{$row->id}</a>";			
			$o->DATUM = "<a href=\"{$link}\" >{$row->datum}</a>";
			$o->VASARLO = "<a href=\"{$link}\" >{$row->vasarlo}</a>";			
			$o->HIDDEN = "";			
			$arr[] = $o; 
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
<input type="hidden" name="controller" value="rendelesek" />
</form>