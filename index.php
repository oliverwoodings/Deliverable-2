<?php

	//Includes
	include("config.php");
	include("cache.php");
	include("db.php");
	//Entities
	foreach (glob("entities/*.php") as $filename) include $filename;
	
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
		public $pages = array("home", "actionresult", "login", "modules", "requests", "responses", "roomavailability");
		public $title;
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
			$userStatus = "<a href='index.php?page=login'>Guest (Login)</a>";
			if ($this->auth->isLoggedIn())
				$userStatus = '<a href="index.php?page=login&get=logout">Logout (' . $this->auth->getUsername() . ')</a>';
				
			?>
			<html> 
				<head>
                	<?php if ($this->refresh) echo '<meta http-equiv="refresh" content="3;url=index.php">'; ?>
					<title><?php echo $compiledTitle; ?></title>
					<meta http-equiv="PRAGMA" content="NO-CACHE">
                    <link rel="shortcut icon" href="images/favicon.ico" />
                    <link href="css/datatables.css" rel="stylesheet" type="text/css" />
                    <link rel="stylesheet" type="text/css" href="css/custom-theme/jquery-ui-1.8.16.custom.css" />
					<link rel="stylesheet" href="js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
					<link href="css/styles.css" rel="stylesheet" type="text/css" />
                    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
                    <script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
        			<script type="text/javascript" src="js/jquery.tools.min.js"></script>
					<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
					<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
					<script type="text/javascript" src="js/combobox.js"></script>
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
				            	
				            	<!-- Menu -->
				            	<div class="menu_container">
					                <ul class="main_menu">
					                    <li><a href="index.php">Home</a></li>
					                    <li><a href="index.php?page=requests">Make Request</a></li>
					                    <li><a href="index.php?page=responses">Request/Response Status</a></li>
					                    <li><a href="index.php?page=roomavailability">Room Availability</a></li>
					                    <li class="last"><a href="index.php?page=modules">Modules</a></li>
					                </ul>
				                </div>
				                
				                <!-- Semester Title & Buttons -->
								<div id="semTitle" class="semTitle" >
									<b> Semester: </b>
									<span class="semSel">	
										<input type="radio" id="sem1" name="sem" checked="checked" value="1" /> 1
										<input type="radio" id="sem2" name="sem" value="2" /> 2
									</span>
								</div>
								
								<!-- Round Indicator -->
								<div id="ri" class="roundInd"><h3>1</h3></div> 
								<div class="tooltip">
									<div><h1>Round 1</h1></div>
									<div>Start Date: <span id="sdate">wegew</span> </div>
									<div>End Date: <span id="edate">wrgwe</span> </div>
								</div>
				                
				                <!-- Logout Button -->
				                <div class="logout"><a href="index.php?page=login&get=logout"><?php echo $userStatus; ?> </a></div>
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
