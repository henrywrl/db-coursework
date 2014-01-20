<?php

/*
* 验证过程发生错误页面
*/

//参数error为反馈错误的字符串
function queryverifyPage($error){


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Error</title>
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
					height:200px;
					margin:50px auto 100px;
					background:#fff;
					border: 1px solid #ddd;
					text-align: center;
					padding: 30px 0 20px;
					line-height: 30px;
					letter-spacing: 1px;
				}
				#showsend a{
					color:green;
				}
				#showsend a:hover{
					text-decoration: underline;
				}
				#again,#loading{
					display: none;
				}
				#timer{
					color:#888;
				}
				</style>
				<div id="showsend">
					<div>
						<?php echo$error; ?><br>
						You can <a href="/">go back to main page</a> or <a href="/register">create new account</a>
						<p>If any adout, contract admin《123456@abcd.com》</p>
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
					<a href="">about us</a>
				</div>
			</div>
		</div>
		<!--footer end-->

	</div>

</body>
</html>

<?php
}