 $(function(){
		
		$.validator.addMethod("check_alphanum", function(value, element) {
			return this.optional(element) || /^[a-z0-9-_ \-]+$/i.test(value);
		}, "This field must contain only letters, numbers,or dashes.");
		$.validator.addMethod("check_valid_url", function(value, element) {
			return this.optional(element) || /^(?:(ftp|http|https):\/\/)?(?:[\w-]+\.)+[a-z]{2,6}$/i.test(value);
		}, "please provide valid url.");
	
			$('#signupForm').validate({
				errorElement: 'div',
				errorClass: 'error-aleart',
				focusInvalid: false,
				rules: {
					 email: {
						required: true,
						email: true
					},
					pwd: {
						required: true,
						minlength: 5,
						remote: {
							type: 'post',
							url:base_url+'users/checkValidLoginDetails',
							data: {
								'email': function () { return $('#email').val(); },
								'pwd': function () { return $('#pwd').val(); }
							},
							dataType: 'json'						
						}
					}
				},
		
				messages: {
					email: {
						required: "Please provide a email.",
						email: "Please provide a valid email."
					},
					pwd: {
						required: "Please provide a password.",
						minlength: "Please provide a password of minimum 5 character.",
						remote:'Email / Password is not correct'
					}
				},
		
				invalidHandler: function (event, validator) { //display error alert on form submit   
					$('.alert-error', $('.login-form')).show();
				},
		
				highlight: function (e) {
					$(e).closest('.control-group').removeClass('info').addClass('error');
				},
		
				success: function (e) {
					$(e).closest('.control-group').removeClass('error').addClass('info');
					$(e).remove();
				},
		
				errorPlacement: function (error, element) {
					if(element.is(':checkbox') || element.is(':radio')) {
						var controls = element.closest('.controls');
						if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
						else error.insertAfter(element.nextAll('.lbl').eq(0));
					} 
					else error.insertAfter(element);
				},
		
				submitHandler: function (form) {
				form.submit();
				},
				invalidHandler: function (form) {
				}
			});
			
			
			 $('#regForm').validate({
				errorElement: 'div',
				errorClass: 'error-aleart',
				focusInvalid: false,
				rules: {
					regname: {
						required: true,
						minlength: 2
					},
					compname: {
						required: true,
						check_alphanum:true,
						minlength: 2
					},
					compurl: {
						required: true,
						check_valid_url: true,
						remote: {
							type: 'post',
							url:base_url+'users/checkDuplicateCompanyUrlByAjax',
							data: {
								'compurl': function () { return $('#compurl').val(); }
							},
							dataType: 'json'						
						}
					},
					email: {
						required: true,
						email: true,
						remote: {
							type: 'post',
							url:base_url+'users/checkDuplicateEmail',
							data: {
								'email': function () { return $('#email').val(); }
							},
							dataType: 'json'						
						}
					},
					regpwd: {
						required: true,
						minlength: 5
					},regphone: 
					{
						number:true						
						
					},
				},
		
				messages: {
					regname: {
						required: "Please provide a name.",
						minlength: "Please provide name of minimum 2 character."
					},
					compname: {
						required: "Please provide a enterprise name.",
						minlength: "Please provide enterprise of minimum 2 character."
					},compurl: {
						required: "Please provide a url.",
						check_valid_url: "Please provide a valid url.",
						remote: "Enterprise domain already in use."
					},email: {
						required: "Please provide a email.",
						email: "Please provide a valid email.",
						remote:"Email is already in use."
					},
					regpwd: {
						required: "Please provide password.",
						minlength: "Please provide password of minimum 5 character."
					},
					regphone: {
						number: "Please provide a numbers only.",
					}
				},
		
				invalidHandler: function (event, validator) { //display error alert on form submit   
					$('.alert-error', $('.login-form')).show();
				},
		
				highlight: function (e) {
					$(e).closest('.control-group').removeClass('info').addClass('error');
				},
		
				success: function (e) {
					$(e).closest('.control-group').removeClass('error').addClass('info');
					$(e).remove();
				},
		
				errorPlacement: function (error, element) {
					
					if(element.is(':checkbox') || element.is(':radio')) {
						var controls = element.closest('.controls');
						if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
						else error.insertAfter(element.nextAll('.lbl').eq(0));
					} 
					else error.insertAfter(element);
				},
		
				submitHandler: function (form) {
				form.submit();
				},
				invalidHandler: function (form) {
				}
			}); 
						
		}); 