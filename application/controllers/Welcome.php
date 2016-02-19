<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome extends CI_Controller {

	public $mediaFilePath;
	public $mediaFileBasePath;
	public $enterprise;
	function __construct() 
	{
        parent::__construct();
		
		$session=$this->session->userdata('logged_in');
		if(!$session)
		{
			redirect(base_url()."users/login");
		}
		$this->enterprise=$session['enterpriseurl'];
		$this->mediaFilePath=FCPATH . "assets/enterprise/".$this->enterprise."/json/artifacts.json";
	    $this->mediaFileBasePath=base_url() . "assets/enterprise/".$this->enterprise."/json/artifacts.json";		
		$this->courseFilePath=FCPATH . "assets/enterprise/".$this->enterprise."/json/course.json";
	    $this->courseFileBasePath=base_url() . "assets/enterprise/".$this->enterprise."/json/course.json";
		$this->load->library('commonfunctions');	
		$this->load->library('layout');	
		$this->commonfunctions->checkDirectoryStructure($this->enterprise);
    }
	
	public function index()
	{
		
		$data['allMedia']=$this->getAllMedia();
		$data['allModules']=$this->getAllModules();
		$data['unprocessedMedia']=$this->getUnprocessedMedia($this->enterprise);
		$data['pagename']='dashboard';
		$this->layout->view('cfs/dashboard',$data);
		
	}
	
	public function getAllMedia()
	{
		if(file_exists($this->mediaFilePath))
		{
			$file=file_get_contents($this->mediaFileBasePath);
			if(!empty($file))
			{
				return $mediaArray=json_decode($file);
			}
		}
		return array();
		
	}
	public function getAllModules()
	{
		if(file_exists($this->courseFilePath))
		{
			$file=file_get_contents($this->courseFileBasePath);
			if(!empty($file))
			{
				return $courseArray=json_decode($file);
			}
		}
		return array();
	}
	
	public function getUnprocessedMedia()
	{
		$dir = FCPATH.'assets/unprocessed/';
		$arr=array();
		if (is_dir($dir)){
		  if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false ){
				if($file!=='.' && $file!='..')
				{
					if(!empty($file))
					{
						$enterprise=explode('_',$file);
						//print_r($file);die;
						$enterpreiseName=@$enterprise[0];
					    $videofile=@$enterprise[1];
						if($this->enterprise==$enterpreiseName)
						{
							$ent_file=explode('.',$videofile);
							$arr[]=@$ent_file[0];
						}					
												
					}
				}
			  
			}
			closedir($dh);
		  }
		}
		return $arr;
	}
	
	public function getUnprocessedMediaByAjax()
	{
		$result_array=$this->getUnprocessedMedia();
		if(!empty($result_array))
		{
			$result['msg']='success';
			$result['status']="1";
			$result['detail']=$result_array;
			$returnData['response']=$result;
			die(json_encode($returnData));
		}else{
			$result['msg']='No media found';
			$result['status']="0";
			$result['detail']=array();
			$returnData['response']=$result;
			die(json_encode($returnData));
		}
		
	}
	
	
}
