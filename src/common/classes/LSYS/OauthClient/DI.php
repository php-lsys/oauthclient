<?php
/**
 * lsys mq
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\OauthClient;
/**
 * @method \LSYS\OauthClient oauthClient()
 */
class DI extends \LSYS\DI{
    /**
     * @return static
     */
    public static function get(){
        $di=parent::get();
        !isset($di->oauthClient)&&$di->oauthClient(new \LSYS\DI\SingletonCallback(function (){
            return new \LSYS\OauthClient();
        }));
        return $di;
    }
}