<?php

/*
* 科目模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class subject extends R{

	/*
	* 处理显示科目
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

		//检测查询数据库是否出错，成立则报错并停止下行
		$sql = $db->iQ("select `id`,`value`,`grade`,`time` from `subject` order by `id` desc limit 10") or die('error: 01');

		//获得数据
		$res = array();
		while($arr = $db->iR($sql)){
			$arr[2] = $this->get_grade($arr[2]);
			$arr[3] = date('Y-m-d H:i',$arr[3]);
			$res[] = $arr;
		}

		//显示页面
		require_once ADPATH.'static/page/subjectPage.php';
		subjectPage($res);
	}

}

$new = new subject();