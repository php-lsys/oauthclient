<?php
/**
 * lsys oauth client
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\OauthClient;
use LSYS\OauthClient\State\Session;
abstract class Driver {
	/**
	 * @var State
	 */
	protected static $state;
	/**
	 * set state apatar
	 * @param State $state
	 */
	public static function setState(State $state){
		self::$state=$state;
	}
	protected static function _state(){
		if (self::$state==null){
			self::$state=new Session();
		}
		return self::$state;
	}
	/**
	 * @var \LSYS\Config
	 */
	protected $_config;
	public function __construct(\LSYS\Config $config){
		$this->_config=$config;
	}
	protected function _stateGet():string{
		$key='_OAUTH_STATE_'.$this->_config->name();
		return self::_state()->create($key);
	}
	protected function _stateCheck(string $state):bool{
		$key='_OAUTH_STATE_'.$this->_config->name();
		return self::_state()->check($key,$state);
	}
	/**
	 * @param string $callback_url
	 * @return Redirect
	 */
	abstract public function authorize(string $redirect_uri);
	/**
	 * get support env
	 * @return int
	 */
	abstract public function supportTerminal():int;
	/**
	 * get access token
	 * @param string $redirect_uri
	 * @return Client
	 */
	abstract public function getClient(string $redirect_uri);
}