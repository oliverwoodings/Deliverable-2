<?php
	
	/**
	 *	File: auth/authAll.php
	 *	Class: Auth
	 *  Author: Oliver Woodings
	 *  Functionality: Provides an abstract interface for user authorisaton
	 */
	
	class Auth {
		
		private $parent;
		private $username = "Anonymous";
		
		function __construct($parent) {
			$this->parent = $parent;
		}
		
		//If user is not logged in, redirect to the login page
		function requireLogin() {
			return true;
		}
		
		//Check if user is logged in or not
		function isLoggedIn() {
			return true;
		}
		
		//Attempt to log user in with name and password
		function loginUser($username, $password) {
			return true;
		}
		
		//Attempt to log out user
		function logoutUser() {
			return true;
		}
		
		//Retrieve username
		function getUsername() {
			return $this->username;
		}
		
	}
?>
