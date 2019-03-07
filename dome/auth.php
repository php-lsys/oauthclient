<?php
use LSYS\OauthClient;
use LSYS\OauthClient\Storage\Redis;
include __DIR__."/Bootstarp.php";
session_start();
//登录完回调
$type="qq";//类型,跟配置的KEY对应
//login
$redirect_uri="http://safe.iwantido.cn/loauth/dome/auth.php";//登录完成回调地址
$redirect_uri=OauthClient::createRedirectUri("ref",$redirect_uri);//ref 为页面传递过来登录完成时返回地址$_GET变量的KEY
try{
	$client=$oauth->getDriver($type)->getClient($redirect_uri);//获取客户端对象
	$user=$client->getUser();
	$utoken=md5(json_encode($user));//得到用户标识,可以用用户ID
	var_dump($client->expires());//客户端剩余有效时间
}catch (\LSYS\OauthClient\Exception $e){
	print_r($e->getMessage());
	var_dump($e->needLogin());
	if ($e->needLogin()){
		//to login page
	}
	exit;
}


$_SESSION['utoken']=$utoken;

//示例:序列化保存,下次不需要登录即可调用接口,根据需要保存到数据库,缓存或其他
//KEY可以是对应的用户ID
$storage= new Redis();
$storage->set($_SESSION['utoken'], $client);

?>
<a href="storage.php">本地存储ACCESS</a>
