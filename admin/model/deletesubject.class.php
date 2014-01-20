<?php

/*
* 删除科目模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class deletesubject extends R{

	/*
	* 删除科目
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
		* nid 科目id
		*/
		if(!isset($_POST['id']))$this->json(2);

		//去除两端空格并重新声明变量、赋值
		$id = trim($_POST['id']);

		//检测id是否为数字
		if(!preg_match("/^[\d]+$/",$id))$this->json(3);

		//使用数据库
		$db = $this->MySQL();

		//检测查询数据库是否出错，成立则报错并停止下行
		if(!$sql = $db->iQ("select `by` from `questions` where `by` = '".$id."'"))$this->json(4);
		//检查该科目下是否存在试题
		if(mysql_num_rows($sql))$this->json(5);

		//执行删除
		if(!$db->iQ("delete from `subject` where `id` = $id"))$this->json(6);

		$this->json(0);

	}

}

$new = new deletesubject();