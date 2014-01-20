<?php

/*
* 登录界面
*/

function loginPage($feedback){

	$postdata = '';
	if(isset($_POST)){
		foreach($_POST as $key => $val){
			$postdata .= 'Parm[\''.$key.'\'] = "'.$val.'";';
		}
	}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Login</title>
<style>
*{
	margin: 0;
	padding: 0;
}

body{
	font-family: arial;
	background: #f0f2f4;
}

a{
	text-decoration: none;
}

ul,li{
	list-style: none;
}
#head{
	width:100%;
}
#head div{
	width:800px;
	margin:0 auto;
	color: #d46418;
}
#head p{
	padding:110px 0 20px;
	font-size: 25px;
	font-weight: bold;
}
#main{
	background: #fff;
	border-top:1px solid #ddd;
	border-bottom:1px solid #ddd;
}
#main-area{
	width:800px;
	margin:0 auto;
	display: table;
	border:1px solid #ccc;
}

input{
	padding:10px 8px 9px;
	font-size: 18px;
	display: block;
	float: left;
	border:0 none; 
	border-right:1px solid #ddd;
	color:#777;
	font-family: arial;
}

#user,#pass,#code{
	width:155px;
}

#pass{
	background-image:url(static/img/pd.png);
	background-repeat: no-repeat;
}

#img{
	float: left;
	border-right: 1px solid #ddd;
}

#refresh{
	display: block;
	float: left;
	padding:13px 0 12px;
	text-align: center;
	width:103px;
	color: blue;
	font-size: 14px;
}

#login{
	width:200px;
	padding: 20px 0 16px;
	text-align: center;
	background: #eaeaea;
	display: block;
	border:1px solid #d8d8d8;
	margin:0 auto;
	font-weight: bold;
	color:#4488f6;
	font-size: 16px
}

#login:hover{
	background: #eee;
}

#show{
	color:red;
	text-align: center;
	padding:10px 0;
	display: none;
}

</style>
<script src="/static/js/jquery.js"></script>
<script>
//声明变量
var
Parm = {},
userEmpty = 'Username',
codeEmpty = 'Captcha',
userEle,
passEle,
codeEle,
flag = false,
feedback = "<?php echo $feedback; ?>";
<?php echo$postdata; ?>

$(function(){
	
	userEle = $('#user');
	passEle = $('#pass');
	codeEle = $('#code');
	showEle = $('#show');
	userEle.val(userEmpty);
	codeEle.val(codeEmpty);

	if(Parm['user'])userEle.val(Parm['user']);

	//显示反馈
	switch(feedback){
		case '1':
		userEle.focus();
		break;
		case '2':
		userEle.focus();
		break;
		case '3':
		codeEle.focus();
		break;
		case '4':
		showEle.html('<p>Captcha code error</p>').slideDown().delay(2000).slideUp();
		break;
		case '5':
		showEle.html('<p>Username or password were incorrect</p>').slideDown().delay(2000).slideUp();
		break;
		default:;
	}

	//点击刷新验证码
	$('#refresh').click(function(){
		RefreshCode();
	});

	//登录名输入框的焦点操作
	userEle.focus(function(){
		if(userEle.val()==userEmpty)userEle.val('');
	}).blur(function(){
		if(!userEle.val())userEle.val(userEmpty);
	});

	//密码输入框的焦点操作
	passEle.focus(function(){
		passEle.css({"background":"#fff"});
	}).blur(function(){
		if(!passEle.val())passEle.css({"background-image":"url(static/img/pd.png)","background-repeat":"no-repeat"});
	});

	//验证码输入框的焦点操作
	codeEle.focus(function(){
		if(codeEle.val()==codeEmpty)codeEle.val('');
	}).blur(function(){
		if(!codeEle.val())codeEle.val(codeEmpty);
	});


});

//执行登录
function checkform(){

		//阻止连续点击
		if(flag)return false;
		//声明变量
		var user = userEle.val(),pass = passEle.val(),code = codeEle.val(),showEle = $('#show'), ths = $(this);
		//检测登录名
		if(user == userEmpty || !user){
			userEle.focus();
			return false;
		}
		//检测密码
		if(!pass){
			passEle.focus();
			return false;
		}
		//检测验证码
		if(code == codeEmpty || !code){
			codeEle.focus();
			return false;
		}
		//标记为真
		flag = true;
}

//刷新验证吗
function RefreshCode(){
	var img = $('#img');
	img.attr("src",img.attr("src")+'?'+Math.random());
}

</script>
</head>
<body>

	<!--head-->
	<div id="head">
		<div>
			<p>Admin Login</p>
		</div>
	</div>

	<!--show-->
	<div id="show"></div>

	<!--main-->
	<form id="main" action="" onsubmit="return checkform()" method="POST">
		<br><br>
		<div id="main-area">
			<input type="text" id="user" name="user">
			<input type="password" id="pass" name="pass">
			<input type="text" id="code" name="code">
			<img src="/verify" id="img">
			<a href="javascript:;" id="refresh">Refresh</a>
		</div><br>
		<br>
		<button id="login">Login</button>
		<br>
	</form>

</body>
</html>

<?php
}