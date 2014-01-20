<?php

/*
* 发送注册激活邮件页面
*/

//参数emailGo为邮箱登录页面地址url
function sendRegiPage($emailGo){

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>发送注册激活邮件</title>
<meta name="keywords" content="keywords">
<meta name="description" content="description">
<link href="/static/css/main.css" rel="stylesheet">
<link href="/static/css/style.css" rel="stylesheet">
<script type="text/javascript" src="/static/js/jquery.js"></script>
<script type="text/javascript">
//声明空对象 Parm
var Parm = {};
//30秒后可重新发送
Parm['time'] = 30;
//计时器
Parm['sl'];
//阻止连续点击标记
Parm['flag'] = false;

$(function(){

	//载入页面即开始计时，单位秒
	Parm['sl'] = setInterval(timer,1000);

	//点击重新发送激活邮件
	$('#again').click(function(){
		//标记为真即停止下行
		if(Parm['flag'])return false;
		//标记为真
		Parm['flag'] = true;
		//提示加载
		$('#loading').show();
		//Ajax提交
		$.ajax({
			type:"POST",
			url:"/querysendmail",
			data:{"user":"<?php echo $_POST['user']; ?>"},
			success:function(response){
				//若返回0代表成功发送
				if(response == '0'){
					//隐藏加载、点击按钮
					$('#loading,#again').hide();
					//显示计时
					$('#timer').show();
					//标记为假
					Parm['flag'] = false;
					//重新计时，单位秒
					Parm['sl'] = setInterval(timer,1000);
				}else{
					alert('发生错误'+response);
				}
			}
		});
	});

});

//执行计时，递减
function timer(){
	//若值大于0，则继续计时
	if(Parm['time'] > 0){
		//显示计时
		$('#timer b').text(Parm['time']);
	}else{
		//停止计时
		clearInterval(Parm['sl']);
		//恢复原始计时值
		Parm['time'] = 30;
		//显示点击发送按钮
		$('#again').show();
		//隐藏计时
		$('#timer').hide();
		//恢复原始计时值
		$('#timer b').text('30');
	}
	//递减
	Parm['time']--;
}

</script>
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

		<!--area start-->
		<div id="area">
			<div id="myarea">
				<style>
				#showsend{
					width:1000px;
					height:200px;
					margin:50px auto 250px;
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
					<div style="width:500px;margin:0 0 0 250px">
						<?php echo' 学员('.$_POST['user'].')'; ?>
						您好！<br>
						<div style="text-align:left">
							您的资料已记录，请 <a href="http://<?php echo$emailGo; ?>">登录您的邮箱</a> 获取帐户激活邮件并激活您的帐号，若没有收到邮件，请执行以下步骤<br>
							1, <span id="timer"><b>30</b>秒后可重新发送邮件</span><a href="javascript:;" id="again">重新发送一次</a><p id="loading"><img src="/static/img/loading.gif"></p><br>
							2, 邮件可能被误放在垃圾箱<br>
							3, 或者联系管理员《123456@abcd.com》<br>
							<a href="/">回到首页</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--area end-->

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