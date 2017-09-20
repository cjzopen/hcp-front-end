<?php
class db_controller extends CI_Controller{
   function __construct(){
        parent::__construct();
    }

	public function index(){
		// Load our view to be displayed
		// to the user
		$this->load->view('login_view');
	}
	
	public function db_data(){
	$this->load->model('db_model');
	//$data['dtl']=$this->db_model->get_gl_segment( );
	//$data['row']=$this->db_model->get_hr_personel_base();
	//$data['dtl']=$this->db_model->get_dataR();
	//$this->load->view('show_data', $data);
	}
}
?>