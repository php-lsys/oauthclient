<?php
/**
 * lsys oauth client
* @author     Lonely <shan.liu@msn.com>
* @copyright  (c) 2017 Lonely <shan.liu@msn.com>
* @license    http://www.apache.org/licenses/LICENSE-2.0
*/
namespace LSYS\OauthClient\Storage;
use LSYS\OauthClient\Client;
use LSYS\OauthClient\Storage;
class Memcache implements Storage{
	protected $_mem;
	const CACHE_CEILING = 2592000;
	public static $prefix="oauth_client:";
	public function __construct(\LSYS\Memcache $memcache=null){
	    $this->_mem=$memcache?$memcache:\LSYS\Memcache\DI::get()->memcache();
	}
	public function set(string $id,Client $client):bool{
	    $this->_mem->configServers();
		$lifetime=$client->expires();
		if ($lifetime<=0)return true;
		$name=substr(strrchr($client->getConfig()->name(), '\\'), 1);
		$key=self::$prefix.$name.':'.$id;
		if ($lifetime > static::CACHE_CEILING)
		{
			$lifetime = static::CACHE_CEILING + time();
		}
		elseif ($lifetime > 0)
		{
			$lifetime += time();
		}
		else
		{
			$lifetime = 0;
		}
		return $this->_mem->set($key,serialize($client),false,$lifetime);
	}
	public function find(string $name,string $id){
	    $this->_mem->configServers();
		$key=self::$prefix.$name.':'.$id;
		$data=$this->_mem->get($key);
		if (empty($key))return null;
		$data=@unserialize($data);
		if((!$data instanceof Client)||$data->expires()<=0){
			$this->_mem->delete($key);
			return null;
		}
		return $data;
	}
}