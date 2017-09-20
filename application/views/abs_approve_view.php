
<script src="<?php echo base_url()?>js/functions.js"></script>
</head>
<body class="page-container">
<!--{include file="block_box_header.html" title="$BLOCK_TITLE" showLine=1}-->
<?php if !is_array($this->input->post(index)) 
      include file="block_leave_approve_list_view.php";  
    else 
      include file="block_leave_approve_result_view.php"; 
 
 ?>