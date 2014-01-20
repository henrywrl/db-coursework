<?php

/*
* 更多的科目，模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class subjectmore extends R{

	/*
	* 加载更多的科目数据
	*/

	function __construct(){
		//执行exec
		$this->exec();
	}

	private function exec(){
		
		//检测是否登录
		if(!$this->checkOnlineR())$this->json(1);

		//检测是否设置限制符
		if(!isset($_POST['limit']))$this->json(2);

		//去除两边空格
		$limit = trim($_POST['limit']);

		//检测是否为数字
		if(!preg_match("/^[\d]+$/",$limit))$this->json(3);

		//使用数据库
		$db = $this->MySQL();

		//检测查询数据库是否出错，成立则报错并停止下行
		$sql = $db->iQ("select `id`,`value`,`grade`,`time` from `subject` order by `id` desc limit ".$limit.", 10") or die('{"status":"4"}');
		
		//获得数据
		$res = '';
		$num = 0;
		while($val = $db->iR($sql)){
			$val[2] = $this->get_grade($val[2]);
			$val[3] = date('Y-m-d H:i',$val[3]);
			$res .= '<div class=\"myitem theitem\"><div class=\"nid nodes\" nid=\"'.$val[0].'\">'.$val[0].'</div><div class=\"value nodes\">'.$val[1].'</div><div class=\"grade nodes\">'.$val[2].'</div><div class=\"time nodes\">'.$val[3].'</div><div class=\"opera nodes\"><a href=\"javascript:;\" onclick=\"del($(this))\">delete</a></div></div>';
		}
		//返回JSON数据
		echo '{"status":"0","msg":"'.$res.'"}';

	}

}

$new = new subjectmore();