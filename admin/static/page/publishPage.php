<?php

/*
*  发布试题页面
*/

//subjectArr: 科目列表数组，insertSuc：发布成功
function publishPage($subjectArr,$insertSuc){

	//年级名字
	$gradeTextArr = array('','One','Two','Three','Four');

	//初始化
	$html = '';
	$options = array(0,0,0,0);

	//拼接各年级的Option
	foreach($subjectArr as $po){
		switch($po[2]){
			case 1:
			$options[0] .= '<option value='.$po[0].'>'.$po[1].'</option>';
			break;
			case 2:
			$options[1] .= '<option value='.$po[0].'>'.$po[1].'</option>';
			break;
			case 3:
			$options[2] .= '<option value='.$po[0].'>'.$po[1].'</option>';
			break;
			case 4:
			$options[3] .= '<option value='.$po[0].'>'.$po[1].'</option>';
			break;
			default:exit;
		}
	}

	for($i = 0; $i < 4; $i++){
		//若该年级没有科目，option值为空
		if(!isset($options[$i]))$options[$i] = '';
		$html[] = '<div class="selector">
						<div class="selector-a">
							<label><span>Grade '.$gradeTextArr[$i+1].'</span></label>
							<select onpropertychange="chooseCate($(this))" onchange="chooseCate($(this))">
								<option value="" disabled="disabled" selected="selected">Please Select</option>
								'.$options[$i].'
							</select>
						</div>
					</div>';
	}

	//拼接各年级的selector
	$html = $html[0].$html[1].$html[2].$html[3];

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
*{
	margin:0;
	padding:0;
}
body{
	font-family: arial;
	overflow:hidden;
}
a{
	text-decoration: none;
}
a:hover{
	text-decoration: underline;
}
#main{
	display: table;
	width: 100%;
	height: 980px;
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
#area{
	margin:0px 20px 10px;
}

.list{
	padding:25px 0 25px;
	border-bottom: 1px dashed #e7e7e7;
}
.titles{
	font-weight: bold;
	font-size:13px;
	padding:0 0 6px;
}
.titles span{
	font-weight: normal;
	letter-spacing: 0;
	color:#555;
}
.grades{
	width:100%;
	display: table;
}

.selector{
	width:146px;
	float: left;
	background: #ddd;
	margin:0 5px 0 0;
}

.selector-a{
	display: table;
	width: 100%;
	position: relative;
}

.grades label{
	display: block;
	font-size:13px;
	color:green;
	padding:7px 10px 6px;
	background:#f3f3f3;
	border:1px solid #ddd;
	text-align: center;
	position: relative;
	z-index: 1;
}

.grades select{
	width:100%;
	position: absolute;
	z-index: 2;
	top:0;
	left:0;
	line-height: 29px;
	height:29px;
	opacity: 0;
}

textarea{
	width:600px;
	height:100px;
	padding:5px;
	font-size: 14px;
	font-family: arial;
	line-height: 23px;
	letter-spacing: 1px;
	border:1px solid #ddd;
}

.an-items{
	width:600px;
	padding:6px 0
}

.an-items b{
	margin:0 13px 0 10px;
}

.inputs{
	width:600px;
	padding:7px 5px 6px;
	font-size:13px;
	font-family: arial;
	border:1px solid #ddd;
}
.operaanswer{
	padding:5px 36px;
}
.operaanswer a{
	margin:0 10px 0 0;
	font-size:13px;
	font-weight: bold;
}
#addanswer{color:green;}
#delanswer{
	color:red;
	display: none;
}
button{
	padding:5px;
}
#emptySubject{
	color:red;
	margin: 0 20px;
	display: none;
}

label{
	font-size:13px;
	color:#555;
}

.timelimit input{
	width:136px;
}

</style>
<script src="/static/js/jquery.js"></script>
<script>

var flag = false,
cate;



$(function(){

	//回到顶部
	window.parent.getTop();

	//输入域，文本域输入
	$('textarea,.inputs').keyup(function(){
		var limit;
		//检查类型，输入域允许输入长度是 160 字符，文本域允许输入长度是 600 字符
		if($(this)[0].tagName == 'INPUT')limit = 160;else limit = 600;
		//检查输入长度
		checkTextInput($(this),limit);
	});

	//输入域，文本域徘徊
	$('textarea,input').hover(
		function(){
			$(this).css({'border':'1px solid #bbb'});
		},
		function(){
			$(this).css({'border':'1px solid #ddd'});
		}
	);

	//添加答案
	$('#addanswer').click(function(){

		var count = $('.an-items').size(),//统计当前答案量
		sort;//排序号
		switch(count){
			//定义排序号
			case 3: sort = 'D'; break;
			case 4: sort = 'E'; break;
			case 5: sort = 'F'; break;
		}
		//定义新增的节点
		var ele = '<div class="an-items a'+(count+1)+'"><b>'+sort+'</b><label><input type="radio"> This is correct answer</label><input type="text" name="parm[]" class="inputs" autocomplete="off" disableautocomplete ></div>';
		//若答案量在4项及以上，显示删除按钮
		if(count > 2)$('#delanswer').show();
		//若答案量在6项，隐藏添加按钮
		if(count > 4)$('#addanswer').hide();
		//插入节点
		$('.answers').append(ele);
		//加载选择正确答案
		chooseCorrectAnswer();

	});

	
	//删除答案
	$('#delanswer').click(function(){
		var count = $('.an-items').size(),//统计当前答案量
		lastItem = $('.an-items').last();//获得最后一个答案，从最后一个起删除
		//若答案量少于4项，隐藏删除按钮
		if(count <= 4)$('#delanswer').hide();
		//若答案量少于6项，显示添加按钮
		if(count <= 6)$('#addanswer').show();
		//检查删除对象是否存在数据输入，提示确认
		if(lastItem.find('input').last().val()){
			if(confirm('The answer options have content, confirm removal?')){
				//删除
				lastItem.detach();
			}else{
				//若答案量等于6项，隐藏添加按钮
				if(count == 6)$('#addanswer').hide();
				//显示删除按钮
				$('#delanswer').show();
			}
		}else lastItem.detach();//没有输入，直接删除
	});

	//加载选择正确答案方法
	chooseCorrectAnswer();
	//加载选择所有权方法
	chooseOwnership();

});

//执行选择正确答案
function chooseCorrectAnswer(){
	$('.answers label').click(function(){
		$('.answers label input').removeAttr('checked',false);
		$(this).find('input').attr('checked',true);
	});
}

//执行选择所有权
function chooseOwnership(){
	$('.ownership label').click(function(){
		$('.ownership label input').removeAttr('checked',false);
		$(this).find('input').attr('checked',true);
		$('#forownership').val($(this).attr('val'));
	});
}

//选择问题的所属科目
function chooseCate(t){
	$('.subject').detach();
	var subject = t.val(),
	par = t.parent(),
	subjectText;
	$(t.find('option')).each(function(){
		if($(this).val() == subject)subjectText = $(this).text();
	});
	par.find('label').append('<span class="subject"> / '+subjectText+'</span>');
	$("select").val('');
	$('#forsubject').val(subject);
	$('#emptySubject').hide();
}

//检查表单
function checkForm(){

	//阻止连续点击提交
	if(flag)return false;
	
	//检查题目
	var title = $('textarea').val();
	title = replaceEnter(title);
	$('textarea').val(title);
	if(!title || isAllBlank(title)){
		$('textarea').focus().val('');
		return false;
	}

	//检查答案
	var answerNum = $('.an-items').size();
	var forcanswer = 0;
	for(var i = 1; i <= answerNum; i++){
		var tags = $('.a'+i).find('input');
		var caTag = tags.first();
		var aTag = tags.last();
		if(caTag.attr('checked'))forcanswer = i;
		if(!aTag.val() || isAllBlank(aTag.val())){
			aTag.focus().val('');
			return false;
			break;
		}
	}

	//检查正确答案
	if(!forcanswer){
		alert('The correct answer is undefined');
		return false;
	}
	$('#forcanswer').val(forcanswer);

	//检查所属科目
	if(!$('#forsubject').val()){
		$('#emptySubject').show();
		return false;
	}

	//检查时间限制
	var timelimitEle = $('.timelimit input'), timelimit = timelimitEle.val();
	if(timelimit == ''){
		timelimit = 0;
	}else{
		if(!/^[\d]{1,5}$/.test(timelimit)){
			alert('Please enter a 10 - Integer 86400');
			timelimitEle.select();
			return false;
		}else{
			if(timelimit > 9 && timelimit < 86401){}else{
				alert('Please enter a 10 - Integer 86400');
				timelimitEle.select();
				return false;
			}
		}
	}

	$('button').text('...');

	flag = true;

	return true;

}

//除去回车
function replaceEnter(str){
	str = str.replace(/\n/g,"");
	return str;
}

//检查值是否全部是空格
function isAllBlank(str){
	var arr = str.split(" "),leng = arr.length,empty = 0;
	for(var i = 0; i < leng; i++){
		if(arr[i] == '')empty++;
	}
	if(empty == leng)return true;
}

//检查输入长度
function checkTextInput(t,limit){
	var v = t.val();
	if(v.length > limit){
		t.val(v.substring(0,limit));
	}
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
			<a href="publish">Publish</a>
		</div>

		<?php
		if($insertSuc){
		?>
		<div id="insertSuc" style="margin:20px 20px 0;color:#fff;font-weight:bold;background:green;width:590px;padding:15px 10px 5px;">Publishing success！</div>
		<script type="text/javascript">
		//提示发布成功
		$(function(){
			//回到顶部
			window.parent.getTop();
			//4000毫秒后消失
			$('#insertSuc').fadeIn().delay(3400).fadeOut();

		});
		</script>
		<?php } ?>

		<form id="area" action="" method="POST" onsubmit="return checkForm()">
			
			<div class="list">
				<div class="titles">Topic<span>(Enter topic of the question)</span></div>
				<div class="text"><textarea name="parm[]"></textarea></div>
			</div>

			<input type="hidden" name="parm[]" id="forcanswer" value="">
			<input type="hidden" name="parm[]" id="forsubject" value="">
			<input type="hidden" name="parm[]" id="forownership" value=0>

			<div class="list">
				<div class="titles">Answers<span>(Enter the answer, at least 3, Up to 6, 160 between characters)</span></div>
				<div class="answers">
					<div class="an-items a1">
						<b>A</b>
						<label><input type="radio"> This is correct answer</label>
						<input type="text" name="parm[]" class="inputs" autocomplete="off" disableautocomplete >
					</div>
					<div class="an-items a2">
						<b>B</b>
						<label><input type="radio"> This is correct answer</label>
						<input type="text" name="parm[]" class="inputs" autocomplete="off" disableautocomplete >
					</div>
					<div class="an-items a3">
						<b>C</b>
						<label><input type="radio"> This is correct answer</label>
						<input type="text" name="parm[]" class="inputs" autocomplete="off" disableautocomplete >
					</div>
				</div>
				<div class="operaanswer">
					<a href="javascript:;" id="addanswer">Add</a>
					<a href="javascript:;" id="delanswer">Delete</a>
				</div>
			</div>

			<div class="list">
				<div class="titles">Classification<span>(Click to select belongs grade subjects)</span><span id="emptySubject">Please click select</span></div>
				<div class="grades">
					<?php echo$html; ?>
				</div>
			</div>

			<div class="list">
				<div class="titles">Timelimit<span>(Please enter a 10 - Integer 86400, Blank means unlimited, unit: seconds)</span></div>
				<div class="timelimit">
					<input type="text" name="parm[]" class="inputs" autocomplete="off" disableautocomplete >
				</div>
			</div>

			<div class="list">
				<div class="titles">Ownership<span>(Please click on the radio)</span></div>
				<div class="ownership">
					<label val=0><input type="radio" checked=true>Private</label>
					<label val=1><input type="radio">Public</label>
				</div>
			</div>
			

			<div class="list">
				<button>Publish</button>
			</div>

		</form>

		<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

	</div>

</body>
</html>
<?php
}