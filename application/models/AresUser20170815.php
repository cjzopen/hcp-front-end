<?php
Class AresUser extends CI_Model
{
	
 function login($username, $password,$ChK= null)
  {     
//$this->load->library('Session');
        $resultOri= array();
        $conn = oci_connect("hcp","hcp","cthcp");
		$sql = "select  a.SEG_SEGMENT_NO,b.USERNAME from hr_personnel_base a,app_users_base b
                         where a.ID =b.psn_id 
                         and  b.USERNAME_NO_SZ= :id_no_sz";
		
		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':id_no_sz', $username);
		oci_execute($stid);
	    $row = oci_fetch_array($stid, OCI_BOTH);
      if (empty($row))
		{
	     $ret= '';
		 $ret = 'The user name does not exist.';
		 return $ret;			
		}
	// echo '<br/>SEG_SEGMENT_NO='.$row["SEG_SEGMENT_NO"];
	// echo '<br/>USERNAME='.$row["USERNAME"];
 
 		$stmt = "begin pk_erp.p_set_segment_no(:company_id); pk_erp.p_set_username(:user_seq_no); p_set_appinfo('FYPYDWENIYFMTWQYPTDZDRCLRHDTEGAMTJBKKYSDVMBPXUXV'); end;";
		$stid = oci_parse($conn, $stmt);
		oci_bind_by_name($stid, ':company_id', $row['SEG_SEGMENT_NO']);
		oci_bind_by_name($stid, ':user_seq_no', $row['USERNAME']);	 
        oci_execute($stid);
 	
	$sql = "select a.seg_segment_no    as company_id,
                   a.username          as user_seq_no,
                   a.psn_id            as user_emp_seq_no,
                   a.username_no_sz    as user_name,
                   a.user_desc         as user_desc,
                   b.emp_id            as user_emp_id,
                   b.emp_name          as user_emp_name,
				   b.sex               as sex,
			       b.dept_id           as dept_id,    
			       b.dept_seq_no       as dept_seqno,
			       b.dept_name         as dept_name,
                   a.email             as email,
                   b.title_id          as title_id,
                   b.title_name        as title_name,
			       b.title_grade       as title_level,
			       b.join_date         as join_date
              from app_users_base a, ehr_employee_v b
             where upper(b.COMPANY_ID) = :company_id
               and upper(a.username_no_sz) = :user_name
			   and a.psn_id = b.emp_seq_no(+)";
			   
		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':company_id', $row['SEG_SEGMENT_NO']);
		oci_bind_by_name($stid, ':user_name', $username);	 
		oci_execute($stid);
 
	    $result = oci_fetch_array($stid, OCI_BOTH);
		 //var_dump($result);exit;
	     //$this->session->set_userdata($result);
		 //echo 'session_id='.$this->session->userdata('session_id');exit;
 		// $this->session->all_userdata();exit;
 		$_SESSION ['user'] ['company_id']  = $result['COMPANY_ID']; // user company id
		$_SESSION ['user'] ['user_seq_no'] = $result ['USER_SEQ_NO']; // user seq no in table app_users
		$_SESSION ['user'] ['emp_seq_no'] = $result ['USER_EMP_SEQ_NO']; // user match emp seq no in table hr_personnel
		$_SESSION ['user'] ['emp_id'] =     $result ['USER_EMP_ID']; // user match emp id
		$_SESSION ['user'] ['emp_name'] =    $result ['USER_EMP_NAME']; // user match emp name
		$_SESSION ['user'] ['user_name'] =  $username; // user login id
		$_SESSION ['user'] ['sex'] = $result ['SEX']; // user sex
		$_SESSION ['user'] ['dept_seqno'] = $result ['DEPT_SEQNO']; // user dept seq no
		$_SESSION ['user'] ['dept_id'] = $result ['DEPT_ID']; // user dept id
		$_SESSION ['user'] ['dept_name'] = $result ['DEPT_NAME']; // user dept name
		$_SESSION ['user'] ['title_id'] = $result ['TITLE_ID']; // user login title_id
		$_SESSION ['user'] ['title_name'] = $result ['TITLE_NAME']; // title name
		$_SESSION ['user'] ['title_level'] = $result ['TITLE_LEVEL']; // title level
		$_SESSION ['user'] ['join_date'] = $result ['JOIN_DATE']; // in date
		
		//$_SESSION ['sys'] ['path'] = base_url();
 
		$_SESSION['Calendar_SelDate']= date('Ymd');
		$year = substr($_SESSION['Calendar_SelDate'], 0, 4);
		$mon  = substr($_SESSION['Calendar_SelDate'], 4, 2);
		$day  = substr($_SESSION['Calendar_SelDate'], 6, 2);
		// For Calender Default
	   $_SESSION['Calendar_yy']=$year;
	   $_SESSION['Calendar_mm']=(int)$mon;
	   $_SESSION['Calendar_dd']=(int)$day; 		
	   
		$this->session->set_userdata($_SESSION ['user']); 
		$this->session->all_userdata();
		//var_dump($_SESSION ['user'] ); exit;
		 //echo '<br/>$_SESSION[Cal_yy]='.$_SESSION['Calendar_yy'].' $_SESSION[Cal_mm]='.$_SESSION['Calendar_mm'].' $_SESSION[Cal_dd]='.$_SESSION['Calendar_dd'];  
		//exit;
		$_SESSION ['user'] ['is_manager1'] = $this->IsManager ( $row['SEG_SEGMENT_NO'],$result ['USER_EMP_SEQ_NO'] ); // 记录当前员工是不是 Manager
		if ($ChK != 'Y')
		{// $CHK =='Y' 有TOKEN 不須檢核
	     $ret= '';
		 $ret = $this->PasswordStatus($_SESSION ['user'] ['company_id'],$_SESSION ['user'] ['user_seq_no'], $password, "US");
		 return $ret;
		}

	oci_free_statement($stid);
	oci_close($conn);		
 
   if($result != null)
   { 
     return ''; 
   }
   else
   {
     return false;
   }
 }
 
 /**
*   Get employee basic information
*   @param no
*   @return array (array("key"=>"value"))
*/
function GetBaiscInfo()
{
	$conn = oci_connect("hcp","hcp","cthcp");
	
		$sql = <<<eof
			select company_id,
				   dept_seq_no,
				   dept_id,
				   dept_name,
				   emp_seq_no,
				   emp_id,
				   emp_name,
				   name_en,
				   decode(sex,'M','男','F','女') as sex,
				   sex as sex_code,
				   birthday,
				   nationality_id,
				   nationality,
				   nations,
				   birthplace,
				   title_id,
				   title_name,
				   emp_type_id,
				   emp_type_name,
				   title_grade,
				   salary_level,
				   is_foreigner,
				   cellphone_no,
				   home_tel,
				   extension,
				   permanent_address,
				   address_contactor,
				   postcode,
				   mail_address,
				   mail_postcode,
				   mail_contactor,
				   emergency_contractor,
				   emergency_tel,
				   e_mail,
				   hiredate,
				   id_no,
				   is_served,
				   blood_type,
				   is_marriage,
				   edu_level,
				   join_date,
				   leave_date,
				   year_sal_startdate,
				   work_years,
				   overtime_fee_id,
				   overtime_fee_name,
				   absence_fee_id,
				   absence_fee_name,
				   year_vacation_id,
				   year_vacation_name,
				   salary_type,
				   brush_card_type,
				   remark,
				   emp_status_id,
				   emp_staus,
				   bank_account,
				   bank_id,
				   bank_account1,
				   room_no,
				   factory_zone_id,
				   factory_zone,
				   passport,
				   probation_endate,
				   residence_permit_no,
				   privilege_level,
				   insurance_no,
				   constellation,
				   icq,
				   history_data_id,
				   is_trainee,
				   job_id,
				   job_name,
				   nonleave_pay_id,
				   sal_period_id,
				   sal_period_name,
				   contract_type_id,
				   contract_type,
				   to_formal_date,
				   salary_ratio,
				   tax_area,
				   tax_type_id,
				   tax_type_name,
				   pay_welfare,
				   jobid,
				   id_type,
				   permanent_address_contactor,
				   current_contactor,
				   emp_archives_loc,
				   is_insured,
				   is_reseve_fund,
				   introductor_emp_id,
				   introductor_emp_name,
				   work_address,
				   phone_no
			  from ehr_employee_v 
			where company_id = :companyid
			  and emp_seq_no = :emp_seqno
eof;

	$stmt = 'begin pk_erp.P_SET_SEGMENT_NO(:companyid); end;';
	$stid = oci_parse($conn, $stmt);
	oci_bind_by_name($stid, ':company_id', $_SESSION ['user'] ['company_id']);
	oci_execute($stid);			
		
	$stid = oci_parse($conn, $sql);
	oci_bind_by_name($stid, ':company_id', $row['SEG_SEGMENT_NO']);
	oci_bind_by_name($stid, ':emp_seqno', $_SESSION ['user'] ['emp_seq_no']);	 
	oci_execute($stid);

	$result = oci_fetch_array($stid, OCI_BOTH);
 
	oci_free_statement($stid);
	oci_close($conn);		 
	return $result;
	}// end GetBaiscInfo()
		
public function getPersonMomo(){

$conn = oci_connect("hcp","hcp","cthcp","UTF8");
	
$sql = "SELECT hp.REMARK hp_REMARK,
       hp.SLY_GRADE hp_SLY_GRADE,
       hp.TEL hp_TEL,
       hp.TEL_PART hp_TEL_PART,
       hp.TRIALDATE hp_TRIALDATE,
       hp.ADDRESS_MAN hp_ADDRESS_MAN,
       gl.SEGMENT_NAME gl_SEGMENT_NAME,
       gl.SEGMENT_NO_SZ gl_SEGMENT_NO_SZ,
       ht.TITLENAME ht_TITLENAME,
       ht.TITLE_NO_SZ ht_TITLE_NO_SZ,
       hp.BIRTH hp_BIRTH,
       pk_hr_code.F_CODENAME ('PERSONTYPE', hp.CONTRACT) hp_CONTRACT,
       pk_hr_code.F_CODENAME ('CONSTELLATION', hp.CONSTELLATION)
          hp_CONSTELLATION,
       hp.CONTRACT_DATE hp_CONTRACT_DATE,
       pk_history_data.f_get_hignschool (hp.seg_segment_no, hp.id, '02')
          hp_EDUCATION,
       pk_hr_code.f_parameter ('HR_SEX', hp.SEX) hp_SEX,
       hp.EMERGENCYCONTACTOR hp_EMERGENCYCONTACTOR,
       hp.ENAME HP_ENAME,
       hp.ENROL_DATE hp_ENROL_DATE,
       hp.EXPERIENCESTARTDATE hp_EXPERIENCESTARTDATE,
       hp.GINSURANCEBEGIN hp_GINSURANCEBEGIN,
       hp.HIRE_DATE hp_HIRE_DATE,
       hp.ID_CARD hp_ID_CARD,
       pk_hr_code.F_CODENAME ('MARRIAGE', hp.MARRIAGE) hp_MARRIAGE,
       hp.ID_NO_SZ hp_ID_NO_SZ,
       hp.INDATE hp_INDATE,
       hp.MAILADDRESS hp_MAILADDRESS,
       hp.MAILADDRESS_MAN hp_MAILADDRESS_MAN,
       hp.NAME_SZ hp_NAME_SZ,
       hp.NATION hp_NATION,
       hp.ORIGIN hp_ORIGIN,
       hp.EMAIL hp_EMAIL,
       hp.MOBILETEL hp_MOBILETEL,
       hp.EMERGENCYCONTACTOR_TEL hp_EMERGENCYCONTACTOR_TEL,
       hp.ADDRESS_TEL hp_ADDRESS_TEL,
       hp.RECORD_ADDRESS hp_RECORD_ADDRESS,
       hp.DORMITORY hp_DORMITORY,
       pk_hr_code.F_CODENAME ('JOB', hp.JOBCATEGORY) hp_JOBCATEGORY,
       pk_hr_code.F_CODENAME ('FOURKINDS', hp.nb_leader) hp_NB_LEADER,
       hp.SLY_DEGREE hp_SLY_DEGREE,
       pk_hr_code.f_parameter ('HR_COSTALLOCATION', hp.COSTALLOCATION)
          hp_COSTALLOCATION,
       pk_hr_code.f_parameter (
          'HR_CARDING2',
          pk_ehr_util.f_get_carding_type (hp.seg_segment_no, hp.id))
          hp_CARDING
  FROM GL_SEGMENT gl, HR_PERSONNEL hp, HR_TITLE ht
 WHERE     hp.seg_segment_no = gl.seg_segment_no
       AND hp.seg_segment_no_department = gl.segment_no
       AND hp.title = ht.title(+)
       AND hp.seg_segment_no = ht.seg_segment_no(+)
       AND hp.seg_segment_no = :company_id
       AND hp.id =:emp_seqno";
	   
	$stmt = "begin pk_erp.P_SET_SEGMENT_NO(:companyid); p_set_appinfo('FYPYDWENIYFMTWQYPTDZDRCLRHDTEGAMTJBKKYSDVMBPXUXV'); end;";
	$stid = oci_parse($conn, $stmt);
	oci_bind_by_name($stid, ':companyid', $_SESSION ['user'] ['company_id']);
	oci_execute($stid);			
		
	$stid = oci_parse($conn, $sql);
	oci_bind_by_name($stid, ':company_id', $_SESSION ['user'] ['company_id']);
	oci_bind_by_name($stid, ':emp_seqno', $_SESSION ['user'] ['emp_seq_no']);	 
	oci_execute($stid);

	$result = oci_fetch_array($stid, OCI_BOTH + OCI_RETURN_NULLS);
// var_dump($result);exit;
	oci_free_statement($stid);
	oci_close($conn);		 
	return $result;	   
}
		
 	/**
	 * 检查当前 Login User是不是主管 (任何一个部门的主管,即不是本部门的主管也可以)
	 *
	 * @param string $user_emp_seq_no        	
	 * @return boolean true_是部门主管 false_非部门主管
	 * @author Dennis 2008-09-17 rewrite
	 */
	 
public function IsManager($company_id,$user_emp_seqno) {
		$sql = "
    		select count(1) as cou
			  from gl_segment
			 where seg_segment_no = :company_id
			   and segment_type = 'DEPARTMENT'
			   and (fa_parent_department = :user_emp_seqno1 or
			       leader_emp_id = :user_emp_seqno2)";
 
        $conn = oci_connect("hcp","hcp","cthcp","UTF8");
		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':company_id', $company_id);
		oci_bind_by_name($stid, ':user_emp_seqno1', $user_emp_seqno);	
		oci_bind_by_name($stid, ':user_emp_seqno2', $user_emp_seqno);
		oci_execute($stid);
		$result = oci_fetch_array($stid, OCI_BOTH);
 
		oci_free_statement($stid);
		oci_close($conn);
		if ($result['COU']!=0) return true;
		return false;
	} // end IsManager()
	
/**
	 * 檢查密碼是否證正確
	 *
	 * @param string $username        	
	 * @param string $password        	
	 * @param string $companyid        	
	 * @return boolen
	 * @author Yun 2015-06-01
	 *        
	 *         錯誤代碼：0. 1. 2.
	 */
	public function PasswordStatus($company_id,$username, $password, $language) {
		//get client ip add by Yun 2015-08-10
		$ipaddress = '';
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		}
		else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else if(!empty($_SERVER['HTTP_X_FORWARDED']))
		{
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		}
		else if(!empty($_SERVER['HTTP_FORWARDED_FOR']))
		{
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		}
		else if(!empty($_SERVER['HTTP_FORWARDED']))
		{
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		}
		else if(!empty($_SERVER['REMOTE_ADDR']))
		{
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		}
		
		if(empty($_SERVER["REMOTE_USER"])){
			$_SERVER["REMOTE_USER"] = '';
		}
		
		$stmt = "begin pk_app_login.main_login(:username,:password,:companyid,:language,'eHR',:ip,:host,'');end;";

        $conn = oci_connect('hcp','hcp','cthcp',"UTF8");
		
		$stid = oci_parse($conn, $stmt);
		oci_bind_by_name($stid, ':companyid', $company_id);
		oci_bind_by_name($stid, ':username', $username);	
		oci_bind_by_name($stid, ':password', $password);		
		oci_bind_by_name($stid, ':language', $language);			
		oci_bind_by_name($stid, ':ip', $ipaddress);	
		oci_bind_by_name($stid, ':host', $_SERVER["REMOTE_USER"]);	
		oci_execute($stid);
		
		$sql = "select pk_app_login.f_login_success as IS_SUCCESS,
             pk_app_login.f_errtype as ERROR_CODE,
             pk_app_login.f_errmsg as ERRORMSG
        from dual";
		$stid = oci_parse($conn, $sql);
		oci_execute($stid);
		$result = oci_fetch_array($stid, OCI_BOTH);		

		//var_dump($result);exit();
		if (! empty ( $result ) and $result['IS_SUCCESS'] != 'Y') 
			{
//			header ( "Content-Type:text/html; charset=utf-8" );
//			echo '<script>alert("' . $result['ERRORMSG'] . '");</script>';
//			echo '<script> location.assign("' . base_url() . 'index.php/login"); </script>';
  
			return   $result['ERRORMSG'];
			}
			else 
			{
			//add set language
			$stmt = "begin pk_erp.p_set_language(:language);end;";
			$stid = oci_parse($conn, $stmt);
			oci_bind_by_name($stid, ':language', $language);		
			oci_execute($stid);				
			}
	oci_free_statement($stid);
	oci_close($conn);	
	return '';	
	}
 

public function SetToken($username,$userid) {
        $conn = oci_connect("hcp","hcp","cthcp","UTF8");
 		
		$sql = " delete from Ares_Token
			 where username = :username";
		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':username', $username );
	 
        oci_execute($stid);
		
		$sql = " insert into Ares_Token(username,userid) values(:username,:userid)";
		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':username',  $username );
		oci_bind_by_name($stid, ':userid',  $userid );		
        oci_execute($stid);
 
		oci_free_statement($stid);
 			
		oci_close($conn);		
		return true;
	} // end IsManager() 

public function QryToken($username) {
	//echo '$username='.$username;exit;
        $conn = oci_connect("hcp","hcp","cthcp","UTF8");
		
		$sql = " select count(0) as cou from Ares_Token
			 where username = :username";
			 
		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':username',$username );
   		oci_execute($stid); 
 
		$result = oci_fetch_array($stid, OCI_BOTH);
 
		//echo ' $username ='.$username.' cou='.$result['COU'].' $sql='.$sql;
		oci_free_statement($stid);
		oci_close($conn);
		if ($result['COU']!=0) return true;
		
		return false;
	} // end QryToken() 

public function QryIdByToken($username) {
	    $result ="";
        $conn = oci_connect("hcp","hcp","cthcp","UTF8");
 		
		$sql = " select userid  from Ares_Token";
		$sql .= " where username = '".$username."'";
			 
		$stid = oci_parse($conn, $sql);
   		oci_execute($stid); 
 
		 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
			 $result = $row['USERID'];
		} 
 
		oci_free_statement($stid);
		oci_close($conn);		
        return $result ;		
 

	} // end QryToken() 

public function DelToken($username) {
        $conn = oci_connect("hcp","hcp","cthcp","UTF8");
  	$sql = " delete from Ares_Token
			 where username = :username";
		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':username', $username );
	 
        oci_execute($stid);
 
		
		return true;
	} // end DelToken() 	
}
?>