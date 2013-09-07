<?php

function delete_station ($db, $station_id, $station_creator_id=NULL, $firephp=NULL) {

	$delete_station_sql = "DELETE FROM ".$db->database.".stations WHERE id=?";

	$query = $db->query($delete_station_sql, array($station_id, $station_creator_id));

	if ($db->affected_rows() == 1) {
		
		if (isset($station_creator_id)) {

			$delete_users_station_binding_sql = "DELETE FROM ".$db->database.".users_stations WHERE station_id=? AND user_id=?";

			$query = $db->query($delete_users_station_binding_sql, array($station_id, $station_creator_id));

			if ($db->affected_rows() == 1) {

				return true;
			}

		} else {

			return true;
		}
	}

	return false;
}

function insert_new_station($db, $station_name, $station_description, $station_url, $station_icon_id, $visibility, $creator, $owner=NULL, $firephp=NULL) {

	$station_url_hash = hash("sha512", $station_url);

	$firephp->log($station_url_hash, "station url hash");

	//$station = get_station_with_url($db, $station_url, $firephp);

	$firephp->log($owner, "owner");

	//if (!$station) {

		$insert_new_station_sql = "INSERT INTO ".$db->database.".stations (name, description, url, url_hash, icon_id, owner, creator, created, visibility) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$created = date("Y-m-d h:i:s", strtotime("now")); 
		$modified = date("Y-m-d h:i:s", strtotime("now")); 

		$query = $db->query($insert_new_station_sql, array($station_name, $station_description, $station_url, $station_url_hash, $station_icon_id, $owner, $creator, $created, $visibility));

		$inserted_station_id = $db->insert_id();

		if (!isset($owner)) {

			$insert_users_new_station_sql = "INSERT INTO ".$db->database.".users_stations (user_id, station_id) VALUES (?, ?)";

			$query = $db->query($insert_users_new_station_sql, array($creator, $inserted_station_id));
		}

		if (isset($inserted_station_id)) {
		
			return $inserted_station_id;
		}
	
	/*} else {

		$select_users_stations_binding_sql = "SELECT * FROM ".$db->database.".users_stations WHERE user_id=? AND station_id=?";

		$query = $db->query($select_users_stations_binding_sql, array($creator, $station['id']));

		if ($query->num_rows() == 0) {
			
			$insert_users_new_station_sql = "INSERT INTO ".$db->database.".users_stations (user_id, station_id) VALUES (?, ?)";

			$query = $db->query($insert_users_new_station_sql, array($creator, $station['id']));

			if ($db->affected_rows() == 1) {
		
				return $station['id'];
			}
		}

		return false;
	}*/

	return false;
}

function update_station($db, $user_id, $station_id, $station_name, $station_description, $station_url, $station_icon_id, $station_visibility) {

	$station_url_hash = hash("sha512", $station_url);

	$update_station_sql = "UPDATE ".$db->database.".stations SET name=?, description=?, url=?, url_hash=?, icon_id=?, visibility=?, modified=?, modified_by=? WHERE id=?";

	$modified = date("Y-m-d h:i:s", strtotime("now")); 

	$query = $db->query($update_station_sql, array($station_name, $station_description, $station_url, $station_url_hash, $station_icon_id, $station_visibility, $modified, $user_id, $station_id));

	if ($db->affected_rows() == 1) {
		
		return true;
	}

	return false;
}

function insert_new_station_icon($db, $user_id, $icon_filename, $icon_filepath, $icon_baseurl, $station_id=null) {

	$insert_new_station_icon_sql = "INSERT INTO ".$db->database.".stations_icons (filename, filepath, baseurl, creator, created, modified) VALUES (?, ?, ?, ?, ?, ?)";

	$created = date("Y-m-d h:i:s", strtotime("now")); 
	$modified = date("Y-m-d h:i:s", strtotime("now")); 

	$query = $db->query($insert_new_station_icon_sql, array($icon_filename, $icon_filepath, $icon_baseurl, $user_id, $created, $modified));

	$inserted_station_icon_id = $db->insert_id();

	if ($inserted_station_icon_id > 0) {
		
		return $inserted_station_icon_id;
	}

	return false;
}

function get_station_with_id($db, $station_id) {

	$station = array();

	$get_station_sql = "";
	$bind_parameter = "";

	if (isset($station_id) && ($station_id != null)) {

		// default to querying based on station_id
		$get_station_sql = "SELECT s.id, s.name, s.description, s.url, s.url_hash, si.id as icon_id, si.filename as icon, si.filepath as icon_path, si.baseurl as icon_url, s.visibility, s.owner, s.creator FROM ".$db->database.".stations s JOIN ".$db->database.".stations_icons si ON s.icon_id = si.id WHERE s.id=?";

		$bind_parameter = $station_id;

	}  else {
		
		return false;
	}

	$query = $db->query($get_station_sql, array($bind_parameter));

	if ($query->num_rows() > 0) {

		$station = $query->row();

		if ($station) {

			$station = array('id'=>$station->id, 'name'=>$station->name, 'description'=>$station->description, 'url'=>$station->url, 'url_hash'=>$station->url_hash, 'icon_id'=>$station->icon_id, 'icon'=>$station->icon, 'icon_path'=>$station->icon_path, 'icon_url'=>$station->icon_url, 'visibility'=>$station->visibility, 'owner'=>$station->owner, 'creator'=>$station->creator);

		} else {
			
			$station = NULL;
		}
	}

	if (isset($station)) {
		
		return $station;
	}
				
	return false;
}

function get_station_with_url($db, $url, $firephp=NULL) {

	$station = array();

	$get_station_sql = "";
	$bind_parameter = "";
	$url_hash = hash("sha512", $url);

	$firephp->log($url_hash, "got station with url_hash");

	if (isset($url_hash) && ($url_hash != null)) {

		$get_station_sql = "SELECT s.id, s.name, s.description, s.url, s.url_hash, si.filename as icon, si.filepath as icon_path, si.baseurl as icon_url, s.owner, s.creator FROM ".$db->database.".stations s JOIN ".$db->database.".stations_icons si ON s.icon_id = si.id WHERE s.url_hash=?";

		$bind_parameter = $url_hash;

	}  else {
		
		return false;
	}

	$query = $db->query($get_station_sql, array($bind_parameter));

	if ($query->num_rows() > 0) {

		$station = $query->row();

		if ($station) {

			$station = array('id'=>$station->id, 'name'=>$station->name, 'description'=>$station->description, 'url'=>$station->url, 'url_hash'=>$station->url_hash, 'icon'=>$station->icon, 'icon_path'=>$station->icon_path, 'icon_url'=>$station->icon_url, 'owner'=>$station->owner, 'creator'=>$station->creator);

		} else {
			
			$station = NULL;
		}
	}

	if (isset($station)) {
		
		return $station;
	}
				
	return false;
}

function get_station_with_name($db, $name, $id=NULL) {

	$station = array();

	$get_station_sql = "";

	$bind_parameters = array();

	if (isset($name) && ($name != null)) {

		$get_station_sql = "SELECT s.id, s.name, s.description, s.url, s.url_hash, si.filename as icon, si.filepath as icon_path, si.baseurl as icon_url, s.owner, s.creator FROM ".$db->database.".stations s JOIN ".$db->database.".stations_icons si ON s.icon_id = si.id WHERE s.name=?";

		$bind_parameters[] = $name;

	}  else {
		
		return false;
	}

	if (isset($id)) {

		$get_station_sql .= " AND s.id !=?";

		$bind_parameters[] = $id;
	}

	$query = $db->query($get_station_sql, $bind_parameters);

	if ($query->num_rows() > 0) {

		$station = $query->row();

		if ($station) {

			$station = array('id'=>$station->id, 'name'=>$station->name, 'description'=>$station->description, 'url'=>$station->url, 'url_hash'=>$station->url_hash, 'icon'=>$station->icon, 'icon_path'=>$station->icon_path, 'icon_url'=>$station->icon_url, 'owner'=>$station->owner, 'creator'=>$station->creator);

		} else {
			
			$station = NULL;
		}
	}

	if (isset($station)) {
		
		return $station;
	}
				
	return false;
}

function get_users_station($db, $user_id) {

	$users_station = array();

	$get_users_station_sql = "SELECT s.id, s.name, s.description, s.url, s.url_hash, si.id as icon_id, si.filename as icon, si.filepath as icon_path, si.baseurl as icon_url, s.visibility, s.owner, s.creator FROM ".$db->database.".stations s JOIN ".$db->database.".stations_icons si ON s.icon_id = si.id WHERE s.owner=?";

	//var_dump($get_users_station_sql);

	//var_dump($user_id);

	$query = $db->query($get_users_station_sql, array($user_id));

	//var_dump($query->num_rows());

	if ($query->num_rows() > 0) {

		foreach ($query->result() as $row) {

			$users_station[] = array('id'=>$row->id, 'name'=>stripslashes($row->name), 'description'=>stripslashes($row->description), 'url'=>$row->url, 'url_hash'=>$row->url_hash, 'icon_id'=>$row->icon_id, 'icon'=>$row->icon, 'icon_path'=>$row->icon_path, 'icon_url'=>$row->icon_url, 'visibility'=>$row->visibility, 'owner'=>$row->owner, 'creator'=>$row->creator);

			/*var_dump($row->id);
			var_dump($row->name);
			var_dump($row->description);
			var_dump($row->url);
			var_dump($row->url_hash);
			var_dump($row->icon_id);
			var_dump($row->icon);
			var_dump($row->icon_path);
			var_dump($row->icon_url);
			var_dump($row->visibility);
			var_dump($row->owner);
			var_dump($row->creator);*/
			
			//var_dump($users_station);			

		}
	}

	if (isset($users_station[0])) {
		
		return $users_station[0];
	}
				
	return false;
}

function get_users_stations($db, $user_id) {

	$get_users_stations_sql = "SELECT 	s.id, 
										s.name, 
										s.description, 
										s.url, 
										s.url_hash, 
										si.filename as icon, 
										si.filepath as icon_path, 
										si.baseurl as icon_url, 
										s.owner, 
										s.creator 
								FROM ".$db->database.".stations s 
								JOIN ".$db->database.".stations_icons si ON s.icon_id = si.id 
								JOIN ".$db->database.".users u ON s.owner = u.id
								WHERE (s.owner IS NOT NULL AND s.owner!=?) AND (u.active=1 AND s.visibility='public') 
								UNION 
								SELECT 	su.id, 
										su.name, 
										su.description, 
										su.url, 
										su.url_hash, 
										siu.filename as icon, 
										siu.filepath as icon_path, 
										siu.baseurl as icon_url, 
										su.owner, 
										su.creator 
								FROM ".$db->database.".users_stations us 
								JOIN ".$db->database.".stations su ON us.station_id = su.id 
								JOIN ".$db->database.".stations_icons siu ON su.icon_id = siu.id 
								WHERE ((su.owner IS NULL AND su.visibility='public') OR (su.owner IS NULL AND su.visibility='private' AND su.creator=?))";

	$query = $db->query($get_users_stations_sql, array($user_id, $user_id));

	$users_stations = array();

	if ($query->num_rows() > 0) {

		foreach ($query->result() as $row) {

			$users_stations[$row->id] = array('id'=>$row->id, 'name'=>stripslashes($row->name), 'description'=>stripslashes($row->description), 'url'=>$row->url, 'url_hash'=>$row->url_hash, 'icon'=>$row->icon, 'icon_path'=>$row->icon_path, 'icon_url'=>'/'.$row->icon_url, 'owner'=>$row->owner, 'creator'=>$row->creator);
		}
	}

	if (isset($users_stations)) {
		
		return $users_stations;
	}
				
	return false;
}

function get_station_icon( $db, $station_icon_id ) {

	$station_icon = array();

	$icon_url = "";

	// default to querying based on icon_id
	$get_station_icon_url_sql = "SELECT si.id as icon_id, si.filename as icon, si.filepath as icon_path, si.baseurl as icon_url FROM ".$db->database.".stations_icons si WHERE si.id=?";

	$result = $db->query($get_station_icon_url_sql, array($station_icon_id));

	if ($result->num_rows() > 0) {

		$station_icon = $result->row();

		if ($station_icon) {

			$station_icon = array('icon_id'=>$station_icon->icon_id, 'icon'=>$station_icon->icon, 'icon_path'=>$station_icon->icon_path, 'icon_url'=>$station_icon->icon_url );

		} else {
			
			$station_icon = NULL;
		}
	}

	if (isset($station_icon)) {
		
		return $station_icon;
	}
				
	return false;
}

?>