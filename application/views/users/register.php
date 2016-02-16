   <div class="container">
        <div class="row" id="register">
            <div id="qustn-regbody" class="col-md-3 col-md-offset-5">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><img src="<?php echo base_url();?>assets/web_assets/dist/img/logo.png" /></h3>
                    </div>
                    <div class="panel-body">
                        <form class="cmxform" id="regForm" method="post" action="<?php echo base_url();?>users/register" autocomplete="off" >
                             <fieldset>                
                                <div class="form-group">
                                    <input class="form-control" placeholder="Name*" name="regname" id="regname" type="text" value="<?php echo set_value('regname');?>">
									<?php echo form_error('regname', '<div class="error-aleart">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Enterprise Name*" name="compname" id="compname" type="text" value="<?php echo set_value('compname');?>" > 
									<?php echo form_error('compname', '<div class="error-aleart">', '</div>'); ?>                               
                                </div>
                                <div class="form-group">                                  
                                    <input id="compurl" class="form-control" placeholder="Url* ( http://www.yourdomain.com )" type="text" name="compurl" value="<?php echo set_value('compurl');?>" aria-required="true" aria-invalid="false" data-toggle="tooltip" data-placement="right" data-html="true" title="Please enter the URL on which you want to enable Capabiliti for support">
									<?php echo form_error('compurl', '<div class="error-aleart">', '</div>'); ?>
								    <!-- tooltip -->
									<div id="tooltip" class="talk-bubble tri-right left-in">
									  <div class="talktext">
										<p>Please enter the URL on which you want to enable Capabiliti for support</p>
									  </div>
									</div>
							  </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Email*" name="email" id="email" value="<?php echo set_value('email');?>" type="email"> 
										<?php echo form_error('email', '<div class="error-aleart">', '</div>'); ?>									
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password*" name="regpwd" id="regpwd" value="<?php echo set_value('regpwd');?>" type="password">
										<?php echo form_error('regpwd', '<div class="error-aleart">', '</div>'); ?>									
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Phone" name="regphone" id="regphone" value="<?php echo set_value('regphone');?>" type="text">  
									<?php echo form_error('regphone', '<div class="error-aleart">', '</div>'); ?>									
                                </div>
                                <button type="submit" id="regsubmit" class="btn btn-sm btn-block">Submit</button>
								
 <div style="float: left; width: 100%;    /*margin-top: 20px;border-top: 1px solid #636161;*/ padding-top: 9px;">
                                <a href="<?php echo base_url();?>users/login"" style="float:right;">Already Registered? <span style="color:#20a0ef">Login		</span>
								</a>
                                </div>								
                               
                            </fieldset>
                        </form>
                    </div> 
                </div>
            </div>
        </div>
  
</div>

<?php $this->load->view('layout/footer');?> 