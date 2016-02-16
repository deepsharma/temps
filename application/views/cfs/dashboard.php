<!----LOADER---->
<!--<div class="spinner-page"><img src="assets/images/logo-gray.png" alt=""></div>-->
<div id="loader">
    <div id="loader-container-page">
      <p id="loadingText-page">Loading...</p>
    </div>
</div>

<!-- Get Chrome Extension Modal -->
<div class="modal fade" id="getEextension" tabindex="-1" role="dialog" aria-labelledby="getEextensionLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body"> 
      <h1 class="modal-title">Awesome! Here’s how to install your extention.</h1>
      	<ul>
          <li>1. Open Google Chrome and type <a href="chrome://extensions/" target="_blank">chrome://extensions/</a> in the browser</li>
          <li>2. Drag the downloaded file to the above location</li>
          <li>3. Hit <strong>Ctrl</strong>+<strong>Shift</strong>+<strong>R</strong> to refresh</li>
		  <li>3. If you want to disable the extension at a future point in time, go to >More tools > Extensions > Uncheck the box</li>    
        </ul>
        </div>
      <div class="modal-footer"> Perfect! Your extention will get installed. </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<!-- /.modal -->


<div id="wrapper">

<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0" id="top-nav">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
    <a class="navbar-brand" href="<?php echo base_url('dashboard');?>"><img src="<?php echo base_url();?>assets/web_assets/dist/img/logo1.png"></a> </div>
  <!-- /.navbar-header -->
  
  <ul class="nav navbar-top-links navbar-right">
	  <a href="<?php echo base_url(); ?>assets/cfs-extn.crx" target="_blank"><button class="btn btn-primary btn-lg  btn-logout btn-extention" type="submit" data-toggle="modal" data-target="#getEextension"><i class="fa fa-chrome"></i> Get extension</button></a>
	  <a href="<?php echo base_url();?>users/logout"><button class="btn btn-primary btn-lg  btn-logout" type="submit"><i class="fa fa-sign-out fa-fw"></i> Logout</button></a>
  </ul>
  <!-- /.navbar-top-links -->
  
  <div class="navbar-default sidebar" role="navigation" id="lsb-tree">
    <div class="sidebar-nav navbar-collapse">
      <ul class="nav">
        <li class="sidebar-title">
          <h3><i class="fa fa-database"></i> Index</h3>
        </li>
        <li class="nav nav-sidebar course-action">
		<a href="javascript:void(0);" class="add-mod" title="Create Module"><i class="fa fa-folder-open"></i><!--<img src="<?php //echo base_url();?>assets/web_assets/dist/img/new module.png">--></a>
		<a href="javascript:void(0);" class="add-sub-mod" title="Create Sub Module"><i class="fa fa-file-text"></i><!--<img src="<?php //echo base_url();?>assets/web_assets/dist/img/new lesson.png">--></a>
		<a href="javascript:void(0);" class="delete-module" title="Delete Module"><i class="fa fa-trash"></i><!--<img src="<?php //echo base_url();?>assets/web_assets/dist/img/delete module_lesson.png">--></a>
		</li>
	  </ul>
	  <ul class="nav" id="side-menu">
		 <?php
		 $i=$k=0;
		if(!empty($allModules->user_repository))
		{
			
			//$allModules->user_repository=array_reverse($allModules->user_repository);
			foreach($allModules->user_repository as $leftModuleValues)
			{
				$i++;
		?>
			<li <?php echo ($i==1)?'class="active"':''?>>
					<a href="javascript:void(0);" class="loadModule" id="moduleHead-<?php echo $i;?>" data-module="<?php echo $leftModuleValues->id;?>" data-js-module="<?php echo $i;?>">
						<i class="fa fa-folder-open fa-fw"></i>
						<span class="_first"><?php echo $string = (strlen($leftModuleValues->title) > 13) ? substr($leftModuleValues->title,0,12).'...' : $leftModuleValues->title;
						  ?></span>
						<span class="fa arrow"></span>
					</a>	
					<ul id="moduleUL-<?php echo $i;?>" class="nav nav-second-level">
						<?php
					  if(!empty($leftModuleValues->courses))
					  {
						  $j=0;
						  foreach($leftModuleValues->courses as $leftLessionValue)
						  {  
						  
							 ?>
						<li>
							<a href="javascript:void(0);" id="moduleSubHead-<?php echo $i;?>-<?php echo $j;?>"><i class="fa fa-file-text"></i>
							<span class="_first"><?php echo isset($leftLessionValue->course->title)?$leftLessionValue->course->title:''; ?></span></a> 
						</li>
						<?php
						$j++;
						  }
						
					  }
					  ?>
					</ul>					
			</li>
			
		
		<?php $k++;}
		
		}else{ ?>
		
				<div id="no-module" class="text-center">
				<h3 class="index-pane-title"></h3>
				<p class="index-pane">Course structure<br/>will be created and shown here.</p>
				</div>	
		<?php
		}
		?>
		
      </ul>
    </div>
	<input type="hidden" id="moduleCount" value="<?php echo $k;?>"> <!-- To count module -->
    <!-- /.sidebar-collapse --> 
  </div>
  <!-- /.navbar-static-side --> 
</nav>
<div id="page-wrapper">
  <div class="row pad-zero boxes">
    <div class="col-lg-7 pad-zero">
      <div class="panel panel-default border-zero">
        <div class="panel-heading sidebar-title">
          <h3><i class="fa fa-pencil-square-o"></i> Creator</h3>
        </div>
        <!-- /.panel-heading -->
		
		<div id="main-container" class="row pad-zero">
		<?php
		if(empty($allModules->user_repository))
		{
			//$allModules->user_repository=array_reverse($allModules->user_repository);
		?>
				<div class="panel-body center-pan text-center">
				  <h1 class="mr-top-90">Hello!</h1><p>Start by adding your first module here which will reflect on the left pane.</p>
				   <button type="button" class="btn btn-primary btn-lg add-nm add-mod">Add new module</button>
				</div>
			
			<?php } else{ ?>
			<!-- module content area -->
			
					<div id="add-new-module" class="panel-body center-pan">
					  <div class="row pad-zero">
						<div class="page-header">
							<input type="text" class="form-control" data-module="1" id="moduleTitle" value="<?php echo $allModules->user_repository[0]->title;?>" placeholder="Untitled Module">
							<input type="hidden" value="<?php echo $allModules->user_repository[0]->id;?>" id="moduleId">
							<input type="hidden" value="<?php echo count($allModules->user_repository[0]->courses);?>" id="subModuleCount">
						</div>
					  </div>
					  <div class="row mod-tag-description">
						<div class="form-inline">
						  <div class="form-group">
							<label for="moduleDescription">Description</label>
							<input type="text" class="form-control" id="moduleDescription" value="<?php echo $allModules->user_repository[0]->description;?>" placeholder="Enter Module Description">
						  </div>
						</div>
					  </div>
					  <div class="row mod-tag-description tag-wrapper" style="display:none;">
						<div class="form-inline">
						  <div class="form-group">
							<label for="moduleTag">Tag</label>
							<input type="text" class="form-control add-tag" placeholder="Enter Module Tag">
							<input type="hidden" value="<?php echo $allModules->user_repository[0]->tags;?>" id="moduleTag" class="form-control all-tags">
						  </div>
						  <div class="tag tag-container">
								<?php 
								$tags=explode(',',$allModules->user_repository[0]->tags);
								foreach($tags as $tagvalue)
								{
										if($tagvalue)
										{ 
									?>
											<span><?php echo $tagvalue; ?><a href="#" class="remove-tag"><i class="fa fa-close"></i></a></span>
								<?php   }
								} ?>
						  </div>
						</div>
					  </div>
					  <div class="seprator-dashed"></div>
					  <!-- subModule container -->
					  <div class="panel-group" id="accordion">
						
						 <?php
						 $lession_counter=0;
						 foreach($allModules->user_repository[0]->courses as $lessionValues)						 
						 {	
						  ?>
						<!-- loop start -->
						<div class="panel panel-default" data-module="<?php echo  $lession_counter; ?>">
							<div class="panel-heading row pad-zero">
								<div class="sub-mod-title col-md-10">
									<input type="text" class="form-control panel-title" data-submodule="<?php ?>" id="subModuleTitle-<?php echo  $lession_counter; ?>" placeholder="Untitled Sub module" value="<?php echo $lessionValues->course->title;?>">
								</div>
								<div class="col-md-2 text-right">
									<button type="button" class="btn btn-info btn-circle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne-<?php echo  $lession_counter; ?>" aria-expanded="false"><i class="fa fa-chevron-down"></i></button>
									<button type="button" class="remove-me btn btn-danger btn-circle" data-submodule="<?php echo  $lession_counter;?>"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div id="collapseOne-<?php echo  $lession_counter;?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
								<div class="panel-body">
									<div class="row mod-tag-description">
										<div class="form-inline">
										  <div class="form-group">
											<label for="subModuleDescription-<?php ?>">Description</label>
											<input type="text" class="form-control" id="subModuleDescription-<?php echo  $lession_counter; ?>" placeholder="Enter Sub Module Description" value="<?php echo $lessionValues->course->description;?>">
										  </div>
										</div>
									  </div>
									<div class="row mod-tag-description tag-wrapper">
										<div class="form-inline">
										  <div class="form-group">
											<label for="subModuleTag-<?php echo  $lession_counter;?>">Tag</label>
											<input type="text" class="form-control add-tag" placeholder="Enter Sub Module Tag">
											<input type="hidden" id="subModuleTag-<?php ?>" class="form-control all-tags">
										  </div>
										  <div class="tag tag-container">
										  <?php 
											$lessionTags=explode(',',$lessionValues->course->tags);
											foreach($lessionTags as $lessionTagValue)
											{
												if($lessionTagValue)
												{	
											?>
												<span><?php echo $lessionTagValue; ?><a href="#" class="remove-tag"><i class="fa fa-close"></i></a></span>
											<?php
												}
											}
										?>
										  </div>
										</div>
									  </div>
									  <div class="seprator-dashed"></div>
									  <div class="row mod-tag-description">
										<div class="form-inline">
										  <label for="subModuleArtifacts-<?php ?>" class="col-md-2 pad-zero">Media</label>
										  <div class="col-md-10">
											<!--<div class="thumbnail drag-placeholder gallery"> <img src="dist/img/lib-thumb1.jpg" alt="..."></div>
											<div class="thumbnail drag-placeholder gallery"> <img src="dist/img/lib-thumb3.jpg" alt="..."></div>-->
											<?php
											if($lessionValues->course->artifacts->video != ""){
											?>
												<div class="thumbnail drag-placeholder gallery" data-type="video">
												<button type="button" class="remove-media btn btn-danger btn-circle"><i class="fa fa-times"></i></button>
												<span class="item-added" data-type="video" data-artifact="<?php echo $lessionValues->course->artifacts->video; ?>">
												<img src="<?php echo base_url();?>assets/web_assets/dist/img/mp4.png"></span>
												</div>
											<?php
											}
											else{
												?>
											<div class="thumbnail drag-placeholder gallery" data-type="video"> <i class="fa fa-file-video-o"></i></div>
												<?php
											}
											if($lessionValues->course->artifacts->pdf != ""){
												?>
												
												
												<div class="thumbnail drag-placeholder gallery" data-type="pdf">
												<button type="button" class="remove-media btn btn-danger btn-circle"><i class="fa fa-times"></i></button>
												<span class="item-added" data-type="pdf" data-artifact="<?php echo $lessionValues->course->artifacts->pdf; ?>">
												<img src="<?php echo base_url();?>assets/web_assets/dist/img/pdf.png"></span>
												</div>
											<?php
											}
											else{
												?>
											<div class="thumbnail drag-placeholder gallery" data-type="pdf"> <i class="fa fa-file-pdf-o"></i></div>
												<?php
											}
											?>
										  </div>
										  <input type="hidden" name="video-<?php echo  $lession_counter; ?>" id="subModuleVideo-<?php echo  $lession_counter;?>" value="<?php echo $lessionValues->course->artifacts->video;?>">
										  <input type="hidden" name="pdf-<?php echo  $lession_counter;?>" id="subModulePdf-<?php echo  $lession_counter; ?>" value="<?php echo $lessionValues->course->artifacts->pdf;?>">
										</div>
									  </div>
								</div>
								<div class="panel-footer text-center"><i class="fa fa-object-ungroup"></i> Drop files from the Media Pane to attach them to this lesson</div>
							</div>
						</div>
					   <?php
					    $lession_counter++;
						}
					?>
					</div>
					  <!-- subModule container closed -->
					  <button type="button" class="btn btn-outline btn-default add-sub-mod"><i class="fa fa-plus-circle"></i> Add new sub module</button>
					  <div class="seprator-dashed">
					  </div>
					  <button id="save-progress" class="btn btn-primary btn-lg btn-save" type="submit"><i class="fa fa-save"></i>Save Module</button>
					  <button id="saved" class="btn btn-primary btn-lg  btn-save smod" type="submit"><i class="fa fa-check"></i>Module Saved</button>
					</div>
					<!-- /.panel-body -->
			
			<!-- module content area -->
			
			
			<?php }?>
		</div>
		<!-- /.panel-body -->
      </div>
      <!-- /.panel --> 
    </div>
    <!-- /.col-lg-8 -->
    <div class="col-lg-5 pad-zero" id="library">
      <div class="panel panel-default border-zero">
        <div class="panel-heading sidebar-title">
          <h3><i class="fa fa-picture-o"></i> Media</h3>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
          <div class="row sidebar-search border-bot-1 pad-zero">
            <div class="input-group custom-search-form search-shell col-xs-8 ">
              <input type="text" class="form-control search" placeholder="Type to Search...">
              <span class="input-group-btn">
              <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
              </span> </div>
            <!-- /input-group -->
            <div class="col-xs-4 lib-action pad-zero">
			  <span class="list-tile-btn">
				<button class="btn btn-default" type="button"> <i class="fa fa-th-large"></i> </button>
              </span>
			  <span class="upload-btn">
				<button class="btn btn-default" type="button"> <i class="fa fa-cloud-upload"></i> </button>
              </span>
              <div class="pull-right">
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-filter"></i><i class="caret"></i> </button>
                  <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="javascript:;" class="filter-pdf"><i class="fa fa-file-pdf-o"></i> PDF</a> </li>
                    <li class="divider"></li>
                    <li><a href="javascript:;" class="filter-video"><i class="fa fa-file-video-o"></i> Video</a> </li>
					<li class="divider"></li>
                    <li><a href="javascript:;" class="filter-all"><i class="fa fa-retweet"></i> All</a> </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
		  
		  <div id="media-loader" class="row text-center">
			  <div class="loader-shell" type="submit">
				<!--<div class="progress"></div>-->
				<div class="spinner">
				  <div class="ball"></div>
				  <p>Files uploading is in process</p>
				</div>
			  </div>
		  </div>
		  <!-- initial dropzone
		   #dropzone -->
		  <div id="dropzone" class="row lib-articles text-center" style="<?php  if(!empty($allMedia) && !empty((array)$allMedia->artifacts)){ echo 'display:none'; }else{ echo 'display:block';} ?>">
			 <input type="file" name="userMedia[]" id="filer_input" multiple="multiple">
		  </div>
		   <!-- / #dropzone
		   initial dropzone -->
		   
		  <!-- #mediaContainer -->
          <div id="mediaContainer" class="row lib-articles" style="<?php  if(!empty($allMedia) && !empty((array)$allMedia->artifacts)){ echo 'display:block'; }else{ echo 'display:none';} ?>">
            <div id="gridView">
			<?php 
			if(!empty($allMedia) && !empty((array)$allMedia->artifacts))
			{
				//echo "<pre>";print_r($allMedia);die;
				foreach($allMedia->artifacts as $mediakey=> $mediaData)
				{
					
				?>
					<div class="col-sm-6 col-md-3 media-elm"  title="<?php echo $mediaData->title;?>">
					  <button type="button" class="delete-media btn-delete btn btn-danger btn-circle" data-artifact="<?php echo $mediakey;?>"><i class="fa fa-times"></i></button>
					  <div class="thumbnail text-center" id="artifact-<?php echo $mediakey;?>" >						
						<?php
						if($mediaData->type=='pdf')
						{
						?>
							<span class="item" data-type="pdf" data-artifact="<?php echo $mediakey;?>" data-name="<?php echo $mediaData->title;?>" data-size="<?php echo isset($mediaData->size)?$mediaData->size:'';?>" data-time="<?php echo date('M d',isset($mediaData->createdTime)?$mediaData->createdTime:time());?>" data-tags="<?php echo isset($mediaData->tags)?$mediaData->tags:'';?>" ><img src="<?php echo base_url();?>assets/web_assets/dist/img/pdf.png"></span>
						<?php						
						}else if($mediaData->type=='video')
						{
							?>
							<span class="item" data-type="video" data-artifact="<?php echo $mediakey;?>" data-name="<?php echo $mediaData->title;?>" data-size="<?php echo isset($mediaData->size)?$mediaData->size:'';?>" data-time="<?php echo date('M d',isset($mediaData->createdTime)?$mediaData->createdTime:time());?>" data-tags="<?php echo isset($mediaData->tags)?$mediaData->tags:'';?>">
							<img src="<?php echo base_url();?>assets/web_assets/dist/img/mp4.png"></span>
							<?php
						}
						?>
						<div class="caption">
						  <h5 title="<?php echo $mediaData->title;?>" ><?php echo $string = (strlen($mediaData->title) > 13) ? substr($mediaData->title,0,10).'...' : $mediaData->title;
						  ?>
						  </h5>
						<!--  <p><?php //echo date('M d',isset($mediaData->createdTime)?$mediaData->createdTime:time());?>, <?php //echo $mediaData->size;?></p>-->
						</div>
					  </div>
					</div>
				<?php				
				}
			?>
				
			<?php			
			}
			
			?>
			
			</div>
		   
		   <!-- /#gridView -->
           <div id="no-data" class="text-center">
		   <p><strong>None of your files or folders matched this search.</strong></p>
		   <p>Try another search, or click the arrow in the search box to find a file by type, owner and more.</p>
		   </div><!-- /#mediaContainer -->
		   <!-- Artifacts meta data  -->
		   
		   <div class="file-pro-shell">
			  <button type="button" class="close" aria-hidden="true">×</button>
				<div class="row">
					<div class="form-inline">
						<label for="artifactName">Name</label>
						<input type="text" class="form-control" id="artifactName" placeholder="Enter File Name">
						<input type="hidden" value="" id="artifactId">  
					</div>
					<div class="form-inline">
						<label for="artifactTag">Uploaded</label>
						<label class="createdDate"><?php echo date('M d'); ?></label>
					</div>
					<div class="form-inline">
						<label for="artifactTag">Size</label>
						<label class="size">2.7 MB</label>
					</div>
					<div class="tag-wrapper">
						<div class="form-inline">
						  <div class="">
							<label for="artifactTag">Tag</label>
							<input type="text" class="form-control add-tag" placeholder="Enter Module Tag">
							<input type="hidden" id="artifactTag" class="form-control all-tags">
						  </div>
						  <div class="tag tag-container">
						  </div>
						</div>
					</div>
				 </div>			  
			</div>
		   
		   <!-- Artifacts meta data ends -->
		   
          </div>
        </div>
        <!-- /.panel-body --> 
      </div>
      <!-- /.panel --> 
      <!-- /.col-lg-4 --> 
    </div>
    <!-- /.row --> 
  </div>
  <!-- /#page-wrapper --> 
  
</div>

</div>
<!-- /#wrapper -->

<!-- dynamic form element -->
<div id="moduleForm"></div>

<?php $this->load->view('layout/footer');?>