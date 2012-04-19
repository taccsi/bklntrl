<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("KATEGORIA") ?></h3>
  	<div class="div_wh_topmenu">
		<?php new whMenu; ?>  
	</div>
</div>

<div class="div_wh_clear"></div>
<div id="editcell">
<form method="post" enctype="multipart/form-data" id="adminForm" >
<?php 
	$pane =& JPane::getInstance('tabs', 0);
    echo $pane->startPane( 'pane' );
    echo $pane->startPanel( JText::_("FOADATOK"), 'panel0' );
    echo html_entity_decode($this->allGroups["maindata"]);
    echo $pane->endPanel();
	foreach ($this->lang_forms as $title =>$form){
    	echo $pane->startPanel( $form->title, 'panel1' );
        echo $form->tabContent;
        echo $pane->endPanel();
    }
	
	echo $pane->endPane();
	

?>
<?php  ?>
<?php // echo  /*$this->images*/ html_entity_decode($this->allGroups["kepek"]); ?>
<input type="hidden" name="controller" value="kategoria" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="option" value="com_wh" />
</form>
</div>
