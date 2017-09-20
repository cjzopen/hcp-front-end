<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class VerifyLogin extends CI_Controller {
 
 function __construct()
 {
   parent::__construct();
   $this->load->model('AresUser','',TRUE);
   $this->load->helper('url');  
   $this->load->library('session');
 }
 
 function index()
 {
   //This method will have the credentials validation
   $this->load->library('form_validation');
 
   $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
   $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
 
   if($this->form_validation->run() == FALSE)
   {
     //Field validation failed.  User redirected to login page
     $this->load->view('login_view');
   }
   else
   {
     //Go to private area 
	 redirect('menu', 'refresh');
   }
 
 }
 
 function check_database($password)
 {
   //Field validation succeeded.  Validate against database
   $username = $this->input->post('username');
 
   //query the database
   $result = $this->AresUser->login($username, $password);
 
   if($result)
   {
	  $this->session->set_userdata($result); 
     $sess_array = array();
 
       $sess_array = array(
         'id' => $result['USER_NAME'],
         'username' => $result['USER_DESC']
       );
	   $this->session->set_userdata('logged_in', null);
       $this->session->set_userdata('logged_in', $sess_array);	 
	 // var_dump($sess_array);exit();
     return TRUE;
   }
   else
   {
     $this->form_validation->set_message('check_database', 'Invalid username or password');
     return false;
   }
 }
}
?>