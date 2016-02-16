<!-- jQuery --> 
<script src="<?php echo base_url();?>assets/web_assets/bower_components/jquery/dist/jquery.min.js"></script> 

<!-- jquery ui -->
<script src="<?php echo base_url();?>assets/web_assets/js/jquery-ui.min.js"></script>

<!-- Bootstrap Core JavaScript --> 
<script src="<?php echo base_url();?>assets/web_assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 

<!-- Metis Menu Plugin JavaScript --> 
<script src="<?php echo base_url();?>assets/web_assets/bower_components/metisMenu/dist/metisMenu.min.js"></script> 

<!-- jQuery upload -->
<script type="text/javascript" src="<?php echo base_url();?>assets/web_assets/js/jquery.filer.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/web_assets/js/filer.js"></script>

<!-- Custom Theme JavaScript --> 
<script src="<?php echo base_url();?>assets/web_assets/dist/js/sb-admin-2.js"></script>

<!-- custom jQuery --> 
<script src="<?php echo base_url();?>assets/web_assets/js/script.js"></script> 

<!-- Custom Scrollbars JavaScript --> 
<!--<script>
		$("#lib-files, #center-pan").addClass("thin");
		// If user has Javascript disabled, the thick scrollbar is shown
		$("#lib-files, #center-pan").mouseover(function(){
		  $(this).removeClass("thin");
		});
		$("#lib-files, #center-pan").mouseout(function(){
		  $(this).addClass("thin");
		});
		$("#lib-files, #center-pan").scroll(function () {
		  $("#lib-files, #center-pan").addClass("thin");
		});
</script>   -->

<script src="<?php echo base_url();?>assets/web_assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url();?>assets/web_assets/js/additional-methods.min.js"></script>
<script> var base_url='<?php echo base_url();?>';</script>
<script src="<?php echo base_url();?>assets/web_assets/dist/js/js_function.js" type="text/javascript"></script>
 <script>		
	var containerY = $(window).height()-0;
	$('.container').height(containerY);
	$('#qustn-regbody').height(containerY);
	$(document).on('focus', "#compurl" , function(e) {
       $('#tooltip').fadeIn();
	});
	$(document).on('blur', "#compurl" , function(e) {
		   $('#tooltip').fadeOut();
	});
</script>	
</body>
</html>