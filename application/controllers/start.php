<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class start extends CI_Controller {
    public function index() {
		$data['title'] = "My Real Title";
 
        $this->load->view ( 'start_view', $data );
    }
}