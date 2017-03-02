(function($)
{
    $.fn.inputField = function(handler, cancel){
    	
    	$(this).on("keydown",function(event){ 
    		
    		//enter or escape
    		if(event.which == 13) {
    			
    			event.preventDefault();
    			event.stopPropagation();

    			$(this).blur();
    			
    		}
    		else if((event.which == 27))
    		{
    			event.preventDefault();
    			event.stopPropagation();
    			
    			cancel();
    		}
    			
    	});
    	
    	$(this).on("blur", handler);
    	
    	return this;
    };
    
})(jQuery);


mediaManager = function(config){
		
		var mouseX = 0;
		var mouseY = 0;
		var dir = config.dir;
		var contextMain = {'newfolder':{'label': config.lang.newFolder }};
		var opener = config.opener;
		var uploader;
		var type = config.dir;
		
		/* define functions */ 
				
		function showContext(context)
		{
					
				hideContext();
				
				$("#context").fadeOut('fast',function(){$(this).remove()});
				
				$menu = $("<div>").attr("id","context").addClass('dropdown-menu');
						
				for(var key in context)
				{
					$item = $('<a href="#" />').attr("data-action",key).html(context[key]["label"]);
					$item.on("click", handleContextClick);
					
					$menu.append($('<li>').append($item));
				}
				
				$("#mediaManagerContent").append($menu);
			
				y = mouseY - $('#mediaManagerContent').offset().top;
				x = mouseX - $('#mediaManagerContent').offset().left;
				
				$menu.css("top", y + 10).css("left", x + 10).fadeIn("fast");
		}
		

		function refresh()
		{
			loadDir(dir);
		}
					
		function hideTip($el)
		{
			
			$el.stop("tip", true, true);
			
			$(".tip").fadeOut("fast",function(){
						$(this).remove();
					});
		}
			
		function hideContext()
		{
			$("#context").fadeOut("fast",function(){$(this).remove();});
		}
					
		function handleContextClick(event)
		{
			event.preventDefault();
			event.stopPropagation();
					
			switch( $(this).attr("data-action") )
			{
					
				case "rename":
					if($('#files .item[data-checked=checked]').length>0)
						renameFile( $('#files .item[data-checked=checked]') );
				break;
					
				case "delete":
					deleteFile();
				break;
					
				case "newfolder":
					createFolder();
				break;
					
			}
			
			hideContext();
			
		}			
		
		function deleteFile()
		{
			
			$checked = $('#files .item[data-checked=checked]');
			
			if($checked.length==0)
				return;
			else if($checked.length == 1)
				msg = config.lang.confirm_delete.replace("__filename__",$checked.attr('data-name'));
			else
				msg = config.lang.confirm_deleteN;
			
			if(confirm( msg ))
			{	
				files = [];
				
				$checked.each(function()
				{
					files.push($(this).attr('data-path'));
				});
				
				jQuery.ajax({
								'url': config.deleteFileUrl,
								'data': {files : JSON.stringify(files) },
								'success':function(json){
										
										$checked.each(function()
										{
											$(this).remove();
										});
								},
								'error':function(xhr, status){
									alert(status);
								},
								'cache':false
							});
				
			}
			
		}

		
					
		function createFolder()
		{
					
			
			folder = $('<div class="col-xs-4 col-sm-3 col-lg-2 item folder selectable"><div class="item-container"><div class="thumb"><span class="glyphicon glyphicon-folder-open"></span></div><div class="selector"><span class="tick glyphicon glyphicon-unchecked"></span></div></div></div>');

			$('.item-container',folder).append($('<div class="icon"><span class="glyphicon glyphicon-folder-open"></span>&nbsp;</div>'));
			
			field = $('<textarea>');
			
			$('.item-container',folder).append(field);
			
			$("#files").prepend(folder);
			
			field.focus();

			field.inputField(function() {

					name = $(this).val();
					
					if(name == '')
					{
						folder.remove();
						return;
					}
			
					jQuery.ajax({
						
									'url': config.createFolderUrl,
									'data': 'dir='+dir+'&name='+name,
									'dataType': 'json',
									'success': function(json){
				
												if(json.success)
												{
													
													if(name.length > 30)
													{
														short_name = name.substr(0,27) + "&hellip;";
													}
													else
														short_name = name;
													
													$('.item-container',folder).append($("<div>").addClass("name").html(short_name));
													$('.item-container',folder).append($("<div>").addClass("name_full").html(name));
													
													folder.attr("data-name", name).attr("data-path",dir+"/"+name);
													field.remove();
												}
												else
												{
													alert(json.message);
													field.focus();
												}
				
										},
										'error': function(xhr, status){
														folder.remove();
														alert(status);
										},
										'cache':false
						});
			},function(){
				folder.remove();
			});
			
		}
					
		function renameFile(file)
		{
			
			oldname = file.attr("data-name");
			
			pos = oldname.lastIndexOf(".");
			
			if( pos != -1 )
			{
				oldnameNoExt = oldname.substr(0,pos);
				ext = oldname.substr(pos);
			}
			else
			{
				oldnameNoExt = oldname;
				ext = '';
			}
			
			nameField = file.find(".name");
			nameFieldFull = file.find(".name_full");
			
			field = $('<textarea>');
			
			nameFieldFull.after(field);
			
			nameFieldFull.css('display', 'none');
			nameField.css('display', 'none');
			
			field.val(oldnameNoExt).inputField(function(){

					newname = $(this).val()+ext;
					
					if(newname != oldname)
					{
					
						jQuery.ajax({
									'url': config.renameFileUrl,
									'data': 'dir='+dir+'&oldname='+encodeURI(oldname)+'&newname='+encodeURI(newname),
									'dataType': 'json',
									'success': function(json){ 
				
											if(json.success)
											{
												file.attr("data-name",newname);
												file.attr("data-path",dir+'/'+newname);
									
												nameFieldFull.html(newname);
												
												if(newname.length > 30)
												{
													newname = newname.substr(0,27) + "&hellip;";
												}
												
												nameField.html(newname);
												
												field.remove();
												
												nameFieldFull.css('display', 'auto');
												nameField.css('display', 'auto');
												
											}
											else
											{
												alert(json.message);
												field.focus();
											}


									},
									'error': function(xhr, status){ 
														field.remove();
														nameField.show();
														alert(status); 
										}
								
								});
								
					
					}
					else
					{
						field.remove();
						nameFieldFull.css('display', 'auto');
						nameField.css('display', 'auto');
					}
					
			}, function(){
				
				field.remove();
				nameFieldFull.css('display', 'auto');
				nameField.css('display', 'auto');
				
			}).show().focus().select();
			
			
		}
					
		
						
		function loadDir(path)
		{
					
			$("#mediaManagerContent .loader").fadeIn('fast');
					
			jQuery.ajax({
					'url': config.urlLoadFiles,
					'data': 'dir='+path,
					'type': 'GET',
					'dataType': 'html',
					'success': function(html){
					
								dir = path;
								
								uploader.settings.multipart_params = {"dir": dir};
								
								
								checked = [];
								$("#files").html(html);
								$("#mediaManagerContent .loader").fadeOut('fast');
								
						},
					'error': function(xhr, status){ 
									alert(status); $("#mediaManagerContent .loader").fadeOut('fast');
									},
					}
			);
				
		}
						
		

		function selectItems(items)
		{
			
			if(config.opener.name == null)
				return;
			
			
			if(items.length == 0)
				return;
			
			paths = [];
			
			items.each(function(){
				//TODO: include baseUrl!
				paths.push(encodeURI(config.baseUrl+'/medias/' + items.attr('data-path')));
			});
		
			if(config.opener.name == "mediaOpener" && window.mediaManagerCallback)
			{
				if(config.multi)
					window.mediaManagerCallback.callbackMulti(paths[0]);
				else
					window.mediaManagerCallback.callback(paths[0]);
			}
			else if(config.opener.name == "ckeditor" && CKEDITOR)
			{
				CKEDITOR.tools.callFunction(config.opener.CKEditorFuncNum, paths[0], "");
			}
			else if(config.opener.name == "tinymce" && tinyMCE)
			{
			     $(document).find('#' + field).val(paths[0]);
			     
			     fieldElm =  document
					.getElementById(field);
			     
			     if ("createEvent" in document) {
						var evt = document
								.createEvent("HTMLEvents");
						evt
								.initEvent(
										"change",
										false,
										true);
						fieldElm
								.dispatchEvent(evt)
					} else {
						fieldElm
								.fireEvent("onchange")
					}
			}
			
			$('#mediaManagerModal').modal('hide');
		}
		
		this.changeType = function(ntype)
		{
			if(config.types[ntype])
			{
				config.type = ntype;
				if(config.types[ntype].ext != null)
				{
					exts = config.types[ntype].ext.join("|\\\.");
					regexp = new RegExp("(\\\."+exts+")$","i");
					uploader.settings.filters.ext = regexp;
				}
				else
				{
					uploader.settings.filters.ext = null;
				}
				type = ntype;
				loadDir(ntype);
			}
		}
		
		this.init = function()
		{
			
				
				uploadPopup = $("#upload");
				overlay = $(".overlay");
				
				// configure uploader

				exts = config.types[config.type].ext.join("|\\\.");
				regexp = new RegExp("(\\\."+exts+")$","i");
				
				uploader = new plupload.Uploader({
	
					browse_button : "pickfiles",
					runtimes: "html5",													
					url: config.uploadUrl,
					multipart_params : {"dir": dir},
					drop_element: "files",
					filters: {
						max_file_size: config.maxFileSize,
						ext: regexp,
					
					},
					init : {
						 Error: function(up, args) {
				                // Called when error occurs
							 
				                if(args.code == '-600')
				                	alert(config.lang.file_size_error.replace('{name}',args.file.name).replace('{maxsize}', config.maxFileSize));
								else if(args.code == '-601')
								{
									pos = args.file.name.lastIndexOf(".");
									
									if( pos != -1 )
									{
										ext = args.file.name.substr(pos);
									}
									else
									{
										ext = '';
									}
									
									alert(config.lang.ext_error.replace('{ext}',ext).replace('{allowed}', config.types[type].ext.join(", ")));
								}
				            }
					}
				});


				uploader.init();
				
				uploader.bind("FilesAdded", function(up, files) {
				
					
						uploadPopup.find(".progress .progress-bar").css('width', '0%');
							
							overlay.fadeIn("fast");
							uploadPopup.find(".closebox").hide();
							uploadPopup.fadeIn("fast",function(){
								uploader.start();
							});
						  
				});
			 
				uploader.bind("UploadProgress", function(up, file) {
			
					uploadPopup.find(".verbose").html(file.name);
					uploadPopup.find(".progress .progress-bar").css('width', up.total.percent+'%');
					uploadPopup.find(".progress span").html(up.total.percent+"%" + " (" + up.total.uploaded + "/" + this.files.length + ")");
									
				});
																
				
				plupload.addFileFilter('ext', function(regexp, file, cb) {
			        		
					if (regexp && !regexp.test(file.name)) {
				        this.trigger('Error', {
				                code : plupload.FILE_EXTENSION_ERROR,
				                message : plupload.translate('File extension error.'),
				                file : file
				        });
				        cb(false);
				} else {
				        cb(true);
				}
					
				});
										
				uploader.bind("UploadComplete", function(up, files){
					uploadPopup.find(".verbose").html("");
					uploader.splice();
					uploader.refresh();
					uploadPopup.find(".closebox").fadeIn("fast");
					refresh();
					overlay.fadeOut("fast",function(){$(this).remove()});
					uploadPopup.fadeOut("fast");
					
				});
				
			
				
				uploadPopup.find(".closebox").on("click",function(){
				
					overlay.fadeOut("fast",function(){$(this).remove()});
					uploadPopup.fadeOut("fast");
				});
			
				
				/* bind events */
				
				$("#mediaManagerContent")
				.on("mousemove",function(e){
					
					mouseX = e.pageX;
					mouseY = e.pageY;
					
				})
				.on("selectstart", function(evt){ evt.preventDefault(); return false; })
				
				.on("click",function(){ hideContext(); })
				
				.on("contextmenu",function(){return false;})
				
				.on("contextmenu", "#files",function(event){
					
					event.stopPropagation();
					showContext(contextMain);
					return false;
				});
				
				
				$("#select", "#mediaManagerModal").on("click",function(event){
						selectItems($('#files .item[data-checked=checked]'));		
	
					});

				$("#options #refresh").on("click",refresh);
				$("#options #addFolder").on("click",createFolder);
				$("#options #list").on("click", function(){$(this).addClass('active'); $("#options #grid").removeClass('active'); $('#files').removeClass('grid').addClass('list');} );
				$("#options #grid").on("click", function(){ $(this).addClass('active'); $("#options #list").removeClass('active'); $('#files').removeClass('list').addClass('grid');} );
				
				
				
				$("#files")
					.on("dragstart",".item",function(event){
						event.preventDefault();
					})
					//double click on folder, open it
					.on("click",".item.folder",function(event){
	
						event.stopPropagation();
										
						loadDir($(this).attr("data-path"));			
	
					})
					//double click on file, return it's url
					.on("dblclick", ".item.file", function(){
		
						selectItems($(this));
							
					})
					//right click on an item, show context menu
					.on("contextmenu", ".item.selectable",function(event){
			
							event.stopPropagation();
							
							if($(this).attr('data-checked') == undefined)
							{
								$('#files .item').trigger('mm.unselect');
								$(this).trigger('mm.select');
							}
						
							contextFile = {'rename':{'label': config.lang.rename },'delete':{'label': config.lang.del }};

							if($('#files .item[data-checked=checked]').length>1)
								delete contextFile.rename;
							
							showContext(contextFile);
							return false;
							
					})
					.on("click",".item.file.selectable, .item.folder.selectable .selector", function(e){

						e.stopPropagation();
						
						if($(this).hasClass('selector'))
							$el = $(this).parents('.item');
						else
							$el = $(this);
						
						if($el.attr("data-checked") == 'checked')
						{
							$el.trigger('mm.unselect');
						}
						else
						{
							$el.trigger('mm.select');
						}
						
						hideContext();
						
					}).
					on("mm.select", ".item", function(){

						if($(this).hasClass('selectable'))
						{
							$(this).attr("data-checked","checked");
							$('.selector span', this).removeClass('glyphicon-unchecked').addClass('glyphicon-check');
							$('.item-container', this).addClass('bg-info');
							$("#mediaManagerModal #select").prop('disabled', $('#files .item.file[data-checked=checked]').length === 0 || ($('#files .item[data-checked=checked]').length > 1 && config.multi == false ));
						}
						
					}).
					on("mm.unselect", ".item", function(){
						
						if($(this).hasClass('selectable'))
						{
							$(this).removeAttr("data-checked");
							$('.selector span', this).removeClass('glyphicon-check').addClass('glyphicon-unchecked');
							$('.item-container', this).removeClass('bg-info');
							$("#mediaManagerModal #select").prop('disabled', $('#files .item.file[data-checked=checked]').length === 0 || ($('#files .item[data-checked=checked]').length > 1 && config.multi == false ));
						}
						
					});

				
				
			
			return this;
			
		};
		
}; 
