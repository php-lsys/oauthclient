<?php
namespace LSYS\OauthClient\Driver;
use LSYS\OauthClient\Redirect;
use LSYS\OauthClient\Exception;
use LSYS\OauthClient;
class Weibo extends \LSYS\OauthClient\Driver {
	public function supportTerminal():int{
		return OauthClient::TERMINAL_PC|OauthClient::TERMINAL_WAP|OauthClient::TERMINAL_WECHAT;
	}
	/**
	 * @return \SaeTOAuthV2
	 */
	protected function _client(){
		static $client;
		if (!$client){
			$WB_AKEY=$this->_config->get("key");
			$WB_SKEY=$this->_config->get("secret");
			include_once __DIR__.'/../../../../libs/sina/saetv2.ex.class.php';
			$client = new \SaeTOAuthV2( $WB_AKEY ,$WB_SKEY );
		}
		return $client;
	}
	public function authorize(string $redirect_uri){
		$client=$this->_client();
		$url = $client->getAuthorizeURL($redirect_uri,'code',$this->_stateGet());
		return new Redirect($url);
	}
	public function getClient(string $redirect_uri){
		if(!isset($_REQUEST['code']) ||!isset($_REQUEST['state'])){
			throw new Exception(__("not access,bad request"),true);
		}
		if (!$this->_stateCheck($_REQUEST['state'])){
			throw new Exception(__("not access,state wrong"),true);
		}
		$client=$this->_client();
		$keys = array();
		$keys['code'] = $_REQUEST['code'];
		$keys['redirect_uri'] = $redirect_uri;
		try {
			$token = $client->getAccessToken( 'code', $keys ) ;
		}catch (Exception $e){
			throw new Exception($e->getMessage(),true,$e->getCode());
		}
		@setcookie( 'weibojs_'.$client->client_id, http_build_query($token) );
		return new \LSYS\OauthClient\Client\Weibo($token,$this->_config,$token['expires_in']);
	}
}
