<?php

/*
* 注册页面
*/

function registerPage($error){

	//声明变量javascript，以拼接JS代码
	$javascript = '';

	//判断参数error是否为数组并单元数大于0，若成立则拼接JS代码
	if(is_array($error)&&count($error) > 0){
		
		//这里拼接的JS代码用于反馈处理结果
		foreach($error as $key => $value){
			$javascript .= 'Parm[\''.$key.'\'] = '.$value.';';
		}
		
		//这里拼接的JS代码将提交的数据分别返回到各自的输入框中
		foreach ($_POST as $key => $value) {
			$javascript .= 'Parm[\''.$key.'v\'] = "'.$_POST["$key"].'";';
		}

	}

	//生成年龄选择的option
	$ageHtml = '<option>Please select</option>';
	for($i=12;$i<=80;$i++){$ageHtml.='<option value="'.$i.'">'.$i.'</option>';}


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register</title>
<meta name="keyword" content="keyword">
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

	//输入框获得焦点和失去焦点的一些提示操作
	$('.myinputs').focus(function(){
		var par = $(this).parent().parent();
		par.find('.dess').show();
		par.find('.errors').hide();
	}).blur(function(){
		if(!$(this).val()){
			var par = $(this).parent().parent();
			var hass = $(this).attr('s'),txt = 'Input';
			if(hass)txt = 'Click to select';
			par.find('.dess').hide();
			par.find('.errors').css({'display':'block'}).text('Please'+txt+par.find('.rowstitle').text());
		}
	});

	//判断是否有用户名称的反馈并提示
	if(Parm['user']){
		if(Parm['user'] == 1){
			showError($('#user'),'Please input account name');
		}else if(Parm['user'] == 2){
			showError($('#user'),'Account must between 2-6 characters');
		}
	}
	$('#user').find('input').val(Parm['userv']);

	//判断是否有密码的反馈并提示
	if(Parm['pass']){
		if(Parm['pass'] == 1){
			showError($('#pass'),'Please input password');
		}else if(Parm['pass'] == 2){
			showError($('#pass'),'Password must between 8-16 characters');
		}else if(Parm['pass'] == 3){
			showError($('#pass'),'Password strength is too low');
		}
	}
	$('#pass').find('input').val(Parm['passv']);

	//判断是否有确认密码的反馈并提示
	if(Parm['repass']){
		if(Parm['repass'] == 1){
			showError($('#repass'),'Please confirm password');
		}else if(Parm['repass'] == 2){
			showError($('#repass'),'The password is different');
		}
	}
	$('#repass').find('input').val(Parm['repassv']);

	//判断是否有电邮地址的反馈并提示
	if(Parm['mail']){
		if(Parm['mail'] == 1){
			showError($('#mail'),'Please input Email address');
		}else if(Parm['mail'] == 2){
			showError($('#mail'),'Email address format error');
		}else if(Parm['mail'] == 3){
			showError($('#mail'),'This email address has been registered, please choose other address');
		}
	}
	$('#mail').find('input').val(Parm['mailv']);

	//判断是否有年龄的反馈并提示
	if(Parm['age']){
		if(Parm['age'] == 1){
			showError($('#age'),'Click to select age');
		}
	}
	$('#age').find('input').val(Parm['agev']);

	//判断是否有性别的反馈并提示
	if(Parm['gender']){
		if(Parm['gender'] == 1){
			showError($('#gender'),'Click to select gender');
		}
	}
	$('#gender').find('input').val(Parm['genderv']);

	//判断是否有年级的反馈并提示
	if(Parm['grade']){
		if(Parm['grade'] == 1){
			showError($('#grade'),'Click to select grade');
		}
	}
	$('#grade').find('input').val(Parm['gradev']);

	//判断是否有验证码的反馈并提示
	if(Parm['code']){
		if(Parm['code'] == 1){
			showError($('#code'),'Please Enter Verify Code');
		}else if(Parm['code'] == 2){		
			showError($('#code'),'Verify Code wrong');
		}
	}
	$('#code').find('input').val('');

	//点击select标签时隐藏错误反馈，显示操作提示
	$('select').click(function(){
		var par = $(this).parent().parent();
		par.find('.errors').hide();
		par.find('.dess').show();
	});

});

//显示反馈
function showError(ele,node){
	ele.find('.errors').css({'display':'block'}).text(node);
	ele.find('.dess').hide();
}

//点击选择项，将选择的值注入到指定的输入框
function setData(t){
	var v = t.val();
	if(v=='Please select')return false;
	t.parent().find('input').val(v);
}

//这里是为了防止用户连续点击提交
function checkForm(){
	$('#submit').attr('disabled','disabled').val('subminting...');
	return true;
}

</script>
</head>
<body>

	<!--main start-->
	<div class="main">

		<!--header start-->
		<div class="header">
			<div class="areas">
				<div id="header-area">
					<div id="logo">
						<a href="/" title="home">LOGO</a>
					</div>
				</div>
			</div>
		</div>
		<!--header end-->

		<!--myarea start-->
		<div id="area">
			<div id="myarea">
				<div id="table">
					<div id="table-title">
						<span id="table-title-txt">Create new account</span>
					</div>
					<form id="table-cell" action="" method="POST" onsubmit="return checkForm()">
						<div class="rows" id="user">
							<div class="rowstitle">Account name</div>
							<div class="rowsinput"><input type="text" name="user" class="myinputs"></div>
							<div class="rowsdes">
								<span class="dess">2-16 characters</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>
						<div class="rows" id="pass">
							<div class="rowstitle">Password</div>
							<div class="rowsinput"><input type="password" name="pass" class="myinputs"></div>
							<div class="rowsdes">
								<span class="dess">8-16 characters</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>
						<div class="rows" id="repass">
							<div class="rowstitle">Confirm password</div>
							<div class="rowsinput"><input type="password" name="repass" class="myinputs"></div>
							<div class="rowsdes">
								<span class="dess">Repeat password</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>
						<div class="rows" id="mail">
							<div class="rowstitle">Email address</div>
							<div class="rowsinput"><input type="text" name="mail" class="myinputs"></div>
							<div class="rowsdes">
								<span class="dess">Input your Email address, use for login and find password</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>
						<div class="rows" id="age">
							<div class="rowstitle">age</div>
							<div class="rowsinput">
								<input type="text" name="age" class="myinputs" s="1">
								<select onpropertychange="setData($(this))" onchange="setData($(this))">
									<?php echo$ageHtml; ?>
								</select>
							</div>
							<div class="rowsdes">
								<span class="dess">Select your age</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>
						<div class="rows" id="gender">
							<div class="rowstitle">Gender</div>
							<div class="rowsinput">
								<input type="text" name="gender" class="myinputs" s="1">
								<select onpropertychange="setData($(this))" onchange="setData($(this))">
									<option>Please select</option>
									<option value="male">male</option>
									<option value="female">female</option>
								</select>
							</div>
							<div class="rowsdes">
								<span class="dess">Select your gender</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>
						<div class="rows" id="grade">
							<div class="rowstitle">Grade</div>
							<div class="rowsinput">
								<input type="text" name="grade" class="myinputs" s="1">
								<select onpropertychange="setData($(this))" onchange="setData($(this))">
									<option>Please select</option>
									<option value="first year">first year</option>
									<option value="second year">second year</option>
									<option value="third year">third year</option>
									<option value="fourth year">fourth year</option>
								</select>
							</div>
							<div class="rowsdes">
								<span class="dess">Select your grade</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>
						<div class="rows" style="padding:5px 21px 0">
							<div class="rowstitle">&nbsp;</div>
							<div class="rowsinput"><img src="/verify" width="224" onclick="this.src=this.src+'?'+new Date().getTime()"></div>
							<div class="rowsdes"></div>
						</div>
						<div class="rows" style="padding:10px 21px" id="code">
							<div class="rowstitle">verify code</div>
							<div class="rowsinput"><input type="text" name="code" class="myinputs"></div>
							<div class="rowsdes">
								<span class="dess">Input verify code, click to change image</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>
						<div class="rows" style="padding:10px 21px">
							<div class="rowstitle">&nbsp;</div>
							<div class="rowsinput"><input type="submit" id="submit" value="Agree service and register"></div>
							<div class="rowsdes">
								<span class="dess"><a href="/" id="read">Read our terms of service</a></span>
							</div>
						</div>
					</form>
				</div>
				<div id="other">
					<div id="other-area">
							Have account？<a href="/">click here to login</a>
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