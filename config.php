<?php

	class Config {
		
		public $database;
		public $general;
		
		function __construct() {
		
			/**
			 * Database settings
			 */
			$this->database["host"] = "localhost";
			$this->database["user"] = "team03";
			$this->database["pass"] = "";
			$this->database["db"]   = "team03";
			
			/**
			 * General settings
			 */
			$this->general["defaultPage"]  = "home";
			$this->general["defaultTitle"] = "Home";
			$this->general["titleSuffix"]  = "Loughborough Timetabling";
			$this->general["authFile"]     = "authAll.php"; #Set to 'authForum.php' for phpBB auth or 'authAll.php' for general auth
			
		}
		
	}

?>
