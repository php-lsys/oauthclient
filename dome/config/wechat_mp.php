<?php
//微信公众号配置文件,如果你不想写到文件里,实现CONFIG接口即可
//或者使用 LSYS/Config 存储方式
return array(
	"dome"=>
		array(
		"app_id"=>'wxa3f2202198fde906',
		//access 用到
		"appkey_secret"=>'d8fbfe6912f602bdad41c5140f7a0326',
		//msg 用到
		"msg_token"=>'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
		"msg_aeskey"=>'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFG',
		//模板消息用到
		'tpl_map'=>array(
			'reg_dome'=>array(
				'template_id'=>'HUjOa2TnKf6Vn5s7E_j29GRTEU9MkW-jc686jwVI6xQ',
				'colors'=>array(
					'title'=>'#161511',
					'body'=>'#663399'
				)
			)
		)
	),
);