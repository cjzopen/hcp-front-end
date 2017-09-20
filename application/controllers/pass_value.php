<?php 
session_start();
 
if (!empty($_POST['company_id']))  $company_id =$_POST['company_id'];
if (!empty($_POST['emp_seq_no']))  $emp_seq_no =$_POST['emp_seq_no'];
if (!empty($_POST['Cal_yy']))  $yy = $_POST ['Cal_yy'];
if (!empty($_POST['Cal_mm']))  $mm = (int)$_POST ['Cal_mm'];
if (!empty($_POST['Cal_dd']))  $dd = (int)$_POST ['Cal_dd'];

$_SESSION['Calendar_yy']= $yy;
$_SESSION['Calendar_mm']= $mm;  
$_SESSION['Calendar_dd']= $dd;  
   
if (strlen($mm) == 1) $mm ='0'.$mm;
if (strlen($dd) == 1) $dd ='0'.$dd;
$Calendar_SelDate = $yy.$mm.$dd;

$conn = oci_connect("hcp","hcp","cthcp","UTF8");
$sql = "select to_char(my_day,'YYYY-MM-DD') as my_day,workgroup_name,to_char(in_time,'YYYY-MM-DD') as shift_in_time,to_char(in_time,'YYYY-MM-DD HH24:MI:SS')  as in_time,to_char(out_time,'YYYY-MM-DD HH24:MI:SS') as out_time,holiday_code,holiday from ehr_calendar_v where company_id = '".$company_id."' and emp_seq_no = '".$emp_seq_no."' and to_char(my_day, 'YYYYMMDD') = to_char(to_date('".$Calendar_SelDate."','YYYYMMDD'),'YYYYMMDD')  and  rownum = 1";
			   
$stid = oci_parse($conn, $sql);
oci_execute($stid);
 $result = oci_fetch_array($stid, OCI_BOTH);
 
//$cal['sql']= $sql;
$cal['MY_DAY']= $result['MY_DAY'];
$cal['WORKGROUP_NAME']= $result['WORKGROUP_NAME'];
$cal['IN_TIME']= $result['IN_TIME'];
$cal['OUT_TIME']= $result['OUT_TIME'];

$_SESSION['user']['MY_DAY']= $result['MY_DAY'];
$_SESSION['user']['WORKGROUP_NAME']= $result['WORKGROUP_NAME'];
$_SESSION['user']['IN_TIME']= $result['IN_TIME'];
$_SESSION['user']['OUT_TIME']= $result['OUT_TIME'];
		
oci_free_statement($stid);
oci_close($conn);	
echo json_encode($cal); 
 
//print json_encode(array('message' => $_SESSION['Calendar_SelDate']));
//die();
exit;
?>