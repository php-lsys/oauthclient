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
use LSYS\OauthClient\Exception;
use function LSYS\OauthClient\__;
class File implements Storage{
    protected $_prefix="oauthclient_";
    protected $_dir;
    public function __construct($dir=null){
        if (!is_dir($dir))$dir=sys_get_temp_dir();
        if (!@is_readable($dir))throw new Exception(__("stroage use dir[:dir] can't be write.",[':dir'=>$dir]));
        $this->_dir=rtrim($dir,"\\/").DIRECTORY_SEPARATOR;
    }
    public function set($id,Client $client){
        $timeout=$client->expires();
        if ($timeout<=0)return true;
        $name=substr(strrchr($client->getConfig()->name(), '\\'), 1);
        $filename=$this->_dir.$this->_prefix.md5($name.':'.$id);
        $client=serialize($client);
        $timeout=time()+$timeout;
        return @file_put_contents($filename, $timeout."\n".$client);
    }
    public function find($name,$id){
        $filename=$this->_dir.$this->_prefix.md5($name.':'.$id);
        if(!is_file($filename))return null;
        $data=file_get_contents($filename);
        $data=explode("\n", $data);
        $time=array_shift($data);
        $data=implode("\n", $data);
        $data=@unserialize($data);
        if((!$data instanceof Client)||$time<=time()){
            unlink($filename);
            return null;
        }
        return $data;
    }
}