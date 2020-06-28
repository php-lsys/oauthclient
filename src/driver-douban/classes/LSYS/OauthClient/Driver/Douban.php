<?php
namespace LSYS\OauthClient\Driver;
use LSYS\OauthClient\Redirect;
use LSYS\OauthClient\Exception;
use function LSYS\OauthClient\__douban as __;
use LSYS\OauthClient;

class Douban extends \LSYS\OauthClient\Driver {
	public function supportTerminal():int{
		return OauthClient::TERMINAL_PC|OauthClient::TERMINAL_WAP|OauthClient::TERMINAL_WECHAT;
	}
	/**
	 * @return \DoubanOauth
	 */
	protected function _client(string $redirect_uri){
		static $client;
		if (!$client){
			include_once __DIR__.'/../../../../libs/douban/src/DoubanOauth.php';
			$key=$this->_config->get("key");
			$secret=$this->_config->get("secret");
			$scope=$this->_config->get("scope","douban_basic_common,shuo_basic_r,shuo_basic_w");
			$appConfig = array(
				'client_id' => $key,
				'secret' =>$secret,
				'redirect_uri' => $redirect_uri,
				'scope' => $scope,
				'need_permission' => true
			);
			$client = new \DoubanOauth($appConfig);
		}
		return $client;
	}
	
	public function authorize(string $redirect_uri){
		$douban = $this->_client($redirect_uri);
		return new Redirect($douban->getAuthorizeUrl());
	}
	public function getClient(string $redirect_uri){
		if (!isset($_REQUEST['code'])){
			throw new Exception(__("your not access visit"),true);
		}
		$douban = $this->_client($redirect_uri);
		$douban->setAuthorizeCode($_REQUEST['code']);
		try{
			$result=$douban->requestAccessToken();
		}catch (\Exception $e){
			throw new Exception($e->getMessage());
		}
		if (!$douban->getAccessToken()){
			throw new Exception(__("can't get access token"),true);
		}
		return new \LSYS\OauthClient\Client\Douban(array(
			'access_token'=>$result->access_token,
			'refresh_token'=>$result->refresh_token,
			'douban_user_id'=>$result->douban_user_id,
		),$this->_config,$result->expires_in);
	}
}
