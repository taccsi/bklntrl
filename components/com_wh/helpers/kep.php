<?php
defined( '_JEXEC' ) or die( '=;)' );
class kep extends xmlParser{
	var $images = 1;
	var $kepPrefix = "termek_";
	var $dir_cel = "images/resized";
	var $w = 100;
	var $h = 60;	
	var $mode = "resize";	

	function init(){
		$p_ = "";
		$p_.= "'".jtext::_("SIKERES_FELTOLTES")."',";
		$p_.= "'".jtext::_("HIBA")."'";
		//$document = jfactory::getDocument();		
		//$document->addScriptDeclaration("window.addEvent(\"domready\", function(){initKepfeltoltes({$p_})} )");
	}

   function getNopic(){
      ( strstr( realpath("."), "administrator" ) ) ?  $x="../" : $x="";
      //die("{$x}{$this->nopic}");
	  return "{$x}{$this->nopic}";
   }

   function getKepNev($id){
      ( strstr( realpath("."), "administrator" ) ) ?  $x="../" : $x="";
	  $dir = "{$x}{$this->uploaded}/";
    // die($this->kepPrefix.'dfs');
	  if( !file_exists($dir) ){
         mkdir($dir);
      }
	  //echo "{$x}{$dir}{$this->kepPrefix}{$id}.jpg";
      //die;
	  return "{$dir}{$this->kepPrefix}{$id}.jpg";
   }

	function upload(){ 
		$tmp_name=$_FILES["kepFajl"]["tmp_name"];
		//echo $tmp_name.'xxx';
		$o = "";
		$o->termek_id = jrequest::getVar("termek_id", 0);
		$q = "select sorrend from #__wh_kep where termek_id = {$o->termek_id} order by sorrend desc limit 1";
		$this->_db->setQuery($q);
		$o->sorrend = (int)$this->_db->loadResult()+1;
		$this->_db->insertObject("#__wh_kep", $o, "id");
		$kep_id = $this->_db->insertId();
		$imgname = $this->getKepNev( $kep_id );
		if($tmp_name){
			move_uploaded_file( $tmp_name, $imgname );
			
			chmod($imgname, 0777);
			//ouput 'success' inside <div id="output">
			echo '<div id="output">success</div>';
			//then output your message (optional)
			echo '<div id="message">';
			
			echo '</div>';
		}else{
			//ouput 'failed' inside <div id="output">
			
			echo '<div id="output">failed</div>';
			
			echo '<div id="message">';
			print_r($_FILES);
			echo'</div>';
		}
		die('sdfsdfs');
	}


   function getKepek($node) {
    ob_start();
	//$this->init();
	$document = jfactory::getDocument();
	
	$document->addScriptDeclaration("\$j(document).ready(function(){\$j('#uploader').fileUploader() });");
	$document->addScriptDeclaration("\$j(document).ready(function(){ getKepLista(); });");
	//$document->addScriptDeclaration('initSortable()');
	$termek_id = $this->getAktval("id");
	$action = "index.php?option=com_wh&controller=termek&task=upload&termek_id={$termek_id}"; ?>
	<form action="<?php echo $action ?>" method="post" enctype="multipart/form-data">
		<input id="uploader" name="kepFajl" type="file" />
		<input type="submit" value="<?php echo jtext::_("FELTOLTES") ?>" id="pxUpload" />
		<input type="reset" value="<?php echo jtext::_("TOVABBI_KEPEK") ?>" id="pxClear" />
	</form>
    <div id="ajaxContentKepek"></div>
      <?php
      $ret = ob_get_contents();    
      ob_end_clean();
      return $ret;
      // die($readonly);
   }

   function getCelKepNev($id, $w="", $h="", $mode="" ){
		($w)? $w : $w = $this->w;
		($h)? $h : $h = $this->h;
		($mode)? $mode : $mode = $this->mode;		
		( strstr( realpath("."), "administrator" ) ) ?  $x="../" : $x="";
		$dir = "{$x}{$this->dir_cel}/";
		if( !file_exists($dir) ){
		   mkdir($dir);
		}
		return "{$dir}{$this->kepPrefix}{$id}_{$w}_{$h}_{$mode}.jpg";
   }
   
	function image($forras_kep, $cel_kep, $link="", $w="", $h="", $mode="", $class="", $buborek_kep="", $alt=""){
		($w)? $w : $w = $this->w;
		($h)? $h : $h = $this->h;
		($mode)? $mode : $mode = $this->mode;		
		//(file_exists("administrator") ) ? $pre = "" : $pre="../";
		//$forras_kep = "{$pre}{$this->dir_forras}{$forras_kep}";
		//$cel_kep = "{$pre}{$this->dir_cel}{$forras_kep}";		
		//$this->mkdir_resized();		
		ob_start();
		//echo $link; exit;
		if ( !file_exists($forras_kep)){
			//echo forras_kep;
			$forras_kep =$this->getNopic();
			$ujragyart = 0;
		}else{
			$ujragyart = 0;
		}
		//echo "---{$ujragyart}";
		switch($mode){
			case "crop" : 
				$this->cropimage($forras_kep, $cel_kep, $w, $h, $ujragyart); break;
			default:
				$this->resizeimage($forras_kep, $cel_kep, $w, $h, $ujragyart); break;			
		}
		if(file_exists($cel_kep)){
			if(!$buborek_kep){ ?>
                <a href="<?php echo $link ?>" <?php echo $class ?> >
                <img alt="<?php echo $alt ?>" title="<?php echo $alt ?>" class="kiskep" src="<?php echo $cel_kep ?>" /></a>
                <?php
			}else{
				?>
                <a href="<?php echo $link ?>" <?php echo $class ?> title="<?php echo $buborek_kep ?>" >
                <img class="kiskep" alt="<?php echo $alt ?>" src="<?php echo $cel_kep ?>" /></a>
                <?php
			}
		}else{

		}
		$tpl=ob_get_contents();
		ob_end_clean();
		return $tpl;
	}	

	function image_($forras_kep, $cel_kep, $link="", $w="", $h="", $mode="", $class="", $buborek_kep="", $alt=""){
		($w)? $w : $w = $this->w;
		($h)? $h : $h = $this->h;
		($mode)? $mode : $mode = $this->mode;		
		//(file_exists("administrator") ) ? $pre = "" : $pre="../";
		//$forras_kep = "{$pre}{$this->dir_forras}{$forras_kep}";
		//$cel_kep = "{$pre}{$this->dir_cel}{$forras_kep}";		
		//$this->mkdir_resized();		
		ob_start();
		//echo $link; exit;
		if ( !file_exists($forras_kep)){
			//echo forras_kep;
			$forras_kep =$this->getNopic();
			$ujragyart = 1;
		}else{
			$ujragyart = 0;
		}
		//echo "---{$ujragyart}";
		switch($mode){
			case "crop" : 
				$this->cropimage($forras_kep, $cel_kep, $w, $h, $ujragyart); break;
			default:
				$this->resizeimage($forras_kep, $cel_kep, $w, $h, $ujragyart); break;			
		}
		if(file_exists($cel_kep)){
			if(!$buborek_kep){ ?>
                <a href="<?php echo $link ?>" <?php echo $class ?> >
                <img alt="<?php echo $alt ?>" title="<?php echo $alt ?>" class="kiskep" src="<?php echo $cel_kep ?>" /></a>
                <?php
			}else{
				?>
                <span class="zoomTip" title='<div class=buborek><?php echo $buborek_kep ?></div>' >
                <a href="<?php echo $link ?>" <?php echo $class ?> >
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

	function resizeimage($forras_kep, $cel_kep, $szel, $mag, $ujragyart=0){ 
		if(!file_exists($cel_kep)  || ( filectime($forras_kep) > filectime($cel_kep) ) || $ujragyart ){
			//die("-{$forras_kep}----");
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

	function cropimage($forras_kep, $cel_kep, $szel, $mag, $ujragyart){ 
		if(!file_exists($cel_kep)  || ( filectime($forras_kep) > filectime($cel_kep)  ) || $ujragyart ){
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

	function mkdir_resized(){
		$dir_cel = substr($this->dir_cel,0,strlen($this->dir_cel)-1);
		if(!is_dir($dir_cel) ){
			mkdir($dir_cel);
		}
	}

}