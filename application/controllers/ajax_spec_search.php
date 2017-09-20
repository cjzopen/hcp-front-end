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
 
$p_family_type = '';
$p_death_date = '';
$p_family_name = '';
//如果是特別假，則取得特別假detail
 $cc =0;
 $result =Array();
 $rows = array();
 if (!empty ( $_REQUEST ['func'] ) && $_REQUEST ['func'] == 'GetFamilyType') {
		$sql = " select funeral_id||'#'|| family_type || '-'|| family_type_name||'('||FAMILY_NAME ||')'  as family_type
                 from ehr_funeral_v
                where company_id = :company_id
                  and emp_seq_no = :emp_seqno
		          and absence_type_id = :abs_type_id";
				
		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':company_id', $_SESSION ['user'] ['company_id']);
		oci_bind_by_name($stid, ':emp_seqno', $_SESSION ['user'] ['emp_seq_no']);
		oci_bind_by_name($stid, ':abs_type_id', $_REQUEST ['abs_type_id']);		
		oci_execute($stid);
		
		 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
	//	$result[$cc]['FAMILY_TYPE'] = $row['FUNERAL_ID'];
		$result[$cc]= $row['FAMILY_TYPE'];
			$cc =$cc + 1; 
		 }
 	 
   //echo json_encode(array('aaData'=> $rows));
  //var_dump($result);
	oci_free_statement($stid);
	oci_close($conn);	 
 }
 
echo json_encode ( $result );
exit ();