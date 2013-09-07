<?php

function get_theme_colors($db) {
	
	$theme_colors = array();

	$sql = "SELECT letter_code, name FROM ".$db->database.".themes_colors";

	$query = $db->query($sql);
	
	if ($query->num_rows() > 0) {

		foreach ($query->result() as $row) {

			$theme_colors[$row->letter_code] = strval($row->name);
		}
	}

	return $theme_colors;
}

function get_themes($db) {

	$themes = array();


	$sql = "SELECT * FROM ".$db->database.".themes";

	$query = $db->query($sql);

	if ($query->num_rows() > 0) {

		foreach ($query->result_array() as $row) {

			$themes[] = $row;
		}
	}

	return $themes;
}

function get_theme($db, $theme_id) {

	$theme = array();

	$get_theme_sql = "SELECT t.id, t.bars, t.buttons, t.body, t.controls, t.actions, t.active FROM ".$db->database.".themes t WHERE t.id=?";

	$query = $db->query($get_theme_sql, array($theme_id));

	if ($query->num_rows() > 0) {

		$row = $query->row();
	}

	if (isset($row)) {

		$theme['bars'] 		= $row->bars;
		$theme['buttons'] 	= $row->buttons;
		$theme['body'] 		= $row->body;
		$theme['controls'] 	= $row->controls;
		$theme['actions'] 	= $row->actions;
		$theme['active'] 	= $row->active;

	} else {
		
		$theme = NULL;
	}

	if (isset($theme)) {
		
		return $theme;
	}
				
	return false;
}

function insert_new_theme($db, $user_id, $theme_name, $bars_letter_code, $buttons_letter_code, $body_letter_code, $controls_letter_code, $action_buttons_letter_code, $active_state_letter_code) {

	$insert_new_theme_sql = "INSERT INTO ".$db->database.".themes (bars, buttons, body, controls, actions, active, name, creator, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$created = date("Y-m-d h:i:s", strtotime("now")); 
	$modified = date("Y-m-d h:i:s", strtotime("now")); 

	$query = $db->query($insert_new_theme_sql, array($bars_letter_code, $buttons_letter_code, $body_letter_code, $controls_letter_code, $action_buttons_letter_code, $active_state_letter_code, $theme_name, $user_id, $created, $modified));

	if ($db->affected_rows() == 1) {
		
		return true;
	}

	return false;
}
?>