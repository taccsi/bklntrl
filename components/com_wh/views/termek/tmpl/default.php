<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">
  <h3 class="h3_contentheading"><?php echo JText::_("TERMEK") ?></h3> 

<div class="div_wh_topmenu">

<?php 
	if( JRequest::getVar("tmpl") == "component" ){
		$arr = array();
	}else{
		$arr = array(JText::_("M") );
	}	
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
			$pane =& JPane::getInstance('tabs', 0);
            echo $pane->startPane( 'pane' );
            echo $pane->startPanel( JText::_("FOADATOK"), 'panel0' );
            echo html_entity_decode($this->allGroups["maindata"]);
            echo $pane->endPanel();
            echo $pane->startPanel( JText::_("TERMEKVARIACIOK"), 'panel1' );
            echo html_entity_decode($this->allGroups["termvarok"]);
            echo $pane->endPanel();
            
			echo $pane->startPanel( JText::_("FAJLOK"), 'panel1' );
            echo html_entity_decode($this->allGroups["fajlok"]);
            echo $pane->endPanel();
			echo $pane->startPanel( JText::_("KAPCSOLODO_TERMEKEK"), 'panel1' );
            echo html_entity_decode($this->allGroups["kapcsolodo_termekek"]);
            echo $pane->endPanel();
			echo $pane->startPanel( JText::_("CIMKEK"), 'panel1' );
			$document = jfactory::getDocument();
			$document->addscriptdeclaration("\$j(document).ready(function(){ listazCimkek() } )");
			?>
            <div class="cimkelista" style="margin-left:15px;">
              <div class="div_cimkeform"><?php echo $this->cimkeForm; ?></div>
              <div id="ajaxContentCimkek"></div>
            </div>
			<?php
            
            echo $pane->endPanel();
            
            foreach ($this->lang_forms as $title =>$form){
            	echo $pane->startPanel( $form->title, 'panel1' );
	            echo $form->tabContent;
	            echo $pane->endPanel();
            }
            
            echo $pane->endPane();
            ?>            
            <input type="hidden" name="option" value="com_wh" />
           
            <input type="hidden" name="task" id="task" value="" />
            <input type="hidden" name="controller" value="termek" />
            </form>
        </td>
        <td class="td_jobb">
			<?php
            $pane =& JPane::getInstance('tabs', 0);
            echo $pane->startPane( 'pane_kepek' );
            echo $pane->startPanel( JText::_("KEPEK"), 'panel0_' );
			echo html_entity_decode($this->allGroups["kepek"]);
            echo $pane->endPanel();
            echo $pane->endPane();
			?>
		</td>
      </tr>
    </table>

