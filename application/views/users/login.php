    <div class="container">
        <div class="row" id="qlogin">
            <div class="col-md-3">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><img src="<?php echo base_url();?>assets/web_assets/dist/img/logo.png" /></h3>
                    </div>
                    <div class="panel-body">
                        <form class="cmxform" id="signupForm" method="post" action="<?php echo base_url();?>users/login" autocomplete="off" >
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail*" name="email" id="email" type="email" value="<?php echo (isset($_COOKIE['username']) && $_COOKIE['username'] != '') ? trim($_COOKIE['username']) : ''; ?>" autofocus>  
									<?php echo form_error('email', '<div class="error-aleart">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password*" name="pwd" id="pwd" type="password" value="<?php echo (isset($_COOKIE['password']) && $_COOKIE['password'] != '') ? trim($_COOKIE['password']) : ''; ?>">                                
								<?php echo form_error('pwd', '<div class="error-aleart">', '</div>'); ?>
							   </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" id="remember" type="checkbox" checked value="1"> &nbsp;Remember Me
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-sm btn-block">Login</button>
								 <div style="float: left; width: 100%; /*margin-top: 20px; border-top: 1px solid #636161;*/ padding-top: 9px; ">
                                <a href="<?php echo base_url();?>users/register" style="float:right;">Don't you have an account? <span style="color:#20a0ef">Register now</span></a>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php $this->load->view('layout/footer');?>