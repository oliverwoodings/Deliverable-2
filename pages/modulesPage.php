<?php 

	/**
	 *	File: entities/modulesPage.php
	 *	Page: Modules
	 *  Author: Oliver Woodings and Alex Shea
	 *  Functionality: Allows the user to add/remove modules and assign lecturers
	 */

	class modules {
		
		function run($parent) {
			
			$parent->auth->requireLogin();
			
			if (isset($parent->get)) {
			
				switch ($parent->get) {
					//Return all modules
					case "all":
						$modules = array();
						foreach($parent->db->getModules() as $module) {
							array_push($modules, $module->getAsArray());
						}
						echo json_encode($modules);
						break;
						
					//Return all lecturers
					case "lecturers":
						$lecturers = array();
						foreach ($parent->db->getLecturers() as $lecturer) {
							array_push($lecturers, get_object_vars($lecturer));
						}
						echo json_encode($lecturers);
						break;
						
					//Save a module
					case "save":
						//Validation
						if (!isset($_GET["old_code"]) || strlen($_GET["old_code"]) < 3 || !$parent->db->getModuleByCode($_GET["old_code"])) return $parent->errorJSON(array(), "Module match error!");
						if (!isset($_GET["new_code"]) || strlen($_GET["new_code"]) < 3) return $parent->errorJSON(array(), "Invalid module code!");
						if (!isset($_GET["module_name"]) || strlen($_GET["module_name"]) < 3) return $parent->errorJSON(array(), "Invalid module name!");
						$lecturers = false;
						if (isset($_GET["lecturers"])) {
							$lecturers = json_decode($_GET["lecturers"]);
							if (count($lecturers) > 0) { 
								foreach ($lecturers as $lecturer) {
									if (!$parent->db->getLecturerById($lecturer)) return $parent->errorJSON(array(), "Invalid lecturer!");
								}
							}
						}
						//Update info
						$module = $parent->db->getModulebyCode($_GET["old_code"]);
						$module->setCode($_GET["new_code"]);
						$module->setName($_GET["module_name"]);
						$lecEntities = array();
						foreach ($lecturers as $lecturer) {
							array_push($lecEntities, $parent->db->getLecturerById($lecturer));
						}
						$module->setLecturers($lecEntities);
						//Save the module
						$parent->db->saveModule($module);
						break;
						
					//Add a module
					case "add":
						//Validation
						if (!isset($_GET["module_code"]) || strlen($_GET["module_code"]) < 3) return $parent->errorJSON(array(), "Invalid module code!");
						if ($parent->db->getModuleByCode($_GET["module_code"])) return $parent->errorJSON(array(), "Module code already exists!" . $_GET["module_code"]);
						if (!isset($_GET["module_name"]) || strlen($_GET["module_name"]) < 3) return $parent->errorJSON(array(), "Invalid module name!");
						$lecturers = false;
						if (isset($_GET["lecturers"])) {
							$lecturers = json_decode($_GET["lecturers"]);
							if (count($lecturers) > 0) { 
								foreach ($lecturers as $lecturer) {
									if (!$parent->db->getLecturerById($lecturer)) return $parent->errorJSON(array(), "Invalid lecturer!");
								}
							}
						}
						//Update info
						$lecEntities = array();
						foreach ($lecturers as $lecturer) {
							array_push($lecEntities, $parent->db->getLecturerById($lecturer));
						}
						$module = new Module($_GET["module_name"], $parent->db->getDepartmentById($parent->auth->getUserId()), $_GET["module_code"], $lecEntities);
						//Save the module
						$parent->db->saveModule($module);
						break;
						
					//Remove a module
					case "delete":
						if (!isset($_GET["module_code"]) || !$parent->db->getModuleByCode($_GET["module_code"])) return $parent->errorJSON(array(), "Invalid Module!");
						$parent->db->deleteModuleByCode($_GET["module_code"]);
						break;
						
					//Return a module
					default:
						$module = $parent->db->getModuleByCode($parent->get);
						if (!$module) return;
						$lecturers = array();
						foreach ($module->getLecturers() as $lecturer) array_push($lecturers, get_object_vars($lecturer));
						echo json_encode(array("id" => $module->getId(), "name" => $module->getName(), "code" => $module->getCode(), "department" => get_object_vars($module->getDepartment()), "lecturers" =>  $lecturers));
						break;
				}
				return;
			}
			
			$parent->title = "Modules";
			$parent->displayHeader();
			
			?>

			<!--<h2 class="section_head">Request Room</h2>-->
			<div id="module_actions">
				<a id="add_module" rel="#addModuleOverlay" class="module_action">Add Module</a>
				<a id="edit_module" rel="#editModuleOverlay" class="module_action">Edit Module</a>
				<a id="remove_module" class="module_action">Remove Module</a>
				<div id="confirm" style="display:none;">Are you sure? <a id="yes" >Yes</a>/<a id="no">No</a></div>
			</div>
			
			<!-- ADD OVERLAY -->
			<div id="addModuleOverlay" class="overlay">
				<h2 id="model_head">Add Module</h2>
				<div class="option">
					<label>Module Code</label>
					<input id="add_mod_code" type="text" class="ui-autocomplete-input" style="width:100px;margin-left:4px;"/>
				</div>
				<div class="option">
					<label>Module Name</label>
					<input id="add_mod_name" type="text" class="ui-autocomplete-input" style="width:200px;"/>
				</div>
				<div class="option">
					<label>Lecturer</label>
					<select id="add_lec_list" class="ui-autocomplete-input" >
						<?php
							foreach ($parent->db->getLecturers() as $lecturer) {
								echo "<option value='" . $lecturer->id . "'>" . $lecturer->name . "</option>";
							}
						
						?>
					</select>
					<div id="buttons">
						<input id="add_add_l" type="button" value="Add" />
						<input id="add_remove_l" type="button" value="Remove" />
					</div>
					<select id="add_lecturers" class="lecturers" size="4">
					</select>
				</div>
				<input id="sub_addmodule" type="button" value="Add Module" />
			</div>
			
			<!-- EDIT OVERLAY -->
			<div id="editModuleOverlay" class="overlay">
				<h2 id="model_head">Edit Module</h2>
				<div class="option">
					<label>Module Code</label>
					<input id="edit_mod_code" type="text" class="ui-autocomplete-input" style="width:100px;margin-left:4px;"/>
				</div>
				<div class="option">
					<label>Module Name</label>
					<input id="edit_mod_name" type="text" class="ui-autocomplete-input" style="width:200px;"/>
				</div>
				<div class="option">
					<label>Lecturer</label>
					<select id="edit_lec_list" class="ui-autocomplete-input" >
						<?php
							foreach ($parent->db->getLecturers() as $lecturer) {
								echo "<option value='" . $lecturer->id . "'>" . $lecturer->name . "</option>";
							}
						
						?>
					</select>
					<div id="buttons">
						<input id="edit_add_l" type="button" value="Add" />
						<input id="edit_remove_l" type="button" value="Remove" />
					</div>
					<select id="edit_lecturers" class="lecturers" size="4">
					</select>
				</div>
				<input id="sub_editmodule" type="button" value="Edit Module" />
			</div>
			
			
			<h2 class="section_head">Modules</h2>
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="modules">
				<thead>
					<tr>
						<th>Module Code</th>
						<th>Module Name</th>
						<th width="300px">Lecturers</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
	
			<?php
					
			$parent->displayFooter();
			
		}
		
	}

?>
