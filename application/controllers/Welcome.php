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
	
	
	
	
	
}
