<?php
namespace LSYS\OauthClient\Driver;
use LSYS\OauthClient\Redirect;
use LSYS\OauthClient\Exception;
use LSYS\OauthClient;
class Taobao extends \LSYS\OauthClient\Driver {
	public function supportTerminal():int{
		return OauthClient::TERMINAL_PC|OauthClient::TERMINAL_WAP;
	}
	/**
	 * @return \TaobaoOauth
	 */
	protected function _client(){
		static $client;
		if (!$client){
			$key=$this->_config->get("key");
			$secret=$this->_config->get("secret");
			include_once __DIR__.'/../../../../libs/taobao/taobao.php';
			$client = new \TaobaoOauth($key, $secret);
		}
		return $client;
	}
	public function authorize(string $redirect_uri){
		$taobao=$this->_client();
		$code_url = $taobao->getLoginUrl($redirect_uri,$this->_stateGet());
		return new Redirect($code_url);
	}
	public function getClient(string $redirect_uri){
		if(!isset($_REQUEST['code']) ||!isset($_REQUEST['state'])){
			throw new Exception(__("not access,bad request"),true);
		}
		if (!$this->_stateCheck($_REQUEST['state'])){
			throw new Exception(__("not access,state wrong"),true);
		}
		$taobao = $this->_client();
		try{
			$access=$taobao->getAccessToken($_REQUEST['code'], $redirect_uri);
			$access=json_decode($access,true);
		}catch (\Exception $e){
			throw new Exception($e->getMessage(),true,$e->getCode());
		}
		if (isset($access['error'])) {
			throw new Exception($access['error'],true);
		}
		return new \LSYS\OauthClient\Client\Taobao( $access,$this->_config,$access['expires_in']);
	}
}
