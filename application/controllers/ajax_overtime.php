<?php
session_start();
/*  
 *  计算加班时数
 *  Create by Boll Yuan 
 *  $HeadURL: http://192.168.0.126:9001/svn/EHR/trunk/eHR/ess/includes/ajax_overtime_DB.php $
 *  $Id: ajax_overtime_DB.php 3028 2010-12-06 11:01:35Z dennis $
 *  $Rev: 3028 $ 
 *  $Date: 2010-12-06 19:01:35 +0800 (星期一, 06 十二月 2010) $
 *  $Author: dennis $   
 *  $LastChangedDate: 2010-12-06 19:01:35 +0800 (星期一, 06 十二月 2010) $
 *********************************************************/
$r = array();
if (! empty ( $_POST ['overtime_date'] ) && ! empty ( $_POST['begin_time'])&& ! empty ( $_POST['end_time']))
	{
		//增加跨日判斷 by Yun 2015-12-04
		// 如果加班結束時間小於開始時間, 表示其跨天
		$start_time = $_POST['overtime_date'].' '.$_POST['begin_time'];
		$end_time = strtotime($_POST['overtime_date'].' '.$_POST['end_time']);
		if ($end_time < strtotime($start_time)) {
			$end_time = date ('Y-m-d H:i', strtotime("+1 day",$end_time));
		}else{
			$end_time = date ('Y-m-d H:i', $end_time);
		}
	$p_hours ="0";$p_errmsg="";
	$conn = oci_connect("hcp","hcp","cthcp","UTF8");
	$stmt  ="begin begin pk_erp.p_set_segment_no('".$_SESSION['user']['company_id']."'); end; begin pk_overtime.p_overtime_hours(p_seg_segment_no => :p_seg_segment_no,p_psn_id => :p_psn_id,p_begintemp => to_date(:p_begintemp,'YYYY-MM-DD'),p_begintime => to_date(:p_begintime,'YYYY-MM-DD HH24:MI:SS'),p_endtime => to_date(:p_endtime,'YYYY-MM-DD HH24:MI:SS'),p_hours => :p_hours,p_errmsg => :p_errmsg); end ;end;";

	$stid = oci_parse($conn, $stmt);
	oci_bind_by_name($stid, ':p_seg_segment_no', $_SESSION ['user'] ['company_id']);
	oci_bind_by_name($stid, ':p_begintemp', $_POST['overtime_date']);
	oci_bind_by_name($stid, ':p_psn_id', $_SESSION['user']['emp_seq_no']);
	oci_bind_by_name($stid, ':p_begintime', $start_time);
	oci_bind_by_name($stid, ':p_endtime', $end_time);
	oci_bind_by_name($stid, ':p_hours', $p_hours);
	oci_bind_by_name($stid, ':p_errmsg', $p_errmsg);
	oci_execute($stid);

	$r['hours'] = $p_hours;$r['errmsg'] =$p_errmsg;
//print json_encode(array('hours' => $p_hours,'errmsg' =>$p_errmsg));
//die();
	/* add by dennis 2010-12-06 for auto get holidy type*/
	$sql = "select holiday from hr_carding
		 where psn_seg_segment_no = :company_id
		   and psn_id             = :emp_seqno
		   and cday               = to_date(:the_date,'yyyy-mm-dd')";

	$stid = oci_parse($conn, $sql);
	oci_bind_by_name($stid, ':company_id', $_SESSION ['user'] ['company_id']);
	oci_bind_by_name($stid, ':emp_seqno', $_SESSION['user']['emp_seq_no']);
	oci_bind_by_name($stid, ':the_date', $_POST['overtime_date']);
 
	oci_execute($stid);
	$r['day_type']='';
	 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
		 $r['day_type'] = $row['HOLIDAY'];
	} 
		 
	$sql = "select to_char(OVERTIMEBEGIN,'hh24:mi') OVERTIMEBEGIN from hr_carding";
	$sql .= " where psn_seg_segment_no = '".$_SESSION['user']['company_id']."'";
	$sql .= " and psn_id             = '".$_SESSION['user']['emp_seq_no']."'";
	$sql .= " and cday               = to_date('".$_POST['overtime_date']."','yyyy-mm-dd')";
     $stid = oci_parse($conn, $sql);
	oci_execute($stid);
	 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
		 $r['OVERTIMEBEGIN'] = $row['OVERTIMEBEGIN'];
		} 	
 	$r['OVERTIMEBEGIN'] = is_null($r['OVERTIMEBEGIN']) ? '00:00' : $r['OVERTIMEBEGIN'];  									 
	$r['day_type'] = is_null($r['day_type']) ? 'N' : $r['day_type'];
	
	//$r['hours'] ="3";
	oci_free_statement($stid);
	oci_close($conn);		
	//echo json_encode($r);
	exit(json_encode($r));
	}
exit;
?>