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
			
			if ($alert) $parent->echoError($alert);
			
			?>
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title>Loughborough University TimeTabling System</title>
					<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
				
					<!--<link rel="stylesheet" href="css/default.css" type="text/css" />-->
					<link rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.16.custom.css" type="text/css" />
					
					<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
					<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
					
					<!-- per page -->
					<link rel="stylesheet" href="css/pages/login.css" type="text/css" />
					<script type="text/javascript" src="js/pages/login.js"></script>
				</head>
				
				<body>
					<a href="http://www.lboro.ac.uk"><img id="lboro_image" src="images/lulogo_204_52.jpg" alt="Loughborough University" width="204" height="52" /></a>
					<div id="login_box">
						<form id="login" action="index.php?page=login&get=auth" method="post">
							<p id="logintitle"><h2>Timetabling System</h2></p>
							<label>Username</label>
							<div class="ui-widget" id="user_input">
								<label for="user_combobox"></label>
								<select id="user_combobox" name="username">
									<?php
										$departments = $parent->db->getDepartments();
										$i = 1;
										foreach ($departments as $department) {
											echo "<option " . ($i == 1?"selected='selected'":"") . ">" . $department->getName() . "</option>";
											$i++;
										}
									?>
								</select>
							</div>
							<label for="password">Password</label><br /><input id="password" name="password" type="password" class="ui-autocomplete-input"/><br/>
							<input id="sub_login" type="submit" value="Log In"/>
							<a href="#">Forgot your password?</a>
						</form>
					</div>
				</body>
			</html>
			
			<?php
			
		}
		
	}

?>