<?php
/**
 * lsys database
* @author     Lonely <shan.liu@msn.com>
* @copyright  (c) 2017 Lonely <shan.liu@msn.com>
* @license    http://www.apache.org/licenses/LICENSE-2.0
*/
namespace LSYS\OauthClient;
class Exception extends \LSYS\Exception{
	protected $_login;
	public function __construct($message, $need_login=false,$code=0)
	{
		parent::__construct($message, (int) $code);
		$this->_login=$need_login;
	}
	/**
	 * 是否是需要登录接口而当前没有登录
	 * @return bool
	 */
	public function needLogin(){
		return $this->_login;
	}
}