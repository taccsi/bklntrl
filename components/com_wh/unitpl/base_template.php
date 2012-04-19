<?php defined('_JEXEC') or die('Restricted access'); 
jimport("unitemplate.simpleimage.simpleimage");

class base_template{

	var $th_szel=135;
	var $th_mag=92;

	var $th_k_szel=500;
	var $th_k_mag=50;

	var $th_bontas_szel=386;
	var $th_bontas_mag=288;

	var	$dir_forras = "media/wh/termekek/";
	var $dir_cel = "images/resized/"; 

	function __construct(){
		global $lang;
		//jimport("unitemplate.lang.".$_REQUEST['lang']);
		$this->mkdir_resized();
	}
	
	function getPrice($price){
		return number_format("{$price}", 2,","," ")." Ft";
	}
		
	function ikonok(){
		ob_start();
		$count = count($this->cell->pics);
		//echo $count;
		?> <div class="div_szinval_ikonok"> <?php
		foreach($this->cell->pics as $p){ 
			$ind = array_search($p, $this->cell->pics)+1;
			//echo $p->eszakai."<br />"; 
			if(!$p->eszakai && $p->ikonnev){
				$js = "onclick=\"setIkonkep('kepid_{$ind}', 'ikon_{$ind}' )\"";
				$ikonid = "id=\"ikon_{$ind}\"";
				$ikonHTML = "<div {$ikonid} class=\"div_ikon\"><img {$js} src=\"administrator/{$p->ikonnev}\"/></div>";
			}else{
				$ikonHTML="";
			}			
			echo $ikonHTML;
		}
		?> </div><div class="clr"></div> <?php
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;
	}

	function ar_brutto()
	{
		ob_start();
		echo @number_format($this->cell->ar_brutto, 0, "", " ")." Ft"; 
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;
	}
	
	function ar_brutto_nagyker()
	{
		ob_start();
		$user = JFactory::getUser();
		if($user->id){
			?>
			<span class="span_bontas_ar_text"><?php echo JText::_('NAGYKERAR'); ?></span>
			<?php
			echo @number_format($this->cell->ar_brutto_nagyker, 1, "", " ")." Ft"; 
			?>
			<?php
		}
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;
	}

	function getListLink(){
		global $Itemid;
		return JRoute::_("index.php?option=com_vs&controller=p_group&rajszam={$this->cell->rajszam}&Itemid={$Itemid}&view=p_group&p_din=&p_felueletkezeles=&p_atmer=&p_hossz=&p_gyarto=&p_horony=");
	//
	
	
	
	}

	function megnevezes(){
		ob_start();
		if(strlen($this->cell->megnevezes) > 40) {
			$class = "class=\"termeknev_long\"";
		} else {
			$class = "";
		}
		if($this->cell->megnevezes){
		?><a <?php echo $this->getTitle() ?> <?php echo $class ?> href="<?php echo  $this->getListLink() ?>"><?php echo $this->cell->megnevezes ?></a>
		<?php
		}else{
			?><?php
		}
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;		
	}
	
	function product_name()
	{
		global $Itemid;
		ob_start();
		//print_r($this->cell);
		//$forras = $this->cell->link;
		$link = JRoute::_("index.php?option=com_pvm&view=showproduct&product_id={$this->cell->product_id}&Itemid={$Itemid}&category_id={$this->cell->category_id}");
		
		
		echo ($this->cell->product_name);
		echo "</a>";
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;
	}	
		
	function image_kapcsolodo($forras_kep, $cel_kep, $w, $h, $mode, $link, $rel, $title){
		ob_start();
		switch($mode){
			case "crop" : 
				@$this->cropimage($forras_kep, $cel_kep, $w, $h); break;
			default:
				@$this->resizeimage($forras_kep, $cel_kep, $w, $h); break;			
		}
		if(file_exists($cel_kep)){
			?>
			<a href="<?php echo $link ?>" <?php echo $rel ?> >
			<img alt="<?php echo $title ?>" title="<?php echo $title ?>" class="kiskep" src="<?php echo $cel_kep ?>" /></a>
			<?php
		}/*else{
			?>
			<a href="<?php echo $link ?>" >
			<img alt="<?php echo $title ?>" title="<?php echo $title ?>" src="images/nincskep.jpg" /></a>
			<?php
		}*/
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;
	}
	
	function image_($forras_kep, $cel_kep, $w, $h, $mode, $link, $rel){
		ob_start();
		switch($mode){
			case "crop" : 
				@$this->cropimage($forras_kep, $cel_kep, $w, $h); break;
			default:
				@$this->resizeimage($forras_kep, $cel_kep, $w, $h); break;			
		}
		if(file_exists($cel_kep)){
			?>
            <span class="zoomTip" title='<div class=buborek><?php echo $buborek_kep ?>****-----------------------------------------------------------*</span>' >
			<a <?php echo $this->getTitle(); ?> href="<?php echo $link ?>" <?php echo $rel ?> >
			<img alt="<?php echo $this->cell->product_name ?>" title="<?php echo $this->cell->product_name ?>" class="kiskep" src="<?php echo $cel_kep ?>" /></a>
			<?php
		}/*else{
			?>
			<a href="<?php echo $link ?>" >
			<img alt="<?php echo $this->cell->product_name ?>" title="<?php echo $this->cell->product_name ?>" src="images/nincskep.jpg" /></a>
			<?php
		}*/
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;
	}
	
	function image($forras_kep, $cel_kep, $w, $h, $mode, $link, $class, $buborek_kep="", $alt){
		ob_start();
		//echo $link; exit;
		switch($mode){
			case "crop" : 
				@$this->cropimage($forras_kep, $cel_kep, $w, $h); break;
			default:
				@$this->resizeimage($forras_kep, $cel_kep, $w, $h); break;			
		}
		if(file_exists($cel_kep)){
			if(!$buborek_kep){ ?>
                <a href="<?php echo $link ?>" <?php echo $class ?> >
                <img alt="<?php echo $alt ?>" title="<?php echo $alt ?>" class="kiskep" src="<?php echo $cel_kep ?>" /></a>
                <?php
			}else{
				?>
                <span class="zoomTip" title='<div class=buborek><?php echo $buborek_kep ?></div>' >
                <a href="<?php echo $link ?>"  >
                <img class="kiskep" alt="<?php echo $alt ?>" src="<?php echo $cel_kep ?>" /></a>
                </span>
                <?php
			}
		}else{

		}
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;
	}	
	
	function getTitle(){
		return "title=\"{$this->cell->megnevezes}\"";
	}
	
	function kosar(){
		ob_start();
		global $Itemid;
		$kosarlink = urlencode(JRoute::_("index.php?option=com_pvm&view=kosar"));
		$link="index.php?option=com_pvm&view=kosar&task=add&product_id={$this->cell->id}&Itemid={$Itemid}&return={$kosarlink}";
		?>
        <a href="<?php echo $link ?>"><?php echo JText::_("KOSARBA"); ?></a>
        <?php
		$ret=ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function kapcsolodo_termekek(){
		ob_start();
		global $Itemid;
		?>
        <div class="div_related_products">
            <h4><?php echo JText::_("KAPCSOLODOTERM"); ?></h4>
              
                <div class="related_items">
                    <?php
                foreach($this->cell->relatedProducts as $r){
                    $link=JRoute::_("index.php?option=com_pvm&view=showproduct&category_id={$r->category_id}&product_id={$r->product_id}&Itemid={$Itemid}");
                    $pic = $this->kapcsPic($r);
                    ?>
                    <div class="div_item">
                    <a class="a_related_products" href="<?php echo $link ?>"><?php echo $pic ?></a>
                    <a class="a_related_products" href="<?php echo $link ?>"><?php echo $r->product_name ?></a> 
                    </div>
                    <?php
                }?> 
                <div class="clr"></div>
            </div>
        </div>
		<?php 
		$ret=ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function kapcsPic($r){
		global $Itemid;
		$forras_kep = $this->dir_forras.$r->product_full_image;
		$cel_kep = "{$this->dir_cel}{$r->product_sku}_{$this->th_k_szel}_{$this->th_k_mag}.jpg";
		$link=JRoute::_("index.php?option=com_pvm&view=showproduct&category_id={$r->category_id}&product_id={$r->product_id}&Itemid=5");
		//print_r($this->cell->kepek[1]->file_name);exit;
		return $this->image_kapcsolodo($forras_kep, $cel_kep, $this->th_k_szel, $this->th_k_mag, "resize", $link, "rel=\"\"" ,"", "");	
	}
	
	function velemenyForm(){
		ob_start();
		$user = JFactory::getUser();
		if($user->id ){
			echo ps_reviews::show_reviewform( $this->cell->product_id );
		}else{
			//echo JText::_("Vélemény írásához kérem jelentkezzen be.");
		}
		echo ps_reviews::product_reviews( $this->cell->product_id );
		//echo $this->ps_preview->show_voteform( $this->cell->product_id );
		$ret=ob_get_contents();
		ob_end_clean();
		return $ret;
	}	

	function getProductImage($name)
	{
		$name=stripcslashes($name);
		$name=str_replace("'","", $name);
		
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT f.name FROM #__rsgallery2_files as f INNER JOIN #__rsgallery2_galleries as g ON f.gallery_id = g.id
						WHERE LOWER(g.name) = 'webshop' AND replace(f.title, '\'', '') = '{$name}' LIMIT 1");
		$img = $db->loadResult();
		echo $db->getErrorMsg();
		if (strlen($img) > 0)
			return "images/rsgallery/original/" . $img;
		else return "nincskep";
	}



	function resizeimage($forras_kep, $cel_kep, $szel, $mag){ 
		if(!file_exists($cel_kep)  || ( filectime($forras_kep) > filectime($cel_kep) ) ){
			$image = new SimpleImage;
			$image->load($forras_kep);
			
			if ($image->getWidth() > $szel)
				$image->resizeToWidth($szel);
			if ($image->getHeight() > $mag)
				$image->resizeToHeight($mag);
			
			$image->save($cel_kep);
		}
	}
	
	function resizetoHeight($forras_kep, $cel_kep, $mag){ 
		if(!file_exists($cel_kep) /* || ( filectime($forras_kep) > filectime($cel_kep)  ) */  ){
			$image = new SimpleImage;
			$image->load($forras_kep);
			if ($image->getHeight() > $mag){
				$image->resizeToHeight($mag);
			}
			$image->save($cel_kep);
		}
	}

	function cropimage($forras_kep, $cel_kep, $szel, $mag){ 
		if(!file_exists($cel_kep)  || ( filectime($forras_kep) > filectime($cel_kep)  )  ){
			$r_akt = $szel/$mag;
			$image = new SimpleImage;
			$image->load($forras_kep);
			$r_orig = $image->getWidth() / $image->getHeight();
			if ($r_orig < $r_akt){
				$image->resizeToWidth($szel);
				if ($image->getHeight() > $mag){
					$newHeightOffset = $image->getHeight() / 2 - $mag / 2;
					$image->crop(0,$newHeightOffset,$szel,$mag);
				}
			} else {
				$image->resizeToHeight($mag);
				if ($image->getWidth() > $szel){
					$newWidthOffset = $image->getWidth() / 2 - $szel / 2;
					$image->crop($newWidthOffset,0,$szel,$mag);
				}
			}
			$image->save($cel_kep);
		}
	}

	function tab($id, $arr){
		?>
		<div class="tab_adatlap">
		<ul id="<?php echo $id ?>" class="shadetabs_2">
		<?php
		$i=0;
		foreach($arr as $r){
			?>
			<li id="<?php echo "tab{$i}" ?>"><a href="javasript:void(0)" rel="<?php echo "cont{$i}" ?>">
			<?php echo $r["title"] ?></a></li>
			<?php
			$i++;
		}
		?>
		</ul>
		<div style="clear:both"></div>
		<div >
		<?php
		$i=0;
		foreach($arr as $r){
			?>
			<div id="<?php echo "cont{$i}" ?>" class="tabcontent">
			<?php echo $r["content"] ?>
			</div>
			<?php
			$i++;
		}
		?>
		</div>
		<script type="text/javascript">
		var <?php echo "microtab_{$id}" ?>=new ddtabcontent("<?php echo $id ?>")
		<?php echo "microtab_{$id}" ?>.setpersist(true)
		<?php echo "microtab_{$id}" ?>.setselectedClassTarget("link") //"link" or "linkparent"
		<?php echo "microtab_{$id}" ?>.init()
		</script></div>
		<?php
	}

	function datum($row){
		ob_start();
		$st = strtotime($row->datum);
		$honap =$this->get_honap(date("n",$st));
		$nap =$this->get_nap(date("N",$st));
		$nap2 = date("j",$st);
		$oraperc = date("H:i",$st);
		?>
		<table border="0" cellspacing="0" cellpadding="0" class="table_datum">
		  <tr>
			<td class="td_honap"><?php echo $honap ?></td>
			<td class="td_nap"><?php echo $nap ?></td>
		  </tr>
		  <tr>
		    <td class="td_nap2"><?php echo $nap2 ?></td>		 
			<td class="td_oraperc"><?php echo $oraperc ?></td>
		  </tr>
		</table>
		<?php
		$ret=ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function mkdir_resized(){
		$dir_cel = substr($this->dir_cel,0,strlen($this->dir_cel)-1);
		if(!is_dir($dir_cel) ){
			mkdir($dir_cel);
		}
	}

	function product_price(){
		ob_start();
		echo $this->ps_product->show_price( $this->cell->product_id, true );
		$ret=ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	function get_cim($str, $h){
		($h < strlen($str) ) ? $ret = substr($str,0,$h)."..." : $ret = $str;
		return $ret;
	}
}

?>