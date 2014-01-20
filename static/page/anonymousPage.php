<?php

/*
* 匿名者动向页面
*/


function anonymousPage(){


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
						<span id="table-title-txt">Anonymous can only answer public questions. Please select grades.</span>
					</div>
					<!---->
					<div class="table-cell">
						<div class="inlinebox">
							<div class="inlinetitle">Exam</div>
							<div class="inlinearea">
								<a href="/publicsubject/1">Grade 1</a>
								<a href="/publicsubject/2">Grade 2</a>
								<a href="/publicsubject/3">Grade 3</a>
								<a href="/publicsubject/4">Grade 4</a>
							</div>
						</div>
					</div>
				</div>
				<div id="other">
					<div id="other-area">
						<a href="/">HomePage</a>
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