<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("TERMEK") ?></h3> 

<div class="div_wh_topmenu">

<?php 
	
		$arr = array(JText::_("M"),JText::_("MENT"),JText::_("CANCEL") );
	
	?>

	<?php new whMenu($arr); ?>
</div>
</div>
<div class="div_wh_clear"></div>

<?php

	?>
	<table class="table_element" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="td_bal">
            <form action="index.php" method="post" enctype="multipart/form-data" id="adminForm" name="adminForm" class="noborderForm">
			<?php 
			$pane = JPane::getInstance('tabs', array('startOffset'=>0, 'allowAllClose'=>false, 'opacityTransition'=>true, 'duration'=>600));
            echo $pane->startPane( 'pane_x' );
            echo $pane->startPanel( JText::_("FOADATOK"), 'pane0' );
            echo html_entity_decode($this->allGroups["maindata_kapcsolodo"]);
            echo $pane->endPanel();
			           
            foreach ($this->lang_forms as $title =>$form){
            	echo $pane->startPanel( $form->title, 'panel1' );
	            echo $form->tabContent;
	            echo $pane->endPanel();
            }
            
            echo $pane->endPane();
            ?>            
            <input type="hidden" name="option" value="com_wh" />
           <input type="hidden" name="layout" value="kapcsolodo" />
            <input type="hidden" name="task" id="task" value="" />
            <input type="hidden" name="controller" value="termek" />
            </form>
        </td>
        
      </tr>
    </table>

