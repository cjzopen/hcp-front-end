<?php
Class AresCalendar extends CI_Model
{
	
 function GetSchedulInfo($Calendar_SelDate,$company_id,$emp_seq_no)
  {  
 //  if (empty($_SESSION['Calendar_SelDate'])) $_SESSION['Calendar_SelDate']= date('Ymd');
//$this->load->library('Session');
        $conn = oci_connect("hcp","hcp","cthcp","UTF8");
		$sql = "select to_char(my_day,'YYYY-MM-DD') as my_day,
				   workgroup_name,
				   to_char(in_time,'YYYY-MM-DD') as shift_in_time,
				   to_char(in_time,'YYYY-MM-DD HH24:MI:SS')  as in_time,
				   to_char(out_time,'YYYY-MM-DD HH24:MI:SS') as out_time,
				   holiday_code,
				   holiday
			  from ehr_calendar_v
			 where company_id = '".$company_id."'
			   and emp_seq_no = '".$emp_seq_no."'
			 and to_char(my_day, 'YYYYMMDD') = to_char(to_date('".$Calendar_SelDate."','YYYYMMDD'),'YYYYMMDD')  and  rownum = 1";
			   
		$stid = oci_parse($conn, $sql);
		
		//oci_bind_by_name($stid, ':company_id', $company_id);
		//oci_bind_by_name($stid, ':emp_seq_no', $emp_seq_no);		
		//oci_bind_by_name($stid, ':ymd', $_SESSION['Calendar_SelDate']);
		oci_execute($stid, OCI_DEFAULT);
 
	    $result = oci_fetch_array($stid, OCI_BOTH+ OCI_RETURN_NULLS);
		
        $_SESSION ['user']['MY_DAY']= $result['MY_DAY'];
        $_SESSION ['user']['WORKGROUP_NAME']= $result['WORKGROUP_NAME'];
		$_SESSION ['user']['IN_TIME']= $result['IN_TIME'];
		$_SESSION ['user']['OUT_TIME']= $result['OUT_TIME'];		
		
	//	echo '$sql='.$sql;
	 //var_dump($result);exit; 
		oci_free_statement($stid);
		oci_close($conn);		
return $result;
 }

 // Get Arrar (KEY) =  VAL
 // 傳入參數: 系統年月,公司別,員工工號,系統日(1-31)
 // KEY:day 
 // VAL:Holiday_code
 function GetSchedulForSetColor($yymm,$company_id,$emp_seq_no,$day)
  {  
        $result = array();
        $conn = oci_connect("hcp","hcp","cthcp");
		$sql = "select  
				   EXTRACT(day FROM in_time) as day,
				   holiday_code as holiday_code
			  from ehr_calendar_v
			 where company_id = '".$company_id."'
			   and emp_seq_no = '".$emp_seq_no."'
			 and to_char(my_day, 'YYYYMM') = to_char(to_date('".$yymm."','YYYYMM'),'YYYYMM') ";
			   
		$stid = oci_parse($conn, $sql);
		oci_execute($stid);
 
		while (($row = oci_fetch_array($stid,OCI_ASSOC)) != false) {
			//var_dump($row);
		    if (isset($row['HOLIDAY_CODE']))  
			 	$result[$row['DAY']] = $row['HOLIDAY_CODE'];
			 	else
				$result[$row['DAY']] ="N";
			
		//當前日.SYCU	
         if ( $row['DAY']== $day)  $result[$row['DAY']] ="SYCU";  
		}
      
	//	echo '$sql='.$sql;
	 //var_dump($result);exit; 
	oci_free_statement($stid);
	oci_close($conn);		
return $result;
 } 
}
?>