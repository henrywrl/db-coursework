<?php

/*
* 管理前台
*/

function frontPage(){

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Background</title>
<script src="/static/js/jquery.js"></script>
<style>
*{
	margin:0;
	padding:0;
}
body{
	font-size: 14px;
	overflow: hidden;
	font-family: arial;
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
#nav{
	width:230px;
	height:1000px;
	background:url(static/img/adminline.jpg);
	border-right:1px solid #ccc;
	position: fixed;
}
#nav-a a{
	display:block;
	padding:12px 0 10px;
	width:90%;
	margin:10px auto;
	text-align: center;
	color:#444;
}

#nav-a a:hover{
	background:#eee;
}

#frame{
	float: left;
	margin:0 0 0 231px;
	overflow-y:scroll;
}
iframe{
	width:100%;
	margin:0;
	padding:0;
	border:0;
}
</style>
<script>

//屏幕宽
var windowWidth = window.screen.width,
dom = document;

window.onload=function(){
	
	//设置框架窗口宽
	setFrameWidth();

}

//改变浏览器大小时设置框架窗口宽
$(window).resize(function(){
		setFrameWidth();
});

//执行设置框架窗口宽
function setFrameWidth(){
	var scrollBarWidth = windowWidth-dom.body.scrollWidth;
	$('#frame').css({'width':windowWidth-232-scrollBarWidth+'px','height':window.screen.height-50+'px'});
}

//适应子窗口高度
function setFrameHeight(){
	var frame = dom.getElementById('myframe'), 
    win = frame.contentWindow, 
    doc = win.document, 
    html = doc.documentElement, 
    body = doc.body; 
    // 获取高度 
    var height = Math.max( body.scrollHeight, body.offsetHeight,html.clientHeight, html.scrollHeight, html.offsetHeight ); 
    frame.setAttribute('height', height);
}

//更改框架页面
function changeFrame(url){
	//回到顶部
	getTop();
	$('#myframe').attr('src',url);
}

//执行回到顶部
function getTop(){
	$('#frame').scrollTop(0);
}

function out(flag){
	if(flag)alert('You are offline, please login');
	location.href="/admin";
}

</script>
</head>
<body>

	<!--main-->
	<div id="main">

		<!--nav-->
		<div id="nav">
			<div style="font-size:30px;width:100%;text-align:center;padding:20px 0">Website Background</div>
			<div id="nav-a">
					<a href="javascript:;" onclick="changeFrame('info')">Home</a>
					<a href="javascript:;" onclick="changeFrame('publish')">Publish</a>
					<a href="javascript:;" onclick="changeFrame('question')">Questions</a>
					<a href="javascript:;" onclick="changeFrame('subject')">Subjects</a>
					<a href="javascript:;" onclick="changeFrame('users')">Students</a>
					<a href="javascript:;" onclick="changeFrame('changepd')">Password</a>
					<a href="/admin/logout">Logout</a>
			</div>
		</div>

		<div id="frame">
			<iframe src="info" frameborder="0" onload="setFrameHeight()" id="myframe"></iframe>
		</div>

	</div>

</body>
</html>
<?php
}