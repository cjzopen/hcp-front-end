<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Ovt_approve extends CI_Controller {
 
 function __construct()
 {
   parent::__construct();
    $this->load->helper('url');
   $this->load->library('session');  
   // 檢核Session 停滯時間是否超過 sess_expiration時間 (config.php)   
   if($this->session->userdata('logged_in'))
    {    
	$this->load->model('AresApprove_Model','',TRUE);  
	$this->load->helper('form');
	$this->load->library('form_validation');
	$this->load->library('pagination');  
    }
    else{ // time out
        redirect(site_url().'/login','refresh');
    }  
 }
 
 function index()
 {
   //$this->load->helper(array('form'));
    $this->load->helper(array('form', 'url'));
    $rowCount = $this->AresApprove_Model->GetApproveInfo($_SESSION ['user'] ['company_id'],$_SESSION ['user']['emp_seq_no'],'overtime' ,'','');
   
	// 分頁設置
	$config['base_url'] = site_url("ovt_approve/index"); //url地址
	$route['abs_approve/index/(:num).html'] = "ovt_approve/index";
	$config['total_rows']  =$rowCount; //總數據量（一般從資料庫讀取，可以使用$this->db->count_all_results('表名');）
	$config['per_page']   = 5; // 每頁顯示數量
	$config['use_page_numbers'] = TRUE; // 使用頁碼方式而非偏移量方式傳值
    $config['suffix'] = '.html'; //设置后缀
    $config['first_url'] = site_url('ovt_approve'); // 要設置的第一頁鏈結
    $config['uri_segment'] = 3;
    $choice = $config["total_rows"] / $config["per_page"];
    $config["num_links"] = round($choice);
	$config['prev_link'] = '上一頁';
	$config['next_link'] = '下一頁';
	$config['full_tag_open']= '<div class="pagination">';
	$config['full_tag_close']= '</div>';	
	// 執行分頁類初始化
	$this->pagination->initialize($config);
	 
	// 資料庫查詢(假設已經裝載了資料庫類)
	$page = (int)$this->uri->segment(3); // 获取页码
	$offset = $page == false?0:($config['per_page'] * ($page - 1)); // 计算偏移量
	 
	//$this->db->limit($config['per_page'], $offset); // limit(每頁顯示數量，偏移量)
    if ($page =='')  $page=1;
 
	$this->pagination->initialize($config); 
 
     $data['list'] = $this->AresApprove_Model->GetApproveInfo($_SESSION ['user'] ['company_id'],$_SESSION ['user']['emp_seq_no'],'overtime' ,$page,$config['per_page']);
	//$data['list'] = $result; // 獲取資料庫裏的資料
     // var_dump($result );exit; 
	// 生成分頁鏈結
	$data['links'] = $this->pagination->create_links();
    $data['scriptname'] ='overtime_approve';
	
	$this->form_validation->set_rules('workflow_seqno[]', 'workflow_seqno', 'required');
 	// 輸出範本
    $this->load->view('PageHeader_view');	

	if ($this->form_validation->run() == FALSE)
	{
		$this->load->view('block_overtime_approve_list_view', $data);
	}
	else
	{
	 $ret =$this->ActionApprove();	
	 $data['success_count'] = $ret['success_count'];
	 $data['failure_count'] = $ret['failure_count'];  	 
	 $this->load->view('block_overtime_approve_result_view',$data);
	}
    $this->load->view('PageFooter_view',$data);		
    //var_dump($result );exit; 
 
 }
 
 // 簽核申請單
 function ActionApprove()
 { 
 $data = Array();
 //$this->AresApprove_Model->leaveBatchApprove($_POST);
  $Num_Row = count($_POST['approve_seqno']);
  $list= $this->CombineVarsFromView();
  //print_r($list);
  $action_ret =$this->ImmediatelyApprove($list,$Num_Row);
   $data['success_count'] = $action_ret['success_count'];
   $data['failure_count'] = $action_ret['failure_count'];  
   return $data;
 }
 
 // 即時簽核(直接寫入DB)
 // 回傳執行結果 Array()
 function ImmediatelyApprove($_workflow_info,$Num_Row)
 {
 $success_count = 0;
 $failure_count = 0;
 $action_ret = Array();
 $j = 0;
   for ($i=0; $i<$Num_Row; $i++)
	{    
		if (!empty($_workflow_info[$i]['is_approved']))
		{
			$_result = $this->AresApprove_Model->Approve($_workflow_info[$i]);
			
			//print_r($_result);exit;
			// release memory
			unset($_workflow_info[$i]);
			$_approve_result_list[$j] = $this->AresApprove_Model->GetApproveResultList($_result['company_id'],
																		$_result['workflow_seqno'],
																		$_result['apply_type']);
			//var_dump($_approve_result_list);exit;															
		   // pr($_approve_result_list); 
			$_approve_result_list[$j]['IS_SUCCESS'] = $_result['is_success'];
			$_approve_result_list[$j]['MSG']        = $_result['msg'];
			if($_result['is_success']=='Y'){
				$success_count++;
			}else{
				$failure_count++;
			}
			$j++;
		}// end if
	} // end for	
	 $action_ret['success_count'] = $success_count;
	 $action_ret['failure_count'] = $failure_count;	 
	//var_dump($action_ret);
	return $action_ret;
 }
 // 將 表單輸入的資料作彙整 
 function CombineVarsFromView()
  { 
  $list = array();
  for ($i=0; $i<count($_POST['approve_seqno']); $i++)
	{
		$list[$i]['company_id']          = $_POST['company_id'][$i];
		$list[$i]['workflow_seqno']      = $_POST['workflow_seqno'][$i];
		$list[$i]['apply_type']          = $_POST['apply_type'][$i];
		$list[$i]['approver_emp_seqno']  = $_POST['approver_emp_seqno'][$i];
		$list[$i]['approver_user_seqno'] = $_SESSION['user']['user_seq_no']; // 因為有從郵件簽核,所以有把 userseqno放到畫面上
		$list[$i]['approve_seqno']       = $_POST['approve_seqno'][$i];
		
		// change workflow_seqno to approve_seqno by dennis 2012-01-18 
		// 當同一階有兩個以上的簽核人，且兩人設為代理人關係時,會出現兩筆一樣的記錄，在前臺無法選取核准或是駁回
		// 因為 radio 是 flow_sz_id,是相同的，現在改為 approve_seqno 這個是不同的
		$is_approved_name = 'approve_action'.$_POST['approve_seqno'][$i];
		if (isset($_POST[$is_approved_name]) && !empty($_POST[$is_approved_name]))
		    $list[$i]['is_approved']         = $_POST[$is_approved_name]; 
		    else
			$list[$i]['is_approved']        = "";
		
		//移除空白以讓empty判斷正確 by Yun 2015-07-31
		//$reject_reason = trim($_POST['reject_reason'][$i]);
		$all_reject_reason = trim($_POST['all_reject_reason']);
        $list[$i]['reject_reason'] = $all_reject_reason;
		// by Yun 2015-07-31
	/*	if(!empty($reject_reason)){
			$list[$i]['reject_reason'] = $reject_reason;
		}else if(!empty($all_reject_reason)){
			$list[$i]['reject_reason'] = $all_reject_reason;
		}else{
			$list[$i]['reject_reason'] = null;
		}*/
	 }
  return $list;	 
  }
 
}
 
?>