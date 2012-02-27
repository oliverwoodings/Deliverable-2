<?php
	
	class Auth {
		
		private $parent;
		private $username;
		private $id;
		
		function __construct($parent) {
			$this->parent = $parent;
			if (isset($_SESSION["team03TimetableUsername"]))
				$this->username = $_SESSION["team03TimetableUsername"];
		}
		
		//If user is not logged in, redirect to the login page
		function requireLogin() {
			if (!$this->isLoggedIn()) {
				session_write_close();
				header("location:index.php?page=login");
				exit();
			}
		}
		
		//Check if user is logged in or not
		function isLoggedIn() {
			if (isset($_SESSION["team03TimetableLoggedIn"])) return true;
			return false;
		}
		
		//Attempt to log user in with name and password
		function loginUser($username, $password) {
			if ($this->parent->db->checkLoginDetails($username, $password)) {
				$_SESSION["team03TimetableLoggedIn"] = true;
				$_SESSION["team03TimetableUsername"] = $username;
				$this->username = $username;
				return true;
			}
			return false;
		}
		
		//Attempt to log out user
		function logoutUser() {
			unset($_SESSION["team03TimetableLoggedIn"]);
		}
		
		//Retrieve username
		function getUsername() {
			if (!$this->isLoggedIn()) return "Guest";
			return $this->username;
		}
		
		//Retrieve user id
		function getUserId() {
			if (!$this->isLoggedIn()) return "0";
			if (strlen($this->id) == 0) {
				$this->id = $this->parent->db->getDepartmentByName($this->username)->getId();
			}
			return $this->id;
		}
		
		//Get user semester
		function getUserSemester() {
			if (!isset($_SESSION["team03Timetablesemester"])) return 1;
			else return $_SESSION["team03Timetablesemester"];
		}
		
		//Set user in semester
		function setUserSemester($semester) {
			$_SESSION["team03Timetablesemester"] = $semester;
		}
			
		
	}
?>
