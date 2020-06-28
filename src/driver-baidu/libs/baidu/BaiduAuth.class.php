<?php
$_dir=dirname(__FILE__);
include_once($_dir.'/Auth.class.php');
class BaiduAuth extends BAuth{
    public function getLoginUrl($callback,$scope,$state){
        $url = 'https://openapi.baidu.com/oauth/2.0/authorize';
        $params = array(
            'client_id'=>$this->appid,
            'response_type'=>'code',
            'redirect_uri'=>$callback,
        	'scope'=>$scope,
        	'state'=>$state
        );
        return $url.'?'.$this->toUrlString($params);
    }

    public function getAccessToken($code,$callback){
        $url = 'https://openapi.baidu.com/oauth/2.0/token';
        $params = array(
            'client_id'=>$this->appid,
            'client_secret'=>$this->appkey,
            'grant_type'=>'authorization_code',
            'code'=>$code,
            'redirect_uri'=>$callback,
        );
        return  $this->https($url,$params);
    }

    public function getOpenId($access_token){
        $url = 'https://openapi.baidu.com/rest/2.0/passport/users/getLoggedInUser';
        $params = array(
            'access_token'=>$access_token,
        );
        $openid = $this->https($url,$params);
        $arr = json_decode($openid,true);
        if(!empty($arr['uid'])){
            return $arr['uid'];
        }
        return false;
    }
    public function getUserInfo($access_token,$uid,$fields){
    	$url = 'https://openapi.baidu.com/rest/2.0/passport/users/getInfo';
    	$params = array(
    		'uid'=>$uid,
    		'fields'=>implode(",",$fields),
    		'access_token'=>$access_token,
    	);
    	return  $this->https($url,$params);
    }
    
    public function getUserid($access_token){
    	$url = 'https://openapi.baidu.com/rest/2.0/passport/users/getLoggedInUser';
    	$params = array(
    		'access_token'=>$access_token,
    	);
    	return  $this->https($url,$params);
    }

}
