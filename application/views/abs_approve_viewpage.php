<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>jquery.datepicker</title>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <!-- include jquery.datepicker -->
    <script type="text/javascript" src="js/jquery.datepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="css/jquery.datepicker.css" />
    <style type="text/css">
        * {
            margin: 0px;
            padding: 0px;
        }

        body {
            font-family: 'Arial';
            font-size: 16px;
            color: #383838;
        }

        .a,
        .a:visited {
            color: #176bb8;
            text-decoration: none;
        }

        .a:hover {
            text-decoration: underline;
        }

        .btn {
            display: inline-block;
            cursor: pointer;
            border: 1px solid #c1c1c1;
            border-bottom: 2px solid #c1c1c1;
            padding: 5px; border-radius: 2px;
            transition: all 250ms;
            font-size: 16px;
        }

        .selected,
        .start {
            font-size: 16px;
        }

        .btn:hover {
            background: #f1f1f1;
        }
 		
table.TB_COLLAPSE {
  width:100%;
   border-collapse:separate;
}
table.TB_COLLAPSE caption {
  padding:10px;
  font-size:24px;
  background-color:#f3f6f9;
}
table.TB_COLLAPSE thead th {
  padding:5px 0px;
  color:#fff;
  background-color:#69F;
}
table.TB_COLLAPSE tr th {
  color:#fff;
  background-color:#69F;
}
table.TB_COLLAPSE tbody td {
  padding:5px 0px;
  color:#555;
  text-align:center;
  background-color:#e0ffff;
  border-bottom:1px solid #915957;
}
table.TB_COLLAPSE tfoot td {
  padding:5px 0px;
  text-align:center;
  background-color:#d6d6a5;
}
</style>
</head>
<body><br/><br/>
    <div style="text-align: center; font-family: 'Arial';"><a href="<?php echo base_url()?>index.php/menu"><img src="<?php echo base_url()?>img/previous.png" width="32" height="32" alt="pev"></a>
      <div style="font-size: 50px; height:75px; display: inline-block; color:#69F; font-family:'微軟正黑體'">
        線上請假 >待簽核表單 </div>
 　　　    <br/><br/>  
      <?php 
	       $attributes = array('id' => 'approveform');
	       echo form_open('abs_approve/ActionApprove',$attributes) ;
	  ?>
      <table class="TB_COLLAPSE">
          <thead>
		   <tr>
            <th width="15%"><div style="font-size: 32px; height:70px; display: inline-block;">　　　</div></th>
            <th width="15%"><div style="font-size: 32px; height:70px; display: inline-block;">駁回<br/>原因</div></th>
            <th width="17%"><div style="font-size: 32px; height:70px; display: inline-block;">假別<br/>名稱</div></th>                            
            <th width="20%"><div style="font-size: 32px; height:70px; display: inline-block;">部門<br/>員工名字</div></th>
            <th width="33%"><div style="font-size: 32px; height:70px; display: inline-block;">開始時間<br/>結束時間</div></th>
			</tr>
          </thead>
	 <?php  
	      echo  "<span style=\"border:0px font-size:30px;\">". $links."</span>" ;
		  if (isset($list) &&!empty($list))
		  {
		  $row_i=1;	  
	      foreach ($list as $row) 
			 {
			 ?>	 		  
          <tr>
            <td>
                <label>
                  <input type="radio" name="approve_action<?php echo $row['approve_seqno'] ?>" value="" id="none_action<?php echo $row_i?>">
                  <div style="font-size: 25px; height:55px; display: inline-block;font-weight:bold;font-family:'新細明體'">無決定</div></label>
                <br>
                <label>
                  <input type="radio" name="approve_action<?php echo $row['approve_seqno'] ?>" value="Y" id="approve_action<?php echo $row_i?>">
                  <div style="font-size: 25px; height:55px; display: inline-block;font-weight:bold;font-family:'新細明體'">核准</div></label>
                <br>
                <label>
                  <input type="radio" name="approve_action<?php echo $row['approve_seqno'] ?>" value="N" id="reject_action<?php echo $row_i?>">
                  <div style="font-size: 25px; height:55px; display: inline-block;font-weight:bold;font-family:'新細明體'">駁回</div></label>
                <br>
            </td>          
            <td> 
                <label for="reject_reason"></label>
                <textarea name="reject_reason[]"  cols="12" rows="5" ></textarea>
             </td>
 
            <td><div style="font-size: 28px; height:55px; display: inline-block;font-weight:bold;"><?php echo $row['absence_id'].$row['absence_name']?></div></td>
            <td><div style="font-size: 28px; height:55px; display: inline-block;font-weight:bold;"><?php echo $row['dept_id']?><?php echo $row['dept_name']?><br/><?php echo $row['emp_name']?></div></td>            
            <td><div style="font-size: 28px; height:55px; display: inline-block;font-weight:bold;"><?php echo $row['begin_time']?><br/><?php echo $row['end_time']?></div></td>

			<input type="hidden" name="company_id[]" value="<?php echo $row['company_id'] ?>"/>
			<input type="hidden" name="workflow_seqno[]" value="<?php echo $row['workflow_seqno'] ?>"/>
			<input type="hidden" name="approve_seqno[]" value="<?php echo $row['approve_seqno'] ?>"/>
			<input type="hidden" name="approver_emp_seqno[]" value="<?php echo $row['approver_emp_seqno'] ?>"/>
			<input type="hidden" name="apply_type[]" value="<?php echo $row['apply_type'] ?>"/>				
          </tr>
	  <?php   
				 $row_i = $row_i + 1;
				}
			 }
			 ?>	 		  
              <tr>
                <th colspan="2"><div style="font-size: 25px; height:70px; display: inline-block;text-align:center;">駁回原因<div></th>
                <td colspan="3">
                	<textarea name="all_reject_reason" style="width:98%;"/></textarea>
                </td>
            </tr>                                   
        </table></div>
        <br/>
       <div style="text-align: center;">
       <a href="#" id="approveSel"  onclick ="document.getElementById('approveform').submit();"><img src="<?php echo base_url()?>img/提交所選-1.png" width="149" height="41" alt="B1"></a>　<a href="#" id="approveReject" name="href" onclick ="document.getElementById('approveform').submit();"><img src="<?php echo base_url()?>img/駁回全部.png" width="149" height="41" alt="B2"></a>　<a href="#" id="approveAll" onclick ="document.getElementById('approveform').submit();"><img src="<?php echo base_url()?>img/核准全部.png" width="149" height="41" alt="B3"></a></td><td>　<a href="#" id="reset" ><img src="<?php echo base_url()?>img/重置.png" width="149" height="41" alt="B4"></a> </div>
       </form>
</body>
</html>