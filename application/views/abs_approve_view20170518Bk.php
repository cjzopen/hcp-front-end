<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>jquery.datepicker</title>
    <script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.11.1.min.js"></script>
 
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
  border-collapse:collapse;
}
table.TB_COLLAPSE caption {
  padding:10px;
  font-size:24px;
  background-color:#f3f6f9;
}
table.TB_COLLAPSE thead th {
  padding:5px 0px;
  color:#fff;
  background-color:#915957;
}
table.TB_COLLAPSE tbody td {
  padding:5px 0px;
  color:#555;
  text-align:center;
  background-color:#fff;
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

      <table class="TB_COLLAPSE">
          <tr>
            <th width="15%"><div style="font-size: 32px; height:50px; display: inline-block;">　　　</div></th>
            <th width="15%"><div style="font-size: 32px; height:50px; display: inline-block;">駁回<br/>原因</div></th>
            <th width="17%"><div style="font-size: 32px; height:50px; display: inline-block;">假別<br/>名稱</div></th>                            
            <th width="20%"><div style="font-size: 32px; height:50px; display: inline-block;">部門<br/>員工名字</div></th>
            <th width="33%"><div style="font-size: 32px; height:50px; display: inline-block;">開始時間<br/>結束時間</div></th>
          </tr>
	 <?php  
	      echo  "<span style="border:2px red solid;font-size:18px;"> $links </span>" ;
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
        <br/>
       <div style="text-align: center;">
       <img src="<?php echo base_url()?>img/提交所選-1.png" width="149" height="41" alt="B1">　<img src="<?php echo base_url()?>img/駁回全部.png" width="149" height="41" alt="B2">　<img src="<?php echo base_url()?>img/核准全部.png" width="149" height="41" alt="B3"></td><td>　<img src="<?php echo base_url()?>img/重置.png" width="149" height="41" alt="B4"> </div>
 
</body>
</html>