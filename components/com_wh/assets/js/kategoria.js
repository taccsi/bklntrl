

function sorrendKategoria(sorrendId, irany) {
	var controller = 'kategoriak';
	var task = 'sorrendKategoria';
	var ajaxContentId = "ajaxContentKategoriak";
	//$(ajaxContentId).empty().addClass("ajax-loading").setHTML('<img src="components/com_wh/assets/images/ajax-loader.gif">' );
	var url = "index.php?option=com_wh&format=raw";
	try {
		url += "&controller=" + controller
	} catch(e) {
	};
	try {
		url += "&task=" + task
	} catch(e) {
	};
	try {
		url += "&cond_kategoria_id=" + $j('#cond_kategoria_id').val();
	} catch(e) {
	};
	try {
		url += "&cond_kategoria_szulo=" + $j('#cond_kategoria_szulo').val();
	} catch(e) {
	};
	try {
		url += "&sorrendId=" + sorrendId
	} catch(e) {
	};
	try {
		url += "&irany=" + irany
	} catch(e) {
	};
	//alert(url);
	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			var resp = $j.parseJSON(response);
			if(!resp.error) {
				$j( "#" + ajaxContentId ).html( resp.html );
			} else {
				alert(resp.error);
			}
		}
	});
}


function getKategoriak() {
	//alert("*****");
	var controller = 'kategoriak';
	var task = 'getKategoriak';
	var ajaxContentId = "ajaxContentKategoriak";
	//$(ajaxContentId).empty().addClass("ajax-loading").setHTML('<img src="components/com_wh/assets/images/ajax-loader.gif">' );
	var url = "index.php?option=com_wh&format=raw";
	try {
		url += "&controller=" + controller
	} catch(e) {
	};
	try {
		url += "&task=" + task
	} catch(e) {
	};
	try {
		val = $j('#cond_kategoria_id').val();
		url += "&cond_kategoria_id=" + ( val == "undefined" ) ? "" : val;
	} catch(e) {
	};
	try {
		url += "&cond_kategoria_szulo=" + $j('#cond_kategoria_szulo').val();
	} catch(e) {
	};
	//alert(url);
	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			var resp = $j.parseJSON(response);
			if(!resp.error) {
				$j( "#" + ajaxContentId ).html( resp.html );
			} else {
				alert(resp.error);
			}
		}
	});
}

function getFokatSelect() {
	var controller = 'kategoriak';
	var task = 'getFokatSelect';
	var ajaxContentId = "ajaxContentFokatSelect";
	//var fx=new Fx.Style( $(ajaxContentId), "color", { duration:400 } );
	//$(ajaxContentId).empty().addClass("ajax-loading").setHTML('<img src="components/com_wh/assets/images/ajax-loader.gif">' );
	var url = "index.php?option=com_wh&format=raw";
	url += "&controller=" + controller
	url += "&task=" + task
	url += "&cond_kategoria_id=" + $j('#cond_kategoria_id').val();
	url += "&cond_kategoria_szulo=" + $j('#cond_kategoria_szulo').val();
	try{url += "&sorrendId=" + sorrendId}catch(e){}
	try{url += "&irany=" + irany}catch(e){}	
	//alert(url);
	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			var resp = $j.parseJSON(response);
			if(!resp.error) {
				//alert('--');
				//getKalkulatorMezokFotermek();
				//$j( "#" + ajaxContentId ).html( "" );
			} else {
				alert(resp.error);
			}
		}
	});
}
