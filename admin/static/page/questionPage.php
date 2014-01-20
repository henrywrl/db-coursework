<?php

/*
* 试题管理页面
*/

//res: 试题数组列表
function questionPage($res){

//初始化
$html = '';
//定义未有试题的节点
$nothing = '<div class=\'myitem\' style=\'padding:20px 0;\'>No questions, please publish</div>';


//检查试题数组列表单元数
if(count($res)){
//拼接数据
foreach($res as $key => $po){

	$html .= '<div class="myitem theitem">
	<div class="nid nodes" nid="'.$po[0].'">'.$po[0].'</div>
	<div class="topic nodes">'.$po[1].'</div>
	<div class="correctanswer nodes">The <b>'.$po[2].'</b></div>
	<div class="subject nodes">'.$po[8].'</div>
	<div class="grade nodes">'.$po[9].'</div>
	<div class="timelimit nodes">'.$po[4].'</div>
	<div class="ownership nodes">'.$po[5].'</div>
	<div class="time nodes">'.date('m-d-Y',$po[6]).'</div>
	<div class="participate nodes">'.$po[3][0].'</div>
	<div class="rate nodes">'.$po[3][1].'%</div>
	<div class="opera nodes">
	<a href="javascript:;" onclick="get_answers($(this))" title="View the answers" class="va"></a><a href="javascript:;" title="delete" onclick="del($(this))" class="delete"></a></div>
	</div>';

}
}else{
	$html = $nothing;
}


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
	overflow: hidden;
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
	margin:20px 20px 0;
	position: relative;
}
.title{
	font-weight: bold;
	font-size:13px;
}
.title a{
	color:green;
}

.iarea{
	background: none repeat scroll 0 0 #f2f2f2;
  border: 1px solid #DDDDDD;
  color: #444444;
  font-size: 14px;
  letter-spacing: 1px;
  margin: 10px 0 20px;
  padding: 10px;
}
.iarea a{
	color:blue;
}
.add-area {
  display: none;
  position: absolute;
  left: 0;
  top:0;
  border:1px solid #ddd;
  padding:10px;
  background:#fff;
}
.add-area div {
  display: table;
  padding: 0 0 10px;
}
.add-area input {
  display: block;
  float: left;
  padding: 5px;
  width: 150px;
}
.add-area p {
  color: #666666;
  float: left;
  font-size: 13px;
  padding: 7px 0 0 10px;
}
.add-area button {
  font-weight: bold;
  padding: 5px;
}
.cancel{
	color:#666;
}
.cg-area{
	padding:0 0 10px;
	display: none;
}
.cg-area input{
	width:150px;
	padding:5px;
}

</style>
<script src="/static/js/jquery.js"></script>
<script>

var flag,//进行添加中的标记
limit = 10,
nothing = "<?php echo$nothing; ?>";

$(function(){


	//加载更多的数据
	$('#more').click(function(){
		if(flag)return false;
		var t= $(this);//重新赋值点击对象，因为$(this)指向的是最近调用它的jquery对象, 在jQuery Ajax里的$(this)指的是$.ajax了
		t.hide();//隐藏对象
		$.ajax({
			type:"POST",url:"questionmore",dataType:"json",data:"limit="+limit,
			success:function(json){
				switch(json.status){
					case '1': window.parent.out(); break;
					case '0':
					if(json.msg){
						$('.theitem').last().after(json.msg);
						limit += 10;
						t.show();
						setTimeout(function(){
							window.parent.setFrameHeight();
						},100);
						hover();
					}else{
						$('#nomore').show();
						t.parent().detach();
					}
					break;
					default:alert('System error: '+json.status);
				}
			}
		});
	});

	hover();

});

//查看答案
function get_answers(t){
	if(flag)return false;
	var par = t.parent().parent(),
	nid = par.find('.nid').attr('nid');
	$.ajax({
		type:"POST",
		url:"getanswers",
		dataType:"json",
		data:{'id':nid},
		success:function(json){
			if(json.status == '0'){
				var str = '',answerleng = json.msg.length;
				for(var i = 0; i < answerleng; i++){
					str += (i+1)+', '+json.msg[i].node+"\n";
				}
				alert(str);
			}else alert('System error: '+json.status);
		}
	});
}

//删除试题
function del(t){
	var par = t.parent().parent(),
	nid = par.find('.nid').attr('nid');
	if(confirm("Confirm Delete?")){
		$.ajax({
			type:"POST",url:"deletequestion",dataType:"json",
			data:{'id':nid},
			success:function(json){
				switch(json.status){
						case '1': window.parent.out(); break;
						case '0':break;
						default:alert('发生错误'+json.status);
					}
			}
		});
		//删除节点
		par.detach();
		//若列数为0，提示未有试题
		if($('.theitem').size()==0)$('#mylist-a').append(nothing);
	}
}

function hover(){
	$('.theitem').hover(//徘徊效果
		function(){
			$(this).css({'background':'#f5f5f5'});
		},
		function(){
			$(this).css({'background':'#fff'});
		}
	);
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
			<a href="question">Questions</a>
		</div>

		<div id="area">
			<div class="title"><a href="publish">Publish</a></div>
		</div>

	</div>

<style>
#mylist{
	margin:20px;
	font-size:13px;
}
#mylist-a{
	width:100%;
}
.myitem{
	display: table;
	width:100%;
	border-bottom: 1px dashed #ccc;
}
.myitem input{
	width:80%;
	padding:3px;
}
.nodes{
	float: left;
	text-align: center;
	padding:12px 0 10px;
}

.nid{
	width:5%;
}
.topic{
	width:20%;
	word-wrap: break-word; word-break: break-all;
	max-height:100px;
	overflow: hidden;
	}
.correctanswer{
	width:7%;
}
.subject,.grade,.timelimit,.ownership{
	width:8.5%;
}
.time{
	width:10%;
}

.opera{
	width:4%;
	display: table;
}
.participate,.rate{
	width:9.3%;
}
.top{
	background:#eee;
	border-bottom: 1px solid #ccc;
}
.opera a{
	display: block;
	float: left;
	width:16px;
	height:16px;
	background-image:url(/static/img/icons.png);
	margin:0 2px;
}
.delete{
	background-position: 0px -32px;
}
.delete:hover{
	background-position: 0px -48px;
}
.va{
	background-position: 0 0;
}
.va:hover{
	background-position: 0px -16px;
}
</style>

		<div id="mylist">
			<div id="mylist-a">
				<div class="myitem top">
					<div class="nid nodes">Sort</div>
					<div class="topic nodes">Topic</div>
					<div class="correctanswer nodes">Correct</div>
					<div class="subject nodes">Subject</div>
					<div class="grade nodes">Grade</div>
					<div class="timelimit nodes">Timelimit</div>
					<div class="ownership nodes">Ownership</div>
					<div class="time nodes">Date</div>
					<div class="participate nodes">Participate</div>
					<div class="rate nodes">Correct rate</div>
					<div class="opera nodes">Opera</div>
				</div>
				<?php echo$html; ?>
			</div>
			<p style="text-align:right;padding:10px 10px 50px;"><button style="padding:5px;" id="more">More</button></p>
			<p id="nomore"style="color:#888;padding:10px 10px 50px;display:none">No more</p>
		</div>

	</div>
	<br><br>
	<br><br>
	<br><br>
	<br><br>

</body>
</html>
<?php
}