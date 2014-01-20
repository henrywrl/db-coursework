<?php

/*
* 选择动向页面
*/


function choosePage($name){


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Choose movements</title>
<meta name="keywords" content="keywords">
<meta name="description" content="description">
<link href="/static/css/main.css" rel="stylesheet">
<link href="/static/css/style.css" rel="stylesheet">
<script type="text/javascript" src="/static/js/jquery.js"></script>
<style>
.inlinearea a{
	margin: 0 20px;
	letter-spacing: 1px;
	color:green;
}
.inlinearea a:hover{
	color:#d33;
	text-decoration: underline;
}
</style>
</head>
<body>

	<!--main start-->
	<div class="main">

		<!--header start-->
		<div class="header" style="background:#000">
			<div class="areas">
				<div id="header-area">
					<div id="logo">
						<a href="/" title="home" style="font-size:0"><img src="/static/img/logo.png" width="150" alt="logo"></a>
					</div>
				</div>
			</div>
		</div>
		<!--header end-->

		<!--myarea start-->
		<div id="area">
			<br>
			<div id="myarea">
				<div id="table">
					<div id="table-title">
						<span id="table-title-txt">Choose your movements</span>
					</div>
					<!---->
					<div class="table-cell">
						<div class="inlinebox">
							<div class="inlinetitle">Exam</div>
							<div class="inlinearea">
								<a href="/choosesubject/exam/1">Grade 1</a>
								<a href="/choosesubject/exam/2">Grade 2</a>
								<a href="/choosesubject/exam/3">Grade 3</a>
								<a href="/choosesubject/exam/4">Grade 4</a>
							</div>
						</div>
					</div>
					<!---->
					<div class="table-cell">
						<div class="inlinebox">
							<div class="inlinetitle">Review</div>
							<div class="inlinearea">
								<a href="/choosesubject/review/1">Grade 1</a>
								<a href="/choosesubject/review/2">Grade 2</a>
								<a href="/choosesubject/review/3">Grade 3</a>
								<a href="/choosesubject/review/4">Grade 4</a>
							</div>
						</div>
					</div>
					<!---->
					<div class="table-cell">
						<div class="inlinebox">
							<div class="inlinetitle">Account</div>
							<div class="inlinearea"><a href="/users">My Account</a></div>
						</div>
					</div>
				</div>
				<div id="other">
					<div id="other-area">
						<a href="/users"><?php echo$name; ?></a>
						|
						<a href="/logout">Log out</a>
					</div>
				</div>
			</div>
		</div>
		<!--myarea end-->

		<!--footer start-->
		<div id="footer">
			<div id="footera">
				<div id="mylinks">
					<span>© all rights reserved</span>
					<a href="#">About</a>
				</div>
			</div>
		</div>
		<!--footer end-->

	</div>

</body>
</html>

<?php
}