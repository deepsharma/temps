    <div class="container">
        <div class="row" id="qlogin">
            <div class="col-md-3">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><img src="<?php echo base_url();?>assets/web_assets/dist/img/logo.png" /></h3>
                    </div>
                    <div class="panel-body">
                        <form class="cmxform" id="changepassword" method="post" action="<?php echo base_url();?>users/resetPassword/<?php echo $token.'/'.$email;?>" autocomplete="off" >
                            <fieldset>
							<input type="hidden" name="email" value="<?php echo $email;?>">
							<input type="hidden" name="token" value="<?php echo $token;?>">
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" id="password" type="password" value="" autofocus>  
									<?php echo form_error('password', '<div class="error-aleart">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password Confirm*" name="pwd_confirm" id="pwd_confirm" type="password" value="">                                
								<?php echo form_error('pwd', '<div class="error-aleart">', '</div>'); ?>
							   </div>
                                <button type="submit" class="btn btn-sm btn-block" style="width: 132px;" >Change password</button>
								
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php $this->load->view('layout/footer');?>