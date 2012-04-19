/*
*	Class: fileUploader
*	Use: Upload multiple files the jQuery way
*	Author: Michael Laniba (http://pixelcone.com)
*	Version: 1.0
*/
/*
function initKepfeltoltes(SIKERES_FELTOLTES, HIBA){
	//alert(SIKERES_FELTOLTES);
	this.SIKERES_FELTOLTES=SIKERES_FELTOLTES;
	this.HIBA=HIBA;
}
*/
(function($j) {
	$j.fileUploader = {version: '1.0'};
	$j.fn.fileUploader = function(config){
		
		config = $j.extend({}, {
			imageLoader: 'components/com_whp/assets/js/fileuploader/image_upload.gif',
			buttonUpload: '#pxUpload',
			buttonClear: '#pxClear',
			successOutput: 'Sikeres feltöltés',
			errorOutput: 'Hiba',
			inputName: 'userfile',
			inputSize: 30,
			allowedExtension: 'jpg|jpeg'
		}, config);
		
		var itr = 0; //number of files to uploaded
		
		//public function
		$j.fileUploader.change = function(e){
			var fname = px.validateFile( $j(e).val() );
			if (fname == -1){
				alert ("Invalid file!");
				$j(e).val("");
				return false;
			}
			$j('#px_button input').removeAttr("disabled");
			var imageLoader = '';
			if ($j.trim(config.imageLoader) != ''){
				imageLoader = '<img src="'+ config.imageLoader +'" alt="uploader" />';
			}
			var display = '<div class="uploadData" id="pxupload'+ itr +'_text" title="pxupload'+ itr +'">' + 
				'<div class="close">&nbsp;</div>' +
				'<span class="fname">'+ fname +'</span>' +
				'<span class="loader" style="display:none">'+ imageLoader +'</span>' +
				'<div class="status">Feltöltésre várakozik...</div></div>';
			
			$j("#px_display").append(display);
			px.appendForm();
			$j(e).hide();
		}
		
		$j(config.buttonUpload).click(function(){
			if (itr > 1){
				$j('#px_button input').attr("disabled","disabled");
				$j("#pxupload_form form").each(function(){
					e = $j(this);
					var id = "#" + $j(e).attr("id");
					var input_id = id + "_input";
					var input_val = $j(input_id).val();
					if (input_val != ""){
						$j(id + "_text .status").text("Feltöltés folyamatban...");
						$j(id + "_text").css("background-color", "#FFF0E1");
						$j(id + "_text .loader").show();
						$j(id + "_text .close").hide();
						
						$j(id).submit();
						$j(id +"_frame").load(function(){
							$j(id + "_text .loader").hide();
							up_output = $j(this).contents().find("#output").text();
							if (up_output == "success"){
								$j(id + "_text").css("background-color", "#F0F8FF");
								up_output = config.successOutput;
								//alert('dfjsdlfjsdflkj');
								getKepLista();
							}else{
								$j(id + "_text").css("background-color", "#FF0000");
								up_output = config.errorOutput;
							}
							up_output += '<br />' + $j(this).contents().find("#message").text();
							$j(id + "_text .status").html(up_output);
							$j(e).remove();
							$j(config.buttonClear).removeAttr("disabled");
						});
					}
				});
			}
		});
		
		$j(".close").live("click", function(){
			var id = "#" + $j(this).parent().attr("title");
			$j(id+"_frame").remove();
			$j(id).remove();
			$j(id+"_text").fadeOut("slow",function(){
				$j(this).remove();
			});
			return false;
		});
		
		$j(config.buttonClear).click(function(){
			$j("#px_display").fadeOut("slow",function(){
				$j("#px_display").html("");
				$j("#pxupload_form").html("");
				itr = 0;
				px.appendForm();
				$j('#px_button input').attr("disabled","disabled");
				$j(this).show();
			});
		});
		
		//private function
		var px = {
			init: function(e){
				var form = $j(e).parents('form');
				px.formAction = $j(form).attr('action');
				$j(form).before(' \
					<div id="pxupload_form"></div> \
					<div id="px_display"></div> \
					<div id="px_button"></div> \
				');
				$j(config.buttonUpload+','+config.buttonClear).appendTo('#px_button');
				if ( $j(e).attr('name') != '' ){
					config.inputName = $j(e).attr('name');
				}
				if ( $j(e).attr('size') != '' ){
					config.inputSize = $j(e).attr('size');
				}
				$j(form).hide();
				this.appendForm();
			},
			appendForm: function(){
				itr++;
				var formId = "pxupload" + itr;
				var iframeId = "pxupload" + itr + "_frame";
				var inputId = "pxupload" + itr + "_input";
				var contents = '<form method="post" id="'+ formId +'" action="'+ px.formAction +'" enctype="multipart/form-data" target="'+ iframeId +'">' +
				'<input type="file" name="'+ config.inputName +'" id="'+ inputId +'" class="pxupload" size="'+ config.inputSize +'" onchange="$j.fileUploader.change(this);" />' +
				'</form>' + 
				'<iframe id="'+ iframeId +'" name="'+ iframeId +'" src="about:blank" style="display:none"></iframe>';
				
				$j("#pxupload_form").append( contents );
			},
			validateFile: function(file) {
				if (file.indexOf('/') > -1){
					file = file.substring(file.lastIndexOf('/') + 1);
				}else if (file.indexOf('\\') > -1){
					file = file.substring(file.lastIndexOf('\\') + 1);
				}
				//var extensions = /(.jpg|.jpeg|.gif|.png)$j/i;
				var extensions = new RegExp(config.allowedExtension + '$j', 'i');
				if (extensions.test(file)){
					return file;
				} else {
					return -1;
				}
			}
		}
		
		px.init(this);
		
		return this;
	}
})(jQuery);