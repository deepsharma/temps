<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enterprise extends CI_Controller {

	public $mediaFilePath;
	public $mediaFileBasePath;
	public $enterprise;
	function __construct() {
        parent::__construct();
		$this->enterprise='shopclues';
		$this->mediaFilePath=FCPATH . "assets/enterprise/".$this->enterprise."/json/artifacts.json";
	    $this->mediaFileBasePath=base_url() . "assets/enterprise/".$this->enterprise."/json/artifacts.json";		
		$this->courseFilePath=FCPATH . "assets/enterprise/".$this->enterprise."/json/course.json";
	    $this->courseFileBasePath=base_url() . "assets/enterprise/".$this->enterprise."/json/course.json";
		$this->load->library('commonfunctions');	
		$this->commonfunctions->checkDirectoryStructure($this->enterprise); // check directory structure
		
    }
	
		
	public function index()
	{
		$this->load->view('cfs');
	}
	
	public function artifacts()
	{ 
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
					unset($mediaArray->artifacts->$data['media_id']); // remove the media from file
					file_put_contents($this->mediaFilePath,json_encode($mediaArray));
					$result['msg']='Success !';
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
				$filename=md5($path_parts['filename'].time()).'.'.$ext;
				$this->upload->initialize($this->set_upload_options($filename,$ext));
				$this->upload->do_upload();
				if ( ! $this->upload->do_upload('userMedia'))
				{
					$failureArray['details'][$failure]['name']=$_FILES['userMedia']['name'];
					$failureArray['details'][$failure]['reason']=strip_tags($this->upload->display_errors());
					$failure++;
				}else{
					
					$art_data['artifacts'][$i]['path']=$filename;
					$art_data['artifacts'][$i]['url']='';
					$art_data['artifacts'][$i]['type']=$ext;
					$art_data['artifacts'][$i]['total_video']='';
					$art_data['artifacts'][$i]['video_dir']=$this->enterprise.'/video/';
					$art_data['artifacts'][$i]['window']='newtab/iframe';
					$art_data['artifacts'][$i]['title']=$_FILES['userMedia']['name'];
					$art_data['artifacts'][$i]['description']='';
					$art_data['artifacts'][$i]['tags']='';					
					$art_data['artifacts'][$i]['size']=$_FILES['userMedia']['size'];					
					$successArray['details'][$success]['name']=$_FILES['userMedia']['name'];
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
								$result['detail']['createdTime']=date('M,d,Y',$time);
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
								$result['detail']['createdTime']=date('M,d,Y',$time);
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
								$result['detail']['createdTime']=date('M,d,Y',$time);
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
	}else if($ext=='mp4')
	{
		$config['upload_path'] = './assets/enterprise/'.$this->enterprise.'/video/';
	}
    $config['allowed_types'] = 'pdf|mp4';
    $config['overwrite']     = FALSE;
	$config['file_name'] = $filename;
    return $config;
}
	
	
/********************************** course / module/ lession starts *****************/	
	
	
	public function course()
	{ 
//echo "<pre>";print_r($_POST);die;	
		$data=$_POST;
		//$data['action']='get_module';
		//$data['action']='delete_module';
		//$data['action']='course';
		//$data['action']='delete_lession';
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
		/* $data['module_id']='00000004';
		$data['lession_id']='0001'; */
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
		//$data['module_id']='00000004';
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
				foreach($courseArray->user_repository as $coursekey=>$coursevalue)
					{
						if($coursevalue->id==$data['module_id'])
						{
							unset($courseArray->user_repository[$coursekey]); // remove the media from file
							file_put_contents($this->courseFilePath,json_encode($courseArray));
							$success++;
						}
					}
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
					
					$course['title']="Shopclues University";
					$course['user_repository'][]=array('id'=>'00000001','title'=>$postdata['title'],'description'=>'','tags'=>'','lock'=>'',
							'disabled'=>'','createdTime'=>(string)time(),'updatedTime'=>'','courses'=>array());
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
								$appendArray=array('id'=>$new_key,'title'=>$postdata['title'],'description'=>'','tags'=>'','lock'=>'','disabled'=>'',
								'createdTime'=>(string)time(),'updatedTime'=>'','courses'=>array());
								$courseArray->user_repository[]=$appendArray;
								file_put_contents($this->courseFilePath,json_encode($courseArray));
								$result['msg']='Successfully added';
								$result['data']=end($courseArray->user_repository);
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
					$course['title']="Shopclues University";
					$course['user_repository'][]=array('id'=>'00000001','title'=>$postdata['title'],'description'=>'','tags'=>'','lock'=>'','disabled'=>'',
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
									
									//echo "<pre>";print_r($courseArray);die;
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
		//$data['module_id']='00000005';
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
	
	
	
}
?>
