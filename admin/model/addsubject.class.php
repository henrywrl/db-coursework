<?php

/*
* 增加科目模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class addsubject extends R{

	/*
	* 增加科目
	*/

	function __construct(){
		//检测是否登录
		$this->exec();
	}

	private function exec(){

		//检测是否登录
		if(!$this->checkOnlineR())$this->json(1);

		/*
		* s检测必须值是否设置
		* value 科目名称
		* grade 科目所属
		*/
		if(!isset($_POST['value'])||!isset($_POST['grade']))$this->json(2);

		//去除两端空格并重新声明变量、赋值
		$value = trim($_POST['value']);
		$grade = trim($_POST['grade']);

		//检测二项是否为空值
		if($value == '' || $grade == '')$this->json(3);

		//替换特殊符号
		$value = $this->escapeStr($value);
		//截取
		$value = mb_substr($value,0,20,'utf-8');

		//检测年级
		if(!$this->get_grade($grade))$this->json(4);

		//使用数据库
		$db = $this->MySQL();

		//检测查询数据库是否出错，成立则报错并停止下行
		if(!$sql = $db->iQ("select `value` from `subject` where `value` = '$value' and `grade` = '$grade'"))$this->json(5);
		
		//检测该科目在本年级是否存在，成立则报错并停止下行
		if(mysql_num_rows($sql))$this->json(6);

		//插入科目
		if(!$db->iQ("insert into `subject`(`id`,`value`,`grade`,`time`)values(NULL,'".mysql_real_escape_string($value)."','$grade','".time()."')"))$this->json(7);
		$this->json(0);

	}

}

$new = new addsubject();