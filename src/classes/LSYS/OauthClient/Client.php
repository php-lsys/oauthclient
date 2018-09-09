<?php
/**
 * lsys oauth client
* @author     Lonely <shan.liu@msn.com>
* @copyright  (c) 2017 Lonely <shan.liu@msn.com>
* @license    http://www.apache.org/licenses/LICENSE-2.0
*/
namespace LSYS\OauthClient;
abstract class Client implements \Serializable{
	protected $_config;
	protected $_access_token;
	protected $_expires_in;
	public function __construct($access_token,\LSYS\Config $config,$expires_in=3600){
		$this->_config=$config;
		$this->_access_token=$access_token;
		$this->_expires_in=time()+$expires_in;
	}
	/**
	 * 获取配置对象
	 * @return \LSYS\Config
	 */
	public function get_config(){
		return $this->_config;
	}
	/**
	 * 剩余时间[秒]
	 * @return number
	 */
	public function expires(){
		$expires_in=$this->_expires_in-time();
		return $expires_in>0?$expires_in:0;
	}
	/**
	 * 序列化保存授权
	 * {@inheritDoc}
	 * @see \Serializable::serialize()
	 */
	public function serialize () {
	    if (!$this->_config instanceof \Serializable){
	        throw new Exception(__("your config can't be serialize"));
	    }
		$config=serialize(array($this->_config,$this->_expires_in,$this->_access_token));
		return $config;
	}
	/**
	 * 反序列化授权
	 * {@inheritDoc}
	 * @see \Serializable::unserialize()
	 */
	public function unserialize ($serialized) {
		try{
			list($this->_config,$this->_expires_in,$this->_access_token)=unserialize($serialized);
		}catch (\Exception $e){
			$this->_expires_in=0;
		}
	}
	/**
	 * 得到授权KEY
	 * @return string
	 */
	public function get_access_token(){
		return $this->_access_token;
	}
	/**
	 * 刷新授权
	 * @return Client
	 */
	abstract public function refresh_token();
	/**
	 * 统一调用接口
	 * @param string $api
	 * @param array $param
	 * @return mixed
	 */
	abstract public function call($api,array $param=array());
	/**
	 * 获取当前登录的用户信息
	 * @return array
	 */
	abstract public function get_user();
}