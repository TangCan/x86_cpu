<?php

class DB {
	private static $instance;
	private $MySQLi;
	
	private function __construct(array $dbOptions){

		$this->MySQLi = @ new mysqli(	$dbOptions['db_host'],
										$dbOptions['db_user'],
										$dbOptions['db_pass'],
										$dbOptions['db_name'] );

		if (mysqli_connect_errno()) {
			throw new Exception('Database error.');
		}

		$this->MySQLi->set_charset("utf8");
	}
	
	public static function init(array $dbOptions){
		if(self::$instance instanceof self){
			return false;
		}
		
		self::$instance = new self($dbOptions);
		
		self::$instance->MySQLi->query("CREATE TABLE IF NOT EXISTS `webchat_lines` (  `id` int(10) unsigned NOT NULL auto_increment,  `author` varchar(16) NOT NULL,  `gravatar` varchar(32) NOT NULL,  `text` varchar(255) NOT NULL,  `ts` timestamp NOT NULL default CURRENT_TIMESTAMP,  PRIMARY KEY  (`id`),  KEY `ts` (`ts`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
		self::$instance->MySQLi->query("CREATE TABLE IF NOT EXISTS `webchat_users` (  `id` int(10) unsigned NOT NULL auto_increment,  `name` varchar(16) NOT NULL,  `gravatar` varchar(32) NOT NULL,  `last_activity` timestamp NOT NULL default CURRENT_TIMESTAMP,  PRIMARY KEY  (`id`),  UNIQUE KEY `name` (`name`),  KEY `last_activity` (`last_activity`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
	}
	
	public static function getMySQLiObject(){
		return self::$instance->MySQLi;
	}
	
	public static function query($q){
		return self::$instance->MySQLi->query($q);
	}
	
	public static function esc($str){
		return self::$instance->MySQLi->real_escape_string(htmlspecialchars($str));
	}
}

?>