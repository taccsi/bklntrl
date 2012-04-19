<?php
defined( '_JEXEC' ) or die( '=;)' );

if (count($this->items)):
$cegek = array();
foreach($this->items as $i){
	$cegek[] = $i->nev;
}
$cegek = array_unique($cegek);
?>
<div class="div_vcrm_forgalom">
	<table class="adminlist">
        <tr>
        	<td class="key"><?php echo JText::_("OSSZESEN") ?></td>
            <td class="value"><?php echo number_format(floatval($this->forgalom['forgalom']), 0, '', ' '); ?> HUF</td>
        </tr>
        <tr>
        	<td class="key"><?php echo JText::_("TELJESITETT") ?></td>
            <td class="value"><?php echo $this->forgalom['teljesitett'] . ' db '; ?></td>
        </tr>
        <tr>
        	<td class="key"><?php echo JText::_("OSSZES_SZAMLA") ?></td>
            <td class="value"><?php echo $this->forgalom['osszes'] . ' db '; ?></td>
        </tr>
    </table>
</div>
<?php endif; ?>

<div id="editcell">
	<table class="adminlist" id="adminlist">
	<?php
	if(count( $this->items )){		
		$k = 0;
		global $Itemid;
		for ($i=0, $n=count( $this->items ); $i < $n; $i++)
		{
			$row = &$this->items[$i];
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td class="td_parname">
					
                    <table class="adminlist_item" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="td_left"><?php echo JText::_("BIZONYLATSZAM") ?></td>
                        <td class="td_right"><?php echo $row->bizonylatszam; ?></td>
                      </tr>
                      <tr>
                        <td class="td_left"><?php echo JText::_("BIZONYLAT_KELTE") ?></td>
                        <td class="td_right"><?php echo $row->bizonylat_kelte; ?></td>
                      </tr>
                      <tr>
                        <td class="td_left"><?php echo JText::_("FIZETESI_MOD") ?></td>
                        <td class="td_right"><?php echo $row->fizetesi_mod; ?></td>
                      </tr>
                      <tr>
                        <td class="td_left"><?php echo JText::_("FIZETESI_HATARIDO") ?></td>
                        <td class="td_right"><?php echo $row->fizetesi_hatarido; ?></td>
                      </tr>
                      <tr>
                        <td class="td_left"><?php echo JText::_("TELJESITETT") ?></td>
                        <td class="td_right"><?php echo ($row->kiegyenlites_datuma>0) ? $row->kiegyenlites_datuma: 'függőben'; ?></td>
                      </tr>
                    </table>
                                       
				</td>
				<td class="td_input">
                <?php echo number_format(floatval($row->netto_ertek), 0, '', ' '); ?> HUF
                <?php if ($row->keses>0 && $row->kiegyenlites_datuma==0): ?><p class="keses"><?php echo $row->keses; ?> napja lejárt</p><?php endif; ?>
				</td>	
    		</tr>
			<?php
			$k = 1 - $k;
		}
	}else{?>
			<tr>
				<td colspan="7" >
					<div class="nincs_talalat" ><?php echo JText::_("NINCS TALALAT") ?></div>
				</td>
			</tr>
	<?php
	}
	?>
	 
	</table>
<div class="clr"></div></div>