<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout  
{ 
    public $head = 'layout/head';
	function __construct()
	{
		$this->lt =& get_instance();	
		
	}
	function view($view ='', $data ='')		
	{	
		$this->lt->load->view($this->head,$data);
		$this->lt->load->view($view, $data);
	}
}
