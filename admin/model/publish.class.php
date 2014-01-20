<?php

/*
* 发布试题模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ADPATH')||!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class publish extends R{

	/*
	* 发布试题
	*/

	function __construct(){
		//执行exec
		$this->exec();
	}

	private function exec(){
		
		//检测是否登录
		if(!$this->checkOnlineR())exit('<meta charset="utf-8"><script>window.parent.out(1);</script>');

		//使用数据库
		$db = $this->MySQL();

		//检测查询数据库是否出错，成立则报错并停止下行
		$sql = $db->iQ("select `id`,`value`,`grade` from `subject`") or die('System error: 01');

		//科目
		$subjectArr = '';

		//
		$insertSuc = 0;

		//获得全年级、科目数
		while($arr = $db->iR($sql)){
			$subjectArr[] = $arr;
		}

		//若未有科目，提示先添加
		if(!$subjectArr)exit('<meta charset="utf-8"><script>alert("No subject, please add");location.href="subject";</script>');

		//若存在提交，则进行发布步骤
		if(isset($_POST['parm'])){

			//计算POST的单元数
			$count = count($_POST['parm']);

			//检查单元数是否通过
			if($count > 7 && $count < 12){

				/*
				*
				* $_POST['parm'] 是一个二维数组
				* 以下解析单元含义
				* 0：题目
				* 1：正确答案编号
				* 2：科目ID号
				* 3：所有权
				* 4-(6,9)：按答案量实际情况，量在3则4-6单元均为答案，最大量为6，则4-9单元均为答案
				* 最后一个单元：时间限制
				*/

				//声明error数组，储存各步骤的检测情况
				$error = array();
				
				//获得题目并除去和替换特别字符
				$topic = self::replaceStr(trim($_POST['parm'][0]));
				if($topic == '')exit('Error: Title is empty');
				//题目字符截取1000个
				$topic = mb_substr($topic,0,1000,'utf-8');

				//获得正确答案编号并检测
				$correctanswer = trim($_POST['parm'][1]);
				if($correctanswer == '')exit('correctanswer is empty');
				if(!preg_match("/^[1-6]{1}$/",$correctanswer))exit('correctanswer`s Value must be a number of 1-6');				

				//获得科目ID并检查
				$subject = trim($_POST['parm'][2]);
				if($subject == '')exit('subject is empty');
				if(!preg_match("/^[\d]{1,5}$/",$subject))exit('subject`s Value must be a number of 10');

				//获得所有权
				$ownership = trim($_POST['parm'][3]);
				if($ownership == '')exit('ownership is empty');
				//0：私有，1：公共
				if($ownership == 0){}elseif($ownership == 1){}else exit('ownership`s value must be a number 0 or 1');

				//获得试题时间限制并检查，最后一个单元
				$timelimit = trim($_POST['parm'][($count-1)]);
				//若空值，代表无限制
				if(!$timelimit)$timelimit = 0;
				else{
					if(!preg_match("/^[\d]{1,5}$/",$timelimit))exit('timelimit`s Value must be a number of 1-5');
					//允许在10秒到86400秒内，即最大值是一天
					if($timelimit > 9 && $timelimit < 86401){}else exit('if timelimit`s value is not equal to 0, timelimit`s Value must be greater than 10 and less than 86400');
				}

				$time = time();

				//插入试题
				$sql = $db->iQ("insert into `questions`(`id`,`topic`,`correctanswer`,`by`,`timelimit`,`ownership`,`time`)values(NULL,'".mysql_real_escape_string($topic)."',$correctanswer, $subject, $timelimit, $ownership, $time)") or die('System error: 02'.mysql_error());
				//获得试题ID号
				$id = $db->iD();

				//初始化
				//答案SQL
				$answer = '';
				//获得答案
				$answerData = '';

				//设定答案的单元量在总单元量上1个，即舍去最后一个单元
				$answerLength = $count-1;
				//答案单元从第4个开始
				$i = 4;
				for($i; $i < $answerLength; $i++){
					//获得答案数据并出去和替换特别字符
					$answerData = self::replaceStr(trim($_POST['parm'][$i]));
					//检查答案数据
					if($answerData == ''){
						$db->iQ("delete from `questions` where `id` = $id");
						exit('Error: Answer '.$i.' is empty');
					}else{
						//拼接SQL
						$answer .= "(NULL, $id, '".mysql_real_escape_string(mb_substr($answerData,0,200,'utf-8'))."', ".($i-4)."),";
					}
				}
				//除去最后的逗号
				$answer = substr($answer,0,strlen($answer)-1);

				//插入答案
				if(!$sql = $db->iQ("insert into `answers`(`id`,`pid`,`value`,`sort`)values".$answer)){
					$db->iQ("delete from `questions` where `id` = $id");
					exit('System error: 03');
				}

				//反馈成功
				$insertSuc = 1;

			}

		}

		self::show($subjectArr,$insertSuc);

	}

	//除去或替换字符
	static function replaceStr($str){
		$reg[0] = "\n";
		$reg[1] = "<";
		$reg[2] = ">";
		$reg[3] = "\\";
		$reg[4] = "\"";
		$exc[4] = "";
		$exc[3] = "&lt;";
		$exc[2] = "&gt;";
		$exc[1] = "";
		$exc[0] = "\\\"";
		return str_replace($reg,$exc,$str);
	}

	//显示页面
	static function show($subjectArr,$insertSuc){
		require_once ADPATH.'static/page/publishPage.php';
		publishPage($subjectArr,$insertSuc);
		exit;
	}

}

$new = new publish();