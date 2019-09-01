<?php
use LSYS\Config\File;
use LSYS\OauthClient;
use LSYS\OauthClient\Driver\QQ;
use LSYS\OauthClient\Driver\Baidu;
use LSYS\OauthClient\Driver\Wechat;
include_once __DIR__."/../vendor/autoload.php";
File::dirs(array(
	__DIR__."/config",
));
$oauth=new OauthClient();
$oauth->addDriver("qq", new QQ(\LSYS\Config\DI::get()->config("oauthclient.qq")));
$oauth->addDriver("baidu", new Baidu(\LSYS\Config\DI::get()->config("oauthclient.baidu")));
$oauth->addDriver("wechat", new Wechat(\LSYS\Config\DI::get()->config("wechat_mp.dome")));
