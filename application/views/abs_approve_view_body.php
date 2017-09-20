<body><br/><br/>
    <div style="text-align: center; font-family: 'Arial';"><a href="<?php echo base_url()?>index.php/menu"><img src="<?php echo base_url()?>img/previous.png" width="32" height="32" alt="pev"></a>
      <div style="font-size: 50px; height:75px; display: inline-block; color:#69F; font-family:'微軟正黑體'">
        線上請假 >待簽核表單 </div>
 　　　    <br/><br/>

      <table>
          <tr>
            <th width="15%"><div style="font-size: 32px; height:50px; display: inline-block;">　　　</div></th>
            <th width="15%"><div style="font-size: 32px; height:50px; display: inline-block;">駁回<br/>原因</div></th>
            <th width="17%"><div style="font-size: 32px; height:50px; display: inline-block;">假別<br/>名稱</div></th>                            
            <th width="20%"><div style="font-size: 32px; height:50px; display: inline-block;">部門<br/>員工名字</div></th>
            <th width="33%"><div style="font-size: 32px; height:50px; display: inline-block;">開始時間<br/>結束時間</div></th>
          </tr>
	 <?php  
	      echo  $links;
	      foreach ($list as $row) 
			 {
			 ?>	 		  
          <tr>
            <td>
                <label>
                  <input type="radio" name="RadioGroup1" value="0" id="RadioGroup1_0">
                  <div style="font-size: 25px; height:45px; display: inline-block;font-weight:bold;font-family:'新細明體'">無決定</div></label>
                <br>
                <label>
                  <input type="radio" name="RadioGroup1" value="0" id="RadioGroup1_1">
                  <div style="font-size: 25px; height:45px; display: inline-block;font-weight:bold;font-family:'新細明體'">核准</div></label>
                <br>
                <label>
                  <input type="radio" name="RadioGroup1" value="0" id="RadioGroup1_2">
                  <div style="font-size: 25px; height:45px; display: inline-block;font-weight:bold;font-family:'新細明體'">駁回</div></label>
                <br>
            </td>          
            <td> 
                <label for="Area"></label>
                <textarea name="Area" id="Area" cols="12" rows="5" ></textarea>
             </td>
 
            <td><div style="font-size: 28px; height:45px; display: inline-block;font-weight:bold;"><?php echo $row['absence_id'].$row['absence_name']?></div></td>
            <td><div style="font-size: 28px; height:45px; display: inline-block;font-weight:bold;"><?php echo $row['dept_id']?><?php echo $row['dept_name']?><br/><?php echo $row['emp_name']?></div></td>            
            <td><div style="font-size: 28px; height:45px; display: inline-block;font-weight:bold;"><?php echo $row['begin_time']?><br/><?php echo $row['end_time']?></div></td>
          </tr>
	 <?php  
			 }
			 ?>	 		  
                                    
        </table></div>
<script type="text/javascript">
 $(document).ready(function () {
            $('a').click(function(){
                var page_sel = $(this).attr("value");
				console.log('page_sel='+page_sel);
                $.ajax({
					type: 'POST',
					url: '<?php echo base_url()?>application/controllers/set_page.php',
					data: {page_sel:page_sel},
					dataType: 'json',
					async: false,					
					sucess: function(data) {
						//var db_data = JSON.parse(data);
						var db_data =Ext.util.JSON.decode(data.responseText);
					  //   console.log(db_data);
						// console.log( db_data[0]['leafy']['absence_id'] );
				       // console.log(db_data[0]['absence_id']);
 
						$.each(data[0], function(key, val) {
						$('body').append('<div>' + val.id + ' ' + val.name + '</div>');
						});
						
						//window.location ="<?php echo base_url()?>index.php/abs_approve";
						//window.location.href=window.location.href;
						//$(this).attr("href",<?php echo base_url()?>index.php/abs_approve);
                      //  $('#sucess').html(data);
					}
				});
				return false;
			});
		});
</script>		
        <br/>
       <div style="text-align: center;">
       <img src="<?php echo base_url()?>img/提交所選-1.png" width="149" height="41" alt="B1">　<img src="<?php echo base_url()?>img/駁回全部.png" width="149" height="41" alt="B2">　<img src="<?php echo base_url()?>img/核准全部.png" width="149" height="41" alt="B3"></td><td>　<img src="<?php echo base_url()?>img/重置.png" width="149" height="41" alt="B4"> </div>
 </body>		