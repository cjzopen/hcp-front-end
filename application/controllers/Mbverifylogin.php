<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class MbverifyLogin extends CI_Controller {
 
 function __construct()
 {
   parent::__construct();
   $this->load->model('AresUser','',TRUE);
   $this->load->helper('url');  
   $this->load->helper('security');
   $this->load->library('session');
   
 }
 
 function index()
 {
   //This method will have the credentials validation
   $this->load->library('form_validation');
 
   $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
  // $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');  
   if (isset($_GET ['token']) && ! empty($_GET ['token']))
   {
	if ($this->AresUser->QryToken($_GET ['token']))
		{ 
	     $userid =$this->AresUser->QryIdByToken($_GET ['token']);
   
		 // Write Session
		 $this->AresUser->login($userid, '','Y');
 		 $this->AresUser->DelToken($_GET ['token']);
 
        $sess_array = array(
         'id' => $userid,
         'username' => $userid
       );
	   $this->session->set_userdata('logged_in', null);
       $this->session->set_userdata('logged_in', $sess_array);	 
	   
		 // Exist usr's token >>> Login in 
		redirect('menu', 'refresh');
		}
   }		
 
  if ( empty ( $_REQUEST ['username'] ) || empty ( $_REQUEST ['password'] ))
			{
			$rtn['status'] ='false';
			$rtn['url'] ='';
            $rtn['message'] ='username and password should be filled!';
			echo json_encode($rtn) ; // return token		
			exit;	
			}
			
   if($this->form_validation->run() == FALSE)
   {
     //Field validation failed.  User redirected to login page
     $this->load->view('login_view');
   }
   else
   {
	   //This method will have the credentials validation
	   //query the database
		if ( isset ( $_REQUEST ['username'] ) && isset ( $_REQUEST ['password'] )) {  
 
		$result = $this->AresUser->login($_REQUEST ['username'], $_REQUEST ['password']);
         //var_dump($result);exit();
		if (isset($result)&& empty ( $result))
			{
			$this->AresUser->SetToken(do_hash($_REQUEST ['username'], 'md5'),$_REQUEST ['username']); // 記錄 TOKEN	
			$rtn = Array();
			$rtn['status'] ='true';
			$rtn['url'] =base_url().'index.php/MbverifyLogin?token='. do_hash($_REQUEST ['username'], 'md5');
			echo json_encode($rtn) ; // return token
			exit;
			//redirect('menu', 'refresh');//Go to private area
			}
			else
			{
			$rtn['status'] ='false';
			$rtn['url'] ='';
            $rtn['message'] =$result ;
			echo json_encode($rtn) ; // return token		
			exit;	
			}
		}	   
 
   }
 
 }
 
 function check_database($password)
 {
   //Field validation succeeded.  Validate against database
   $username = $this->input->post('username');
 
   //query the database
   $result = $this->AresUser->login($username, $password);
 // var_dump($sess_array);exit();
   if (isset($result)&& empty ( $result['ERRORMSG']))
   {
	  $this->session->set_userdata($result); 
     $sess_array = array();
 
       $sess_array = array(
         'id' => $username,
         'username' => $username
       );
	   $this->session->set_userdata('logged_in', null);
       $this->session->set_userdata('logged_in', $sess_array);	 
 
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