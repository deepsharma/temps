/*!
 * qhelper JavaScript v1.0.0
 * http://qustn.com/
 *
 * Devesh Chauhan
 * Qustn Technologies
 *
 * Includes jquery.js
 * https://jquery.com/
 *
 * Includes jquery-ui.js
 * https://jqueryui.com/
 *
 * Includes ifvisible.js
 * https://github.com/serkanyersen/ifvisible.js/
 *
 * Date: Wed Nov 25 15:28:23 2015 -0530
 *
 * Default position right top
 */
function getDomain(url) {
 url = url.replace(/https?:\/\/(www.)?/i, '');
 if (url.indexOf('/') === -1) {
   return url;
 }
 return url.split('/')[0];
}
var currentURL = getDomain(window.location.href);
currentURL = currentURL.replace(/\./g,'')
// alert(currentURL);

var CFS;
var foundEp = false;
$.ajax({
	url: "http://cfs.capabiliti.co/assets/getEnterprise.php",
	type: 'GET',
	async: false,
	success: function (data) {
		CFS = data;
		//search string
		
		if((CFS).indexOf(currentURL)>=0){
	//		alert("true=="+CFS);
			foundEp = true;
		}
	},
	error:function(error){
	}
});
CFS = currentURL;
//alert(foundEp);
if(foundEp == true){
	var modalBox = '<div class="qustn-modal qustn-fade qustn-in" id="qustn-modalBox" tabindex="-1" role="dialog">\
		<div class="qustn-modal-dialog">\
		<button type="button" class="qustn-close" onclick="hideThis(\'qustn-modalBox\');">&times;</button>\
			<div class="qustn-modal-content">\
				<div class="qustn-modal-header"></div>\
				<div class="qustn-modal-body"></div>\
				<div class="qustn-modal-footer"></div>\
			</div>\
		</div>\
	</div>';
	var frameBox = '<div class="qustn-modal qustn-fade qustn-in" id="qustn-frameBox" tabindex="-1" role="dialog">\
		<div class="qustn-modal-dialog">\
		<button type="button" class="qustn-close"  onclick="hideThis(\'qustn-frameBox\');">&times;</button>\
			<div class="qustn-modal-content">\
			<div class="qustn-modal-header"></div>\
				<div id="qustn-frame-height" class="qustn-modal-body"></div>\
			</div>\
		</div>\
	</div>';
	var popupMessage = '<div id="qustn-popup-msg-wrapper">\
	<div id="qustn-popup-msg" class="qustn-tooltip-box qustn-tooltip-box-rt" style="display:none;">\
		<button type="button" class="qustn-close" onclick="hidePopupMsg(\'qustn-popup-msg\');">&times;</button>\
								<div class="qustn-tooltip-header">\
									<h2>Hello!!!</h2>\
								</div>\
								<div class="qustn-tooltip-content">\
									<p>\
									I\'m "Q"! If at any point of time you need help, simply click on me\
									</p>\
								</div>\
								<div class="qustn-tooltip-footer">\
								</div>\
							</div>\
						</div>';
	var enterprise_id = '';
	var token_key = '';
	var user_id = 'default';
	var qHelperData = '';// qhelper data as json string
	var courseData = '';// course data as json string
	var viewData = ''; // all icons and tooltip
	var artifactData = ''; // all artifacts
	var logData = ''; // store logging info
	var lastArtifact = '';// store last artifact id to get close log
	var lastMsgID = '';// store last message box id to get close log
	var walkthroughPopup = ''; //html of walkthrough popup
	var logDetails = ''; // document click event log
	var mouseoverDetails = ''; // document mouse move event log
	var _posTop = ''; // store top position of draggable
	var _posLeft = ''; // store left position of draggable
	var _position = '';
	var jquery_qustn = $.noConflict(true);
	var baseURL="http://cfs.capabiliti.co/assets/enterprise/"+CFS+"/";
	jquery_qustn(document).ready(function(){
		
		if(typeof(_qHelper) != "undefined" && _qHelper !== null){
			enterprise_id = _qHelper.enterprise;
			token_key = _qHelper.token;
			//user_id = _qHelper.userid;
			user_id = getCookie("sc_mid");
		}
		else{
			enterprise_id = "chrome-extension";
			token_key = "EDCEB93511A25488A9DB9C8733620C08";
			user_id = "000000";
		}
		/*******************************/
		//clear all sessionStorage
		//sessionStorage.clear();
		
		
		//qustn-modalBox show/hide event
		/*jquery_qustn('body').on("click",function(event){
		if(jquery_qustn(event.target).closest("#qHelp-launcher,.qustn-modal-dialog,.click_launcher").length > 0){
			//do nothing
		}
		else{
			if(jquery_qustn('#qustn-modalBox').is(':visible') && jquery_qustn('#qustn-frameBox').is(':visible')){
				
				eventType = "close";
				eventTime = getDate();
				eventID = lastArtifact;
				elementType = "popup window";
				createJsonObj(eventType,eventTime,eventID,elementType);
				//reset variables
				lastArtifact = '';
				lastMsgID = '';
				ifvisibleFlag = true;
				jquery_qustn('#qustn-frameBox').hide();
				//remove src of video
				jquery_qustn('#qustn-video').attr('src','');
			}
			else{
				
				//hide pop up window
				jquery_qustn('#qustn-modalBox,#qustn-frameBox').hide();
				//remove src of video
				jquery_qustn('#qustn-video').attr('src','');
			}
		}
		});*/
		
		//event triggered on click of window 
		var objClick;
		jquery_qustn(window).click(function (e) {
			
			var text = e.target.innerText;
			if(text != ""){
				text = text.substring(0, 30)+'...';
			}
			
			objClick = new Object();
			objClick.userID = user_id;
			objClick.enterpriseID = enterprise_id;
			objClick.elementID = "";
			objClick.elementType = e.target.tagName;
			objClick.event = "Click";
			objClick.datetime  = getDate();
			objClick.source = getBrowser();
			objClick.URL = getURL();
			objClick.innerText =  text;
			objClick.x = e.clientX;
			objClick.y = e.clientY;
			objClick.left = e.offsetX;
			objClick.top = e.offsetY;
			objClick.height = getDocHeight();
			objClick.width = getDocWidth();
			objClick.language = getBrowserLang();
			objClick.userAgent = getBrowserUserAgent();
			objClick.platform = getBrowserPlatform();
			jsonString= JSON.stringify(objClick);
			logDetails+=jsonString+',';
			
		});
		//string1.replace(/~+$/,'');

		//count hover time
		/*
		var hoverCounter = 0;
		var myInterval =null;
		var objMove;
		jquery_qustn(window).hover(function(e){
			hoverCounter = 0;
			myInterval = setInterval(function () {
				++hoverCounter;
			}, 1000);
		},function(e){
			clearInterval(myInterval);
			
			var text = e.target.innerText;
			if(text != ""){
				text = text.substring(0, 30)+'...';
			}
			
			objMove = new Object();
			objMove.userID = user_id;
			objMove.enterpriseID = enterprise_id;
			objMove.elementID = "";
			objMove.elementType = e.target.tagName;
			objMove.event = "Mouse Move";
			objMove.datetime  = getDate();
			objMove.source = getBrowser();
			objMove.URL = getURL();
			objMove.innerText =  text;
			objMove.x = e.clientX;
			objMove.y = e.clientY;
			objMove.left = e.offsetX;
			objMove.top = e.offsetY;
			objMove.height = getDocHeight();
			objMove.width = getDocWidth();
			objMove.language = getBrowserLang();
			objMove.userAgent = getBrowserUserAgent();
			objMove.platform = getBrowserPlatform();
			jsonString= JSON.stringify(objMove);
			mouseoverDetails+=jsonString+',';
		});
		*/
		//check qhelper version and update sessionStorage
		jquery_qustn.ajax({
					url: baseURL+"json/version.json",
					type: 'GET',
					jsonpCallback: 'callback',
					async: false,
					contentType: "application/json",
					dataType: 'jsonp',
					success: function (result) {
						oldVersion = sessionStorage.getItem(enterprise_id+"-version");
						newVersion = result.version;
						sessionStorage.setItem(enterprise_id+"-version", result.version);
						if(oldVersion != newVersion){
						sessionStorage.removeItem(enterprise_id+"-course");
						sessionStorage.removeItem(enterprise_id+"-node");
						sessionStorage.removeItem(enterprise_id+"-viewData");
						sessionStorage.removeItem(enterprise_id+"-artifactData");
						}
					},
					error:function(error){
						console.log(error);
					}		
				});

		//create unique user id
		checkCookie();
		setTimeout(loadContent,1000);
	});
	function loadContent(){
		//get view
		if(sessionStorage.getItem(enterprise_id+"-viewData") === null){
		jquery_qustn.ajax({
					url: baseURL+"json/view.json",
					type: 'GET',
					jsonpCallback: 'callback',
					async: false,
					contentType: "application/json",
					dataType: 'jsonp'		
				}).always(function( result ) {
					viewData = result;
					sessionStorage.setItem(enterprise_id+"-viewData", JSON.stringify(viewData));
				});
		}
		else{
			//already exist
			//get data and append it to web
			viewData = sessionStorage.getItem(enterprise_id+"-viewData");
			//console.log(result);
			viewData = JSON.parse(viewData);
		}
		setTimeout(init,1000);
	}

	function init(){
		eventType = "load";
		eventTime = getDate();
		eventID = "000";
		elementType = "body";
		createJsonObj(eventType,eventTime,eventID,elementType);
		
		
		if(sessionStorage.getItem(enterprise_id+"_posTop") !== null){
			_posTop = sessionStorage.getItem(enterprise_id+"_posTop");
			_position = 'top:'+_posTop+'px;';
		}
		if(sessionStorage.getItem(enterprise_id+"_posLeft") !== null){
			_posLeft = sessionStorage.getItem(enterprise_id+"_posLeft");
			_position += 'left:'+_posLeft+'px;';
		}
		if (!document.getElementById('qustn-modalBox')) {
		 //add modal
		 jquery_qustn('body').append(modalBox);
		}
		if (!document.getElementById('qustn-frameBox')) {
		 jquery_qustn('body').append(frameBox);
		}
		 //check local storage
		 if(sessionStorage.getItem(enterprise_id+"-course") === null){
		 jquery_qustn.ajax({
					url: baseURL+"json/qhelper.json",
					type: 'GET',
					jsonpCallback: 'callback',
					async: false,
					contentType: "application/json",
					dataType: 'jsonp'	
				}).always(function( result ) {
					qHelperData = JSON.stringify(result);
					sessionStorage.setItem(enterprise_id+"-course", qHelperData);
					
					radius = result.attributes.radius;
					//fab-icon path
					fabIcon = baseURL+result.attributes.image;
					
					if (!document.getElementById('qHelp-container')) {
					
					// add help launcher
					launcher = '<div id="qHelp-container" style="'+_position+'">\
						<div onclick="toggleLauncher();" id="qHelp-revert" title="show/hide"></div>\
						<div id="qHelp-launcher" class="qustn-fab qustn-fab-position" onmouseenter="qhelperEnter();" style="color:'+result.attributes.color+'; background-color: '+result.attributes.bgcolor+'; box-shadow:'+result.attributes.shadow+';">\
						</div>\
					</div>';
					jquery_qustn('body').append(launcher).find('#qHelp-container').draggable({
						start: function( event ) {
							qustnDragStart(event);
						},
						stop: function( event ) {
							qustnDragStop(event);
							_posTop = qustnDragTop("#qHelp-container");
							_posLeft = qustnDragLeft("#qHelp-container");
							sessionStorage.setItem(enterprise_id+"_posTop", _posTop);
							sessionStorage.setItem(enterprise_id+"_posLeft", _posLeft );
						},
						containment: "window",
						scroll: false,
						cursor: "move"
					});
					// append icon inside it
					jquery_qustn('#qHelp-launcher').append(jquery_qustn('<div><span class="qustn-label-danger qustn-lable-aleart qustn-count-notify">0</span><img src="'+fabIcon+'" class="qustn-fab-icon"></div>').attr({ "data-enterprise" : enterprise_id, "data-token":token_key}));
					//append div which get animated using css3
					jquery_qustn('#qHelp-launcher').append('<div id="qustn-helpcontent" class="qustn-fab__actions qustn-fab__actions--down"></div>');
					//append popupMessage
					jquery_qustn('#qHelp-container').append(popupMessage);
					
					//get data and append it to web
					dataString = fetchTitle(result);
					jquery_qustn('#qustn-helpcontent').append(dataString);
					
					//set all position
					setDragPosition(_posLeft,_posTop);
					
					}
				});
		}
		else{
			//already exist
			//get data and append it to web
			result = sessionStorage.getItem(enterprise_id+"-course");
			//console.log(result);
			result = JSON.parse(result);
			
			radius = result.attributes.radius;
			//fab-icon path
			fabIcon = baseURL+result.attributes.image;
			
			if (!document.getElementById('qHelp-container')) {
			
			
			// add help launcher
			launcher = '<div id="qHelp-container" style="'+_position+'">\
				<div onclick="toggleLauncher();" id="qHelp-revert" title="show/hide"></div>\
				<div id="qHelp-launcher" class="qustn-fab qustn-fab-position" onmouseenter="qhelperEnter();" style="color:'+result.attributes.color+'; background-color: '+result.attributes.bgcolor+'; box-shadow:'+result.attributes.shadow+';">\
				</div>\
			</div>';
			jquery_qustn('body').append(launcher).find('#qHelp-container').draggable({
				start: function( event ) {
					qustnDragStart(event);
				},
				stop: function( event ) {
					qustnDragStop(event);				
					_posTop = qustnDragTop("#qHelp-container");
					_posLeft = qustnDragLeft("#qHelp-container");
					sessionStorage.setItem(enterprise_id+"_posTop", _posTop);
					sessionStorage.setItem(enterprise_id+"_posLeft", _posLeft );
				},
				containment: "window",
				scroll: false,
				cursor: "move"
			});
			// append icon inside it
			jquery_qustn('#qHelp-launcher').append(jquery_qustn('<div><span class="qustn-label-danger qustn-lable-aleart qustn-count-notify">0</span><img src="'+fabIcon+'" class="qustn-fab-icon"></div>').attr({ "data-enterprise" : enterprise_id, "data-token":token_key}));
			//append div which get animated using css3
			jquery_qustn('#qHelp-launcher').append('<div id="qustn-helpcontent" class="qustn-fab__actions qustn-fab__actions--down"></div>');
			//append popupMessage
			jquery_qustn('#qHelp-container').append(popupMessage);
			
			dataString = fetchTitle(result);
			jquery_qustn('#qustn-helpcontent').append(dataString);
			
			//set all position
			setDragPosition(_posLeft,_posTop);
			
			
			}
		}
		
		//check does wolkthrough is playing
		if(sessionStorage.getItem(enterprise_id+"-walkthroughPopup")!== null){
			walkthroughPopup = sessionStorage.getItem(enterprise_id+"-walkthroughPopup");
			jquery_qustn('body').append(walkthroughPopup);
			//sessionStorage.setItem(enterprise_id+"-walkthroughPopup", '');
		}
		//check whether launcher is on/off
		loadLauncher();
	}

	/** launcher js **/
	function toggleLauncher(){
		if(sessionStorage.getItem(enterprise_id+"-launcher")!== null){
			if(sessionStorage.getItem(enterprise_id+"-launcher") === "0"){
				jquery_qustn('#qHelp-launcher').show();
				jquery_qustn('#qustn-popup-msg-wrapper').show();
				jquery_qustn('#qustn-popup-msg').hide();
				sessionStorage.setItem(enterprise_id+"-launcher", "1");
				img = 'url("'+baseURL+'img/minus.png")';
				jquery_qustn('#qHelp-revert').css({'background-image': img});
			}
			else{
				jquery_qustn('#qHelp-launcher').hide();
				jquery_qustn('#qustn-popup-msg-wrapper').hide();
				sessionStorage.setItem(enterprise_id+"-launcher", "0");
				img = 'url("'+baseURL+'img/add.png")';
				jquery_qustn('#qHelp-revert').css({'background-image': img});
			}
		}
	}

	function loadLauncher(){
		jquery_qustn('#qHelp-launcher').show();
		jquery_qustn('#qustn-popup-msg-wrapper').show();
		jquery_qustn('#qustn-popup-msg').hide();
		sessionStorage.setItem(enterprise_id+"-launcher", "1");
		img = 'url("'+baseURL+'img/minus.png")';
		jquery_qustn('#qHelp-revert').css({'background-image': img});
	}
	/** launcher js **/
	/** get co-ordinates of draggable **/
	function qustnDragTop(element) {
		element = jquery_qustn(element);
		return element.position().top;
	}
	function qustnDragLeft(element) {
		element = jquery_qustn(element);
		return element.position().left;
	}

	function fetchTitle(result){
		dataString = '';
		radius = result.attributes.radius;
		i = 0;

		jquery_qustn.each( result.properties, function( index, data ) {
		 i++;
		 current = this.property;
		 dataString += '<button onclick="openModal(this);" class="qustn-btn qustn-btn--fab" style="border-radius:'+radius+'; background-color: '+current.bgcolor+';" data-id="'+current.id+'" data-element="'+current.title+'" data-toggle="modal" data-value="'+current.id+'" data-target="#icon-'+i+'" id="icon-'+i+'_launcher">';
		 dataString += '<span class="qustn-fab-tip qustn-fab-tip-left qustn-lh" style="color:'+current.color+'; background-color: '+current.bgcolor+'; box-shadow:'+current.shadow+';">'+current.title+'</span>';
		 dataString += '<img src="'+baseURL+current.image+'">';
		 dataString += '</button>';
		});
		return dataString;
	}

	//function to create content according to the type like training, search, notification etc.
	function openModal(e){
		
		ifvisibleFlag = false;
		
		eventType = "click";
		eventTime = getDate();
		eventID = e.getAttribute('data-id');
		elementType = e.getAttribute('data-element');
		createJsonObj(eventType,eventTime,eventID,elementType);
		
		var dataValue = e.getAttribute('data-value');// type of menu content
		//dataValue 001 search
		// 002 training
		//check local storage
		dataString = '';
		if(sessionStorage.getItem(enterprise_id+"-node") === null){
			if(dataValue == '002'){
			//loop to count no. of title
			 jquery_qustn.ajax({
						url: "http://cfs.capabiliti.co/assets/getContent.php",
						type: 'POST',
						//jsonpCallback: 'callback',
						async: false,
						//contentType: "application/json",
						//dataType: 'jsonp',
						data: {url:baseURL+"json/course.json"}
					}).always(function( result ) {
						//courseData = JSON.stringify(result);
						courseData = result;
						//console.log(courseData);
						//courseData = courseData.replace(/\\/g,'');
						sessionStorage.setItem(enterprise_id+"-node", courseData);
						//get data and append it to web
						dataString = fetchMenu(JSON.parse(result));
						jquery_qustn('#qustn-modalBox .qustn-modal-body').html(dataString);
						jquery_qustn('#qustn-modalBox').show();
				});
			}//Recommended Training
			else if(dataValue == '001'){
				dataString = fetchSearchData();
				jquery_qustn('#qustn-modalBox .qustn-modal-header').html('');
				jquery_qustn('#qustn-modalBox .qustn-modal-body').html(dataString);
				jquery_qustn('#qustn-modalBox').show();
			}
			else if(dataValue == '003'){
				jquery_qustn('#qustn-modalBox .qustn-modal-header').html('');
				jquery_qustn('#qustn-modalBox .qustn-modal-body').html(dataString);
				jquery_qustn('#qustn-modalBox').show();
			}
		}
		else{
			//already exist
			//get data and append it to web
			if(dataValue == '002'){
			result = sessionStorage.getItem(enterprise_id+"-node");
			//console.log(result);
			result = JSON.parse(result);
			dataString = fetchMenu(result);
			jquery_qustn('#qustn-modalBox .qustn-modal-body').html(dataString);
			jquery_qustn('#qustn-modalBox').show();
			}//Recommended Training
			else if(dataValue == '001'){
				fetchSearchData();
			}
			else if(dataValue == '003'){
				jquery_qustn('#qustn-modalBox .qustn-modal-header').html('');
				jquery_qustn('#qustn-modalBox .qustn-modal-body').html(dataString);
				jquery_qustn('#qustn-modalBox').show();
			}
		}
	}

	//fetch all menu data
	function fetchMenu(result){
		dataString = '';
		heading = result.title;
		i = 0;

		jquery_qustn.each( result.user_repository, function( index, data ) {
			i++;
			current = this;
			name = current.title;
			id = current.id;
			htmlid = name.replace(/\s+/g, '-').toLowerCase();
			disabledCourse = current.disable;
			lockedCourse = current.lock;
			courses = current.courses;
			jquery_qustn('#qustn-modalBox .qustn-modal-header').html('<div class="qustn-form-group qustn-input-group qustn-modal-head"><h3 class="qustn-h3">'+heading+'</h3></div>');
			dataString += '<div class="qustn-panel-default">';
			dataString += '<div onclick="openChapter(this);" data-id="'+id+'" data-element="'+name+'" class="qustn-panel-heading"><h4 class="qustn-h4 qustn-panel-title qustn-panel-title'+i+'"> <a data-toggle="qustn-collapse" data-parent="" data-target="#'+htmlid+'-'+i+'" aria-expanded="false" style="text-decoration:none"><span class="qustn-panel-icon"><img class="qustn-icon-list" src="'+baseURL+viewData.view.iconList.icon+'"></span><!--Module '+i+' : -->'+name+'</a></h4></div>';
			dataString += '<div id="'+htmlid+'-'+i+'" class="qustn-panel-collapse qustn-collapse" aria-expanded="false">';
			//check if lesson is disabled
			// true = disabled and false = enable
			//if(disabledCourse == "false"){
				//loop to count no. of content title
				j=0;
				jquery_qustn.each( courses, function( index, data ) {
					j++;
					currentCourse = this.course;
					courseID = currentCourse.id;
					courseTitle = currentCourse.title;
					courseDesc = currentCourse.description;
					artifacts = currentCourse.artifacts;
					
					dataString += '<div class="qustn-panel-body qustn-panel-contant">\
					<div class="qustn-row qustn-row-height">';

					dataString += '<div class="qustn-col-md-8 qustn-contant-title'+j+'"><img src="'+baseURL+viewData.view.iconbullet.icon+'" style="margin-right:18px"><!--Lesson '+j+' : --><span>'+courseTitle+'</span></div>\
					<div class="qustn-col-md-4">\
					<div class="qustn-row">';
					//loop to count content type
					jquery_qustn.each( artifacts, function( index, data ) {
						artifactType = index;
						artifactID = data;
							if(artifactID == ""){
							dataString += '<div class="qustn-contant-icon" style="opacity:0.5;">\
							<a class="click_launcher" onclick="" data-id="'+artifactID+'" data-content="'+artifactType+'" style="cursor: not-allowed;">\
							<img src="'+baseURL+viewData.view[artifactType].icon+'" alt="" title="'+viewData.view[artifactType].tooltiptext+'"></a></div>';
							}
							else{
							dataString += '<div class="qustn-contant-icon">\
							<a class="click_launcher" onclick="getContent(this);" data-element="'+artifactType+'" data-id="'+artifactID+'" data-content="'+artifactType+'">\
							<img src="'+baseURL+viewData.view[artifactType].icon+'" alt="" title="'+viewData.view[artifactType].tooltiptext+'"></a></div>';
							}
					});
					dataString += '</div><!--qustn-row closed-->\
					</div>\
					</div>\
					</div><!--panel-body closed-->';
				});	
			//}//if condition closed
			dataString +='</div>\
			</div><!--panel-default closed-->';
		});
		return dataString;
	}
	jquery_qustn('body').delegate('#qustnTextSearch','blur', function(event) {
		eventType = "Entered";
		eventTime = getDate();
		eventID = "000";
		elementType = "search-input-"+jquery_qustn(this).val();
		createJsonObj(eventType,eventTime,eventID,elementType);
	});
	//search lessions on the basis of tags and title
	jquery_qustn('body').delegate('#qustnTextSearch','keyup', function(event) {
		jquery_qustn("#qustn-no-msg").hide();
		var searchtext=jquery_qustn(this).val();
		if(searchtext=='' || searchtext==null){
			jquery_qustn(".qustn-panel-contant").hide();
		}else{
		var found=false;
		jquery_qustn(".qustn-panel-contant").each(function(){
			var current = jquery_qustn(this).find("span").html().toLowerCase();
			var temp = 0;
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
				jquery_qustn(this).show();
				found=true;
			}else{
				jquery_qustn(this).hide();
			}
		});
		if(found==true){
				jquery_qustn("#qustn-no-msg").hide();
			}else{
				jquery_qustn("#qustn-no-msg").show();
			}
		}
	});
	//create search string and fetch search data
	function fetchSearchData(){
	if(sessionStorage.getItem(enterprise_id+"-node") === null){
		jquery_qustn.ajax({
						url: "http://cfs.capabiliti.co/assets/getContent.php",
						type: 'POST',
						//jsonpCallback: 'callback',
						async: false,
						//contentType: "application/json",
						//dataType: 'jsonp',
						data: {url:baseURL+"json/course.json"}
					}).always(function( result ) {
						//courseData = JSON.stringify(result);
						courseData = result;
						sessionStorage.setItem(enterprise_id+"-node", courseData);
						dataString = searchString(JSON.parse(result));
				});
	}
	else{
		result = sessionStorage.getItem(enterprise_id+"-node");
		//console.log(result);
		result = JSON.parse(result);
		dataString = searchString(result);
	}
	}

	//create search string
	function searchString(result){
	searchIcon = baseURL+'img/icon-spotlight-search.png';
	dataString = '<div id="qustn-search-group" class="qustn-form-group qustn-input-group">\
	<span class="qustn-input-group-addon qustn-search-icon-bar">\
	<img src="'+searchIcon+'">\
	</span>\
	<input type="text" id="qustnTextSearch" class="qustn-form-control qustn-search-field" placeholder="Need Help? Type what you are looking for..."><!-- /.dropdown-user -->\
	</div>';
	dataString += '<div id="qustn-no-msg">Nothing found...</div>';

		heading = result.title;
		i = 0;

		jquery_qustn.each( result.user_repository, function( index, data ) {
			i++;
			current = this;
			name = current.title;
			disabledCourse = current.disable;
			lockedCourse = current.lock;
			courses = current.courses;
			
			dataString += '<div class="qustn-panel-default">';

			//check if lesson is disabled
			// true = disabled and false = enable
			//if(disabledCourse == "false"){
				//loop to count no. of content title
				j=0;
				jquery_qustn.each( courses, function( index, data ) {
					j++;
					currentCourse = this.course;
					courseID = currentCourse.id;
					courseTitle = currentCourse.title;
					courseTags = currentCourse.tags;
					courseDesc = currentCourse.description;
					artifacts = currentCourse.artifacts;
					
					dataString += '<div class="qustn-panel-body qustn-panel-contant" style="display:none;">\
					<div class="qustn-row qustn-row-height">';

					dataString += '<div class="qustn-col-md-8 qustn-contant-title'+j+'"><img src="'+baseURL+viewData.view.iconbullet.icon+'" style="margin-right:18px"><!--Lesson '+j+' : --><span>'+courseTitle+'<span style="display:none;">'+courseTags+'</span></span></div>\
					<div class="qustn-col-md-4">\
					<div class="qustn-row">';
					//loop to count content type
					jquery_qustn.each( artifacts, function( index, data ) {
						artifactType = index;
						artifactID = data;
							if(artifactID == ""){
							dataString += '<div class="qustn-contant-icon" style="opacity:0.5;">\
							<a class="click_launcher" onclick="" data-id="'+artifactID+'" data-content="'+artifactType+'" style="cursor: not-allowed;">\
							<img src="'+baseURL+viewData.view[artifactType].icon+'" alt="" title="'+viewData.view[artifactType].tooltiptext+'"></a></div>';
							}
							else{
							dataString += '<div class="qustn-contant-icon">\
							<a class="click_launcher" onclick="getContent(this);" data-element="'+artifactType+'" data-id="'+artifactID+'" data-content="'+artifactType+'">\
							<img src="'+baseURL+viewData.view[artifactType].icon+'" alt="" title="'+viewData.view[artifactType].tooltiptext+'"></a></div>';
							}
					});
					dataString += '</div><!--qustn-row closed-->\
					</div>\
					</div>\
					</div><!--panel-body closed-->';
				});	
			//}//if condition closed
			dataString +='</div>\
			</div><!--panel-default closed-->';
		});
		jquery_qustn('#qustn-modalBox .qustn-modal-header').html('');
		//<div class="qustn-form-group qustn-input-group qustn-modal-head"><h3 class="qustn-h3">Search</h3></div>
		jquery_qustn('#qustn-modalBox .qustn-modal-body').html(dataString);
		jquery_qustn('#qustn-modalBox').show();
	}

	//function collapse menu script
	function openChapter(e){
		
		eventType = "click";
		eventTime = getDate();
		eventID = e.getAttribute('data-id');
		elementType = e.getAttribute('data-element');
		createJsonObj(eventType,eventTime,eventID,elementType);
		
		dataTarget = jquery_qustn(e).find("a").attr("data-target");//id
		if(jquery_qustn(dataTarget).hasClass("qustn-collapse")){
		jquery_qustn('.qustn-panel-collapse').removeClass("qustn-in").addClass("qustn-collapse");
		jquery_qustn(dataTarget).removeClass("qustn-collapse").addClass("qustn-in");
		}
		else{
		jquery_qustn(dataTarget).addClass("qustn-collapse").removeClass("qustn-in");	
		}
	}

	//function to get pdf/video or whatever the content is
	function getContent(e){
		
		ifvisibleFlag = false;
		
		eventType = "click";
		eventTime = getDate();
		eventID = e.getAttribute('data-id');
		elementType = e.getAttribute('data-element');
		createJsonObj(eventType,eventTime,eventID,elementType);
		
		lastArtifact = e.getAttribute('data-id');
		
		artifactType = e.getAttribute('data-content');
		artifactID = e.getAttribute('data-id');
		if(sessionStorage.getItem(enterprise_id+"-artifactData") === null){
		jquery_qustn.ajax({
					url: "http://cfs.capabiliti.co/assets/getContent.php",
					type: 'POST',
					//jsonpCallback: 'callback',
					async: false,
					//contentType: "application/json",
					//dataType: 'jsonp'
					data: {url:baseURL+"json/artifacts.json"}
				}).always(function( result ) {
						//artifactData = result;
						artifactData = JSON.parse(result);
						//sessionStorage.setItem(enterprise_id+"-artifactData", JSON.stringify(result));
						sessionStorage.setItem(enterprise_id+"-artifactData", result);
						//url must be absolute path in case of walkthrough it is relative
						//path must be relative
						artifactLink = artifactData.artifacts[artifactID].url;
						artifactPath = "video/"+artifactData.artifacts[artifactID].path;
						if(artifactType == 'pdf'){
							//jquery_qustn('#qustn-frame-height').css({'height':'auto'});
							//dataString = '<embed id="qustn-pdf" src="'+artifactLink+'" type="application/pdf"/>';
							OpenInNewTab(baseURL+"pdf/"+artifactData.artifacts[artifactID].path);
						}
						else if(artifactType == 'video'){
							dataString = '<iframe onload="loadQustniFrame();" id="qustn-video" src="'+baseURL+'video.html?src='+artifactPath+'&user_id='+user_id+'&enterprise_id='+enterprise_id+'&artifact_type='+artifactType+'&artifact_id='+artifactID+'"/>';
							jquery_qustn('#qustn-frameBox .qustn-modal-body').html(dataString);
							jquery_qustn('#qustn-frameBox').show();
						}
						else if(artifactType == 'simulation'){
							dataString = '<iframe onload="loadQustniFrame();" id="qustn-video" src="'+baseURL+artifactPath+'"/>';
							jquery_qustn('#qustn-frameBox .qustn-modal-body').html(dataString);
							jquery_qustn('#qustn-frameBox').show();
						}
						else if(artifactType == 'walkthrough'){
							video_dir = artifactData.artifacts[artifactID].video_dir;
							no_of_walkthrough = artifactData.artifacts[artifactID].total_video;
							dataString = '<iframe onload="loadQustniFrame();" id="qustn-video" src="'+baseURL+"walkthrough.html?video_name="+video_dir+"_1.mp4&video_dir="+video_dir+"&total_video="+no_of_walkthrough+"&play_type=replay"+'&artifact_id='+artifactID+'&user_id='+user_id+'&enterprise_id='+enterprise_id+'&artifact_type='+artifactType+'"/>';
							jquery_qustn('#qustn-modalBox').hide();
							jquery_qustn('#qustn-frameBox .qustn-modal-body').html(dataString);
							jquery_qustn('#qustn-frameBox').show();
						}
						
						//jquery_qustn('#qustn-modalBox').hide();
						jquery_qustn('.qustn-video-shell').remove();
				});
		}
		else{
			artifactData = sessionStorage.getItem(enterprise_id+"-artifactData");
			artifactData = JSON.parse(artifactData);
			artifactLink = artifactData.artifacts[artifactID].url;
			artifactPath = "video/"+artifactData.artifacts[artifactID].path;
			if(artifactType == 'pdf'){
				//jquery_qustn('#qustn-frame-height').css({'height':'auto'});
				//dataString = '<embed id="qustn-pdf" src="'+artifactLink+'" type="application/pdf"/>';
				OpenInNewTab(baseURL+"pdf/"+artifactData.artifacts[artifactID].path);
			}
			else if(artifactType == 'video'){
				dataString = '<iframe onload="loadQustniFrame();" id="qustn-video" src="'+baseURL+'video.html?src='+artifactPath+'&user_id='+user_id+'&enterprise_id='+enterprise_id+'&artifact_type='+artifactType+'&artifact_id='+artifactID+'"/>';
				jquery_qustn('#qustn-frameBox .qustn-modal-body').html(dataString);
				jquery_qustn('#qustn-frameBox').show();
			}
			else if(artifactType == 'simulation'){
				dataString = '<iframe onload="loadQustniFrame();" id="qustn-video" src="'+baseURL+artifactPath+'"/>';
				jquery_qustn('#qustn-frameBox .qustn-modal-body').html(dataString);
				jquery_qustn('#qustn-frameBox').show();
			}
			else if(artifactType == 'walkthrough'){
				video_dir = artifactData.artifacts[artifactID].video_dir;
				no_of_walkthrough = artifactData.artifacts[artifactID].total_video;
				dataString = '<iframe onload="loadQustniFrame();" id="qustn-video" src="'+baseURL+"walkthrough.html?video_name="+video_dir+"_1.mp4&video_dir="+video_dir+"&total_video="+no_of_walkthrough+"&play_type=replay"+'&artifact_id='+artifactID+'&user_id='+user_id+'&enterprise_id='+enterprise_id+'&artifact_type='+artifactType+'"/>';
				jquery_qustn('#qustn-modalBox').hide();
				jquery_qustn('#qustn-frameBox .qustn-modal-body').html(dataString);
				jquery_qustn('#qustn-frameBox').show();
			}
			//jquery_qustn('#qustn-modalBox').hide();
			jquery_qustn('.qustn-video-shell').remove();
		}
	}

	//function get walkthrough
	function getWalkthrough(e){
		
		ifvisibleFlag = false;
		
		artifactID = e.getAttribute('data-id');
		video_title = e.getAttribute('data-element');
		video_button = e.getAttribute('data-button');
		total_video = e.getAttribute('data-videos');
		
		if(video_button == "next"){
			if(video_title != total_video)
			video_title++;
		}
		else if(video_button == "previous"){
			if(video_title != 1)
			video_title--;
		}

		eventType = "play";
		eventTime = getDate();
		eventID = artifactID;
		elementType = 'Step 0'+video_title+'-walkthrough';
		createJsonObj(eventType,eventTime,eventID,elementType);
		
		artifactPath = e.getAttribute('data-link');
		dataString = '<iframe onload="loadQustniFrame();" id="qustn-video" src="'+artifactPath+'"/>';
		jquery_qustn('#qustn-frameBox .qustn-modal-body').html(dataString);
		jquery_qustn('#qustn-frameBox').show();
		//jquery_qustn('#qustn-modalBox').hide();
		jquery_qustn('.qustn-video-shell').remove();
	}

	//function to close video shell
	function closeVideoshell(e){
		jquery_qustn('.qustn-video-shell').remove();
		sessionStorage.setItem(enterprise_id+"-walkthroughPopup", '');
	}

	//function to set height of iframe while loading video or pdf
	function loadQustniFrame(){
	var w = jquery_qustn('#qustn-frame-height').width();
	ratio = screen.width / screen.height;
	//widthT = heightT * ratio;
	heightT = w / ratio;
	heightT = heightT+'px';
	jquery_qustn('#qustn-frameBox .qustn-modal-dialog').css({'margin-top':'50px'});
	jquery_qustn('#qustn-frame-height').css({'height':heightT});
	}
	//function to create UUID
	function generateUUID(){
		var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
		var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
		return v.toString(16);
	});
		return uuid;
	};
	//function setCookie for unique user
	function setCookie(cname,cvalue,exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires=" + d.toGMTString();
		document.cookie = cname+"="+cvalue+"; "+expires+"; path=/";
	}
	//function to get cookie
	function getCookie(cname) {
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for(var i=0; i<ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1);
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}
	//function update or create cookie
	function checkCookie() {
		var user=getCookie("qustnUserId");
		if (user != "") {
			//alert("Welcome again " + user);
			setCookie("qustnUserId", user, 90);//update
		} else {
		   user = generateUUID();
		   if (user != "" && user != null) {
			   setCookie("qustnUserId", user, 90);//create
		   }
		   setTimeout(firstLogin,5000);
		}
	}

	// Create IE + others compatible event handler
	var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
	var eventer = window[eventMethod];
	var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";


	// Listen to message from child window
	eventer(messageEvent,function(e) {
	  //console.log('parent received message!:  ',e.data)
	  if(e.data){
		  var vid_details = (e.data).split('^');
		  video_name = vid_details[0];
		  video_title = vid_details[1];
		  video_dir = vid_details[2];
		  total_video = vid_details[3];
		  artifactID = vid_details[4];
		  
		  contentType = 'walkthrough';
		  
		  eventType = vid_details[5];
		  eventTime = getDate();
		  if(video_title == 0)
		  elementType = total_video+'-'+artifactID+'-'+contentType;
		  else
		  elementType = video_title+'-'+artifactID+'-'+contentType;
		  eventID = artifactID;
		  if(!isNaN(eventID) || eventID ==''){
			createJsonObj(eventType,eventTime,eventID,elementType);
		  }
		  walkthroughEnded(video_name,video_title,video_dir,total_video,contentType,artifactID);
	  }
	});

	//function when walkthrough ended and converted to video shell
	function walkthroughEnded(video_name,contentTitle,video_dir,total_video,contentType,artifactID){
		
		contentLink = baseURL+'walkthrough.html?video_name='+video_name+'&video_dir='+video_dir+'&total_video='+total_video+'&artifact_id='+artifactID+'&user_id='+user_id+'&enterprise_id='+enterprise_id+'&artifact_type=walkthrough';
		
		jquery_qustn('#qustn-frameBox').hide();
		jquery_qustn('#qustn-video').attr('src','');
		dataString = '<div class="qustn-col-md-3 qustn-video-shell">\
			<div class="qustn-box qustn-box-success qustn-box-solid">\
				<div class="qustn-box-header qustn-with-border"> <h3 class="qustn-h3 qustn-box-title">'+contentTitle+'</h3>\
					<div class="qustn-box-tools qustn-pull-right">\
						<button onclick="closeVideoshell(this);" class="qustn-btn qustn-btn-box-tool" data-widget="remove">\
							<img src="'+baseURL+'img/btn_close.png" style="width:15px">\
						</button>\
					</div><!-- /.qustn-box-tools -->\
				</div><!-- /.qustn-box-header -->';
		dataString += '<a onclick="getWalkthrough(this);" data-button="replay" data-videos="'+total_video+'" data-element="'+video_name+'" data-id="'+artifactID+'" data-content="'+contentType+'" class="click_launcher" data-link="'+contentLink+'&play_type=replay">\
					<div class="qustn-overlay"><img src="'+baseURL+'img/overlay-play.png" width="100%"></div>\
				</a>';
		dataString += '<div class="qustn-box-footer qustn-row">';
		dataString += '<a onclick="getWalkthrough(this);" data-button="previous" data-videos="'+total_video+'" data-element="'+video_name+'" data-id="'+artifactID+'" data-content="'+contentType+'" class="click_launcher" data-link="'+contentLink+'&play_type=previous">\
						<div class="qustn-col-md-2 qustn-play-previous"><img src="'+baseURL+'img/icon-left.png"></div>\
					</a>';
		dataString += '<div class="qustn-player-title" style="float:left; padding-left:10px;">Prev</div>';
		
		//check condition of last next button
		if(video_name == total_video){
		dataString += '<a onclick="getWalkthrough(this);" data-button="next" data-videos="'+total_video+'" data-element="'+video_name+'" data-id="'+artifactID+'" data-content="'+contentType+'" class="click_launcher" data-link="'+contentLink+'&play_type=replay">\
						<div class="qustn-col-md-2 qustn-play-next" style="float:right;"><img src="'+baseURL+'img/icon-right.png"></div>\
					</a>';
		}
		else{
		dataString += '<a onclick="getWalkthrough(this);" data-button="next" data-videos="'+total_video+'" data-element="'+video_name+'" data-id="'+artifactID+'" data-content="'+contentType+'" class="click_launcher" data-link="'+contentLink+'&play_type=next">\
					<div class="qustn-col-md-2 qustn-play-next" style="float:right;"><img src="'+baseURL+'img/icon-right.png"></div>\
				</a>';
		}
		dataString += '<div class="qustn-player-title" style="float:right; padding-right:10px;">Next</div>\
				</div><!-- /.qustn-box-footer -->\
			</div><!-- /.qustn-box-solid -->\
		</div>';
		
		walkthroughPopup = dataString;
		sessionStorage.setItem(enterprise_id+"-walkthroughPopup", walkthroughPopup);
		jquery_qustn('body').append(dataString);
	}
	//get document height
	function getDocHeight(){
		return Math.max(
			document.body.scrollHeight, document.documentElement.scrollHeight,
			document.body.offsetHeight, document.documentElement.offsetHeight,
			document.body.clientHeight, document.documentElement.clientHeight
		);
	}
	//get document height
	function getDocWidth(){
		var actualWidth = window.innerWidth ||
						  document.documentElement.clientWidth ||
						  document.body.clientWidth ||
						  document.body.offsetWidth;
		return actualWidth;
	}
	function hideThis(id){
		ifvisibleFlag = true;
		//remove src of video
		jquery_qustn('#qustn-video').attr('src','');
				
		eventType = "close";
		eventTime = getDate();
		eventID = lastArtifact;
		elementType = "popup window";
		createJsonObj(eventType,eventTime,eventID,elementType);
		lastArtifact = '';
		jquery_qustn('#'+id).hide();
		sessionStorage.setItem(enterprise_id+"-walkthroughPopup", '');
	}
	//function to hide welcome, idle, hidden msg. 
	function hidePopupMsg(id){
		eventType = "Disappear";
		eventTime = getDate();
		eventID = lastMsgID;
		elementType = "message box";
		createJsonObj(eventType,eventTime,eventID,elementType);
		lastMsgID = '';
		jquery_qustn('#'+id).hide("");
	}
	/*ifvisible*/
	/*
	001 welcome
	002 idle
	003 hidden
	*/

	var counter = 0;
	var pre_status = '';
	var hiddenInterval = null; // user is in hidden status or moves to other tab
	var idleInterval = null; // user is in idle mode
	var ifvisibleFlag = true;
	/*video play/pdf reading/walkthrough set false when getContent triggers and reset when popup window close*/
	function d(el){
		return document.getElementById(el);
	}
	ifvisible.setIdleDuration(30);

	ifvisible.on('statusChanged', function(e){
		if(e.status == "idle"){
		 if(ifvisibleFlag){
			if(jquery_qustn('#qustn-idle').length){
						//do nothing
					}
			else
			{
				if(jquery_qustn('.qustn-tooltip-box').length){
							jquery_qustn('.qustn-tooltip-box').hide();
						}
				
				eventType = "Appear";
				eventTime = getDate();
				eventID = "002";
				elementType = "message box";
				createJsonObj(eventType,eventTime,eventID,elementType);
				
				lastMsgID = eventID;
				
				jquery_qustn('#qustn-popup-msg h2').html('Hey!');
				jquery_qustn('#qustn-popup-msg p').html('Looking for something specific?');
			
				jquery_qustn('#qustn-popup-msg').show("");
				
				/*flag = 1;
				idleInterval = setInterval(function(){
					if(flag == 1){
						jquery_qustn('#qustn-idle').hide("");
						flag=0;
					}
					else{
						jquery_qustn('#qustn-idle').show("");
						flag=1;
					}
				},15000);*/
			}
		 }
		}
	});

	ifvisible.focus(function(){
		if(counter >= 20){
			//console.log("Hey! you was not active since last "+counter+" seconds...");
			if(jquery_qustn('.qustn-tooltip-box').length){
				jquery_qustn('.qustn-tooltip-box').hide();
			}
			
			eventType = "Appear";
			eventTime = getDate();
			eventID = "003";
			elementType = "message box";
			createJsonObj(eventType,eventTime,eventID,elementType);
			
			lastMsgID = eventID;
			
			jquery_qustn('#qustn-popup-msg h2').html('Welcome Back!');
			jquery_qustn('#qustn-popup-msg p').html('We\'re Happy to Help you !');
		
			jquery_qustn('#qustn-popup-msg').show("");
			
			counter = 0;
			pre_status = '';
			clearInterval(hiddenInterval);
			setTimeout(function(){
				jquery_qustn('#qustn-popup-msg').hide("");
			},8000);
		}
	});

	ifvisible.blur(function(){
		counter = 0;
		hiddenInterval = setInterval(function () {
			++counter;
		}, 1000);
		pre_status = 'hidden';
	});

	ifvisible.wakeup(function(){
		counter = 0;
		pre_status = '';
		clearInterval(hiddenInterval);
		clearInterval(idleInterval);	
		setTimeout(function(){
			jquery_qustn('#qustn-popup-msg').hide("");
		},8000);
	});

	/* function triggers when user login to web first time */
	function firstLogin(){
		if(jquery_qustn('.qustn-tooltip-box').length){
					jquery_qustn('.qustn-tooltip-box').hide();
				}
				
		eventType = "Appear";
		eventTime = getDate();
		eventID = "001";
		elementType = "message box";
		createJsonObj(eventType,eventTime,eventID,elementType);
		
		lastMsgID = eventID;
		
		jquery_qustn('#qustn-popup-msg h2').html('Hello!!!');
		jquery_qustn('#qustn-popup-msg p').html('Iâ€™m "Q"! If at any point of time you need help, simply click on me');
		
		jquery_qustn('#qustn-popup-msg').show("");
		
		setTimeout(function(){
			jquery_qustn('#qustn-popup-msg').hide("");
		},15000);
	}

	function qhelperEnter(){
		eventType = "mouseenter";
		eventTime = getDate();
		eventID = "000";
		elementType = "qhelper";
		createJsonObj(eventType,eventTime,eventID,elementType);
		if(jquery_qustn('.qustn-tooltip-box').length){
					jquery_qustn('.qustn-tooltip-box').hide("",function(){
						jquery_qustn('.qustn-tooltip-box').hide();
					});
				}
	}
	function getDate(){
		return new Date().getTime();
	}
	function createJsonObj(a,b,c,d){
		var obj = new Object();
		obj.userID = user_id;
		obj.enterpriseID = enterprise_id;
		obj.elementID = c;
		obj.elementType = d;
		obj.event = a;
		obj.datetime  = b;
		obj.source = getBrowser();
		obj.URL = getURL();
		obj.language = getBrowserLang();
		obj.userAgent = getBrowserUserAgent();
		obj.platform = getBrowserPlatform();
		jsonString= JSON.stringify(obj);
		logData+=jsonString+',';
	}
	function getBrowser(){
		var ua= navigator.userAgent, tem, 
		M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
		if(/trident/i.test(M[1])){
			tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
			return 'IE '+(tem[1] || '');
		}
		if(M[1]=== 'Chrome'){
			tem= ua.match(/\bOPR\/(\d+)/);
			if(tem!= null) return 'Opera '+tem[1];
		}
		M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
		if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
		return M.join(' ');
	}
	function getURL(){
		return window.location.href;
	}
	function getBrowserLang(){
		return navigator.language;
	}
	function getBrowserUserAgent(){
		return navigator.userAgent;
	}
	function getBrowserPlatform(){
		return navigator.platform;
	}
	function OpenInNewTab(url){
	  var win = window.open(url, '_blank');
	  win.focus();
	}
	/************************************************/
	/*******    AJAX request for reporting    *******/
	/************************************************/
	/*
	var tempQdata = "";
	setInterval(function(){
		tempQdata += logData;
		if(tempQdata != ''){
			logData = '';
			jquery_qustn.ajax({
						url: "http://stage1.qustn.com/qh/report/api/getqhelper.php",
						type: 'POST',
						data: {data:tempQdata, enterprise:enterprise_id, user:user_id},
						success: function (result) {
							console.log(result);
							tempQdata = "";
						},
						error:function(error){
							console.log(error);
						}
					});
		}
	},60000);


	var tempHitdata = "";
	setInterval(function(){
		tempHitdata += logDetails;
		if(tempHitdata != ''){
			logDetails = '';
			jquery_qustn.ajax({
						url: "http://stage1.qustn.com/qh/report/api/gethelperclick.php",
						type: 'POST',
						data: {data:tempHitdata, enterprise:enterprise_id, user:user_id},
						success: function (result) {
							console.log(result);
							tempHitdata = "";
						},
						error:function(error){
							console.log(error);
						}
					});
		}
	},30000);


	var tempHeatdata = "";
	setInterval(function(){
		tempHeatdata += mouseoverDetails;
		if(tempHeatdata != ''){
			mouseoverDetails = '';
			jquery_qustn.ajax({
						url: "http://stage1.qustn.com/qh/report/api/gethelpermousemove.php",
						type: 'POST',
						data: {data:tempHeatdata, enterprise:enterprise_id, user:user_id},
						success: function (result) {
							console.log(result);
							tempHeatdata = "";
						},
						error:function(error){
							console.log(error);
						}
					});
		}
	},120000);
	*/
	/****************************************************/
	/******* drag and drop using jquery draggable *******/
	/****************************************************/
	function clientXqHelper(e){
		var isIE = document.all ? true : false;
		if (!isIE) {
			_x = e.clientX;
		}
		if (isIE) {
			_x = e.clientX + document.body.scrollLeft;
		}
		x=_x-35;
		return(x);
	}
	function clientYqHelper(e){
		var isIE = document.all ? true : false;
		if (!isIE) {
			_y = e.clientY;
		}
		if (isIE) {
			_y = e.clientY + document.body.scrollTop;
		}
		y=_y-35;
		return(y);
	}
	function qustnDragStart(event){
		jquery_qustn('#qustn-helpcontent button').hide();
	}
	function qustnDragStop(e){
		jquery_qustn('#qustn-helpcontent button').show();
		x = clientXqHelper(e);
		y = clientYqHelper(e);
		setDragPosition(x,y);
	}
	function setDragPosition(x,y){
		if(x != "" && y != ''){
			h = window.innerHeight;
			w = window.innerWidth;
			posX = w/2;
			posY = h/2;
			if(x<=posX && y<=posY){
			//1-1
				jquery_qustn('#qustn-helpcontent').addClass('qustn-fab__actions--down').removeClass('qustn-fab__actions--up');
				jquery_qustn('#qustn-helpcontent .qustn-fab-tip').addClass('qustn-fab-tip-right').removeClass('qustn-fab-tip-left');
				jquery_qustn('.qustn-tooltip-box').attr('class', 'qustn-tooltip-box').addClass('qustn-tooltip-box-lt');
			}
			else if(x>=posX && y<=posY){
			//1-2
				jquery_qustn('#qustn-helpcontent').addClass('qustn-fab__actions--down').removeClass('qustn-fab__actions--up');
				jquery_qustn('#qustn-helpcontent .qustn-fab-tip').addClass('qustn-fab-tip-left').removeClass('qustn-fab-tip-right');
				jquery_qustn('.qustn-tooltip-box').attr('class', 'qustn-tooltip-box').addClass('qustn-tooltip-box-rt');
			}
			else if(x<=posX && y>=posY){
			//2-1
				jquery_qustn('#qustn-helpcontent').addClass('qustn-fab__actions--up').removeClass('qustn-fab__actions--down');
				jquery_qustn('#qustn-helpcontent .qustn-fab-tip').addClass('qustn-fab-tip-right').removeClass('qustn-fab-tip-left');
				jquery_qustn('.qustn-tooltip-box').attr('class', 'qustn-tooltip-box').addClass('qustn-tooltip-box-lb');
			}
			else if(x>=posX && y>=posY){
			//2-2
				jquery_qustn('#qustn-helpcontent').addClass('qustn-fab__actions--up').removeClass('qustn-fab__actions--down');
				jquery_qustn('#qustn-helpcontent .qustn-fab-tip').addClass('qustn-fab-tip-left').removeClass('qustn-fab-tip-right');
				jquery_qustn('.qustn-tooltip-box').attr('class', 'qustn-tooltip-box').addClass('qustn-tooltip-box-rb');
			}
		}
	}
}