<?php

/*
* 试题管理页面
*/

//res: 科目数组列表
function subjectPage($res){

//初始化
$html = '';
//定义未有试题的节点
$nothing = '<div class=\'myitem\' style=\'padding:20px 0;\'>No subjects, Please Add</div>';

//检查数组单元数量
if(count($res)){
//拼接数据
foreach($res as $key => $po){

	$html .= '<div class="myitem theitem">
	<div class="nid nodes" nid="'.$po[0].'">'.$po[0].'</div>
	<div class="value nodes">'.$po[1].'</div>
	<div class="grade nodes">'.$po[2].'</div>
	<div class="time nodes">'.$po[3].'</div>
	<div class="opera nodes"><a href="javascript:;" onclick="del($(this))">delete</a></div>
	</div>';

	//<input type="text" value="'.$po[1].'"" maxlength="20">
	//<a href="javascript:;" onclick="save($(this))">save</a> | 
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
.change,.del,.canceled{
	font-weight:bold;
	font-size:12px;
}
.canceled{
	color:#777;
}
.del{
	color:#d33;
}
</style>
<script src="/static/js/jquery.js"></script>
<script>

var flag,//进行添加中的标记
cFlag,//进行编辑中的标记
limit = 10,//一页显示10条数据
nothing = "<?php echo$nothing; ?>";

$(function(){

	//显示和隐藏添加窗口
	$('.title a').click(function(){
		$('.add-area').slideDown();
	});

	//取消添加
	$('#cancel').click(function(){
		$('.add-area').slideUp();
	});

	//执行添加
	$('#add').click(function(){
		//阻止连续点击
		if(flag)return false;
		var t = $(this),//点击对象
		par = t.parent(),//对象父级
		valueEle = par.find('input'),//科目名输入框
		value = valueEle.val(),//科目名
		gradeEle = par.find('select'),//年级选择列表
		grade = gradeEle.val();//年级

		//检查科目名
		if(!value||isAllBlank(value)){
			valueEle.focus().val('');return false
		}
		//检查年级
		if(!grade||grade=='Please select grade'){
			alert('Please select grade');
			return false
		}
		t.text('...');
		flag = true;
		$.ajax({
			type:"POST",url:"addsubject",dataType:"json",
			data:{'value':value,'grade':grade},
			success:function(json){
				switch(json.status){
					case '1': out(); break;
					case '6': 
					alert(value+' - Already exists in the grade, consider using a different name, or added to other grades');
					t.text('Add');
					flag = false;
					valueEle.select();
					break;
					case '0':
					location.reload();
					break;
					default:alert('System error: '+json.status);
				}
			}
		});
	});

	//加载更多的数据
	$('#more').click(function(){
		if(flag)return false;
		var t= $(this);//重新赋值点击对象，因为$(this)指向的是最近调用它的jquery对象, 在jQuery Ajax里的$(this)指的是$.ajax了
		t.hide();//隐藏对象
		$.ajax({
			type:"POST",url:"subjectmore",dataType:"json",data:"limit="+limit,
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

//删除试题
function del(t){
	var par = t.parent().parent(),
	nid = par.find('.nid').attr('nid');
	if(confirm("Confirm Delete?")){
		$.ajax({
			type:"POST",url:"deletesubject",dataType:"json",
			data:{'id':nid},
			success:function(json){
				switch(json.status){
						case '1': window.parent.out(); break;
						case '5': alert('Sorry, there are questions under the subject, please delete it'); break;
						case '0':break;
						default:alert('System error：'+json.status);
					}
			}
		});
		par.detach();
		if($('.theitem').size()==0)$('#mylist-a').append(nothing);
	}
}

function save(t){
	if(cFlag)return false;
	var par = t.parent().parent(),
	nid = par.find('.nid').text(),
	ele = par.find('input'),
	cn = ele.first().val(),
	en = ele.last().val();
	if(!cn){
		ele.first().focus();
		return false;
	}
	var regex =/^[\u4E00-\u9FA5\ ]+$/;
	if(!regex.exec(cn)){
		alert('请确认中文的名称全是中文');
		ele.first().select();
		return false
	}
	if(!en){
		ele.last().focus();
		return false;
	}
	var regex =/^[A-Za-z\ ]+$/;
	if(!regex.exec(en)){
		alert('请确认英文的名称全是英文');
		ele.last().select();
		return false
	}
	cFlag = true;
	$.ajax({
		type:"POST",url:"changecate",
		data:"nid="+nid+"&cn="+encodeURIComponent(cn)+"&en="+en,
		dataType:"json",success:function(json){
			switch(json.status){
				case '1': out(); break;
				case '8':
				alert('该中文名称已存在，请考虑使用其他名称');
				cFlag = false;
				ele.first().select();
				break;
				case '10':
				alert('该英文名称已存在，请考虑使用其他名称');
				cFlag = false;
				ele.last().select();
				break;
				case '0':
				alert('操作成功');
				location.reload();break;
				default:alert('发生错误'+json.status);
			}
		}
	});
}

//检查值是否全部是空格
function isAllBlank(str){
	var arr = str.split(" "),leng = arr.length,empty = 0;
	for(var i = 0; i < leng; i++){
		if(arr[i] == '')empty++;
	}
	if(empty == leng)return true;
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
			<a href="subject">Subjects</a>
		</div>

		<div id="area">
			<div class="title"><a href="javascript:;">+ Add Subject</a></div>
			<div class="add-area">
				<div><input type="text" maxlength="20"><p>Please enter the subject name, between 20 characters</p></div>
				<div>
					<select>
						<option value="" disabled="disabled" selected="selected">Please select grade</option>
						<option value=1>Grade One</option>
						<option value=2>Grade Two</option>
						<option value=3>Grade Three</option>
						<option value=4>Grade Four</option>
					</select>
				</div>
				<button id="add">Add</button>
				<button id="cancel">Cancel</button>
			</div>
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
	width:14%;
}
.value,.grade,.time{
	width:22%;
	padding:7px 0 6px;
}

.opera{
	width:15%;
}
.top{
	background:#eee;
	border-bottom: 1px solid #ccc;
}
</style>

		<div id="mylist">
			<div id="mylist-a">
				<div class="myitem top">
					<div class="nid nodes">Sort</div>
					<div class="value nodes"style="padding:10px 0 0px">Name</div>
					<div class="grade nodes"style="padding:10px 0 0px">Grade</div>
					<div class="time nodes">Date</div>
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