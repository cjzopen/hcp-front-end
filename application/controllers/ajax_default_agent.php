<?php
/*************************************************************\
 *  Description:
 *    ajax取得預設代理人
 *  $Rev: 38XX $
 *  $Date: 2015-05-20
 *  $Author: Yun $
 *  $LastChangedDate: 2015-05-20
 ****************************************************************************/

// 取得html參數
if (empty ( $_REQUEST ['begin_date'] )) {
	echo json_encode ( array (
			'error_code' => '3' 
	) );
	exit ();
} else {
	$begin_date = $_REQUEST ['begin_date'];
}

if (empty ( $_REQUEST ['end_date'] )) {
	echo json_encode ( array (
			'error_code' => '3' 
	) );
	exit ();
} else {
	$end_date = $_REQUEST ['end_date'];
}
$conn = oci_connect("hcp","hcp","cthcp","UTF8");	
	
// 從EHR_WF_AGENCY_RANGE判斷是否需要拆單
$sql = "select count('EWAR.PSN_ID') as NUMBER_COUNT
		FROM EHR_WF_AGENCY_RANGE EWAR
		INNER JOIN APP_USERS_BASE AUB ON EWAR.AGENCY_PSN_ID = AUB.PSN_ID
		AND AUB.SEG_SEGMENT_NO =:company_id
		where EWAR.PSN_ID = :psn_id
		AND EWAR.ASSIGN_DATE_B>TO_DATE(:begin_date,'yyyy-mm-dd')
		AND EWAR.ASSIGN_DATE_E<TO_DATE(:end_date,'yyyy-mm-dd')
		AND EWAR.IS_ACTIVE = 'Y'
		AND EWAR.SEG_SEGMENT_NO =:company_id";
		
$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ':company_id', $_SESSION['user']['company_id']);
oci_bind_by_name($stid, ':psn_id', $_SESSION ['user'] ['emp_seq_no']);
oci_bind_by_name($stid, ':begin_date', $begin_date);
oci_bind_by_name($stid, ':end_date', $end_date);	
oci_execute($stid);

$result =Array();
 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
			$result['NUMBER_COUNT'] = $row['NUMBER_COUNT'];
 }
 
$number_count = $result['NUMBER_COUNT'];
// 如果超過一筆則代理區間重複
if ($number_count > 0) {
	echo json_encode ( array (
			'error_code' => '2'
	) );
	exit ();
}

// 從EHR_WF_AGENCY_RANGE取得預設代理人資料
$sql = "SELECT EWAR.AGENCY_PSN_ID as AGENT_ID, AUB.USER_DESC AS AGENT_NAME, EWAR.ASSIGN_TYPE as ASSIGN_TYPE
		FROM EHR_WF_AGENCY_RANGE EWAR 
		INNER JOIN APP_USERS_BASE AUB ON EWAR.AGENCY_PSN_ID = AUB.PSN_ID 
		AND AUB.SEG_SEGMENT_NO =:company_id
		WHERE EWAR.PSN_ID = :psn_id 
		AND EWAR.ASSIGN_DATE_B<=TO_DATE(:begin_date,'yyyy-mm-dd') 
		AND EWAR.ASSIGN_DATE_E>=TO_DATE(:end_date,'yyyy-mm-dd') 
		AND EWAR.IS_ACTIVE = 'Y'
		AND EWAR.SEG_SEGMENT_NO =:company_id";

$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ':company_id', $_SESSION['user']['company_id']);
oci_bind_by_name($stid, ':psn_id', $_SESSION ['user'] ['emp_seq_no']);
oci_bind_by_name($stid, ':begin_date', $begin_date);
oci_bind_by_name($stid, ':end_date', $end_date);	
oci_execute($stid);

$result =Array();
 $cc =0;
 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
			$result[$cc]['agent_id'] = $row['AGENT_ID'];
			$result[$cc]['agent_name'] = $row['AGENT_NAME'];
			$result[$cc]['assign_type'] = $row['ASSIGN_TYPE'];				
			$cc =$cc + 1; 
 }
	 
 
// 如果超過一筆則代理區間重複，沒有則是無預設代理人
$result_count = count ( $result );
if ($result_count == 0) {
	echo json_encode ( array (
			'error_code' => '1' 
	) );
	exit ();
} elseif ($result_count > 1) {
	echo json_encode ( array (
			'error_code' => '2' 
	) );
	exit ();
} elseif ($result_count == 1) {
	echo json_encode ( $result ['0'] );
	exit ();
} else {
	echo 'data catch error';
	exit ();
}