<?php
namespace LSYS\OauthClient\Client;
use LSYS\OauthClient\Exception;
use function LSYS\OauthClient\__douban as __;

class Douban extends \LSYS\OauthClient\Client {
	public function refreshToken(){
		throw new Exception(__("not support this method"));
	}
	/**
	 * @var \DoubanOauth
	 */
	protected $_client;
	/**
	 * @return \DoubanOauth
	 */
	protected function _client(){
		if ($this->_client) return $this->_client;
		$key=$this->_config->get("key");
		$secret=$this->_config->get("secret");
		$scope=$this->_config->get("scope","super_msg");
		$appConfig = array(
				'client_id' => $key,
				'secret' =>$secret,
				'redirect_uri' => $redirect_uri,
				'scope' => $scope,
				'need_permission' => true
		);
		include_once __DIR__.'/../../../../libs/douban/src/DoubanOauth.php';
		$this->_client=new \DoubanOauth($appConfig);;
		$this->_client->setAccessToken($this->_access_token['access_token']);
		return $this->_client;
	}
	/**
	 * oaut call
	 * @param string $api
	 * @param array $param
	 * @return mixed
	 */
	public function call($api,array $param=array()){
		$client=$this->_client();
		$res=$client->api($api,$param)->makeRequest();
		$data=json_decode($res,true);
		if(isset($data['msg'])){
			throw new Exception($data['msg'],$data['code']);
		}
		return $data;
	}
	/**
	 * get user info
	 * @return array
	 */
	public function getUser(){
		return $this->call('User.me.GET');
// 		'name');//显示名
// 		'id');
// 		'avatar');
// 		'alt');
	}

}
