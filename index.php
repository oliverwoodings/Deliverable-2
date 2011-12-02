<?php

	//Includes
	include("config.php");
	include("cache.php");
	include("db.php");
	
	//Start up sessions and initiate main class
	session_start();
	$main = new Main();
	
	class Main {
		
		//Setup some variables
		public $config;
		public $db;
		public $auth;
		public $page;
		public $cache;
		public $get;
		public $pages = array("home", "actionresult", "login");
		public $title;
		public $navbar = array();
		public $refresh = false;
		
		function __construct() {
			
			$this->config = new Config();
			$this->db     = new Db($this);
			$this->cache  = new Cache();
			$this->page   = (isset($_GET["page"]) ? $_GET["page"] : $this->config->general["defaultPage"]);
			$this->title  = $this->config->general["defaultTitle"];
			
			//Load auth
			include("auth/" . $this->config->general["authFile"]);
			$this->auth = new Auth($this);
			
			//See if there is a 'get' to do
			if (isset($_GET["get"])) $this->get = strtolower($_GET["get"]);
			else unset($this->get);
			
			//Add nav elements	
			$this->addNavElement("index.php", "Home");
			
			//Include requested page
			if (!in_array($this->page, $this->pages)) {
				$this->page = $this->config->general["defaultPage"];
			}
			include("pages/".$this->page."Page.php");
			$child = new $this->page;
			$child->run($this);
			
		}
		
		//Display global page header
		function displayHeader() {
			
			//Format require information for template
			$compiledTitle = $this->title . " | " . $this->config->general["titleSuffix"];
			$userStatus = "Welcome, Guest. <a href='index.php?page=login'>Login</a>";
			if ($this->auth->isLoggedIn())
				$userStatus = "Welcome, " . $this->auth->getUsername() . '. <a href="index.php?page=login&get=logout">Logout</a>';
				
			?>
			<html> 
				<head>
                	<?php if ($this->refresh) echo '<meta http-equiv="refresh" content="3;url=index.php">'; ?>
					<title><?php echo $compiledTitle; ?></title>
					<meta http-equiv="PRAGMA" content="NO-CACHE">
                    <link rel="shortcut icon" href="images/favicon.ico" />
					<link href="css/styles.css" rel="stylesheet" type="text/css" />
                    <link rel="stylesheet" type="text/css" href="css/custom-theme/jquery-ui-1.8.16.custom.css" />
                    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
                    <script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
        			<script type="text/javascript" src="js/jquery.tools.min.js"></script>
        			<script type="text/javascript" src="js/scripts.js"></script>
					<?php 
						if (file_exists("css/pages/" . $this->page . ".css")) echo '<link rel="stylesheet" type="text/css" href="css/pages/' . $this->page . '.css" />';
						if (file_exists("js/pages/" . $this->page . ".js")) echo '<script type="text/javascript" src="js/pages/' . $this->page . '.js"></script>';
					?>
				</head>
				<body>
					<div class="wrapper">
						<div class="header">
						
						</div>
						<div class="contentContainer">
			<?php
		}
		
		//Display global footer
		function displayFooter() {
			?>
						</div>
						<div class="push">
						
						</div>
					</div>
					<div class="footer">
						&copy; Team 03 2011
					</div>
							
				</body>
			</html>
			<?php
		}
		
		//Go to action result page
		function actionResult($id) {
			header("location:index.php?page=actionresult&type=".$id);
		}
		
		//Adds an element to the NavBar
		function addNavElement($url, $title) {
			$this->navbar[] = array("url" => $url, "title" => $title);
		}
		
		//Echos an error with custom message
		function echoError($message) {
			?><div class="ui-widget errorMsg">
				<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
					<p>
						<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
						<strong>Alert:</strong>
						<?php echo $message; ?>
					</p>
				</div>
			</div><?php
		}
		
		//Echos an alert with custom message
		function echoAlert($message) {
			?><div class="ui-widget errorMsg">
				<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
					<p>
						<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
						<strong>Notice:</strong>
						<?php echo $message; ?>
					</p>
				</div>
			</div><?php
		}
		
		//Issue a JSON error
		function errorJSON($json, $msg) {
			$json["error"] = $msg;
			echo json_encode($json);
			return;
		}
		
	}
	
?>
