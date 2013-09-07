<?php

function username_already_exists($db, $username) {

	$select_username_sql = "SELECT id FROM ".$db->database.".users WHERE email = ?";

	$query = $db->query($select_username_sql, array($username));

	if ($query->num_rows() > 0) {

		return true;
	}

	return false;
}

function insert_new_user($db, $first_name, $last_name, $email, $password, $role=3, $active=0) {
	
    /*var_dump($first_name);
    var_dump($last_name);
    var_dump($email);
    var_dump($password);
    var_dump($role);
    var_dump($active);*/

	require_once('includes/php/library/crypto.inc.php');

    // Open the AES cipher
    /*$aes_cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', 'cbc', '');
    
    // Create salt and determine the keysize length
    $music_salt = mcrypt_create_iv(mcrypt_enc_get_iv_size($aes_cipher), MCRYPT_RAND);
    $music_salt_length = mcrypt_enc_get_key_size($aes_cipher);
    $queue_salt = mcrypt_create_iv(mcrypt_enc_get_iv_size($aes_cipher), MCRYPT_RAND);
    $queue_salt_length = mcrypt_enc_get_key_size($aes_cipher);
    $art_salt = mcrypt_create_iv(mcrypt_enc_get_iv_size($aes_cipher), MCRYPT_RAND);
    $art_salt_length = mcrypt_enc_get_key_size($aes_cipher);*/

    // I'm going to use these for the password salt rather than MCRYPT_RAND because the
    // randomness of MCRYPT_RAND can vary on different systems.  Basically, I still use the
    // system random number generator at the core, but only to determine the distribution of
    // the different characters (i.e. password_pask to pass into the create password function)
    // which is then used to randomly generate the password which will have a much larger
    // keyspace than a simple random number.  The keyspace would be about 68^32...which is
    // pretty ridiculous.  
    $password_mask = generate_password_mask(32);
    $password_salt = create_password($password_mask);
    $salted_password_hash = hash('SHA512', hash('SHA512', $password).$password_salt);

    // created the sha512 hash of the password
    //$hashed_password = hash("sha512", $password);

    /*var_dump($music_salt);
    var_dump($music_salt_length);
    var_dump($queue_salt);
    var_dump($queue_salt_length);
    var_dump($art_salt);
    var_dump($art_salt_length);
    var_dump($hashed_password);*/
	
	$insert_user_sql = "INSERT INTO ".$db->database.".users (first_name, last_name, email, password, password_salt, role, created, modified, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$created = date("Y-m-d h:i:s", strtotime("now")); 
	$modified = date("Y-m-d h:i:s", strtotime("now")); 

	$query = $db->query($insert_user_sql, array($first_name, $last_name, $email, $salted_password_hash, $password_salt, $role, $created, $modified, $active));

	$new_user_id = $db->insert_id();

    //var_dump($db->affected_rows());

	if ($db->affected_rows() == 1) {

		return $new_user_id;
	}

	return false;
} 

function update_existing_user($db, $first_name, $last_name, $email, $password, $user_id) {

    if (isset($password) && $password != '') {
    
        require_once('includes/php/library/crypto.inc.php');

        $password_mask = generate_password_mask(32);
        $password_salt = create_password($password_mask);
        $salted_password_hash = hash('SHA512', hash('SHA512', $password).$password_salt);
        
        $update_existing_user_sql = "UPDATE ".$db->database.".users SET first_name=?, last_name=?, email=?, password=?, password_salt=?, modified=? WHERE id=?";

        $modified = date("Y-m-d h:i:s", strtotime("now")); 

        $query = $db->query($update_existing_user_sql, array($first_name, $last_name, $email, $salted_password_hash, $password_salt, $modified, $user_id));

    } else {

        $update_existing_user_sql = "UPDATE ".$db->database.".users SET first_name=?, last_name=?, email=?, modified=? WHERE id=?";

        $modified = date("Y-m-d h:i:s", strtotime("now")); 

        $query = $db->query($update_existing_user_sql, array($first_name, $last_name, $email, $modified, $user_id));
    }

    if ($db->affected_rows() == 1) {

        return true;
    }

    return false;
} 

function toggle_user_active($db, $user_id, $enabled=false) {

    $active = ($enabled ? 1 : 0);

    $toggle_user_active_sql = "UPDATE ".$db->database.".users SET modified=?, active=? WHERE id=?";

    $modified = date("Y-m-d h:i:s", strtotime("now")); 

    $query = $db->query($toggle_user_active_sql, array($modified, $active, $user_id));

    if ($db->affected_rows() == 1) {

        return true;
    }

    return false;
} 

function delete_user($db, $user_id, $firephp) {

    $delete_user_sql = "DELETE FROM ".$db->database.".users WHERE id=?";

    $query = $db->query($delete_user_sql, array($user_id));

    if ($db->affected_rows() == 1) {
        
        return true;
    }

    return false;
}

function get_available_languages($db) {

    $languages = array();

    $sql = "SELECT * FROM ".$db->database.".languages";

    $query = $db->query($sql);

    if ($query->num_rows() > 0) {

        foreach ($query->result_array() as $row) {

            $languages[] = $row;
        }
    }

    return $languages;
}

function get_users($db) {

    $users = array();

    $sql = "SELECT id, email FROM ".$db->database.".users ORDER BY id ASC";

    $query = $db->query($sql);

    if ($query->num_rows() > 0) {

        foreach ($query->result_array() as $row) {

            $users[] = $row;
        }
    }

    return $users;
}

function get_user_info ($db, $user_id) {

    $user_info = array();

    $get_user_info_sql = "SELECT first_name, last_name, email, active FROM ".$db->database.".users WHERE id=?";

    $query = $db->query($get_user_info_sql, array($user_id));

    if ($query->num_rows() > 0) {

        $row = $query->row();
    }

    if (isset($row)) {

        $user_info['first_name']    = $row->first_name;
        $user_info['last_name']     = $row->last_name;
        $user_info['email']         = $row->email;
        $user_info['active']        = $row->active;

    } else {
        
        $user_info = NULL;
    }

    if (isset($user_info)) {
        
        return $user_info;
    }
                
    return false;
}

?>