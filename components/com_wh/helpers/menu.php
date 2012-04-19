<?php
defined( '_JEXEC' ) or die( '=;)' );
//require_once("modules/mod_wh_menu/helper.php");
//new mod_wh_menu($params);

class whMenu{
	function __construct($mArr=array() ){
		$this->mArr = $mArr;
		$this->construct = JREquest::getVar("controller", "beszallitok");
		$this->setMenuItems();
		$this->HTML();
	}
	
	function HTML(){
		$tmpl = JRequest::getVar('tmpl');
		$tmpl = ($tmpl) ? '&tmpl='.$tmpl : '';
		?>
		<ul class="ul_wh_menu">
        <?php
		//print_r($this->menuArr[$this->construct]);
		//die();
		foreach($this->menuArr[$this->construct] as $m ){
			if(count($this->mArr)){
				$okMenu = !in_array($m["cim"], $this->mArr);
			}else{
				$okMenu=1;
			}
			
			if( ( $this->getJogosult($m["jog"]) || 1 ) && $okMenu ){
				//$m['link'] .= $tmpl;
				?>
                <li class="li_wh_item <?php echo @$m['class']; ?>" style="background-image: <?php echo "url(components/com_wh/assets/images/{$m['src']})" ?>" >
                <a <?php echo $m["js"] ?> href="<?php echo "{$m['link']}" ?>"><?php echo $m["cim"] ?></a>
                </li>
                <?php
			}
		}
		?>
		</ul>
        <span class="clr"></span>
        <?php
	}
	
	function getJogosult($jog){
		$user=JFactory::getUser(); 
		//echo $user->usertype." **************";
		if(in_array($user->usertype,$jog)) return 1; else return 0;
	}

	function setMenuItems(){
		global $Itemid;
		$this->menuArr = array(
			"szamlap"=>array(
			),

"hirlevelek"=>array( 
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"\$j('#task').val('add'); \$j('#adminForm').submit()\"",
					"jog"=>array( ""),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"\$j('#task').val('remove'); \$j('#adminForm').submit();\"", 	
					"jog"=>array( ""),
					"src"=>"bg_torol_gomb.png",
				),							
			),
			
			"hirlevel"=>array(
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"\$j('#task').val('apply'); \$j('#adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=hirlevelek&Itemid={$Itemid}"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),

"hirlevel_cimek"=>array( 
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"\$j('#task').val('add'); \$j('#adminForm').submit()\"",
					"jog"=>array( ""),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"\$j('#task').val('remove'); \$j('#adminForm').submit();\"", 	
					"jog"=>array( ""),
					"src"=>"bg_torol_gomb.png",
				),							
			),
			
			"hirlevel_cim"=>array(
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"\$j('#task').val('apply'); \$j('#adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=hirlevel_cimek&Itemid={$Itemid}"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),			

			"hirlevel_listak"=>array( 
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"\$j('#task').val('add'); \$j('#adminForm').submit()\"",
					"jog"=>array( ""),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"\$j('#task').val('remove'); \$j('#adminForm').submit();\"", 	
					"jog"=>array( ""),
					"src"=>"bg_torol_gomb.png",
				),							
			),
			
			"hirlevel_lista"=>array(
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"\$j('#task').val('apply'); \$j('#adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=hirlevel_listak&Itemid={$Itemid}"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),
			
			"galleries"=>array( 
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"\$j('#task').val('add'); \$j('#adminForm').submit()\"",
					"jog"=>array( ""),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"\$j('#task').val('remove'); \$j('#adminForm').submit();\"", 	
					"jog"=>array( ""),
					"src"=>"bg_torol_gomb.png",
				),							
			),
			
			"gallery"=>array(
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"\$j('#task').val('apply'); \$j('#adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=galleries&Itemid={$Itemid}"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),
			
			"content"=>array(
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"\$j('#task').val('apply'); \$j('#adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=contents&Itemid={$Itemid}"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),
			
			"contents"=>array(
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"\$j('#task').val('add'); \$j('#adminForm').submit()\"",
					"jog"=>array( ""),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>Jroute::_("javascript:;"),
					"js"=>"onclick=\"\$j('#task').val('remove'); \$j('#adminForm').submit();\"",	
					"jog"=>array( ""),
					"src"=>"bg_torol_gomb.png",
				),							
			),
			
			"kuponok"=>array(
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"$('task').value='remove'; $('adminForm').submit();\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),							
			),
			"keresesek"=>array(
				
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"$('task').value='remove'; $('adminForm').submit();\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),							
			),
			"kupon"=>array(		
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=kuponok&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),

			"uzletek"=>array(
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),				
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"$('task').value='remove'; $('adminForm').submit();\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),							
			),

			"uzlet"=>array(		
				array(
					"cim"=>Jtext::_("ALKALMAZ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=uzletek&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),

			"kommentek"=>array(
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"$('task').value='remove'; $('adminForm').submit();\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),							
			),

			"komment"=>array(		
				array(
					"cim"=>Jtext::_("ALKALMAZ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=kommentek&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),

			"jogtulok"=>array(
				array(
					"cim"=>Jtext::_("TOMEGES_FUNKCIOK"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('controller').value='jogtul';document.getElementById('task').value='tomegesFunkciok';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
					"class"=>"wide"
				),
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"$('task').value='remove'; $('adminForm').submit();\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),							
			),

			"jogtul"=>array(		
				array(
					"cim"=>Jtext::_("ALKALMAZ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=jogtulok&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),							
			),
			"fcsoportok"=>array(
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){\$j('#task').val('remove');\$j('#adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
			),

			"fcsoport"=>array(		
				array(
					"cim"=>Jtext::_("ALKALMAZ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=szerzok&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),

		
			"kimutatas"=>array(
				array(
					"cim"=>Jtext::_("TOP10_TERMEKEK"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='TOP10_TERMEKEK';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_uj_gomb_wide.png",
					"class"=>"wide",
				),
				/*
				array(
					"cim"=>Jtext::_("TOP10_TERMEKCSOPORTOK"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"$('task').value='TOP10_TERMEKCSOPORTOK';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_uj_gomb_wide.png",
					"class"=>"wide",
				),
				array(
					"cim"=>Jtext::_("TOP10_BESZALLITTO"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"$('task').value='TOP10_BESZALLITTO'; $('adminForm').submit();\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_uj_gomb_wide.png",
					"class"=>"wide",
				),							
				*/
				array(
					"cim"=>Jtext::_("MEGRENDELESEK_STAT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"$('task').value='MEGRENDELESEK_STAT'; $('adminForm').submit();\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_uj_gomb_wide.png",
					"class"=>"wide",
				),
				/*							
				array(
					"cim"=>Jtext::_("HASZON_STAT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"$('task').value='HASZON_STAT'; $('adminForm').submit();\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_uj_gomb_wide.png",
					"class"=>"wide",
				),
				*/											
			),
		
			"szerzok"=>array(
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){\$j('#task').val('remove');\$j('#adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
			),

			"szerzo"=>array(		
				array(
					"cim"=>Jtext::_("ALKALMAZ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=szerzok&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),

			"atvhelyek"=>array(
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){\$j('#task').val('remove');\$j('#adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
			),

			"atvhely"=>array(		
				array(
					"cim"=>Jtext::_("ALKALMAZ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=atvhelyek&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),

			
			"fcsoport"=>array(		
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=fcsoportok&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),
		
			"kampanyok"=>array(
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){\$j('#task').val('remove');\$j('#adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
			),

			"kampany"=>array(		
				array(
					"cim"=>Jtext::_("ALKALMAZ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=kampanyok&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),
						
			"tetelek"=>array(
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){\$j('#task').val('remove');\$j('#adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
			),

			"tetel"=>array(		
				array(
					"cim"=>Jtext::_("ALKALMAZ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=tetelek&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),
			"kep"=>array(
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("ALKALMAZ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("M"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='megsemKep';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
			),			 

			"felhasznalok"=>array(
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='mentFelhasznalok'; document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
			),
			"felhasznalo"=>array(
				array(
					"cim"=>Jtext::_("SAVE"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
					
				array(
					"cim"=>Jtext::_("APPLY"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=felhasznalo&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),
			"rendeles"=>array(
				/*array(
					"cim"=>Jtext::_("VISSZARU_OSSZEALLIT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"$('task').value='visszaruosszeallit'; $('adminForm').submit();\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
					"class"=>"wide",
				),*/
				array(
					"cim"=>Jtext::_("MASOL"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"$('task').value='tetelmasol'; $('adminForm').submit();\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"$('task').value='teteltorol'; $('adminForm').submit();\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),							
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),	
				array(
					"cim"=>Jtext::_("ALKALMAZ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),	
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=rendeles&Itemid={$Itemid}&task=cancel"),
					"js"=>"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
				
			),
			"rendelesek"=>array(		
			/*
				array(
					"cim"=>Jtext::_("OSSZEALLIT_PICKPACK_SZALLITMANY"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){ $('task').value='osszeallitPickPackSzallitmany'; $('adminForm').submit();}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
					"class"=>"wide",
				),			
				*/
				array(
					"cim"=>Jtext::_("MASOL"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"$('task').value='masol'; $('adminForm').submit();\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){\$j('#task').val('remove');\$j('#adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
			),
			"kep"=>array(
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("ALKALMAZ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("M"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='megsemKep';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
			),			 

			"kepek"=>array(
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='hozzaadKep';document.getElementById('controller').value='kep';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),		
				array(
					"cim"=>Jtext::_("BEZAR"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='bezar';document.getElementById('adminForm').submit();\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"if(torolKep()){document.getElementById('task').value='remove';document.getElementById('adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
			),

			"kategoria"=>array(	
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"\$j('#task').val('save');\$j('#adminForm').submit()\"",					
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("ALKALMAZ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"\$j('#task').val('apply');\$j('#adminForm').submit()\"",					
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=kategoria&Itemid={$Itemid}&task=cancel"),
					"js"=>"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
			),			 
			"kategoriak"=>array(
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){\$j('#task').val('remove');\$j('#adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
			),
			"move"=>array(
			
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){\$j('#task').val('remove');\$j('#adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
			),
			"beszallitok"=>array(
			
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("KIVALASZT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"if(kivalasztBeszallito()){document.getElementById('task').value='kivalaszt'; tabEllenoriz(); document.getElementById('adminForm').submit()}\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),

				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){\$j('#task').val('remove');\$j('#adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
				
			),	
			"beszallito"=>array(
			
				array(
					"cim"=>Jtext::_("SAVE"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
					
				array(
					"cim"=>Jtext::_("APPLY"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=beszallito&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),			
			),
			"gyartok"=>array(
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),
				/*array(
					"cim"=>Jtext::_("APPLY"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"uj.png",
				),*/
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){\$j('#task').val('remove');\$j('#adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
			),	
			"gyarto"=>array(
				/*array(
					"cim"=>Jtext::_("SAVE + NEW"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save_and_new';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"uj.png",
				), */

				array(
					"cim"=>Jtext::_("SAVE"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("APPLY"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=gyarto&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
			),
			"msablonok"=>array(
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){\$j('#task').val('remove');\$j('#adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
			),	
			"msablon"=>array(
				array(
					"cim"=>Jtext::_("SAVE"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("APPLY"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=msablon&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
			),
			"msablon_mezok"=>array(
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("KIVALASZT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='kivalaszt';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){\$j('#task').val('remove');\$j('#adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
			),	
			"msablon_mezo"=>array(
				array(
					"cim"=>Jtext::_("SAVE"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("APPLY"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=msablon_mezo&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
			),	
			"webshopok"=>array(
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){\$j('#task').val('remove');\$j('#adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
			),	
			"webshop"=>array(
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("APPLY"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='apply';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=webshop&Itemid={$Itemid}&task=cancel"),
					"js"=>"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
			),
			"beallitas"=>array(	
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='save';tabEllenoriz();document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
			),			
			"termekek"=>array(
				/*array(
					"cim"=>Jtext::_("XML_IMPORT"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=move&task=importXmlbol"),
					"js"=>"onclick=\"if (confirm('Biztos hogy elkezdjük az importálást?') == false){return false;}\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),*/
				array(
					"cim"=>Jtext::_("KLONOZ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='klonoz';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb_wide.png",
					"class"=>"wide",
				),
				array(
					"cim"=>Jtext::_("UJ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='add';document.getElementById('adminForm').submit()\"",
					"jog"=>array("Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='mentTermekek'; document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("KIVALASZT RENDELESHEZ"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"if(kivalasztTermek()){document.getElementById('task').value='kivalaszt_rendeleshez';document.getElementById('adminForm').submit()}\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
				array(
					"cim"=>Jtext::_("KIVALASZT"),
					"link"=>Jroute::_("javascript:void(0);"),
					//"js"=>"onclick=\"if(kivalasztTermek()){document.getElementById('task').value='kivalaszt'; tabEllenoriz(); document.getElementById('adminForm').submit()}\"",
					"js"=>"onclick=\"if(kivalasztTermek()){document.getElementById('task').value='kivalaszt';document.getElementById('adminForm').submit()}\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
				array(
					"cim"=>Jtext::_("TOROL"),
					"link"=>"javascript:;",
					"js"=>"onclick=\"if(confirm('".jtext::_("BIZTOS_VAGY_BENNE")."')){\$j('#task').val('remove');\$j('#adminForm').submit()}\"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),		
			),
			"termek"=>array(
				array(
					"cim"=>Jtext::_("MENT"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value = 'save'; document.getElementById('adminForm').submit();\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_mentes_gomb.png",
				),
				array(
					"cim"=>Jtext::_("APPLY"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value = 'apply'; document.getElementById('adminForm').submit();\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_uj_gomb.png",
				),	
				array(
					"cim"=>Jtext::_("M"),
					"link"=>Jroute::_("javascript:void(0);"),
					"js"=>"onclick=\"document.getElementById('task').value='megsemtermekVariacio';document.getElementById('adminForm').submit()\"",
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
				array(
					"cim"=>Jtext::_("CANCEL"),
					"link"=>Jroute::_("index.php?option=com_wh&controller=termek&Itemid={$Itemid}&task=cancel"),
					"js"=>"",	
					"jog"=>array( "Super Administrator"),
					"src"=>"bg_torol_gomb.png",
				),
			),			

		);
	}
	
	//function get
}