
</head>
<body class="page-container">
<div class="msg-center">
<!--{include file="block_box_header.html" title="<img src='$IMG_DIR/error.png'/> Error" showLine = 1}-->
<!--{include file="block_failure_msgbox.html" message_text=$smarty.get.msgtxt|html_entity_decode}-->
<!--{include file="block_back_to_page.html"}-->
<!--{include file="block_box_footer.html"}-->
</div>
<script type="text/javascript">
    function goback(){
    	console.log('goback');
    	window.history.back();
    }	
</script>