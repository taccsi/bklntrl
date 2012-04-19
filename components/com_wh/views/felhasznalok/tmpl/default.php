<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">		
    <h3 class="h3_contentheading"><?php echo JText::_("FELHASZNALOK") ?></h3>
	<div class="div_wh_topmenu">
	 <?php new whMenu; ?>  
	</div>    
</div>
<div class="div_wh_clear"></div>
<div id="editcell">
<form action="index.php" method="get" name="adminForm" id="adminForm" >
<div id="editcell">
<?php
	echo $this->search;
	if( jrequest::getVar("cond_webshop_id", "") || 0 ){
		$arr = array();
		if(count( $this->items )){
			$k = 0;
			global $Itemid;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row = &$this->items[$i];
				//print_r($row);
				$link = "index.php?option=com_wh&controller=felhasznalo&task=edit&fromlist=1&Itemid={$Itemid}&user_id={$row->user_id}&webshop_id={$row->webshop_id}";
				$o = "";
				$o->NEV = "<a href=\"{$link}\">{$row->name}</a>";
				$o->EMAIL = "{$row->email}";
				$o->TELEFON = "{$row->telefon}";				
				$o->SZAMLAZASI_CIM = "{$row->irszam} {$row->varos} {$row->utca} {$row->varos}";				
				$o->SZALLITASI_CIM = "{$row->sz_irszam} {$row->sz_varos} {$row->sz_utca}";
				$o->FELHASZNALOI_CSOPORT = "{$row->fcsoport}";				
				$o->ID = "{$row->id}";
				$o->USER_ID = "{$row->user_id}";
				
				//$o->RESZLETEK = "<a href=\"{$link}\">".jtext::_("RESZLETEK")."</a>";
				$arr[] = $o; 
				$k = 1 - $k;
			}
			$lista = new listazo($arr, "adminlist", $this->pagination->getPagesLinks(), $this->pagination->getPagesLinks() );
			echo $lista->getLista();
		}
	}else{
		echo jtext::_("KEREM_VALASSZON_WEBSHOPOT");
	}
?>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="felhasznalok" />
</form>
</div>