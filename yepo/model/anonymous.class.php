<?php

/*
* 选择动向模型
*/

//检测是否定义根路径
defined('ROOTPATH') or die("You don't have permission to access the path on this server.");

//包含R文件
require_once ROOTPATH.'yepo/R.php';

class anonymous extends R{

	/*
	* 选择动向
	*/

	function __construct(){
		//执行exec方法
		$this->exec();
	}

	private function exec(){

		//包含indexPage.php文件
		require_once ROOTPATH.'static/page/anonymousPage.php';
		//执行indexPage方法
		anonymousPage();
		
	}

}

$new = new anonymous();