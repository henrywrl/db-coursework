<?php

/*
* 管理登出
*/

class logout{
	
	static function exec(){
		//销毁session，回到登录页
		session_destroy();
		header("Location: /admin");
	}

}

logout::exec();