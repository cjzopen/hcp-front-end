<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF8" />
    <title>jquery.datepicker</title>
    <script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.11.1.min.js"></script>

    <!-- include jquery.datepicker -->
    <script type="text/javascript" src="<?php echo base_url()?>js/jquery.datepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/jquery.datepicker.css" />
 
    <script type="text/javascript">
        $(document).ready(function () {
 
            var $selected = $('.selected');
            var $start = $('.start');
            var $toggleMode = $('.toggleMode');

            var $some_datepicker = $('.some_datepicker');
            $some_datepicker.datepicker();
            var datepicker = jQueryDatepicker.data($some_datepicker);

            var date = new Date();

            $some_datepicker.on(jQueryDatepicker.event('date_selected'), function (event, date) {
                if (date.mode == 'date') {
					var yy= date.details.year;
					var mm= date.details.month;
					var dd= date.details.day;
                    var company_id = '<?php echo $_SESSION ['user'] ['company_id']?>';
                    var emp_seq_no = '<?php echo $_SESSION ['user'] ['emp_seq_no']?>';

 					var yy_s=  '<?php echo $_SESSION ['Calendar_yy']?>';
					var mm_s=  '<?php echo $_SESSION ['Calendar_mm']?>';
					var dd_s=  '<?php echo $_SESSION ['Calendar_dd']?>';
                    if (yy_s == yy && mm_s ==mm && dd_s == dd)
						{
							$("#MY_DAY").text($_SESSION['user']['MY_DAY']);
							$("#WORKGROUP_NAME").text($_SESSION['user']['WORKGROUP_NAME']);
							$("#IN_TIME").html($_SESSION['user']['IN_TIME']+'<br/>'+$_SESSION['user']['OUT_TIME']);	
							return;
						}
					
					$.ajax({
						type: "POST",
						url: "<?php echo base_url()?>application/controllers/pass_value.php",
						async: false, 
						data:{company_id:company_id,
						      emp_seq_no:emp_seq_no,
							  Cal_yy: yy,
							  Cal_mm: mm,
							  Cal_dd: dd},
						dataType: 'text',
						cache: false,
						success: function(data2) {
						//	console.log(data2);
							var data =JSON.parse(data2.replace(/^\s+|\s+$/g, ''));
                        //   console.log('myday='+data['MY_DAY']);
							$("#MY_DAY").text(data['MY_DAY']);
							$("#WORKGROUP_NAME").text(data['WORKGROUP_NAME']);
							$("#IN_TIME").html(data['IN_TIME']+'<br/>'+data['OUT_TIME']);
							 //window.location.href ='<?php echo base_url()?>'+'index.php/schedul'; 
								//alert(response.message);
								//console.log(response.responseText);
							},
						error:function(d){
						 console.log(d);
						 //alert('Get Shift Time Error:'+d.responseText);
						 //alert('Get Shift Time Error:'+d);
						}// end function 
				
					});
 
                  //   console.log('date selected:', date);
                } else if (date.mode == 'start_date') {
                    console.log('start date selected:', date);
                }

                if (datepicker.isStartDateSelected()) {
                    $start.show().html('<b>start date:</b> '+date.start_date.date.toString());
                }
                 $selected.show().html('<b>date:</b> '+date.date.toString());
            });

            // If you need a date range
            datepicker.setStartDate({
                year: 2017,
                month: date.getMonth()+1,
                day: 1
            });

            datepicker.setDate({
                year: <?php echo $_SESSION['Calendar_yy']?>,
                // jquery.datepicker accepts first month as 1
                // (built-in Date() class accepts first month as 0)
                month: <?php echo $_SESSION['Calendar_mm']?>,
                day: <?php echo $_SESSION['Calendar_dd']?>
            });

            $toggleMode.on('click', function (event) {
                datepicker.toggleMode();

                if (datepicker.getMode() == 'date') {
                    $toggleMode.html('toggle start date selection mode');
                } else {
                    $toggleMode.html('toggle date selection mode');
                }
            });
 

        });
	function default_Colorset()
	{
	<?php 
 		
		for($i=0;$i< count($data1);$i++)
		 {
		 echo "$('[day=\"".$data1[$i]."\"]').css('background-color','".$data2[$i]."');"; 	
		 }
         ?>		
	}	
    </script> 
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
  margin: 100px auto;
  width: 100%;
} 
</style>
</head>
<body onload='default_Colorset();'>

    <div class ="header" style="text-align:left; font-family: 'Arial';position: fixed" >
	<table border="0" cellpadding="0" cellspacing="0"><tr>
      <td><div style="font-size: 70px; height:75px; display: inline-block; color:#69F; font-family:'微軟正黑體'">我的排班 </div> </td> <td><div style="text-align:right;"><a href="<?php echo base_url()?>index.php/menu"><img src="<?php echo base_url()?>img/previous3.png" width="450" height="85" alt="pev"></a></div></td>
	  </tr></table>
	  </div>  
	  
 <div class="page__content-container">
      <div style="width: 935px; height:900px; font-family: 'Arial'; margin: auto;" >
            <div class="some_datepicker">
            </div>
      </div>
        <div style="text-align: left;font-size: 50px; height:50px; display: inline-block;">
           　<font color ="<?php echo $HolidyCode_color['N'];?>">▉工作日</font>　<font color ="<?php echo $HolidyCode_color['S'];?>">▉例假日</font>　<font color ="<?php echo $HolidyCode_color['R'];?>">▉休息日</font>　<font color ="<?php echo $HolidyCode_color['H'];?>">▉國定</font>
           </div><br/>
        <div style="text-align: left;font-size: 50px; height:50px; display: inline-block;">　<font color ="<?php echo $HolidyCode_color['H'];?>">假日</font>　<font color ="<?php echo $HolidyCode_color['U'];?>">▉免出勤日</font>　<font color ="<?php echo $HolidyCode_color['SYCU'];?>">▉當前日</font>　□未排程
 　　　   </div> <br/><br/>
 
      <table border="0" cellpadding="0" cellspacing="0" class="bordertable">
          <tr>
            <th><div style="font-size: 45px; height:100px; display: inline-block;text-align:left;">排程日期</div></th>
            <th><div style="font-size: 45px; height:100px; display: inline-block;text-align:left;">班別名稱</div></th>
            <th><div style="font-size: 45px; height:100px; display: inline-block;text-align:left;">上班時間<br/>下班時間</div></th>
          </tr>
 
          <tr> 
            <td><div id ="MY_DAY" style="font-size: 45px; height:45px; display: inline-block;"><?php echo  $_SESSION['user']['MY_DAY'] ?></div></td>
            <td><div id ="WORKGROUP_NAME" style="font-size: 45px; height:45px; display: inline-block;"><?php echo  $_SESSION['user']['WORKGROUP_NAME'] ?></div></td>
            <td><div id ="IN_TIME" style="font-size: 45px; height:45px; display: inline-block;"><?php echo  $_SESSION['user']['IN_TIME']?><br/><?php echo  $_SESSION['user']['OUT_TIME']?></div></td>
          </tr>
        </table>
	  </div> 
</body> 
</html>
