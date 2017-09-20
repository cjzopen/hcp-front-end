</head>
<body class="page-container">
	<div class="msg-center">
	<!--{include file="block_box_header.html" title="<img src='$IMG_DIR/success.png'/>" showLine = 1}-->
	<!--{include file="block_success_msgbox.html" message_text=$smarty.get.msgtxt|html_entity_decode}-->
	<!--{include file="block_back_to_page2.html"}-->
	<!--{include file="block_box_footer.html"}-->
	</div>
<script type="text/javascript">
    function goback2(){
    	//console.log('goback');
    	//window.history.go(-1);
		  window.location.href ='../ess/redirect.php?scriptname=<!--{$Ori_scriptname}-->&ReDoQry=Y';
 
    }	
</script>	