<?php
/*
 * 员工Lov
 * create by Boll 2009-05-07
 * $HeadURL: https://svn.areschina.com/svn/ehr/trunk/eHR/mgr/includes/employee_lov_DB.php $
 * $Id: employee_lov_DB.php 2484 2009-12-24 07:55:24Z boll $
 * $Rev: 2484 $
 * $Date: 2009-12-24 15:55:24 +0800 (週四, 24 十二月 2009) $
 * $LastChangedDate: 2009-12-24 15:55:24 +0800 (週四, 24 十二月 2009) $
 * **************************************************************************
 */
/**
 * last modify by dennis 20090623 17:15
 * 加过虑离职人员的条件
 */
$sql = "select emp_seq_no as dept_id, 
		       emp_id     as dept_no,
		       emp_name   as dept_name
		  from ehr_employee_v
		 where company_id = :v_company_id
		   and (emp_name like :v_search_condition or
		        emp_id   like :v_search_condition)
		   --and (dept_seq_no = :v_dept_id or :v_dept_id is null)
		   and rownum < 30
		   and pk_history_data.f_get_status(company_id,emp_seq_no,sysdate) = 'JS1'
		 order by emp_id"; // updated by Gracie at 20090819 先显出本部门的人
include_once 'AresLov.class.php';
$lov = new AresLov ();
$lov->setSql ( $sql );
$lov->tpl->assign ( 'DIS_CODE_LABEL', '員工代碼' );
$lov->tpl->assign ( 'DIS_NAME_LABEL', '員工姓名' );
$lov->tpl->assign ( 'SUBMIT_QRY_BTN_LABEL', '查詢' );
if(!empty($_REQUEST['agent_name'])){
$lov->tpl->assign ( 'agent_name', $_REQUEST['agent_name'] );
$lov->tpl->assign ( 'agent_id', $_REQUEST['agent_id'] );
$lov->tpl->assign ( 'agent_no', $_REQUEST['agent_no'] );
}
$lov->run ();
?>