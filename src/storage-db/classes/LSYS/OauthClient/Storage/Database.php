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
class Database implements Storage{
	public static $table="oauth_tokens";
	/**
	 * @var \LSYS\Database
	 */
	protected $_db;
	public function __construct(\LSYS\Database $database=null){
		//table see :table.sql
	    $this->_db=$database?$database:\LSYS\Database\DI::get()->db();
	}
	public function set(string $id,Client $client):bool{
		$timeout=$client->expires();
		if ($timeout<=0)return true;
		$name=substr(strrchr($client->getConfig()->name(), '\\'), 1);
		$_config_key=$this->_db->quote($name);
		$_client_id=$this->_db->quote($id);
		$_client_data=$this->_db->quote(serialize($client));
		$_timeout=time()+$timeout;
		$table=$this->_db->quoteTable('oauth_tokens');		
		$sql="SELECT id FROM {$table} where config_name={$_config_key} and client_id={$_client_id}";
		$id=$this->_db->query( $sql)->get("id");
		if ($id){
			$sql="UPDATE {$table} SET client_data={$_client_data},timeout={$_timeout} where id={$id}";
		}else{
			$sql="INSERT INTO {$table} (config_name,client_id,client_data,timeout)
			VALUES({$_config_key},{$_client_id},{$_client_data},{$_timeout})";
		}
		return $this->_db->exec($sql);
	}
	public function find(string $name,string $id){
		$table=$this->_db->quoteTable('oauth_tokens');
		$_config_key=$this->_db->quote($name);
		$_client_id=$this->_db->quote($id);
		$sql="SELECT id,client_data,timeout FROM {$table} where config_name={$_config_key} and client_id={$_client_id}";
		$row=$this->_db->query( $sql);
		if ($row->count()==0)return NULL;
		if($row->get("timeout")<time()){
			$id=$row->get("id");
			$sql="DELETE FROM  {$table} WHERE id={$id}";
			$this->_db->exec($sql);
			return NULL;
		}
		$data=$row->get("client_data");
		$data=@unserialize($data);
		if((!$data instanceof Client)||$data->expires()<=0){
			$id=$row->get("id");
			$sql="DELETE FROM  {$table} WHERE id={$id}";
			$this->_db->exec($sql);
			return null;
		}
		return $data;
	}
}