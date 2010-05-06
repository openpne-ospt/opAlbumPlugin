<?php

	class FNUser {
	
	
		function FNUser() {
			GLOBAL $FN_DB;
		
			$this->requestUri = $_SERVER['REQUEST_URI'];
			$this->script_name = $_SERVER['SCRIPT_NAME'];
			$this->query_string = $_SERVER['QUERY_STRING'];
			
		}


		function addUser($username, $email, $password) {
			global $FN_DB;
			$query = "INSERT INTO fn_users (username, email, password) VALUES ('$username', '$email', '$password')";
			$FN_DB->query($query);
		}

		function loginUser($username, $password) {
			global $FN_DB;
			$query = "SELECT * FROM fn_users WHERE username = '$username' AND password = '$password'";
			$r = $FN_DB->query($query);
			if ($r->numrows() == 1) {
				$_SESSION['username'] = $username;
				$_SESSION['password'] = md5($password);
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
		
		function logoutUser() {
			unset($_SESSION['username']);
			unset($_SESSION['password']);
			if (!ini_get('register_globals')) {
				session_unregister('username');
				session_unregister('password');
			}
			return TRUE;
		}		
		
		function loggedInUser() {
			global $FN_DB;
			if (isset($_SESSION['username'])) {					
				$query = "SELECT password FROM fn_users WHERE username = '$_SESSION[username]'";
				$r = $FN_DB->query($query);
				$row = $r->fetch();
				if (md5($row['password']) == $_SESSION['password']) {
					return $_SESSION['username'];

				}
				else {
					unset($_SESSION['username']);
					unset($_SESSION['password']);
				}
			}
			return FALSE;
		}
		
		function userInfo($username) {
			global $FN_DB;
			$query = "SELECT * FROM fn_users WHERE username = '$username'";
			$r = $FN_DB->query($query);
			if ($r->numrows() == 1) {
				$info = $r->fetch();
				return $info;
			}
			else {
				return FALSE;
			}
		}
		
		function modifyUserInfo($username, $email, $password) {
			global $FN_DB;				
			if ($password <> '') {
				$query = "UPDATE fn_users SET email = '$email', password = '$password' WHERE username = '$username'";			
			}
			else {
				$query = "UPDATE fn_users SET email = '$email' WHERE username = '$username'";
			}
			$r = $FN_DB->query($query);		
		}
		
		function getDisplayNameByUserID($userid) {
			// stub to return userDisplayName
			return $_SESSION['username']; 
		
		}
		
		
		function getProperNameByUserID($userid) {
			// stub to return userDisplayName
			return $_SESSION['username']; 
		
		}

		function userPrivilege($privilege) {
			global $FN_DB;
			/*
			if (isset($_SESSION['username'])) {					
				$query = "SELECT password FROM fn_users WHERE username = '$_SESSION[username]'";
				$r = $FN_DB->query($query);
				$row = $r->fetch();
				if (md5($row['password']) == $_SESSION['password']) {
					return $_SESSION['username'];
				}
				else {
					unset($_SESSION['username']);
					unset($_SESSION['password']);
				}
			}
			*/
			// This needs to be improved and check db
			if ( $this->loggedInUser() ) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
		
		function getUserPermissionArchive($permission, $archive, $username='') {
			global $FN_DB;
			
			// if no archive specify grant permission
			if (!$_GET['archive']) {
				return true;
			}
			
			//if archive is public return true;
			// This needs to check better for single result rather than array
			$privatestatus = FNArchive::getArchiveProperty($_GET['archive'],'private');
			if ($privatestatus['0']['archive_property_value'] == 'false') {
				return true;
			} 
			if (!$username) {
				// search for logged in user
				$query = "SELECT user_property_value FROM fn_user_properties WHERE user_property = '".$archive."' AND username='".$this->loggedInUser()."'";
			} else {
				$query = "SELECT user_property_value FROM fn_user_properties WHERE user_property = '".$archive."' AND username='".$username."'";
			}
			$r = $FN_DB->query($query);
			if ($r->numrows() == 1) {
				$properties = $r->fetch();
				//echo "properties: ".$properties['user_property_value']." <br>";
				//$permissions 
				$permissions = split(" ",$properties['user_property_value']);
				//echo "permissions count; ".count($permissions)."<br />";
				if (in_array ($permission, $permissions) ||
					in_array ("editor", $permissions) ||
					in_array ("administrator", $permissions) ) 
				{
					//echo "permission found $permission: ". $permissions[$permission]."<br>" ;
					return true;
				} else {
					//echo "permission $permission not found<br>";
					return false;
				}
			}
			else {
				return FALSE;
			}
		}
		
		
		
		function setUserPermissionArchive($permission, $archive, $username='') {
			global $FN_DB;
			if (!$username) {
				// search for logged in user
				$query = "SELECT user_property_value FROM fn_user_properties WHERE user_property = '".$archive."' AND username='".$this->loggedInUser()."'";
			} else {
				$query = "SELECT user_property_value FROM fn_user_properties WHERE user_property = '".$archive."' AND username='".$username."'";
			}
			$r = $FN_DB->query($query);
			if ($r->numrows() == 1) {
				$properties = $r->fetch();
				//echo "properties: ".$properties['user_property_value']." <br>";
				//$permissions 
				$permissions = split(" ",$properties['user_property_value']);
				//echo "permissions count; ".count($permissions)."<br />";
				if (in_array ($permission, $permissions) ||
					in_array ("editor", $permissions) ||
					in_array ("administrator", $permissions) ) 
				{
					echo "permission found $permission: ". $permissions[$permission]."<br>" ;
					return true;
					
				} else {
				
					$new_permissions = $properties['user_property_value'] . " " . $permission;
					if (!$username) {
						$query = "UPDATE fn_user_properties SET user_property_value = '".$new_permissions."' WHERE user_property = '".$archive."' AND username='".$this->loggedInUser()."'";
					} else {
						$query = "UPDATE fn_user_properties SET user_property_value = '".$new_permissions."' WHERE user_property = '".$archive."' AND username='".$username."'";
					}
					$r = $FN_DB->query($query);
					return true;
				}
			}
			else {
					
					$new_permissions = $permission;
					if (!$username) {
						$query = "INSERT INTO fn_user_properties (user_property, user_property_value, username)
							VALUES ('".$archive."','".$new_permissions."','".$this->loggedInUser()."')";
					} else {
							$query = "INSERT INTO fn_user_properties (user_property, user_property_value, username)
							VALUES ('".$archive."','".$new_permissions."','".$username."')";					}
					$r = $FN_DB->query($query);
				return true;
			}
		}		
		
		
		
	}
?>