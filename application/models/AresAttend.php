<?php
Class AresAttend extends CI_Model
{

	/**
	 * 從多語資料中挑 List 的多語
	 *
	 * @param string $programno
	 *        	程式代碼
	 * @param string $labelid
	 *        	多語 Key
	 * @param string $lang
	 *        	語言代碼
	 * @return array 2d-array of workflow status
	 * @author Dennis 2008-09-11
	 *        
	 */
	function getOvertime_applyList($programno, $labelid, $lang) 
	{
	 $sql = "";
     $conn = oci_connect("hcp","hcp","cthcp","UTF8");
   
	 $sql = "select  seq as option_value, value as lable_text
			  from app_muti_lang
			 where program_no = :program_no
			   and name = :label_id
			   and lang_code  = :lang_code
			   and type_code = 'LL'";
			   
	 $stid = oci_parse($conn, $sql);
	oci_bind_by_name($stid, ':program_no', $programno);		 
	oci_bind_by_name($stid, ':label_id', $labelid);
	oci_bind_by_name($stid, ':lang_code', $lang);	
	
	oci_execute($stid);
 
 	$result =Array();
	 $cc =0;

	 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
				$result[$cc]['option_value'] = $row['OPTION_VALUE'];
				$result[$cc]['lable_text'] = $row['LABLE_TEXT'];
				$cc =$cc + 1; 
	 }
	oci_free_statement($stid);
	oci_close($conn);	
   //var_dump($result);	
	return $result;		
		 
	} // end getOvertime_applyList()
	
		/**
	 * 取得程式錯誤代號所屬的名稱()
	 * 群組編碼 = (LEAVE/NOCARD/OVERTIME...)
	 *
	 * @param
	 *        	string (程式代碼 ,語系 , 群組編碼)
	 * @return val
	 * @author dick
	 */
	public function getProgramERR($program_no, $lang, $keyWd) {
	 $sql = "";
     $conn = oci_connect("hcp","hcp","cthcp","UTF8");		
		$sql = "select NAME, nvl(VALUE, '') as VAL
				  from app_muti_lang
				  where   TYPE_CODE='IT'
				         and program_no = :program_no
                         and  lang_code=:lang  
                         and  substr(NAME, 1, 9)=:keyWd";
 
		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':program_no', $program_no);		 
		oci_bind_by_name($stid, ':keyWd', $keyWd);
		oci_bind_by_name($stid, ':lang', $lang);	
		oci_execute($stid);
	 
		$result =Array();
		 $cc =0;

		 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
					$result[$cc]['name'] = $row['NAME'];
					$result[$cc]['val'] = $row['VAL'];
					$cc =$cc + 1; 
		 }
		oci_free_statement($stid);
		oci_close($conn);	
	   //var_dump($result);	
		return $result;		
	 
	}
	
	/**
	 * Get overtime reason list
	 *
	 * @param
	 *        	no parameter
	 * @return 2-d array
	 */
	function GetOvertime_Reason() 
	{
	 $sql = "";
     $conn = oci_connect("hcp","hcp","cthcp","UTF8");
	 
		$sql = "select overtime_reason_id,
                 overtime_reason_no ||' '||overtime_reason_desc as overtime_reason,
                 reason
            from hr_overtime_reason
           where is_active = 'Y'
             and seg_segment_no = : company_id";
 
	$stid = oci_parse($conn, $sql);
	oci_bind_by_name($stid, ':company_id',  $_SESSION ['user'] ['company_id']);		 
	oci_execute($stid);
  
	$result =Array();
	 $cc =0;

	 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
				$result[$cc]['overtime_reason'] = $row['OVERTIME_REASON'];
				$result[$cc]['reason'] = $row['REASON'];
				$result[$cc]['overtime_reason_id'] = $row['OVERTIME_REASON_ID'];				
				$cc =$cc + 1; 
	 }
					
   // var_dump($result);
	oci_free_statement($stid);
	oci_close($conn);		  
	return $result;	
	}// end GetOvertime_Reason
	
	/**
	 * 暂存或提交加班申请
	 *procedure p_save_overtime_apply ( 
	 *	pi_seg_segment_no      varchar2, -- 公司ID
	 *	pi_psn_id              varchar2, -- 员工ID
	 *	pi_cost_dept_id        varchar2, -- 成本部门
	 *	pi_stype               varchar2, -- N/S/H
	 *	pi_reason              varchar2, -- A:计费  B:补休
	 *	pi_overtime_reason_id  number, --加班原因ID
	 *	pi_date_begin          date, -- 请假开始时间
	 *	pi_date_end            date, -- 请假结束时间
	 *	pi_remark              varchar2, -- 备注
	 *	po_hours               in out number, -- 返回加班小时数
	 *	po_errmsg              out varchar2, -- 返回错误信息
	 *	po_overtime_flow_sz_id in out number, -- 返回加班申请单ID
	 *	po_success             out varchar2, -- 操作是否成功 Y/N
	 *	pi_submit              varchar2 default 'N', -- 是否立即提交申请
	 *	pi___only_submit       varchar2 default 'N' -- 私有参数,仅供单独提交申请程式传入Y值使用
	 *) 
	 * @param string $userseqno     Login User Seq. No.
	 * @param string $deptseqno		申请人的部门代码流水号	 		
	 * @param string $ot_begin_time 加班开始时间
	 * @param string $ot_end_time   加班结束时间
	 * @param number $ot_hours      加班时数
	 * @param string $ot_reason     加班原因 
	 * @param string $ot_fee_type   补偿方式
	 * @param string $ot_type       加班类型（平常 /假日/国假）
	 * @param string $remark		备注
	 * @param string $tmp_save		只是暂存，不提交 
	 * @param string $empseqno		员工代码流水号(psn_id),如果为空表示是当前 login user
	 * @return array, 加班申请单提交结果的相关信息
	 * @author Dennis 2008-09-19 rewrite
	 */
	function SaveOvertimeApply($userseqno, 
							   $cost_deptid,
							   $ot_begin_time, 
							   $ot_end_time, 
							   $ot_hours, 
							   $ot_reason, 
							   $ot_fee_type, 
							   $ot_type, 
							   $remark, 
							   $tmp_save, 
							   $emp_seqno = null) {
	$stmt1 = "";
	$conn = oci_connect("hcp","hcp","cthcp","UTF8");								   
	$result = array ('hours'=>'',
					 'msg'=> '',
					 'flow_seqno'=>'',
					 'is_success'=>'');
	$stmt1 = "begin begin pk_erp.p_set_segment_no(:in_company_id1); end; begin pk_erp.p_set_username(:in_user_seqno); end; begin wf.pkg_work_flow.p_save_overtime_apply(pi_seg_segment_no => :in_companyid,pi_psn_id => :in_empseqno,pi_cost_dept_id => :in_cost_dept_id,pi_stype => :in_overtime_type,pi_reason => :in_overtime_fee,pi_overtime_reason_id=> :in_overtime_reason,pi_date_begin => to_date(:in_begin_date,'YYYY-MM-DD HH24:MI'),pi_date_end => to_date(:in_end_date,'YYYY-MM-DD HH24:MI'),pi_remark => :in_remark,pi_submit=>:in_submit,po_hours=>:out_hours,po_errmsg=>:out_msg,po_overtime_flow_sz_id=>:out_overtime_flowseqno,po_success=>:out_issuccess);end;end;";
	//echo '<br/>$ot_type='.$ot_type.' $ot_fee_type='.$ot_fee_type.' $ot_reason='.$ot_reason.' $ot_begin_time='.$ot_begin_time.' $ot_end_time='.$ot_end_time.' $remark='.$remark.' $tmp_save='.$tmp_save;
   //var_dump($_SESSION['user']);exit;
	$stmt = oci_parse($conn, $stmt1);
	oci_bind_by_name($stmt,':in_company_id1',$_SESSION['user']['company_id'],10);
	oci_bind_by_name($stmt,':in_user_seqno',$_SESSION['user']['user_seq_no'],10);
	oci_bind_by_name($stmt,':in_companyid',$_SESSION['user']['company_id'],10);
	oci_bind_by_name($stmt,':in_empseqno',$_SESSION['user']['emp_seq_no'],10);
	oci_bind_by_name($stmt,':in_cost_dept_id',$_SESSION['user']['dept_id'],10);
	oci_bind_by_name($stmt,':in_overtime_type',$ot_type,10);
	oci_bind_by_name($stmt,':in_overtime_fee',$ot_fee_type,10);	
	oci_bind_by_name($stmt,':in_overtime_reason',$ot_reason,10);
	oci_bind_by_name($stmt,':in_begin_date',$ot_begin_time,20);
	oci_bind_by_name($stmt,':in_end_date',$ot_end_time,20);
	oci_bind_by_name($stmt,':in_remark',$remark,400);
	oci_bind_by_name($stmt,':in_submit',$tmp_save,2);

		// add by dennis 2006-04-24 09:43:37
		if (! is_null ( $ot_hours ) && floatval ( $ot_hours ) > 0) {
			oci_bind_by_name($stmt,':out_hours',$ot_hours,6);
		} else {
			oci_bind_by_name($stmt,':out_hours',$result['hours'],6);				
		} // end if
		
	oci_bind_by_name($stmt,':out_msg',$result['msg'],200);
	oci_bind_by_name($stmt,':out_overtime_flowseqno',$result['flow_seqno'],9);
	oci_bind_by_name($stmt,':out_issuccess',$result['is_success'],2);

	// Execute the statement but do not commit
	oci_execute($stmt,OCI_DEFAULT);

	// The status parameter will be negative if the procedure encountered a problem
	if ( !$result['msg'] ) {
			// Rollback the procedure
			oci_rollback($conn);
			die ($result['msg']."\n");
		}
	// Everything OK so commit
	oci_commit($conn);
	 
	oci_free_statement($stmt);
	oci_close($conn);		 
	return $result;
	} // end SaveOvertimeApply()

	/**
	 * 加班或请假规则说明
	 *
	 * @param string $rtype 规则类型
	 * @return string
	 * @author Dennis 2009-03-23
	 */
	function getRuleText($rtype = 'overtime_apply_rule')
	{
	$conn = oci_connect("hcp","hcp","cthcp","UTF8");			
	$sql = "select text from ehr_md_content where code=:rtype";
	
	$stid = oci_parse($conn, $sql);
	oci_bind_by_name($stid, ':rtype', $rtype);		 
	oci_execute($stid);

	$result =Array();
	 $cc =0;
	 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
				$result['text'] = $row['TEXT'];
	 }
	oci_free_statement($stid);
	oci_close($conn);	
	return $result['text'];	

	}// end getRuleText()
	
/**
	 *  取得当前使用者可以请的假别清单
	 *  @param $sex string , sex, M_男 F_女 upper case
	 *  @return  array
	 *	@author : dennis 2006-05-11 14:14:14
	 *	@last update: 2008-08-07  by dennis
	 */
	/**
	 * 
	 * 取得假别清单
	 * @param $sex			区分性别的假别清单
	 * @param $except_id	是否弃除年假和补休
	 * @author Dennis
	 */
	function GetLeaveNameList($sex = null,$except_id = null) {
	
		$where = is_null ( $sex ) ? '' : " and (sex_absence = 'A' or sex_absence ='" . $sex . "')";
		if (!is_null($except_id))
		{
			$r = $this->_getYearMendID();
			if (is_array($r))
			{
				$where .= !empty($r['YEAR_LEAVE']) ? ' and absence_seq_no != \''.$r['YEAR_LEAVE'].'\'' : '';
				$where .= !empty($r['MEND_LEAVE']) ? ' and absence_seq_no != \''.$r['MEND_LEAVE'].'\'' : '';
			}
		}
	   $conn = oci_connect("hcp","hcp","cthcp","UTF8");		
		$sql ="select absence_seq_no,
                       absence_id ||' - '||absence_name  absence_name
				  from ehr_absence_type_v
				 where company_id = :company_id
                   and is_active = 'Y'";
        $sql .=$where;
        $sql .="order by absence_id";
 
 		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':company_id', $_SESSION['user']['company_id']);		 
		oci_execute($stid);
	 
		$result =Array();
		 $cc =0;
		 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
					$result[$cc]['absence_seq_no'] = $row['ABSENCE_SEQ_NO'];
					$result[$cc]['absence_name'] = $row['ABSENCE_NAME'];
					$cc =$cc + 1; 
		 }
		oci_free_statement($stid);
		oci_close($conn);	
		return $result;		

	} // end GetLeaveNameList()	
	
	private function _getYearMendID()
	{
	$conn = oci_connect("hcp","hcp","cthcp","UTF8");		
		$sql = "select year_leave,mend_leave
			  from hr_attendset
			 where seg_segment_no = :company_id";
 
	$stid = oci_parse($conn, $sql);
	oci_bind_by_name($stid, ':company_id', $_SESSION['user']['company_id']);		 
	oci_execute($stid);

	$result =Array();
	 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
				$result['YEAR_LEAVE'] = $row['YEAR_LEAVE'];
				$result['MEND_LEAVE'] = $row['MEND_LEAVE'];				
	 }
	oci_free_statement($stid);
	oci_close($conn);	
	return $result;	
	}

	/**
	 * 特殊假別
	 *
	 * @param
	 *        	no
	 * @author dennis
	 */
	function getSpecAbsence() {
	$conn = oci_connect("hcp","hcp","cthcp","UTF8");
	
		$sql = "select absence_type_id
			  from hr_family_type_master
			 where seg_segment_no = :company_id";

	$stid = oci_parse($conn, $sql);
	oci_bind_by_name($stid, ':company_id', $_SESSION['user']['company_id']);		 
	oci_execute($stid);
 
//echo json_encode(array('aaData'=> $rows));
	
	$result =Array();
	$cc = 0;
	 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
				$result[$cc] = $row['ABSENCE_TYPE_ID'];
				$cc = $cc + 1;
	 }
	 
	$rows = array();
	foreach ($result as $row) {
	  $rows[] = array_values((array)$row);
	}	 
   //echo json_encode(array('aaData'=> $rows));
 
	oci_free_statement($stid);
	oci_close($conn);	 
	return json_encode ($rows);
	}

/**
	 * procedure p_save_absence_apply
	 * ****************************************************************************
	 * 傳入公司ID，員工ID，請假類別ID，請假時間起訖，pi_submit : Y/N 保存時是否提交申請
	 * 返回天數/時數,請假申請單ID和錯誤資訊，如果返回的po_success為'N' ，則保存不成功
	 * 即使 po_success為'Y' ，這時錯誤資訊可能也有值，那可能是一些提示資訊的返回
	 * 保存至HR_ABSENCE_FLOW_SZ表中
	 * ****************************************************************************
	 * (pi_seg_segment_no varchar2, -- 公司ID
	 * pi_psn_id varchar2, -- 員工ID
	 * pi_absence_type_id number, --請假類別ID
	 * pi_date_begin date, -- 請假開始時間
	 * pi_date_end date, -- 請假結束時間
	 * pi_funeral_id varchar2,--喪假建檔ID
	 * pi_remark varchar2, -- 備註
	 * po_days out number, -- 返回天數
	 * po_hours out number, -- 返回小時數
	 * po_errmsg out varchar2, -- 返回錯誤資訊
	 * po_absence_flow_sz_id in out number, -- 返回請假申請單ID
	 * po_success out varchar2, -- 操作是否成功 Y/N
	 * pi_submit varchar2 default 'N', -- 是否立即提交申請
	 * pi___only_submit varchar2 default 'N' -- 私有參數,僅供單獨提交申請程式傳入Y值使用)
	 * 暫存或提交請假申請
	 *
	 * @param string, $user_seqno
	 *        	請假人的使用者流水號(app_users_base.user_id)
	 * @param string, $absence_id
	 *        	假別代碼
	 * @param string, $begin_time
	 *        	請假開始時間
	 * @param string, $end_time
	 *        	請假結束時間
	 * @param string, $leave_reason請假原因        	
	 * @param string, $submit_type
	 *        	提交類型,save_只是暫存請假單 submit_提交到流程中簽核
	 * @param string, $funeral_id
	 *        	特殊假別代碼,default null
	 * @param number, $emp_seqno
	 *        	提交人的員工代碼流水號 (hr_personnel_base.id)
	 * @return array, procedure 傳出參數組成的 Array
	 * @author Dennis 2008-09-12 last update
	 */
	function SaveLeaveForm($user_seqno, $absence_id, $begin_time, $end_time, $leave_reason, $submit_type, $funeral_id = null, $emp_seqno = null) {
		// add by dennis 2006-04-02 18:43:45 for support batch apply
		// $this->DBConn->debug = 1;
			$conn = oci_connect("hcp","hcp","cthcp","UTF8");
			
		$emp_seqno = $_SESSION ['user'] ['emp_seq_no'];
		// 保存或提交返回的結果
		$_save_result = array (
				'days' => '',
				'hours' => '',
				'msg' => '',
				'flow_seqno' => '',
				'is_success' => '' 
		);
		
		$stmt1 = "begin begin pk_erp.p_set_segment_no(:in_company_id1); end; begin pk_erp.p_set_username(:in_user_seqno); end; begin wf.pkg_work_flow.p_save_absence_apply(pi_seg_segment_no=>:in_companyid,pi_psn_id=>:in_empseqno,pi_absence_type_id=>:in_leavetype_seqno,pi_date_begin=>to_date(:in_begintime,'YYYY-MM-DD HH24:MI'),pi_date_end=>to_date(:in_endtime,'YYYY-MM-DD HH24:MI'),pi_funeral_id=>:in_funeral_id,pi_remark=>:in_reason,pi_submit=>:in_submit,po_days=>:out_days,po_hours=>:out_hours,po_errmsg=>:out_msg,po_absence_flow_sz_id=>:out_leave_flowseqno,po_success=>:out_issuccess,pi_assis_username=>:in_assis_username); end;end;";
		$stmt = oci_parse($conn, $stmt1);
		
	oci_bind_by_name($stmt,':in_company_id1',$_SESSION['user']['company_id'],10);
	oci_bind_by_name($stmt,':in_user_seqno',$_SESSION['user']['user_seq_no'],10);
	oci_bind_by_name($stmt,':in_companyid',$_SESSION['user']['company_id'],10);
	oci_bind_by_name($stmt,':in_empseqno',$_SESSION['user']['emp_seq_no'],10);
	oci_bind_by_name($stmt,':in_leavetype_seqno',$absence_id,10);
	oci_bind_by_name($stmt,':in_begintime',$begin_time,30);
	oci_bind_by_name($stmt,':in_endtime',$end_time,30);
	oci_bind_by_name($stmt,':in_funeral_id',$funeral_id,30);
	oci_bind_by_name($stmt,':in_reason',$leave_reason,4000);	
	oci_bind_by_name($stmt,':in_submit',$tmp_save,2);
	oci_bind_by_name($stmt,':in_assis_username',$user_seqno,20);
	
	oci_bind_by_name($stmt,':out_days',$_save_result ['days'],10);
	oci_bind_by_name($stmt,':out_hours', $_save_result ['hours'],10);	
	oci_bind_by_name($stmt,':out_msg',$_save_result['msg'],2000);
	oci_bind_by_name($stmt,':out_leave_flowseqno', $_save_result['flow_seqno'],9);
	oci_bind_by_name($stmt,':out_issuccess',$_save_result['is_success'],2);
 

		// Execute the statement but do not commit
	oci_execute($stmt,OCI_DEFAULT);

	// The status parameter will be negative if the procedure encountered a problem
	if ( !$_save_result['msg'] ) {
			// Rollback the procedure
			oci_rollback($conn);
			die ($_save_result['msg']."\n");
		}
	// Everything OK so commit
	oci_commit($conn);
	 
	oci_free_statement($stmt);
	oci_close($conn);		 
	
	return $_save_result;
	} // end function SaveLeaveForm();	
}
?>