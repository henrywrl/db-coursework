<?php

/*
* 进行中页面
*/


/*
 *
 * name:    用户
 *
 * grade：  用户选择的年级
 *
 * subject：用户选择的科目
 *
 * count：  试题总数
 *
 */
function underwayPage($name,$grade,$subject,$count){

	$opera = ucfirst($_GET['opera']);

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Underway in</title>
<meta name="keywords" content="keywords">
<meta name="description" content="description">
<link href="/static/css/main.css" rel="stylesheet">
<script type="text/javascript" src="/static/js/jquery.js"></script>
<script type="text/javascript">

//声明空对象 Parm
var Parm = {};
//进行类型，考试还是复习
Parm['type'] = "<?php echo $_GET['opera']; ?>";

//回答标记，当值为真时表示正在提交回答中(也代表禁止回答)，为假时代表等待回答(也代表允许回答)
Parm['aFlag'] = false;

//答题计时器
Parm['timer'];
//答题计时值50%,单位：秒，若要计时5秒，则为10
Parm['timerSec'] = 0;

//答题时间限制值,秒
Parm['timelimit'] = 0;

//点击答题
Parm['clickAnswer'] = 0;

//反馈的计时器
Parm['fbTimer'];
//反馈计时值50%,单位：秒，若要计时5秒，则为10
Parm['fbTimerSec'] = 0;

//延时启用允许答题
Parm['enableAnswer'];

</script>
<style>

body{
	background:#ddd;
	font-family: arial;
}

#box{
	width:860px;
	background:#fff;
	margin:80px auto;
	border:1px solid #ccc;
	border-radius: 2px;
	position: relative;
}

#bar{
	padding:10px 10px 8px;
	background:#333;
	color:#fff;
	font-size:16px;
}

#quit{
	position: absolute;
	width:45px;
	height:38px;
	background:#d33;
	right:0;
	top:0;
	line-height: 38px;
	text-align: center;
	color:#fff;
	font-weight: bold;
}

#score{
	background:#eee;
	padding:10px;
	border-top:1px solid #e7e7e7;
	line-height: 24px;
}

#question{
	width:100%;
	background:#fff;
}

#loading{
	width:100%;
	text-align: center;
	padding:40px 0;
}

#area{
	padding:15px;
	display: none;
}

#topic{
	background:#f5f8fb;
	padding:15px 12px 12px;
	width:780px;
	border-radius: 3px;
	border:1px #cfe6f3 solid;
	line-height: 20px;
	word-wrap: break-word; word-break: break-all;
	margin:10px auto;
	position: relative;
}

#topic-flag{
	padding:5px 5px 4px;
	position: absolute;
	background: green;
	left:30px;
	color:#fff;
	border-radius: 3px;
	top:-19px;
}

#prompt{
	width:790px;
	margin:20px auto 0;
	background:#4f7fbd;
	padding:8px 8px 7px;
	color:#fff;
	font-size: 15px;
	border-radius: 2px 2px 0 0;
}

#answer{
	width:804px;
	margin:0 auto;
	border:1px solid #ddd;
}

.answers{
	width:100%;
	display: table;
	margin:6px 0 5px;
	padding:3px 0 5px;
	color:#3f6fac;
	font-size:14px;
}

.answers:hover{
	background: #eee;
}

.ano{
	width:4%;
	float: left;
	padding:7px 0 5px;
	text-align: center;
	margin:0 5px;
}

.aval{
	width: 93%;
	float: left;
	line-height: 27px;
	word-wrap: break-word; word-break: break-all;
}

#quit:hover{
	background:#c00;
}

.parm{
	font-weight: bold;
	font-size:14px;
	color:#333;
	margin:0 10px 0 0;
}

.values{
	margin:0 5px;
	color:#555;
	font-weight: normal;
}

.right{
	font-weight: bold;
	color:green;
}

.wrong{
	font-weight: bold;
	color:red;
}

.answered{
	font-weight: bold;
	color:blue;
}

#feedback{
	width:788px;
	margin:20px auto 0;
	padding:8px 8px 7px;
	color:red;
	font-size: 15px;
	border-radius: 2px 2px 0 0;
	display: none;
}

.correct{
	background: url(/static/img/correct.png);
}

</style>
</head>
<body>

	<!--main start-->
	<div class="main">

		<div id="box">

			<div id="bar">
				<?php echo '('.$opera.')  Grade '.$grade.' / '.$subject.' &nbsp; Total:('.$count.')'; ?>
			</div>

			<div id="question">
				<p id="loading"><img src="/static/img/loading.gif"></p>

				<div id="area">

					<div id="topic">
						<div></div>
						<p id="topic-flag">Topic</p>
					</div>

					<div id="feedback">asd
					</div>

					<div id="prompt">Click to select the correct answer</div>

					<div id="answer"></div>

				</div>

			</div>

			<div id="score">
				<?php
				echo '<span class="parm">Student:<span class="values Student">'.$name.'</span></span>
				<span class="parm">Answered:<span class="values answered">undefined</span></span>
				<span class="parm">Right:<span class="values right">undefined</span></span>
				<span class="parm">Wrong:<span class="values wrong">undefined</span></span>
				<span class="parm">Timelimit:<span class="values timelimit">undefined</span></span>';
				?>
			</div>

			<a href="javascript:;" id="quit" title="Quit" onclick="Quit()">Quit</a>

		</div>
<style>
#complete{
	width:500px;
	border:1px solid #ccc;
	margin:150px auto;
	background: #fff;
	display: none;
}
#com-title{
	padding:15px 20px 10px;
	background:#222;
	font-size: 20px;
	color:#fff;
}
#result{
	width:90%;
	margin:10px auto;
}
.result-list{
	display: table;
	width: 100%;
	padding:10px 0 8px;
	margin:5px 0;
	background: #f6f6f6;
}
.res-tit{
	float: left;
	width:30%;
	text-align: right;
	margin:0 5px 0 0;
}
.res-val{
	width:60%;
	text-align: left;
	float: left;
}
</style>
		<div id="complete">
			<div id="com-title"><?php echo$opera; ?> has ended</div>
			<div id="result">
				<div class="result-list">
					<p class="res-tit">Total: </p>
					<p class="res-val"><?php echo$count; ?></p>
				</div>
				<div class="result-list">
					<p class="res-tit">Answered: </p>
					<p class="res-val answered"></p>
				</div>
				<div class="result-list">
					<p class="res-tit">Right: </p>
					<p class="res-val right"></p>
				</div>
				<div class="result-list">
					<p class="res-tit">Wrong: </p>
					<p class="res-val wrong"></p>
				</div>
				<div class="result-list">
					<p class="res-tit">Movements: </p>
					<p class="res-val"><a href="/choose">Choose Movements</a> | <a href="/users">Account Center</a></p>
				</div>
			</div>
		</div>

	</div>

</body>
</html>

<script>

$(function(){

	//读取试题
	loading();

});

//执行读取试题方法
function loading(){

	//Ajax，GET提交，要求获得JSON数据
	$.ajax({
		type:"GET",url:"/getquestion/"+Parm['type'],dataType:"json",success:function(json){

			//判断返回指令
			switch(json.status){
				//试题列表Session丢失
				case '1': 
				alert('Session Lost');
				break;
				//已离线，并转向登录页
				case '2':
				alert("You are offline, please login");
				location.href="/";
				break;

				//返回成功
				case '0':
				//检查是否结束
				if(json.end){
					//提示结束
					$('.answered').text(json.answered);
					$('.right').text(json.right);
					$('.wrong').text(json.wrong);
					//设置为真，禁止回答
					Parm['aFlag'] = true;
					//显示结束
					complete();
					//终止下行
					return;
				}

				//清空答案容器，等待注入新的答案
				$('#answer').html('');

				//获得时间限制值
				Parm['timelimit'] = json.timelimit;

				//设置界面数据
				if(!setData(Parm['timelimit'],json.answered,json.right,json.wrong,json.topic,json.answers,json.isAnswered)){
					return false;
				}

				//判断试题是否有时间限制
				if(Parm['timelimit'] != '0'){
					//销毁旧的计时器
					clearTimeout(Parm['timer']);
					//启动计时
					timer();
				}else{
					//检查原时间限制，防止用户刷新页面。影响正确计时
					if(json.stimelimit != '0'){
						//若计时已为0，但原时间限制不为0，则断定为用户刷新页面，影响正确计时，即刻判断为答题错误
						//延时约9秒，等待进入下一题
						//归0
						$('.timelimit').html('<m style="color:red">0</m>');
						//显示反馈
						feedback('#fdd','#f66','f66','Exceed');
						//设置计时为8秒
						Parm['fbTimerSec'] = 16;
						//500毫秒执行一次计时,所以上面设置值为16，实则是8秒
						Parm['fbTimer'] = setInterval(fbTimer,500);
						//提交答案
						setTimeout(function(){
						    //这里提交的ID是9，即错误答案(答案ID最大值是5)，因为已超时，所以当作错误处理
						    submitAnswer(9);
						},500);

					}else{
						//提示无限制
						$('.timelimit').html('<m style="color:#f66">Unlimited</m>');
					}
				}

				break;

				default:alert('System error: '+json.status);
			}
		}
	});
}

//执行设置界面数据，并返回是否已回答过
function setData(timelimit,answered,right,wrong,topic,answers,isAnswered){
	//注入数据
	$('.timelimit').text(timelimit);
	$('.answered').text(answered);
	$('.right').text(right);
	$('.wrong').text(wrong);
	$('#topic div').text(topic);
	//显示区域
	$('#area').show();
	$('#loading').hide();
	//获得答案数量
	var total = answers.length;
	if(!total){
		$('#feedback').css({'background':'#fdd','border':'1px solid #f66'})
		.html('<m style="color:#d33">Sorry, You have not answered the questions. Please go to the next section to answer the exam questions. Prepare the next question.</m> &nbsp; <a href="javascript:;" onclick="Next()">Next</a>').slideDown();
		//设置计时为4秒
		Parm['fbTimerSec'] = 8;
		//500毫秒执行一次计时,所以上面设置值为6，实则是3秒
		Parm['fbTimer'] = setInterval(fbTimer,500);
		$('#answer').append('<span style="padding:20px;display:block">Nothing</span>');
		return false;
	}
	//注入答案数据
	for(var i = 0; i < total; i++){
		$('#answer').append('<a class="answers" href="javascript:;" title="Select This" data="'+i+'"><p class="ano an'+(i+1)+'">'+(i+1)+'.</p><p class="aval">'+answers[i].item+'</p></a>');
	}
	//检测是否已回答过
	if(isAnswered){
		//值为1或真，代表回答过该试题，禁止再次回答
		$('.an'+isAnswered).addClass('correct').html('&nbsp;');
		//显示反馈
		feedback('#eee','#ccc','green','You have answered the questions');
		//设置计时为5秒
		Parm['fbTimerSec'] = 10;
		//500毫秒执行一次计时,所以上面设置值为6，实则是3秒
		Parm['fbTimer'] = setInterval(fbTimer,500);
		return false;
	}else{
		//值为0或假，代表未回答过
		//允许回答
		Answer();
		return true;
	}
}

//执行答题计时
function timer(){
	//设置计时器
	Parm['timer'] = setTimeout(timer,1000);
	//如果点击回答，则停止并销毁计时器
	if(Parm['clickAnswer']){
		clearTimeout(Parm['timer']);
		Parm['timelimit'] = 0;
		return false;
	}
	//大于5秒的提示
	if(Parm['timelimit'] > 5){
		$('.timelimit').html('<m style="color:#333">'+Parm['timelimit']+'</m>');
	}else if(Parm['timelimit'] > 0 && Parm['timelimit'] <= 5){
		//小于5秒的提示
		$('.timelimit').html('<m style="color:#d33">'+Parm['timelimit']+'</m>');
	}else if(Parm['timelimit'] == 0){
		//时间到，延时约9秒，等待进入下一题
		//归0
		$('.timelimit').html('<m style="color:red">0</m>');
		//答题销毁计时器
		clearTimeout(Parm['timer']);
		//显示反馈
		feedback('#fdd','#f66','#f66','Exceed');
		 //设置计时为8秒
		 Parm['fbTimerSec'] = 16;
		 //500毫秒执行一次计时,所以上面设置值为16，实则是8秒
		 Parm['fbTimer'] = setInterval(fbTimer,500);
		 //提交答案
		 setTimeout(function(){
		 	//这里提交的ID是9，即错误答案(答案ID最大值是5)，因为已超时，所以当作错误处理
		 	submitAnswer(9);
		 },500);
	}
	//递减时间
	Parm['timelimit']--;
}

//点击选择，回答问题
function Answer(){
	$('.answers').click(function(){
		//停止计时器
		Parm['clickAnswer'] = true;
		//销毁之前的计数器
		clearTimeout(Parm['timer']);
		//获得答案ID
		var id = $(this).attr('data');
		//提交答案
		submitAnswer(id);
	});
}

//执行提交回答
function submitAnswer(id){
	//禁止连续回答
	if(Parm['aFlag'])return false;
	Parm['aFlag'] = true;
	//Ajax, POST 提交，要求返回JSON
	$.ajax({
		type:"POST",
		url:"/answer/"+Parm['type'],
		dataType:"json",
		data:{'id':id},
		success:function(json){
			//判断返回指令
			switch(json.status){
				//试题列表Session丢失
				case '2': 
				alert('Session Lost');
				break;
				//已离线，并转向登录页
				case '3':
				alert("You are offline, please login");					
				location.href="/";
				break;
				//返回成功
				case '0':
				//1，回答正确，程序延时约4秒，等待进行下一题
				if(json.res == '1'){
					Parm['clickAnswer'] = false;
					//显示反馈
					feedback('#eee','#ddd','green','U r Right');
					//设置计时为3秒
					Parm['fbTimerSec'] = 6;
					//500毫秒执行一次计时,所以上面设置值为6，实则是3秒
					Parm['fbTimer'] = setInterval(fbTimer,500);
				}else if(json.res == '2'){
					//回答错误，程序延时约10秒，等待进行下一题
					//延时启用允许答题
					Parm['enableAnswer'] = setTimeout(function(){
						Parm['aFlag'] = false;
					},10500);
					//是否点击答题
					if(Parm['clickAnswer']){
						//允许答题
						Parm['clickAnswer'] = false;
						//显示反馈
						feedback('#fdd','#f66','#d33','Sorry, u r wrong');
						//设置计时为10秒
						Parm['fbTimerSec'] = 20;
						//500毫秒执行一次计时,所以上面设置值为20，实则是10秒
						Parm['fbTimer'] = setInterval(fbTimer,500);
					}
				}
				//显示正确答案
				$('.an'+json.correct).addClass('correct').html('&nbsp;');
				break;
				default:alert('System error: '+json.status);
			}
		}
	});
}

//执行示完成或结束
function complete(){
	setTimeout(function(){
		$('#box').slideUp();
		$('#complete').slideDown();
	},100);
}

//执行显示反馈
//bgcolor:背景颜色，brcolor：边框颜色，color：字体颜色，content：内容
function feedback(bgcolor,brcolor,color,content){
	$('#feedback').css({'background':bgcolor,'border':'1px solid '+brcolor})
		.html('<m style="color:'+color+'">'+content+'. Please get the correct answer in the answer list. Prepare the next question.</m> &nbsp; <a href="javascript:;" onclick="Next()">Next</a>')
		.slideDown();
}

//退出进行
function Quit(){
	//检测进行类型是否为考试，是：询问确认，否：直接退出
	if(Parm['type'] == 'exam'){
		if(confirm('Do you really want to quit?')){location.href='/'}
	}else{
		location.href="/";
	}
}

//手动点击进入下一题
function Next(){
	//将计时值设置为0
	Parm['fbTimerSec'] = 0;
	//销毁延时启用允许答题
	clearTimeout(Parm['enableAnswer']);
}

function fbTimer(){

	//时间到就关闭反馈
	if(Parm['fbTimerSec'] <= 0){
		//销毁反馈计时器
		clearInterval(Parm['fbTimer']);
		//
		$('#feedback').slideUp();
		//读取下一题
		loading();
		//允许答题
		Parm['aFlag'] = false;
	}
	//递减时间
	Parm['fbTimerSec']--;

}

</script>

<?php
}
