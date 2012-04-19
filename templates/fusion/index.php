<?php defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//HU" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>
<meta name="verify-v1" content="" /> 
<meta name="google-site-verification" content="" /> 
<jdoc:include type="head" />
<link href="<?php echo $this->baseurl ?>/templates/fusion/css/style.css" rel="stylesheet" type="text/css" />
<?php /*
<!--[if IE]>
<LINK href="<?php echo $this->baseurl ?>/templates/fusion/css/style_ie.css" rel="stylesheet" type="text/css">
<![endif]-->
<!--[if lte IE 6]>
<LINK href="<?php echo $this->baseurl ?>/templates/fusion/css/style_ie_6.css" rel="stylesheet" type="text/css">
<![endif]-->
*/ ?>

 

</head>

<body>
 	
	<div id="wrapper">
    	<div id="nav-main">
    		<a class="logo_fusion" href="index.php?option=com_wh"><img src="templates/fusion/images/logo_fusion.png"/> FUSION Webáruház</a>
    		<a class="logo_trifid" href="http://www.trifid.hu" target="_blank">&nbsp;</a>
    	</div>       			
        <div id="message"><jdoc:include type="message" /></div>
        <div id="body-outer">
        	<div id="left"><jdoc:include type="modules" name="whmenu" style="XHTML" /><jdoc:include type="modules" name="position-7" style="XHTML" /></div>
        	<div id="right">
		        <div class="body_wr1">
		        	<div class="body_wr2">
		                <div id="body">
		                    <div class="inner">
		                        <jdoc:include type="component" style="XHTML" />
		                    </div> 
		                </div>   
		            </div>
		        </div>
	        </div>  
	   </div>
     </div>                       
</body>
</html>