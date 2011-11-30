<?php 

	class login {
		
		function run($parent) {
			
			$alert = false;
			
			if (isset($parent->get)) {
				
				//Log out
				if ($parent->get == "logout") {
					$parent->auth->logoutUser();
					$parent->actionResult(1);
				}
				
				//Validate
				else if ($parent->get == "auth") {
					$res = $parent->auth->loginUser($_POST["username"], $_POST["password"]);
					if (!$res) {
						$alert = "Invalid login credentials!";
					}
					else {
						$parent->actionResult(0);
					}
				}
				
				//Must be logged in message
				else if ($parent->get == "msg") {
					$alert = "You must be logged in to do that!";
				}
				
			}
			else {
				//If already logged in, redirect
				if ($parent->auth->isLoggedIn()) {
					$parent->actionResult(2);
				}
			}
			
			$parent->title = "Login";
			$parent->displayHeader();
			
			if ($alert) $parent->echoError($alert);
			
			?>
			
			<div class="loginForm">
				<form action="index.php?page=login&get=auth" method="post">
					<div class="username">Username: <input type="text" name="username" /></div>
					<div class="password">Password: <input type="password" name="password" /></div>
					<input id="submitButton" type="submit" value="Submit" />
				</form>
			</div>
			
			<?php
			
			$parent->displayFooter();
			
		}
		
	}

?>