<?php
/**
 * lsys oauth client
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS;
use LSYS\OauthClient\Driver;
use function LSYS\OauthClient\__;
use LSYS\OauthClient\Exception;
class OauthClient{
	const TERMINAL_PC=1;
	const TERMINAL_WAP=1<<1;
	const TERMINAL_WECHAT=1<<2;
	/**
	 * create redirect uri
	 * @param string $get_key GET index
	 * @param string $callback_url login page url
	 * @return string
	 */
	public static function create_redirect_uri($get_key='redirect_uri',$callback_url=TRUE){
	    if ($callback_url===true){
	        $https=false;
	        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
	            $https=true;
	        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
	            $https=true;
	        } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
	            $https=true;
	        }
	        $host=isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:NULL;
	        if (!$host)$host=isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:'127.0.0.1';
	        $request=isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'/';
	        $callback_url=($https?'https://':'http://').$host.$request;
	    }
	    $ref=isset($_GET[$get_key])?strip_tags($_GET[$get_key]):NULL;
	    if ($ref!=null){
	        $ref=urlencode(urldecode($ref));
	        $callback_url.=strpos($callback_url, "?")===false?"?":"&";
	        $callback_url=$callback_url.$get_key."=".$ref;
	    }
	    return $callback_url;
	}
	/**
	 * @var Driver[]
	 */
	protected $_driver=array();
	/**
	 * list support driver object
	 * @param int $terminal
	 * @return Driver[]
	 */
	public function list_driver($terminal){
	    $out=[];
	    foreach ($this->_driver as $k=>$dr){
	        if ($dr->support_terminal()&$terminal)  $out[$k]=$dr;
		}
		return $out;
	}
	/**
	 * add driver
	 * @param string $name
	 * @throws Exception
	 * @return 
	 */
	public function add_driver($key,\LSYS\OauthClient\Driver $driver){
        $this->_driver[$key]=$driver;
	   return $this;
	}
	/**
	 * get driver by driver name
	 * @param string $key
	 * @throws Exception
	 * @return \LSYS\OauthClient\Driver
	 */
	public function get_driver($key){
	    if (!isset($this->_driver[$key])){
	        throw new Exception(__("not _fint your oauth client driver"));
	    }
	    return $this->_driver[$key];
	}
}