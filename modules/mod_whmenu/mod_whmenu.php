<?php
defined( '_JEXEC' ) or die( '=;)' );
class mod_whmenu{
	
	var $defaultMenuId = 11;
	
	function __construct($params){
		if (jrequest::getvar('layout') != 'kapcsolodo'){
			echo $this->HTML();
		} else {
			$doc = jfactory::getdocument();
			$doc->addscriptdeclaration('$j(function() {balModulEltuntet();});');
		}
		
	}
	
	function HTML(){
		ob_start();
		?>	
	<ul class="menu">
<li class="item-113 deeper parent">
		<a href="index.php?option=com_wh&amp;controller=termekek&amp;Itemid=113">Termékek</a>
		<ul>
			<li class="item-114">
				<a href="index.php?option=com_wh&amp;controller=kategoriak&amp;Itemid=114">Kategóriák</a>
			</li>
			<li class="item-115">
				<a href="index.php?option=com_wh&amp;controller=termekek&amp;Itemid=115">Termékek</a>
			</li>
			<li class="item-116">
				<a href="">Átárazás</a>
			</li>
			<li class="item-124">
				<a href="index.php?option=com_wh&amp;controller=gyartok&amp;Itemid=124">Márkák</a>
			</li>
			<li class="item-125">
				<a href="index.php?option=com_wh&amp;controller=beszallitok&amp;Itemid=125">Beszállítók</a>
			</li>
			<li class="item-142">
				<a href="index.php?option=com_wh&amp;controller=msablonok&amp;Itemid=142">Műszaki sablonok</a>
			</li>
			<li class="item-143">
				<a href="index.php?option=com_wh&amp;controller=msablon_mezok&amp;Itemid=143">Műszaki sablon mezők</a>
			</li>
		</ul>
	</li>
	<li class="item-118 deeper parent">
		<a href="">Marketing</a>
		<ul>
			<li class="item-119">
				<a href="index.php?option=com_wh&amp;controller=kampanyok&amp;Itemid=119">Akciók</a>
			</li>
			<li class="item-120 deeper parent">
				<a href="index.php?option=com_wh&amp;controller=hirlevelek&amp;Itemid=120">Hírlevél</a>
				<ul>
					<li class="item-147">
						<a href="index.php?option=com_wh&amp;controller=hirlevelek&amp;Itemid=147">Hírlevelek</a>
					</li>
					<li class="item-145">
						<a href="index.php?option=com_wh&amp;controller=hirlevel_listak&amp;Itemid=145">Hírlevél listák</a>
					</li>
					<li class="item-146">
						<a href="index.php?option=com_wh&amp;controller=hirlevel_cimek&amp;Itemid=146">Címek</a>
					</li>
				</ul>
			</li>
			<li class="item-121">
				<a href="">Bannerek</a>
			</li>
			<li class="item-122">
				<a href="index.php?option=com_wh&amp;controller=kuponok&amp;Itemid=122">Kuponok</a>
			</li>
			<li class="item-129">
				<a href="index.php?option=com_wh&amp;controller=kimutatas&amp;Itemid=129">Kimutatás</a>
			</li>
		</ul>
	</li>
	<li class="item-123 deeper parent">
		<a href="">Vásárlók</a>
		<ul>
			<li class="item-126">
				<a href="index.php?option=com_wh&amp;controller=rendelesek&amp;Itemid=126">Rendelések</a>
			</li>
			<li class="item-127">
				<a href="index.php?option=com_wh&amp;controller=felhasznalok&amp;Itemid=127">Vásárlók</a>
			</li>
			<li class="item-144">
				<a href="index.php?option=com_wh&amp;controller=fcsoportok&amp;Itemid=144">Vásárlói csoportok</a>
			</li>
			<li class="item-130">
				<a href="index.php?option=com_wh&amp;controller=keresesek&amp;Itemid=130">Rögzített keresés</a>
			</li>
		</ul>
	</li>
	<li class="item-131 deeper parent">
		<a href="">Tartalom</a>
		<ul>
			<li class="item-132">
				<a href="index.php?option=com_wh&amp;controller=contents&amp;Itemid=132">Tartalom</a>
			</li>
			<li class="item-133">
				<a href="">Menükezelés</a>
			</li>
			<li class="item-134">
				<a href="index.php?option=com_wh&amp;controller=galleries&amp;limitstart=0&amp;Itemid=134">Galéria</a>
			</li>
		</ul>
	</li>
	<li class="item-135 deeper parent">
		<a href="index.php?option=com_wh&amp;controller=beallitas&amp;Itemid=135">Beállítások</a>
		<ul>
			<li class="item-136">
				<a href="">Általános</a>
			</li>
			<li class="item-138">
				<a href="index.php?option=com_wh&amp;controller=atvhelyek&amp;Itemid=138">Átvevőhelyek</a>
			</li>
			<li class="item-140">
				<a href="index.php?option=com_wh&amp;controller=webshopok&amp;Itemid=140">Webshopok</a>
			</li>
		</ul>
	</li>
	<li class="item-236">
		<a href="index.php?option=com_wh&amp;controller=felhasznalo&amp;task=logout&amp;Itemid=236">Kilépés</a>
	</li></ul>
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
}
new mod_whmenu($params);