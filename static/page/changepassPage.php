<?php


/*
* 修改密码页面
*/

function changepassPage($error){

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
<title>Change Password</title>
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


	//判断是否有当前密码的反馈并提示
	if(Parm['pass']){
		if(Parm['pass'] == 1){
			showError($('#pass'),'Please enter the current password');
		}else if(Parm['pass'] == 2){
			showError($('#pass'),'The current password error, please try again');
		}
	}
	//将之前提交的当前密码返回到指定输入框中
	$('#pass').find('input').val(Parm['passv']);


	//判断是否有新密码的反馈并提示
	if(Parm['repass']){
		if(Parm['repass'] == 1){
			showError($('#repass'),'Please enter the new password');
		}else if(Parm['repass'] == 2){
			showError($('#repass'),'This value must be 8-16 characters');
		}else if(Parm['repass'] == 3){
			showError($('#repass'),'Security is too low');
		}else if(Parm['repass'] == 4){
			showError($('#repass'),"The new password can't be the same as the current password");
		}
	}
	//将之前提交的新密码返回到指定输入框中
	$('#repass').find('input').val(Parm['repassv']);

	//判断是否有确认密码的反馈并提示
	if(Parm['repass2']){
		if(Parm['repass2'] == 1){
			showError($('#repass2'),'Please Re-enter the new password');
		}else if(Parm['repass2'] == 2){
			showError($('#repass2'),'Two passwords are not the same');
		}
	}
	//将之前提交的确认密码返回到指定输入框中
	$('#repass2').find('input').val(Parm['repass2v']);


	//判断是否有处理成功的反馈并提示
	if(Parm['suc']){
		$('#pass').find('input').val('');
		$('#repass').find('input').val('');
		$('#show-suc').fadeIn().delay(3000).fadeOut(function(){
			location.href="/";
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
						<span id="table-title-txt">Change Password</span>
						<span id="show-suc">Successfully modified! Go home after 3 sec</span>
					</div>
					<form id="table-cell" action="" method="POST">
						
						<div class="rows" id="pass">
							<div class="rowstitle">Current</div>
							<div class="rowsinput"><input type="password" name="pass" class="myinputs" value=""></div>
							<div class="rowsdes">
								<span class="dess">Enter the current password</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>

						<div class="rows" id="repass">
							<div class="rowstitle">New</div>
							<div class="rowsinput"><input type="password" name="repass" class="myinputs" value=""></div>
							<div class="rowsdes">
								<span class="dess">Enter the new password, 8-16 characters</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>

						<div class="rows" id="repass2">
							<div class="rowstitle">Confirm</div>
							<div class="rowsinput"><input type="password" name="repass2" class="myinputs" value=""></div>
							<div class="rowsdes">
								<span class="dess">Re-enter the new password</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>

						<div class="rows" style="padding:10px 21px">
							<div class="rowstitle">&nbsp;</div>
							<div class="rowsinput"><input type="submit" id="submit" value="Save" style="width:209px;"></div>
						</div>

					</form>
				</div>
				<div id="other">
					<div id="other-area">
						<a href="/users">Account Center</a>
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
					<a href="">About</a>
				</div>
			</div>
		</div>
		<!--footer end-->

	</div>

</body>
</html>

<?php
}