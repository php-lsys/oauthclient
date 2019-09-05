-- import your database 
CREATE TABLE `oauth_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(32) DEFAULT NULL ,
  `client_data` varchar(1024) DEFAULT NULL,
  `client_id` varchar(120) DEFAULT NULL,
  `timeout` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_tokens_config_key_idx` (`config_key`,`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
