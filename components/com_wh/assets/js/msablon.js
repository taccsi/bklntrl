// JavaScript Document

function mezoIrany(irany, kapcsolo_id) {
	var controller = 'msablon';
	var task = 'mentMsablonMezo';
	var ajaxContentId = 'ajaxContentmsablonMezok';
	termek_id = $j('#id').val();
	var url = "index.php?option=com_wh&controller=msablon&task=mezoIrany&format=raw&kapcsolo_id=" + kapcsolo_id + "&irany=" + irany;
	try {
		url += "&msablon_id=" + $('id').value
	} catch(e) {
	};

	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			//alert(response);
			var resp = $j.parseJSON(response);
			if(!resp.error) {
				getMsablonMezok("ajaxContentmsablonMezok");
			} else {
				alert(resp.error);
				//error
			}
		}
	});
}

function mentMsablonMezo(checked, mezo_id) {
	//alert($(idHidden).value);
	var controller = 'msablon';
	var task = 'mentMsablonMezo';
	var ajaxContentId = 'ajaxContentmsablonMezok';
	var fx = new Fx.Style($(ajaxContentId), "color", {
		duration : 400
	});
	$(ajaxContentId).empty().addClass("ajax-loading").setHTML('<img src="components/com_wh/assets/images/ajax-loader.gif">');
	var url = "index.php?option=com_wh&controller=szerzo&task=torolMsablonMezo&format=raw";
	try {
		url += "&controller=" + controller
	} catch(e) {
	};
	try {
		url += "&task=" + task
	} catch(e) {
	};
	try {
		url += "&msablon_id=" + $('id').value
	} catch(e) {
	};
	try {
		url += "&mezo_id=" + mezo_id
	} catch(e) {
	};
	try {
		url += "&checked=" + checked
	} catch(e) {
	};
	//alert(url);
	var a = new Ajax(url, {
		method : "post",
		onComplete : function(response) {
			//alert("d");
			var resp = Json.evaluate(response);
			$(ajaxContentId).removeClass("ajax-loading").setHTML(resp.html);
			fx.set("#fff").start("#000").chain(function() {
				this.start.delay(0, this, "#000");
			});
		}
	}).request();
}

function torolMsablonMezo(kapcsolo_id) {
	var controller = 'msablon';
	var task = 'torolMsablonMezo';
	var ajaxContentId = 'ajaxContentmsablonMezok';

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
		url += "&msablon_id=" + $('id').value
	} catch(e) {
	};
	try {
		url += "&kapcsolo_id=" + kapcsolo_id
	} catch(e) {
	};

	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			//alert(response);
			var resp = $j.parseJSON(response);
			if(!resp.error) {
				getMsablonMezok("ajaxContentmsablonMezok");
			} else {
				alert(resp.error);
				//error
			}
		}
	});

}

function hozzaadMsablonMezo(ajaxContentId) {
	var controller = 'msablon';
	var task = 'hozzaadMsablonMezo';
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
		url += "&msablon_mezo=" + $('msablon_mezo').value
	} catch(e) {
	};
	try {
		url += "&msablon_id=" + $('id').value
	} catch(e) {
	};
	//alert(url);

	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			//alert(response);
			var resp = $j.parseJSON(response);
			if(!resp.error) {
				//alert(resp.debug);
				//$j("#" + ajaxContentId).html(resp.html);
				//$j('#msablon_mezo').val('');
				getMsablonMezok("ajaxContentmsablonMezok");
			} else {
				alert(resp.error);
				//error
			}
		}
	});
}

function getMsablonMezok(ajaxContentId) {
	var controller = 'msablon';
	var task = 'getMsablonMezok';
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
		url += "&msablon_id=" + $('id').value
	} catch(e) {
	};
	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			//alert(response);
			var resp = $j.parseJSON(response);
			if(!resp.error) {
				$j("#" + ajaxContentId).html(resp.html);
			} else {
				alert(resp.error);
				//error
			}
		}
	});
}