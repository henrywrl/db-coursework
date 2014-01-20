<?php

/*
* 获得答案
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class getanswers extends R{

	/*
	* 获得某试题的答案
	*/

	function __construct(){
		//检测是否登录
		$this->exec();
	}

	private function exec(){
		
		//检测是否登录
		if(!$this->checkOnlineR())$this->json(1);

		/*
		* 检测必须值是否设置
		* nid 试题id
		*/
		if(!isset($_POST['id']))$this->json(2);

		//去除两端空格并重新声明变量、赋值
		$id = trim($_POST['id']);

		//检测id是否为数字
		if(!preg_match("/^[\d]+$/",$id))$this->json(3);

		//使用数据库
		$db = $this->MySQL();

		//检测查询数据库是否出错，成立则报错并停止下行
		if(!$sql = $db->iQ("select `value` from `answers` where `pid` = $id order by `sort`"))$this->json(4);

		//获得数据
		$res = '';
		while($arr = $db->iR($sql)){
			$res .= '{"node":"'.$arr[0].'"},';
		}

		//去除最后的逗号
		$res = substr($res,0,strlen($res)-1);

		echo '{"status":"0","msg":['.$res.']}';

	}

}

$new = new getanswers();