<?php

/*
* 选择科目页面
*/


/*
 *
 * name：   用户
 *
 * grade：  用户年级
 *
 * opera：  用户操作，考试、复习
 *
 * res：    科目数据
 *
 */
function choosesubjectPage($name,$grade,$opera,$res){

	//科目列表HTML
	$subjectHtml = '';

	//检测是否存在科目
	if(is_array($res)&&count($res)){

		//排序号
		$ii = 1;

		//遍历科目数据数组
		foreach($res as $po){

			//拼接数据
			$subjectHtml .= '<div class="subjectitem">
			<div class="item-name subjectitem-child">&nbsp;'.$ii.'. '.$po[0].'</div>
			<div class="item-total subjectitem-child">total: '.$po[1].'</div>
			<div class="item-timelimit subjectitem-child">timelimit: '.$po[2].'</div>
			<div class="item-go subjectitem-child"><a href="/underway/'.$_GET['opera'].'/'.$_GET['opera2'].'/'.$po[3].'" title="Select this">Go</a></div>
			</div>';
			$ii++;

		}

	}else{

		//提示未有科目
		$subjectHtml = '<a href="/choose" style="color:#d33">Sorry, no subjects that grade, click here Back to previous</a>';

	}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Choose <?php echo$opera; ?> subjects</title>
<meta name="keywords" content="keywords">
<meta name="description" content="description">
<link href="/static/css/main.css" rel="stylesheet">
<link href="/static/css/style.css" rel="stylesheet">
<style>
.inlinearea{
	min-height:190px;
}

.inlinearea a{
	margin: 0 20px;
	letter-spacing: 1px;
	color:green;
}
.inlinearea a:hover{
	color:#d33;
	text-decoration: underline;
}
</style>
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
						<span id="table-title-txt">
							<?php echo$name; ?>, Please select your <?php echo$opera; ?> subjects.
						</span>
					</div>
					<!---->
					<div class="table-cell">
						<div class="inlinebox">
							<div class="inlinetitle">Subjects of Grade <?php echo$grade; ?></div>
							<div class="inlinearea">
								<?php echo$subjectHtml; ?>
							</div>
						</div>
					</div>
				</div>
				<div id="other">
					<div id="other-area">
						<a href="/choose">Choose movements</a>
						|
						<a href="/logout">Log out</a>
					</div>
				</div>
			</div>
			<br><br><br><br><br>
		</div>
		<!--myarea end-->

		<!--footer start-->
		<div id="footer">
			<div id="footera">
				<div id="mylinks">
					<span>© all rights reserved</span>
					<a href="#">About</a>
				</div>
			</div>
		</div>
		<!--footer end-->

	</div>

</body>
</html>

<?php
}