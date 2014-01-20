<?php

/*
* 首页页面
*/


function indexPage($error,$mail){

	/*
	* 参数error反馈错误
	* 参数mail传递提交的帐号名
	*/

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Home</title>
<meta name="keywords" content="keywords"/>
<meta name="description" content="description"/>
<link href="/static/css/main.css" rel="stylesheet">
<link href="/static/css/index.css" rel="stylesheet">
<script type="text/javascript" src="/static/js/jquery.js"></script>
<script type="text/javascript">

//声明空对象 Parm
var Parm = {};
//屏幕宽
Parm['windowWidth'] = window.screen.width;
//屏幕高
Parm['windowHeight'] = window.screen.height;
//错误反馈
Parm['error'] = "<?php echo$error; ?>";
//帐号名
Parm['mail'] = "<?php echo$mail; ?>";

$(function(){

	//调整窗口间距高
	var winWidth = $(window).width();
	var winHeight = $(window).height();
	$('#con,#header-area').css({'margin-top':winHeight/2-$('#con').height()*0.7+'px'});
	//setWindowSize(winWidth);

	//提交按钮徘徊
	$('button').hover(
		function(){
			$(this).css({'filter':'alpha(opacity=80)','opacity':'0.8'});
		},
		function(){
			$(this).css({'filter':'alpha(opacity=100)','opacity':'1'});
		}
	);

	$(window).resize(function(){
		$('#con,#header-area').css({'margin-top':$(window).height()/2-$('#con').height()*0.7+'px'});
	});

	//如果设置了帐号名，填充到帐号输入框
	if(Parm['mail']){
		$('#mail').val(Parm['mail']).parent().find('p').hide();
	}

	//判断并反馈错误
	switch(Parm['error']){
		case '1':
		feedback('You forgot to enter your email address');
		break;
		case '2':
		feedback('Your email format error');
		$('#mail').select().parent().find('p').hide();
		break;
		case '3':
		feedback('You forgot to enter your password!');
		$('#mail').select().parent().find('p').hide();
		break;
		case '4':
		feedback('Your email or password were incorrect');
		$('input').select().parent().find('p').hide();
		break;
		case '5':
		feedback('This account has been frozen');
		$('#mail').select().parent().find('p').hide();
		break;
		case '6':
		feedback('This account is not activated');
		$('#mail').select().parent().find('p').hide();
		break;
	}

});

//若输入框发生变化，显示或者隐藏输入提示
function inputChange(t){
	if(t.val()){
			t.parent().find('p').hide();
		}else{
			t.parent().find('p').show();
	}
}

//反馈
function feedback(val){
	$('#showError').append('<p>'+val+'</p>').show();
}

</script>
</head>
<body style="background:#ddd">

	<!--wrap start-->
	<div id="wrap">


		<!--登录窗口 start-->
		<div id="con">

			<div id="logo">
				<div id="logo-text">UCL LECTURE</div>
				<div id="logo-img"><img src="/static/img/logo.png"></div>
			</div>

			<!--登录表单 start-->
			<form id="con-a" action="" method="POST">
				
				<!--LOGO-->
				<div id="logo-h">Log in</div>

				<div id="input">
					<div class="inputs">
						<input type="text" id="mail" name="mail" value="" autocomplete="off" disableautocomplete onkeyup="inputChange($(this))">
						<p class="prompts">Email</p>
					</div>
					<div class="inputs">
						<input type="password" id="pass" value="" name="pass"onkeyup="inputChange($(this))">
						<p class="prompts">Password</p>
					</div>
				</div>

				<div id="showError">
				</div>

				<div id="submit">
					<button onclick="//location.href='/choosegrade';">Log In</button>
					<a href="/register" style="margin-left:11px;">Create an account</a><a href="/forget">Forget Password?</a>
				</div>

			</form>
			<!--登录表单 end-->

			<div id="anonymous"><a href="anonymous">Anonymous</a></div>

		</div>
		<!--登录窗口 end-->

	</div>
	<!--wrap end-->

</body>
</html>

<?php

}