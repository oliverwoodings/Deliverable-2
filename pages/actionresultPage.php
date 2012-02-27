<?php

	/**
	 *	File: entities/actionresultPage.php
	 *	Page: Action Result
	 *  Author: Oliver Woodings
	 *  Functionality: Displays a message to the user as a result of one of their actions
	 */

	class actionresult {
		
		function run($parent) {
			
			$parent->refresh = true;
			$parent->title = "Action";
			$parent->displayHeader();
			switch ($_GET["type"]) {
				case 0:
					echo "Successfully logged in.";
					break;
				case 1:
					echo "Successfully logged out.";
					break;
				case 2;
					echo "You are already logged in.";
					break;
				case 3:
					echo "Semester changed.";
					break;
			}
			echo " Redirecting to index page...";
			$parent->displayFooter();
			
		}
	
	
	}
	
?>