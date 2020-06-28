<?php
namespace LSYS\OauthClient\Client;
use LSYS\OauthClient\Exception;
use function LSYS\OauthClient\__qq as __;

class QQ extends \LSYS\OauthClient\Client {
	public function refreshToken(){
		throw new Exception(__("not support this method"));
	}
	/**
	 * oaut call
	 * @param string $api
	 * @param array $param
	 * @return mixed
	 */
	public function call($api,array $param=array()){
		throw new Exception(__("not support this method"));
	}
	/**
	 * get user info
	 * @return array
	 */
	public function getUser():array{
		$appid=$this->_config->get("appid");
		$scope=$this->_config->get("scope","super_msg");
		$qq_access_token=$this->_access_token['access_token'];
		$openid=$this->_access_token['openid'];
		$get_user_info = "https://graph.qq.com/user/get_user_info?"
					. "access_token=" . $qq_access_token
					. "&oauth_consumer_key=" .$appid
					. "&openid=" .$openid
					. "&format=json";
		$info = file_get_contents($get_user_info);
		$data = json_decode($info, true);
		if($data['msg']&&$data['ret']!=0){
			throw new Exception($data['msg'],false,$data['ret']);
		}
		$data['openid']=$this->_access_token['openid'];
		return $data;
// 		'nickname');//显示名
// 		'figureurl_qq_2');
// 		'gender');
// 		$openid;
	}
}
