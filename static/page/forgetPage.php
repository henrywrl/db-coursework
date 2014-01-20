<?php

/*
* 找回密码页面
*/


function forgetPage($error){


	//声明变量javascript，以拼接JS代码
	$javascript = '';

	//判断参数error是否为数组并单元数大于0，若成立则拼接JS代码
	if(is_array($error) && count($error) > 0){

		//这里拼接的JS代码用于反馈处理结果
		foreach($error as $key => $value){
			$javascript .= 'Parm[\''.$key.'\'] = '.$value.';';
		}

		//这里拼接的JS代码将提交的数据分别返回到各自的输入框中
		foreach ($_POST as $key => $value) {
			$javascript .= 'Parm[\''.$key.'v\'] = "'.$_POST["$key"].'";';
		}

	}


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Forget Password</title>
<meta name="keywords" content="keywords">
<meta name="description" content="description">
<link href="/static/css/main.css" rel="stylesheet">
<link href="/static/css/style.css" rel="stylesheet">
<script type="text/javascript" src="/static/js/jquery.js"></script>
<script type="text/javascript">

//声明空对象 Parm
var Parm = {};

//输出拼接的JS代码
<?php echo$javascript; ?>


$(function(){

	//判断是否有帐号的反馈并提示
	if(Parm['mail']){
		if(Parm['mail'] == 1){
			showError($('#mail'),'Please enter your E-mail');
		}else if(Parm['mail'] == 2){
			showError($('#mail'),'E-mail format error');
		}else if(Parm['mail'] == 3){
			showError($('#mail'),'This E-mail is not registered');
		}else if(Parm['mail'] == 4){
			showError($('#mail'),'The e-mail account has been frozen associated');
		}else if(Parm['mail'] == 5){
			showError($('#mail'),'This account is not active');
		}else if(Parm['mail'] == 6){
			$('#show-suc span').html('<a href="http://'+Parm['suc']+'">Login Your E-mail</a>');
			$('#show-suc').fadeIn();
		}
	}
	//将之前提交的邮箱返回到指定输入框中
	$('#mail').find('input').val(Parm['mailv']);


	//判断是否有密码的反馈并提示
	if(Parm['code']){
		if(Parm['code'] == 1){
			showError($('#code'),'Please enter verification');
		}else if(Parm['code'] == 2){		
			showError($('#code'),'verification error');
		}
	}

});

//显示反馈
function showError(ele,node){
	ele.find('.errors').css({'display':'block'}).text(node);
	ele.find('.dess').hide();
}

//这里是为了防止用户连续点击提交
function checkForm(){
	$('#submit').attr('disabled','disabled').val('Loading...');
	return true;
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

		<!--myarea start-->
		<div id="area">
			<br>
			<div id="myarea">
				<div id="table">
					<div id="table-title">
						<span id="table-title-txt">Forgot Password</span>
					</div>
					<div id="show-suc">Password Reset! Please <span></span> Get the new Password, if you does't receive the mail, Please try again or contact Admin</div>
					<form class="table-cell" action="" method="POST" onsubmit="return checkForm()" style="padding:10px 0">
						
						<div class="rows" id="mail">
							<div class="rowstitle">E-mail</div>
							<div class="rowsinput"><input type="text" name="mail" class="myinputs" value=""></div>
							<div class="rowsdes">
								<span class="dess">Enter Your E-mail</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>

						<div class="rows" style="padding:0px 21px 0">
							<div class="rowstitle">&nbsp;</div>
							<div class="rowsinput"><img src="/verify" width="224" onclick="this.src=this.src+'?'+new Date().getTime()"></div>
							<div class="rowsdes"></div>
						</div>

						<div class="rows" id="code">
							<div class="rowstitle">Verification</div>
							<div class="rowsinput"><input type="text" name="code" class="myinputs" value=""></div>
							<div class="rowsdes">
								<span class="dess">Enter Verification</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>

						<div class="rows" style="padding:10px 21px">
							<div class="rowstitle">&nbsp;</div>
							<div class="rowsinput"><input type="submit" id="submit" value="Submit" style="width:226px;"></div>
						</div>

					</form>
				</div>
				<div id="other">
					<div id="other-area">
						<a href="/">Homepage</a>
					</div>
				</div>
			</div>
			<br><br><br><br><br>
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