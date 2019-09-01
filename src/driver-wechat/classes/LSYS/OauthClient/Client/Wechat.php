<?php
namespace LSYS\OauthClient\Client;
use LSYS\OauthClient\Exception;
use function LSYS\OauthClient\__wechat as __;
use LSYS\OauthClient\State\Session;
class Wechat extends \LSYS\OauthClient\Client {
    /**
     * @var \LSYS\Wechat\Sns
     */
    protected $_sns;
    public function __construct($access_token,\LSYS\Config $config,$expires_in=3600,\LSYS\Wechat\Sns $sns=null){
		$this->_config=$config;
		$this->_access_token=$access_token;
		$this->_expires_in=time()+$expires_in;
		$this->_sns($sns);
	}
	private function _sns($sns=null){
	    if (!$sns){
	        $sns=new \LSYS\Wechat\Sns($this->_config);
	        $sns->session(new Session());
	    }
	    $this->_sns=$sns;
	}
	public function unserialize ($serialized) {
	    parent::unserialize($serialized);
	    $this->_sns();
	}
	public function refreshToken(){
	    $sns=$this->_sns;
		$result=$sns->refreshToken();
		if (!$result->getStatus()){
			throw new Exception($result->getMsg());
		}
		$this->_access_token=$sns->getAccessToken();
		$this->_expires_in=time()+$this->_access_token['expires_in'];
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
	public function getUser(){
	    $sns=$this->_sns;
		$user_info=$sns->getUser();
		if (!$user_info->getStatus()){
			throw new Exception($user_info->getMsg());
		}
		return $user_info->getData();
// 		$r_user['openid'];
// 		$r_user['nickname'],
// 		$r_user['headimgurl'],
// 		$r_user['sex']==2?'女':'男',
	}
}
