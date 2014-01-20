<?php

/*
* 获得试题模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class getquestion extends R{

	/*
	* 获得试题
	*/

	function __construct(){
		//执行exec
		$this->exec();
	}

	private function exec(){

		//获得进行中的类型，考试还是复习
		if(!isset($_GET['opera']))$this->json(5);

		//获得试题list session
		if(!isset($_SESSION['list_underway']))$this->json(1);
		$list = $_SESSION['list_underway'];

		//已回答
		$answered = 0;
		//下一题ID
		$next = 0;
		//回答正确
		$right = 0;
		//回答错误
		$wrong = 0;
		//试题时间限制
		$timelimit = 0;
		//原试题时间限制
		$stimelimit = 0;
		//获得试题总数
		$count = count($list);

		//若未注册session surplustime 则注册
		//计算该试题的剩余限制时间，array(试题ID号，剩余时间)
		if(!isset($_SESSION['surplustime']))$_SESSION['surplustime'] = array(0,0);

		//
		for($i = 0; $i < $count; $i++){

			//list若干二维数组项的三维数组[2]单元不等0时，进入计算答题结果
			if($list[$i][2]){

				//该单元为 1 ，递加正确
				if($list[$i][2] == 1){
					$right++;
					//获得已答数量
					$answered++;
				}elseif($list[$i][2] == 2){//该单元为 2 ，递加错误
					$wrong++;
					//获得已答数量
					$answered++;
				}

			}else{//三维数组[2]单元为0时，抽取该题目

				//若下一题值为0，则将该项设为下一题
				if($next == 0){

					//获得题目时间限制
					$timelimit = $list[$i][1];
					//下一题ID
					$next = $list[$i][0];

				}

			}

		}

		$stimelimit = $timelimit;

		//若在这里检测下一题的值仍为0时，代表没有下一题，即结束
		if($next == 0){

			//注册结束session
			$_SESSION['underwayend'] = 1;
			//显示结束结果
			exit('{"status":"0","end":"1","answered":"'.$answered.'","right":"'.$right.'","wrong":"'.$wrong.'"}');

		}

		//使用数据库
		$db = $this->MySQL();

		//检测是否登录并获得用户姓名
		if(!$name = $this->checkOnline($db))$this->json(2);

		//检测查询数据库是否出错，成立则报错并停止下行
		if(!$sql = $db->iQ("select `questions`.`topic`,`questions`.`correctanswer`,`answers`.`value` from questions, answers where answers.pid = questions.id and questions.id = $next"))$this->json(3);

		//题目
		$topic = '';
		//正确答案
		$correctanswer = 0;
		//答案列
		$answers = '';
		while($arr = $db->iR($sql)){

			//获得题目
			$topic = $arr[0];
			//获得正确答案
			$correctanswer = $arr[1];
			//JSON数据方式储存答案
			$answers .= '{"item":"'.$arr[2].'"},';

		}

		//去除字符串最后的逗号
		$answers = substr($answers,0,strlen($answers)-1);

		//若session surplustime [0]单元值不等于 下一题ID，则更新 surplustime；
		if($_SESSION['surplustime'][0] != $next){

			//[0]单元为该题目ID，[1]单元储存题目答题时间限制
			$_SESSION['surplustime'] = array($next,(time()+$timelimit));
		}

		//计算剩余时间
		$timelimit = $_SESSION['surplustime'][1] - time();

		//剩余时间最小值为0
		if($timelimit < 1)$timelimit = 0;

		//该题目是否已回答过，0代表未回答，其他代表已回答过
		$isAnswered = 0;
			
		//检测该试题是否已经答案过
		if(!$sql = $db->iQ("select `examlog`.`result`,`questions`.`correctanswer` from `examlog`,`questions` where `examlog`.`qid` = $next and `questions`.`id` = `examlog`.`qid` and `examlog`.`uid` = '".$_SESSION['online'][0]."'"))$this->json(4);
		if(is_array($result = $db->iR($sql))){
			
			//已回答过
			$isAnswered = $result[1];

		}

		if($_GET['opera'] == 'exam'){
			//若类型为 exam 
			if($isAnswered){
				//记录已回答
			for($i = 0; $i < $count; $i++){
				//记录该题已回答，跳出循环
				if($list[$i][2] == 0){
					$_SESSION['list_underway'][$i][2] = $result[0];
					break;
				}
			}
			}
		}elseif($_GET['opera'] == 'review'){
			//若类型为 review ，则检查是否回答过，未回答过的禁止复习
			if(!$isAnswered){
				//注空答案
				$answers = '';
				//记录未回答
				for($i = 0; $i < $count; $i++){
				    //记录该题未回答，跳出循环
					if($list[$i][2] == 0){
						$_SESSION['list_underway'][$i][2] = 3;
						break;
					}
				}
			}
			$isAnswered = 0;
		}else $this->json(6);//类型不正确

		//已回答的JSON数据片段
		$isAnswered = ',"isAnswered":'.$isAnswered;

		//输出结果，JSON
		echo '{"status":"0","answered":"'.$answered.'","stimelimit":"'.$stimelimit.'","timelimit":"'.$timelimit.'","right":"'.$right.'","wrong":"'.$wrong.'","topic":"'.$topic.'","answers":['.$answers.']'.$isAnswered.'}';

	}

}

$new = new getquestion();