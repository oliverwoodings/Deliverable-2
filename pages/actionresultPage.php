<?php

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
			}
			echo " Redirecting to index page...";
			$parent->displayFooter();
			
		}
	
	
	}
	
?>