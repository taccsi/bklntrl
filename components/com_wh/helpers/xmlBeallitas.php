<?php
defined( '_JEXEC' ) or die( '=;)' );
class xmlbeallitas extends xmlParser{

	/*function delSzazalek_kat($node){
		print_r JRequest::getVar("torol_szazalek_id", "");
		die;
	}*/
	
	function getSzazalek_kat($node){
		$name = $node->getAttribute('name');
		$value=$this->getAktVal($name);
		$szazalek_ertek = $this->getSessionVar("szazalek_id");
		ob_start();
		$listaz_link = "$('task').value='keep'; tabEllenoriz(); $('adminForm').submit()";
		?>
		<input name="<?php echo $name ?>" type="hidden" value='<?php echo $value ?>'  />
        <div id="content-container">
		<div class="sel-bal">	
        	<div class="sel-cimek"><?php echo JText::_("KOLTSEG") ?></div>
        	<div class="">
				<input type="text" name="szazalek_id" value="<?php echo $szazalek_ertek; ?>" />
                <input type="button" onclick="<?php echo $listaz_link; ?>" value="<?php echo JText::_("LISTAZ"); ?>" />
			</div>
        </div>
		<div class="sel-jobb">
        	<div class="sel-cimek"><?php echo JText::_("VALASSZON KATEGORIAT A KOLTSEGHEZ") ?></div>
        	<div class="">
				<?php
					$kat_id_ = $value;
					$kategoriafa = new kategoriafa(array(), 3);
					if($szazalek_ertek){
						foreach (unserialize($value) as $szazalek_id => $kat_id){
							if($szazalek_id==$szazalek_ertek){
								if( !is_array( $kat_id ) && $kat_id ){
									$kat_id_ = explode(",", $kat_id );
		 						}else{
		 							
								}
							}
						}
      					echo JHTML::_('Select.genericlist', $kategoriafa->catTree, "kat_id3[]", array("multiple"=>"multiple", "class"=>"alapinput"), "value", "option", $kat_id_);
					}			
				?>
        	</div>
        </div> 
        <div class="div_wh_clear"></div>
        </div>
        
        <div id="content-container">	
        <div class="sel-cimek"><?php echo JText::_("A MEGADOTT KOLTSEG(EK)HEZ TARTOZO KATEGORIAK") ?></div>
        <div class="sel-content">
		<?php
		$count_users = 0;
		$arr_ = unserialize($value);
		foreach ( (array)$arr_ as $szazalek_id => $kat_id){
			/*$q = "select {$name_} from {$table_} where id = {$user_id}";
      		$this->db->setQuery($q);
      		$row = $this->db->loadObject();*/
			//echo $szazalek_id;
            $torol_link = "if(confirm('".JText::_("ARE YOU SURE")."')){ $('torolHaszonkulcs').value={$szazalek_id}; tabEllenoriz(); $('task').value='torolSzazalekKat'; $('adminForm').submit() }";
         	echo JText::_("KOLTSEG"),': ',$szazalek_id; ?>
            <input type="button" onclick="<?php echo $torol_link; ?>" value="<?php echo JText::_("TORLES"); ?>" />
            
            <?php
			$q = "select * from #__wh_kategoria where id in ({$kat_id})";
      		$this->db->setQuery($q);
      		$kat = $this->db->loadObjectList();
			$marg = "";
			foreach((array)$kat as $k)	{
			?>	
				<p class="kateg-felsorolas">
				- <?php 
				echo $k -> nev; 
				?>
				</p>
            <?php
			}
			//echo JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name, array("class"=>"alapinput"), "value", "option", $value);
            ?>
            <hr/>
            <?php
		}
		?>
        <input name="torolHaszonkulcs" id="torolHaszonkulcs" type="hidden" value=""  />
        </div>
		</div>
        </div>
        <?php
		$ret = ob_get_contents();	
		ob_end_clean();
		return $ret;	
	}

	function getFelh_kat($node){
		$table_ = "#__users";
		$name = $node->getAttribute('name');
		$value=$this->getAktVal($name);
		$user_id = $this->getSessionVar("user_id");
		ob_start();
		$listaz_link = "$('task').value='keep'; tabEllenoriz(); $('adminForm').submit()";
		?>
		<input name="<?php echo $name ?>" type="hidden" value='<?php echo $value ?>'  />
		<?php
		$q = "select id, `name` from #__users";
      	$this->db->setQuery($q);
      	$rows = $this->db->loadObjectList();
		$o="";
		$o->name = $o->id = "";
		
		array_unshift($rows, $o );
		?>
        <div id="content-container">
		<div class="sel-bal">	
        	<div class="sel-cimek"><?php echo JText::_("VALASSZA KI A KIVANT FELHASZNALOT"); ?></div>
        	<div class="">
				<?php
				echo JHTML::_('Select.genericlist', $rows, "user_id", array( "onchange" => "{$listaz_link}", "class"=>"multiple_search"), "id", "name", $user_id);
				?>
			</div>
			<?php
	  		$kategoriafa = new kategoriafa(array(), 1);
			?>
        </div>
		<div class="sel-jobb">	
        	<div class="sel-cimek"><?php echo JText::_("VALASSZON KATEGORIAT A FELHASZNALOHOZ"); ?></div>
        	<div class="">
				<?php
				$kat_id_ = $value;
				if($user_id){
					foreach (unserialize($value) as $kulcs => $kat_id){
						if($kulcs==$user_id){
							if( !is_array( $kat_id ) && $kat_id ){
								$kat_id_ = explode(",", $kat_id );
		 					}else{
		 						
							}
						}
					}
      			
				}
				echo JHTML::_('Select.genericlist', $kategoriafa->catTree, "kat_id1[]", array("multiple"=>"multiple", "class"=>"alapinput"), "value", "option", $kat_id_);
				?>
        	</div>
        </div> 
        <div class="div_wh_clear"></div>
        </div>
        
        <div id="content-container">	
        <div class="sel-cimek"><?php echo JText::_("A MEGADOTT FELHASZNALO(K)HOZ TARTOZO KATEGORIAK") ?></div>
        <div class="sel-content">
		<?php
		$count_users = 0;
		$arr_ = unserialize($value);
		foreach ((array)$arr_ as $user_id_ => $kat_id){
			$q = "select name from {$table_} where id = {$user_id_}";
      		$this->db->setQuery($q);
      		$row = $this->db->loadObject();
			
			$torol_link = "if(confirm('".JText::_("ARE YOU SURE")."')){ $('torol_Felh').value={$user_id_}; tabEllenoriz(); $('task').value='torolFelh'; $('adminForm').submit() }";
       		?>
            Név: <?php echo @$row -> name; ?>
            <input type="button" onclick="<?php echo $torol_link; ?>" value="<?php echo JText::_("TORLES"); ?>" />
            <?php
			$q = "select * from #__wh_kategoria where id in ({$kat_id})";
      		$this->db->setQuery($q);
      		$kat = $this->db->loadObjectList();
			$marg = "";
			foreach((array)$kat as $k)	{
			?>	
				<p class="kateg-felsorolas">
				- <?php 
				echo $k -> nev; 
				?>
				</p>
            <?php
			}
			//echo JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name, array("class"=>"alapinput"), "value", "option", $value);
            ?>
            <hr/>
            <?php
		}
		?>
        <input name="torol_Felh" id="torol_Felh" type="hidden" value=""  />
        </div>
		</div>
        </div>
        <?php
		$ret = ob_get_contents();	
		ob_end_clean();
		return $ret;
	}

	function getWebshop_kat($node){
		$table_ = "#__wh_webshop";
		$name = $node->getAttribute('name');
		$value=$this->getAktVal($name);
		$webshop_id = $this->getSessionVar("webshop_id");
		ob_start();
		$listaz_link = "$('task').value='keep'; tabEllenoriz(); $('adminForm').submit()";
		?>
		<input name="<?php echo $name ?>" type="hidden" value='<?php echo $value ?>'  />
		<?php
		$q = "select id, nev as `name` from #__wh_webshop";
      	$this->db->setQuery($q);
      	$rows = $this->db->loadObjectList();
		$o="";
		$o->name = $o->id = "";
		array_unshift($rows, $o );
		//return $ret;
		?>
        <div id="content-container">
		<div class="sel-bal">	
        	<div class="sel-cimek"><?php echo JText::_("VALASSZA KI A KIVANT WEBSHOPOT"); ?></div>
        	<div class="">
				<?php
				echo JHTML::_('Select.genericlist', $rows, "webshop_id", array( "onchange" => "{$listaz_link}", "class"=>"multiple_search"), "id", "name", $webshop_id);
				?>
			</div>
			<?php
	  		$kategoriafa = new kategoriafa(array(), 1);
			?>
        </div>
		<div class="sel-jobb">	
        	<div class="sel-cimek"><?php echo JText::_("VALASSZON KATEGORIAT A WEBSOPHOZ"); ?></div>
        	<div class="">
				<?php
				$kat_id_ = $value;
				if($webshop_id && $value){
					foreach (unserialize($value) as $kulcs => $kat_id){
						if($kulcs==$webshop_id){
							if( !is_array( $kat_id ) && $kat_id ){
								$kat_id_ = explode(",", $kat_id );
		 					}else{
		 						
							}
						}
					}
      			
				}
				echo JHTML::_('Select.genericlist', $kategoriafa->catTree, "kat_id2[]", array("multiple"=>"multiple", "class"=>"alapinput"), "value", "option", $kat_id_);
				?>
        	</div>
        </div> 
        <div class="div_wh_clear"></div>
        </div>
        
        <div id="content-container">	
        <div class="sel-cimek"><?php echo JText::_("A MEGADOTT WEBSHOP(OK)HOZ TARTOZO KATEGORIAK") ?></div>
        <div class="sel-content">
		<?php
		$count_users = 0;
		foreach (unserialize($value) as $webshop_id_ => $kat_id){
			$q = "select nev from {$table_} where id = {$webshop_id_}";
      		$this->db->setQuery($q);
      		$row = $this->db->loadObject();
			
			$torol_link = "if(confirm('".JText::_("ARE YOU SURE")."')){ $('torol_Ws').value={$webshop_id_}; tabEllenoriz(); $('task').value='torolWs'; $('adminForm').submit() }";
       		?>
            Név: <?php echo @$row->nev; ?>
            <input type="button" onclick="<?php echo $torol_link; ?>" value="<?php echo JText::_("TORLES"); ?>" />
            <?php
			$q = "select * from #__wh_kategoria where id in ({$kat_id})";
      		$this->db->setQuery($q);
      		$kat = $this->db->loadObjectList();
			$marg = "";
			foreach($kat as $k)	{
			?>	
				<p class="kateg-felsorolas">
				- <?php 
				echo $k -> nev; 
				?>
				</p>
            <?php
			}
			//echo JHTML::_('Select.genericlist', $kategoriafa ->catTree, $name, array("class"=>"alapinput"), "value", "option", $value);
            ?>
            <hr/>
            <?php
		}
		?>
        <input name="torol_Ws" id="torol_Ws" type="hidden" value=""  />
        </div>
		</div>
        </div>
        <?php
		$ret = ob_get_contents();	
		ob_end_clean();
		return $ret;
	}
	
   /*function catDepth($id){
   	//print $id."<br/>";
      $q = "select szulo from #__wh_kategoria where id = {$id}";
      $this->db->setQuery($q);
      $res = $this->db->loadResult();
      //echo $res."<br />";
	  if($res){
         $this->depth++;
         $this->margo .="&nbsp;";
         $this->catDepth($res);
      }
   }*/
	
}