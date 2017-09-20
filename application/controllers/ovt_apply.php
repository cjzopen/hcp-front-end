<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ovt_apply extends CI_Controller {
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
   $data['overtime_fee_type'] = $this->AresAttend->getOvertime_applyList ('ESNA010','OVERTIME_FEE_TYPE','ZHT');
    $data['overtime_type'] = $this->AresAttend->getOvertime_applyList ('ESNA010','OVERTIME_TYPE','ZHT');
    $data['overtime_reason'] = $this->AresAttend->GetOvertime_Reason();
    $data['rule_name'] = "加班規則";
    $data['apply_rule'] = $this->AresAttend->getRuleText("overtime_apply_rule_b");	
    $data['scriptname'] ='overtime_approve';

	$this->form_validation->set_rules('overtime_date', '加班日期', 'required');
	$this->form_validation->set_rules('begin_time', '開始時間', 'required');
	$this->form_validation->set_rules('end_time', '結束時間', 'required'); 
	$this->form_validation->set_rules('overtime_reason', '加班原因', 'required');
	$this->form_validation->set_rules('overtime_fee_type1', '計費/補休', 'required');
	$this->form_validation->set_rules('overtime_type1', '加班類型', 'required'); 		
 	// 輸出範本
    $this->load->view('PageHeader_view');	
	if ($this->form_validation->run() == FALSE)
	{
		$this->load->view('block_overtime_apply_view', $data);
		$this->load->view('block_box_rule_view.php', $data);
	}
	else
	{ 
    //var_dump($_POST);exit;
	// 計算加班起始結束日期時間
	$_date  = explode ( '-', $_POST ['overtime_date'] );
	$_btime = explode ( ':', $_POST ['begin_time'] );
	$_etime = explode ( ':', $_POST ['end_time'] );
	$_begin_date = mktime ( $_btime [0], $_btime [1], 0, $_date [1], $_date [2], $_date [0] );
	$_end_date   = mktime ( $_etime [0], $_etime [1], 0, $_date [1], $_date [2], $_date [0] );
	$begin_time  = date ( 'Y-m-d H:i', $_begin_date );
	$end_time    = date ( 'Y-m-d H:i', $_end_date );
 
	// 如果加班結束時間小於開始時間, 表示其跨天
	if ($_end_date < $_begin_date) {
		$end_time = date ('Y-m-d H:i', mktime($_etime[0],$_etime [1],0,$_date[1],$_date[2] + 1, $_date[0]));
	}// end if

	//$tmp_save = isset($_POST['submit']) ? 'Y' : 'N';
	$tmp_save = 'Y'; // modify by dennis 2013/10/21 已經無暫存功能，所有記錄都即時提交

    // 送出申請單
	$result = $this->AresAttend->SaveOvertimeApply($_SESSION ['user'] ['user_seq_no'],
										  $_SESSION ['user'] ['dept_seqno'],
										  $begin_time,
										  $end_time,
										  floatval($_POST['overtime_hours2']),
										  $_POST['overtime_reason'],
										  $_POST['overtime_fee_type1'],
										  $_POST['overtime_type1'],
										  $_POST['remark'],
										  $tmp_save);
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

	}
    $this->load->view('PageFooter_view',$data);
    //var_dump($result );exit; 
 }
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