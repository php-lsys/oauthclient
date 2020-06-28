<?php
namespace LSYS\OauthClient\Client;
use LSYS\OauthClient\Exception;
use function LSYS\OauthClient\__baidu as __;
class Baidu extends \LSYS\OauthClient\Client {
	/**
	 * @var \BaiduAuth
	 */
	protected $_client;
	/**
	 * @return \BaiduAuth
	 */
	protected function _client(){
		if ($this->_client) return $this->_client; 
		$key=$this->_config->get("key");
		$secret=$this->_config->get("secret");
		include_once __DIR__.'/../../../../libs/baidu/BaiduAuth.class.php';
		$this->_client=new \BaiduAuth($key, $secret);
		return $this->_client;
	}
	public function refreshToken(){
		throw new Exception(__("not support this method"));
	}
	public function call($api,array $param=array()){
		throw new Exception(__("not support this method"));
	}
	public function getUserAvatar($data):string{
		return "http://tb.himg.baidu.com/sys/portrait/item/".$data['portrait'];
	}
	public function getUser():array{
		$data=$this->_client()->getUserid($this->_access_token['access_token']);
		$data=@json_decode($data,true);
		if(isset($data['error_code'])){
			throw new Exception($data['error_code']);
		}
		$fields=array(
			"userid","username","realname","userdetail","birthday","sex","portrait"
		);
		$data=$this->_client()->getUserInfo($this->_access_token['access_token'], $data['uid'], $fields);
		$data=@json_decode($data,true);
		if(isset($data['error_code'])){
			throw new Exception($data['error_msg']);
		}
		return $data;
	}
}
