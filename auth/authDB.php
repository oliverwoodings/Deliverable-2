<?php
	
	class Auth {
		
		private $parent;
		private $username;
		
		function __construct($parent) {
			$this->parent = $parent;
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
			if (isset($_SESSION["team03LoggedIn"])) return true;
			return false;
		}
		
		//Attempt to log user in with name and password
		function loginUser($username, $password) {
			if ($this->parent->db->checkLoginDetails($username, $password)) {
				$_SESSION["team03LoggedIn"] = true;
				$this->username = $username;
				return true;
			}
			return false;
		}
		
		//Attempt to log out user
		function logoutUser() {
			unset($_SESSION["team03LoggedIn"]);
		}
		
		//Retrieve username
		function getUsername() {
			if (!$this->isLoggedIn()) return "Guest";
			return $this->username;
		}
		
	}
?>
