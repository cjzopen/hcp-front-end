 <style type="text/css"> 
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
  margin: 100px auto;
  width: 100%;
}
</style>
    <div class ="header" style="text-align:left; font-family: 'Arial';position: fixed" >
	<table border="0" cellpadding="0" cellspacing="0"><tr>
      <td><div style="font-size: 70px; height:75px; display: inline-block; color:#69F; font-family:'微軟正黑體'"><a href="<?php echo base_url()?>index.php/abs_approve">請假待簽核</a></div> </td> <td><div style="text-align:right;"><a href="<?php echo base_url()?>index.php/menu"><img src="<?php echo base_url()?>img/previous3.png" width="450" height="85" alt="pev"></a></div></td>
	  </tr></table>
	  </div>
 
 <div class="page__content-container">
	<div  style="text-align:left;overflow-y:auto;overflow-x:auto;padding:10px; margin-bottom:10px;font-family:'微軟正黑體';font-size: 45px;" class="ui-widget-content ui-corner-all"><h4 class="ui-widget-header">簽核請假結果</h4>
         提示:
        簽核成功 <?php echo $success_count;?> 筆
        ,失敗<?php echo $failure_count;?>筆 
    </div>
    </div>	