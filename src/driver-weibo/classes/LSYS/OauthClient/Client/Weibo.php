<?php
namespace LSYS\OauthClient\Client;
use LSYS\OauthClient\Exception;

class Weibo extends \LSYS\OauthClient\Client {
	public function refreshToken(){
		throw new Exception("not support this method");
	}
		/**
	 * @return \SaeTClientV2
	 */
	protected function _client(){
		static $client;
		if (!$client){
			$WB_AKEY=$this->_config->get("key");
			$WB_SKEY=$this->_config->get("secret");
			include_once __DIR__.'/../../../../libs/sina/saetv2.ex.class.php';
			$client = new \SaeTClientV2(
					$WB_AKEY,
					$WB_SKEY,
					$this->_access_token['access_token']
			);
		}
		return $client;
	}
	/**
	 * oaut call
	 * @param string $api
	 * @param array $param
	 * @return mixed
	 */
	public function call($api,array $param=array()){
		$client=$this->_client();
		call_user_func_array(array($client,$api), $param);
	}
	/**
	 * get user info
	 * @return array
	 */
	public function getUser(){
		$api=$this->_client();
		$uid_get = $api->get_uid();
		if (!isset($uid_get['uid'])){
			throw new Exception("error:".json_encode($uid_get));
		}
		$user_message = $api->show_user_by_id($uid_get['uid']);//根据ID获取用户等基本信息
		if (!isset($user_message['idstr'])) throw new Exception("error:".json_encode($user_message)); 
		return $user_message;
// 		 'screen_name');//显示名
// 		'name');
// 		 'idstr');
// 		'profile_url');
// 		'avatar_hd');
// 		'gender');//m?"男":"女",
	}

}
