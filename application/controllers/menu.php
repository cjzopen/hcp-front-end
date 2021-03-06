<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI
class Menu extends CI_Controller {
 
 function __construct()
 {
   parent::__construct();
   $this->load->helper('form');
   $this->load->helper('url');
   $this->load->library('session');
 }
 
 function index()
 {
   if($this->session->userdata('logged_in'))
   {
	   
     $session_data = $this->session->userdata('logged_in');
     $data['username'] = $session_data['username'];
 
     $this->load->view('menu_view', $data);
   }
   else
   {  
     //If no session, redirect to login page
     redirect('login', 'refresh');
   }
 }
 
 function logout()
 {
   $this->session->unset_userdata('logged_in');
   session_destroy();
   redirect('home', 'refresh');
 }
 
}
 
?>