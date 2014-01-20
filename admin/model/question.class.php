<?php

/*
* 试题模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class question extends R{

	/*
	* 处理显示试题
	*/

	function __construct(){
		//执行exec
		$this->exec();
	}

	private function exec(){

		//检测是否登录
		if(!$this->checkOnlineR())exit('<meta charset="utf-8"><script>window.parent.out();</script>');

		//使用数据库
		$db = $this->MySQL();

		//联表查询，检测查询数据库是否出错，成立则报错并停止下行
		$sql = $db->iQ("select questions.*,subject.* from questions, subject where questions.by = subject.id order by questions.id desc limit 10") or die('error: 01');

		//获得数据
		$res = array();
		while($arr = $db->iR($sql)){
			$arr[3] = $this->get_log('qid',$arr[0],$db);
			$arr[5] = $this->get_own($arr[5]);
			$arr[9] = $this->get_grade($arr[9]);
			$res[] = $arr;
		}

		//显示页面
		require_once ADPATH.'static/page/questionPage.php';
		questionPage($res);
	}

}

$new = new question();