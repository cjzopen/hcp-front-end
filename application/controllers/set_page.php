<?php 
session_start();
 
if (!empty($_POST['page_sel']))  $page_sel =$_POST['page_sel'];
 
$_SESSION['Abs_approve_page']= $page_sel;
 
   $sql = "";
   $conn = oci_connect("hcp","hcp","cthcp","UTF8");
 
    $sql  = " select company_id,
                               approve_seqno,
                               approver_emp_seqno,
                               workflow_seqno,
                               can_approve,
                               dept_id,
                               dept_name,
                               emp_id,
                               emp_name,
                               absence_ID,
							   absence_name,
                               begin_time,
                               end_time,
                               leave_reason,
                               days,
                               hours,
                               status_name,
                               agency_info
                          from ehr_leave_approve_v
                         where company_id =:company_id
                           and agency_emp_seqno =:approver_emp_seqno
                           and can_approve = 'Y'
                           order by begin_time,dept_id,emp_id";	
			   
		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':company_id', $_SESSION ['user'] ['company_id']);
		oci_bind_by_name($stid, ':approver_emp_seqno', $_SESSION ['user']['emp_seq_no']);	
 
		oci_execute($stid);
		$result =Array();
		 $cc =0;$cc2 =0;
		 
		 $pageSize =5;
		 $first_row_i = ($page-1)*$pageSize;
		 $last_row_i  = ($page-1)*$pageSize + ($pageSize-1);
		 //echo '<br/> $first_row_i='. $first_row_i.' $last_row_i='.$last_row_i;
		 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
		      
			  if ($cc>=$first_row_i && $cc<=$last_row_i)
				{
				$result[$cc2]['dept_id'] = $row['DEPT_ID'];
				$result[$cc2]['dept_name'] = $row['DEPT_NAME'];
				$result[$cc2]['emp_name'] = $row['EMP_NAME'];
				$result[$cc2]['absence_id'] = $row['ABSENCE_ID'];
				$result[$cc2]['absence_name'] = $row['ABSENCE_NAME'];	 
				$result[$cc2]['begin_time'] = $row['BEGIN_TIME'];
				$result[$cc2]['end_time'] = $row['END_TIME'];
				$cc2 = $cc2 +1;
				}	
		  $cc =  $cc + 1;
		}

 
	 var_dump($result);exit; 
		oci_free_statement($stid);
		oci_close($conn);	
 	
 echo json_encode ( $result );
exit;
?>