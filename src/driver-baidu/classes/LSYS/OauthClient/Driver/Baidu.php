<?php
namespace LSYS\OauthClient\Driver;
use LSYS\OauthClient;
use LSYS\OauthClient\Redirect;
use LSYS\OauthClient\Exception;
use function LSYS\OauthClient\__baidu as __;
class Baidu extends \LSYS\OauthClient\Driver {
	public function supportTerminal():int{
		return OauthClient::TERMINAL_PC|OauthClient::TERMINAL_WAP|OauthClient::TERMINAL_WECHAT;
	}
	public function authorize(string $redirect_uri){
		$key=$this->_config->get("key");
		$secret=$this->_config->get("secret");
		$scope=$this->_config->get("scope","super_msg");
		include_once __DIR__.'/../../../../libs/baidu/BaiduAuth.class.php';
		$baidu=new \BaiduAuth($key, $secret);
		$code_url = $baidu->getLoginUrl($redirect_uri,$scope,$this->_stateGet());
		return new Redirect($code_url);
	}
	public function getClient(string $redirect_uri){
		if(!isset($_REQUEST['code']) ||!isset($_REQUEST['state'])){
			throw new Exception(__("not access,bad request"),true);
		}
		if (!$this->_stateCheck($_REQUEST['state'])){
			throw new Exception(__("not access,state wrong"),true);
		}
		$key=$this->_config->get("key");
		$secret=$this->_config->get("secret");
		include_once __DIR__.'/../../../../libs/baidu/BaiduAuth.class.php';
		$baidu=new \BaiduAuth($key, $secret);
		try{
		    $access=$baidu->getAccessToken($_REQUEST['code'], $redirect_uri);
			$access=json_decode($access,true);
		}catch (\Exception $e){
			throw new Exception($e->getMessage(),false,$e->getCode());
		}
		if (isset($access['error'])||!isset($access['access_token'])) {
			throw new Exception($access['error'],true);
		}
		return new \LSYS\OauthClient\Client\Baidu($access,$this->_config,$access['expires_in']);
	}
}
