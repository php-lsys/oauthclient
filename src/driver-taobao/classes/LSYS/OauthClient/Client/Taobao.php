<?php
namespace LSYS\OauthClient\Client;
use LSYS\OauthClient\Exception;
use function LSYS\OauthClient\__taobao as __;

class Taobao extends \LSYS\OauthClient\Client {
	public function refreshToken(){
		throw new Exception(__("not support this method"));
	}
	/**
	 * @var \
	 */
	protected $_client;
	/**
	 * @return \TopClient
	 */
	protected function _client(){
		include_once __DIR__.'/../../../../libs/taobao/taobao.php';
		if ($this->_client) return $this->_client;
		$key=$this->_config->get("key");
		$secret=$this->_config->get("secret");
		$c = new \TopClient;
		$c->appkey = $key;
		$c->secretKey = $secret;
		$c->format= 'json';
		$this->_client=$c;
		return $this->_client;
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
		$c=$this->_client();
		$req = new \UserBuyerGetRequest;
		$req->setFields("sex,type,status,alipay_bind,avatar");
		try{
			$resp = $c->execute($req, $this->_access_token['access_token']);
		}catch (Exception $e){
			throw new Exception($e->getMessage(),false,$e->getCode());
		}
		if (isset($resp->code)) {
			throw new Exception($resp->msg,false,$resp->code);
		}
		$resp['name']=@$this->_access_token['taobao_user_nick'];
		$resp['user_id']=@$this->_access_token['taobao_user_id'];
		return $resp; 
	}
}
