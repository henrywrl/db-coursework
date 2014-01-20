<?php

/*
* 匿名者获得试题模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class getquestiona extends R{

	/*
	* 处理考试
	*/

	function __construct(){
		//执行exec
		$this->exec();
	}

	private function exec(){

		//获得试题list session
		if(!isset($_SESSION['list_anonymous']))$this->json(1);
		$list = $_SESSION['list_anonymous'];

		//已回答
		$answered = 0;
		//下一题ID
		$next = 0;
		//回答正确
		$right = 0;
		//回答错误
		$wrong = 0;
		//获得试题总数
		$count = count($list);

		//
		for($i = 0; $i < $count; $i++){

			//list若干二维数组项的三维数组[2]单元不等0时，进入计算答题结果
			if($list[$i][2]){

				//该单元为 1 ，递加正确
				if($list[$i][2] == 1){
					$right++;
					$answered++;
				}elseif($list[$i][2] == 2){//该单元为 2 ，递加错误
					$wrong++;
					$answered++;
				}
				

			}else{//三维数组[2]单元为0时，抽取该题目

				//若下一题值为0，则将该项设为下一题
				if($next == 0){

					//下一题ID
					$next = $list[$i][0];

				}

			}

		}

		//若在这里检测下一题的值仍为0时，代表没有下一题，即结束
		if($next == 0){

			//注册结束session
			$_SESSION['anonymousend'] = 1;
			//显示结束结果
			exit('{"status":"0","end":"1","answered":"'.$answered.'","right":"'.$right.'","wrong":"'.$wrong.'"}');

		}

		//使用数据库
		$db = $this->MySQL();

		//检测查询数据库是否出错，成立则报错并停止下行
		if(!$sql = $db->iQ("select `questions`.`topic`,`questions`.`correctanswer`,`answers`.`value` from questions, answers where answers.pid = questions.id and questions.id = $next order by `sort`"))$this->json(3);

		//题目
		$topic = '';
		//正确答案
		$correctanswer = 0;

		//答案列
		$answers = '';
		while($arr = $db->iR($sql)){

			//获得正确答案
			$correctanswer = $arr[1];

			//检查该ip地址+试题ID是否在anonylog表存在
			if(!$_sql = $db->iQ("select `id` from `anonylog` where `ip` = '".$this->ip()."' and `qid` = $next"))$this->json(5);
			if(is_array($db->iR($_sql))){

				$topic = '<span style=\"color:red\">You\'ve answered the questions</span>';
				//记录已回答
				for($i = 0; $i < $count; $i++){
					//记录该题已回答，跳出循环
					if($list[$i][2] == 0){
						$_SESSION['list_anonymous'][$i][2] = 3;
						break;
					}

				}
			}else{
				//获得题目
				$topic = $arr[0];
				//JSON数据方式储存答案
				$answers .= '{"item":"'.$arr[2].'"},';
			}
			
		}

		//去除字符串最后的逗号
		$answers = substr($answers,0,strlen($answers)-1);

		//输出结果，JSON
		echo '{"status":"0","answered":"'.$answered.'","right":"'.$right.'","wrong":"'.$wrong.'","topic":"'.$topic.'","answers":['.$answers.']}';

	}

}

$new = new getquestiona();