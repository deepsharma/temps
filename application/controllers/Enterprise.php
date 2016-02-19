<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enterprise extends CI_Controller {

	public $mediaFilePath;
	public $mediaFileBasePath;
	public $enterprise;
	function __construct() {
        parent::__construct();
			/*if(!$session)
			{
				redirect(base_url()."users/login");
			}*/
		$session=$this->session->userdata('logged_in');
		$this->enterprise=$session['enterpriseurl'];
		$this->mediaFilePath=FCPATH . "assets/enterprise/".$this->enterprise."/json/artifacts.json";
	    $this->mediaFileBasePath=base_url() . "assets/enterprise/".$this->enterprise."/json/artifacts.json";		
		$this->courseFilePath=FCPATH . "assets/enterprise/".$this->enterprise."/json/course.json";
	    $this->courseFileBasePath=base_url() . "assets/enterprise/".$this->enterprise."/json/course.json";
		$this->load->library('commonfunctions');	
		$this->commonfunctions->checkDirectoryStructure($this->enterprise); // check directory structure
		
    }
	
		
	public function index()
	{
		show_404();
		//$this->load->view('cfs');
	}
	
	public function artifacts()
	{ 
		//$_POST['action']='delete';
		if(empty($_POST))
		{
			$result['msg']='Bad request!';
			$returnData['response']=$result;
			die(json_encode($returnData));
		}
	    $data=$_POST;
		$action=$data['action'];
		switch ($action) {
		case 'add':
			$this->add_media($data);
			break;
		case 'update':
			$this->update_media($data);
			break;
		case 'delete':
			$this->delete_media($data);
			break;
		case 'get_media':
			$this->get_media($data);
			break;
		default:
			$result['msg']='Bad request!';
			$returnData['response']=$result;
			die(json_encode($returnData));
		}
		
	}
	
	public function update_media($data)
	{
		
		if(empty($data['media_id']))
		{
			$result['msg']='Media id is required!';
			$returnData['response']=$result;
			die(json_encode($returnData));
		}
		if(file_exists($this->mediaFilePath))
		{
			$file=file_get_contents($this->mediaFileBasePath);
			if(!$file){
				$result['msg']='Empty media content !';
					$returnData['response']=$result;
					die(json_encode($returnData));
			}else{
				$mediaArray = array();
				$mediaArray=json_decode($file);
				if (array_key_exists($data['media_id'],$mediaArray->artifacts))
				{
					foreach($mediaArray->artifacts->$data['media_id'] as $key => $value)
					{
							if(isset($data[$key]))
							{
								$mediaArray->artifacts->$data['media_id']->$key=$data[$key];
							}
					}
					file_put_contents($this->mediaFilePath,json_encode($mediaArray));
					$result['msg']='Success!';
					$returnData['response']=$result;
					die(json_encode($returnData));
				}
				else{
					$result['msg']='Invalid media id !';
					$returnData['response']=$result;
					die(json_encode($returnData));
				}
			}
		}else{
			$result['msg']='Failed to find media file!';
			$returnData['response']=$result;
			die(json_encode($returnData));
		}
		
	}
	
	public function delete_media($data)
	{
		if(empty($data['media_id']))
		{
			$result['msg']='Media id is required!';
			$returnData['response']=$result;
			die(json_encode($returnData));
		}	
		if(file_exists($this->mediaFilePath))
		{
			$file=file_get_contents($this->mediaFileBasePath);
			if(!$file){
					$result['msg']='Empty media content !';
					$returnData['response']=$result;
					die(json_encode($returnData));
			}else{
				$mediaArray = array();
				$mediaArray=json_decode($file);
				if (array_key_exists($data['media_id'],$mediaArray->artifacts))
				{
					/* delete media artifacts log*/
					$artifactsList=FCPATH . "assets/deteled_media.txt"; // deleted artifacts log file
					$filePath=$this->enterprise.'/'.$mediaArray->artifacts->$data['media_id']->type.'/'.$mediaArray->artifacts->$data['media_id']->path;
					if(file_exists($artifactsList))
					{
						$file=file_get_contents($artifactsList);
						if(!$file)
							{
								file_put_contents($artifactsList,$filePath);
							}else
							{
								$file=$file.','.$filePath;
								file_put_contents($artifactsList,$file);
							}
					}else
					{
						file_put_contents($artifactsList,$filePath);
					}
					/* delete media artifacts log*/
					
					unset($mediaArray->artifacts->$data['media_id']); // remove the media from file
					file_put_contents($this->mediaFilePath,json_encode($mediaArray));
					$result['msg']='Success !';
					$result['status']='1';
					$returnData['response']=$result;
					$this->updateCourseArtifacts($data);
					
					
					die(json_encode($returnData));
				}
				else{
					
					$result['msg']='Invalid media id !';
					$result['status']='0';
					$returnData['response']=$result;
					die(json_encode($returnData));
				}
			}
		}else{
			$result['msg']='Failed to find media file!';
			$result['status']='0';
			$returnData['response']=$result;
			die(json_encode($returnData));
			
		}
	}
	// function to remove artifacts assigned to any artifacts if delete artifacts
	public function updateCourseArtifacts($data)
	{
		
		if(empty($data['media_id']))
		{
			$result['msg']='media_id field is required';
			$returnData['response']=$result;
			return false;
		}	
		if(file_exists($this->courseFilePath))
		{
			$file=file_get_contents($this->courseFileBasePath);
			if(!$file){
				$result['msg']='Empty Course content !';
				$returnData['response']=$result;
				return false;
			}else{
				$courseArray = array();
				$courseArray=json_decode($file);
				$success=0;
				if(isset($courseArray->user_repository))
				{
					foreach($courseArray->user_repository as $coursekey=>$coursevalue)
						{
							if(isset($coursevalue->courses))
							{
								foreach($coursevalue->courses as $lessionkey=>$lessionValues)
								{
									if(isset($lessionValues->course->artifacts->video) && ($lessionValues->course->artifacts->video==$data['media_id']))
									{
										$courseArray->user_repository[$coursekey]->courses[$lessionkey]->course->artifacts->video='';
									}
									if(isset($lessionValues->course->artifacts->pdf) && ($lessionValues->course->artifacts->pdf==$data['media_id']))
									{
										$courseArray->user_repository[$coursekey]->courses[$lessionkey]->course->artifacts->pdf='';
									}
								}
							}
							
						}
						file_put_contents($this->courseFilePath,json_encode($courseArray));
						return true;
				}	
			}
		}else{
			$result['msg']='Failed to find course file!';
			$returnData['response']=$result;
			return false;
			
		}
		
	}
	
	public function get_media($data)
	{
		
		if(empty($data['media_id']))
		{
			$result['msg']='Media id is required!';
			$returnData['response']=$result;
			die(json_encode($returnData));
		}	
		if(file_exists($this->mediaFilePath))
		{
			$file=file_get_contents($this->mediaFileBasePath);
			if(!$file){
					$result['msg']='Empty media content !';
					$returnData['response']=$result;
					die(json_encode($returnData));
			}else{
				$mediaArray = array();
				$mediaArray=json_decode($file);
				if (array_key_exists($data['media_id'],$mediaArray->artifacts))
				{
					$data=$mediaArray->artifacts->$data['media_id'];
					$data->createdTime=date('M d',strtotime($data->createdTime));
					$result['msg']='Success !';
					$result['status']='1';
					$result['detail']=$data;
					$returnData['response']=$result;
					die(json_encode($returnData));
				}
				else{
					
					$result['msg']='Invalid media id !';
					$result['status']='0';
					$result['detail']=array();
					$returnData['response']=$result;
					die(json_encode($returnData));
				}
			}
		}else{
			$result['msg']='Failed to find media file!';
			$returnData['response']=$result;
			die(json_encode($returnData));
			
		}
	}
	
	public function clean($string) 
	{
		   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		   return preg_replace('/[^A-Za-z0-9_\-]/', '', $string); // Removes special chars.
	}
	public function add_media($data)
	{
			if(empty($_FILES))
			{
				$result['msg']='Empty media list';
				$returnData['response']=$result;
				die(json_encode($returnData));
			}
			$success=$failure=0;
			$successArray=$failureArray=array();
			$this->load->library('upload');
			$files = $_FILES;
			//echo "<pre>";print_r($_FILES);die;
			$cpt = count($_FILES['userMedia']['name']);
			for($i=0; $i<$cpt; $i++)
			{           
				$_FILES['userMedia']['name']= $files['userMedia']['name'][$i];
				$_FILES['userMedia']['type']= $files['userMedia']['type'][$i];
				$_FILES['userMedia']['tmp_name']= $files['userMedia']['tmp_name'][$i];
				$_FILES['userMedia']['error']= $files['userMedia']['error'][$i];
				$_FILES['userMedia']['size']= $files['userMedia']['size'][$i];
				$path_parts = pathinfo($_FILES['userMedia']['name']);
				$ext =$path_parts['extension'];
				$myfile=md5($path_parts['filename'].time());
				$filename=$myfile.'.'.$ext;
				$enterpriseFile=$this->enterprise.'_'.$filename;
				$this->upload->initialize($this->set_upload_options(($ext=='pdf')?$filename:$enterpriseFile,$ext));
				$this->upload->do_upload();
				if ( ! $this->upload->do_upload('userMedia'))
				{
					$failureArray['details'][$failure]['name']=$_FILES['userMedia']['name'];
					$failureArray['details'][$failure]['reason']=strip_tags($this->upload->display_errors());
					$failure++;
				}else{
					
					$art_data['artifacts'][$i]['path']=($ext=='pdf')?$filename:$myfile.'.mp4';
					$art_data['artifacts'][$i]['url']='';
					$art_data['artifacts'][$i]['type']=($ext=='pdf')?'pdf':'video';
					$art_data['artifacts'][$i]['total_video']='';
					$art_data['artifacts'][$i]['video_dir']=$this->enterprise.'/video/';
					$art_data['artifacts'][$i]['window']='newtab/iframe';
					//$art_data['artifacts'][$i]['title']=$_FILES['userMedia']['name'];
					$art_data['artifacts'][$i]['title']=$this->clean($path_parts['filename']);
					$art_data['artifacts'][$i]['description']='';
					$art_data['artifacts'][$i]['tags']='';					
					$art_data['artifacts'][$i]['size']=$_FILES['userMedia']['size'];					
					//$successArray['details'][$success]['name']=$_FILES['userMedia']['name'];
					$successArray['details'][$success]['name']=$this->clean($path_parts['filename']);
					$successArray['details'][$success]['reason']='';
					$success++;
				} 
			}
			
		
		if(file_exists($this->mediaFilePath))
		{
			$file=file_get_contents($this->mediaFileBasePath);
			if(!$file)
			{
				
				if(!empty($art_data['artifacts']))
				{
						
						$last_key=0;
						$i=0;
						foreach($art_data['artifacts'] as $key=>$artifactsData)
						{
							$time=(string)time();
							$i++;
							$new_key=str_pad($last_key+$i, 8, "0", STR_PAD_LEFT);
							$appendArray[$new_key]=array('path'=>$artifactsData['path'],'url'=>$artifactsData['url'],
											'type'=>$artifactsData['type'],'total_video'=>$artifactsData['total_video'],
											'video_dir'=>$artifactsData['video_dir'],'window'=>$artifactsData['window'],
											'title'=>$artifactsData['title'],'description'=>$artifactsData['description'],
											'tags'=>$artifactsData['tags'],'size'=>number_format($artifactsData['size']/(1024*1024),2).' MB','createdTime'=>$time);
							$mediaArray['artifacts']=$appendArray;
							
							if($success>0)
							{
								$result['status']=1;
								$result['msg']='success';
								$result['detail']['path']=$artifactsData['path'];
								$result['detail']['id']=$new_key;
								$result['detail']['title']=$artifactsData['title'];
								$result['detail']['size']=number_format($artifactsData['size']/(1024*1024),2).' MB';
								$result['detail']['type']=$artifactsData['type'];
								$result['detail']['tags']=$artifactsData['tags'];
								$result['detail']['createdTime']=date('M d',$time);
								$returnData['response']=$result;
								file_put_contents($this->mediaFilePath,json_encode($mediaArray));
								die(json_encode($returnData));
								
							}
						}			
						
				}
				else{
					
						$result['status']=0;
						$result['msg']=$failureArray['details'][0]['reason'];
						$result['detail']['path']='';
						$result['detail']['id']='';
						$result['detail']['title']=$failureArray['details'][0]['name'];
						$result['detail']['size']='';
						$result['detail']['type']='';
						$result['detail']['tags']='';
						$result['detail']['createdTime']='';
						$returnData['response']=$result;
						die(json_encode($returnData));
					
				}
				
				
				/* if file is blank /*/
				
					
			}else
			{
				if(!empty($art_data['artifacts']))
				{
						$mediaArray = array();
						$mediaArray=json_decode($file);
						end($mediaArray->artifacts);
						$last_key= key($mediaArray->artifacts);
						$i=0;
						foreach($art_data['artifacts'] as $key=>$artifactsData)
						{
							$time=(string)time();
							$i++;
							$new_key=str_pad($last_key+$i, 8, "0", STR_PAD_LEFT);
							$appendArray=array('path'=>$artifactsData['path'],'url'=>$artifactsData['url'],
											'type'=>$artifactsData['type'],'total_video'=>$artifactsData['total_video'],
											'video_dir'=>$artifactsData['video_dir'],'window'=>$artifactsData['window'],
											'title'=>$artifactsData['title'],'description'=>$artifactsData['description'],
											'tags'=>$artifactsData['tags'],'size'=>number_format($artifactsData['size']/(1024*1024),2).' MB','createdTime'=>$time);
							$mediaArray->artifacts->$new_key=$appendArray;
							
							if($success>0)
							{
								$result['status']=1;
								$result['msg']='success';
								$result['detail']['path']=$artifactsData['path'];
								$result['detail']['id']=$new_key;
								$result['detail']['title']=$artifactsData['title'];
								$result['detail']['size']=number_format($artifactsData['size']/(1024*1024),2).' MB';
								$result['detail']['type']=$artifactsData['type'];
								$result['detail']['tags']=$artifactsData['tags'];
								$result['detail']['createdTime']=date('M d',$time);
								$returnData['response']=$result;
								file_put_contents($this->mediaFilePath,json_encode($mediaArray));
								die(json_encode($returnData));
								
							}
						}
						
						
				}else{
					
						$result['status']=0;
						$result['msg']=$failureArray['details'][0]['reason'];
						$result['detail']['path']='';
						$result['detail']['id']='';
						$result['detail']['title']=$failureArray['details'][0]['name'];
						$result['detail']['size']='';
						$result['detail']['type']='';
						$result['detail']['tags']='';
						$result['detail']['createdTime']='';
						$returnData['response']=$result;
						die(json_encode($returnData));
					
				}
				
				
			}
		}else{
			
			if(!empty($art_data['artifacts']))
				{
						
						$last_key=0;
						$i=0;
						foreach($art_data['artifacts'] as $key=>$artifactsData)
						{
							
							
							$time=(string)time();
							$i++;
							$new_key=str_pad($last_key+$i, 8, "0", STR_PAD_LEFT);
							$appendArray[$new_key]=array('path'=>$artifactsData['path'],'url'=>$artifactsData['url'],
											'type'=>$artifactsData['type'],'total_video'=>$artifactsData['total_video'],
											'video_dir'=>$artifactsData['video_dir'],'window'=>$artifactsData['window'],
											'title'=>$artifactsData['title'],'description'=>$artifactsData['description'],
											'tags'=>$artifactsData['tags'],'size'=>number_format($artifactsData['size']/(1024*1024),2).' MB','createdTime'=>$time);
							$mediaArray['artifacts']=$appendArray;
							
							if($success>0)
							{
								$result['status']=1;
								$result['msg']='success';
								$result['detail']['path']=$artifactsData['path'];
								$result['detail']['id']=$new_key;
								$result['detail']['title']=$artifactsData['title'];
								$result['detail']['size']=number_format($artifactsData['size']/(1024*1024),2).' MB';
								$result['detail']['type']=$artifactsData['type'];
								$result['detail']['tags']=$artifactsData['tags'];
								$result['detail']['createdTime']=date('M d',$time);
								$returnData['response']=$result;
								file_put_contents($this->mediaFilePath,json_encode($mediaArray));
								die(json_encode($returnData));
								
							}
						}			
					
				}
				else{
					
						$result['status']=0;
						$result['msg']=$failureArray['details'][0]['reason'];
						$result['detail']['path']='';
						$result['detail']['id']='';
						$result['detail']['title']=$failureArray['details'][0]['name'];
						$result['detail']['size']='';
						$result['detail']['type']='';
						$result['detail']['tags']='';
						$result['detail']['createdTime']='';
						$returnData['response']=$result;
						die(json_encode($returnData));
					
				}
				
			
		}
		
	}
	

private function set_upload_options($filename,$ext)
{   
    //upload an userMedia options
    $config = array();
	if($ext=='pdf')
	{
		$config['upload_path'] = './assets/enterprise/'.$this->enterprise.'/pdf/';
	}else
	{
		//$config['upload_path'] = './assets/enterprise/'.$this->enterprise.'/video/';
		$config['upload_path'] = './assets/unprocessed/';
	}
    $config['allowed_types'] = 'mkv|avi|flv|mp4|3gp|wmv|pdf|mov|mpeg|mpg';
    //$config['allowed_types'] = 'mp4|pdf';
    $config['overwrite']     = FALSE;
	$config['file_name'] = $filename;
    return $config;
}
	
	
/********************************** course / module/ lession starts *****************/	
	
	
	public function course()
	{ 
		$data=$_POST;
		$action='';
		if(isset($data['action'])){
			$action=$data['action'];
		}
		switch ($action) {
		case 'course':
			$this->add_update_course($data);
			break;
		case 'delete_module':
			$this->delete_course($data);
			break;
		case 'delete_lession':
			$this->delete_lession($data);
			break;
		case 'get_module':
			$this->getMOduleDetailsByModuleId($data);
			break;
		default:
			$result['msg']='Bad request!';
			$returnData['response']=$result;
			die(json_encode($returnData));
		}
		
	}
	
	
	public function delete_lession($data)
	{
		
		if(empty($data['module_id']) || empty($data['lession_id'] ))
		{
			$result['msg']='Module_id and lession_id fields are required!';
			$returnData['response']=$result;
			die(json_encode($returnData));
		}	
		if(file_exists($this->courseFilePath))
		{
			$file=file_get_contents($this->courseFileBasePath);
			if(!$file){
				$result['msg']='Empty Course content !';
				$returnData['response']=$result;
				die(json_encode($returnData));
			}else{
				$courseArray = array();
				$courseArray=json_decode($file);
				$success=0;
				foreach($courseArray->user_repository as $coursekey=>$coursevalue)
					{
						if($coursevalue->id==$data['module_id'])
						{
							foreach($coursevalue->courses as $lessionkey=>$lessionValues)
							{
								if($lessionValues->course->id==$data['lession_id'])
								{
									unset($courseArray->user_repository[$coursekey]->courses[$lessionkey]); // remove the lession from file
									file_put_contents($this->courseFilePath,json_encode($courseArray));
									$success++;
								}
							}
						}
					}
				if ($success)
				{
					$result['msg']='Lession deleted successfully!';
					$returnData['response']=$result;
					die(json_encode($returnData));
				}
				else
				{
					$result['msg']='Invalid module id !';
					$returnData['response']=$result;
					die(json_encode($returnData));
				} 
			}
		}else{
			$result['msg']='Failed to find course file!';
			$returnData['response']=$result;
			die(json_encode($returnData));
			
		}
		
	}
	
	public function delete_course($data)
	{
		if(empty($data['module_id']))
		{
			$result['msg']='Module_id field is required!';
			$returnData['response']=$result;
			die(json_encode($returnData));
		}	
		if(file_exists($this->courseFilePath))
		{
			$file=file_get_contents($this->courseFileBasePath);
			if(!$file){
					$result['msg']='Empty Course content !';
					$returnData['response']=$result;
					die(json_encode($returnData));
			}else{
				$courseArray = array();
				$courseArray=json_decode($file);
				$success=0;
				$course['title']=$courseArray->title;
				foreach($courseArray->user_repository as $coursekey=>$coursevalue)
					{
						if($coursevalue->id==$data['module_id'])
						{
							unset($courseArray->user_repository[$coursekey]); // remove the media from file
							$success++;
						}else{
							
							$course['user_repository'][]=array('id'=>$coursevalue->id,'title'=>$coursevalue->title,'description'=>$coursevalue->description,'tags'=>$coursevalue->tags,'lock'=>$coursevalue->lock,'disabled'=>$coursevalue->disabled,
							'createdTime'=>$coursevalue->createdTime,'updatedTime'=>$coursevalue->createdTime,'courses'=>$coursevalue->courses);
						}
						
					}
				file_put_contents($this->courseFilePath,json_encode($course));
				if ($success)
				{
					$result['msg']='Module deleted successfully!';
					$returnData['response']=$result;
					die(json_encode($returnData));
				}
				else
				{
					$result['msg']='Invalid module id !';
					$returnData['response']=$result;
					die(json_encode($returnData));
				} 
			}
		}else{
			$result['msg']='Failed to find course file!';
			$returnData['response']=$result;
			die(json_encode($returnData));
			
		}
		
	}
	
	
	
	public function add_update_course($data)
	{
		
		$postdata['module_id']=isset($data['moduleId'])?$data['moduleId']:'-404';
		$postdata['title']=isset($data['moduleTitle'])?$data['moduleTitle']:'';
		$postdata['description']=isset($data['moduleDescription'])?$data['moduleDescription']:'';
		$postdata['tags']=isset($data['moduleTag'])?$data['moduleTag']:'';
		$count=isset($data['subModuleCount'])?$data['subModuleCount']:0;
		if($count>0)
		{
			for($i=0;$i<$count;$i++)
			{
				$postdata['courses'][$i]['course']['title']=$data['subModuleTitle-'.$i];
				$postdata['courses'][$i]['course']['description']=$data['subModuleDescription-'.$i];
				$postdata['courses'][$i]['course']['tags']=$data['subModuleTag-'.$i];
				$postdata['courses'][$i]['course']['artifacts']['video']=$data['subModuleVideo-'.$i];
				$postdata['courses'][$i]['course']['artifacts']['pdf']=$data['subModulePdf-'.$i];
				
			}
		}
	
		if($postdata['module_id']=='-404') // add new module
		{
			
			if(file_exists($this->courseFilePath))
			{
				$file=file_get_contents($this->courseFileBasePath);
				$validJson=json_decode($file);				
				if(empty($validJson) || empty($validJson->user_repository))
				{
					
					$course['title']="Recommended Training";
					$course['user_repository'][]=array('id'=>'00000001','title'=>$postdata['title'],'description'=>'','tags'=>'','lock'=>'false',
							'disabled'=>'false','createdTime'=>(string)time(),'updatedTime'=>'','courses'=>array());
					file_put_contents($this->courseFilePath,json_encode($course));
					$result['msg']='Successfully added';
					$result['data']=$course['user_repository'];
					$response['response']=$result;					
					die(json_encode($response));
				}
				else{ // append in existing file
					
					if(!empty($postdata['title']))
						{						
								$courseArray = array();
								$courseArray=json_decode($file);
								$nextkey=count($courseArray->user_repository);
								$lastarr=end($courseArray->user_repository);
								$last_key= $lastarr->id;
								$new_key=str_pad($last_key+1, 8, "0", STR_PAD_LEFT);
								$appendArray=array('id'=>$new_key,'title'=>$postdata['title'],'description'=>'','tags'=>'','lock'=>'false','disabled'=>'false',
								'createdTime'=>(string)time(),'updatedTime'=>'','courses'=>array());
								$courseArray->user_repository[]=$appendArray;
								file_put_contents($this->courseFilePath,json_encode($courseArray));
								$result['msg']='Successfully added';
								$result['data'][]=end($courseArray->user_repository);
								$response['response']=$result;					
								die(json_encode($response));
						}else{
								$result['msg']='Title field is required';
								$response['response']=$result;					
								die(json_encode($response));
						}
					
					
				}
			}else{
			
					//"create new file with name json"
					$course['title']="Recommended Training";
					$course['user_repository'][]=array('id'=>'00000001','title'=>$postdata['title'],'description'=>'','tags'=>'','lock'=>'false','disabled'=>'false',
					'createdTime'=>(string)time(),'updatedTime'=>'','courses'=>array());
					file_put_contents($this->courseFilePath,json_encode($course));
					$result['msg']='Successfully added';
					$result['data']=array('id'=>'00000001','title'=>$postdata['title']);
					$response['response']=$result;					
					die(json_encode($response));
				
			}
		
		}	/***************************************************** Add new module block ends********************************/		
		else{ 
				
				/***************************************************** update module block starts*****************************/
			if(file_exists($this->courseFilePath))
			{
				$file=file_get_contents($this->courseFileBasePath);
				$validJson=json_decode($file);
				if(empty($validJson) || empty($validJson->user_repository))
				{
					$result['msg']='Invalid file format';
					$response['response']=$result;					
					die(json_encode($response));
				}else
				{			
					$courseArray = array();
					$courseArray=json_decode($file);
					foreach($courseArray->user_repository as $coursekey=>$coursevalue)
					{
						
						if($coursevalue->id==$postdata['module_id'])
						{
						  foreach($coursevalue as $innerkey=>$innervalue)
							{
								if(isset($postdata['courses']) && $innerkey=='courses')
								{
									/* if(empty($coursevalue->courses)) // check if courses array is empty
									{
										 $course_id=str_pad(1, 4, "0", STR_PAD_LEFT);
									}else
									{
										$res=end($coursevalue->courses); // if course already exist auto increment course id
										$next_id=$res->course->id;
										$course_id=str_pad($next_id+1, 4, "0", STR_PAD_LEFT);
									} */ // commented replace old lession details
									
									$course_id=str_pad(1, 4, "0", STR_PAD_LEFT);
									foreach($postdata['courses'] as $innercoursekey=>$newCourse) // post data
									{
										 $arr[]=json_decode(json_encode(array('course'=>array('id'=>$course_id,
										 'title'=>isset($newCourse['course']['title'])?$newCourse['course']['title']:'',
										 'tags'=>isset($newCourse['course']['tags'])?$newCourse['course']['tags']:'',
										 'description'=>isset($newCourse['course']['description'])?$newCourse['course']['description']:'',
										 'createdTime'=>(string)time(),'updatedTime'=>(string)time(),
										 'artifacts'=>array('video'=>isset($newCourse['course']['artifacts']['video'])?$newCourse['course']['artifacts']['video']:'',
										 'pdf'=>isset($newCourse['course']['artifacts']['pdf'])?$newCourse['course']['artifacts']['pdf']:''
										)
										)))); 
										$course_id=str_pad($course_id+1, 4, "0", STR_PAD_LEFT);
										
									}
									$courseArray->user_repository[$coursekey]->$innerkey=$arr; // adding value in course array
									
								}
								if(isset($postdata[$innerkey]) && $innerkey!='courses')
								{
									$courseArray->user_repository[$coursekey]->$innerkey=$postdata[$innerkey];
								}	
							}
							$courseArray->user_repository[$coursekey]->updatedTime=(string)time();
							
							file_put_contents($this->courseFilePath,json_encode($courseArray));
							$result['msg']='Module successfully updated !';
							$result['data']=$courseArray->user_repository[$coursekey];
							$returnData['response']=$result;
							die(json_encode($returnData));
						}
						
						
					}
										
				}
				
				
			}else
			{
				$result['msg']='File not found';
				$returnData['response']=$result;
				die(json_encode($returnData));
			}
			
		}
		/***************************************************** update module block ends*************************************/
		
	} 
	
	function getMOduleDetailsByModuleId($data)
	{
		
		if(empty($data['module_id']))
		{
			$result['msg']='Module_id field is required!';
			$returnData['response']=$result;
			die(json_encode($returnData));
		}	
		if(file_exists($this->courseFilePath))
		{
			$file=file_get_contents($this->courseFileBasePath);
			if(!$file){
					$result['msg']='Empty Course content !';
					$returnData['response']=$result;
					die(json_encode($returnData));
			}else{
				$courseArray = array();
				$result_array = array();
				$courseArray=json_decode($file);
				$success=0;
				foreach($courseArray->user_repository as $coursekey=>$coursevalue)
					{
						if($coursevalue->id==$data['module_id'])
						{
							$result_array=$courseArray->user_repository[$coursekey];
							$success++;
						}
					}
					
					
				if ($success)
				{
					$result['msg']='Success!';
					$result['status']="1";
					$result['detail']=$result_array;
					$returnData['response']=$result;
					die(json_encode($returnData));
				}
				else
				{
					$result['msg']='No result found !';
					$result['status']="0";
					$result['detail']=$result_array;
					$returnData['response']=$result;
					die(json_encode($returnData));
				} 
			}
		}else{
			$result['msg']='Failed to find course file!';
			$returnData['response']=$result;
			die(json_encode($returnData));
			
		}
		
		
		
	}
	
	function moveProcessdVideo()
	{
		
		$success=0;
		$uploadedfile='';
		$flag=0;
		$dir = FCPATH.'assets/processed/';
		if (is_dir($dir)){
		  if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false ){
				if($file!=='.' && $file!='..')
				{
					if(!empty($file))
					{
						$movedfiles=APPPATH  . "/logs/videoprocess/processed.php";
						if(file_exists($movedfiles))
						{
							$filecontent=file_get_contents($movedfiles);
							if($filecontent)
							{
								$processedArray=explode(',',rtrim($filecontent,','));
								if(in_array($file,$processedArray))
								{
									$flag=1;
								}
							}
						}
						if(!$flag)
						{
							$enterprise=explode('_',$file);
							$enterpreiseName=@$enterprise[0];
							$videofile=@$enterprise[1];
							$myfile = $dir.$file;						
							$newfile = FCPATH.'assets/enterprise/'.$enterpreiseName.'/video/'.$videofile;
								if (!is_dir(APPPATH  . "/logs/videoprocess/"))
								{
									@mkdir(APPPATH  . "/logs/videoprocess",0777);
								}
								if (is_dir(FCPATH.'assets/enterprise/'.$enterpreiseName.'/video/')){									
									if (!copy($myfile, $newfile)) {
										$logMessage= "failed to copy $file...\n";
										$failedpath=APPPATH  . "/logs/videoprocess/videoProcessLogFailed".date('Y-m-d').".php";
										file_put_contents($failedpath, $logMessage. "\n", FILE_APPEND | LOCK_EX);
									}else{	
										$success++;
										$uploadedfile.=$file.',';
										//@unlink($dir.$file);
										$logMessage= "process  for file ".$videofile."is success for enterprise ".$enterpreiseName."...\n";
										$failedpath=APPPATH  . "/logs/videoprocess/videoProcessLogSuccess".date('Y-m-d').".php";
										file_put_contents($failedpath, $logMessage. "\n", FILE_APPEND | LOCK_EX);
									}
								}
						}
					}
				}
			  
			}
			closedir($dh);
		  }
		}
		// after success move
			if($success){
				$movedfiles=APPPATH  . "/logs/videoprocess/processed.php";
				if(file_exists($movedfiles))
				{
					$filecontent=file_get_contents($movedfiles);
					file_put_contents($movedfiles, $uploadedfile, FILE_APPEND | LOCK_EX);
				}else{
					file_put_contents($movedfiles, $uploadedfile, FILE_APPEND | LOCK_EX);
				}
			}	
			$this->cronDeleteMediaArtifacts(); // delete  media from folder if deleted from json
			echo $success.' file(s) moved';
	}
	
	function cronDeleteMediaArtifacts()
	{
		/* delete media artifacts log*/
		$delete=0;
		$artifactsList=FCPATH . "assets/deteled_media.txt"; // deleted artifacts log file
		if(file_exists($artifactsList))
		{
			$file=file_get_contents($artifactsList);
			if(!empty($file))
				{ 
					$deleteThis=explode(',',$file);
					$newArray=array();
					foreach($deleteThis as $key => $deleteFile)
					{ 
						$deletePath=FCPATH."assets/enterprise/".$deleteFile;
						if(file_exists($deletePath))
						{	
							$delete++;
							@unlink($deletePath);
							unset($deleteThis[$key]);
						}else{
							$newArray[]=$deleteFile;
						}
					}
					$result=implode($newArray,',');
					file_put_contents($artifactsList,$result);
				}
		}
		echo $delete.'deleted';
		/* delete media artifacts log*/
	}
	
}
?>
