<?php
defined( '_JEXEC' ) or die( '=;)' );
?>
<div class="div_wh_top_holder">		
    <h3 class="h3_contentheading"><?php echo JText::_("WEBSHOPOK") ?></h3>    
</div>
<div class="div_wh_topmenu">
	<?php new whMenu; ?>  
</div>
<div class="div_wh_clear"></div>
<div>
<?php echo $this->search ?>
<form action="index.php" method="get" name="adminForm" id="adminForm" >
<div id="editcell">
	<table class="adminlist" id="adminlist">
	<tr>
      <td colspan="6">
      	<?php  echo $this->pagination->getPagesLinks(); ?> 
      </td>
    </tr>
    <tr>
        <th><?php echo JText::_("CEGNEV") ?></th>
         <th><?php echo JText::_("URL") ?></th>
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
			$link = JRoute::_( 'index.php?option=com_wh&controller=webshop&task=show&fromlist=1&Itemid='.$Itemid.$tmpl.'&cid[]='. $row->id );
			?>
			<tr class="<?php echo "row$k"; ?>">
           		 <td class="td_input">
                	<?php echo $checked; ?>
				</td>
				<td class="td_parname">
                	<span class="zoomTip" title='<?php echo $row->buborek; ?>' ><a href="<?php echo $link; ?>"><?php echo $row->nev; ?></a></span>
                </td>
				
                <td class="td_parname">
                	<a href="<?php echo $row->url; ?>" target="_blank"><?php echo $row->url; ?></a>
                	<input type="hidden" id="rn<?php echo $i; ?>" value="<?php echo str_replace('"','',$row->nev); ?>" />
                    <input type="hidden" id="ri<?php echo $i; ?>" value="<?php echo str_replace('"','',$row->ugyfelkod); ?>" />
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
</div>
<input type="hidden" name="option" value="com_wh" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="webshopok" />
<input type="hidden" name="tmpl" value="<?php echo JRequest::getVar('tmpl'); ?>" />
</form>
<div class="clr"></div></div>
