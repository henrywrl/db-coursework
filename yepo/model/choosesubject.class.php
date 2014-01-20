<?php

/*
* 科目模型
*/

//检测是否定义管理目录路径、根路径
if(!defined('ROOTPATH'))exit("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

//继承 R
class choosesubject extends R{

	/*
	* 处理显示科目
	*/

	function __construct(){
		//执行exec
		$this->exec();
	}

	private function exec(){

		//检查必须值，opera：类型(考试/复习)，opera2：年级
		if(!isset($_GET['opera']) || !isset($_GET['opera2']))exit('Must not set values');

		//操作类型
		$opera = '';
		if($_GET['opera'] == 'exam'){
		}elseif($_GET['opera'] == 'review'){
		}else exit('Opera value error');

		//首字母大写
		$opera = ucfirst($_GET['opera']);

		//获得年级
		$id = $_GET['opera2'];
		$grade = $this->get_grade($id) or die('Must value error');

		//使用数据库
		$db = $this->MySQL();

		//检测是否登录并获得用户姓名
		if(!$name = $this->checkOnline($db))exit('<meta charset="utf-8"><script>alert("You are offline, please login");location.href="/";</script>');

		//检测查询数据库是否出错，成立则报错并停止下行
		$sql = $db->iQ("SELECT `questions`.`timelimit` , `subject`.`id`, `subject`.`value` FROM `questions` , `subject` WHERE `questions`.`by` = `subject`.`id` AND `subject`.`grade` = $id") or die('error: 01');

		//获得数据

		//试题量,默认为1
		$_count = 1;

		//时间限制量
		$timelimit = 0;

		//科目列表
		$result = array();

		$sort = 0;

		while($arr = $db->iR($sql)){

			//赋值运算符 += (将左边的值加上右边的值赋值给左边)
			$timelimit = $arr[0];

			//获得排序号
			$sort = self::dealArray($result,$arr[2]);

			if(isset($result[$sort][1])){
				//试题量+1
				$_count = $result[$sort][1]+1;
				//时间限制加入
				$timelimit = $result[$sort][2]+$arr[0];
			}

			//array(科目名，试题量，时间限制量，科目ID)
			$result[$sort] = array($arr[2],$_count,$timelimit,$arr[1]);
			
		}
		
		//显示页面
		require_once ROOTPATH.'static/page/choosesubjectPage.php';
		choosesubjectPage($name,$grade,$opera,$result);
	}

	//检查试题的科目名是否重复，返回排序号
	static function dealArray($arr,$str){
		$count = count($arr);
		if(!$count){
			return 0;
		}else{
			//
			foreach($arr as $key => $value){
				if($value[0] == $str)return $key;
			}
			return $count;
		}
	}

}

$new = new choosesubject();