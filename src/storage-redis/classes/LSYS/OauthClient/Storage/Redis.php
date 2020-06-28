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
class Redis implements Storage{
	protected $_redis;
	public static $prefix="oauth_client:";
	public function __construct(\LSYS\Redis $redis=null){
	    $this->_redis=$redis?$redis:\LSYS\Redis\DI::get()->redis();
	}
	public function set(string $id,Client $client):bool{
	    $this->_redis->configConnect();
		$timeout=$client->expires();
		if ($timeout<=0)return true;
		$name=substr(strrchr($client->getConfig()->name(), '\\'), 1);
		$key=self::$prefix.$name.':'.$id;
		$stat=$this->_redis->set($key,serialize($client));
		if ($this->_redis->ttl($key)<=$timeout){//重设过期时间
			$this->_redis->expire($key,$timeout);
		}
		return $stat;
	}
	public function find(string $name,string $id){
		//QQ config id
		//config
	    $this->_redis->configConnect();
		$key=self::$prefix.$name.':'.$id;
		if(!$this->_redis->exists($key))return null;
		$data=$this->_redis->get($key,$name);
		$data=@unserialize($data);
		if((!$data instanceof Client)||$data->expires()<=0){
			$this->_redis->del($key);
			return null;
		}
		return $data;
	}
}