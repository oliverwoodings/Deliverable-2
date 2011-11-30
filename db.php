<?php

	//Database class
	class Db {
		
		private $config;
		
		function __construct($parent) {
			$this->config = $parent->config;
			if (!$this->config->database["enable"]) return;
			mysql_connect($this->config->database["host"], $this->config->database["user"], $this->config->database["pass"]) or die("Unable to connect to SQL Database!");
			mysql_select_db($this->config->database["db"]) or die("Unable to select database!");
			$this->createTables();
		}
		
		/**
		 * Base MySQL query function. Cleans all parameters to prevent injection
		 */
		function query() {
			$args = func_get_args();
			$sql = array_shift($args);
			foreach ($args as $key => $value)
				$args[$key] = $this->clean($value);
			return mysql_query(vsprintf($sql, $args));
		}
		/**
		 * Stops MySQL injection
		 */
		private function clean($string) {
			return mysql_real_escape_string(trim($string));
		}
		
		/**
		 * Creates default tables if they don't exist
		 */
		private function createTables() {
			
		}
		
		
		
		
	}
	
?>