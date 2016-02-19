    <div class="container">
        <div class="row" id="qlogin">
            <div class="col-md-3">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><img src="<?php echo base_url();?>assets/web_assets/dist/img/logo.png" /></h3>
                    </div>
                    <div class="panel-body">
                        <form class="cmxform" id="signupForm" method="post" action="<?php echo base_url();?>users/forgotPassword" autocomplete="off" >
                            <fieldset>
							<?php echo $this->session->flashdata('flashData');?>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail*" name="email" id="email" type="email" value="<?php echo set_value('email');?>" autofocus>  
									<?php echo form_error('email', '<div class="error-aleart">', '</div>'); ?>
                                </div>
                                <button type="submit" class="btn btn-sm btn-block">Get Password</button>
								
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php $this->load->view('layout/footer');?>