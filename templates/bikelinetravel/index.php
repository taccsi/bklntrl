<?php defined('_JEXEC') or die('Direct Access to this location is not allowed.');?>

<?php
$doc = Jfactory::getdocument();
$doc -> addScript("templates/bikelinetravel/js/superfish.js"); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//HU" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
	<head>
		<jdoc:include type="head" />
		<link href="<?php echo $this->baseurl ?>/templates/bikelinetravel/css/style.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" language="JavaScript">
						$j(document).ready(function() {
							$j(".moduletable_mainmenu ul a").hover(function() {
								
								$j(this).parent().find("ul.subnav").slideDown(100).show();
								$j(this).parent().hover(function() {
								}, function(){
									$j(this).parent().find("ul.subnav").slideUp(100);
								});
								}).hover(function() {
									$j(this).addClass("subhover");
								}, function(){
									$j(this).removeClass("subhover");
							});
							
							$j(".table_csomag tr:odd").addClass("odd");
							$j(".table_csomag tr td:first-child").addClass("key");
							initFancybox();
						});
		</script>
	</head>
	<body>
		<div id="page">
			<div class="bg1" id="page-in">
				<div id="navi"><jdoc:include type="modules" name="mm" style="XHTML" /></div>
													
				<div id="header">
					<div id="lang">xxx
						<jdoc:include type="modules" name="lang" style="XHTML" />
					</div>
					<div id="search">
					</div>
					<a title="BikeLine" href="<?php echo $this->baseurl ?>"><img border="0" alt="Bikeline" src="templates/bikelinetravel/images/bikeline-logo.png"></a>
				</div>
			
				<div id="content">
					<div id="left">
						<div id="specials"><jdoc:include type="modules" name="specials" style="XHTML" /></div>
						<jdoc:include type="modules" name="left" style="XHTML" />
						
						
						<div class="box" id="booking">
							<h3><?php echo jtext::_('FOGLALAS') ?></h3>
							<form accept-charset="utf-8" action="/tours" method="post" id="TourIndexForm" controller="tours"><div style="display:none;"><input type="hidden" value="POST" name="_method"></div><div class="input text"><input type="text" id="TourStartDate" placeholder="Indulás dátuma" class="jqdate date" name="data[Tour][start_date]"></div><div class="input text"><input type="text" id="TourArrivalDate" placeholder="Érkezés dátuma" class="jqdate date" name="data[Tour][arrival_date]"></div><div class="submit"><input type="submit" value="Foglalás"></div></form>					
						</div> <!-- booking -->
						
						<div class="box" id="webshop">
							<h3><?php echo jtext::_('WEBARUHAZ') ?></h3>
							<a title="a" href="#">Nézze meg webáruházunkat &gt;&gt;</a>
							Kiegészítők, túrafelszerelések, kerékpárok, stb.
						</div> <!-- webshop -->
					
					</div> 
				
					<div id="main">
						<div id="main-in">
							<jdoc:include type="message" />
							<jdoc:include type="component" style="XHTML" />
						</div>
					</div>
						
					<div id="right">
						<div id="right-in">
							<jdoc:include type="modules" name="right" style="XHTML" />
						</div>
					</div>
					<div id="foobar"></div>	
				</div> 
				
			<div id="bottom">
				<jdoc:include type="modules" name="bottom" style="XHTML" />
				<div id="footer">
					<jdoc:include type="modules" name="footer" style="XHTML" />
				</div>
			</div><!-- footer, bootom-->
		</div></div>
		
	</body>
</html>