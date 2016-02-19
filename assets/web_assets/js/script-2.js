var saveClick = false; // to check save button clicked or not
$(document).ready(function(){
	$('#loader').hide();
	// set height of media container
	var winY = $(window).height();
	var x = winY-230;
	$(".lib-articles,#side-menu").height(x);
	x = winY-240;
	$(".jFiler-input-dragDrop").height(x);
	x = winY-110;
	$('#main-container').height(x);
	
	// function to add tags
	$(document).on('keypress', ".add-tag" , function(e) {
		var tag = $(this).val();
		if(e.which == 13 && tag != "" && validateInput(tag)){
			pretags = $(this).closest('.tag-wrapper').find('.all-tags').val();
			// get all module tags
			var temp = 1;
			var pretagsTemp = pretags.replace(/^,|,$/g,'');
			var res = pretagsTemp.split(",");
			for(j=0; j< res.length; j++){
				if(res[j] == tag){
					//already added
					temp = 0;
				}else{
					temp = 1;
				}
			}
			if(temp == 1){
				$(this).closest('.tag-wrapper').find('.tag-container').append('<span>'+tag+'<a href="#" class="remove-tag"><i class="fa fa-close"></i></a></span>');
				$(this).closest('.tag-wrapper').find('.all-tags').val(pretags+","+tag);
				$(this).val("");
			}else{
				alert("This tag is already added.");
			}
		}
	});
	// function to remove tags
	$(document).on('click', ".remove-tag" , function(e) {
		var tag = $(this).closest('span').text();
		pretags = $(this).closest('.tag-wrapper').find('.all-tags').val();
		pretags = pretags.replace(","+tag, "");
		$(this).closest('.tag-wrapper').find('.all-tags').val(pretags);
		$(this).closest('span').remove();
	});
	
	// remove untitled text
	$(document).on('focus', "#moduleTitle" , function(e) {
		if($(this).val() == "Untitled Module"){
			$(this).val("");
		}
	});
	// remove untitled text
	$(document).on('focus', "input[id^='subModuleTitle']" , function(e) {
		if($(this).val() == "Untitled Sub Module"){
			$(this).val("");
		}
	});
	
	// function to delete module
	$(document).on('click', ".delete-module" , function(e) {		
		var moduleId = $('#moduleId').val();
		if(typeof(moduleId) !== "undefined"){
			var moduleTitle = $('#moduleTitle').val();
			if (confirm("Are you sure that you want to delete this Module?") == true) {
				$.ajax({
					url: web_baseURL+"enterprise/course",
					type: 'POST',
					data: {"action":"delete_module","module_id":moduleId},
					success: function (result) {
						//alert("Successfully Deleted!");
						location.reload();
					},
					error:function(error){
						console.log(error);
					}
				});
			}
		}
	});
	
	// function to remove any element
	$(document).on('click', ".remove-me" , function(e) {
		if (confirm("Are you sure that you want to delete this Sub-module?") == true) {	
			$(this).closest('.panel').remove();
			// remove it from side bar
			var mid = $('#moduleTitle').attr('data-module');
			var sbid = $(this).attr('data-submodule');
			$('#moduleSubHead-'+mid+'-'+sbid).remove();
		}
	});
	
	// function to remove artifact
	$(document).on('click', ".remove-media" , function(e) {
		if (confirm("Are you sure that you want to remove this file?") == true) {
			var containerType = $(this).closest('.thumbnail').attr("data-type");
			var string = "";
			if(containerType == "video"){
				string = '<i class="fa fa-file-video-o"></i>';
				$(this).closest('.panel').find("input[id^='subModuleVideo']").val("");
			}
			else{
				string = '<i class="fa fa-file-pdf-o"></i>';
				$(this).closest('.panel').find("input[id^='subModulePdf']").val("");
			}
			$(this).closest('.thumbnail').html(string);
		}
	});
	
	// function to delete artifact
	$(document).on('click', ".delete-media" , function(e) {
		if (confirm("Are you sure that you want to delete this file?") == true) {
			$('#loader').show();
			var artifactId = $(this).attr('data-artifact');
			$this = $(this);
			$.ajax({
				url: web_baseURL+"enterprise/artifacts",
				type: 'POST',
				data: {"action":"delete","media_id":artifactId},
				success: function (result) {
					result = JSON.parse(result);
					if(result.response.status == "1"){
						$this.closest('.media-elm').remove();
						$('#loader').hide();
					}
					else{
						alert("Something went wrong! Please try again.");
					}
				},
				error:function(error){
					console.log(error);
				}
			});
		}
	});
	
	// click event on upload button
	$(document).on('click', ".upload-btn" , function(e) {
		$('#dropzone').show();
		$('#mediaContainer').hide();
	});
	
	// click event on list-tile-btn button
	$(document).on('click', ".list-tile-btn" , function(e) {
		$('#no-data, #dropzone').hide();
		$('#mediaContainer').show();
		$('.media-elm').show();
		/*
		var cls = $(this).find('i').attr("class");
		if(cls == "fa fa-th-large"){
			$(this).find('i').attr("class","fa fa-list-ul");
			$('#listView').hide();
			$('#gridView').show();
		}
		else{
			$(this).find('i').attr("class","fa fa-th-large");
			$('#listView').show();
			$('#gridView').hide();
		}
		*/
	});
	
	// filter by pdf
	$(document).on('click', ".filter-pdf" , function(e) {
		$("#dropzone").hide();
		$('#mediaContainer').show();
		var i = 0;
		$("#mediaContainer .item").each(function(){
			var type = $(this).attr('data-type').toLowerCase();
			if(type == "pdf"){
				$(this).closest('.media-elm').show();
				i++;
			}else{
				$(this).closest('.media-elm').hide();
			}
		});
		(i == 0)?$("#no-data").show():$("#no-data").hide();
	});
	
	// filter by video
	$(document).on('click', ".filter-video" , function(e) {
		$('#dropzone').hide();
		$('#mediaContainer, .media-elm').show();
		var i = 0;
		$("#mediaContainer .item").each(function(){
			var type = $(this).attr('data-type').toLowerCase();
			if(type == "video"){
				$(this).closest('.media-elm').show();
				i++;
			}else{
				$(this).closest('.media-elm').hide();
			}
		});
		(i == 0)?$("#no-data").show():$("#no-data").hide();
	});
	
	// filter by all
	$(document).on('click', ".filter-all" , function(e) {
		$('#dropzone').hide();
		$('#mediaContainer, .media-elm').show();
		var i = 0;
		$("#mediaContainer .item").each(function(){
			i++;
		});
		(i == 0)?$("#no-data").show():$("#no-data").hide();
	});
	
	
	// count no. of modules
	var m_count = $('#moduleCount').val();
	//function to add module
	$(document).on('click', ".add-mod" , function(e) {
		//save current module progress
		var x;
		if(m_count > 0){
			x = $saveProgress();
		}
		else{
			x = true;
		}
		if(x == true){
			$('#loader').show();
			// function to create module instance
			var moduleId = "-404";
			var moduleTitle = "Untitled Module";
			if(moduleTitle != ""){
				$.ajax({
					url: web_baseURL+"enterprise/course",
					type: 'POST',
					data: {"action":"course","moduleId":moduleId,"moduleTitle":moduleTitle},
					success: function (result) {
						result = JSON.parse(result);
						moduleId = result.response.data[0].id;
						
						// add to side bar
						$('#no-module').hide();
						$('#side-menu li').removeClass("active");
						$('#side-menu').append('<li class="active"><a href="#" class="loadModule" id="moduleHead-'+m_count+'" data-js-module="'+m_count+'" data-module="'+moduleId+'"><i class="fa fa-folder-open fa-fw"></i><span class="_first">Untitled Module</span><span class="fa arrow"></span></a>\
						<ul id="moduleUL-'+m_count+'" class="nav nav-second-level">\
						</ul>\
						</li>');
						
						//add to main creator
						$('#main-container').html('<div id="add-new-module" class="panel-body center-pan">\
						  <div class="row pad-zero">\
							<div class="page-header">\
								<input type="text" class="form-control" data-module="'+m_count+'" id="moduleTitle" placeholder="Untitled Module">\
								<input type="hidden" value="'+moduleId+'" id="moduleId">\
								<input type="hidden" value="0" id="subModuleCount">\
							</div>\
						  </div>\
						  <div class="row mod-tag-description">\
							<div class="form-inline">\
							  <div class="form-group">\
								<label for="moduleDescription">Description</label>\
								<input type="text" class="form-control" id="moduleDescription" placeholder="Enter Module Description">\
							  </div>\
							</div>\
						  </div>\
						  <div class="row mod-tag-description tag-wrapper" style="display:none;">\
							<div class="form-inline">\
							  <div class="form-group">\
								<label for="moduleTag">Tag</label>\
								<input type="text" class="form-control add-tag" placeholder="Enter Module Tag">\
								<input type="hidden" id="moduleTag" class="form-control all-tags">\
							  </div>\
							  <div class="tag tag-container">\
							  </div>\
							</div>\
						  </div>\
						  <div class="seprator-dashed"></div>\
						  <!-- subModule container -->\
						  <div class="panel-group" id="accordion">\
						  </div>\
						  <!-- subModule container closed -->\
						  <button type="button" class="btn btn-outline btn-default add-sub-mod"><i class="fa fa-plus-circle"></i> Add new sub module</button>\
						  <div class="seprator-dashed">\
						  </div>\
						  <button id="save-progress" class="btn btn-primary btn-lg btn-save" type="submit">Save</button>\
						</div>\
						<!-- /.panel-body -->');
						$('#loader').hide();
					},
					error:function(error){
						console.log(error);
					}
				});
			}
			m_count++;
		}
	});
	
	// function to add submodules
	$(document).on('click', ".add-sub-mod" , function(e) {
		var count = $("#subModuleCount").val(); // var for count no. of submodule
		// add to side bar
		var mid = $('#moduleTitle').attr('data-module');
		$('#moduleUL-'+mid).append('<li><a href="#" id="moduleSubHead-'+mid+'-'+count+'"><i class="fa fa-file-text"></i><span class="_first">Untitled Sub Module</span></a> </li>');
		
		// add to main creator
		$("#accordion").append('<div class="panel panel-default" data-module="'+count+'">\
				<div class="panel-heading row pad-zero">\
					<div class="sub-mod-title col-md-10">\
						<input type="text" class="form-control panel-title" data-submodule="'+count+'" id="subModuleTitle-'+count+'" placeholder="Untitled Sub module">\
					</div>\
					<div class="col-md-2 text-right">\
						<button type="button" class="btn btn-info btn-circle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne-'+count+'" aria-expanded="false"><i class="fa fa-chevron-down"></i></button>\
						<button type="button" class="remove-me btn btn-danger btn-circle" data-submodule="'+count+'"><i class="fa fa-times"></i></button>\
					</div>\
				</div>\
				<div id="collapseOne-'+count+'" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">\
					<div class="panel-body">\
						<div class="row mod-tag-description">\
							<div class="form-inline">\
							  <div class="form-group">\
								<label for="subModuleDescription-'+count+'">Description</label>\
								<input type="text" class="form-control" id="subModuleDescription-'+count+'" placeholder="Enter Sub Module Description">\
							  </div>\
							</div>\
						  </div>\
						<div class="row mod-tag-description tag-wrapper">\
							<div class="form-inline">\
							  <div class="form-group">\
								<label for="subModuleTag-'+count+'">Tag</label>\
								<input type="text" class="form-control add-tag" placeholder="Enter Sub Module Tag">\
								<input type="hidden" id="subModuleTag-'+count+'" class="form-control all-tags">\
							  </div>\
							  <div class="tag tag-container">\
							  </div>\
							</div>\
						  </div>\
						  <div class="seprator-dashed"></div>\
						  <div class="row mod-tag-description">\
							<div class="form-inline">\
							  <label for="subModuleArtifacts-'+count+'" class="col-md-2 pad-zero">Media</label>\
							  <div class="col-md-10">\
								<!--<div class="thumbnail drag-placeholder gallery"> <img src="dist/img/lib-thumb1.jpg" alt="..."></div>\
								<div class="thumbnail drag-placeholder gallery"> <img src="dist/img/lib-thumb3.jpg" alt="..."></div>-->\
								<div class="thumbnail drag-placeholder gallery" data-type="video"> <i class="fa fa-file-video-o"></i></div>\
								<div class="thumbnail drag-placeholder gallery" data-type="pdf"> <i class="fa fa-file-pdf-o"></i></div>\
							  </div>\
							  <input type="hidden" name="video-'+count+'" id="subModuleVideo-'+count+'">\
							  <input type="hidden" name="pdf-'+count+'" id="subModulePdf-'+count+'">\
							</div>\
						  </div>\
					</div>\
					<div class="panel-footer text-center"><i class="fa fa-object-ungroup"></i> Drop files from the Media Pane to attach them to this lesson</div>\
				</div>\
			</div>');
			
			//make droppable to dynamically added gallery items 
			$('.gallery').droppable({
				accept: "#mediaContainer .item",
				activeClass: "ui-state-highlight",
				drop: function( event, ui ) {
					dropNow( event, ui, this);
				}
			});
			// increment one more submodule
			count++;
			$("#subModuleCount").val(count);
	});
	
	// drag and drop element
	$('.item').draggable({
		cancel: "a.ui-icon", // clicking an icon won't initiate dragging
		//revert: true, // bounce back when dropped
		helper: "clone", // create "copy" with original properties, but not a true clone
		cursor: "move",
		stack: ".item",
		appendTo: 'body',
		containment: 'window',
		scroll: false,
	});
	
	//make droppable to gallery items 
	$('.gallery').droppable({
		accept: "#mediaContainer .item",
		activeClass: "ui-state-highlight",
		drop: function( event, ui ) {
			dropNow( event, ui, this);
		}
	});
	
	// function to save module progress
	$(document).on('click', "#save-progress" , function(e) {
		saveClick = true;
		$saveProgress();
	});
	
	// function to validate tags
	$(document).on('keyup', '.add-tag' , function(e) {
		var txt = $(this).val();
		if(!validateInput(txt) && txt !=""){
			$(this).css({"background-color":"#FFE6E6"});
		}else{
			$(this).css({"background-color":"#F8F8F8"});
		}
	});
	
	// function to change module name on side bar
	$(document).on('keyup', "#moduleTitle" , function(e) {
		var txt = $(this).val();
		var mid = $('#moduleTitle').attr("data-module");
		if(txt !=""){
			$('#moduleHead-'+mid+' ._first').html(txt);
		}else if(txt == ""){
			$('#moduleHead-'+mid+' ._first').html("Untitled Module");
		}
	});
	
	// function to change sub module name on side bar
	$(document).on('keyup', "input[id^='subModuleTitle']" , function(e) {
		var txt = $(this).val();
		var mid = $('#moduleTitle').attr("data-module");
		var sbid = $(this).attr("data-submodule");
		if(txt != ""){
			$('#moduleSubHead-'+mid+'-'+sbid+' ._first').html(txt);
		}else if(txt == ""){
			$('#moduleSubHead-'+mid+'-'+sbid+' ._first').html("Untitled Submodule");
		}
	});
	
	// function load module by clicking side bar
	$(document).on('click', ".loadModule" , function(e) {
		
		
		$('#side-menu li').removeClass("active");
		$(this).closest('li').addClass("active");
		//save current module progress
		var x = $saveProgress();
		if(x == true){
			$('#loader').show();
			var moduleId = $(this).attr("data-module");
			var m_count = $(this).attr("data-js-module");
			if(moduleId != ""){
				// load data from server
				$.ajax({
					url: web_baseURL+"enterprise/course",
					type: 'POST',
					data: {"action":"get_module","module_id":moduleId},
					success: function (result) {
						result = JSON.parse(result);
						//console.log(result);
						if(result.response.status == "1"){
							//add to main creator
							moduleTitle = result.response.detail.title;
							moduleDescription = result.response.detail.description;
							moduleTag = result.response.detail.tags;
							subModules = result.response.detail.courses;
							subModuleCount = subModules.length;
							string = '<div id="add-new-module" class="panel-body center-pan">\
							  <div class="row pad-zero">\
								<div class="page-header">\
									<input type="text" class="form-control" data-module="'+m_count+'" id="moduleTitle" value="'+moduleTitle+'" placeholder="Untitled Module">\
									<input type="hidden" value="'+moduleId+'" id="moduleId">\
									<input type="hidden" value="'+subModuleCount+'" id="subModuleCount">\
								</div>\
							  </div>\
							  <div class="row mod-tag-description">\
								<div class="form-inline">\
								  <div class="form-group">\
									<label for="moduleDescription">Description</label>\
									<input type="text" class="form-control" id="moduleDescription" value="'+moduleDescription+'" placeholder="Enter Module Description">\
								  </div>\
								</div>\
							  </div>\
							  <div class="row mod-tag-description tag-wrapper" style="display:none;">\
								<div class="form-inline">\
								  <div class="form-group">\
									<label for="moduleTag">Tag</label>\
									<input type="text" class="form-control add-tag" placeholder="Enter Module Tag">\
									<input type="hidden" value="'+moduleTag+'" id="moduleTag" class="form-control all-tags">\
								  </div>\
								  <div class="tag tag-container">';
							// get all module tags
							moduleTag = moduleTag.replace(/^,|,$/g,'');
							var res = moduleTag.split(",");
							for(j=0; j< res.length; j++){
								if(res[j] != ""){
									string += '<span>'+res[j]+'<a href="#" class="remove-tag"><i class="fa fa-close"></i></a></span>';
								}
							}
							
							string += '</div>\
								</div>\
							  </div>\
							  <div class="seprator-dashed"></div>\
							  <!-- subModule container -->\
							  <div class="panel-group" id="accordion">\
							  ';
							  
							for(var i = 0; i < subModuleCount; i++){
							//load sub module
							subModuleId = subModules[i].course.id;
							subModuleTitle = subModules[i].course.title;
							subModuleDescription = subModules[i].course.description;
							subModuleTag = subModules[i].course.tags;
							subModuleVideo = subModules[i].course.artifacts.video;
							subModulePdf = subModules[i].course.artifacts.pdf;
							string += '<div class="panel panel-default" data-module="'+i+'">\
								<div class="panel-heading row pad-zero">\
									<div class="sub-mod-title col-md-10">\
										<input type="text" class="form-control panel-title" data-submodule="'+i+'" id="subModuleTitle-'+i+'" value="'+subModuleTitle+'" placeholder="Untitled Sub module">\
									</div>\
									<div class="col-md-2 text-right">\
										<button type="button" class="btn btn-info btn-circle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne-'+i+'" aria-expanded="false"><i class="fa fa-chevron-down"></i></button>\
										<button type="button" class="remove-me btn btn-danger btn-circle" data-submodule="'+i+'"><i class="fa fa-times"></i></button>\
									</div>\
								</div>\
								<div id="collapseOne-'+i+'" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">\
									<div class="panel-body">\
										<div class="row mod-tag-description">\
											<div class="form-inline">\
											  <div class="form-group">\
												<label for="subModuleDescription-'+i+'">Description</label>\
												<input type="text" class="form-control" id="subModuleDescription-'+i+'" value="'+subModuleDescription+'" placeholder="Enter Sub Module Description">\
											  </div>\
											</div>\
										  </div>\
										<div class="row mod-tag-description tag-wrapper">\
											<div class="form-inline">\
											  <div class="form-group">\
												<label for="subModuleTag-'+i+'">Tag</label>\
												<input type="text" class="form-control add-tag" placeholder="Enter Sub Module Tag">\
												<input type="hidden" value="'+subModuleTag+'" id="subModuleTag-'+i+'" class="form-control all-tags">\
											  </div>\
											  <div class="tag tag-container">';
										
										// get all module tags
										
										subModuleTag = subModuleTag.replace(/^,|,$/g,'');
										var res = subModuleTag.split(",");
										for(j=0; j< res.length; j++){
											if(res[j] != ""){
												string += '<span>'+res[j]+'<a href="#" class="remove-tag"><i class="fa fa-close"></i></a></span>';
											}
										}
										
										string += '</div>\
											</div>\
										  </div>\
										  <div class="seprator-dashed"></div>\
										  <div class="row mod-tag-description">\
											<div class="form-inline">\
											  <label for="subModuleArtifacts-'+i+'" class="col-md-2 pad-zero">Media</label>\
											  <div class="col-md-10">';
											  
											if(subModuleVideo != ""){  
												string += '<div class="thumbnail drag-placeholder gallery" data-type="video">\
												<button type="button" class="remove-media btn btn-danger btn-circle"><i class="fa fa-times"></i></button>\
												<span class="item-added" data-type="pdf" data-artifact="'+subModuleVideo+'">\
												<img src="'+baseImage+'mp4.png"></span>\
												</div>';
											}
											else{
												string += '<div class="thumbnail drag-placeholder gallery" data-type="video"> <i class="fa fa-file-video-o"></i></div>';
											}
											if(subModulePdf != ""){
												string += '<div class="thumbnail drag-placeholder gallery" data-type="pdf">\
												<button type="button" class="remove-media btn btn-danger btn-circle"><i class="fa fa-times"></i></button>\
												<span class="item-added" data-type="pdf" data-artifact="'+subModulePdf+'">\
												<img src="'+baseImage+'pdf.png"></span>\
												</div>';
											}
											else{
												string += '<div class="thumbnail drag-placeholder gallery" data-type="pdf"> <i class="fa fa-file-pdf-o"></i></div>';
											}
												
									string += '</div>\
											  <input type="hidden" value="'+subModuleVideo+'" name="video-'+i+'" id="subModuleVideo-'+i+'">\
											  <input type="hidden" value="'+subModulePdf+'" name="pdf-'+i+'" id="subModulePdf-'+i+'">\
											</div>\
										  </div>\
									</div>\
									<div class="panel-footer text-center"><i class="fa fa-object-ungroup"></i> Drop files from the Media Pane to attach them to this lesson</div>\
								</div>\
							</div>';
							}
							// submodule closed
							string += '</div>\
							  <!-- subModule container closed -->\
							  <button type="button" class="btn btn-outline btn-default add-sub-mod"><i class="fa fa-plus-circle"></i> Add new sub module</button>\
							  <div class="seprator-dashed">\
							  </div>\
							  <button id="save-progress" class="btn btn-primary btn-lg btn-save" type="submit"><i class="fa fa-save"></i>Save Module</button>\
							  <button id="saved" class="btn btn-primary btn-lg  btn-save smod" type="submit"><i class="fa fa-check"></i>Module Saved</button>\
							</div>\
							<!-- /.panel-body -->';
							
							$('#main-container').html(string);
							
							//make droppable to dynamically added gallery items 
							$('.gallery').droppable({
								accept: "#mediaContainer .item",
								activeClass: "ui-state-highlight",
								drop: function( event, ui ) {
									dropNow( event, ui, this);
								}
							});
							$('#loader').hide();
						}
					},
					error:function(error){
						console.log(error);
					}
				});
			}
		}
	});
	
	// function to save module name
	$(document).on('blur', "#moduleTitle" , function(e) {
		var moduleId = $("#moduleId").val();
		var moduleTitle = $("#moduleTitle").val();
		if(moduleTitle != ""){
			$.ajax({
				url: web_baseURL+"enterprise/course",
				type: 'POST',
				data: {"action":"course","moduleId":moduleId,"moduleTitle":moduleTitle},
				success: function (result) {
					result = JSON.parse(result);
					$("#moduleId").val(result.response.data.id);
				},
				error:function(error){
					console.log(error);
				}
			});
		}
	});
	
	// function to add artifact metadata
	$(document).on('click', ".item" , function(e) {
		$('.file-pro-shell').show();
		artifactType = $(this).attr("data-type");
		artifactName = $(this).attr("data-name");
		artifactId = $(this).attr("data-artifact");
		createdDate = $(this).attr("data-time");
		size = $(this).attr("data-size");
		artifactTag = $(this).attr("data-tags");
		$('#artifactName').val(artifactName);
		$('#artifactId').val(artifactId);
		$('.file-pro-shell .createdDate').html(createdDate);
		$('.file-pro-shell .size').html(size);
		$('#artifactTag').val(artifactTag);
		// get all tags
		artifactTag = artifactTag.replace(/^,|,$/g,'');
		var res = artifactTag.split(",");
		string = "";
		for(j=0; j< res.length; j++){
			if(res[j] != ""){
				string += '<span>'+res[j]+'<a href="#" class="remove-tag"><i class="fa fa-close"></i></a></span>';
			}
		}
		$('.file-pro-shell .tag-container').html(string);
	});
	
	// auto save artifact metadata
	$(document).on('mouseleave', ".file-pro-shell" , function(e) {
		updateArtifact();
	});
	$(document).on('click', ".file-pro-shell .close" , function(e) {
		updateArtifact();
		$('.file-pro-shell').hide();
	});
	
	//search artifacts on the basis of tags and title
	// by pressing enter key
	$(document).on('keyup','.search', function(e) {
		searchArtifact();
	});
	// by clicking enter key
	$(document).on('click','.input-group-btn', function(e) {
		searchArtifact();
	});
	
	// function to show submodule on click
	$(document).on('click','a[id^="moduleSubHead"]', function(e) {
		var str = $(this).attr("id");
		var sbid = str.split("-");
		sbid = sbid[sbid.length-1];
		//show current child of accordion by default
		$('div[id^="collapseOne"]').removeClass("in");
		$('#collapseOne-'+sbid).collapse("show")
		$('#subModuleTitle-'+sbid).focus();
	});
	
	
	// function to get artifact details
	$(document).on('click', ".item-added" , function(e) {
		artifactId = $(this).attr("data-artifact");
		$.ajax({
		url: web_baseURL+"enterprise/artifacts",
		type: 'POST',
		data: {"action":"get_media", "media_id":artifactId},
		success: function (result) {
			//console.log(result);
			result = JSON.parse(result);
			$('.file-pro-shell').show();
			artifactType = result.response.detail.type;
			artifactName = result.response.detail.title;
			createdDate = result.response.detail.type.createdTime;
			size = result.response.detail.type.size;
			artifactTag = result.response.detail.type.tags;
			$('#artifactName').val(artifactName);
			$('#artifactId').val(artifactId);
			$('.file-pro-shell .createdDate').html(createdDate);
			$('.file-pro-shell .size').html(size);
			$('#artifactTag').val(artifactTag);
			// get all tags
			artifactTag = artifactTag.replace(/^,|,$/g,'');
			var res = artifactTag.split(",");
			string = "";
			for(j=0; j< res.length; j++){
				if(res[j] != ""){
					string += '<span>'+res[j]+'<a href="#" class="remove-tag"><i class="fa fa-close"></i></a></span>';
				}
			}
			$('.file-pro-shell .tag-container').html(string);
		},
		error:function(error){
			//console.log(error);
		}
	});
	});
	
});// jquery

// function save progress
function $saveProgress(){
	$('#loader').show();
	// make sub module count zero for next module or no.of submodules
	count = $("#subModuleCount").val();
	var div = document.getElementById('moduleForm');
	var form = document.createElement('form');
	form.setAttribute('action', '');
	form.setAttribute('id', 'newForm');
	form.setAttribute('method', 'POST');
	
	input = addInput("action","course");
	form.appendChild(input);
	
	var moduleId = $('#moduleId').val();
	var moduleTitle = $('#moduleTitle').val();
	var moduleDescription = $('#moduleDescription').val();
	var moduleTag = $('#moduleTag').val();
	
	input = addInput("moduleId",moduleId);
	form.appendChild(input);
	input = addInput("moduleTitle",moduleTitle);
	form.appendChild(input);
	input = addInput("moduleDescription",moduleDescription);
	form.appendChild(input);
	input = addInput("moduleTag",moduleTag);
	form.appendChild(input);
	input = addInput("lock",false);
	form.appendChild(input);
	input = addInput("disable",false);
	form.appendChild(input);
	
	var temp = 0;		
	var subModules = $("#accordion .panel");
	$.each(subModules, function( index, value ) {
		var subModuleTitle = ($(this).find('input[id^="subModuleTitle"]').val())?$(this).find('input[id^="subModuleTitle"]').val():"Untitled Sub Module";
		var subModuleDescription = $(this).find('input[id^="subModuleDescription"]').val();
		var subModuleTag = $(this).find('input[id^="subModuleTag"]').val();
		var subModuleVideo = $(this).find('input[id^="subModuleVideo"]').val();
		var subModulePdf = $(this).find('input[id^="subModulePdf"]').val();
		input = addInput("subModuleTitle-"+index,subModuleTitle);
		form.appendChild(input);
		input = addInput("subModuleDescription-"+index,subModuleDescription);
		form.appendChild(input);
		input = addInput("subModuleTag-"+index,subModuleTag);
		form.appendChild(input);
		input = addInput("subModuleVideo-"+index,subModuleVideo);
		form.appendChild(input);
		input = addInput("subModulePdf-"+index,subModulePdf);
		form.appendChild(input);
		temp++;
	});
	input = addInput("subModuleCount",temp);
	form.appendChild(input);
	
	$("#moduleForm").html(form);
	
	var succeed = false;
	if(moduleTitle != ""){
		//form.submit();
		$.ajax({
			url: web_baseURL+"enterprise/course",
			type: 'POST',
			async: false,
			data: $('#newForm').serialize(),
			success: function (result) {
				//alert("Successfully Created!");
				//location.reload();
				succeed = true ;
				setTimeout(function() {
					$('#loader').hide();
					if(saveClick){
						$('#saved').show();
						setTimeout(function() {
							$("#saved").fadeOut()
						}, 3000);
					}
				}, 2000);
			},
			error:function(error){
				//console.log(error);
				succeed = false ;
			}
		});
	}else{
		$('#loader').hide()
		alert("Add a module title to save.");
	}
	return succeed;
}
//$saveProgress ends here

//function to search artifacts
function searchArtifact(){
	var searchtext=$('.search').val();
	if(searchtext=='' || searchtext==null){
		//do nothing
	}else{
	$("#no-data").hide();
	var found=false;
	$("#mediaContainer .item").each(function(){
			var tags = $(this).attr('data-tags').toLowerCase();
			var title = $(this).attr('data-name').toLowerCase();
			current = tags+title;
			arr = searchtext.toLowerCase().trim().split(" ");
			for (i = 0; i < arr.length; i++) {
				if((current).indexOf(arr[i])>=0){
					temp = 1;
				}
				else{
					temp = 0;
					break;
				}
			}
			if(temp == 1){
				$(this).closest('.media-elm').show();
				found=true;
			}else{
				$(this).closest('.media-elm').hide();
			}
		});
		if(found==true){
				$("#no-data").hide();
			}else{
				$("#no-data").show();
			}
	}
}

function updateArtifact(){
	var artifactName = $('#artifactName').val();
	var artifactId = $('#artifactId').val();
	var artifactTag = $('#artifactTag').val();
	$.ajax({
		url: web_baseURL+"enterprise/artifacts",
		type: 'POST',
		data: {"action":"update", "media_id":artifactId, "title":artifactName, "tags":artifactTag},
		success: function (result) {
			$('#artifact-'+artifactId+' span').attr("data-name",artifactName);
			$('#artifact-'+artifactId+' span').attr("data-tags",artifactTag);
			artifactName = artifactName.substr(0,13);
			artifactName = artifactName+'...';
			$('#artifact-'+artifactId+' h5').html(artifactName);
			//console.log(result);
		},
		error:function(error){
			//console.log(error);
		}
	});
}

// function to add input type
function addInput(name,value){
var input = document.createElement('input');
input.setAttribute('type', 'text');
input.setAttribute('name', name);
input.setAttribute('value', value);
return input;
}
//function works after drop and validation
function dropNow( event, ui, $this){
	// clone item to retain in original "list"
	var $item = ui.draggable.clone();
	var current = $($this).closest('.panel').attr('data-module');
	var dataContainer = $($this).attr('data-type');
	var subModuleVideo = $($this).closest('.panel').find("input[id^='subModuleVideo']").val();
	var subModulePdf = $($this).closest('.panel').find("input[id^='subModulePdf']").val();
	
	var dataArtifact = $item.attr("data-artifact");
	var dataType = $item.attr("data-type");
	var dataName = $item.attr("data-name");
	
	var removebtn = '<button type="button" class="remove-media btn btn-danger btn-circle"><i class="fa fa-times"></i></button>';
	if(dataContainer == "video" && dataType == "video"){
		if(subModuleVideo != ""){
			if (confirm("Do you want to overwrite the existing video file?") == true) {
				$('#subModuleVideo-'+current).val(dataArtifact);
				$($this).html("");
				$($this).append(removebtn);
				$($this).append($item);
			}
		}else{
			$('#subModuleVideo-'+current).val(dataArtifact);
			$($this).html("");
			$($this).append(removebtn);
			$($this).append($item);
		}
	}else if(dataContainer == "pdf" && dataType == "pdf"){
		if(subModulePdf != ""){
			if (confirm("Do you want to overwrite the existing pdf file?") == true) {
				$('#subModulePdf-'+current).val(dataArtifact);
				$($this).html("");
				$($this).append(removebtn);
				$($this).append($item);
			}
		}else{
			$('#subModulePdf-'+current).val(dataArtifact);
			$($this).html("");
			$($this).append(removebtn);
			$($this).append($item);
		}
	}else{
		alert("Please drag "+dataContainer+" file only.");
	}
}

// function validate all input type
function validateInput(txt) {
    var re = /^[a-zA-Z0-9\-\_\ ]+$/;
    return re.test(txt);
}