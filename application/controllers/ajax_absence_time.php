<?php
session_start();

/*************************************************************\
 *  Description:
 *    ajax眔叫安计
 *  $Rev: 38XX $
 *  $Date: 2015-05-20
 *  $Author: Yun $
 *  $LastChangedDate: 2015-07-20
 ****************************************************************************/
$conn = oci_connect("hcp","hcp","cthcp","UTF8");
// 取得html參數
 //var_dump($_REQUEST);
if (empty ( $_REQUEST ['begin_date'] )) {
	echo json_encode ( array (
			'error_code' => '3' 
	) );
	exit ();
} else {
	$begin_date = $_REQUEST ['begin_date'];
}

if (empty ( $_REQUEST ['begin_time'] )) {
	echo json_encode ( array (
			'error_code' => '3' 
	) );
	exit ();
} else {
	$begin_time = $_REQUEST ['begin_time'];
}

if (empty ( $_REQUEST ['end_date'] )) {
	echo json_encode ( array (
			'error_code' => '3' 
	) );
	exit ();
} else {
	$end_date = $_REQUEST ['end_date'];
}

if (empty ( $_REQUEST ['end_time'] )) {
	echo json_encode ( array (
			'error_code' => '3' 
	) );
	exit ();
} else {
	$end_time = $_REQUEST ['end_time'];
}

if (empty ( $_REQUEST ['absence_id'] )) {
	echo json_encode ( array (
			'error_code' => '3' 
	) );
	exit ();
} else {
	$absence_seq_no = (int)$_REQUEST ['absence_id'];
	$sql = "select absence_id from ehr_absence_type_v where absence_seq_no = :absence_seq_no";
	$stid = oci_parse($conn, $sql);
	oci_bind_by_name($stid, ':absence_seq_no', $absence_seq_no);		 
	oci_execute($stid);	
	$result =Array();
	 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
				$result['absence_id'] = $row['ABSENCE_ID'];
	 }	
 
	$p_reason = $result['absence_id'];
}

$p_family_type = '';
$p_death_date = '';
$p_family_name = '';
//如果是特別假，則取得特別假detail
if (! empty ( $_REQUEST ['is_spec'] ) && $_REQUEST ['is_spec'] == 1) {
	if (!empty ( $_REQUEST ['funeral_id'] )) {
		
		$sql = "select family_type, family_name,  to_char(death_date,'YYYY-MM-DD') as death_date from ehr_funeral_v";
        $sql .= " where funeral_id = :funeral_id";
				
		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':funeral_id', $_REQUEST ['funeral_id']);		 
		oci_execute($stid);
		
		$result =Array();
		 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
		$result['FAMILY_TYPE'] = $row['FAMILY_TYPE'];
		$result['DEATH_DATE']= $row['DEATH_DATE'];
		$result['FAMILY_NAME']= $row['FAMILY_NAME'];
		
		$p_family_type = $result['FAMILY_TYPE'];
		$p_death_date = $result['DEATH_DATE'];
		$p_family_name = $result['FAMILY_NAME'];		
		 }	

	}else{
		$p_family_type = '';
		$p_death_date = '';
		$p_family_name = '';
	}
}else{
	$p_family_type = '';
	$p_death_date = '';
	$p_family_name = '';
}

// 開始取得時數
// 設定輸出陣列
$out_array = array (
		'hours' => '',
		'days' => '',
		'error' => '',
		'confirm' => '' 
);
//設定請假日期
$begin_date .=" ".$begin_time;
$end_date .= " ".$end_time;

$stmt = "begin p_absence_check_flow_tw(p_serno=>'',p_id_no_sz=>:p_id_no_sz,p_dept=>:p_dept,p_reason=>:p_reason, p_datetime_b=>to_date(:p_datetime_b,'YYYY-MM-DD HH24:MI'),";
$stmt .="p_datetime_e=>to_date(:p_datetime_e,'YYYY-MM-DD HH24:MI'),p_family_type=>:p_family_type,p_death_date=>to_date(:p_death_date,'YYYY-MM-DD'),p_hours=>:p_hours, p_days=>:p_days,";
$stmt .="p_segment_no=>:p_segment_no,p_error=>:p_error,p_confirm=>:p_confirm,p_error_type=>'MSG',p_family_name=>:p_family_name,p_intrans_hour=>0,p_intrans_day=>0); end;";
 
// 綁定參數
$stid = oci_parse($conn, $stmt);
oci_bind_by_name($stid,':p_id_no_sz', $_SESSION ['user'] ['emp_id'],100);
oci_bind_by_name($stid,':p_dept', $_SESSION ['user'] ['dept_id'],100);
oci_bind_by_name($stid,':p_reason', $p_reason,10);
oci_bind_by_name($stid,':p_datetime_b', $begin_date,50);
oci_bind_by_name($stid,':p_datetime_e', $end_date,50);
oci_bind_by_name($stid,':p_family_type', $p_family_type,10);
oci_bind_by_name($stid,':p_death_date', $p_death_date,50);
oci_bind_by_name($stid,':p_hours', $out_array ['hours'],10);
oci_bind_by_name($stid,':p_days', $out_array ['days'],10);
oci_bind_by_name($stid,':p_segment_no', $_SESSION ['user'] ['company_id'],10);
oci_bind_by_name($stid,':p_error', $out_array ['error'],200);
oci_bind_by_name($stid,':p_confirm', $out_array ['confirm'],50);
oci_bind_by_name($stid,':p_family_name', $p_family_name,100);		
 
/*
 echo '<br />p_id_no_sz:'.$_SESSION ['user'] ['emp_id'];
 echo '<br />p_dept:'.$_SESSION ['user'] ['dept_id'];
 echo '<br />p_reason:'.$p_reason;
 echo '<br />begin_date:'.$begin_date;
 echo '<br />end_date:'.$end_date;
 echo '<br />p_family_type:'.$p_family_type;
 echo '<br />p_death_date:'.$p_death_date;
 echo '<br />p_family_name:'.$p_family_name;  
 echo '<br />p_segment_no:'.$_SESSION ['user'] ['company_id'];*/
 //exit();
 
// Execute the statement but do not commit
oci_execute($stid);

// The status parameter will be negative if the procedure encountered a problem
if ( !$out_array ['error'] ) {
 
	die ($out_array['error']."\n");
}
 
oci_free_statement($stid);
oci_close($conn);	
		 
echo json_encode ( $out_array );
exit ();