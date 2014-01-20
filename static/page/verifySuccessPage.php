<?php

/*
* 验证成功页面
*/

//参数com为学员的姓名
function verifySuccessPage($name){


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>验证成功！</title>
<meta name="keywords" content="keywords">
<meta name="description" content="description">
<link href="/static/css/main.css" rel="stylesheet">
<link href="/static/css/style.css" rel="stylesheet">

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
				<style>
				#showsend{
					width:1000px;
					margin:50px auto 100px;
					background:#fff;
					border: 1px solid #ddd;
					text-align: center;
					padding: 40px 0 40px;
					line-height: 30px;
					letter-spacing: 1px;
				}
				#showsend a{
					color:green;
				}
				#showsend a:hover{
					text-decoration: underline;
				}
				</style>
				<div id="showsend">
					<div>
						<p style="color:green;font-size:17px;">学员(<?php echo$name; ?>)，您好，验证已成功！</p>
						您可 <a href="/">回到首页</a> 登录您的帐户
						<p>若有疑问请联系管理员《123456@abcd.com》</p>
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
					<a href="">关于我们</a>
				</div>
			</div>
		</div>
		<!--footer end-->

	</div>

</body>
</html>

<?php
}