<?php
/**
 * lsys oauth client
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\OauthClient;
interface State{
	/**
	 * get state
	 * @param string $key
	 * @return string
	 */
    public function create($key);
	/**
	 * check state
	 * @param string $key
	 * @param string $state
	 * @return bool
	 */
	public function check($key,$state);
}