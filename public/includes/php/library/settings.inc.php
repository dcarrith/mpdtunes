<?php

function insert_update_users_preferences($db, $user_id, $theme_id=1, $mode="streaming", $crossfade=5, $volume_fade=5, $language_id=1) {

	$insert_update_users_preferences_sql = "INSERT INTO ".$db->database.".users_preferences (user_id, theme_id, mode, crossfade, volume_fade, language_id, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE theme_id=?, mode=?, crossfade=?, volume_fade=?, language_id=?, modified=?";

	$created = date("Y-m-d h:i:s", strtotime("now")); 
	$modified = date("Y-m-d h:i:s", strtotime("now")); 

	$query = $db->query($insert_update_users_preferences_sql, array($user_id, $theme_id, $mode, $crossfade, $volume_fade, $language_id, $created, $modified, $theme_id, $mode, $crossfade, $volume_fade, $language_id, $modified));

	// it seems that affected_rows retuns 1 if clean insert, and 2 if updating on duplicate key
	if (($db->affected_rows() == 1) || ($db->affected_rows() == 2)) {
		
		return true;
	}

	return false;
}

function delete_user_preferences($db, $user_id) {

	$delete_users_preferences_sql = "DELETE FROM ".$db->database.".users_preferences WHERE user_id=?";

	$query = $db->query($delete_users_preferences_sql, array($user_id));

	if ($db->affected_rows() == 1) {
		
		return true;
	}

	return false;
}

function get_user_preferences($db, $user_id) {

	$user_preferences = array();

	$get_user_preferences_sql = "SELECT up.theme_id, up.mode, up.crossfade, up.volume_fade, l.language_code, t.bars, t.buttons, t.body, t.controls, t.actions, t.active FROM ".$db->database.".users_preferences up JOIN ".$db->database.".themes t ON up.theme_id = t.id JOIN ".$db->database.".languages l ON up.language_id = l.id WHERE up.user_id=?";

	$query = $db->query($get_user_preferences_sql, array($user_id));

	if ($query->num_rows() > 0) {

		$row = $query->row();
	}

	if (isset($row)) {

		$theme_id = $row->theme_id;

		$user_preferences['theme_id'] 				= $theme_id;
		
		$user_preferences['mode'] 					= $row->mode;
		$user_preferences['crossfade'] 				= $row->crossfade;
		$user_preferences['volume_fade'] 			= $row->volume_fade;
		$user_preferences['language_code']			= $row->language_code;

		$user_preferences[$theme_id]['bars'] 		= $row->bars;
		$user_preferences[$theme_id]['buttons'] 	= $row->buttons;
		$user_preferences[$theme_id]['body'] 		= $row->body;
		$user_preferences[$theme_id]['controls'] 	= $row->controls;
		$user_preferences[$theme_id]['actions'] 	= $row->actions;
		$user_preferences[$theme_id]['active'] 		= $row->active;

	} else {
		
		$user_preferences = NULL;
	}

	if (isset($user_preferences)) {
		
		return $user_preferences;
	}
				
	return false;
}
?>