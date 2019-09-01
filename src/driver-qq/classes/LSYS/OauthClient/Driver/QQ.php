<?php
namespace LSYS\OauthClient\Driver;
use LSYS\OauthClient\Exception;
use function LSYS\OauthClient\__qq as __;
use LSYS\OauthClient\Redirect;
use LSYS\OauthClient;
class QQ extends \LSYS\OauthClient\Driver {
	public function supportTerminal(){
		return OauthClient::TERMINAL_PC|OauthClient::TERMINAL_WAP|OauthClient::TERMINAL_WECHAT;
	}
	public function authorize($redirect_uri){
		$state=$this->_stateGet();
		$appid=$this->_config->get("appid");
		$scope=$this->_config->get("scope","get_user_info");
		$login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id="
				. $appid . "&redirect_uri=" . urlencode($redirect_uri)
				. "&state=" . $state
				. "&scope=".$scope;
		return new Redirect($login_url);
	}
	public function getClient($redirect_uri){
		if(!isset($_REQUEST['code']) ||!isset($_REQUEST['state'])){
			throw new Exception(__("not access,bad request"),true);
		}
		if (!$this->_stateCheck($_REQUEST['state'])){
			throw new Exception(__("not access,state wrong"),true);
		}
		$appid=$this->_config->get("appid");
		$appkey=$this->_config->get("appkey");
		$scope=$this->_config->get("scope","super_msg");
		$token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
				. "client_id=" .$appid. "&redirect_uri=" . urlencode($redirect_uri)
				. "&client_secret=" . $appkey. "&code=" . $_REQUEST["code"];
		$response = file_get_contents($token_url);
		if (strpos($response, "callback") !== false)
		{
			$lpos = strpos($response, "(");
			$rpos = strrpos($response, ")");
			$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
			$msg = json_decode($response);
			if (isset($msg->error))
			{
				throw new Exception($msg->error_description."[{$msg->error}]",true);
			}
		}
		parse_str($response, $params);
		if (!isset($params["access_token"])){
			throw new Exception(__("can't get access token"),true);
		}
		$access_token=$params["access_token"];
		$graph_url = "https://graph.qq.com/oauth2.0/me?access_token=". $access_token;
		$str  = file_get_contents($graph_url);
		if (strpos($str, "callback") !== false){
			$lpos = strpos($str, "(");
			$rpos = strrpos($str, ")");
			$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
			$msg = json_decode($str);
			if (isset($msg->error))
			{
				throw new Exception($msg->error_description."[{$msg->error}]");
			}
		}
		$user = json_decode($str,true);
		if (isset($user['error'])){
			throw new Exception($user['error']);
		}
// 		$user['openid'];
		$params['openid']=$user['openid'];
		return new \LSYS\OauthClient\Client\QQ($params,$this->_config,$params['expires_in']);
	}
}
