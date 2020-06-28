<?php
/**
 * lsys oauth state session
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\OauthClient\State;
use LSYS\OauthClient\State;
class Session implements State,\LSYS\Wechat\Sns\State{
	protected $_is_state=false;
	protected $_session;
	public function __construct(\LSYS\Session $session=null){
		$this->_session=$session?$session:\LSYS\Session\DI::get()->session();
	}
	public function create(string $key):string{
	    $rand=$this->_session->get($key);
	    if (empty($rand)){
	        $rand=uniqid();
	        $this->_session->set($key, $rand);
	    }
	    return strval($rand);
	}
	public function check(string $key,string $state):bool{
		$_state=$this->_session->get($key);
		if (!$_state) return false;
		$status=$_state==$state;
		$this->_is_state=$key;
		return $status;
	}
	public function __destruct(){
		if ($this->_is_state&&!error_get_last()){
			$this->_session->delete($this->_is_state);
		}
	}
}