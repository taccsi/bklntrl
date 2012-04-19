<?php
defined( '_JEXEC' ) or die( '=;)' );
require_once( 'components/com_whp/helpers/whpBeallitasok.php' );
require_once( 'administrator/components/com_whp/helpers/baseModel.php' );
require_once( 'administrator/components/com_whp/helpers/xComponent.php' );
require_once( 'components/com_whp/helpers/xComponent.php' );
require_once( 'components/com_whp/unitpl/base_template.php' );
//require_once( 'components/com_whp/helpers/cron.php' );

class mod_whpkategoriafa /*extends whpPublic*/{
	var $kat_kep_szel = 200;
	var $kat_kep_mag = 200;
	var	$dir_forras = "media/whp/images/";
	var $dir_cel = "images/whp/resized/";
		function __construct(){
			$this->_db = JDatabase::getInstance( whpBeallitasok::getOption() );
			$this->cObj = new modelBase;
			$this->jogok = implode(',', $this->cObj->getjog()->kategoriak );
			//print_r($this->cObj->getjog()->kategoriak);
			//die("----");
			//$this->kategoria_id = JRequest::getVar("kategoria_id", 0);
			$this->kategoria_id =  JRequest::getVar( "cond_kategoria_id", 1 );
			//$this->kategoria_id = 149;
			//echo $this->kampanyok();
			
			$this->tree( 118 );
		}

		function kampanyok(){
			global $Itemid;
			$ret = "";
			//echo $GLOBALS["whp_id"];
			$q = "select concat( 'kampany_id[]=', id, '&' ) from #__wh_kampany as kampany
			where aktiv = 'igen' 
			and webshop_id = {$GLOBALS['whp_id']} 
			and kategoriak_menube = 'igen'
			and now() >= kampany.datum
			and date_format( now(), '%Y-%m-%d' ) <= date_format( date_add(kampany.datum, interval kampany.hossz day), '%Y-%m-%d' )
			and kozos_akcio = 'igen' 
			order by kampany_prioritas desc"; 
			$this->_db->setQuery($q);
			$kozosAkcioArr = $this->_db->loadResultArray();
			if( count( $kozosAkcioArr ) ){
				$kozos_akcio = implode( $kozosAkcioArr );
				$link = "index.php?option=com_whp&{$kozos_akcio}controller=termekek&kozosakcio=igen";
				if (jrequest::getvar('kozosakcio')){$class='li_active';} else {$class='';}
				$ret .= "<li class=\"kampany {$class}\"><a class=\"\" href=\"{$link}\"><span>".jtext::_("KOZOS_AKCIO")."</span></a></li>";
			}
			//( $k->id == jrequest::getVar("kampany_id") ) ? $class = "li_active" : $class = "balmenu_fokategoria";
			//if()
			$q = "select * from #__wh_kampany as kampany
			where aktiv = 'igen' 
			and webshop_id = {$GLOBALS['whp_id']} 
			and kategoriak_menube = 'igen'
			and now() >= kampany.datum
			and date_format( now(), '%Y-%m-%d' ) <= date_format( date_add(kampany.datum, interval kampany.hossz day), '%Y-%m-%d' )
			and kozos_akcio <> 'igen' 
			order by kampany_prioritas desc"; 
			$this->_db->setQuery($q);
			
			$kampanyok = $this->_db->loadObjectList();
			if( count($kampanyok) ) echo "<ul>";
			foreach( $kampanyok as $k){
				($k->id == jrequest::getVar("kampany_id") ) ? $class = "li_active" : $class = "balmenu_fokategoria";				
				$link = "index.php?option=com_whp&kampany_id={$k->id}&controller=termekek&Itemid={$Itemid}";
				$ret .= "<li class=\"kampany {$class}\"><a href=\"{$link}\"><span>{$k->nev}</span></a></li>";
			}
			if( count($kampanyok) ) echo "</ul>";
			return $ret;
		}

		function tree($szulo, $display = 'block'){		

			global $Itemid;

			$q = "select * from #__wh_kategoria as c 

			where c.szulo = {$szulo} and c.id in ({$this->jogok}) and c.aktiv = 'igen' order by c.sorrend";

 			$this->_db->setQuery($q);

			$rows = $this->_db->loadObjectList();

			

			//echo $this->_db->getErrorMsg()."****";

			//echo $this->_db->getQuery();

			//print_r($rows);

			if(count($rows)){

				?>

				<ul style="display:<?php echo $display ?>">

				<?php

				foreach($rows as $row){

					$class = ( $this->kategoria_id == $row->id ) ? $aktiv = "li_active " : "";

					

					$q = "select count(id) from #__wh_kategoria where szulo = '{$row->id}'";

					$this->_db->setquery($q);

					$gyerekek = $this->_db->loadresult();

					if ($gyerekek !=0){ $class .= 'fokat';} else {$class .= 'alkat';}

					

					?>

                    

					<li class="<?php echo $class; ?>">

					<?php

					$this->getItem($row);

					//echo "{$row->id}<br />";

					//print_r($this->get_kat($this->kategoria_id));



					if(

							$this->ellenoriz_gyerek(

								$this->get_kat($this->kategoria_id),//gyerek

								$this->get_kat($row->id)//szulo

							 ) 



						)

					{

						if ($this->kategoria_id != 0  ) { $this->tree($row->id,'block'); }

						//$this->getTermekek($row->id);

					}/* else {

						if ($this->kategoria_id != 0  ) { $this->tree($row->id, 'none'); }

					}*/



					?>

					</li>

					<?php

				}

				?>

				</ul>

				<?php

			}

	}



	function ellenoriz_gyerek($gyerek, $szulo){

		//print_r($szulo);

		if (@$gyerek->id) {

		@$q="select * from #__wh_kategoria where lft >= {$szulo->lft} and rgt <= {$szulo->rgt} and id = {$gyerek->id}";} else {

		@$q="select * from #__wh_kategoria where lft >= {$szulo->lft} and rgt <= {$szulo->rgt}";}

		$this->_db->setQuery($q);

		$obj = $this->_db->loadObject();

		//echo $this->_db->getQuery();

		return $obj;

	}



	function getTermekek($catid){

		global $Itemid;

		( $Itemid ) ? $Itemid : $Itemid = 0;

		$q="select * from #__wh_termek where kategoria_id = {$catid} and aktiv='igen'";

		$this->_db -> setQuery($q);

		$rows = $this->_db ->loadObjectList();

		$id = JRequest::getvar("id",0);

		if (Jrequest::getvar("option") == "com_whp" && Jrequest::getvar("controller") == "termek") $bontas = true;

		else $bontas = false;

		?>

		<ul>

        <?php

		foreach($rows as $row) {

			if (($id == $row->id)) $class="class=\"active\"";

			else $class=""; //&Itemid={$Itemid}

        	echo "<li {$class}><a href=\"index.php?option=com_whp&controller=termek&cond_kategoria_id={$catid}&id={$row->id}\"><span>{$row->nev}</span></a></li>";

		

		}

		?>

		</ul>

        <?php

	}



	function get_kat($id){

		@$q="select * from #__wh_kategoria where id = {$id}";

		$this->_db -> setQuery($q);

		$obj = $this->_db -> loadObject();

		//echo $this->_db->getQuery();

		//print_r($kat);

		return $obj;

	}

	

	function getItem($row){
		global $Itemid;
		//return "";
		//( $this->getActive($row) ) ? $aktiv = "balmenu_link active_menu" : $aktiv = "balmenu_link";
		//print_r($row);
		($row->szulo) ? $class = "balmenu_link" : $class = "balmenu_fokategoria";
		( $this->kategoria_id == $row->id ) ? $aktiv = "{$class} active_menu" : $aktiv = "{$class}"; 
		$link=JRoute::_("index.php?option=com_whp&controller=termekek&cond_kategoria_id={$row->id}");
		$config =& JFactory::getConfig();
		$sitename = $config->getValue( 'config.sitename' );
		$q = "select count(id) from #__wh_kategoria where szulo = '{$row->id}'";
		$this->_db->setquery($q);
		$gyerekek = $this->_db->loadresult();
		if ($gyerekek != 0 and 0){
		
			?><a class="<?php echo $aktiv ?>" style="cursor:pointer;" title="<?php echo $row->nev?>" ><span><?php echo $row->nev ?></span></a>
	        <?php
		} else {
			?><a class="<?php echo $aktiv ?>" title="<?php echo $row->nev?>" href="<?php echo $link ?>" ><span><?php echo $row->nev ?></span></a>
        	<?php	
	
		}
	}

	

	/*function getItem($row){

		global $Itemid;

		//return "";

		//( $this->getActive($row) ) ? $aktiv = "balmenu_link active_menu" : $aktiv = "balmenu_link";

		//print_r($row);

		($row->szulo) ? $class = "balmenu_link" : $class = "balmenu_fokategoria";

		( $this->kategoria_id == $row->id ) ? $aktiv = "{$class} active_menu" : $aktiv = "{$class}"; 

		$link=JRoute::_("index.php?option=com_whp&controller=termekek&cond_kategoria_id={$row->id}");

		$config =& JFactory::getConfig();

		$sitename = $config->getValue( 'config.sitename' );

		if ($row->szulo != 0){

			

		?><a class="<?php echo $aktiv ?>" title="<?php echo $row->nev?>" href="<?php echo $link ?>" ><span><?php echo $row->nev ?></span></a>

        <?php

        	

		

		} else {



		@$forras_kep = "{$GLOBALS['whp_kozp_url']}/media/wh/kategoriak/{$row->id}_1.jpg";

		@$cel_kep = "{$this->dir_cel}kategoria_{$row->id}_{$this->kat_kep_szel}_{$this->kat_kep_mag}_1.jpg";

		if (@imagecreatefromjpeg($forras_kep)) {

			$link=JRoute::_("index.php?option=com_whp&controller=termekek&cond_kategoria_id={$row->id}");

			$config =& JFactory::getConfig();

			$sitename = $config->getValue( 'config.sitename' );

		

			echo base_template::image($forras_kep, $cel_kep, $this->kat_kep_szel, $this->kat_kep_mag, "resize", $link, "rel=\"\"" ,

		"", $row->nev);



		} else {

			?><a class="<?php echo $aktiv ?> fokategoria" title="<?php echo $row->nev?>" href="<?php echo $link ?>" ><span><?php echo $row->nev ?></span></a>

		<?php

		}

		

		

		?>

		<?php

		}

	}*/

	

	

	function getKategoriaKep($kategoria_id, $row){

		global $Itemid;

		//print_r($this->cell);/trifidadmin/media/wh/kategoriak//4_1.jpg

		//exit;

		

		//echo $forras_kep."<br />";

		//echo $cel_kep." ckép<br />";

		//print_r($this->cell->kepek[1]->file_nev);exit;

		//echo ($link); die();

		

	

	}

	

	

	function getActive($obj){

		$akt_cat= $this->get_kat( $this->kategoria_id );

		$q="select * from #__wh_kategoria where lft <= {$akt_cat->lft} and rgt >={$akt_cat->rgt} and id = {$obj->id}";

		$this->_db->setQuery($q);

		$obj = $this->_db->loadObject();

		return $obj->id;

	}



	function getActCat(){

		@$q="select * from #__wh_kategoria where id = {$this->kategoria_id}";

		$this->_db->setQuery($q);

		$obj=$this->_db->loadObject();

		//echo $this->_db->getQuery();

		//echo $this->_db->getErrorMsg();

		//print_r($obj);

		return $obj;

	}



	function rebuild_tree($szulo, $left) {

		$right = $left+1;

		$q="SELECT id FROM #__wh_kategoria WHERE szulo ='{$szulo}'";

		$this->_db->setQuery($q);

		$rows = $this->_db->loadObjectList();

		foreach($rows as $row){

			//print_r($row);

			$right = $this->rebuild_tree($row->id, $right);	   

		}

		$o="";

		$o->id=$szulo;

		$o->lft = $left;

		$o->rgt = $right;

		$this->_db->updateObject("#__wh_kategoria", $o, "id");

		//echo $this->_db->getErrorMsg();

		//$this->_db->query();	   			

		return $right+1;

	}

}

new mod_whpkategoriafa;

?>