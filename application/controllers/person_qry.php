<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Person_qry extends CI_Controller {
 
 function __construct()
 {
   parent::__construct();
    $this->load->helper('url');
   $this->load->library('session');  
   // 檢核Session 停滯時間是否超過 sess_expiration時間 (config.php)   
   if($this->session->userdata('logged_in'))
    {    
	$this->load->model('AresUser','',TRUE);  
	$this->load->helper('form');
    }
    else{ // time out
        redirect(site_url().'/login','refresh');
    }  
 }
 
 function index()
 {
    $this->load->helper(array('form', 'url'));
  
    $data['list'] = $this->AresUser->getPersonMomo();
 
     // var_dump($result );exit; 
 
    $data['scriptname'] ='person_qry';
 
 // 輸出範本
    $this->load->view('PageHeader_view');	
 
	$this->load->view('block_person_qry_list_view', $data);
 
    $this->load->view('PageFooter_view',$data);		
    //var_dump($result );exit; 
 
 }
  
 
}
 
?>