<?php

/*
* 用户中心模型
*/

//检测是否定义根路径
defined('ROOTPATH') or die("You don't have permission to access the path on this server.");
//包含R文件
require_once ROOTPATH.'yepo/R.php';

class users extends R{

	/*
	* 显示用户中心
	*/

	function __construct(){
		//执行exec方法
		$this->exec();
	}

	private function exec(){
		
		//检测是否已离线，成立则跳转
		if(!$this->checkOnline(0)){
			exit('<meta charset="utf-8"><script>alert("You are offline, please login");location.href="/";</script>');
		}

		//声明error数组，储存各步骤的检测情况
		$error = array();

		//声明变量id并赋值，该变量为会员ID号
		$id = $_SESSION['online'][0];

		//使用数据库
		$db = $this->MySQL();
		
		//检测查询数据库是否出错，成立则报错并停止下行
		$sql = $db->iQ("select `mail`,`name`,`age`,`gender`,`grade` from `user` where `id` = '$id'") or die("System error 2");
		
		//检测用户是否存在，成立则停止下行并显示反馈
		is_array($fileArr = $db->iR($sql)) or die("System error 3");

		/*
		* 检测是否设置由POST提交的变量
		* 姓名 $_POST['user']
		* 年龄 $_POST['age']
		* 性别 $_POST['gender']
		* 年级 $_POST['grade']
		* 若成立则进行修改资料步骤
		*/
		if(isset($_POST['user'])&&isset($_POST['age'])&&isset($_POST['gender'])&&isset($_POST['grade'])){

			//去除两端空格并重新声明变量、赋值
			$user = trim($_POST['user']);
			$age = trim($_POST['age']);
			$gender = trim($_POST['gender']);
			$grade = trim($_POST['grade']);

			//检测姓名是否空值
			if($user == ''){
				$error['user'] = 1;
			}else{
				//检测姓名长度
				if(mb_strlen($user) < 2)$error['user'] = 2;
				//截取
				$user = mb_substr($user,0,16,'utf-8');
				//替换特殊符号
				$user = $this->escapeStr($user);
				//重置 user POST值
				$_POST['user'] = $user;
			}

			//检测年龄是否空值
			if($age == ''){
				$error['age'] = 1;
			}else{
				//检测年龄格式
				if(!preg_match("/^[\d]+$/",$age))exit('System error 4');
				//检测年龄范围
				if($age > 11 && $age < 81){}else exit('System error 5');
			}

			//检测性别是否空值
			if($gender == ''){
				$error['gender'] = 1;
			}else{
				//检测性别
				if($gender == 'Male'){
					$gender = 1;
				}elseif($gender == 'Female'){
					$gender = 2;
				}else exit('System erorr 6');
			}

			//检测年级是否空值
			if($grade == ''){
				$error['grade'] = 1;
			}else{
				//检测年级
				print_r($_POST);
				switch($grade){
					case 'One': $grade = 1; break;
					case 'Two': $grade = 2; break;
					case 'Three': $grade = 3; break;
					case 'Four': $grade = 4; break;
					default:exit('System error 7');
				}
			}

			//检测error数组单元是否=0，成立则更新用户资料
			if(count($error) == 0){

				//更新用户阶级为2
				if(!$db->iQ("update `user` set `name` = '".mysql_real_escape_string($user)."', `age` = $age, `gender` = $gender, `grade` = $grade where `id` = $id")){
					exit('System error 8');
				}

				//重置用户基本资料数组
				$fileArr = array($fileArr[0],$user,$age,$gender,$grade);
				//设置修改成功标记
				$error['suc'] = 1;

			}

		}

		//获得性别
		$fileArr[3] = $this->get_gender($fileArr[3]);

		//获得性别
		$fileArr[4] = $this->get_grade($fileArr[4]);
		
		//包含usersPage.php文件
		require_once ROOTPATH.'static/page/usersPage.php';
		//显示页面
		usersPage($fileArr,$error);
	}


}

$new = new users();