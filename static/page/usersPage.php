<?php

/*
* 用户中心页面
*/

//fileArr(数组)用户资料，error反馈错误
function usersPage($fileArr,$error){

	//生成年龄选择的option
	$ageHtml = '<option>Please select</option>';
	for($i=12;$i<=80;$i++){
		$ageHtml.='<option value="'.$i.'">'.$i.'</option>';
	}

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

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Account Center</title>
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

	//输入框获得焦点和失去焦点的一些提示操作
	$('.myinputs').focus(function(){
		var par = $(this).parent().parent();
		par.find('.dess').show();
		par.find('.errors').hide();
	}).blur(function(){
		if(!$(this).val()){
			var par = $(this).parent().parent();
			var hass = $(this).attr('s'),txt = 'Enter ';
			if(hass)txt = 'select ';
			par.find('.dess').hide();
			par.find('.errors').css({'display':'block'}).text('Please '+txt+par.find('.rowstitle').text());
		}
	});

	//判断是否有用户名称的反馈并提示
	if(Parm['user']){
		if(Parm['user'] == 1){
			showError($('#user'),'This value can not be empty');
		}else if(Parm['user'] == 2){
			showError($('#user'),'This value must be 2-16 characters');
		}
	}

	//判断是否有年龄的反馈并提示
	if(Parm['age']){
		if(Parm['age'] == 1){
			showError($('#age'),'Please select the age');
		}
	}

	//判断是否有性别的反馈并提示
	if(Parm['gender']){
		if(Parm['gender'] == 1){
			showError($('#gender'),'Please select the gender');
		}
	}

	//判断是否有年级的反馈并提示
	if(Parm['grade']){
		if(Parm['grade'] == 1){
			showError($('#grade'),'Please select the grade');
		}
	}

	//判断是否有操作成功的反馈并提示
	if(Parm['suc']){
		$('#show-suc').fadeIn().delay(3000).fadeOut();
	}

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
			<div id="myarea">
				<div id="table">
					<div id="table-title">
						<span id="table-title-txt">Account Center</span>
						<span id="show-suc">Successfully saved!</span>
					</div>
					<form class="table-cell" action="" method="POST" style="padding:10px 0">
						<div class="rows" id="user">
							<div class="rowstitle">Username</div>
							<div class="rowsinput">
								<input type="text" name="user" class="myinputs" value="<?php echo$fileArr[1]; ?>" maxlength="16">
							</div>
							<div class="rowsdes">
								<span class="dess">2-16 characters</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>
						<div class="rows" id="pass">
							<div class="rowstitle">Password</div>
							<div class="rowsinput"><input disabled="disabled" type="password" name="pass" class="myinputs" value="******"></div>
							<div class="rowsdes">
								<a href="/changepass">Change Password</a>
							</div>
						</div>
						<div class="rows" id="mail">
							<div class="rowstitle">E-mail</div>
							<div class="rowsinput"><input type="text" name="mail" class="myinputs" value="<?php echo$fileArr[0]; ?>" disabled="disabled"></div>
							<div class="rowsdes">
							</div>
						</div>
						<div class="rows" id="age">
							<div class="rowstitle">Age</div>
							<div class="rowsinput">
								<input type="text" name="age" class="myinputs" value="<?php echo$fileArr[2]; ?>" s="1">
								<select onpropertychange="setData($(this))" onchange="setData($(this))">
									<?php echo$ageHtml; ?>
								</select>
							</div>
							<div class="rowsdes">
								<span class="dess">Click to select your age</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>
						<div class="rows" id="gender">
							<div class="rowstitle">Gender</div>
							<div class="rowsinput">
								<input type="text" name="gender" class="myinputs" s="1" value="<?php echo$fileArr[3]; ?>">
								<select onpropertychange="setData($(this))" onchange="setData($(this))">
									<option>Please select</option>
									<option>Male</option>
									<option>Female</option>
								</select>
							</div>
							<div class="rowsdes">
								<span class="dess">Click to select your gender</span>
								<span class="success"></span>
								<span class="errors"></span>
							</div>
						</div>
						<div class="rows" id="grade">
							<div class="rowstitle">Grade</div>
							<div class="rowsinput">
								<input type="text" name="grade" class="myinputs" s="1" value="<?php echo$fileArr[4]; ?>">
								<select onpropertychange="setData($(this))" onchange="setData($(this))">
									<option>Please select</option>
									<option>One</option>
									<option>Two</option>
									<option>Three</option>
									<option>Four</option>
								</select>
							</div>
							<div class="rowsdes">
								<span class="dess">Click to select your grades</span>
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
						<a href="/choose">Choose movements</a>
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