<?php
namespace LSYS\OauthClient\Driver;
use LSYS\OauthClient;
use LSYS\OauthClient\Redirect;
class WechatPC extends Wechat {
	public function supportTerminal():int{
		return OauthClient::TERMINAL_PC;
	}
	public function authorize(string $redirect_uri){
	    $sns=new \LSYS\Wechat\Sns($this->_config);
	    $sns->session($this->_state());
	    $url=$sns->qrcodeAccessUrl($redirect_uri);
		return new Redirect($url);
	}
}
