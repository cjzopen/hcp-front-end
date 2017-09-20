<!DOCTYPE html>
<html lang="zh">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="IE=edge, chrome=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<title>HCP 人力資產規劃系統</title>
	<meta name="description" content="資通電腦人力資產規劃系統 HCP 行動裝置介紹。全球人資訊平台，在你的指尖展開。">
	<link rel="stylesheet" href="<?php echo base_url()?>css/main.css">
	<script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
</head>
<body>
<div class="wrap">
	<header class="container-fluid">
		<i class="tac"><img src="<?php echo base_url()?>img/home/ic_menu_white_24px.svg" alt="*"></i>
		<h1>HCP 人力資產規劃系統</h1>
	</header>

	<figure>
		<img class="img-responsive" src="<?php echo base_url()?>img/home/HCP-M-banner.jpg" alt="HCP">
	</figure>
	<div class="container-fluid icon-area" style="width:auto; height:auto">
		<div class="row tac">
			<div class="col-xs-4">
				<a href="<?php echo base_url()?>index.php/person_qry">
					<div class="icon" style="background-image: url(<?php echo base_url()?>img/home/message.svg);background-color: #834d99"></div>
					<h4>我的訊息</h4>
				</a>
			</div>
			<div class="col-xs-4"> 
				<a href="<?php echo base_url()?>index.php/schedul">
					<div class="icon" style="background-image: url(<?php echo base_url()?>img/home/Scheduling.svg);background-color: #67b460"></div>
					<h4>我的排班</h4>
				</a>
			</div>
			<div class="col-xs-4">
				<a href="<?php echo base_url()?>index.php/ovt_approve">
					<div class="icon" style="background-image: url(<?php echo base_url()?>img/home/Overtime.svg);background-color: #3795d3"></div>
					<h4>加班簽核</h4>
				</a>
			</div>
			<div class="col-xs-4">
				<a href="<?php echo base_url()?>index.php/abs_approve">
					<div class="icon" style="background-image: url(<?php echo base_url()?>img/home/Leave.svg);background-color: #ffb533"></div>
					<h4>請假簽核</h4>
				</a>
			</div>
			<div class="col-xs-4">
				<a href="<?php echo base_url()?>index.php/ovt_apply">
					<div class="icon" style="background-image: url(<?php echo base_url()?>img/home/gear.svg);background-color: #21b5bd"></div>
					<h4>加班申請</h4>
				</a>
			</div>
			<div class="col-xs-4">
				<a href="<?php echo base_url()?>index.php/abs_apply">
					<div class="icon" style="background-image: url(<?php echo base_url()?>img/home/sickness.svg);background-color: #d75775"></div>
					<h4>請假申請</h4>
				</a>
			</div>
			<div class="col-xs-4">
				<a href="">
　
					<h4>　</h4>
				</a>
			</div>
			<div class="col-xs-4">
				<a href="">
　
					<h4>　</h4>
				</a>
			</div>
			<div class="col-xs-4">
				<a href="<?php echo base_url()?>index.php/welcome">
　
					<h4>登出</h4>
				</a>
			</div>
		</div>
	</div>
</div>
<script>
	$(function(){
		$(document).on('click', 'header i', function(){
			$('nav').toggleClass('on')
		})
	})
</script>
</body>
</html>