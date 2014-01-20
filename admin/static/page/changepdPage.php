<?php

function changepdPage($error){

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
<link href="/static/css/style.css" rel="stylesheet">
<style>
*{
	margin:0;
	padding:0;
}
body{
	font-family: arial;
}
a{
	text-decoration: none;
	color:#444;
}
a:hover{
	text-decoration: underline;
	color:#d33;
}
#main{
	display: table;
	width: 100%;
	position: relative;
}
#head{
	background:#444;
	display: table;
	width:100%;
	padding:10px 0 8px;
	text-align: left;
	color:#ddd;
	font-size:13px;
}
#head span{
	font-size: 14px;
	color: #fff;
	width:50px;
}
#head a{
	font-size: 14px;
	width:60px;
	color:#fff;
}
#iarea{
	margin:20px;
	background:#eee;
	border:1px solid #ddd;
	padding:20px 0;
}
button{
	padding:5px;
	margin-left: 80px;
}
</style>
<script type="text/javascript" src="/static/js/jquery.js"></script>
<script>

//声明空对象 Parm
var Parm = {};
//输出拼接的JS代码
<?php echo$javascript; ?>

$(function(){


	//判断是否有当前密码的反馈并提示
	if(Parm['old']){
		if(Parm['pass'] == 1){
			showError($('#old'),'请输入当前密码');
		}else if(Parm['old'] == 2){
			showError($('#old'),'当前密码输入错误，请重新输入');
		}
	}
	//将之前提交的当前密码返回到指定输入框中
	$('#old').find('input').val(Parm['oldv']);


	//判断是否有新密码的反馈并提示
	if(Parm['pass']){
		if(Parm['pass'] == 1){
			showError($('#pass'),'请新的密码');
		}
	}
	//将之前提交的新密码返回到指定输入框中
	$('#pass').find('input').val(Parm['repassv']);

	//判断是否有确认密码的反馈并提示
	if(Parm['repass']){
		if(Parm['repass'] == 1){
			showError($('#repass'),'请确认密码');
		}else if(Parm['repass'] == 2){
			showError($('#repass'),'密码不一');
		}else if(Parm['repass'] == 3){
			showError($('#repass'),'新密码不能与当前密码相同');
		}
	}
	//将之前提交的确认密码返回到指定输入框中
	$('#repass').find('input').val(Parm['repassv']);


	//判断是否有处理成功的反馈并提示
	if(Parm['suc']){
		$('#iarea').find('input').val('');
		$('#show-suc').fadeIn().delay(3000).fadeOut(function(){
			window.parent.out(0);
		});
	}

});

//显示反馈
function showError(ele,node){
	ele.find('.errors').css({'display':'block'}).text(node);
	ele.find('.dess').hide();
}

</script>
</head>
<body>

	<!--main-->
	<div id="main">

		<!--nav-->
		<div id="head">
			<span>&nbsp;&nbsp;Background：</span>
			<a href="info">Home</a>
			&gt;
			<a href="changepd">Change Password</a>
		</div>

		<div id="iarea">
			<form action="" method="POST">
				<div class="iarea">
					<div class="rows" id="old">
							<div class="rowstitle">Current</div>
							<div class="rowsinput"><input type="password" name="old" class="myinputs" value=""></div>
							<div class="rowsdes">
								<span class="dess">Enter your current password</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>

						<div class="rows" id="pass">
							<div class="rowstitle">New</div>
							<div class="rowsinput"><input type="password" name="pass" class="myinputs" value=""></div>
							<div class="rowsdes">
								<span class="dess">8-16 characters</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>

						<div class="rows" id="repass">
							<div class="rowstitle">Confirm</div>
							<div class="rowsinput"><input type="password" name="repass" class="myinputs" value=""></div>
							<div class="rowsdes">
								<span class="dess">Repeat enter</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>
						<div class="rows">
							<button>Submit</button>
						</div>
						<div class="rows" id="show-suc">
							Success! Go to the login page after 3 sec.
						</div>
				</div>
			</form>
		</div>

	</div>

</body>
</html>
<?php
}