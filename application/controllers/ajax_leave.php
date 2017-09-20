<?php
session_start();
/*  
 *   檢驗開始間、結束時間是不是在排程時間內
 *  Create by Boll   2009-04-20
 *  $HeadURL: http://192.168.0.126:9001/svn/EHR/trunk/eHR/ess/includes/ajax_overtime_DB.php $
 *  $Id: ajax_overtime_DB.php 3028 2010-12-06 11:01:35Z dennis $
 *  $Rev: 3028 $ 
 *  $Date: 2010-12-06 19:01:35 +0800 (星期一, 06 十二月 2010) $
 *  $Author: Boll $   
 *  $LastChangedDate: 2010-12-06 19:01:35 +0800 (星期一, 06 十二月 2010) $
 *********************************************************/
$r = array();
if (! empty ( $_POST ['begin_date'] ) && ! empty ( $_POST['begin_time'])&& ! empty ( $_POST['end_date'])&& ! empty ( $_POST['end_time']))
	{
	 $conn = oci_connect("hcp","hcp","cthcp","UTF8");
	 
	 $sql="SELECT COUNT(*) CNT
		   FROM HR_CARDING
		  WHERE PSN_ID = '".$_SESSION['user']['emp_seq_no']."'
			AND PSN_SEG_SEGMENT_NO = '".$_SESSION['user']['company_id']."'
			AND (   (  BREAKBEGIN IS NULL
						 AND to_date('".$_POST['begin_time']."','YYYY-MM-DD HH24:MI') BETWEEN INTIME AND OUTTIME )
				  OR (  BREAKBEGIN IS NOT NULL
						 AND (   to_date('".$_POST['begin_time']."','YYYY-MM-DD HH24:MI') BETWEEN DECODE(FREETYPE,'N',INTIME,intime-NVL(FREETIME/60/24,0)) AND BREAKBEGIN
							  OR to_date('".$_POST['begin_time']."','YYYY-MM-DD HH24:MI') BETWEEN BREAKEND AND DECODE(FREETYPE,'N',OUTTIME,outtime+nvl(freetime2/60/24,0)) )
					 )
			 )
		";
 	$stid = oci_parse($conn, $sql);
	oci_execute($stid);
	$r['CNT']='';
	 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
		 $r['cnt'] = $row['CNT'];
	} 	
	if($r['cnt']==0) exit('1');
	
    $sql=" SELECT COUNT(*) CNT
		       FROM HR_CARDING
		      WHERE PSN_ID = '".$_SESSION['user']['emp_seq_no']."'
		        AND PSN_SEG_SEGMENT_NO =  '".$_SESSION['user']['company_id']."'
		        AND (   (  BREAKBEGIN IS NULL
				             AND to_date('".$_POST['end_time']."','YYYY-MM-DD HH24:MI') BETWEEN INTIME AND OUTTIME )
				      OR (  BREAKBEGIN IS NOT NULL
				             AND (   to_date('".$_POST['end_time']."','YYYY-MM-DD HH24:MI') BETWEEN INTIME AND BREAKBEGIN
				                  OR to_date('".$_POST['end_time']."','YYYY-MM-DD HH24:MI') BETWEEN BREAKEND AND OUTTIME )
				         )
	             )
			";
 	
	$r['CNT']='';
	 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
		 $r['cnt'] = $row['CNT'];
	} 	
	if($r['cnt']==0) exit('2');
		
	exit('ok');		
	 
	}
exit;
?>