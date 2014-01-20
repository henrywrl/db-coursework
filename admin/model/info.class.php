<?php

/*
* 系统概况模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class info extends R{

	function __construct(){
		//执行exec
		$this->exec();
	}

	private function exec(){

		//检测是否登录
		if(!$this->checkOnlineR())exit('<meta charset="utf-8"><script>window.parent.out(1);</script>');

		//使用数据库
		$db = $this->MySQL();

		//用户状况
		$sql = $db->iQ("select `grade` from `user`") or die('System error 1');
		$userParm = array(0,0,0,0);
		while($arr = $db->iR($sql)){
			switch($arr[0]){
				case 1: $userParm[0]++;break;
				case 2: $userParm[1]++;break;
				case 3: $userParm[2]++;break;
				case 4: $userParm[3]++;break;
			}
		}

		//科目状况
		$sql = $db->iQ("select `grade` from `subject`") or die('System error 2');
		$subjectParm = array(0,0,0,0);
		while($arr = $db->iR($sql)){
			switch($arr[0]){
				case 1: $subjectParm[0]++;break;
				case 2: $subjectParm[1]++;break;
				case 3: $subjectParm[2]++;break;
				case 4: $subjectParm[3]++;break;
			}
		}

		//试题状况
		$sql = $db->iQ("select questions.id,subject.grade from questions, subject where questions.by = subject.id") or die('System error 3');
		$questionParm = array(0,0,0,0);
		while($arr = $db->iR($sql)){
			switch($arr[1]){
				case 1: $questionParm[0]++;break;
				case 2: $questionParm[1]++;break;
				case 3: $questionParm[2]++;break;
				case 4: $questionParm[3]++;break;
			}
		}
	
		include_once ADPATH.'static/page/infoPage.php';
		infoPage($userParm,$subjectParm,$questionParm);
		
	}

}

$new = new info();