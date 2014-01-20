<?php

/*
* 进行模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class underway extends R{

	/*
	* 处理考试或者复习进行中
	*/

	function __construct(){
		//执行exec
		$this->exec();
	}

	private function exec(){

		//检查是否已结束
		if(isset($_SESSION['underwayend'])){
			header('Location: /');
			exit;
		}

		//检查必须值
		//opera：类型，opera2：年级，opera3：科目ID
		if(!isset($_GET['opera']) || !isset($_GET['opera2']) || !isset($_GET['opera3']))exit('Must not set values');

		$grade = $this->get_grade($_GET['opera2']) or die('Must value error');

		$subject = '';

		$id = $_GET['opera3'];

		//使用数据库
		$db = $this->MySQL();

		//检测是否登录并获得用户姓名
		if(!$name = $this->checkOnline($db))exit('<meta charset="utf-8"><script>alert("You are offline, please login");location.href="/";</script>');

		//如果未有注册list_underway
		if(!isset($_SESSION['list_underway'])){

			//检测查询数据库是否出错，成立则报错并停止下行
			$sql = $db->iQ("select `questions`.`id`,`questions`.`timelimit`,`subject`.`value` from questions, subject where questions.by = subject.id and questions.by = $id") or die('System error: 05, SQL Error');

			//获得数据
			$list = array();
			while($arr = $db->iR($sql)){
				$list[] = array($arr[0],$arr[1],0);
				$subject = $arr[2];
			}

			/*
			 * list_underway 结构
			 * 
			 list_underway =  array(

			     array(试题ID号；试题时间限制；记录回答，0代表未回答，1代表回答正确，2代表回答错误)
			     .....

			 )
			 */
			$_SESSION['list_underway'] = $list;

		}else{

			//检测查询数据库是否出错，成立则报错并停止下行
			$sql = $db->iQ("select `value` from subject where `id` = $id") or die('System error: 05, SQL Error');
			$subject = $db->iR($sql);
			//若注册了list_underway，则只需获得本次科目名称即可
			$subject = $subject[0];

		}

		//试题量
		$count = count($_SESSION['list_underway']);

		//显示页面
		require_once ROOTPATH.'static/page/underwayPage.php';
		underwayPage($name,$grade,$subject,$count);
		
	}

}

$new = new underway();