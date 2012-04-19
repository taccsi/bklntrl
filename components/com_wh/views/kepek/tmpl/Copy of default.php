<?php
defined( '_JEXEC' ) or die( '=;)' );
//echo jRequest::getVar("kapcsolodo_id");
?>
<div class="div_wh_top_holder">		
    <h3 class="h3_contentheading"><?php echo JText::_("KEPEK") ?></h3>    
	<?php 
	if( JRequest::getVar("tmpl") == "component" ){
		$arr = array();
	}else{
		$arr = array(JText::_("KIVALASZT") );
	}		
	?>
</div>
<div class="div_wh_topmenu">
	<?php new whMenu($arr); ?>  
</div>

<div class="div_wh_clear"></div>
<div id="editcell">
<form action="index.php" method="get" name="adminForm" id="adminForm" >
	<table class="adminlist" id="adminlist">
	<tr>
      <td colspan="6">
      	<?php //echo $this->pagination->getPagesLinks(); ?> 
      </td>
    </tr>
    <tr>
        <th><?php echo JText::_("KEPEK") ?></th>
        <th></th>
    </tr>
	<?php
	if(count( $this->items )){
		$k = 0;
		global $Itemid;
		for ($i=0, $n=count( $this->items ); $i < $n; $i++)
		{
			$row = &$this->items[$i];
			$checked = JHTML::_('grid.id',   $i, $row->id );
			$tmpl = JRequest::getVar('tmpl');
			$tmpl = ($tmpl) ? '&tmpl='.$tmpl : '';
			$link = JRoute::_( 'index.php?option=com_wh&controller=kep&task=show&fromlist=1&Itemid='.$Itemid.$tmpl.'&cid[]='. $row->id );
			?>
			<tr class="<?php echo "row$k"; ?>">
            	<td class="td_input">
					<?php echo $checked; ?>
				</td>	
				<td class="td_parname">
               		<a href="<?php echo $link; ?>"><img src="media/wh/termekek/<?php echo $row->id; ?>_1.jpg" width="65" alt="" /></a>
                </td>
    		</tr>
			<?php
			$k = 1 - $k;
		}
	}else{?>
			<tr>
				<td colspan="6" >
					<div class="nincs_talalat" ><?php echo JText::_("NINCS TALALAT") ?></div>
				</td>
			</tr>
	<?php
	}
	?>
	<tr>
      <td colspan="6">
      	<?php echo $this->pagination->getPagesLinks(); ?> 
      </td>
    </tr> 
	</table>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" id="controller" value="kepek" />
<input type="hidden" name="tmpl" value="<?php echo JRequest::getVar('tmpl'); ?>" />
</form>
</div>