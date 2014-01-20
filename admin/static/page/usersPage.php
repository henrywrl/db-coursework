<?php

/*
*  用户管理页面
*/
//res：用户列表数组
function usersPage($html){

//定义未有试题的节点
$nothing = '<div class=\'item\' style=\'padding:10px\'>No Students</div>';

if($html == '') $html = $nothing;

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
	font-size: 14px;
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
	margin:20px;
}
#aa{
	width:100%;
}
.item{
	display: table;
	width: 100%;
	background:#fff;
	border-bottom: 1px dashed #ccc;
	text-align: center;
}
.node{
	float: left;
	text-align: center;
	font-size: 13px;
	color:#444;
	padding:10px 0 9px;
}
.num{
	width:6%;
	
}
.acc,.name{
	width:17%;
	overflow: hidden;
}

.age,.gender,.grade,.class{
	width:6.3%;
}
.time{
	width: 13%;
}
em{
	font-size:12px;
	color:#888;
}
.opera{
	width:4%;
}
textarea{
	width:98%;
	line-height: 20px;
	font-size:13px;
	height:100px;
}

.dis{
	color:red;
}
.unc{
	color:orange;
}
.nor{
	color:green;
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
.disabled{
	background-position: 0px -64px;
}
.disabled:hover{
	background-position: 0px -80px;
}
.activating{
	background-position: 0px -128px;
}
.activating:hover{
	background-position: 0px -144px;
}
.enable{
	background-position: 0px -96px;
}
.enable:hover{
	background-position: 0px -112px;
}
.participate{
	width:7%;
}
.rate{
	width:9%;
}
</style>
<script src="/static/js/jquery.js"></script>
<script>

var flag = false,
limit = 10, //一页显示10个学生
nothing = "<?php echo$nothing; ?>";

//执行删除
function _delete(t){
	//阻止连续点击
	if(flag)return false;
	//询问确认
	if(confirm("Confirm Delete?")){

		flag = true;
		var par = t.parent().parent(),//对象祖父级
		id = par.find('.num').text();//对象ID
		par.detach();//删除节点
		if($('.item').size()==1)$('#aa').append(nothing);//检查item数量
		//POST提交删除请求
		$.ajax({
			type:"POST",url:"deleteuser",dataType:"json",data:"id="+id,
			success:function(json){
				switch(json.status){
					case '1': window.parent.isout(); break;
					case '0': flag = false; break;
					default:alert('System error '+json.status);
				}
			}
		});
	}
}

//更改用户阶级
function changeClass(t){
	//阻止连续点击
	if(flag)return false;
	var par = t.parent().parent(),//对象祖父级
	id = par.find('.num').text(),//对象ID
	ty = t.attr('opera'),//操作符
	_type, //更改后的操作符
	txt,//当前操作名称
	atxt,//更改后的操作名称
	iclass;//更改后的class

	//设置值
	switch(ty){
		case '0':
		_type = 2;
		txt = 'Enable';
		atxt = 'disabled';
		iclass = 'nor';
		break;
		case '1':
		_type = 2;
		txt = 'Activating';
		atxt = 'disabled';
		iclass = 'nor';
		break;
		case '2':
		_type = 0;
		txt = 'Disabled';
		atxt = 'enable';
		iclass = 'dis';
		break;
		default:alert('System error');return false;
	}

	//询问确认
	if(confirm('Confirm '+txt+'?')){
		//flag为真
		flag = true;
		//修改节点值
		par.find('.class').html('<i class="'+iclass+'">'+_type+'</i>');
		//POST提交更改请求
		$.ajax({
			type:"POST",url:"changeuserclass",dataType:"json",data:{'id':id,'type':_type},
			success:function(json){
				switch(json.status){
					case '1': window.parent.out(); break;
					case '0': 
					flag = false;
					t.attr('opera',_type);//更改操作符
					t.attr('class',atxt);
					break;
					default:alert('System error: '+json.status);
				}
			}
		});
	}
}

function hover(){
	$('.myitem').hover(//徘徊效果
		function(){
			$(this).css({'background':'#f5f5f5'});
		},
		function(){
			$(this).css({'background':'#fff'});
		}
	);
}



$(function(){

	//加载更多的数据
	$('button').click(function(){
		var t= $(this);//重新赋值点击对象，因为$(this)指向的是最近调用它的jquery对象, 在jQuery Ajax里的$(this)指的是$.ajax了
		t.hide();//隐藏对象
		$.ajax({
			type:"POST",url:"usermore",dataType:"json",data:"limit="+limit,
			success:function(json){
				switch(json.status){
					case '1': window.parent.out(); break;
					case '0':
					if(json.msg){
						$('.item').last().after(json.msg);
						limit += 10;
						t.show();
						$('.cate').each(function(){
							if($(this).find('select').size()==0)$(this).append(sele);
						});
						hover();
						setTimeout(function(){
							window.parent.setFrameHeight();
						},100);
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
			<a href="users">Student</a>
		</div>

		<div id="area">
			<div>
				<div id="aa">
					<div class="item" style="background:#eee;border-bottom:1px solid #ddd">
						<div class="num node">Sort</div>
						<div class="acc node">Email</div>
						<div class="name node">Name</div>
						<div class="age node">Age</div>
						<div class="gender node">Gender</div>
						<div class="grade node">Grade</div>
						<div class="class node">Class</div>
						<div class="participate node">Participate</div>
						<div class="rate node">Correct rate</div>
						<div class="time node">Last update</div>
						<div class="opera node">Opera</div>
					</div>
				</div>
				<?php echo$html;?>
				<p style="text-align:right;padding:10px 10px 50px;"><button style="padding:5px;">More</button></p>
				<p id="nomore"style="color:#888;padding:10px 10px 50px;display:none">No more</p>
			</div>
		</div>

	</div>

</body>
</html>
<?php
}