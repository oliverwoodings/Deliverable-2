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
				$userStatus = '<a href="index.php?page=login&get=logout">Logout (' . $this->auth->getUsername() . ')</a>';
				
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
			        
			        <div id="main">
			        
			        	<div id="header-container">
			        
					        <!-- Top Links -->
					        <div id="main-toplinks">
					           <p class="left">
					           	<a href="http://www.lboro.ac.uk/accessibility/">Accessibility</a> | 
					            <a href="http://www.lboro.ac.uk/contact.html">Getting in touch</a> | 
					            <a href="http://www.lboro.ac.uk/about/findus.html">How to find us</a>  
					           </p>
					           <p class="right"></p>
					           <div class="clear"></div>
					        </div>
		            
				            <!-- Main Header -->
				            <div id="main-header">
				       
				                <!-- Contact Info -->
				                <div class="leftbox">
				                    Loughborough University<br />
				                    Leicestershire, UK<br />
				                    LE11 3TU<br />
				                    +44 (0)1509 263171
				                </div>
				                <div class="rightbox">
				                    <a href="http://www.lboro.ac.uk"><img src="images/lulogo_204_52.jpg" alt="Loughborough University" width="204" height="52" /></a>
				                </div>
				                
				                <!-- Navigation Bar -->
				                <div id="main-header-menu">
				                    <ul>
				                        <li><a href="http://www.lboro.ac.uk/" class="rightborder">University home</a></li>
				                        <li><a href="http://www.lboro.ac.uk/prospectus/" class="rightborder">Prospective students</a></li>
				                        <li><a href="http://www.lboro.ac.uk/international/" class="rightborder">International</a></li>
				                        <li><a href="http://www.lboro.ac.uk/news/" class="rightborder">News and events</a></li>
				                        <li><a href="http://www.lboro.ac.uk/about/" class="rightborder">About us</a></li>
				                        <li><a href="http://www.lboro.ac.uk/departments/" class="rightborder">Faculties and departments</a></li>
				                        <li><a href="http://www.lboro.ac.uk/research/" class="rightborder">Research</a></li>
				                        <li><a href="http://www.lboro.ac.uk/business/">Working with business</a></li>
				                    </ul>
				                </div>
				                    
				            </div>
				            
				            <!-- Main Title -->
				            <div id="main-title">
				                <h1>Loughborough University Timetabling</h1>
				            </div>
				            
				            <!-- Breadcrumb -->
				            <div id="breadcrumb">
				            	<div class="menu_container">
					                <ul class="main_menu">
					                    <li><a href="index.php">Home</a></li>
					                    <li><a href="RequestRoom.php">Request Room</a></li>
					                    <li><a href="#">Pending Requests</a></li>
					                    <li>
					                        <a href="#">Allocated Rooms</a>
					                        <ul>
					                            <li><a href="#">Priority Round</a></li>
					                            <li><a href="#">Round 1</a></li>
					                            <li class="bottomli"><a href="#">Round 2</a></li>
					                        </ul>
					                    </li>
					                    <li class="last"><a href="#">Room Bookings</a></li>
					                </ul>
				                </div>
				                <div class="logout"><a href="index.php?page=login&get=logout">Logout (Computer Science) </a></div>
				            </div>
			            
			            </div>
			            
			            <div id="main-content">
		            
			<?php
		}
		
		//Display global footer
		function displayFooter() {
			?>			
						</div>
						
						<div class="push"></div>
						
			    	</div>
			    	
					<div id="main-footer">
					
						<div id="footertext">
							<span>If you have any problems regarding your timetable please contact your departmental administrator</span>
			            </div>
			  			<div id="footertext">
			            	Website maintained by: <a href="mailto:it.services@lboro.ac.uk">IT.Services@lboro.ac.uk</a>
			            </div>
			            
				        <!-- Bottom Links -->
			            <div id="main-botlinks">
			              <p class="left"> <span class="whitetxt">&copy; Loughborough University</span></p>
			              <p class="right"> <a href="http://www.lboro.ac.uk/disclaimer.html">Legal information</a> | 
			              <a href="http://www.lboro.ac.uk/admin/ar/policy/foi/">Freedom of Information</a> | 
			              <a href="#top">Top of page</a> </p>
			              <div class="clear"></div>
			            </div>
		            
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
