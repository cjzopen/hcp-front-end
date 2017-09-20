<?php
Class AresApprove_Model extends CI_Model
{

 function GetApproveInfo($company_id,$approver_emp_seqno,$applytype,$page,$pageSize )
  {     
   $sql = "";
   $conn = oci_connect("hcp","hcp","cthcp","UTF8");
   
   
   if ($page == '')  
   {
   $rowCount  =Array();	   
   if ($applytype == 'absence')
	{
      $sql  = " select count(0) as RowCounts from ehr_leave_approve_v
                         where company_id ='".$company_id."'
                           and agency_emp_seqno ='".$approver_emp_seqno."'
                           and can_approve = 'Y' ";		 
	}
   if ($applytype == 'overtime')
	{
      $sql  = " select count(0) as RowCounts from ehr_overtime_approve_v eoa
                         where company_id ='".$company_id."'
                           and agency_emp_seqno ='".$approver_emp_seqno."'
                           and can_approve = 'Y' 
                           and  not exists
                          (select 1 from  ehr_concurrent_request_detail ecrd
                           where ecrd.workflow_seqno = eoa.workflow_seqno
                             and ecrd.approver_emp_seqno = '".$approver_emp_seqno."'
                             and ecrd.po_success is null)						   
						   ";		 
	}	
   $stid = oci_parse($conn, $sql);
   oci_execute($stid);
		 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
			 $rowCount['ROWCOUNTS'] = $row['ROWCOUNTS'];
		} 

   return $rowCount['ROWCOUNTS'];		
   }
   
  switch($applytype)
    { 
	case 'absence':
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
                               agency_info,
							   'absence' as apply_type
                          from ehr_leave_approve_v
                         where company_id =:company_id
                           and agency_emp_seqno =:approver_emp_seqno
                           and can_approve = 'Y'
                           order by begin_time,dept_id,emp_id";	
				break;
		
	case 'overtime':		
	   $sql = "select company_id,
                               approve_seqno,
                               agency_emp_seqno as approver_emp_seqno,
                               workflow_seqno,
                               dept_id,
                               dept_name1 as dept_name,
                               emp_id,
                               emp_name,
                               overtime_date,
                               begin_time,
                               end_time,
                               hours,
                               reason,
							   can_approve,
                               overtime_type,
                               overtime_fee,
                               status_name,
                               'overtime' as apply_type,
                               agency_info
                          from ehr_overtime_approve_v eoa
                         where company_id = :company_id
                           and agency_emp_seqno = :approver_emp_seqno
						    and can_approve = 'Y'
                           and  not exists
                          (select 1 from  ehr_concurrent_request_detail ecrd
                           where ecrd.workflow_seqno = eoa.workflow_seqno
                             and ecrd.approver_emp_seqno =  :approver_emp_seqno
                             and ecrd.po_success is null)
							 order by begin_time,dept_id,emp_id";	
				break;					 
                break;
                default: break;
            }// end switch	
		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':company_id', $company_id);
		oci_bind_by_name($stid, ':approver_emp_seqno', $approver_emp_seqno);	
 		
		oci_execute($stid);
		$result =Array();
		 $cc =0;$cc2 =0;
		 
		 $first_row_i = ($page-1)*$pageSize;
		 $last_row_i  = ($page-1)*$pageSize + ($pageSize-1);
		 //echo '<br/> $sql='.$sql;
		 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
		      
			  if ($cc>=$first_row_i && $cc<=$last_row_i)
				{
				   if ($applytype == 'absence')
					{					
					$result[$cc2]['dept_id'] = $row['DEPT_ID'];
					$result[$cc2]['dept_name'] = $row['DEPT_NAME'];
					$result[$cc2]['emp_name'] = $row['EMP_NAME'];
					$result[$cc2]['absence_id'] = $row['ABSENCE_ID'];
					$result[$cc2]['absence_name'] = $row['ABSENCE_NAME'];	 
					$result[$cc2]['begin_time'] = $row['BEGIN_TIME'];
					$result[$cc2]['end_time'] = $row['END_TIME'];
					$result[$cc2]['company_id'] = $row['COMPANY_ID'];
					$result[$cc2]['approve_seqno'] = $row['APPROVE_SEQNO'];
					$result[$cc2]['approver_emp_seqno'] = $row['APPROVER_EMP_SEQNO'];
					$result[$cc2]['workflow_seqno'] = $row['WORKFLOW_SEQNO'];
					$result[$cc2]['can_approve'] = $row['CAN_APPROVE'];
					$result[$cc2]['leave_reason'] = $row['LEAVE_REASON'];
					$result[$cc2]['days'] = $row['DAYS'];
					$result[$cc2]['hours'] = $row['HOURS'];
					$result[$cc2]['status_name'] = $row['STATUS_NAME'];
					$result[$cc2]['apply_type'] = $row['APPLY_TYPE'];
					}
				   if ($applytype == 'overtime')
					{				
					$result[$cc2]['dept_id'] = $row['DEPT_ID'];
					$result[$cc2]['dept_name'] = $row['DEPT_NAME'];
					$result[$cc2]['emp_name'] = $row['EMP_NAME'];
					$result[$cc2]['overtime_type'] = $row['OVERTIME_TYPE'];
					$result[$cc2]['overtime_fee'] = $row['OVERTIME_FEE'];	
					$result[$cc2]['overtime_date'] = $row['OVERTIME_DATE'];						
					$result[$cc2]['begin_time'] = $row['BEGIN_TIME'];
					$result[$cc2]['end_time'] = $row['END_TIME'];
					$result[$cc2]['company_id'] = $row['COMPANY_ID'];
					$result[$cc2]['approve_seqno'] = $row['APPROVE_SEQNO'];
					$result[$cc2]['approver_emp_seqno'] = $row['APPROVER_EMP_SEQNO'];
					$result[$cc2]['workflow_seqno'] = $row['WORKFLOW_SEQNO'];
					$result[$cc2]['can_approve'] = $row['CAN_APPROVE'];
					$result[$cc2]['hours'] = $row['HOURS'];
					$result[$cc2]['status_name'] = $row['STATUS_NAME'];
					$result[$cc2]['apply_type'] = $row['APPLY_TYPE'];
					}					
				//$result[$cc2]['agency_info'] = $row['AGENCY_INFO'];				
				$cc2 = $cc2 +1;
				}	
		  $cc =  $cc + 1;
		}

 
	// var_dump($result);exit; 
		oci_free_statement($stid);
		oci_close($conn);		
 
return $result;
 }

        /**
        *   Check parameter workflow type
        *   @param $flowtype string, must be 'absence' / 'overtime' / 'cancel_absence'
        *   @return void, if not pass, raise trigger error.
        *   @author: dennis 2006-04-17 20:30:07
        *   @last update: 2006-04-17 20:30:13 by dennis
        *   @update log:
        *       1. add new flow type 'cancel_absence' by dennis 2006-05-09 19:06:15
        *   Copy from AresAttend.class.php
        *  last update by dennis 2009118
        */
        private function _checkFlowType($flowtype)
        {
            $flow_type_array = array('absence','overtime','cancel_absence','trans','nocard','resign','user_defined');
            if (!in_array(strtolower($flowtype),$flow_type_array))
            {
                trigger_error('Programming Error: Unknow Workflow Type, Must be <b>absence</b>,<b>overtime</b>,<b>trans</b>,<b>nocard</b>,<b>resign</b>,<b>cancel_absence</b> or <b>user_defined</b>. <br/>Current Workflow Type is '.$flowtype,E_USER_ERROR);
            }
        }// end function _checkFlowType(); 
/**
	 * Get Approve result list
	 * @param string $company_id company id 
	 * @param string $workflow_seqno workflow apply sequence number
	 * @param string $apply_type workflow type
	 * @param string $menu_code the user defined flow menu code
	 * @return array the apply information array
	 * @author Dennis 2006-04-04 14:24:52
	 */
	function GetApproveResultList($company_id,
								  $workflow_seqno,
								  $apply_type = 'overtime',
								  $menu_code = '')
	{
		$conn = oci_connect("hcp","hcp","cthcp","UTF8");
		$this->_checkFlowType($apply_type);
		switch($apply_type)
		{
			case 'absence':
				$sql_str = <<<eof
					select b.id_no_sz as emp_id,
						   b.name_sz as emp_name,
						   a.absence_id||'-'||a.absence_name as absence_name,
						   a.begin_time,
						   a.end_time,
						   a.hours,
						   a.days,
						   a.reason,
						   a.flow_status,
						   a.status_name
					  from ehr_leave_in_flow_v a, hr_personnel_base b
					 where a.company_id = b.seg_segment_no
					   and a.emp_seq_no = b.id
					   and b.seg_segment_no = :company_id
					   and a.leave_flow_seqno = :workflow_seqno
eof;
			break;
			case 'overtime':
				$sql_str = <<<eof
					select b.id_no_sz as emp_id,
						   b.name_sz as emp_name,
						   a.begin_time,
						   a.end_time,
						   a.overtime_fee_name,
						   a.overtime_type_name,
						   a.hours,
						   a.flow_status,
						   a.status_name
					  from ehr_overtime_in_flow_v a,  hr_personnel_base b
					 where a.company_id = b.seg_segment_no
					   and a.emp_seq_no = b.id
					   and a.company_id = :company_id
					   and a.overtime_flow_seqno = :workflow_seqno
eof;
			break;
			case 'cancel_absence':
				$sql_str = <<<eof
					select b.id_no_sz as emp_id,
						   b.name_sz as emp_name,
						   a.absence_id||'-'||a.absence_name as absence_name,
						   a.begin_time,
						   a.end_time,
						   a.hours,
						   a.days,
						   a.reason,
						   a.flow_status,
						   a.status_name
					  from ehr_cancel_leave_in_flow_v a, hr_personnel_base b
					 where a.company_id = b.seg_segment_no
					   and a.emp_seq_no = b.id
					   and a.company_id = :company_id
					   and a.leave_flow_seqno = :workflow_seqno
eof;
			break;
			case 'trans':
				$sql_str = <<<eof
					  select a.trans_flow_sz_id as trans_flow_seqno,
							 a.seg_segment_no,
							 a.psn_id,
							 b.emp_id,
							 b.emp_name,
							 b.dept_id,
							 b.dept_name,
							 a.validdate as TRANS_DATE,
							 pk_personnel_msg.f_transtype_master_no(a.seg_segment_no,a.issuetype) trans_type,
							 pk_personnel_msg.f_transtype_master_desc(a.seg_segment_no,a.issuetype) trans_name,
							 pk_department_message.f_dept_msg(a.seg_segment_no,a.newdepartment,a.validdate,'01') segment_no_sz,
							 pk_department_message.f_dept_msg(a.seg_segment_no,a.newdepartment,a.validdate,'02') new_dept_name,
							 'trans' as apply_type,
							 a.issuetype,
							 a.status,
							 a.create_date,
							 decode(a.status,'00','暫存','01','提交','02','流程中','03','核准','04','駁回','05','作廢','06','異常') as status_name
						from hr_trans_flow_sz a,ehr_employee_v b
					   where a.seg_segment_no=b.company_id
						 and a.psn_id=b.emp_seq_no
						 and a.seg_segment_no = :company_id
						 and a.trans_flow_sz_id = :workflow_seqno
eof;
			break;
			case 'nocard':
				$sql_str = <<<eof
							SELECT A.NOCARD_FLOW_SEQNO,
								   A.EMP_SEQ_NO PSN_ID,
								   B.ID_NO_SZ AS EMP_ID,
								   B.NAME_SZ AS EMP_NAME,
								   C.SEGMENT_NO_SZ AS DEPT_ID,
								   C.SEGMENT_NAME AS DEPT_NAME,
								   A.APPLY_DATE,
								   A.NOCARD_DATE,
								   A.NOCARD_DATE2,
								   A.NOCARD_DATE3,
								   A.NOCARD_DATE4,
								   A.NOCARD_DATE5,
								   A.NOCARD_DATE6,
								   A.SHIFTTYPE_NAME,
								   A.NOCARDING_NAME,
								   A.SHIFTTYPE_NAME2,
								   A.NOCARDING_NAME2,
								   A.SHIFTTYPE_NAME3,
								   A.NOCARDING_NAME3,
								   A.SHIFTTYPE_NAME4,
								   A.NOCARDING_NAME4,
								   A.SHIFTTYPE_NAME5,
								   A.NOCARDING_NAME5,
								   A.SHIFTTYPE_NAME6,
								   A.NOCARDING_NAME6,
								   'nocard' AS APPLY_TYPE,
								   A.FLOW_STATUS,
								   A.CREATE_DATE,
								   A.STATUS_NAME,
				decode(a.shifttype,
					  '',
					  '',
					  f_nocard_msg(a.COMPANY_ID, a.shifttype, '01') || ' :: ' ||
					  to_char(a.NOCARD_DATE, 'yyyy/mm/dd hh24:mi') || ' :: ' ||
					  f_nocard_msg(a.COMPANY_ID, a.nocarding_id, '02')||'<hr/>')||
			   decode(a.shifttype2,
					  '',
					  '',
					  f_nocard_msg(a.COMPANY_ID, a.shifttype2, '01') || ' :: ' ||
					  to_char(a.NOCARD_DATE2, 'yyyy/mm/dd hh24:mi') || ' :: ' ||
					  f_nocard_msg(a.COMPANY_ID, a.nocarding_id2, '02')||'<hr/>')||
			   decode(a.shifttype3,
					  '',
					  '',
					  f_nocard_msg(a.COMPANY_ID, a.shifttype3, '01') || ' :: ' ||
					  to_char(a.NOCARD_DATE3, 'yyyy/mm/dd hh24:mi') || ' :: ' ||
					  f_nocard_msg(a.COMPANY_ID, a.nocarding_id3, '02')||'<hr/>')||
			   decode(a.shifttype4,
					  '',
					  '',
					  f_nocard_msg(a.COMPANY_ID, a.shifttype4, '01') || ' :: ' ||
					  to_char(a.NOCARD_DATE4, 'yyyy/mm/dd hh24:mi') || ' :: ' ||
					  f_nocard_msg(a.COMPANY_ID, a.nocarding_id4, '02')||'<hr/>')||
			  decode(a.shifttype5,
					  '',
					  '',
					  f_nocard_msg(a.COMPANY_ID, a.shifttype5, '01') || ' :: ' ||
					  to_char(a.NOCARD_DATE5, 'yyyy/mm/dd hh24:mi') || ' :: ' ||
					  f_nocard_msg(a.COMPANY_ID, a.nocarding_id5, '02')||'<hr/>')||
			   decode(a.shifttype6,
					  '',
					  '',
					  f_nocard_msg(a.COMPANY_ID, a.shifttype6, '01') || ' :: ' ||
					  to_char(a.NOCARD_DATE6, 'yyyy/mm/dd hh24:mi') || ' :: ' ||
					  f_nocard_msg(a.COMPANY_ID, a.nocarding_id6, '02')||'<hr/>') as nocard_desc 								       
					 , A.REASON as REMARK									   
							  FROM EHR_NOCARD_IN_FLOW_V A,  HR_PERSONNEL_BASE B,  GL_SEGMENT C
							 WHERE A.COMPANY_ID = B.SEG_SEGMENT_NO
							   AND A.EMP_SEQ_NO = B.ID
							   AND B.SEG_SEGMENT_NO_DEPARTMENT = C.SEGMENT_NO
							   AND B.SEG_SEGMENT_NO = C.SEG_SEGMENT_NO
							   AND A.COMPANY_ID = :COMPANY_ID
							   AND A.NOCARD_FLOW_SEQNO = :WORKFLOW_SEQNO

eof;
			break;
			case 'resign':
				$sql_str = <<<eof
				select a.resign_flow_seqno,
					   a.emp_seq_no psn_id,
					   b.id_no_sz as emp_id,
					   b.name_sz as emp_name,
					   c.segment_no_sz as dept_id,
					   c.segment_name as dept_name,
					   a.apply_date,
					   a.out_type,
					   a.out_type_name,
					   a.out_date,
					   a.out_reason_code,
					   a.out_reason_name,
					   'resign' as apply_type,
					   a.flow_status,
					   a.create_date,
					   a.status_name 
					   from ehr_resign_in_flow_v a,  hr_personnel_base b,  gl_segment c
					  where a.company_id = b.seg_segment_no
						and a.emp_seq_no = b.id
						and b.seg_segment_no_department = c.segment_no
						and b.seg_segment_no = c.seg_segment_no
						and a.company_id = :company_id
						and a.resign_flow_seqno = :workflow_seqno
			   
eof;
			break;
			case 'user_defined':
				$sql_str = <<<eof
					select * from udwf_{$menu_code}_approve_v
					 where  company_id = :company_id
					   and  workflow_seqno = :workflow_seqno
eof;
				break;
			default: break;
		}
		$stid = oci_parse($conn, $sql_str);
		oci_bind_by_name($stid, ':company_id', $_SESSION ['user'] ['company_id']);	
		oci_bind_by_name($stid, ':workflow_seqno', $workflow_seqno);
		oci_execute($stid);
		$result = oci_fetch_array($stid, OCI_BOTH);
		  
	 oci_free_statement($stid);
	 oci_close($conn);		  
		return $result;
	}
	/**
	 * 取得排程 工作批號
	 */
	private function _getReqSeqNo()
	{
	$conn = oci_connect("hcp","hcp","cthcp","UTF8");
	$nextval  =Array();	   
    $sql  = "select ehr_concurrent_request_s.nextval request_no from dual";		 
    $stid = oci_parse($conn, $sql);
    oci_execute($stid);
		 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
			 $nextval['REQUEST_NO'] = $row['REQUEST_NO'];
		} 

 	oci_free_statement($stid);
	oci_close($conn);	
    return $nextval['REQUEST_NO'];	
	}

	/**
	 * 將取得排程 工作批號 寫入DB
	 * @param number $req_no
	 * @param string $batch_type
	 */
	private function _addBatchReq($req_no,$batch_type)
	{
	$conn = oci_connect("hcp","hcp","cthcp","UTF8");		
	$sql = "
			insert into ehr_concurrent_request
			  (request_no, data_from, request_emp_no, submit_date, status)
			values
			  (:req_no,
			   :batch_type,
			   :emp_id,
			   sysdate,
			   'N')";
 
		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':req_no', $req_no);	
		oci_bind_by_name($stid, ':batch_type', $batch_type);	
		oci_bind_by_name($stid, ':emp_id', $_SESSION['user']['emp_id']);

	   $rs= oci_execute($stid);
		if (!$rs) {
			$e = oci_error($stid);  // For oci_execute errors pass the statement handle
			print htmlentities($e['message']);
			print "\n<pre>\n";
			print htmlentities($e['sqltext']);
			printf("\n%".($e['offset']+1)."s", "^");
			print  "\n</pre>\n";
		}
		
  	oci_free_statement($stid);
	oci_close($conn);	
	}
	
	/**
	 * 取得郵件簽核人 的員工姓名
	 * @param string (公司代碼,員工代碼)
	 * @return val   (員工姓名)
	 * @author dick
	 */
	public function get_user_name($company_id,$emp_seq_no)
	{
		$conn = oci_connect("hcp","hcp","cthcp","UTF8");
		
		$sql = <<<eof
				select id_no_sz  from hr_personnel_base
				where seg_segment_no =:company_id and id = :emp_seq_no
eof;
		 $stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':company_id', $company_id);	
		oci_bind_by_name($stid, ':id', $emp_seq_no);	
		$user_name='';
		 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
			 $user_name = $row['ID_NO_SZ'];
		}
	 oci_free_statement($stid);
	 oci_close($conn);			
	 return $user_name;
	 }
		
        /**
        *   Workflow Approvement process
        *
        * procedure p_absence_approve(
				pi_seg_segment_no        varchar2, -- 公司ID
                pi_absence_approve_sz_id number, -- 主管簽核ID
                pi_approve_flag          varchar2, -- Y:核准/ N駁回
                pi_reject_reason         varchar2, -- 如果是駁回,則這裡傳入駁回原因
                po_errmsg                out varchar2, -- 返回錯誤資訊
                po_success               out varchar2, -- 操作是否成功 Y/N
                pi_approve_psn_id        varchar2 default null -- 簽核主管員工ID
            )
            procedure p_overtime_approve (
				pi_seg_segment_no         varchar2, -- 公司ID
                pi_overtime_approve_sz_id number, -- 主管簽核ID
                pi_approve_flag           varchar2, -- Y:核准/ N駁回
                pi_reject_reason          varchar2, -- 如果是駁回,則這裡傳入駁回原因
                po_errmsg                 out varchar2, -- 返回錯誤資訊
                po_success                out varchar2, -- 操作是否成功 Y/N
                pi_approve_psn_id         varchar2 default null -- 簽核主管員工ID
            )
		      傳入公司ID，主管簽核ID，核准標誌Y:核准/N:駁回, 駁回原因
		      返回錯誤資訊，如果返回的po_success為'N' ，則操作不成功
			p_approve(pi_seg_segment_no        varchar2, -- 公司ID
		   pi_approve_sz_id         number, -- 主管簽核ID
		   pi_approve_flag          varchar2, -- Y:核准/ N駁回
		   pi_reject_reason         varchar2, -- 如果是駁回,則這裡傳入駁回原因
		   po_errmsg                out varchar2, -- 返回錯誤資訊
		   po_success               out varchar2, -- 操作是否成功 Y/N
		   pi_table_name            varchar2,--flow table 名字
		   pi_id_name               varchar2,--table id 名字
		   pi_type                  varchar2, --申請類別
		   pi_approve_psn_id        varchar2 default null -- 簽核主管員工ID
		   )
		 *
         *
         * @param string $urlcode
         * @param string $reject_resaon
         * @return array
         * @author Dennis  last modify by dennis 20091117
         */
        function Approve($urlcode,$reject_resaon = null)
        {     
		    $conn = oci_connect("hcp","hcp","cthcp" );
 
            $_workflow = array();
            $_workflow = is_array($urlcode) ? $urlcode : $this->ParseWorkflowSecretCode($urlcode); // 解碼
            //print_r($_workflow); exit;
            $_approve_result = array('msg'=>'',
                                     'is_success'=>'',
                                     'apply_type'=>$_workflow['apply_type'],
                                     'workflow_seqno'=>$_workflow['workflow_seqno'],
                                     'company_id'=>$_workflow['company_id']);
   
            if (!isset($urlcode['reject_reason'])) $urlcode['reject_reason'] ='';
			$reject_resaon ='';	
            // 因為要和批量簽核共用,所以這裡 re-write by Dennis 2006-04-05 16:41:05
            $reject_resaon = is_array($urlcode) ? $urlcode['reject_reason'] : $reject_resaon;
            //print_r($_workflow);
            if ($_workflow['apply_type'] =='resign')
				{
				
				$result = $this->Left_ApproveResultList(
										$_workflow['company_id'],
										$_workflow['approver_user_seqno'],
										$_workflow['approver_emp_seqno'],							
										$_workflow['workflow_seqno'],
										$_workflow['is_approved'],
										$reject_resaon,
										'N',
										'N',
										'N',
										'',
										'',
										'',
										'',
										'',
										'',						    
										'N',
										$_workflow['approve_seqno'],
										'',
										'',
										'N',
										'',
										'resign_approve'
										);
										
				$_approve_result['msg'] = $result['msg'];
				$_approve_result['is_success'] = $result['is_success'];	
				return $_approve_result;												
				} 
            $_package_name = $_workflow['apply_type'] == 'cancel_absence' ?
                             'wf.pk_cancel_absence_wf.'                    :
                             'wf.pkg_work_flow.';

            $_procedure_name = "p_{$_workflow['apply_type']}_approve";
             // pl/sql 參數的長度最長只能為 30 所以這裡做一下替換
            // cance_absence -> c_absence
            $_applytype = str_replace('cancel','c',$_workflow['apply_type']);

            // add by dennis 20091118 for user defined workflow
            $user_define_wf_params = '';
            // user defined workflow 多兩個參數
            if (isset($_workflow['wf_flow_table']) && !empty($_workflow['wf_flow_table']))
            {
            	$_procedure_name 		= 'p_approve';
            	$user_define_wf_params 	= 'pi_type=>:in_flow_type,pi_table_name=>:in_wf_table,pi_id_name=>:in_flow_key,';
            	$_applytype 			= '';
            }else{
            	$_applytype = '_'.$_applytype;
            }// end if
            // end add

            $stmt1 = "begin begin pk_erp.p_set_segment_no(:in_company_id1); end; begin pk_erp.p_set_username(:in_user_seqno); end; begin %s%s(pi_seg_segment_no => :in_companyid,pi%s_approve_sz_id => :in_approve_seqno,pi_approve_flag => :in_is_approved,pi_reject_reason=> :in_reject_reason,pi_approve_psn_id=>:in_approver_emp_seqno,%s po_errmsg=>:out_msg,po_success=>:out_issuccess);end;end;";
            $stmt1 = sprintf($stmt1,$_package_name,$_procedure_name,$_applytype,$user_define_wf_params);
			$stmt = oci_parse($conn,$stmt1);
//			print_r($stmt);
 
			//  Bind the input parameter
			oci_bind_by_name($stmt,':in_company_id1',$_workflow['company_id'],10);
			oci_bind_by_name($stmt,':in_user_seqno',$_workflow['approver_user_seqno'],10);
			oci_bind_by_name($stmt,':in_companyid',$_workflow['company_id'],10);
			oci_bind_by_name($stmt,':in_approve_seqno',$_workflow['approve_seqno'],10);
			oci_bind_by_name($stmt,':in_is_approved',$_workflow['is_approved'],10);
			oci_bind_by_name($stmt,':in_reject_reason',$_workflow['reject_resaon'],100);
			oci_bind_by_name($stmt,':in_approver_emp_seqno',$_workflow['approver_emp_seqno'],10);			
             // add by dennis 20091118 for user defined workflow
            if (isset($_workflow['wf_flow_table']) && !empty($_workflow['wf_flow_table']))
            {
			oci_bind_by_name($stmt,':in_flow_type',$_workflow['apply_type'],100);
			oci_bind_by_name($stmt,':in_wf_table',$_workflow['wf_flow_table'],100);
			oci_bind_by_name($stmt,':in_flow_key',$_workflow['tab_key_name'],100);
            }// end if
            // end add
 
			//  Bind the out parameters
			oci_bind_by_name($stmt,':out_msg',$_approve_result['msg'],200);
			oci_bind_by_name($stmt,':out_issuccess',$_approve_result['is_success'],2);
	 			
		// Execute the statement but do not commit
		oci_execute($stmt,OCI_DEFAULT);
 
		// The status parameter will be negative if the procedure encountered a problem
		if ( !$_approve_result['msg'] ) {
			// Rollback the procedure
			oci_rollback($conn);
			die ($_approve_result['msg']."\n");
		}

		// Everything OK so commit
		oci_commit($conn);
 
 
		//add by boll 經理代理人處理
		// modify by dennis 2012-03-14 
		//只有請假且簽核成功才去處理代理人
	/*	if($_workflow['apply_type']       == 'absence' &&
		   $_approve_result['is_success'] == 'Y'       &&       	
		   !isset($_workflow['wf_flow_table'])){
				$_approve_result['msg'] .= $this->_setManagerAgent($_workflow);
		}*/
		
		 oci_free_statement($stmt);
		 oci_close($conn);		
        return $_approve_result;
        }// end function Approve
		
/**
	 * Batch Approve leave Apply
	 * @param array $list
	 */
	public function leaveBatchApprove($list)
	{
	    //pr($list);
		$conn = oci_connect("hcp","hcp","cthcp","UTF8");	
		
	    $request_no = $this->_getReqSeqNo();
	    //$this->_addBatchReq($request_no,'leave_approve');
		echo '<br/>$request_no='.$request_no;
	    // insert detail
	    $n = count($list['workflow_seqno']);
	   // $this->_dbConn->StartTrans();
	     
	    for ($i=0; $i<$n; $i++)
	    {
	        $_workflow_info['company_id']          = $list['company_id'][$i];
	        $_workflow_info['workflow_seqno']      = $list['workflow_seqno'][$i];
	        $_workflow_info['apply_type']          = $list['apply_type'][$i];
	        $_workflow_info['approver_emp_seqno']  = $list['approver_emp_seqno'][$i];
	        $_workflow_info['approver_user_seqno'] = $_SESSION['user']['user_seq_no']; // 因为有从邮件签核,所以有把 userseqno放到画面上
	        $_workflow_info['approve_seqno']       = $list['approve_seqno'][$i];
	        if (isset($list['approve_action'.$list['approve_seqno'][$i]]) && $list['approve_action'.$list['approve_seqno'][$i]] != "") 
	        	{$_workflow_info['is_approved']         = $list['approve_action'.$list['approve_seqno'][$i]];}
	        	else
	        	 $_workflow_info['is_approved'] ='';
	        echo '<br/> $list[approve_action'.$list['approve_seqno'][$i].'='.$list['approve_action'.$list['approve_seqno'][$i]];
			echo '<br>gakki 2342='.$list['approve_action2342'];
			$_workflow_info['reject_reason']	   = $_workflow_info['is_approved'] == 'N' ?
				                                    (is_array($list['reject_reason']) &&
                                				     isset($list['reject_reason'][$i]) &&
                                				     !empty($list['reject_reason'][$i]) ?
				                                     $list['reject_reason'][$i]:
				                                     (isset($list['all_reject_reason'])? 
				                                      $list['all_reject_reason'] : null)):null;
				                                        	                                   
	        if (!empty($_workflow_info['is_approved']))
	        {
				$rowCount = Array();
    	        // change to replace method for improve the performance by dennis 2013/09/24
    	        $sql= <<<eof
        	        select count(1) cnt
        	         from ehr_concurrent_request_detail
        	        where approver_emp_seqno = :appr_emp_seqno
        	          and workflow_seqno = :wf_seqno
        	          and po_success is null
eof;
				$stid = oci_parse($conn, $sql);
				 oci_bind_by_name($stid, ':appr_emp_seqno', $appr_emp_seqno);
				 oci_bind_by_name($stid, ':wf_seqno', $wf_seqno); 
                 oci_execute($stid);
		        while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
			       $rowCount['ROWCOUNTS'] = $row['ROWCOUNTS'];
		         } 
    
            	if($rowCount['ROWCOUNTS'] >0) continue;  ///有重复提交的不处理
       
            	$sql = <<<eof
            		insert into ehr_concurrent_request_detail
					  (request_no,
					   approve_seqno,
					   company_id,
					   workflow_seqno,
					   apply_type,
					   approver_emp_seqno,
					   approver_user_seqno,
					   is_approved,
					   reject_reason)
				    values(:req_no,
					   :approve_seqno,
					   :company_id,
					   :wf_seqno,
					   :apply_type,
					   :appr_emp_seqno,
					   :appr_user_seqno,
					   :is_approve,
					   :rejcet_reason)
eof;
						$stid = oci_parse($conn, $sql);
						oci_bind_by_name($stid, ':req_no', $request_no);
						oci_bind_by_name($stid, ':approve_seqno', $_workflow_info['approve_seqno']);	
						oci_bind_by_name($stid, ':company_id', $_workflow_info['company_id']);	
						oci_bind_by_name($stid, ':wf_seqno', $_workflow_info['wf_seqno']);
						oci_bind_by_name($stid, ':apply_type', $_workflow_info['apply_type']);
						oci_bind_by_name($stid, ':appr_emp_seqno', $_workflow_info['appr_emp_seqno']);	
						oci_bind_by_name($stid, ':appr_user_seqno', $_workflow_info['appr_user_seqno']);	
						oci_bind_by_name($stid, ':is_approve', $_workflow_info['is_approve']);
						oci_bind_by_name($stid, ':rejcet_reason', $_workflow_info['rejcet_reason']);
						// pre-input without immediately for test
						$r = oci_execute($stid, OCI_NO_AUTO_COMMIT);
						if (!$r) {    
							$e = oci_error($stid);
							trigger_error(htmlentities($e['message']), E_USER_ERROR);
						}
 
					}
        }echo '<br/>';
		var_dump($_POST['workflow_seqno'] ); 
	for($i=0;$i<count($_POST['workflow_seqno']);$i++)
	{ 
    // echo '<br/> $_workflow_info[workflow_seqno]['.$i.']='.$_workflow_info['workflow_seqno'][$i].' $_workflow_info[is_approved]='.$_workflow_info['is_approved'];
	 echo '<br/> $_workflow_info[workflow_seqno]['.$i.']='.$_POST['workflow_seqno'][$i].' $_workflow_info[is_approved]='.$_workflow_info['is_approved'];
    }exit;
        // last modify by dennis 2012-01-05 change v_job to v_job_no binary_integer
        //MAARK BY DEBBIE 20150205 ISSUE9129 $sql="declare v_job_no binary_integer; begin dbms_job.submit(v_job_no,'begin pkg_concurrent_request.p_leave_approve_batch(pi_batch_no=>".$request_no."); end;',sysdate,null,false);commit;end;";
        $sql="begin pkg_concurrent_request.p_jobs(pi_seg_segment_no  => '".$_workflow_info['company_id']."', pi_comments => 'WF-BATCH-ABS_APPROVE', pi_username => '".$_workflow_info['approver_user_seqno']."', pi_batch_no => '".$request_no."', pi_job_script => 'begin pkg_concurrent_request.p_leave_approve_batch(pi_batch_no=>".$request_no."); end;'); end;"; //ADD BY DEBBIE 20150205 ISSUE9129
		$r = oci_execute($stid, OCI_NO_AUTO_COMMIT);
		if (!$r) {    
			$e = oci_error($stid);
			trigger_error(htmlentities($e['message']), E_USER_ERROR);
		}
		
		// Commit
		$r = oci_commit($conn);
		if (!$r) {
			$e = oci_error($conn);
			trigger_error(htmlentities($e['message']), E_USER_ERROR);
			exit;
		}
	 }

        /**
        *   Parse serect code (url long code) form mail approve
        *   @param $serect_code string, the encrypt information about workflow
        *   @param $is_batch boolean, batch approve
        *   @param $view_only boolean, view flowchart only
        *   @return array, plain code about workflow information
        *   @author: Dennis 2006-04-03 11:18:03
        *   @last update: 2006-04-28 13:53:33  by Dennis
        */
        public function ParseWorkflowSecretCode($secret_code,$is_batch=false,$view_only = false)
        {
            //$this->_decrypt(); // call private function _decrypte before parse
		    $conn = oci_connect("hcp","hcp","cthcp","UTF8");
			
            $_apply_type = array('A'=>'absence',
            					 'O'=>'overtime',
            					 'C'=>'cancel_absence',
            					 'T'=>'trans',
            					 'N'=>'nocard',
            					 'R'=>'resign',
            					 'U'=>'user_defined');

            $_workflow_info = array('plain_code'=>'',       /* 解析出來的 明碼 AY|AN|OY|ON */
                                    'apply_type'=>'',       /* 請假|加班 (absence|overtime) */
                                    'approve_time'=>'',     /* 解析出來的 時間 */
                                    'approver_emp_seqno'=>'',  /* 解析出來的 簽核人員工流水號 */
                                    'approve_seqno'=>'',    /* 解析出來的 簽核流水號碼 */
                                    'workflow_seqno'=>'',   /* 解析出來的 workflow 流水號 */
                                    'company_id'=>'',       /* 解析出來的 公司代碼 */
                                    'is_approved'=>'',      /* 解析出來的 是否核准碼 Y,N */
                                    'approver_user_seqno'=>''/* 解析出來的 Login User 流水號 */);
            // Get plain code from db
            $sql_str = 'select wf.pks_crypt_sz.decryptc(:serect_code) as plain_code from dual';
			$_plain_code ='';
			$stid = oci_parse($conn, $sql);
			oci_bind_by_name($stid, ':serect_code', $secret_code);
            oci_execute($stid);
			 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
				 $_plain_code = $row['PLAIN_CODE'];
			} 			
			
           //print "<font color=red>$_plain_code</font>";
            // separate explain code
            if (!empty($_plain_code))
            {
                if (!$is_batch && !$view_only){
                    list($_workflow_info['plain_code'],
                         $_workflow_info['approve_time'],
                         $_workflow_info['approver_emp_seqno'],
                         $_workflow_info['approve_seqno'],
                         $_workflow_info['workflow_seqno'],
                         $_workflow_info['company_id'],
                         $_workflow_info['approver_user_seqno']) = explode(';',$_plain_code);
                    $_code = strtoupper($_workflow_info['plain_code']);
                    $_workflow_info['is_approved']= $_code{1}; // Y/N
                }else if($is_batch && !$view_only){
                    list($_workflow_info['plain_code'],
                         $_workflow_info['approve_time'],
                         $_workflow_info['approver_emp_seqno'],
                         $_workflow_info['company_id'],
                         $_workflow_info['approver_user_seqno']) = explode(';',$_plain_code);
                }else if(!$is_batch && $view_only){ // add by dennis 2006-04-28 13:57:07
                	//pr(explode(';',$_plain_code));
                    list($_workflow_info['plain_code'],
                         $_workflow_info['approve_time'],
                         $_workflow_info['approver_emp_seqno'],
                         $_workflow_info['workflow_seqno'],
                         $_workflow_info['company_id'],
                         $_workflow_info['approver_user_seqno']) = explode(';',$_plain_code);
                }// end if
                $_code = strtoupper($_workflow_info['plain_code']);  
                $_workflow_info['apply_type'] = $_apply_type[$_code{0}];// absence/overtime
                //pr($_workflow_info);

	          // 郵件簽核抓不到 session[user][emp_id],手動補  by dk 20150625
       	$sql = <<<eof
        		select id_no_sz  from hr_personnel_base
        		where seg_segment_no =:company_id and id = :emp_seq_no
eof;
			    
			  $stid = oci_parse($conn, $sql);
			   oci_bind_by_name($stid, ':company_id', $_workflow_info['company_id']);
			   oci_bind_by_name($stid, ':emp_seq_no', $_workflow_info['approver_emp_seqno']);
			   $emp_id ='';
				oci_execute($stid);
				 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
					 $emp_id = $row['ID_NO_SZ'];
				} 			   
 		
 		       $_SESSION['user']['emp_id'] = $emp_id;
 		       $_SESSION ['user']['user_seq_no'] = $_workflow_info['approver_user_seqno'];	
 		       $_SESSION ['user'] ['company_id'] = $_workflow_info['company_id'];	
			   $_SESSION ['user'] ['emp_seq_no'] =  $_workflow_info['approver_emp_seqno'];
 		       $_SESSION ['user'] ['user_name'] = $this->get_user_name($_workflow_info['company_id'],$_workflow_info['approver_emp_seqno']);
 			   
			   $this->load->model('user', 'usr');
			   $result = $this->usr->GetBaiscInfo();
 		
				//pr($usrinfo);
				$_SESSION ['user'] ['dept_id'] = $usrinfo['DEPT_ID'];
				$_SESSION ['user'] ['title_level'] = $usrinfo['TITLE_GRADE'];		
 
                return $_workflow_info;
            }else{
                // 私自竄改 approve.php?key=xxx 中的編碼,bacthapprove.php?key=xxx
                echo '<br/>Fatal Error: URL Key Error. Try to Attack Workflow System Failure.';
            }// end if
            return null;
        }// end _parseWorkflowSecretCode()	 
}
?>