$(document).ready(function() {
	var failedFiles = "";
    $("#filer_input").filer({
        limit: null,
        maxSize: null,
        extensions: null,
        changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><h3 style="margin-top:120px;"></h3><p>Drag pdf or mp4 files from your computer and drop here.</p><div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><span style="display:inline-block; margin-bottom: 20px;">or</span></div><a class="jFiler-input-choose-btn blue">Browse Files</a></div></div>',
        showThumbs: true,
        theme: "dragdropbox",
		appendTo: "#filerContainer",
        templates: {
            box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
            item: '<li class="jFiler-item">\
                        <div class="jFiler-item-container">\
                            <div class="jFiler-item-inner">\
                                <div class="jFiler-item-thumb">\
                                    <div class="jFiler-item-status"></div>\
                                    <div class="jFiler-item-info">\
                                        <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                        <span class="jFiler-item-others">{{fi-size2}}</span>\
                                    </div>\
                                    {{fi-image}}\
                                </div>\
                                <div class="jFiler-item-assets jFiler-row">\
                                    <ul class="list-inline pull-left">\
                                        <li>{{fi-progressBar}}</li>\
                                    </ul>\
                                    <ul class="list-inline pull-right">\
                                        <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                    </ul>\
                                </div>\
                            </div>\
                        </div>\
                    </li>',
            itemAppend: '<li class="jFiler-item">\
                            <div class="jFiler-item-container">\
                                <div class="jFiler-item-inner">\
                                    <div class="jFiler-item-thumb">\
                                        <div class="jFiler-item-status"></div>\
                                        <div class="jFiler-item-info">\
                                            <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                            <span class="jFiler-item-others">{{fi-size2}}</span>\
                                        </div>\
                                        {{fi-image}}\
                                    </div>\
                                    <div class="jFiler-item-assets jFiler-row">\
                                        <ul class="list-inline pull-left">\
                                            <li><span class="jFiler-item-others">{{fi-icon}}</span></li>\
                                        </ul>\
                                        <ul class="list-inline pull-right">\
                                            <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                        </ul>\
                                    </div>\
                                </div>\
                            </div>\
                        </li>',
            progressBar: '<div class="bar"></div>',
            itemAppendToEnd: false,
            removeConfirmation: true,
            _selectors: {
                list: '.jFiler-items-list',
                item: '.jFiler-item',
                progressBar: '.bar',
                remove: '.jFiler-item-trash-action'
            }
        },
        dragDrop: {
            dragEnter: null,
            dragLeave: null,
            drop: null,
        },
        uploadFile: {
            url: web_baseURL+"enterprise/artifacts",
            data: {"action":"add"},
            type: 'POST',
            enctype: 'multipart/form-data',
            beforeSend: function(){},
            success: function(data, el){
				//console.log(data);
				result = JSON.parse(data);
				artifactId = result.response.detail.id;
				artifactName = result.response.detail.title;
				artifactTemp = artifactName.substr(0,13);
				artifactTemp = artifactTemp+'...';
				artifactType = result.response.detail.type;
				artifactUploadTime = result.response.detail.createdTime;
				artifactSize = result.response.detail.size;	
				artifactTags = result.response.detail.tags;
				artifactIcon = (artifactType == "pdf")?"pdf.png":"mp4.png";
				if(result.response.status == "1"){
					$('#gridView').append('<div class="media-elm col-sm-6 col-md-3" title="'+artifactName+'">\
					<button type="button" class="delete-media btn-delete btn btn-danger btn-circle" data-artifact="'+artifactId+'"><i class="fa fa-times"></i></button>\
					  <div class="thumbnail text-center">\
						<span class="item" data-type="'+artifactType+'" data-artifact="'+artifactId+'" data-name="'+artifactName+'" data-size="'+artifactSize+'" data-time="'+artifactUploadTime+'" data-tags="'+artifactTags+'"><img src="'+baseImage+artifactIcon+'" /></span>\
						<div class="caption">\
						  <h5>'+artifactTemp+'</h5>\
						  <!--<p>'+artifactUploadTime+' , '+artifactSize+'</p>-->\
						</div>\
					  </div>\
					</div>');
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
				}else{
					alert("Failed to upload - "+artifactName);
					failedFiles += artifactName+"\n";
				}
            },
            error: function(el){
                // file not uploaded
            },
            statusCode: null,
            onProgress: function(){
				// show loader
				$('#media-loader').show();
			},
            onComplete: function(){
				$('#gridView, #mediaContainer').show();
				$('#dropzone, #media-loader, #no-data').hide();
			}
        },
        files: null,
        addMore: false,
        clipBoardPaste: true,
        excludeName: null,
        beforeRender: null,
        afterRender: null,
        beforeShow: null,
        beforeSelect: null,
        onSelect: null,
        onRemove: function(itemEl, file, id, listEl, boxEl, newInputEl, inputEl){
            //var file = file.name;
            //$.post('./php/remove_file.php', {file: file});
        },
        onEmpty: null,
        options: null,
        captions: {
            button: "Choose Files",
            feedback: "Choose files To Upload",
            feedback2: "files were chosen",
            drop: "Drop file here to Upload",
            removeConfirmation: "Are you sure you want to remove this file?",
            errors: {
                filesLimit: "Only {{fi-limit}} files are allowed to be uploaded.",
                filesType: "Only Images are allowed to be uploaded.",
                filesSize: "{{fi-name}} is too large! Please upload file up to {{fi-maxSize}} MB.",
                filesSizeAll: "Files you've choosed are too large! Please upload files up to {{fi-maxSize}} MB."
            }
        }
    });
});