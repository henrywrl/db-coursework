<?php

/*
* 更多的试题，模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class questionmore extends R{

	/*
	* 加载更多的试题数据
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
		$sql = $db->iQ("select questions.*,subject.* from questions, subject where questions.by = subject.id order by questions.id desc limit ".$limit.", 10") or die('{"status":"4"}');
		
		//获得数据
		$res = '';
		$num = 0;
		while($val = $db->iR($sql)){
			$val[3] = $this->get_log('qid',$val[0],$db);
			$val[5] = $this->get_own($val[5]);
			$val[9] = $this->get_grade($val[9]);
			$res .= '<div class=\"myitem theitem\"><div class=\"nid nodes\" nid=\"'.$val[0].'\">'.$val[0].'</div><div class=\"topic nodes\">'.$val[1].'</div><div class=\"correctanswer nodes\">The <b>'.$val[2].'</b></div><div class=\"subject nodes\">'.$val[8].'</div><div class=\"grade nodes\">'.$val[9].'</div><div class=\"timelimit nodes\">'.$val[4].'</div><div class=\"ownership nodes\">'.$val[5].'</div><div class=\"time nodes\">'.date('m-d-Y',$val[6]).'</div><div class=\"participate nodes\">'.$val[3][0].'</div><div class=\"rate nodes\">'.$val[3][1].'%</div><div class=\"opera nodes\"><a href=\"javascript:;\" onclick=\"get_answers($(this))\" title=\"View the answers\" class=\"va\"></a><a href=\"javascript:;\" title=\"delete\" onclick=\"del($(this))\" class=\"delete\"></a></div></div>';
		}
		//返回JSON数据
		echo '{"status":"0","msg":"'.$res.'"}';

	}

}

$new = new questionmore();