<?php
namespace LSYS\OauthClient\Driver;
use LSYS\OauthClient\Redirect;
use LSYS\OauthClient\Exception;
use LSYS\OauthClient;
class Wechat extends \LSYS\OauthClient\Driver {
	public function supportTerminal(){
		return OauthClient::TERMINAL_WECHAT;
	}
	public function authorize($redirect_uri){
	    $url=(new \LSYS\Wechat\Sns($this->_config))->session($this->_state())->accessUrl($redirect_uri);
		return new Redirect($url);
	}
	public function getClient($redirect_uri){
	    $sns=new \LSYS\Wechat\Sns($this->_config);
	    $sns->session($this->_state());
		$result=$sns->accessToken();
		if (!$result->getStatus()){
			throw new Exception($result->getMsg(),true);
		}
		$access=$sns->getAccessToken();
		return \LSYS\OauthClient\Client\Wechat($access,$this->_config,$access['expires_in'],$sns);
	}
}
