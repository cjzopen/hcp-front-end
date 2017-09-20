
</head>
 <style>
table {
    white-space: normal;
    line-height: normal;
    font-weight: normal;
    font-size: medium;
    font-style: normal;
    color: -internal-quirk-inherit;
    text-align: start;
    font-variant: normal normal;
}
user agent stylesheet
table {
    display: table;
    border-collapse: separate;
    border-spacing: 2px;
    border-color: grey;
}
  td {
	font-size: 45px;
	padding-left: 1px;
	padding-bottom: 1px;
	 font-family:'微軟正黑體'
  }
 p {
    line-height: 1.0;
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
<body>
    <div class ="header" style="text-align:left; font-family: 'Arial';position: fixed" >
	<table border="0" cellpadding="0" cellspacing="0"><tr>
      <td><div style="font-size: 70px; height:75px; display: inline-block; color:#69F; font-family:'微軟正黑體'">我的訊息 </div> </td> <td><div style="text-align:right;"><a href="<?php echo base_url()?>index.php/menu"><img src="<?php echo base_url()?>img/previous3.png" width="450" height="85" alt="pev"></a></div></td>
	  </tr></table>
	  </div>  
	  
<div class="page__content-container">
 <div style="text-align:left;overflow-y:auto;overflow-x:auto;padding:10px; margin-bottom:10px;font-family:'微軟正黑體';font-size: 45px;" class="ui-widget-content ui-corner-all"><h4 class="ui-widget-header">個人資料</h4>
<table border="0" cellpadding="0" cellspacing="0" class="bordertable">
<tbody>
       <tr><td class="column-label" width="300">員工編號</td><td class="td-md"><?php echo $list['HP_ID_NO_SZ']?></td></tr>
       <tr><td class="column-label" width="300">員工姓名</td><td class="td-md"><?php echo $list['HP_NAME_SZ']?></td></tr>
       <tr><td class="column-label" width="300">英文姓名</td><td class="td-md"><?php echo $list['HP_ENAME']?></td></tr>
       <tr><td class="column-label" width="300">性别</td><td class="td-md"><?php echo $list['HP_SEX']?></td></tr>
       <tr><td class="column-label" width="300">籍貫</td><td class="td-md"></td></tr>
       <tr><td class="column-label" width="300">民族</td><td class="td-md"><?php echo $list['HP_NATION']?></td></tr>
       <tr><td class="column-label" width="300">生日</td><td class="td-md"><?php echo $list['HP_BIRTH']?></td></tr>
       <tr><td class="column-label" width="300">身份證號</td><td class="td-md"><?php echo $list['HP_ID_CARD']?></td></tr>
       <tr><td class="column-label" width="300">星座</td><td class="td-md"><?php echo $list['HP_CONSTELLATION']?></td></tr>
       <tr><td class="column-label" width="300">人員類別</td><td class="td-md"></td></tr>
       <tr><td class="column-label" width="300">員工分類</td><td class="td-md"></td></tr>
       <tr><td class="column-label" width="300">教育程度</td><td class="td-md"><?php echo $list['HP_EDUCATION']?></td></tr>
       <tr><td class="column-label" width="300">刷卡</td><td class="td-md"><?php echo $list['HP_BIRTH']?></td></tr>
       <tr><td class="column-label" width="300">婚姻狀況</td><td class="td-md"><?php echo $list['HP_CONTRACT']?></td></tr>
</tbody>
</table>
</div>

<div style="text-align:left;overflow-y:auto;overflow-x:auto;padding:10px; margin-bottom:10px;font-family:'微軟正黑體';font-size: 45px;" class="ui-widget-content ui-corner-all"><h4 class="ui-widget-header">聯繫信息</h4>
<table border="0" cellpadding="0" cellspacing="0" class="bordertable">
<tbody>
<tr><td class="column-label" width="300">住家地址</td><td class="td-md"><?php echo $list['HP_MAILADDRESS']?></td></tr>
<tr><td class="column-label" width="300">分機</td><td class="td-md">326</td></tr><tr><td class="column-label" width="300">缴交地点</td><td class="td-md"><?php echo $list['HP_TEL']?></td></tr>
<tr><td class="column-label" width="300">電子信箱</td><td class="td-md"><?php echo $list['HP_EMAIL']?></td></tr>
<tr><td class="column-label" width="300">行動電話</td><td class="td-md"><?php echo $list['HP_MOBILETEL']?></td></tr>
<tr><td class="column-label" width="300">聯絡電話</td><td class="td-md"><?php echo $list['HP_EMERGENCYCONTACTOR_TEL']?></td></tr>
<tr><td class="column-label" width="300">家庭電話</td><td class="td-md"><?php echo $list['HP_ADDRESS_TEL']?></td></tr>
<tr><td class="column-label" width="300">緊急聯絡人</td><td class="td-md"><?php echo $list['HP_EMERGENCYCONTACTOR']?></td></tr>
</tbody>
</table>
</div>

<div style="text-align:left;overflow-y:auto;overflow-x:auto;padding:10px; margin-bottom:10px;font-family:'微軟正黑體';font-size: 45px;" class="ui-widget-content ui-corner-all"><h4 class="ui-widget-header">工作信息</h4>
<table border="0" cellpadding="0" cellspacing="0" class="bordertable">
<tbody>
<tr><td class="column-label" width="300">部門代碼</td><td class="td-md"><?php echo $list['GL_SEGMENT_NO_SZ']?></td></tr>
<tr><td class="column-label" width="300">部門名稱</td><td class="td-md"><p><?php echo $list['GL_SEGMENT_NAME']?></p></td></tr>
<tr><td class="column-label" width="300">職務代碼</td><td class="td-md"><?php echo $list['HT_TITLE_NO_SZ']?></td></tr>
<tr><td class="column-label" width="300">職務名稱</td><td class="td-md"><?php echo $list['HT_TITLENAME']?></td></tr>
<tr><td class="column-label" width="300">錄用日期</td><td class="td-md"></td></tr>
<tr><td class="column-label" width="300">預計試用期滿日</td><td class="td-md"><?php echo $list['HP_TRIALDATE']?></td></tr>
<tr><td class="column-label" width="300">到職日期</td><td class="td-md"><?php echo $list['HP_INDATE']?></td></tr>
<tr><td class="column-label" width="300">年資日期</td><td class="td-md"><?php echo $list['HP_EXPERIENCESTARTDATE']?></td></tr>
<tr><td class="column-label" width="300">職等</td><td class="td-md"><?php echo $list['HP_SLY_GRADE']?></td></tr>
<tr><td class="column-label" width="300">職級</td><td class="td-md"><?php echo $list['HP_SLY_DEGREE']?></td></tr>
<tr><td class="column-label" width="300">轉正日期</td><td class="td-md"></td></tr>
<tr><td class="column-label" width="300">團保</td><td class="td-md"></td></tr>
<tr><td class="column-label" width="300">直接/間接</td><td class="td-md"><?php echo $list['HP_JOBCATEGORY']?></td></tr>
<tr><td class="column-label" width="300">入境日期</td><td class="td-md"></td></tr>
<tr><td class="column-label" width="300">四大類</td><td class="td-md"><?php echo $list['HP_NB_LEADER']?></td></tr>
</tbody>
</table>
</div>

<div style="text-align:left;overflow-y:auto;overflow-x:auto;padding:10px; margin-bottom:10px;font-family:'微軟正黑體';font-size: 45px;" class="ui-widget-content ui-corner-all"><h4 class="ui-widget-header">其他</h4>
<table border="0" cellpadding="0" cellspacing="0" class="bordertable">
<tbody><tr><td class="column-label" width="300">居住地聯絡人</td><td class="td-md"><?php echo $list['HP_MAILADDRESS_MAN']?></td></tr>
<tr><td class="column-label" width="300">戶籍地聯繫人</td><td class="td-md"><?php echo $list['HP_ADDRESS_MAN']?></td></tr>
<tr><td class="column-label" width="300">檔案所在地</td><td class="td-md"><?php echo $list['HP_RECORD_ADDRESS']?></td></tr>
<tr><td class="column-label" width="300">住宿</td><td class="td-md"><?php echo $list['HP_DORMITORY']?></td></tr>
<tr><td class="column-label" width="300">備註</td><td class="td-md"><?php echo $list['HP_REMARK']?></td></tr>
</tbody>
</table>
</div>
</div>
 
      
    
 
 