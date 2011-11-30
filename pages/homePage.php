<?php 

	class home {
		
		function run($parent) {
			
			$parent->auth->requireLogin();
			
			$parent->title = "Home";
			$parent->displayHeader();
					
			$parent->displayFooter();
			
		}
		
	}

?>