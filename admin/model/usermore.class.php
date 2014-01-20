<?php

/*
* 更多的学员管理，模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class usermore extends R{

	/*
	* 加载更多的学员数据
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
		$sql = $db->iQ("select `id`,`mail`,`name`,`age`,`gender`,`grade`,`class`,`time` from `user` order by `id` desc limit ".$limit.", 10") or die('{"status":"4"}');
		
		//获得数据
		$res = '';
		$opera = '';
		$score = '';
		while($val = $db->iR($sql)){
			$score = $this->get_log('uid',$val[0],$db);
			$opera = $this->get_opera_admin($val[6],1);
			$res .= '<div class=\"item\"><div class=\"num node\">'.$val[0].'</div><div class=\"acc node\">'.$val[1].'</div><div class=\"name node\">'.$val[2].'</div><div class=\"age node\">'.$val[3].'</div><div class=\"gender node\">'.$this->get_gender($val[4]).'</div><div class=\"grade node\">'.$this->get_grade($val[5]).'</div><div class=\"class node\">'.$opera[0].'</div><div class=\"participate node\">'.$score[0].'</div><div class=\"rate node\">'.$score[1].'%</div><div class=\"time node\"><em>'.date('Y-m-d H:i',$val[7]).'</em></div><div class=\"opera node\"><a href=\"javascript:;\" title=\"delete\" onclick=\"_delete($(this))\" class=\"delete\"></a>'.$opera[1].'</div></div>';
		}
		//返回JSON数据
		echo '{"status":"0","msg":"'.$res.'"}';

	}

}

$new = new usermore();