function balModulEltuntet(){
	$j('#left').css('display','none');
	$j('#right').css('margin-left','0px');
}
function showHideArazolista(wrapId){
	if ($j('#' + wrapId).css('display') == 'none') {
		$j('#' + wrapId).css('display','block');
	} else {
		$j('#' + wrapId).css('display','none');
	}
}

function beszAr(ajaxContainerId, beszallito_id, termek_id){
	var fx=new Fx.Style($(ajaxContainerId), "color", {duration:400});
		$(ajaxContainerId).empty().addClass("ajax-loading").setHTML("<img src='components/com_wh/assets/images/ajax-loader.gif' border='0'> " );
        var url="index.php?option=com_wh&controller=ajax&task=beszAr&format=raw&beszallito_id="+beszallito_id+"&termek_id="+termek_id;
        var a=new Ajax(url,{
            method:"post",
            onComplete: function(response) {
                var resp=Json.evaluate(response);
                $(ajaxContainerId).removeClass("ajax-loading").setHTML(resp.html);
                fx.set("#fff").start("#000").chain(function() {
                    this.start.delay(0, this, "#000");
                });
            }
        }).request();
}

function setHaszonAjax( netto_ar, afa, arId, termek_id ){
		var ajaxId = "AjaxContentHaszon_"+termek_id;
		var fx=new Fx.Style($(ajaxId), "color", { duration:400} );
		$(ajaxId).empty().addClass("ajax-loading").setHTML("<img src='components/com_wh/assets/images/ajax-loader.gif' border='0'> " );
        var url="index.php?option=com_wh&controller=termekek&task=setHaszonAjax&format=raw&netto_ar="+netto_ar+"&afa="+afa+"&arId="+arId+"&termek_id="+termek_id;
		//alert(url);
        var a=new Ajax(url,{
            method:"post",
            onComplete: function(response) {
                var resp=Json.evaluate(response);
                $(ajaxId).removeClass("ajax-loading").setHTML(resp.html);
                fx.set("#fff").start("#000").chain(function() {
                    this.start.delay(0, this, "#000");
                });
            }
        }).request();
    }


function initKategoriaFa(){
	//alert("initKategoriaFa");
	//$j("#kategoriafa").jstree({ "plugins" : [ "themes", "html_data" ] } );
	//$j("#kategoriafa").jstree("set_theme","apple");
	/*
	$j("#kategoriafa").jstree({
	        "themes" : {
	            "theme" : "default",
	            "dots" : true,
	            "icons" : true
	        },
	        "plugins" : [ "themes", "html_data" ]
    });	
	*/
}

function getMsablonMezok(){
	//alert('------');
}

function setClientnr(oName){
	
	//alert("oName");dhl_feltoltes_datum
	var group = "";
	switch( $(oName).value ){
		case "házhoszállítás - GLS" : group = "clientnr"; break;
		case "házhoszállítás - DHL" : group = "dhlpar"; break;
		case "SZEMELYES_ATVETEL_PICKPACK" : group = "pickpack"; break;		
	}
	if( group ){
		//alert();
		var rendeles_id = $('id').value;
		//alert(rendeles_id);
		var fx=new Fx.Style($("idClientnr"), "color", {duration:400});
			$("idClientnr").empty().addClass("ajax-loading").setHTML("<img src='components/com_wh/images/ajax-loader.gif' border='0'> " );
			var url="index.php?option=com_wh&controller=rendeles&task=getClientnr&format=raw&group="+group+"&rendeles_id="+rendeles_id;
			//alert(url);
			var a=new Ajax(url,{
				method:"post",
				onComplete: function(response) {
					var resp=Json.evaluate(response);
					$("idClientnr").removeClass("ajax-loading").setHTML(resp.html);
					fx.set("#fff").start("#000").chain(function() {
						this.start.delay(0, this, "#000");
					});
				}
			}).request();
	}else{
		$("idClientnr").setHTML("");
	}
}

function eszkPopup(mvallalo_id){

}

function jutalekNyomtatasTomeges(){
	try{
		//$('task').value='jutalekNyomtatas';
		var url = "index.php?option=com_wh&controller=jogtul&tmpl=component&layout=nyomtatastomeges";
		// task=jutalekNyomtatas&
		//try {url += "&cid[]="+$('id').value}catch(e){}; 		
		try {url += "&tomeges=1"}catch(e){}; 
		try {url += "&cond_ev="+$('cond_ev').value}catch(e){}; 
		try {url += "&cond_honap="+$('cond_honap').value}catch(e){};
		//alert(url);
		newwindow=window.open(url,'xresPopup','height=800,width=1100,left=0,top=0,resizable=yes,scrollbars=yes,toolbar=no,status=no');
		newwindow.focus();
	}catch(e){}
}

function jutalekNyomtatas(){
	try{
		//$('task').value='jutalekNyomtatas';
		var url = "index.php?option=com_wh&controller=jogtul&task=jutalekNyomtatas&tmpl=component&layout=nyomtatas";
		try {url += "&cid[]="+$('id').value}catch(e){}; 		
		try {url += "&jogtulajdonos_id="+$('id').value}catch(e){}; 
		try {url += "&cond_ev="+$('cond_ev').value}catch(e){}; 
		try {url += "&cond_honap="+$('cond_honap').value}catch(e){};				
		//alert(url);
		newwindow=window.open(url,'xresPopup','height=800,width=1100,left=0,top=0,resizable=yes,scrollbars=yes,toolbar=no,status=no');
		newwindow.focus();
	}catch(e){}
}

function kuldErtesitoEmail(){
	var ajaxContentId ="ajaxContentEmailelkuldve";
	var fx=new Fx.Style($(ajaxContentId), "color", { duration:400 } );
		$(ajaxContentId).empty().addClass("ajax-loading").setHTML('<img src="components/com_wh/assets/images/ajax-loader.gif">' );
        var url="index.php?option=com_wh&controller=jogtul&task=kuldErtesitoEmail&format=raw";
		try {url += "&jogtulajdonos_id="+$('id').value}catch(e){};
		try {url += "&cond_ev="+$('cond_ev').value}catch(e){};
		try {url += "&cond_honap="+$('cond_honap').value}catch(e){};				
		//alert(url);
		var a=new Ajax(url,{
            method:"post",
            onComplete: function(response) {
				//alert("d");
                var resp=Json.evaluate(response);
                $(ajaxContentId).removeClass("ajax-loading").setHTML(resp.html);
                fx.set("#fff").start("#000").chain(function() {
                    this.start.delay(0, this, "#000");
                });
				//$j("a.zoom").fancybox({"zoomSpeedIn":300,"zoomSpeedOut":300,"overlayShow":false});
            }
        }).request();
}

function getEmailElkuldve(){
	var ajaxContentId ="ajaxContentEmailelkuldve";
	var fx=new Fx.Style($(ajaxContentId), "color", { duration:400 } );
		$(ajaxContentId).empty().addClass("ajax-loading").setHTML('<img src="components/com_wh/assets/images/ajax-loader.gif">' );
        var url="index.php?option=com_wh&controller=jogtul&task=getEmailElkuldve&format=raw";
		try {url += "&jogtulajdonos_id="+$('id').value}catch(e){};
		try {url += "&cond_ev="+$('cond_ev').value}catch(e){};
		try {url += "&cond_honap="+$('cond_honap').value}catch(e){};				
		//alert(url);
		var a=new Ajax(url,{
            method:"post",
            onComplete: function(response) {
				//alert("d");
                var resp=Json.evaluate(response);
                $(ajaxContentId).removeClass("ajax-loading").setHTML(resp.html);
                fx.set("#fff").start("#000").chain(function() {
                    this.start.delay(0, this, "#000");
                });
				//$j("a.zoom").fancybox({"zoomSpeedIn":300,"zoomSpeedOut":300,"overlayShow":false});
            }
        }).request();
}

function getJogtulKimutatas(  ){
	var ajaxContentId ="ajaxContentJogtulKimutatas";
	var fx=new Fx.Style($(ajaxContentId), "color", { duration:400 } );
		$(ajaxContentId).empty().addClass("ajax-loading").setHTML('<img src="components/com_wh/assets/images/ajax-loader.gif">' );
        var url="index.php?option=com_wh&controller=jogtul&task=getJogtulKimutatas&format=raw";
		try {url += "&jogtulajdonos_id="+$('id').value}catch(e){};
		try {url += "&cond_ev="+$('cond_ev').value}catch(e){};
		try {url += "&cond_honap="+$('cond_honap').value}catch(e){};				
		//alert(url);
		var a=new Ajax(url,{
            method:"post",
            onComplete: function(response) {
				//alert("d");
                var resp=Json.evaluate(response);
                $(ajaxContentId).removeClass("ajax-loading").setHTML(resp.html);
                fx.set("#fff").start("#000").chain(function() {
                    this.start.delay(0, this, "#000");
                });
				//$j("a.zoom").fancybox({"zoomSpeedIn":300,"zoomSpeedOut":300,"overlayShow":false});
            }
        }).request();
}

function torolJogtulajdonos(kapcsolo_id){
	var ajaxContentId ="jogtulajdonosok";
	var fx=new Fx.Style($(ajaxContentId), "color", { duration:400 } );
		$(ajaxContentId).empty().addClass("ajax-loading").setHTML('<img src="components/com_wh/assets/images/ajax-loader.gif">' );
        var url="index.php?option=com_wh&controller=szerzo&task=torolJogtulajdonos&format=raw";
		try {url += "&szerzo_id="+$('id').value}catch(e){};
		try {url += "&jogtulajdonos_id="+$('jogtulajdonos_id').value}catch(e){};
		try {url += "&tulhanyad="+$('tulhanyad').value}catch(e){};
		try {url += "&kapcsolo_id="+kapcsolo_id}catch(e){};		
		//alert(url);
		var a=new Ajax(url,{
            method:"post",
            onComplete: function(response) {
				//alert("d");
                var resp=Json.evaluate(response);
                $(ajaxContentId).removeClass("ajax-loading").setHTML(resp.html);
                fx.set("#fff").start("#000").chain(function() {
                    this.start.delay(0, this, "#000");
                });
            }
        }).request();
}

function hozzaadJogtulajdonos(){
	var ajaxContentId ="jogtulajdonosok";
	var fx=new Fx.Style($(ajaxContentId), "color", { duration:400 } );
		$(ajaxContentId).empty().addClass("ajax-loading").setHTML('<img src="components/com_wh/assets/images/ajax-loader.gif">' );
        var url="index.php?option=com_wh&controller=szerzo&task=hozzaadJogtulajdonos&format=raw";
		try {url += "&szerzo_id="+$('id').value}catch(e){};
		try {url += "&jogtulajdonos_id="+$('jogtulajdonos_id').value}catch(e){};
		try {url += "&tulhanyad="+$('tulhanyad').value}catch(e){};				
		//alert(url);
		var a=new Ajax(url,{
            method:"post",
            onComplete: function(response) {
				//alert("d");
                var resp=Json.evaluate(response);
                $(ajaxContentId).removeClass("ajax-loading").setHTML(resp.html);
                fx.set("#fff").start("#000").chain(function() {
                    this.start.delay(0, this, "#000");
                });
				//alert('');
				$j('#jogtulajdonos_id').val('');
				$j('#tulhanyad').val('');
            }
        }).request();
}

function getJogtulajdonosok(){

	var ajaxContentId ="jogtulajdonosok";
	var fx=new Fx.Style($(ajaxContentId), "color", { duration:400 } );
		$(ajaxContentId).empty().addClass("ajax-loading").setHTML('<img src="components/com_wh/assets/images/ajax-loader.gif">' );
        var url="index.php?option=com_wh&controller=szerzo&task=getJogtulajdonosok&format=raw";
		try {url += "&szerzo_id="+$('id').value}catch(e){};

        
		var a=new Ajax(url,{
            method:"post",
            onComplete: function(response) {
				//alert("d");
                var resp=Json.evaluate(response);
                $(ajaxContentId).removeClass("ajax-loading").setHTML(resp.html);
                fx.set("#fff").start("#000").chain(function() {
                    this.start.delay(0, this, "#000");
                });
            }
        }).request();
}


function hozzaadFiktivRendeles( rendeles_id ){
	var ajaxContent = "ajaxContentRendeles_"+rendeles_id;
	var fx=new Fx.Style($(ajaxContent), "color", {duration:400});
		$(ajaxContent).empty().addClass("ajax-loading").setHTML("<img src='components/com_wh/assets/images/ajax-loader.gif' border='0'>" );
        var url="index.php?option=com_wh&controller=rendelesek&task=hozzaadFiktivRendeles&format=raw&rendeles_id="+rendeles_id;
        var a=new Ajax(url,{
            method:"post",
            onComplete: function(response) {
                var resp=Json.evaluate(response);
                $(ajaxContent).removeClass("ajax-loading").setHTML(resp.html);
                fx.set("#fff").start("#000").chain(function() {
                    this.start.delay(0, this, "#000");
                });
            }
        }).request();
}

function getTelepules(telepules_id){

	megye = $('megye').value;
	var atvhely_id = $('id').value;
	var fx=new Fx.Style($("ajaxContentTelepules"), "color", {duration:400});
		//$("ajaxContentTelepules").empty().addClass("ajax-loading").setHTML("<img src='components/com_wh/assets/images/ajax-loader.gif' border='0'>" );
        var url="index.php?option=com_wh&controller=atvhely&task=getTelepules&format=raw"
		url += "&megye="+megye+"&telepules_id="+telepules_id+"&atvhely_id="+atvhely_id;
        var a=new Ajax(url,{
            method:"post",
            onComplete: function(response) {
                var resp=Json.evaluate(response);
                $("ajaxContentTelepules").removeClass("ajax-loading").setHTML(resp.html);
                fx.set("#fff").start("#000").chain(function() {
                    this.start.delay(0, this, "#000");
                });
            }
        }).request();
}

function setGrafika(obj, dir, hiddenId, grafikaImgId){
	//alert( dir+obj.value );
	//alert( $(hiddenId).value );
	var src = dir+obj.value;
	$(hiddenId).value = obj.value;
	if(src){
		$(grafikaImgId).setHTML("<img src='"+src+"' border='0'>" );
	}else{
		$(grafikaImgId).setHTML("" );	
	}
}

function kapcsolHiddenByCheck(check, hidden){
	if($(check).checked){
		$(hidden).value = $(check).value;
	}else{
		$(hidden).value ='';
	}
}

function setDatum(obj, id, datum){
	if(obj.checked){
		if(!$(id).value){
			$(id).value = datum;
		}
	}else{
		$(id).value = '';
	}
}

function setBeszallito_netto_ar(obj, selectedIndex, beszallito_ar_arr_temp){
	obj.value = beszallito_ar_arr_temp[selectedIndex];
}

function arNettoBrutto( N, B, afa, sw, termek_id ){
	//alert(termek_id);
	var sz = afa/100+1;	
	if(sw=="nettoBol"){
		$(B).value = $(N).value*sz;
	}else{
		var sz = afa/100+1;
		$(N).value = $(B).value/sz;
	}
	//alert($(N).value + afa + N + termek_id);
	setHaszonAjax( $(N).value, afa, N, termek_id );
}

function changeVal(obj){
	if(obj.value==1){
		obj.value=2;
	}else{
		obj.value=1;
	}
	//alert(obj);
}

function menthetoE(task){
	var termek_ar = 0;
	var javasolt_ar = 0;
	var webshopszam = document.getElementById("webshopszam").value;
	var mentheto = true;
	
	if(document.getElementById("javasolt_ar") != null){
		javasolt_ar = document.getElementById("javasolt_ar").value;
	
		for(i=0; i<webshopszam; i++){
			if(document.getElementById("ar"+i).value.length > 0 && document.getElementById("ar"+i).value > 0){
				termek_ar = document.getElementById("ar"+i).value;
				if(parseFloat(termek_ar) < parseFloat(javasolt_ar)){
				mentheto = false;
				document.getElementById("ar"+i).style.color = "red";
				}
			}
		}
		if(mentheto == true){
			document.getElementById('task').value = task; 
			tabEllenoriz(); 
			document.getElementById('adminForm').submit();
		}
		else{
			alert('A megadott ar hibas!\n');
		}
	}
	else{
		document.getElementById('task').value = task; 
		tabEllenoriz(); 
		document.getElementById('adminForm').submit();
	}
}
function valtasSzazalekka(input_szama){
	var szazalek = 0;
	var javasolt_ar = document.getElementById("javasolt_ar").value;
	var webshopszam = document.getElementById("webshopszam").value;
	for(i=0; i<webshopszam; i++){
		if(input_szama == i && document.getElementById("ar"+i).value.length > 0 && document.getElementById("ar"+i).value > 0){
			szazalek = ((document.getElementById("ar"+i).value/javasolt_ar)-1)*100;
			
	 		document.getElementById("ar_szazalek"+i).value = szazalek.toFixed(2);
			
			if(parseFloat(szazalek)<0){
				document.getElementById("ar_szazalek"+i).style.color = "red";
			}
			else if(parseFloat(szazalek)>=0 && szazalek<=10){
				document.getElementById("ar_szazalek"+i).style.color = "orange";
			}
			else{
				document.getElementById("ar_szazalek"+i).style.color = "green";
			}
		}
	}
}

function valtasArra(input_szama, javasolt_ar){
	var webshopszam = document.getElementById("webshopszam").value;
	var szazalek = 0;
	for(i=0; i<webshopszam; i++){
		
		if(input_szama == i && document.getElementById("ar_szazalek"+i).value.length > 0){
	 		document.getElementById("ar"+i).value = Math.round(((document.getElementById("ar_szazalek"+i).value/100)*javasolt_ar)+javasolt_ar);
			szazalek = document.getElementById("ar_szazalek"+i).value;
			if(parseFloat(szazalek)<0){
				document.getElementById("ar_szazalek"+i).style.color = "red";
			}
			else if(parseFloat(szazalek)>=0 && szazalek<=10){
				document.getElementById("ar_szazalek"+i).style.color = "orange";
			}
			else{
				document.getElementById("ar_szazalek"+i).style.color = "green";
			}
		}
	}
}

function tabEllenoriz(){
	for (i=0; i<20; i++){
		try
		  {
		  var obj = document.getElementById("panel"+i);
		  	if(obj.className == "open"){
				 document.getElementById("aktiv_pane_id").value = i-1; 
			  	}
		  	}
		catch(err)
		  {
		  //Handle errors here
		  }	
	}
}

function confirmPost()
{
var agree
var ok = false
		var max = document.getElementById("adminForm").elements.length;
		for (var i = 0; i < (max-7); i++)
		{
			if(document.getElementById("adminForm").elements["cb"+i].checked == true){
				ok = true;
			}
		}
		if(ok){
			agree = confirm("Biztosan törlöd?");
			if(agree)return true;
			else return false;
		}
		else{
			alert("Kérem válassz elemet a listából");	
			return false;
		}
}

function confirmPostSM()
{
var agree
var ok = false
		var max = document.getElementById("adminForm").elements.length;
		for (var i = 0; i < (max-8); i++)
		{
			if(document.getElementById("adminForm").elements["cb"+i].checked == true){
				ok = true;
			}
		}
		if(ok){
			agree = confirm("Biztosan törlöd?");
			if(agree)return true;
			else return false;
		}
		else{
			alert("Kérem válassz elemet a listából");	
			return false;
		}
}

function torolTermek()
{
var agree
var ok = false
		var max = document.getElementById("adminForm").elements.length;
		for (var i = 0; i < (max-9); i++)
		{
			if(document.getElementById("adminForm").elements["cb"+i].checked == true){
				ok = true;
			}
		}
		if(ok){
			agree = confirm("Biztosan törlöd?");
			if(agree)return true;
			else return false;
		}
		else{
			alert("Kérem válassz elemet a listából");	
			return false;
		}
}

function torolKateg()
{
var agree
var ok = false
		var max = document.getElementById("adminForm").elements.length;

		for (var i = 0; i < (max-6)/2; i++)
		{
			if(document.getElementById("adminForm").elements["cb"+i].checked == true){
				ok = true;
			}
		}
		if(ok){
			agree = confirm("Biztosan törlöd?");
			if(agree)return true;
			else return false;
		}
		else{
			alert("Kérem válassz elemet a listából");	
			return false;
		}
}

function kivalasztBeszallito()
{
	var max = document.getElementById("adminForm").elements.length;
	//alert(max);
	for (var i = 0; i < (max-8); i++)
	{
			if(document.getElementById("adminForm").elements["cb"+i].checked == true){
				return true;
			}
		}
	alert("Kérem válassz elemet a listából");
	return false;
}
function kivalasztTermek()
{
	var max = document.getElementById("adminForm").elements.length;
	//alert("max");
	for (var i = 0; i < (max-10); i++)
	{
			if(document.getElementById("adminForm").elements["cb"+i].checked == true){
				return true;
			}
		}
	alert("Kérem válassz elemet a listából");
	return false;
}
function sorrend(id, irany){
		document.getElementById('sorrendId').value=id;
		document.getElementById('irany').value=irany;				
		document.getElementById('task').value='sorrend';
		document.getElementById('adminForm').submit();
	}
function sorrend2(id, irany, tablanev, mezo_idk, tabla_id, mezonev){
		document.getElementById('sorrendId').value=id;
		document.getElementById('irany').value=irany;
		document.getElementById('tablanev').value=tablanev;
		document.getElementById('mezo_idk').value=mezo_idk;
		document.getElementById('tabla_id').value=tabla_id;
		document.getElementById('task').value='sorrend';
		document.getElementById('mezonev').value=mezonev;
		document.getElementById('adminForm').submit();
	}   
function kepSorrendezo(irany, sorrend, termek_id){
		document.getElementById('lepetetes_iranya').value=irany;
		document.getElementById('sorrend').value=sorrend;
		document.getElementById('termek_id').value=termek_id;
		document.getElementById('adminForm').submit();
	} 