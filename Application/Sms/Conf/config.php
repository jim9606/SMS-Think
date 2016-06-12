<?php
return array(
	//'配置项'=>'配置值'
	/*
	'DB_TYPE'=>'mysql',// 数据库类型
	'DB_NAME'=>'app_scutjimsms',// 数据库名
	'DB_HOST'=>'127.0.0.1',// 服务器地址
	'DB_USER'=>'root',// 用户名
	'DB_PWD'=>'',// 密码
	'DB_PORT'=>3306,// 端口
	*/
	'DB_PREFIX'=>'',// 数据库表前缀
	'DB_CHARSET'=>'utf8',// 数据库字符集
	
	'DEFAULT_CONTROLLER'=>'User',
	'DEFAULT_ACTION'=>'auth',
	
	'USER_DEFAULT_PASSWORD'=>'123456',
	'USER_PERMISSIONS'=> array(
			'anon'   =>array('read'=>0,'admin'=>0,'score'=>0),
			'admin'  =>array('read'=>1,'admin'=>1,'score'=>0),
			'teacher'=>array('read'=>1,'admin'=>0,'score'=>1),
			'student'=>array('read'=>1,'admin'=>0,'score'=>0),
	),
		
	'PERMISSION_CONTROL'=>false, //True for release,check permissions for API
	
	'MSG_API_PERMISSION_DENIED'=>'Forbidden',
	'MSG_API_INVALID_METHOD'=>'Invalid method'
);