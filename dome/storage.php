<?php
use LSYS\OauthClient\Storage\File;
include __DIR__."/Bootstarp.php";
session_start();
if (!isset($_SESSION['utoken']))die();
$type="qq";

$storage=new File();
//KEY可以是对应的用户ID
$client=$storage->find($_SESSION['utoken'],$type);
if (!$client){
	die('not access');
}

print_r($client->getUser());
