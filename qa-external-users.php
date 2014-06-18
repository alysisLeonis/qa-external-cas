<?php

/*
	Question2Answer by Gideon Greenspan and contributors

	http://www.question2answer.org/

	
	File: qa-external-example/qa-external-users.php
	Version: See define()s at top of qa-include/qa-base.php
	Description: Example of how to integrate with your own user database


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/


/*
	=========================================================================
	THIS FILE ALLOWS YOU TO INTEGRATE WITH AN EXISTING USER MANAGEMENT SYSTEM
	=========================================================================

	It is used if QA_EXTERNAL_USERS is set to true in qa-config.php.
*/

	/*
	=========================================================================
	THIS FILE ALLOWS YOU TO INTEGRATE WITH AN EXISTING USER MANAGEMENT SYSTEM
	=========================================================================

	It is used if QA_EXTERNAL_USERS is set to true in qa-config.php.
*/

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../');
	exit;
}

	// Loading configuration
	require_once 'config.php';
	require_once QA_INCLUDE_DIR.'qa-db-users.php';

/*
	==============
	Initiliaze CAS
	==============
 */
	phpCAS::client(CAS_VER, CAS_HOST, CAS_PORT, CAS_CTX);
	
	// SSL certification validation
	if (constant('CAS_CA_CERT_FILE')!="") {
		phpCAS::setCasServerCACert(CAS_CA_CERT_FILE);
	}
	else {
		phpCAS::setNoCasServerValidation();
	}


	/*
	CAS user info
	*/
	function get_user_infos($cas_uid) {



		if (phpCAS::isAuthenticated()) {
			if (isset($_SESSION['UserInfos'][$cas_uid]) ) {
			//	return $_SESSION['UserInfos'][$user];
			}
		}

		$infos=array();

		global $CAS_USERS_ROLE;
		$dbuser_ids = qa_db_user_find_by_handle($cas_uid);
		if (!empty($dbuser_ids)) {
			$user_id = $dbuser_ids[0];
			update_attributes($user_id);
			return get_user_data($cas_uid);
		}
		
		else {
			if (phpCAS::isAuthenticated()) {
				if (phpCAS::getUser() == $cas_uid) {
					$attributes = get_attributes_from_cas();
					if (isset($CAS_USERS_ROLE[$user])) {
						$user_level = $CAS_USERS_ROLE[$user];
					}
					else {
						$user_level = CAS_DEFAULT_USER_ROLE;
					}
					$newid = qa_db_user_create($attributes['email'], 'randompw',$attributes['publicusername'], $user_level, '127.0.0.1');
					update_attributes($newid);

					return get_user_data($cas_uid);

				} else {
					error_log('eingeloggt, qa und cas out of order -_* ');
					return null;
				}
			} else {
				error_log( "ERROR: not authenticated in cas");
				return null;
			}
				

				
			
		}	

		error_log("User $user infos : ".implode(",",$infos));
		$_SESSION['UserInfos'][$user]=$infos;

		return $infos;

	}

	function get_user_data($cas_uid) {
			$infos = array(); 
			$attributes = get_user_attributes_by_handle($cas_uid);

			$infos['displayname'] = $attributes['content'];
			$infos['email'] = $attributes['email'];
			$infos['userid'] = $attributes['userid'];
			$infos['publicusername'] = $attributes['handle'];

			return $infos;
	}		

	function get_attributes_from_cas() {
		$cas_attributes = phpCAS::getAttributes();
		$cas_uid = phpCAS::getUser();

		$cas_info = array();

		$cas_info['publicusername'] = $cas_uid;
		$cas_info['email'] = $cas_attributes['mail'];
		$cas_info['displayname'] = $cas_attributes['cn'];		
		return $cas_info;
	}

	function update_attributes($userid) {
		$cas_attributes = get_attributes_from_cas();

		qa_db_user_profile_set($userid, 'name', $cas_attributes['displayname']);
		qa_db_user_profile_set($userid, 'location', '');
		qa_db_user_profile_set($userid, 'website', '');
		qa_db_user_profile_set($userid, 'about', '');
	}

	function get_user_attributes_by_handle($handle) 	{
		$query = qa_db_query_sub(
			'SELECT u.handle, u.userid, up.content, u.email FROM ^users u JOIN ^userprofile up ON u.userid = up.userid WHERE up.title = "name" AND u.handle IN ($)',
			$handle
			);
		$result = qa_db_read_one_assoc($query, false);
		return $result;
	}

	function qa_get_mysql_user_column_type()
	{	
		return 'VARCHAR(32)';	
	}


	function qa_get_login_links($relative_url_prefix, $redirect_back_to_url)
	{

		return array(
			'login' => '../cas/login',
			'register' => '../',
			'logout' => $relative_url_prefix.'qa-external-cas/logout.php',
		);

	


	}


	function qa_get_logged_in_user()	
	{

		phpCAS::forceAuthentication();
		session_start();

		$cas_uid = phpCAS::getUser();
		if (!empty($cas_uid)) {			
			$user =	get_user_infos($cas_uid);

			return $user;
		}

		return null;
		
	
	}

	
	function qa_get_user_email($userid)	{
	
		$user =	get_user_infos($cas_uid);
		return $user['email'];
	}
	

	function qa_get_userids_from_public($publicusernames)
	{

		return qa_db_user_get_handle_userids($publicusernames);
		
	}


	function qa_get_public_from_userids($userids)
	{
		$array = qa_db_user_get_userid_handles($userids);	
		return $array;

	}


	function qa_get_logged_in_user_html($logged_in_user, $relative_url_prefix)
	{
		$publicusername=$logged_in_user['publicusername'];
		return '<a href="'.qa_path_html('user/'.$publicusername).'" class="qa-user-link">'.htmlspecialchars($logged_in_user['displayname']).'</a>';
	}


	function qa_get_users_html($userids, $should_include_link, $relative_url_prefix)
	{

		$usershtml=array();

		$cas_uids = qa_get_public_from_userids($userids);
		
		foreach ($userids as $userid) {
			if ($userid != 0) {
				// echo "id to public".$cas_uids[$userid];
				// echo "<br>le userid:".$userid;
				$publicusername=$cas_uids[$userid];

				$usernames = get_user_data($cas_uids[$userid]);
				$displayname = $usernames['displayname'];

				$usershtml[$userid]=htmlspecialchars($publicusername);
				
				if ($should_include_link)
					//$usershtml[$userid]='<a href="'.qa_path_html('user/'.$publicusername).'" class="qa-user-link">'.htmlspecialchars($displayname).'</a>';
					$usershtml[$userid]='<a href="'.qa_path_html('user/'.$publicusername).'" class="qa-user-link">'.$displayname.'</a>';
			}
		}

		return $usershtml;

	
	}


	function qa_avatar_html_from_userid($userid, $size, $padding)
	{
		return null; // show no avatars by default

	
	}


	function qa_user_report_action($userid, $action)
	{
		
	}
