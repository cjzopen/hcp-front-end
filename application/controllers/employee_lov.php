<?php
session_start();
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
 $conn = oci_connect("hcp","hcp","cthcp","UTF8");
$sql = "select emp_seq_no as dept_id, 
		       emp_id     as dept_no,
		       emp_name   as dept_name
		  from ehr_employee_v
		 where company_id = 'A001'
		   and rownum < 30
		   and pk_history_data.f_get_status(company_id,emp_seq_no,sysdate) = 'JS1'";
if (isset($_POST['search_condition']) && !empty ( $_POST['search_condition'] )) $sql .=" and (emp_id like '%".$_POST['search_condition']."%' or emp_name like '%".$_POST['search_condition']."%')";    
		$sql .=" order by emp_id"; // updated by Gracie at 20090819 先显出本部门的人

$stid = oci_parse($conn, $sql);
 
oci_execute($stid);

$rs =Array();
 $cc =0;
 while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
			$rs[$cc]['dept_id'] = $row['DEPT_ID'];
			$rs[$cc]['dept_no'] = $row['DEPT_NO'];
			$rs[$cc]['dept_name'] = $row['DEPT_NAME'];
            	
			$cc =$cc + 1; 
 }
  //var_dump($rs);
//echo '$sql='.$sql;	
?>
<html>
<head xmlns="http://www.w3.org/1999/html">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<script type="text/javascript" src="http://localhost/eHR_H5/js/jquery-1.11.1.min.js"></script>
<link rel="icon" href="http://localhost/eHR_H5/img/ares.ico" type="image/x-icon" />
<link rel="shortcut icon" href="http://localhost/eHR_H5/img/ares.ico" />	
<link rel="stylesheet" href="http://localhost/eHR_H5/css/blueprint/screen.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="http://localhost/eHR_H5/css/hint2.css">
<!-- include jquery.datepicker -->
<script type="text/javascript" src="http://localhost/eHR_H5/js/jquery.datepicker.js"></script>
<script type="text/javascript" src="http://localhost/eHR_H5/js/jquery-ui.js"></script>	
<link rel="stylesheet" type="text/css" href="http://localhost/eHR_H5/css/redmond2/jquery-ui.css" />
<link rel="stylesheet" href="http://localhost/eHR_H5/css/default.css?m=20130917" type="text/css" media="screen, projection">	
<link rel="stylesheet" type="text/css" href="http://localhost/eHR_H5/css/jquery.datepicker.css" />
 
<style type="text/css">
/*注意选择器的层叠关系*/
.stripe_tb th {
	background: #B5CBE6;
	color: #003399;
	text-align: center;
}

.stripe_tb td {
	height:65px;
	border-bottom: 1px solid #DDDDDD;
	vertical-align: top;
	background: #FFFFFF;
	color: #000000;
	
}
 .control_checkbox{ width: 4em; height: 4em; }
 
.stripe_tb tr.alt td {
	background: #FCFCFC; /*#ecf6fc这行将给所有偶数行加上背景色*/
}

.stripe_tb tr.over td {
	background: #CCE6FF; /*这个将是鼠标经过高亮行的背景色*/
}

.stripe_tb tr.click td {
	background: #C9F6F6; /*这个将是鼠标点下后高亮行的背景色#FFD1D1*/
	color: #003366;
}

.Lov_Btn{
    height:70px;
    width:170px;
   font-size : 50px; 
   vertical-align: top;  
}
.input-text{
    width:400px;
    height:70px;
   font-size : 50px;	
    vertical-align: top;	
} 
 .Label{
    width:400px;
    height:70px;
   font-size : 50px;	
    vertical-align: top;
 display: inline-block;
 font-weight:bold;
 font-family:'新細明體'	
}  
.Label-header {
    border: 1px solid #4297d7;
    background: #5c9ccc url(images/ui-bg_gloss-wave_55_5c9ccc_500x100.png) 50% 50% repeat-x;
    color: #ffffff;
    width: auto;
	height: auto;
	vertical-align: top;
	font-size : 50px;
    font-weight: bold;
	margin:0;
}

  .header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height:445px;
  z-index: 10;
  background: #eeeeee;
  -webkit-box-shadow: 0 7px 8px rgba(0, 0, 0, 0.12);
  -moz-box-shadow: 0 7px 8px rgba(0, 0, 0, 0.12);
  box-shadow: 0 7px 8px rgba(0, 0, 0, 0.12);
}
.page__content-container {
  margin:470px auto;
  width: 100%;
  height:100%;
}
</style>
</head>
<body class="page-container"> 

<div class ="header" style="text-align:left; font-family: 'Arial';position: fixed;padding:10px; margin-bottom:10px;" >
<h4 class="Label-header">員工姓名</h4>
   		<form name="form1" method="post" action="employee_lov.php" target="lov_win">
		<input type="text"  class="input-text" id="search_condition" name="search_condition" value=""> <input type="submit" name="submit_qry" id="submit_qry" class="Lov_Btn" value=" 查詢 " role="button"> <br clear="all">
		<div class="Label">預計顯示 30 筆資料, 可以在輸入工號</div><div class="Label">或是姓名模糊查找</div>

	<input type="hidden" name="agent_name" value="agent">
	<input type="hidden" name="agent_id" value="agent_id">
	<input type="hidden" name="agent_no" value="agent_code">
	</form> <div id="no" class="Label">　</div>
	<div align="left" >
		<input id="confirm" type="button" class="Lov_Btn" value="確定" onclick="closeLOV();" role="button">
		<input id="cancel1" type="button" class="Lov_Btn" value="取消" onclick="javascript:window.history.back();" role="button"><div id="msg" class="Label">　</div>
	</div> 	
 	
		<table class="stripe_tb gridview" style="margin: 5px">
		<tbody><tr class="alt">
			<th></th>
				 <th width="40%">
				<div style="font-size: 50px; height:70px; display: inline-block;font-weight:bold;font-family:'新細明體'">員工代碼</div>
			     </th>
			    <th width="60%">
				<div style="font-size: 50px; height:70px; display: inline-block;font-weight:bold;font-family:'新細明體'">員工姓名</div>
			    </th>
		</tr></tbody></table>
</div>	
	
<div class="page__content-container">	 
<div style="padding:10px; margin-bottom:10px;" class="ui-widget-content ui-corner-all">
		<table class="stripe_tb gridview">
         <tbody> 
		
		<?php
         $i =  0;
		 foreach ($rs as $result) 	 
		 {	
		?>
				<tr line_no="<?php echo $i; ?>" <?php if (($i+1)%2==0) echo 'alt';?>>
			<td> <input class="control_checkbox" type="radio" name="choose_rdo" id="dept_id<?php echo $i; ?>" value="<?php echo $result['dept_id'].'||'.$result['dept_no'].'||'.$result['dept_name']; ?>" ontouchstart="setagent(this);" ></td>
						<td>
				<div style="font-size: 45px; height:45px; display: inline-block;font-weight:bold;font-family:'新細明體'"><?php echo $result['dept_no']; ?> <input type="hidden" id="dept_no<?php echo $i; ?>" value="<?php echo $result['dept_no']; ?>"></div>
			</td>
						<td>
				<div style="font-size: 45px; height:45px; display: inline-block;font-weight:bold;font-family:'新細明體'"><?php echo $result['dept_name']; ?> <input type="hidden" id="dept_name<?php echo $i; ?>" value="<?php echo $result['dept_name']; ?>"></div>
			</td>
		</tr>
		 <?php 
		 $i = $i + 1;		 
		 }
		 ?>
	 
				<input type="hidden" id="count" value="<?php echo $cc; ?>">
	</tbody></table>

	</div>				
	</div>
<script>
function setagent(item)
{
	// document.getElementById('test').innerHTML="進入Test";
	 try{
		 var aaa=  item.value.split('||');
		 sessionStorage.setItem('agent_id',aaa[0] );
		 sessionStorage.setItem('agent_code', aaa[1] ) ;
		 sessionStorage.setItem('agent',aaa[2])
 
		//	document.getElementById('test').innerHTML=sessionStorage.getItem("agent_id");
	 }
	 catch(e){
	//	 document.getElementById('test').innerHTML=e;
	 }

}
 
		function closeLOV() {  
			// modify by dennis 2012/5/9,当只有一笔资料时,点确定按钮自动选中当前这笔
			// issue： 非IE浏览器查询后无法选取联络人
			//if (select_row_idx != -1 || $('#count').val() == 1) {
			//	if ($('#count').val() != 1 ) {
			// 	var i = ($('#count').val() == 1) ? 0 : select_row_idx;
               
            // var count  = document.getElementById("count").value;
			// i = select_row_idx;
			// if ( count == 1) i = 0;
			// 
	        //   var dept_id = document.getElementById('dept_id2');
			// var dept_no = document.getElementById('dept_no2');
			// var dept_name = document.getElementById('dept_name2');	
				 
 /*			   var dept_id =  $('#dept_id'+i).val();
			   var dept_no =  $('#dept_no'+i).val();
			   var dept_name =  $('#dept_name'+i).val();*/
 
 /*
		         var dept_id = $("input[id=dept_id"+i+"]").val();
				 var dept_no = $("input[id=dept_no"+i+"]").val();
				 var dept_name = $("input[id=dept_name"+i+"]").val();	*/ 
 /*
			  var va1 ="dept_id"+i;
			  var va2 ="dept_no"+i;
			  var va3 ="dept_name"+i;				 
	             var dept_id = document.getElementById(va1);
				 var dept_no = document.getElementById(va2);
				 var dept_name = document.getElementById(va3);	 
				 
 
			  const va1="dept_id"+i;
			  const va2="dept_no"+i;
			  const va3="dept_name"+i;
  
	             var dept_id = document.getElementById(va1).value;
				 var dept_no = document.getElementById(va2).value;
				 var dept_name = document.getElementById(va3).value; */
   		 
	  /*       	 	var dept_id = $('#dept_id1').val();
				var dept_no = $('#dept_no1').val();
				var dept_name = $('#dept_name1').val();
	  	  
	   	 	var dept_id = $("#dept_id" + i).val();
				var dept_no = $("#dept_no" + i).val();
				var dept_name = $("#dept_name" + i).val();	*/	
		
	/*	var ret = {
					id : dept_id.trim(),
					no : dept_no.trim(),
					name : dept_name.trim()
				};
			} else {
				var ret = {
					id : '',
					no : '',
					name : ''
				};
			}// end if
			if (window.opener) {
				//for chrome
				window.opener.returnValue = ret;
			} else {
				window.returnValue = ret;
			} */
			//var opr = opener.document;
			//Add by Yun 2015-07-29 to input employee
		/*  opr.getElementById("agent").value = dept_name;
			opr.getElementById("agent_id").value = dept_id;
			opr.getElementById("agent_code").value = dept_no;
		 	window.close(); */ 
	  		//sessionStorage.agent_id =dept_id.value ;
			//sessionStorage.agent_code= dept_no.value ;
			//sessionStorage.agent= dept_name.value ;  
			window.history.back();
		// }
		}// end closeLOV()

		$("#submit_qry").click(function() {
			if ($('#search_condition').val() == '') {
				$('#msg').html('<font size="45px" color ="red">請輸入查詢條件!</font>');
				return false;
			}
			else
			{
			$('#msg').html('');	
			}
		});
	
	</script>	
<div id="employee_lov"></div>
</body>
</html>
 
	