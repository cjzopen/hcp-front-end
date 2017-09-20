<?php if ( ! defined('BASEPATH')) exit('No x direct script access allowed');
class abs_apply extends CI_Controller {
 function __construct()
 {
   parent::__construct();
    $this->load->helper('url');
   $this->load->library('session');  
  // require_once 'functions.php';
   // 檢核Session 停滯時間是否超過 sess_expiration時間 (config.php)   
   if($this->session->userdata('logged_in'))
    {    
 	$this->load->model('AresAttend','',TRUE);  
	$this->load->helper(array('form', 'url'));
	$this->load->library('form_validation');
    }
    else{ // time out
        redirect(site_url().'/login','refresh');
    }  
 }
 function index()
 {
   //$this->load->helper(array('form'));
    $this->load->helper(array('form', 'url'));
    $data['LeaveNameList'] = $this->AresAttend->GetLeaveNameList($_SESSION ['user'] ['sex']);
    $data['rule_name'] = "請假規則";
    $data['apply_rule'] = $this->AresAttend->getRuleText("leave_apply_rule_b");	
    $data['spec_abs'] = $this->AresAttend->getSpecAbsence(); //  特殊假別	
	//var_dump($data['spec_abs']);exit;
    $data['scriptname'] ='leave_apply';
	$this->form_validation->set_rules('absence_id', '假別名稱', 'required');
	$this->form_validation->set_rules('begin_date', '開始時間', 'required');
	$this->form_validation->set_rules('end_date', '結束時間', 'required'); 
	$this->form_validation->set_rules('leave_reason', '事由', 'required');
 
 
 	// 輸出範本
    $this->load->view('PageHeader_view');	
	if ($this->form_validation->run() == FALSE)
	{
 		
		$this->load->view('block_leave_apply_view', $data);
		$this->load->view('block_box_rule_view.php', $data);		
	}
	else
	{ 
	
    $begin_date = $_POST['begin_date'].' '.$_POST['begin_time'];
    $end_date   = $_POST['end_date'].' '.$_POST['end_time'];
 
	// add by boll 經理代理人處理
	$agent_manager_str= ($_SESSION['user']['is_manager1']=='1')?'Manager ':'';
	$is_manager		  = ($_SESSION['user']['is_manager1']=='1')?'Y':'N';
	//echo $agent_manager_str;

	//特別假 funeral_id
	$funeral_id   = empty($_POST['funeral_id'])? null : $_POST['funeral_id'];
	$agent_id1    = isset($_POST['agent_id1']) ? $_POST['agent_id1'] : '';
	$agent_item   = isset($_POST['agent_item']) ? $_POST['agent_item'] : '';
	$agent_item1  = isset($_POST['agent_item1']) ? $_POST['agent_item1'] : '';
	
	if (!isset($_POST['assign_type']))  $_POST['assign_type']= "0"; 
	$leave_reason_str=($_POST['assign_type']=='0')?
					   $_POST['leave_reason']     :
					   $_POST['leave_reason'].'<hr size="1" is_manager="'.
					   $is_manager.'" assign_type="'.$_POST['assign_type'].
					   '" agent_id="'.$_POST['agent_id'].'" agent_id1="'.
					   $agent_id1.'" agent_item="'.$agent_item.
					   '" agent_item1="'.$agent_item1.
					   '" emp_seq_no="'.$_SESSION['user']['emp_seq_no'].'" >'.
					   $agent_manager_str.'Agent:'.$_POST['agent'];		
					   
	$submit_type ='Y';				   


//$tmp_save = isset($_POST['submit']) ? 'Y' : 'N';
	$tmp_save = 'Y'; // modify by dennis 2013/10/21 已經無暫存功能，所有記錄都即時提交

    // 送出申請單
	$result = $this->AresAttend->SaveLeaveForm($_SESSION ['user'] ['user_seq_no'],
										  $_POST['absence_id'],
										  $begin_date,
										  $end_date,
										  $leave_reason_str ,
										  $submit_type,
										  $funeral_id);
		//pr($result);
		if ($result ['is_success'] == 'Y') 
		{
			if (! empty ( $_POST ['submit'] )) {
				$this->showMsg($result ['msg'], 'success');
			}// end if
			if (! empty ( $_POST ['save'] )) {
				$this->showMsg($result ['msg']);
			}// end if
		} else {
			$this->showMsg($result ['msg'],'error' );
		}// end if
     }// end if
	 
    $this->load->view('PageFooter_view',$data);
    //var_dump($result );exit; 
 }
 	/**
	 * 取得親屬清單
	 *
	 * @return array add already_days=0 by dennis 2012-10-26 for filter expired data
	 *         last update by dennis 2012-11-06
	 *         Fixed bug: 特殊假起訖時間內，且未休完的假都要列出來
	 *         Fixed Bug: 親屬類型未按假別來取 by Dennis 2014/11/06
	 */
	function GetFamilyType($abs_type_id) {
		$abs_type_id = $_POST ['abs_type_id'];
		console.log("AAAA");
	$conn = oci_connect("hcp","hcp","cthcp","UTF8");		
		$sql = "select funeral_id, family_type || '-'|| family_type_name||'('||FAMILY_NAME ||')'  as family_type
                 from ehr_funeral_v
                where company_id = :company_id
                  and emp_seq_no = :emp_seqno
		          and absence_type_id = :abs_type_id";
 
			$stid = oci_parse($conn, $sql);
			oci_bind_by_name($stid, ':company_id', $_SESSION['user']['company_id']);
			oci_bind_by_name($stid, ':emp_seqno', $_SESSION['user']['emp_seq_no']);
			oci_bind_by_name($stid, ':abs_type_id', $abs_type_id);			
			oci_execute($stid);

			$result =Array();
			 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
						$result['FUNERAL_ID'] = $row['FUNERAL_ID'];
						$result['FAMILY_TYPE'] = $row['FAMILY_TYPE'];				
			 }
			oci_free_statement($stid);
			oci_close($conn);	
			return $result;	
	
	} // end GetFamilyType();
function showMsg($msg_text, $msg_type = 'information', $back_url = null) {
	$backurl = is_null ( $back_url ) ? 'Javascript:history.back();' : $back_url;
	$page_header = <<<eof
	<?xml version="1.0" encoding="utf-8"?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="expires" content="wed, 20 Feb 2000 08:30:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="owner" content="Dennis Lan/R&D/ARES CHINA" />
	<meta name="author" content="Dennis Lan, Lan Jiangtao" />
	<meta name="Copyright" content="ARES China Inc." />
	<meta name="description" content="eHR for HCP" />
	<link rel="icon" href="../img/ares.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="../img/ares.ico" type="image/x-icon" />
	<link rel="stylesheet" href="../css/blueprint/screen.css"
		type="text/css" media="screen, projection" />
	<link rel="stylesheet" href="../css/blueprint/print.css" type="text/css"
		media="print" />
	<!--[if IE]><link rel="stylesheet" href="../css/blueprint/ie.css" type="text/css" media="screen, projection"/><![endif]-->
	<link rel="stylesheet" href="../css/default.css" type="text/css" />
	</head>
	<body class="page-container">
	<div class="span-4"></div>
	<div class="span-8">
eof;
	$msg_header = <<<eof
	<div class="sidebox resources">
		<div class="x-box-tl">
			<div class="x-box-tr">
				<div class="x-box-tc"></div>
			</div>
		</div>
		<div class="x-box-ml">
			<div class="x-box-mr">
				<div class="x-box-mc">
					<h3 style="margin:3px;"><img src=%s.png></h3>
					<hr noshade="noshade" size="1" style="color: rgb(128, 128, 128);" />
					<!--{/if}-->
					<ul>
eof;
	$msg_footer = <<<eof
					<div align="right">
						<br>
						<a href=%s><< Back </a>
					<div>
					</ul>
				</div>
			</div>
		</div>
		<div class="x-box-bl">
			<div class="x-box-br">
				<div class="x-box-bc"></div>
			</div>
		</div>
eof;
	$page_footer = <<<eof
		<div class="span-4 last">&nbsp;</div>
		<div>&nbsp;</div>
		</body>
		</html>
eof;
	echo sprintf ( $page_header . $msg_header . $msg_text . $msg_footer . $page_footer, base_url().'img'. '/' . $msg_type, $backurl, date ( 'Y' ) );
	exit ();
} // end showMsg()
 
}
 
?>