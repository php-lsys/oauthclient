<?php
$_dir=dirname(__FILE__);
include_once($_dir.'/Auth.class.php');
class TaobaoOauth extends TAuth{
    public function getLoginUrl($callback,$state){
        $url = 'https://oauth.taobao.com/authorize';
    //    $url = 'https://oauth.tbsandbox.com/authorize';
        $params = array(
            'client_id'=>$this->appid,
            'response_type'=>'code',
            'redirect_uri'=>$callback,
        	'state'=>$state
        );
        return $url.'?'.$this->toUrlString($params);
    }

    public function getAccessToken($code,$callback){
        $url = 'https://oauth.taobao.com/token';
      //  $url = 'https://oauth.tbsandbox.com/token';
        $params = array(
            'client_id'=>$this->appid,
            'client_secret'=>$this->appkey,
            'grant_type'=>'authorization_code',
            'code'=>$code,
            'redirect_uri'=>$callback,
        );
        return  $this->https($url,$params);
    }
}
