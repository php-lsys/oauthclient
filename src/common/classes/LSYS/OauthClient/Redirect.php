<?php
/**
 * lsys oauth client
* @author     Lonely <shan.liu@msn.com>
* @copyright  (c) 2017 Lonely <shan.liu@msn.com>
* @license    http://www.apache.org/licenses/LICENSE-2.0
*/
namespace LSYS\OauthClient;
class Redirect {
	protected $_url;
	public function __construct(string $url){
		$this->_url=$url;
	}
	public function __toString(){
		return $this->_url;
	}
	public function go(int $code=301){
		$uri=str_replace(array("\n","\t","\r"), '',$this->_url);
		if (!headers_sent()){
			Header( "HTTP/1.1 {$code} Moved Permanently" );
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
			header("Cache-Control: no-cache, must-revalidate");
			header("Pragma:no-cache");
			header("location: ".$uri);
		}else{
			@ob_end_clean();
			echo <<<EOT
			<html><head>
			<meta http-equiv="pragma" content="no-cache">
			<meta http-equiv="cache-control" content="no-store, must-revalidate">
			<meta http-equiv="expires" content="wed, 26 feb 1997 08:21:57 gmt">
			<meta http-equiv=\"refresh\" content=\"0;url=$uri\">
			</head><body></body></html>
EOT;
			flush();
		}
		exit;
	}
}