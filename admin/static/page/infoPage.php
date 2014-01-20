<?php

/*
*  后台概况页
*/

function infoPage($userParm,$subjectParm,$questionParm){

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
#area{
	margin:20px;
}
h3{
	color:#666;
}
#userParm p{
	float: left;
	margin:0 10px 0 0;
}

#date {
  background: none repeat scroll 0 0 #f2f2f2;
  border: 1px solid #DDDDDD;
  color: #444444;
  font-size: 14px;
  margin: 10px 0 20px;
  padding: 10px;
}
h5{
	padding:0 0 10px;
}
.iarea{
	background: none repeat scroll 0 0 #f2f2f2;
  border: 1px solid #DDDDDD;
  color: #444444;
  font-size: 14px;
  margin: 10px 0 20px;
  padding: 10px;
}
.text{
	font-size:13px;
	color:#444;
}
b{
	color:green;
}
.iarea span{
	margin-right:10px;
}
</style>
<script>
</script>
</head>
<body>

	<!--main-->
	<div id="main">

		<!--nav-->
		<div id="head">
			<span>&nbsp;&nbsp;Background：</span>
			<a href="info">Home</a>
		</div>

		<div id="area">
			<div id="welcome">
				<h3>Welcome</h3>
			</div>
			<div id="date">It is <?php echo date('m-d-Y H:i'); ?></div>
			<div id="userParm">
				<div class="iarea">
					<h5>Users</h5>
					<div style="display:table;padding:0 0 10px" class="text">
						<span>Grade One: <b>(<?php echo$userParm[0]; ?>)</b>students</span>
						<span>Grade Two: <b>(<?php echo$userParm[1]; ?>)</b>students</span>
						<span>Grade Three: <b>(<?php echo$userParm[2]; ?>)</b>students</span>
						<span>Grade Four: <b>(<?php echo$userParm[3]; ?>)</b>students</span>
						<span>[<a href="users">Management</a>]</span>
					</div>
				</div>
			</div>
			<div id="subjectParm">
				<div class="iarea">
					<h5>Subjects</h5>
					<div style="display:table;padding:0 0 10px" class="text">
						<span>Grade One: <b>(<?php echo$subjectParm[0]; ?>)</b>subjects</span>
						<span>Grade Two: <b>(<?php echo$subjectParm[1]; ?>)</b>subjects</span>
						<span>Grade Three: <b>(<?php echo$subjectParm[2]; ?>)</b>subjects</span>
						<span>Grade Four: <b>(<?php echo$subjectParm[3]; ?>)</b>subjects</span>
						<span>[<a href="subject">Management</a>]</span>
					</div>
				</div>
			</div>
			<div id="questionParm">
				<div class="iarea">
					<h5>Questions</h5>
					<div style="display:table;padding:0 0 10px" class="text">
						<span>Grade One: <b>(<?php echo$questionParm[0]; ?>)</b>questions</span>
						<span>Grade Two: <b>(<?php echo$questionParm[1]; ?>)</b>questions</span>
						<span>Grade Three: <b>(<?php echo$questionParm[2]; ?>)</b>questions</span>
						<span>Grade Four: <b>(<?php echo$questionParm[3]; ?>)</b>questions</span>
						<span>[<a href="question">Management</a>]</span>
					</div>
				</div>
			</div>
		</div>

	</div>

</body>
</html>
<?php
}