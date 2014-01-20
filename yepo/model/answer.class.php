<?php

/*
* 回答模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class answer extends R{

	/*
	* 处理考试，接收前台提交的答案ID，进行匹配正确答案，返回答案
	*/

	function __construct(){
		//执行exec
		$this->exec();
	}

	private function exec(){

		//获得进行中的类型，考试还是复习
		if(!isset($_GET['opera']))$this->json(5);

		//检查答案ID
		if(!isset($_POST['id'])||!preg_match("/^[\d]+$/",$_POST['id']))$this->json(1);

		//因为在前台的排序是从0开始的，数据存储是从1开始，所以这里+1
		$correctanswer = $_POST['id']+1;

		//检查进行中的试题数组Session
		if(!isset($_SESSION['list_underway']))$this->json(2);

		$list = $_SESSION['list_underway'];

		//下一题ID
		$next = 0;
		//回答结果，1：正确，2：错误
		$res = 0;
		//获得试题量
		$count = count($list);
		//取得第一题-------------如果对数组Session list_underway结构不清楚，请参阅/yepo/model/underway.class.php的注释
		for($i = 0; $i < $count; $i++){
			//若该组的单元[2] 为0，代表这题没有回答过，抽取此题
			if($list[$i][2] == 0){
				//取得第一题ID号并跳出循环
				if($next == 0){
					$next = $list[$i][0];
					break;
				}
			}
		}

		//使用数据库
		$db = $this->MySQL();

		//检测是否登录并获得用户姓名
		if(!$name = $this->checkOnline($db))$this->json(3);

		//检测查询数据库是否出错，成立则报错并停止下行
		if(!$sql = $db->iQ("select `questions`.`correctanswer` from questions where `id` = $next"))$this->json(4);

		//检查查询结果，获得正确答案
		if(!is_array($arr = $db->iR($sql)))$this->json(5);

		//若id等于9，代表是回答超时，默认回答错误
		if($_POST['id'] == 9){
			$res = 2;
		}else{
			//判断回答
			$res = $arr[0] == $correctanswer ? 1 : 2;
		}

		//检测是否为考试，是：记录考试log
		if($_GET['opera'] == 'exam')if(!$db->iQ("insert into `examlog`(`id`,`qid`,`uid`,`result`,`time`)values(NULL,$next,'".$_SESSION['online'][0]."',$res,'".time()."')"))$this->json(6);

		//记录已回答
		for($i = 0; $i < $count; $i++){
			//记录该题已回答，跳出循环
			if($list[$i][2] == 0){
				$_SESSION['list_underway'][$i][2] = $res;
				break;
			}
			
		}

		//返回结果
		echo'{"status":"0","res":"'.$res.'","correct":'.$arr[0].'}';

	}

}

$new = new answer();