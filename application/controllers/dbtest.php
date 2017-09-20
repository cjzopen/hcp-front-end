<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class dbtest extends CI_Controller {
    public function index() {
     $this->load->database();
	$query = $this->db->query('SELECT * FROM gl_segment');
	echo $query->num_rows();
    }
 
}