<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Schedul extends CI_Controller {
 
 function __construct()
 {
   parent::__construct();
    $this->load->model('AresCalendar','',TRUE);  
   $this->load->helper('form');
   $this->load->helper('url');
   $this->load->library('session');
 }
 
 function index()
 {
 //  $this->load->library('session'); 
   $this->load->helper(array('form'));
   
	$_SESSION['Calendar_SelDate']= date('Ymd');
	$year = substr($_SESSION['Calendar_SelDate'], 0, 4);
	$mon  = substr($_SESSION['Calendar_SelDate'], 4, 2);
	$day  = substr($_SESSION['Calendar_SelDate'], 6, 2);
	// For Calender Default
   $_SESSION['Calendar_yy']=$year;
   $_SESSION['Calendar_mm']=(int)$mon;
   $_SESSION['Calendar_dd']=(int)$day; 
	   
   $yy= $_SESSION['Calendar_yy'];
   $mm= $_SESSION['Calendar_mm'];  
   $dd= $_SESSION['Calendar_dd'];    
   
   if (strlen($mm) == 1) $mm ='0'.$mm;
   if (strlen($dd) == 1) $dd ='0'.$dd;
   $calendr_data = $this->AresCalendar->GetSchedulInfo($yy.$mm.$dd,$_SESSION ['user'] ['company_id'],$_SESSION ['user']['emp_seq_no']);  
   // var_dump($calendr_data );exit; 
   $data  = array();
    $data['cal'] = $calendr_data;
	$dat = $this->AresCalendar->GetSchedulForSetColor($yy.$mm,$_SESSION ['user'] ['company_id'],$_SESSION ['user']['emp_seq_no'],$_SESSION['Calendar_dd']);
   //var_dump($dat );exit;
   
   //S.例假日 H.國定假日 N.工作日(#ffffff) R.休息日 U.免出勤日 SYUN.未排班 當前日.SYCU
	 $HolidyCode_color = array(
		'S' => '#DDA0DD',
		'R' => '#FFB6C1',
		'U' => '#FF7F50',
		'N' => '#D6D6D6',
		'SYCU' => '#DAA520',
		'SYUN' => '#ffffff',	
		'H' => '#C641C6'	
	);
   
   $data1 = array();
   $data2 = array();
   $ii = 0;
   foreach ($dat as $key => $val)  
   {
	$data1[$ii] =$key;
    $data2[$ii] =$HolidyCode_color[$val];
    $ii=$ii+1; 	
   }
   $data['HolidyCode_color'] =$HolidyCode_color;
   $data['data1'] =$data1;
   $data['data2'] =$data2;  
   
    $data['scriptname'] ='schedul';   
  // 輸出範本
    $this->load->view('PageHeader_view');	
	
    $this->load->view('schedul_view',$data);
	
    $this->load->view('PageFooter_view',$data);		 
 }
 
 
}
 
?>