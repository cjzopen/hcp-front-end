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
 .control_checkbox{ width: 4em; height: 4em; }
 
.styled-select option.sel_Data {
	font-size: 45px;
	padding: 5px;
	background: #dddddd;
 	text-vlign: middle;
}

 .column-label{ background: #FFFF00;}
 
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
      <td><div style="font-size: 70px; height:75px; display: inline-block; color:#69F; font-family:'微軟正黑體'">線上申請</div> </td> <td><div style="text-align:right;"><a href="<?php echo base_url()?>index.php/menu"><img src="<?php echo base_url()?>img/previous3.png" width="450" height="85" alt="pev"></a></div></td>
	  </tr></table>
	  </div>

 <div class="page__content-container">	   
 <div style="text-align:left;overflow-y:auto;overflow-x:auto;padding:10px; margin-bottom:10px;font-family:'微軟正黑體';font-size: 45px;" class="ui-widget-content ui-corner-all"><h4 class="ui-widget-header">請假單</h4>
      <?php
	       echo validation_errors();
	       $attributes = array('id' => 'absapplyform','name' => 'absapplyform');
	       echo form_open('abs_apply',$attributes) ;
	  ?>
<div style="overflow-y:auto;overflow-x:auto;padding:10px; margin-bottom:10px;font-family:'微軟正黑體';font-size: 45px;" class="ui-widget-content ui-corner-all">		
<table border="0" cellpadding="0" cellspacing="0" class="bordertable">
		<tbody><tr>
			<td width="100" class="column-label" >假別名稱</td>		
			<td >
			<div class="styled-select" >
				<select name="absence_id" id="absence_id"  style="width: 400px;">
				<optgroup>
				<option value="">-- 請選擇 --</option>
                <?php 
				foreach ($LeaveNameList as $row) 
                { ?>
				<option label="<?php echo $row['absence_name'];?>"  class="sel_Data"  value="<?php echo $row['absence_seq_no'];?>"><?php echo $row['absence_name'];?></option>
               <?php 
			    }
			   ?>
				</optgroup>
				</select>
				</div>
			</td>
			</tr>
	<!--特別假 親屬類別 -->
	<tr id="Layer_funeral" style="display: none;">
		<td class="column-label" >名稱</td>
		<td>
		<div class="styled-select">
		<select name="funeral_id" id="funeral_id"
			onchange="ajax_absence_time()">
				<option value="-1"></option>
		</select>
		</div>		
		</td>
	</tr>			
		<tr>			
			<td width="100" class="column-label" >開始時間</td>
			<td>
				<input type="date" 
					   name="begin_date" 
					   id="begin_date"
					   title="Date Format: YYYY-MM-DD" onchange="set_agent()"
					   value="<?php echo substr($_SESSION['Calendar_SelDate'], 0, 4).'-'.substr($_SESSION['Calendar_SelDate'], 4, 2).'-'.substr($_SESSION['Calendar_SelDate'], 6, 2) ?>"/>
 
				<input type="time" 
					   name="begin_time" 
					   id="begin_time"
					   class="input-text" onchange="ajax_absence_time()" 
					   title="Time format: HH24:MI"
					   value="08:00"/>
			</td>

		</tr>
		<tr>
			<td class="column-label" >結束時間</td>
			<td>
				<input type="date" 
					   name="end_date" 
					   id="end_date"
					   title="Date Format: YYYY-MM-DD"  onchange="set_agent()"
					   value="<?php echo substr($_SESSION['Calendar_SelDate'], 0, 4).'-'.substr($_SESSION['Calendar_SelDate'], 4, 2).'-'.substr($_SESSION['Calendar_SelDate'], 6, 2) ?>"/>
 
				<input type="time" 
					   name="end_time" 
					   id="end_time"
					   class="input-text" onchange="ajax_absence_time()" 
					   title="Time format: HH24:MI"
					   value="17:30"/>
			</td>
		</tr>
		<tr id="TR_LEAVE_HOURS">
				<td >
					時數
				</td>
				<td id="leave_hours"></td>
			</tr>		
		<tr >
			<td class="column-label" >事由</td>
			<td>
				<input type="text"
				<textarea name="leave_reason" id="leave_reason"></textarea>
			</td>
		</tr>
		<tr>
			<td class="column-label">代理類型</td>
            <td>
			<input type="radio" class = "control_checkbox" name="assign_type" id="assign_type_0" value="0" checked="">不授權
			<input type="radio" class = "control_checkbox" name="assign_type" id="assign_type_1" value="1">完全代理
			<input type="radio" class = "control_checkbox" name="assign_type" id="assign_type_2" value="2">受限代理
			</td>	
		</tr>
  
		<tr >
			<td  class="column-label">代理人</td>
			<td>
			 <input type="text" name="agent" id="agent" value="无" readonly="" class="input-lov" style="width:300px;" onclick="show_employee_list('employee_name','employee_no','psn_id')" >
			 <input type="hidden" name="agent_id" id="agent_id">
			 <input type="hidden" name="agent_code" id="agent_code">
			</td>
		</tr>
		<tr>
			<td valign="top" >錯誤訊息</td>
			<td id='show_error'></td>
		</tr>
                <tr>
			<td id='zzz'></td>
			<td>
				<input type="submit" name="submit" id="doPost" value="送出" class="button-submit ui-button ui-widget ui-state-default ui-corner-all" role="button">
			</td>
		</tr>
        		
	</tbody></table>
      	  <input type="submit" name="submit" id="submitEditForm"
	style="display: none;">
       </form>
	 	</div>
     </div>		
    <script type="text/javascript">
	//settings.setDomStorageEnabled(true);
	/*
	if (sessionStorage) {$('#zzz').html('support') ;
  // LocalStorage is supported!
} else {
	$('#zzz').html('No support') ;
  // No support. Use a fallback such as browser cookies or store on the server.
}*/
    var is_spec = false;//是否為特別假
	$().ready(function(){
		
    	$('.ui-datepicker-trigger').attr('style','margin-left:2px;margin-bottom:-4px;');
        $('#dialog').hide();
    	//$('#TR_LEAVE_HOURS').hide();  // 表格中 時數部分 隱藏
	//	document.getElementById('test').innerHTML="安安";
    	set_agent();
/*		document.getElementById('test').innerHTML="set_agent";
		try{
			document.getElementById('test').innerHTML=sessionStorage.getItem("agent_id");
		}
		catch(e){
			document.getElementById('test').innerHTML="錯誤";
		}*/
		
		if (sessionStorage.getItem("agent_id") !=null)
		{	 
	//document.getElementById('test').innerHTML="設定內容";
		var loc_agent_id = sessionStorage.getItem('agent_id');
		var loc_agent_code = sessionStorage.getItem('agent_code');
		var loc_agent = sessionStorage.getItem('agent');	
		
		if(loc_agent_id.replace(/\s/g,"") != "")  {$('#agent_id').val(loc_agent_id) ;$( "#doPost" ).button().button( 'enable' );}//jquery UI support
		if(loc_agent_code.replace(/\s/g,"") != "")  $('#agent_code').val(loc_agent_code) ;
		if(loc_agent.replace(/\s/g,"") != "")  $('#agent').val(loc_agent) ; 
		}
		
	/*if (sessionStorage.agent_id !=null)
		{	 
		var loc_agent_id = sessionStorage.getItem('agent_id');
		var loc_agent_code = sessionStorage.getItem('agent_code');
		var loc_agent = sessionStorage.getItem('agent');	
		
		if(loc_agent_id.replace(/\s/g,"") != "")  {$('#agent_id').val(loc_agent_id) ;$( "#doPost" ).button().button( 'enable' );}//jquery UI support
		if(loc_agent_code.replace(/\s/g,"") != "")  $('#agent_code').val(loc_agent_code) ;
		if(loc_agent.replace(/\s/g,"") != "")  $('#agent').val(loc_agent) ; 
		}	*/
	});	
 
     /* add by boll 2009-04-20
     **  檢驗開始間、結束時間是不是在排程時間內
     */

    $("#doPost").click(function(){
	     return postData();
    });
    function postData(){
        var ok=CheckMustBeEntry(document.editForm);
        if(!ok) return false;
 
        var action=$("#action").val(); // 批量申請不檢驗
        if(action=='batch_apply' ) return $("#submitEditForm").click();
		      
        var begin_time = $("#begin_date").val() +' '+ $("#begin_time").val();// + ':' + $("#begin_minute").val();
        var end_time   = $("#end_date").val() +' '+ $("#end_time").val();//   + ':' + $("#end_minute").val();
        //alert(begin_time);alert(end_time);

        $.post('?<?php echo base_url()?>application/controllers/ajax_leave.php',
        		{ 
        			begin_time: begin_time,
        			end_time: end_time 
        		},
		        function(data){
		            //alert(data);
 
		            if(confirm('確定要送出?')){
		            	$('input[name=assign_type]').each(function(){$(this).prop('disabled', false);});
		                $("#submitEditForm").click();
		            }
		        }
		    );
    	}
		
  	
   /**
     *  特別假處理
     * last modify by dennis 2013-10-22
     * 有特别假设定才去 bind 这个事件
     */
 
    $("#absence_id").change(function(){
        var spec_abs = Array();
        is_spec = false;
         var tmp = <?php echo $spec_abs; ?>;
	//	 var tmp2 = JSON.parse(<?php echo $spec_abs; ?>); 
        // 先挑出特别假 id
        if ('<?php echo $spec_abs; ?>' != '')
        { 
            spec_abs = <?php echo $spec_abs ; ?> ;
			//spec_abs = spec_abs.replace(/^\s+|\s+$/g, '');
            //console.log(spec_abs);
            $("#Layer_funeral").hide();
            $('#funeral_id').val(-1);
            for (var i=0; i<spec_abs.length; i++)
            { //console.log(i + " >>>>"+ spec_abs[i]);
                // 是特别假的 show select
                if ($(this).val() == spec_abs[i])
                {
                    $("#Layer_funeral").show();
                    is_spec = true;
                    break;
                }
            }
           
            if(is_spec)
            {
			var ret =Array();
			var datas = { abs_type_id: $(this).val()};
            	$.ajax({
                    type:"post",
					data:datas,
                    url:"<?php echo base_url()?>application/controllers/ajax_spec_search.php?func=GetFamilyType",
                    async: false, 
                    timeout:1000,
                    dataType:'json',
                    success: function(json){
						ret = json2array(json);
						//for (i=0;i<ret.length;i++) console.log(i+"="+ret[i]);
						//console.log(json2array(json));
//						console.log(ret);
						
						var html = '';
						var tem ;
						for (var j=0; j<ret.length; j++)
						{//console.log( j+" addOptionToList>>>>str"+ret[j] );
							tem = ret[j].split("#");
						   html += '<option value='+tem[0]+'>'+tem[1]+'</option>';
						}// end for loop
						$('#funeral_id').append(html);

                  //  	addOptionToList('funeral_id',ret); 
                    	ajax_absence_time();
                    },
    				error:function(d){
    					alert('error->'+d.responseText);
    				}// end function
                });
            	
            }else{
            	//如果不是特別假就帶出請假時數
            	ajax_absence_time();
            }
        }
    });
 
 function json2array(json){
    var result = [];
    var keys = Object.keys(json);
    keys.forEach(function(key){
        result.push(json[key]);
    });
	//console.log(result);
    return result;
}
   /**
     * 把 ajax 返回的 json data 放到 agent_id 、 agent_name 、agent_code 中
     * @author Yun
     */
    function set_agent(){
 
    	
    	var data_array={};
    	$('#agent').val('');
    	//$('#doPost').hide();
    	$('input[name=assign_type][value=1]').prop('disabled', true);
    	$('input[name=assign_type][value=2]').prop('disabled', true);
    	$( "#doPost" ).button().button( 'disable' );//jquery UI support
    	$('#agent_id').val('');
    	ajax_absence_time();//取得請假時數 by Yun 2015-07-24
		
    	data_array['begin_date'] = $('#begin_date').val();
    	data_array['end_date'] = $('#end_date').val();
    	//console.log(data_array);
    	if(Date.parse(data_array['begin_date']) <= Date.parse(data_array['end_date']) || true){
    		$.ajax({
    			url : '<?php echo base_url()?>application/controllers/ajax_default_agent.php',
    			data : data_array,
    			type : "post",
    			dataType : 'text',
    			timeout : 100,

    			success : function(data) {
    		//		console.log(data);
					data = data.replace(/^\s+|\s+$/g, '');
    				if (is_Json(data) == true) {
    					var db_data = JSON.parse(data);
						console.log(data_array);return;
    					if(isNaN(parseInt(db_data['error_code'])) == false){
    						switch(parseInt(db_data['error_code'])){
    							case 1:
    								$('input[name=assign_type][value=0]').prop("checked", true);
    								clear_agent();
    								$('#show_error').html("無預設代理人");
    								break;
    							case 2:
    								$('#show_error').html("代理區間重疊，請拆單");
    								break;
    							case 3:
    								$('#show_error').html("資料未完整傳輸");
    								break;
    							default:
    								$('#show_error').html("無對應的錯誤代碼說明");
									break;
    						}
    					}else{
							$('#agent_id').val(db_data['AGENT_ID']);
							$('#agent').val(db_data['AGENT_NAME']);
							$('input[name=assign_type][value='+ db_data['ASSIGN_TYPE'] +']').prop("checked", true);
							$('#show_error').empty();
							//$('#doPost').show();
						//	$( "#doPost" ).button().button( 'enable' );//jquery UI support
    					}
    				} else {
    					$('#show_error').html("資料取得失敗");
    				}
    			},

    			error : function(xhr, ajaxOptions, thrownError) {
    				$('#show_error').html(xhr.status);
    				$('#show_error').html(thrownError);
    			}
    		});
    	}else{
    		$('#show_error').html("資料未輸入正確");
    	}
    }
    /**
     * function, decide it's Json or not
     * 
     * @access public
     * @param str,
     *            need to decide string
     * @return boolen
     */
    function is_Json(str) {
    	try {
    		JSON.parse(str);
    	} catch (e) {
    		return false;
    	}
    	return true;
    }
	
      /**
     * 把 ajax 返回的 json data 装载到 Select List 中
     * @param string list
     * @param array data
     * @param string month
     * @author Dennis
     */
    function addOptionToList(list,data)
    {
        // Clear list before add options
        $('#'+list).html('');
        // append options via jquery 
		var html = '';
		for (var j=0; j<data.length; j++)
        {
            //s = (data[j][0] == month ? 'selected' : '');
           html += '<option value='+data[j][0]+'>'+data[j][1]+'</option>';
        }// end for loop
		$('#'+list).append(html);
    }// end addOptionToList()
	
    /**
     * function, clear agent_id 、 agent_name
     * 
     * @access public
     * @param no_agent, boolen
     *            
     * @return boolen
     */
    function clear_agent(no_agent) {
    	//$('#agent').val('');
    	if(no_agent){
			$('#agent').val('');
			$('#agent_id').val('');
    	}
		$('#show_error').empty();
		//$('#doPost').hide();
		$( "#doPost" ).button().button( 'enable' );//jquery UI support
    }
    
		 /**
			 * show popup window to choose employee
			 *
			 * @param error_code
			 * @author Yun 2015-05-18
			 */
			 function show_employee_list(agent_name,agent_no,agent_id){
				 var url="<?php echo base_url()?>application/controllers/employee_lov.php?agent_name="+agent_name+"&agent_no="+agent_no+"&agent_id="+agent_id;
               //      var url="<?php echo base_url()?>application/controllers/employee_lov";
			         openw(url,"employee_lov",400,500);

         /*
            if(loc_agent_id.replace(/\s/g,"") != "")  $('#agent_id').text(loc_agent_id) ;
			if(loc_agent_code.replace(/\s/g,"") != "")  $('#agent_code').text(loc_agent_code) ;
			if(loc_agent.replace(/\s/g,"") != "")  $('#agent').text(loc_agent) ; 
			*/
 	        $('input[name=assign_type]').each(function(){$(this).prop('disabled', false);});
	        $('input[name=assign_type][value=2]').prop('checked', true);
	        $( "#doPost" ).button().button( 'enable' );//jquery UI support
	        $('#show_error').empty();
               //  window.location.href = "<?php echo base_url()?>application/controllers/employee_lov.php";
			}
    
	    /**
		 * 取得請假時數
		 *
		 * @author Yun 2015-07-23
		 */
		 
	function ajax_absence_time(){
		$('#leave_hours').empty();
		
	    var data_array = $('#absapplyform').serialize();
	 //   	console.log(data_array);
		if(is_spec == true){
			data_array += '&is_spec=1';
		}
		
		$.ajax({
			url : '<?php echo base_url()?>application/controllers/ajax_absence_time.php',
			data : data_array,
			type : "post",
			dataType : 'text',
			timeout : 10000,
			success:function(data){
				data = data.replace(/^\s+|\s+$/g, '');
				
				if (is_Json(data) == true) {
					var db_data = JSON.parse(data);
					if(isNaN(parseInt(db_data['error_code'])) == false){
						$('#leave_hours').html("資料未完整傳輸");
					}else{
						if(db_data['error'] == 'OK'){
							$('#leave_hours').append(parseFloat(db_data['days']) + "天,");
							$('#leave_hours').append(parseFloat(db_data['hours']) + "小時");
						}else{
							$('#leave_hours').append(db_data['error']);
						}
					}
				}else{
					$('#leave_hours').html(data);				
				}
			}
			
		});
	}
	
	/**
 * open url in new windows
 * 
 * @param url
 * @param winid
 * @param w
 * @param h
 * @param left
 * @param top
 * @returns {Boolean}
 */
function openw(url, winid, w, h, left, top) {
	winid='_self';
	if (left == undefined)
		left = 220;
	if (top == undefined)
		top = 200;
	var w = window
			.open(
					url + '&openW=Y&rand=' + Math.random(),
					winid,
					'top='
							+ top
							+ ', left='
							+ left
							+ ',width='
							+ w
							+ ',height='
							+ h
							+ ',location=yes,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no');
	// if (window.focus) {
	// w.focus();
	// }
	return false;
} // end openw()
    </script>