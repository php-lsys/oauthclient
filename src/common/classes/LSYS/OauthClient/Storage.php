<?php
/**
 * lsys oauth client
* @author     Lonely <shan.liu@msn.com>
* @copyright  (c) 2017 Lonely <shan.liu@msn.com>
* @license    http://www.apache.org/licenses/LICENSE-2.0
*/
namespace LSYS\OauthClient;
interface Storage{
	/**
	 * set client to storage
	 * @param string $id key
	 * @param Client $client
	 */
	public function set(string $id,Client $client):bool;
	/**
	 * @param string $name config key
	 * @param string $id key
	 * @return mixed
	 */
	public function find(string $name,string $id);
}