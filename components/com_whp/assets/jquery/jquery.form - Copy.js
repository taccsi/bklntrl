(function(a){function b(){var a="[jquery.form] "+Array.prototype.join.call(arguments,"");if(window.console&&window.console.log){window.console.log(a)}else if(window.opera&&window.opera.postError){window.opera.postError(a)}}a.fn.ajaxSubmit=function(c){function r(d){function z(c){if(m.aborted||y){return}try{w=t(k)}catch(d){b("cannot access response document: ",d);c=s}if(c===r&&m){m.abort("timeout");return}else if(c==s&&m){m.abort("server abort");return}if(!w||w.location.href==g.iframeSrc){if(!p)return}k.detachEvent?k.detachEvent("onload",z):k.removeEventListener("load",z,false);var e="success",f;try{if(p){throw"timeout"}var i=g.dataType=="xml"||w.XMLDocument||a.isXMLDoc(w);b("isXml="+i);if(!i&&window.opera&&(w.body==null||w.body.innerHTML=="")){if(--x){b("requeing onLoad callback, DOM not available");setTimeout(z,250);return}}var l=w.body?w.body:w.documentElement;m.responseText=l?l.innerHTML:null;m.responseXML=w.XMLDocument?w.XMLDocument:w;if(i)g.dataType="xml";m.getResponseHeader=function(a){var b={"content-type":g.dataType};return b[a]};if(l){m.status=Number(l.getAttribute("status"))||m.status;m.statusText=l.getAttribute("statusText")||m.statusText}var n=g.dataType||"";var o=/(json|script|text)/.test(n.toLowerCase());if(o||g.textarea){var u=w.getElementsByTagName("textarea")[0];if(u){m.responseText=u.value;m.status=Number(u.getAttribute("status"))||m.status;m.statusText=u.getAttribute("statusText")||m.statusText}else if(o){var B=w.getElementsByTagName("pre")[0];var D=w.getElementsByTagName("body")[0];if(B){m.responseText=B.textContent?B.textContent:B.innerHTML}else if(D){m.responseText=D.innerHTML}}}else if(g.dataType=="xml"&&!m.responseXML&&m.responseText!=null){m.responseXML=A(m.responseText)}try{v=C(m,g.dataType,g)}catch(c){e="parsererror";m.error=f=c||e}}catch(c){b("error caught: ",c);e="error";m.error=f=c||e}if(m.aborted){b("upload aborted");e=null}if(m.status){e=m.status>=200&&m.status<300||m.status===304?"success":"error"}if(e==="success"){g.success&&g.success.call(g.context,v,"success",m);h&&a.event.trigger("ajaxSuccess",[m,g])}else if(e){if(f==undefined)f=m.statusText;g.error&&g.error.call(g.context,m,e,f);h&&a.event.trigger("ajaxError",[m,g,f])}h&&a.event.trigger("ajaxComplete",[m,g]);if(h&&!--a.active){a.event.trigger("ajaxStop")}g.complete&&g.complete.call(g.context,m,e);y=true;if(g.timeout)clearTimeout(q);setTimeout(function(){if(!g.iframeTarget)j.remove();m.responseXML=null},100)}function u(){function f(){try{var a=t(k).readyState;b("state = "+a);if(a.toLowerCase()=="uninitialized")setTimeout(f,50)}catch(c){b("Server abort: ",c," (",c.name,")");z(s);q&&clearTimeout(q);q=undefined}}var c=l.attr("target"),d=l.attr("action");e.setAttribute("target",i);if(e.getAttribute("method")!="POST"){e.setAttribute("method","POST")}if(e.getAttribute("action")!=g.url){e.setAttribute("action",g.url)}if(!g.skipEncodingOverride){l.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"})}if(g.timeout){q=setTimeout(function(){p=true;z(r)},g.timeout)}var h=[];try{if(g.extraData){for(var m in g.extraData){h.push(a('<input type="hidden" name="'+m+'" />').attr("value",g.extraData[m]).appendTo(e)[0])}}if(!g.iframeTarget){j.appendTo("body");k.attachEvent?k.attachEvent("onload",z):k.addEventListener("load",z,false)}setTimeout(f,15);e.submit()}finally{e.setAttribute("action",d);if(c){e.setAttribute("target",c)}else{l.removeAttr("target")}a(h).remove()}}function t(a){var b=a.contentWindow?a.contentWindow.document:a.contentDocument?a.contentDocument:a.document;return b}var e=l[0],f,g,h,i,j,k,m,n,o,p,q;if(d){for(f=0;f<d.length;f++){a(e[d[f].name]).attr("disabled",false)}}if(a(":input[name=submit],:input[id=submit]",e).length){alert('Error: Form elements must not have name or id of "submit".');return}g=a.extend(true,{},a.ajaxSettings,c);g.context=g.context||g;i="jqFormIO"+(new Date).getTime();if(g.iframeTarget){j=a(g.iframeTarget);o=j.attr("name");if(o==null)j.attr("name",i);else i=o}else{j=a('<iframe name="'+i+'" src="'+g.iframeSrc+'" />');j.css({position:"absolute",top:"-1000px",left:"-1000px"})}k=j[0];m={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(c){var d=c==="timeout"?"timeout":"aborted";b("aborting upload... "+d);this.aborted=1;j.attr("src",g.iframeSrc);m.error=d;g.error&&g.error.call(g.context,m,d,c);h&&a.event.trigger("ajaxError",[m,g,d]);g.complete&&g.complete.call(g.context,m,d)}};h=g.global;if(h&&!(a.active++)){a.event.trigger("ajaxStart")}if(h){a.event.trigger("ajaxSend",[m,g])}if(g.beforeSend&&g.beforeSend.call(g.context,m,g)===false){if(g.global){a.active--}return}if(m.aborted){return}n=e.clk;if(n){o=n.name;if(o&&!n.disabled){g.extraData=g.extraData||{};g.extraData[o]=n.value;if(n.type=="image"){g.extraData[o+".x"]=e.clk_x;g.extraData[o+".y"]=e.clk_y}}}var r=1;var s=2;if(g.forceSync){u()}else{setTimeout(u,10)}var v,w,x=50,y;var A=a.parseXML||function(a,b){if(window.ActiveXObject){b=new ActiveXObject("Microsoft.XMLDOM");b.async="false";b.loadXML(a)}else{b=(new DOMParser).parseFromString(a,"text/xml")}return b&&b.documentElement&&b.documentElement.nodeName!="parsererror"?b:null};var B=a.parseJSON||function(a){return window["eval"]("("+a+")")};var C=function(b,c,d){var e=b.getResponseHeader("content-type")||"",f=c==="xml"||!c&&e.indexOf("xml")>=0,g=f?b.responseXML:b.responseText;if(f&&g.documentElement.nodeName==="parsererror"){a.error&&a.error("parsererror")}if(d&&d.dataFilter){g=d.dataFilter(g,c)}if(typeof g==="string"){if(c==="json"||!c&&e.indexOf("json")>=0){g=B(g)}else if(c==="script"||!c&&e.indexOf("javascript")>=0){a.globalEval(g)}}return g}}if(!this.length){b("ajaxSubmit: skipping submit process - no element selected");return this}if(typeof c=="function"){c={success:c}}var d=this.attr("action");var e=typeof d==="string"?a.trim(d):"";e=e||window.location.href||"";if(e){e=(e.match(/^([^#]+)/)||[])[1]}c=a.extend(true,{url:e,success:a.ajaxSettings.success,type:this[0].getAttribute("method")||"GET",iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},c);var f={};this.trigger("form-pre-serialize",[this,c,f]);if(f.veto){b("ajaxSubmit: submit vetoed via form-pre-serialize trigger");return this}if(c.beforeSerialize&&c.beforeSerialize(this,c)===false){b("ajaxSubmit: submit aborted via beforeSerialize callback");return this}var g,h,i=this.formToArray(c.semantic);if(c.data){c.extraData=c.data;for(g in c.data){if(c.data[g]instanceof Array){for(var j in c.data[g]){i.push({name:g,value:c.data[g][j]})}}else{h=c.data[g];h=a.isFunction(h)?h():h;i.push({name:g,value:h})}}}if(c.beforeSubmit&&c.beforeSubmit(i,this,c)===false){b("ajaxSubmit: submit aborted via beforeSubmit callback");return this}this.trigger("form-submit-validate",[i,this,c,f]);if(f.veto){b("ajaxSubmit: submit vetoed via form-submit-validate trigger");return this}var k=a.param(i);if(c.type.toUpperCase()=="GET"){c.url+=(c.url.indexOf("?")>=0?"&":"?")+k;c.data=null}else{c.data=k}var l=this,m=[];if(c.resetForm){m.push(function(){l.resetForm()})}if(c.clearForm){m.push(function(){l.clearForm()})}if(!c.dataType&&c.target){var n=c.success||function(){};m.push(function(b){var d=c.replaceTarget?"replaceWith":"html";a(c.target)[d](b).each(n,arguments)})}else if(c.success){m.push(c.success)}c.success=function(a,b,d){var e=c.context||c;for(var f=0,g=m.length;f<g;f++){m[f].apply(e,[a,b,d||l,l])}};var o=a("input:file",this).length>0;var p="multipart/form-data";var q=l.attr("enctype")==p||l.attr("encoding")==p;if(c.iframe!==false&&(o||c.iframe||q)){if(c.closeKeepAlive){a.get(c.closeKeepAlive,function(){r(i)})}else{r(i)}}else{a.ajax(c)}this.trigger("form-submit-notify",[this,c]);return this};a.fn.ajaxForm=function(c){if(this.length===0){var d={s:this.selector,c:this.context};if(!a.isReady&&d.s){b("DOM not ready, queuing ajaxForm");a(function(){a(d.s,d.c).ajaxForm(c)});return this}b("terminating; zero elements found by selector"+(a.isReady?"":" (DOM not ready)"));return this}return this.ajaxFormUnbind().bind("submit.form-plugin",function(b){if(!b.isDefaultPrevented()){b.preventDefault();a(this).ajaxSubmit(c)}}).bind("click.form-plugin",function(b){var c=b.target;var d=a(c);if(!d.is(":submit,input:image")){var e=d.closest(":submit");if(e.length==0){return}c=e[0]}var f=this;f.clk=c;if(c.type=="image"){if(b.offsetX!=undefined){f.clk_x=b.offsetX;f.clk_y=b.offsetY}else if(typeof a.fn.offset=="function"){var g=d.offset();f.clk_x=b.pageX-g.left;f.clk_y=b.pageY-g.top}else{f.clk_x=b.pageX-c.offsetLeft;f.clk_y=b.pageY-c.offsetTop}}setTimeout(function(){f.clk=f.clk_x=f.clk_y=null},100)})};a.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")};a.fn.formToArray=function(b){var c=[];if(this.length===0){return c}var d=this[0];var e=b?d.getElementsByTagName("*"):d.elements;if(!e){return c}var f,g,h,i,j,k,l;for(f=0,k=e.length;f<k;f++){j=e[f];h=j.name;if(!h){continue}if(b&&d.clk&&j.type=="image"){if(!j.disabled&&d.clk==j){c.push({name:h,value:a(j).val()});c.push({name:h+".x",value:d.clk_x},{name:h+".y",value:d.clk_y})}continue}i=a.fieldValue(j,true);if(i&&i.constructor==Array){for(g=0,l=i.length;g<l;g++){c.push({name:h,value:i[g]})}}else if(i!==null&&typeof i!="undefined"){c.push({name:h,value:i})}}if(!b&&d.clk){var m=a(d.clk),n=m[0];h=n.name;if(h&&!n.disabled&&n.type=="image"){c.push({name:h,value:m.val()});c.push({name:h+".x",value:d.clk_x},{name:h+".y",value:d.clk_y})}}return c};a.fn.formSerialize=function(b){return a.param(this.formToArray(b))};a.fn.fieldSerialize=function(b){var c=[];this.each(function(){var d=this.name;if(!d){return}var e=a.fieldValue(this,b);if(e&&e.constructor==Array){for(var f=0,g=e.length;f<g;f++){c.push({name:d,value:e[f]})}}else if(e!==null&&typeof e!="undefined"){c.push({name:this.name,value:e})}});return a.param(c)};a.fn.fieldValue=function(b){for(var c=[],d=0,e=this.length;d<e;d++){var f=this[d];var g=a.fieldValue(f,b);if(g===null||typeof g=="undefined"||g.constructor==Array&&!g.length){continue}g.constructor==Array?a.merge(c,g):c.push(g)}return c};a.fieldValue=function(b,c){var d=b.name,e=b.type,f=b.tagName.toLowerCase();if(c===undefined){c=true}if(c&&(!d||b.disabled||e=="reset"||e=="button"||(e=="checkbox"||e=="radio")&&!b.checked||(e=="submit"||e=="image")&&b.form&&b.form.clk!=b||f=="select"&&b.selectedIndex==-1)){return null}if(f=="select"){var g=b.selectedIndex;if(g<0){return null}var h=[],i=b.options;var j=e=="select-one";var k=j?g+1:i.length;for(var l=j?g:0;l<k;l++){var m=i[l];if(m.selected){var n=m.value;if(!n){n=m.attributes&&m.attributes["value"]&&!m.attributes["value"].specified?m.text:m.value}if(j){return n}h.push(n)}}return h}return a(b).val()};a.fn.clearForm=function(){return this.each(function(){a("input,select,textarea",this).clearFields()})};a.fn.clearFields=a.fn.clearInputs=function(){return this.each(function(){var a=this.type,b=this.tagName.toLowerCase();if(a=="text"||a=="password"||b=="textarea"){this.value=""}else if(a=="checkbox"||a=="radio"){this.checked=false}else if(b=="select"){this.selectedIndex=-1}})};a.fn.resetForm=function(){return this.each(function(){if(typeof this.reset=="function"||typeof this.reset=="object"&&!this.reset.nodeType){this.reset()}})};a.fn.enable=function(a){if(a===undefined){a=true}return this.each(function(){this.disabled=!a})};a.fn.selected=function(b){if(b===undefined){b=true}return this.each(function(){var c=this.type;if(c=="checkbox"||c=="radio"){this.checked=b}else if(this.tagName.toLowerCase()=="option"){var d=a(this).parent("select");if(b&&d[0]&&d[0].type=="select-one"){d.find("option").selected(false)}this.selected=b}})};})(jQuery)