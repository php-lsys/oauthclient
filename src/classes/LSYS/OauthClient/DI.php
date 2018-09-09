<?php
/**
 * lsys mq
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\OauthClient;
/**
 * @method \LSYS\OauthClient oauth_client()
 */
class DI extends \LSYS\DI{
    /**
     * @return static
     */
    public static function get(){
        $di=parent::get();
        !isset($di->oauth_client)&&$di->oauth_client(new \LSYS\DI\SingletonCallback(function (){
            return new \LSYS\OauthClient();
        }));
        return $di;
    }
}