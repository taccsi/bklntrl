<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlmsablon extends xmlParser{

	function getMezoId($node){
		ob_start();
		$name = $node->getAttribute('name');
		$value=$this->getaktVal($name);
		$id=$this->getaktVal("id");
		$selectMezoLink = "index.php?option=com_wh&controller=msablon_mezok&kapcsolodo_id={$id}&tmpl=component";
		?>
        <input name="<?php echo $name ?>" id="<?php echo $name ?>" value="<?php echo $value ?>" type="hidden"  />
 <a href="<?php echo $selectMezoLink ?>" rel="lightbox[x]" rev="width: 800px height:500px" ><?php echo  JText::_("MEZO HOZZAADASA") ;  ?></a>        
		<?php
		if( $value ) {
			
			$mezo_id_ = "0".$value."0";
			//$db = 0;
			$curent_table = "#__wh_msablon";
			$mezo = "mezo_id";
			$arr = array();
			
			foreach(explode(",", $mezo_id_) as $id_){
				$this->db->setQuery("SELECT * FROM #__wh_msablon_mezo WHERE id = {$id_}");				
				$rows = $this->db->loadObjectList();			
				foreach($rows as $row){
					$torol_link = "if(confirm('".JText::_("ARE YOU SURE")."')){ $('torol_id').value={$row->id}; $('task').value='torolMezo'; $('adminForm').submit(); }";
					$obj = "";
					$obj -> NEV = $row->nev;
					$obj -> AKTIV = $row->aktiv;
					$obj -> TOROL = "<input type=\"button\" onclick=\"{$torol_link}\" value=\"".JText::_("TORLES")."\" >";
					$obj -> SORREND = $this->sorrendNyilak($id,$row->id, $curent_table, $mezo)."<input name=\"sorrend[]\" value=\"{$row->id}\" type=\"hidden\" />";
					$arr[] = $obj;
				}
			}
			echo $this->kapcsListaz($arr);
		}
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;	
	}//functon
		
	function getCountry(){
		$this->db->setQuery("select `name` as `option`, id as `value` from #__ic_country order by `name` asc");
		$rows = $this->db->loadObjectList();
		$country= modelBase::getSessionVar("country_id");
		if(JRequest::getVar("fromlist","")){
			$cid = JRequest::getVar("cid",array());
			$obj = $this->getObj($cid[0]);
			@$country = explode(",", $obj->country_id);
		}
		return JHTML::_('Select.genericlist', $rows, "country_id[]", array("multiple"=>"multiple"), "value", "option", $country);
	}//functon

	function getObj($id){
		$q = "select * from #__ic_beszallito where id = {$id}";
		$db = JFactory::getDBO();
		$db->setQuery($q);
		return $db->loadObject();
	}//functon
	
	/*function lepteto($mezo_id, $id){
	ob_start();
	$mozgat_fel = "index.php?option=com_wh&controller=msablon&task=edit&fromlist=1&Itemid=9&cid[]=1&melyiket={$id}&fel=1";
	$mozgat_le = "index.php?option=com_wh&controller=msablon&task=edit&fromlist=1&Itemid=9&cid[]=1&melyiket={$id}&le=1";
	$melyiket = JRequest::getVar("melyiket", "");
	//echo $mezo_id;
	echo $id;
	if($id == $melyiket){
		$mezo_id_arr = array();
		$mezo_id_arr = explode(',',$mezo_id);
		print_r($mezo_id_arr);
	}
	?>
    <a href="<?php echo $mozgat_fel; ?>">
    	<img src="/webholding/components/com_wh/assets/images/uparrow.png" />
    </a>
    <a href="<?php echo $mozgat_le; ?>">
    	<img src="/webholding/components/com_wh/assets/images/downarrow.png" />
    </a> 
    
    <input type="hidden" name="mozgat_mezo_fel" id="mozgat_mezo_fel" value="" />  
	<?php	
	$ret = ob_get_contents();	
	ob_end_clean();
	return $ret;
	}*///functon
	
	function mezoListaz($mezo_id,$id){
	$curent_table = "#__wh_msablon";
	$mezo = "mezo_id";
	if($mezo_id != ","){
	ob_start();
	?>
    <div class="table_padding">
	<table class="table_sablon">
        <tr>
        	<th class="th_sablon"><?php echo JText::_("NEV"); ?></th>
            <th class="th_sablon"><?php echo JText::_("TIPUS"); ?></th>
            <th class="th_sablon"><?php echo JText::_("SUFFIX"); ?></th>
            <th class="th_sablon"><?php echo JText::_("KERESO"); ?></th>
            <th class="th_sablon"><?php echo JText::_("HIRLEVEL"); ?></th>
            <th class="th_sablon"><?php echo JText::_("AKTIV"); ?></th>
            <th class="th_sablon"><?php echo JText::_("TOROL"); ?> </th>
            <th class="th_sablon"><?php echo JText::_("RENDEZ"); ?></th>
        </tr>
	<?php
		$mezo_id_ = "0".$mezo_id."0";
		$mezo_id_arr = array();
		$mezo_id_arr = explode(",",$mezo_id_);
		$db = 0;
		for($i=0; $i<count($mezo_id_arr); $i++){
			if($mezo_id_arr[$i]>0){
			$this->db->setQuery("SELECT * FROM #__wh_msablon_mezo WHERE id = ({$mezo_id_arr[$i]})");
			$rows = $this->db->loadObjectList();
			$db++;
				foreach($rows as $row)	{
				$torol_link = "if(confirm('".JText::_("ARE YOU SURE")."')){ $('torol_mezo_id').value={$row->id}; $('task').value='torolMezo'; $('adminForm').submit(); }";
				
				if($db%2 == 0) $class = "class=\"td_sablon1\"";
				else $class = "class=\"td_sablon2\"";
				?>
                	</tr>
						<td <?php  echo $class; ?> ><?php echo $row -> nev; ?></td>
						<td <?php  echo $class; ?> ><?php echo $row -> tipus; ?></td>
						<td <?php  echo $class; ?> ><?php echo $row -> suffix; ?></td>
						<td <?php  echo $class; ?> ><?php echo $row -> kereso; ?></td>
						<td <?php  echo $class; ?> ><?php echo $row -> hirlevel; ?></td>
						<td <?php  echo $class; ?> ><?php echo $row -> aktiv; ?></td>
                    	<td <?php  echo $class; ?> >
                    	<input type="button" onclick="<?php  echo $torol_link; ?>" value="<?php echo JText::_("TOROL") ?>" >
                   		</td>
                        <td <?php  echo $class; ?> >
                        <?php echo $this->sorrendNyilak($id,$row->id, $curent_table, $mezo); ?> <input name="sorrend[]" value="<?php echo $row->id; ?>" type="hidden" />
                        
                        </td>
                 	</tr>
                 	<?php
				 	
                	}
				}
			}
				 ?>
		</table>
        </div>
        <input type="hidden" name="torol_mezo_id" id="torol_mezo_id" value="" />
        <input type="hidden" name="sorrendId" id="sorrendId" value="" />
        <input type="hidden" name="irany" id="irany" value="" />
        <input type="hidden" name="tablanev" id="tablanev" value="" />
        <input type="hidden" name="mezo_idk" id="mezo_idk" value="" />
        <input type="hidden" name="tabla_id" id="tabla_id" value="" />
        <input type="hidden" name="mezonev" id="mezonev" value="" />
        <?php
		
		$ret = ob_get_contents();	
		ob_end_clean();
		
		return $ret;
		}
	}//function

}