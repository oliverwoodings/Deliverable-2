<?php

	class Config {
		
		public $database;
		public $general;
		
		function __construct() {
			
			//Include database information
			global $host, $user, $pass, $db;
			include("../db-info.php");
		
			/**
			 * Database settings
			 */
			$this->database["host"]   = $host;
			$this->database["user"]   = $user;
			$this->database["pass"]   = $pass;
			$this->database["db"]     = $db;
			$this->database["enable"] = true;
			
			/**
			 * General settings
			 */
			$this->general["defaultPage"]  = "home";
			$this->general["defaultTitle"] = "Home";
			$this->general["titleSuffix"]  = "Loughborough Timetabling";
			$this->general["authFile"]     = "authAll.php";
			
		}
		
	}

?>
