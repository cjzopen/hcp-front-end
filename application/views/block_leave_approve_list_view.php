
</head>
<style type="text/css">
.pagination
{
    border:0px solid #f09;
    font-size:50px;    
    text-align:center;
    float:center;
    right:0px;
    width:auto;
    position:relative;
}
.pagination a, .pagination-left, .pagination-right, .pagination-current
{
    float:center;    
    height:28px;
    width:auto;
    text-decoration:none;
    border:3px solid #0f0;
}
.pagination a, .pagination-current
{
    line-height:28px;    
}
.pagination a:link, a:visited
{
    background:#fff;    
    color:#69F;
}

.pagination a:hover, .pagination-current
{
    background:#fff;    
    color:#444;
}

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
 .control_checkbox{ width: 3em; height: 3em; }
 
  .header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 90px;
  z-index: 10;
  background: #eeeeee;
  -webkit-box-shadow: 0 7px 8px rgba(0, 0, 0, 0.12);
  -moz-box-shadow: 0 7px 8px rgba(0, 0, 0, 0.12);
  box-shadow: 0 7px 8px rgba(0, 0, 0, 0.12);
}
.page__content-container {
  margin: 80px auto;
  width: 100%;
}

textarea { font-size: 40px; }
</style>
<body>
    <div class ="header" style="text-align:left; font-family: 'Arial';position: fixed" >
	<table border="0" cellpadding="0" cellspacing="0"><tr>
      <td><div style="font-size: 70px; height:75px; display: inline-block; color:#69F; font-family:'微軟正黑體'">請假待簽核</div> </td> <td><div style="text-align:right;"><a href="<?php echo base_url()?>index.php/menu"><img src="<?php echo base_url()?>img/previous3.png" width="450" height="85" alt="pev"></a></div></td>
	  </tr></table>
	  </div> 
      <br/>
 <div class="page__content-container">
       <?php 
 
	       $attributes = array('id' => 'approveform','name' => 'approveform');
	       echo form_open('abs_approve',$attributes) ;
	  ?>
	  <table border="0" cellpadding="0" cellspacing="0" class="bordertable">
          <thead>
		   <tr >
            <th width="20%"><div style="font-size: 40px; height:45px; display: inline-block;">　　　</div></th>
            <th width="38%"><div style="font-size: 45px; height:45px;text-align:left;margin-top:10px;">部門　　</div><div style="font-size: 45px; height:45px;text-align:left;margin-top:10px;">員工名字</div></th>
            <th width="42%"><div style="font-size: 45px; height:45px;text-align:left;margin-top:10px;">開始時間</div><div style="font-size: 45px; height:45px;text-align:left;margin-top:10px;">結束時間</div></th>
			</tr>
          </thead>
	 <?php  
	      echo  $links;
		  if (isset($list) &&!empty($list))
		  {
		  $row_i=1;	  
	      foreach ($list as $row) 
			 {
			 ?>	 		  
          <tr>
            <td>
                <label>
                  <input type="radio" class="control_checkbox" name="approve_action<?php echo $row['approve_seqno'] ?>" value="" id="none_action<?php echo $row_i?>" ;width="100%";Height=4em; checked="checked" >
                  <div style="font-size: 45px; height:45px; display: inline-block;font-weight:bold;font-family:'新細明體'">無決定</div></label>
				  <br/>
                <label>
                  <input type="radio" class="control_checkbox" name="approve_action<?php echo $row['approve_seqno'] ?>" value="Y" id="approve_action<?php echo $row_i?>;width="100%";Height=4em">
                  <div style="font-size: 45px; height:45px; display: inline-block;font-weight:bold;font-family:'新細明體'">核准</div></label>
		        <br/>
                <label>
                  <input type="radio" class="control_checkbox" name="approve_action<?php echo $row['approve_seqno'] ?>" value="N" id="reject_action<?php echo $row_i?>;width="100%";Height=4em">
                  <div style="font-size: 45px; height:45px; display: inline-block;font-weight:bold;font-family:'新細明體'">駁回</div></label>
            </td>          
            <td><span class="hint  hint--right"  data-hint="<?php echo $row['absence_id'].$row['absence_name']?>"><div style="font-size: 45px; height:45px; display: inline-block;font-weight:bold;text-align:center;"><?php echo $row['dept_id']?></div><div style="font-size: 45px; height:45px; display: inline-block;font-weight:bold;text-align:center;"><?php echo $row['dept_name']?></div><br/><div style="font-size: 45px; height:45px; display: inline-block;font-weight:bold;text-align:center;"><?php echo $row['emp_name']?></div></span></td>       
            <td><div style="font-size: 45px; height:45px; display: inline-block;font-weight:bold;text-align:center;"><?php echo $row['begin_time']?></div><br/><div style="font-size: 45px; height:45px; display: inline-block;font-weight:bold;text-align:center;"><?php echo $row['end_time']?></div></td>
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
                <th colspan="1"><div style="font-size: 45px; height:45px; display: inline-block;text-align:center;vertical-align:top;margin-top:5px;">駁回原因<div></th>
                <td colspan="3">
                	<textarea name="all_reject_reason" style="width:98%;" rows="2"/></textarea>
                </td>
            </tr>                                   
        </table>
<div align="center" id="toolbarS" style="padding:5px; margin:5px;font-size: 37px; height:37px;">
	<input type="submit" name="dosubmit" id="submitform" class="button-submit ui-button ui-widget ui-state-default ui-corner-all" value="提交選單" role="button">
	<input type="button" name="reject_all" class="button-submit ui-button ui-widget ui-state-default ui-corner-all" value="駁回全部" onclick="confirmDoAllAction(document.form1,'reject');" role="button">
	<input type="button" name="approve_all" class="button-submit ui-button ui-widget ui-state-default ui-corner-all" value="核准全部" onclick="confirmDoAllAction(document.form1,'approve')" role="button">
	<input type="reset" name="reset" class="button-submit ui-button ui-widget ui-state-default ui-corner-all" value="重置" role="button">
</div>	   
       </form>
	   </div> 
    <script type="text/javascript">
 
      function confirmDoAllAction(the_form,action_type)
	  {
		var msg = "";  
		if (action_type=='approve')
          	msg = "確認要全部核准?";
            else
            msg = "確認要全部駁回?";				

		if (confirm(msg))
		{	  
			  batchApprove(the_form,action_type);
			  the_form.submit();
		}
	  }	
	  
	/**
	 * 全核准或全部驳回
	 */
	function batchApprove(myform,aType)
	{
		var els = myform.elements;
		for (var i=0; i<els.length; i++)
		{
			if (els[i].type == 'radio' &&
				els[i].id.substr(0,els[i].id.indexOf('_')) == aType)
			{
				els[i].checked = true;
			}// end if
		}// end for loop;
	}// end batchApprove()
    </script>			