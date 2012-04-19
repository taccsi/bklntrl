//alert('valami ******')
function getTermekTipus() {
	var termek_id = $j("#id").val();
	var url = "index.php?option=com_wh&controller=termek&task=getTermekTipus&format=raw";
	url += "&termek_id=" + termek_id;
	url += "&termek_tipus=" + $j("#termek_tipus").val();
	var ajaxContentId = "ajaxContentTermekTipus";
	//console.log( url );
	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			var resp = $j.parseJSON(response);
			if(!resp.error) {
				//alert('--');  
				getKalkulatorMezokFotermek();
				//$j( "#" + ajaxContentId ).html( "" );  
			} else {  
				alert(resp.error); 
			}
		}
	}); 
}

function mentTermekTipusTv(name, obj) {
	var parent = $j(obj).parent();
	var rel = $j(parent).attr("rel");
	var termek_id = $j("#id").val();
	var url = "index.php?option=com_wh&controller=termek&task=mentTermekTipusTv&format=raw";
	url += "&tvId=" + rel;
	//url += "&termek_tipus=" + $j( "#termek_tipus" ).val();
	//alert( url );
	//ajaxContentTermekTipusTv[rel="+rel+"]"
	$j("#ajaxContentTermekTipusTv" + rel + " .termek_tipus_input").each(function() {
		var name = $j(this).attr("name");
		url += "&" + name + "=" + $j(this).val();
		url += "&parArr[]=" + name;
	});
	//alert(url)
	$j("#ajaxContentCsiga").html('<img src="components/com_wh/assets/images/ajax-loader.gif" />');
	//console.log(url);
	var ajaxContentId = "ajaxContentTermekTipus";
	//console.log( url );
	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			var resp = $j.parseJSON(response);
			if(!resp.error) {
				$j("#ajaxContentCsiga").html('');
				//getKalkulatorMezokFotermek();
				//$j( "#" + ajaxContentId ).html( resp.html );
				//$j( ".ajaxContentTermekTipusTv" ).html( resp.html );
			} else {
				alert(resp.error);
			}
		}
	});
}

function getKalkulatorMezokFotermek() {
	var termek_id = $j("#id").val();
	var url = "index.php?option=com_wh&controller=termek&task=getKalkulatorMezokFotermek&format=raw";
	url += "&termek_id=" + termek_id;
	url += "&termek_tipus=" + $j("#termek_tipus").val();
	//var ajaxContentId = "ajaxContentTermekTipus";
	//console.log( url );
	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			var resp = $j.parseJSON(response);
			if(!resp.error) {
				//getKalkulatorMezokFotermek();
				//$j( "#" + ajaxContentId ).html( resp.html );
				$j(".ajaxContentTermekTipusTv").html(resp.html);
			} else {
				alert(resp.error);
			}
		}
	});
}

function setListaKep(inputId) {
	//alert(inputId);
	//alert( $j( "#inputId:checked" ) );
	if($j("#" + inputId).is(":checked")) {
		var listakep_id = $j("#" + inputId).val();
	} else {
		var listakep_id = 0;
	}

	var termek_id = $j("#id").val();
	var url = "index.php?option=com_wh&controller=termek&task=setListaKep&format=raw";
	url += "&listakep_id=" + listakep_id;
	url += "&termek_id=" + termek_id;
	var ajaxContentId = "ajaxContentParameterLista";
	//alert(url);
	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			//alert(response);
			var resp = $j.parseJSON(response);
			//alert("";)
			getKepLista()
		}
	});
}

function termvarIrany(irany, termvar_id) {
	//alert('---');
	var termek_id = $j('#id').val();
	//$("ajaxContentParameterLista").empty().addClass("ajax-loading").setHTML("<img src='components/com_wh/assets/images/ajax-loader.gif' border='0'>" );
	var url = "index.php?option=com_wh&controller=termek&task=termvarIrany&format=raw&termek_id=" + termek_id + "&termvar_id=" + termvar_id + "&irany=" + irany;

	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			//alert(response);
			var resp = $j.parseJSON(response);
			getTermekVariaciok();
		}
	});

}

function letrehozUjTermekVariacio(termek_id) {
	//alert(parameter_id);
	var url = "index.php?option=com_wh&controller=termek&task=letrehozUjTermekVariacio&format=raw&termek_id=" + termek_id;
	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			//alert(response);
			var resp = $j.parseJSON(response);
			getTermekVariaciok();
		}
	});

}

function torolTermekVariacio( tvar_id, termek_id ) {
	//alert(termek_id);
	var url = "index.php?option=com_wh&controller=termek&task=torolTermekVariacio&format=raw&tvar_id=" + tvar_id + "&termek_id=" + termek_id;
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
				$j("#ajaxContentParameterLista" ).html(resp.html);
				//getKapcsolodoTermekek();
			} else {
				alert(resp.error);
				//error
			}
		}
	});
	/*
	var a = new Ajax(url, {
		method : "post",
		onComplete : function(response) {
			var resp = Json.evaluate(response);
			$("ajaxContentParameterLista").removeClass("ajax-loading").setHTML(resp.html);
			fx.set("#fff").start("#000").chain(function() {
				this.start.delay(0, this, "#000");
			});
		}
	}).request();
	*/
}

function getTermekVariaciok() {
	var termek_id = $j("#id").val();
	var url = "index.php?option=com_wh&controller=termek&task=getTermekVariaciok&format=raw&termek_id=" + termek_id;
	var ajaxContentId = "ajaxContentParameterLista";
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
				$j( "#" + ajaxContentId).html(resp.html);
				//getKapcsolodoTermekek();
			} else {
				alert(resp.error);
				//error
			}
		} 
	});

}

function setProductPriceNettoBrutto(wrapId, from) {
	//alert('');
	var afa = $j("#" + wrapId + " select option:selected").text();
	if($j(from).attr("class") == "afaSelect") {
		$j("#" + wrapId + " .netto").each(function(index) {
			var arr = $j("#" + wrapId + " .brutto");
			var val = Math.round($j(this).val() * (afa / 100 + 1));
			$j(arr[index]).val((val) ? val : "");
		});
	} else if($j(from).attr("class") == "netto") {
		$j("#" + wrapId + " .netto").each(function(index) {
			var arr = $j("#" + wrapId + " .brutto");
			var val = Math.round($j(this).val() * (afa / 100 + 1));
			$j(arr[index]).val((val) ? val : "");
		});
	} else if($j(from).attr("class") == "brutto") {
		$j("#" + wrapId + " .brutto").each(function(index) {
			var arr = $j("#" + wrapId + " .netto");
			var val = Math.round($j(this).val() / (afa / 100 + 1));
			$j(arr[index]).val((val) ? val : "");
		});
	}
}

function getProductPriceList(product_id ) {
	//alert( ajaxContentId );
	var ajaxContentId = (ajaxContentId ) ? ajaxContentId : "ajaxContentProductPriceList";
	var url = "index.php?option=com_wh&controller=termek&task=getProductPriceList&format=raw&product_id=" + product_id;
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
				//alert(resp.html); 
				$j("#ajaxContentProductPriceList" ).html(resp.html);
				//$j("#ajaxContentProductPriceList" ).html("-");				
				//getKapcsolodoTermekek();
			} else {
				alert(resp.error);
				//error
			}
		}
	});
}

function kapcsolodoTermekIrany(irany, kapcsolo_id) {
	termek_id = $('id').value;
	var url = "index.php?option=com_wh&controller=termek&task=kapcsolodoTermekIrany&format=raw&termek_id=" + kapcsolo_id + "&irany=" + irany;
	url += "&kapcsolo_id=" + $j('#id').val();
	alert(url);
	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			//alert(response);
			var resp = $j.parseJSON(response);
			if(!resp.error) {
				getKapcsolodoTermekek();
			} else {
				alert(resp.error);
				//error
			}
		}
	});
}

function torolKapcsolodoTermek(kapcsolodo_termek_id) {
	var url = "index.php?option=com_wh&controller=termek&task=torolKapcsolodoTermek&format=raw";
	url += "&termek_id=" + $j("#id").val();
	url += "&kapcsolodo_termek_id=" + kapcsolodo_termek_id;
	$j.ajax({
		url : url,
		type : "POST",
		async : false,
		/*dataType: "html",*/
		success : function(response) {
			//alert(response);
			//alert('succccccc');
			//var resp= $j.parseJSON( response );
			getKapcsolodoTermekek();
			if(!resp.error) {
			} else {
				alert(resp.error);
				//error
			}
		}
	});
}

function initAutoComplete(inputId, arrName, inputIdHidden) {

	try {
		var v_ = this["msablonMezoArr"];
		$j("#msablon_mezo").autocomplete(v_, {
			matchContains : true,
			selectFirst : false,
			max : 100
		});
		//alert( $j("#jogtulajdonos_id") );
	} catch(e) {

	}

	var option = {
		matchContains : true,
		selectFirst : false,
		formatItem : function(row, i, max) {
			//return i + "/" + max + ": \"" + row.option + "\" [" + row.value + "]";
			return row.option + row.value;
		},
		formatMatch : function(row, i, max) {
			//$j("#"+inputIdHidden).val( row.value+2222 );
			return row.option + " " + row.value;
		},
		formatResult : function(row) {
			//alert(inputIdHidden);
			return row.option + " (" + row.value + ")";
		},
		max : 100
	}
	//alert(  "#"+inputId );
	$j("#" + inputId).autocomplete(this[arrName], option);
}

function hozzaadKapcsolodoTermek() {
	var url = "index.php?option=com_wh&controller=termek&task=hozzaadKapcsolodoTermek&format=raw";
	url += "&termek_id=" + $j("#id").val();
	url += "&kapcsolodo_termek_id=" + $j("#kapcsolodo_termek_id").val();
	//url += "&value="+encodeURI(value);
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
				$j("#kapcsolodo_termek_id").val('');
				getKapcsolodoTermekek();
			} else {
				alert(resp.error);
				//error
			}
		}
	});
}

function getKapcsolodoTermekek() {
	var url = "index.php?option=com_wh&controller=termek&task=getKapcsolodoTermekek&format=raw";
	url += "&termek_id=" + $j("#id").val();
	//url += "&value="+encodeURI(value);
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
				$j('#ajaxContentKapcsolodTermekek').html(resp.html);
			} else {
				alert(resp.error);
				//error
			}
		}
	});
}

function setNetGrossPrice(netId, grossId, obj) {
	if(obj == '') {
		obj = $j('#' + netId);
		//alert(obj);
	}
	var vat = $j("#vat_id :selected").text();
	if($j(obj).attr("id") == netId) {
		var target = "#" + grossId;
		var value = $j("#" + netId).val() * (vat / 100 + 1);
	} else {
		var target = "#" + netId;
		var value = $j("#" + grossId).val() / (vat / 100 + 1);
	}
	//value = Math.round( value );
	$j(target).val(value);
	//var netId = "netPrice_RETAIL_"+currency+"_"+ind;
	//var grossId = "grossPrice_RETAIL_"+currency+"_"+ind;
}

function changeVatId() {
	var vat = $j("#vat_id :selected").text();
	var grossArr = $j('.gross_RETAIL');
	$j('.net_RETAIL').each(function(index) {
		var value = $j(this).val() * (vat / 100 + 1);
		$j(grossArr[index]).val(value);
	});
	var grossArr = $j('.gross_WHOLESALE');
	$j('.net_WHOLESALE').each(function(index) {
		var value = $j(this).val() * (vat / 100 + 1);
		$j(grossArr[index]).val(value);
	});
}