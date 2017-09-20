</head>
<style type="text/css">
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

input[type='text']
{
    font-size:45px;
	width: auto;
}
input[type='date']
{
    font-size:45px;
	width: auto;
}
input[type='time']
{
    font-size:45px;
	width: auto;
}
.styled-select {
	overflow: hidden;
	height: 74px;
	float: left;
	width: auto;
	margin-right: 10px;
	background: url(<?php echo base_url()?>img/9ldb8j.png) no-repeat right center #5c5c5c;
}

.styled-select select {
	font-size: 45px;
	border-radius: 0;
	border: none;
	width: auto;
	overflow: hidden;
	padding-top: 10px;
	height: 70px;
	text-indent: 10px;
	color: #000000;
	-webkit-appearance: none;
}

.styled-select optgroup {
    font-size: 45px;
}

.styled-select option.sel_Data {
	font-size: 45px;
	padding: 5px;
	background: #dddddd;
}

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
  margin: 40px auto;
  width: 100%;
} 
</style>
<?php include("block_calendar_js_view.php"); ?>
<body>
    <div class ="header" style="text-align:left; font-family: 'Arial';position: fixed" >
	<table border="0" cellpadding="0" cellspacing="0"><tr>
      <td><div style="font-size: 70px; height:75px; display: inline-block; color:#69F; font-family:'微軟正黑體'">線上申請 </div> </td> <td><div style="text-align:right;"><a href="<?php echo base_url()?>index.php/menu"><img src="<?php echo base_url()?>img/previous3.png" width="450" height="85" alt="pev"></a></div></td>
	  </tr></table>
	  </div><div class="page__content-container">
 <div  style="text-align:left;overflow-y:auto;overflow-x:auto;padding:10px; margin-bottom:10px;font-family:'微軟正黑體';font-size: 45px;" class="ui-widget-content ui-corner-all"><h4 class="ui-widget-header">加班單</h4>
      <?php
	       echo validation_errors();
	       $attributes = array('id' => 'ovtapplyform','name' => 'ovtapplyform');
	       echo form_open('ovt_apply',$attributes) ;
	  ?>
<table border="0" cellpadding="0" cellspacing="0" class="bordertable">
		<tbody><tr>
			<td width="100" class="column-label" bgcolor="#FFFF00">加班日期</td>
			<td>
				<input type="date" 
					   name="overtime_date" 
					   id="overtime_date"
					   title="Date Format: YYYY-MM-DD"  
					   value="<?php echo substr($_SESSION['Calendar_SelDate'], 0, 4).'-'.substr($_SESSION['Calendar_SelDate'], 4, 2).'-'.substr($_SESSION['Calendar_SelDate'], 6, 2) ?>"/>
				<script type="text/javascript">
					//	if (!Modernizr.inputtypes.date) {
							$('input[type=date]').datepicker({ 
								// Consistent format with the HTML5 picker
								dateFormat: 'yyyy-mm-dd'
							});
					//	}
				</script>
			</td>
		</tr>
		<tr>
			<td class="column-label" bgcolor="#FFFF00">開始時間</td>
			<td>
				<input type="time" 
					   name="begin_time" 
					   id="begin_time"
					   class="input-text"  
					   title="Time format: HH24:MI"
					   value="18:00"/>
			</td>

		</tr>
		<tr>
			<td class="column-label" bgcolor="#FFFF00">結束時間</td>
			<td>
				<input type="time" 
					   name="end_time" 
					   id="end_time"
					   class="input-text"
					   title="Time format: HH24:MI"
					   value="21:00"/>
			</td>
		</tr>
		<tr >
			<td class="column-label">時數</td>
			<td>
				<input type="text"
					   name="overtime_hours" 
					   id="overtime_hours" 
					   class="input-text"/>
					    <input type="hidden" name="overtime_hours2" id="overtime_hours2" value=""/>
			</td>
		</tr>
		<tr>
			<td class="column-label">加班原因</td>
			<td>
			<div class="styled-select">
				<select name="overtime_reason" id="overtime_reason">
				<optgroup>
				<option value="">-- 請選擇 --</option>
                <?php 
				foreach ($overtime_reason as $row) 
                { ?>
				<option label="<?php echo $row['overtime_reason'];?>"  class="sel_Data" value="<?php echo $row['overtime_reason_id']; ?>"><?php echo $row['overtime_reason']; ?></option>';
               <?php 
			    }
			   ?>
				</optgroup>
				</select>
				</div>
			</td>
		</tr>
		<tr>
			<td class="column-label" bgcolor="#FFFF00">計費/補休</td>
			<td>
			<div class="styled-select">			
				<select  name="overtime_fee_type1"  id="overtime_fee_type1">
				<optgroup>				
					<option value="">-- 請選擇 --</option>
                <?php 
	           foreach ($overtime_fee_type as $row) 
                { ?><option label="<?php echo $row['lable_text'];?>"  class="sel_Data" value="<?php echo $row['option_value']; ?>"><?php echo $row['lable_text']; ?></option>';
               <?php 
			    }
			   ?>
				</optgroup>			   
 				</select>
				</div>				
				<input type="hidden" name="overtime_fee_type" id="overtime_fee_type" value="">
			</td>
		</tr>
		<tr >
			<td class="column-label" bgcolor="#FFFF00">加班類型</td>
			<td>
			<div class="styled-select">				
				<select name="overtime_type1" id="overtime_type1">
				<optgroup>				
					<option value="">-- 請選擇 --</option>
                <?php 
	           foreach ($overtime_type as $row)
                { ?><option label="<?php echo $row['lable_text'];?>" class="sel_Data" value="<?php echo $row['option_value']; ?>"><?php echo $row['lable_text']; ?></option>';
               <?php 
			    }
			   ?>	
				</optgroup>					   
			</select>
				</div>				
				<input type="hidden" name="overtime_type" id="overtime_type" value="N">
			</td>
		</tr>
		<tr>
			<td valign="top" class="column-label">備註</td>
			<td><textarea name="remark" id="remark"></textarea>
			</td>
		</tr>
        <tr>
			<td></td>
			<td>
				<input type="submit" name="submit" id="doPost" value="送出" class="button-submit ui-button ui-widget ui-state-default ui-corner-all" role="button">
			</td>
		</tr>
        		
	</tbody></table>
       </form>
        </div></div>
    <script type="text/javascript">
	$().ready(function(){
		$('#overtime_hours').prop("disabled", true);
		$('#overtime_hours').css('background-color' , '#DEDEDE'); // change the background color	
		getFactHour();		
	});	
	/* add by boll 2009-04-20 
	**  取加班時數
	*/
	$("#overtime_date").blur(function(){
		getFactHour();
	});
	$("#begin_time").change(function(){
		getFactHour();
	});
	
	$("#overtime_type1").change(function(){
		$('#overtime_type').val($(this).val());
	});
	
	$("#end_time").change(function(){
		getFactHour();
	});
	function getFactHour(){
		var d1=$("#begin_time").val();
		var d2=$("#end_time").val();
		var d3=$("#overtime_date").val();

		$.ajax({
			  type: 'POST',
			   url: '<?php echo base_url()?>application/controllers/ajax_overtime.php',
			   dataType: 'text',				   
			   data: {begin_time: d1,
					  end_time: d2, 
					  overtime_date: d3 },
				  success: function(data2){
					  console.log(data2);
				 var data =JSON.parse(data2.replace(/^\s+|\s+$/g, ''));
					 if (data['hours'] == '')
						   {
						   alert($r['errmsg'] );
						   return '';
						   }		 
					   $("#overtime_hours").val(data['hours']);$("#overtime_hours2").val(data['hours']);
					   $("#overtime_type1").val(data['day_type']);
					   $("#overtime_type").val(data['day_type']);
				   },
				error:function(d){
				 //console.log(d);
				 //alert('Get Shift Time Error:'+d.responseText);
				 alert('Get Shift Time Error:'+d);
				}// end function 				   
				  
				  });
	};
	$(function(){getFactHour()});
    </script>
   