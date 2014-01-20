<?php

/*
* 学员管理模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class users extends R{

	function __construct(){
		//执行exec
		$this->exec();
	}

	private function exec(){

		//检测是否登录
		if(!$this->checkOnlineR())exit('<meta charset="utf-8"><script>alert("您已处于离线状态，请登录");location.href="index";</script>');

		//使用数据库
		$db = $this->MySQL();

		//检测查询数据库是否出错，成立则报错并停止下行
		$sql = $db->iQ("select `id`,`mail`,`name`,`age`,`gender`,`grade`,`class`,`time` from `user` order by `id` desc limit 10") or die('System error: 01');
		
		//获得数据
		$html = '';

		$opera = '';

		//
		$score = '';

		while($arr = $db->iR($sql)){

			$score = $this->get_log('uid',$arr[0],$db);
			$opera = $this->get_opera_admin($arr[6],0);
			$arr[4] = $this->get_gender($arr[4]);
			$arr[5] = $this->get_grade($arr[5]);
			$html .='<div class="item myitem">
					<div class="num node">'.$arr[0].'</div>
					<div class="acc node">'.$arr[1].'</div>
					<div class="name node">'.$arr[2].'</div>
					<div class="age node">'.$arr[3].'</div>
					<div class="gender node">'.$arr[4].'</div>
					<div class="grade node">'.$arr[5].'</div>
					<div class="class node">'.$opera[0].'</div>
					<div class="participate node">'.$score[0].'</div>
					<div class="rate node">'.$score[1].'%</div>
					<div class="time node"><em>'.date('Y-m-d H:i',$arr[7]).'</em></div>
					<div class="opera node"><a href="javascript:;" onclick="_delete($(this))" class="delete" title="delete"></a>'.$opera[1].'</div>
				</div>';

		}
		//显示页面
		require_once ADPATH.'static/page/usersPage.php';
		usersPage($html);
	}

}

$new = new users();