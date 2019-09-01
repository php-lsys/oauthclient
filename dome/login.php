<?php
use LSYS\OauthClient;
include __DIR__."/Bootstarp.php";
if (isset($_GET['type'])){
	$url="http://safe.iwantido.cn/loauth/dome/auth.php";//登录完成回调地址
	$url=OauthClient::createRedirectUri("ref",$url);//ref 为页面传递过来登录完成时返回地址$_GET变量的KEY
	$d=$oauth->getDriver($_GET['type'])->authorize($url);
	$d->go();
}

foreach ($oauth->listDriver(OauthClient::TERMINAL_WECHAT) as $k=>$_){
    echo "<a href='?type={$k}'>{$k}</a><br>";
}
