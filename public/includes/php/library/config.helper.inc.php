<?php
require_once('includes/php/library/crypto.inc.php');
require_once('includes/php/library/errors.inc.php');
/**
 * Function read_in_config_xml reads in a config XML file 
 * @param $encrypted is to indicate whether or not the actual config file is in an encrypted state on disk
 * @param $need_plaintext is to indicate whether or not the function should decrypt the config file or return it as it is encrypted
 * @return $config_xml is the encrypted or decrypted config XML as a string
 */ 
function read_in_config_xml( $dir='includes/xml/', $filename='config.xml', $encrypted=true, $compressed=true, $need_plaintext=true ) {

    // the absolute path to the config file to be read in
    $file = $dir . $filename;

    //var_dump($file);

    if ( file_exists( $file ) ){

        if ( $encrypted ) {

            $dom = new DOMDocument();
            
            try {

                set_error_handler('xml_error_handler');

                // try to load the config file as is to see if it's already decrypted
                if ($dom->load($file)) {
                        
                    $file_handle = fopen($file, 'rt');
                            
                    $config_xml = fread($file_handle, filesize($file));
                
                    fclose($file_handle);
                    
                    return $config_xml;
                }

                restore_error_handler();

            } catch (Exception $e) {
                
                //print("The config file seems to be encrypted as expected.\r\nProceeding with decryption.\r\n");
            }

            if ($need_plaintext){
        
                $file_handle = fopen($file, 'rb');
                        
                $encrypted = fread($file_handle, filesize($file));
            
                fclose($file_handle);

                $decrypted_config_xml = decrypt_data( $encrypted );

                //var_dump("decrypted_config_xml: \n".$decrypted_config_xml);

                if ( !$compressed ) {

                    return $decrypted_config_xml;
                }

                $decrypted_uncompressed_config_xml = decompress_config_xml($decrypted_config_xml);
            
                return $decrypted_uncompressed_config_xml;
            
            } else {
            
                $file_handle = fopen($file, 'rb');
                        
                $config_xml = fread($file_handle, filesize($file));
            
                fclose($file_handle);
                
                return $config_xml;
            }

        } else {
        
            $file_handle = fopen($file, 'rt');
                    
            $config_xml = fread($file_handle, filesize($file));
        
            fclose($file_handle);
            
            return $config_xml;
        }
    }
}
/**
 * Function config_xml_to_assoc parses the config XML file into an associative array of the same structure
 * @param $config_xml is the config XML file to parse
 * @return config_assoc_array which is the array representing the config XML
 */ 
function config_xml_to_assoc($config_xml, $constants_groups, $comment_expected=true){

    // create a master DOMDocument object for storing a newly modified XML document
    $config_file_xml_doc = new DOMDocument('1.0','UTF-8');  

    $config_assoc_array['config']['constants'] = array();

    //$config_xml = preg_replace('/\s\s+/', '', $config_xml);
    $config_xml = str_replace('&', '&amp;', $config_xml);

    $config_file_xml_doc->loadXML($config_xml);
    
    foreach($constants_groups as $constants_group) {

        // create the DOMXPath object to be used to query the XML results using XPATH
        $constants_retrieval_xpath = new DOMXPath($config_file_xml_doc);

        $retrieved_constants = $constants_retrieval_xpath->query('/config/constants/'.$constants_group);

        foreach ($retrieved_constants as $constant_dom_element){

            foreach ($constant_dom_element->childNodes as $constant_specific_element){
                
                $field_name = trim(preg_replace("/#text/", "", $constant_specific_element->nodeName));

                $field_value = trim($constant_specific_element->nodeValue);

                if ($field_name != ''){
                    
                    $config_assoc_array['config']['constants'][$constants_group][$field_name] = $field_value;
                }
            }
        }      
    }

    if ($comment_expected) {

        // create the DOMXPath object to be used to query the XML results using XPATH
        $config_comment_xpath = new DOMXPath($config_file_xml_doc);

        $config_comment = $config_comment_xpath->query('/config/comment');
        
        foreach ($config_comment as $comment_dom_element){

            $field_name = trim(preg_replace("/#text/", "", $comment_dom_element->nodeName));

            $field_value = trim($comment_dom_element->nodeValue);
            
            $config_assoc_array['config']['comment'] = $field_value;
        } 
    }
    
    return $config_assoc_array;
}

/**
 * Function assoc_to_config_xml creates the config XML based on the passed in associative array
 * @param $config_assoc_array is the associative array passed in to use in generating the config XML 
 * @return $config_xml the XML string representing the config 
 */ 
function assoc_to_config_xml($config_assoc_array, $include_comment=true){

    // create a master DOMDocument object for creating the new config XML document
    $master = new DOMDocument('1.0','UTF-8');
    $root = $master->createElement('config');
    $root = $master->appendChild($root);

    // add a new constants node in the master DOMDocument 'master' 
    $row_element = $master->createElement('constants');
    $root->appendChild($row_element);
    
    if ($include_comment) {

        // add a new comment node in the master DOMDocument 'master' 
        $comment_element = $master->createElement('comment');
        $root->appendChild($comment_element);

        // add the text value for the comment element
        $comment_value_element = $master->createTextNode($config_assoc_array['config']['comment']);
        $comment_element->appendChild($comment_value_element);
    }

    $constants_groups = array_keys($config_assoc_array['config']['constants']);

    foreach($constants_groups as $constants_group) {

        // add a new column node in the master DOMDocument 'master' to hold the tags childNodes
        $column_field_element = $master->createElement($constants_group);
        $row_element->appendChild($column_field_element);

        foreach ($config_assoc_array['config']['constants'][$constants_group] as $field_name => $field_value){
        
            // add a new tag element in the master DOMDocument 'master' 
            $config_db_field_element = $master->createElement($field_name);
            $column_field_element->appendChild($config_db_field_element);
            
            // add the text value for the column element
            $config_db_value_element = $master->createTextNode($field_value);
            $config_db_field_element->appendChild($config_db_value_element);
        }        
    } 
    
    $config_xml = trim($master->saveXML());

    return $config_xml;
}

/**
 * Function save_config_xml writes the config XML file based on the passed in associative array
 * @param $config_assoc_array is the associative array passed in to save as the config XML file
 * @param $encrypted true or false
 * @return bool to indicate whether a new config was saved or not (return value could be 0, 1 
 *              or -1 if the config file was the same as the existing config file)
 */ 
function save_config_xml($config_assoc_array, $encrypted=true, $compressed=true, $zipper=null, $firephp=null, $dir='includes/xml/', $filename='config.xml'){

    // the mercurial repo ignores the xml/* but it causes the xml dir to not exist in a fresh clone
    if (!file_exists($dir)) {

        // first, let's create the music, queue and art directoris for this new user.
        $firephp->log('mkdir '.$dir, "executing mkdir command");
        exec(escapeshellcmd('mkdir '.$dir));
    }

    // the absolute path to the config file to be read in
    $file = $dir . $filename;

    $new_config_xml = assoc_to_config_xml($config_assoc_array, false);

    $firephp->log($new_config_xml, "new_config_xml");
    
    /*if (($compressed && isset($zipper))) {
        
        // add the data to be compressed by the Zip library
        $zipper->add_data($filename, $new_config_xml);

        // get the compressed data
        $new_config_xml = $zipper->get_zip();

        $zipper->clear_data();

        $firephp->log(utf8_encode($new_config_xml), "compressed_config_xml");
    }

    if ($encrypted) {

        $new_config_xml = encrypt_data($new_config_xml);

        $firephp->log(utf8_encode($new_config_xml), "new_config_xml");
    }*/

    if ( ( $compressed && isset( $zipper ) ) ) {
        
        // add the data to be compressed by the Zip library
        $zipper->add_data($filename, $new_config_xml);

        // get the compressed data
        $new_config_xml = $zipper->get_zip();

        $zipper->clear_data();

        $firephp->log( utf8_encode($new_config_xml), "inside save_config_xml after compression - new_config_xml");

        $decompressed_config_xml = decompress_config_xml($new_config_xml);

        $firephp->log( utf8_encode($decompressed_config_xml), "inside save_config_xml after decompression - decompressed_config_xml");
    }

    if ($encrypted) {

        $new_config_xml = encrypt_data( $new_config_xml );

        $firephp->log(utf8_encode($new_config_xml), "inside save_config_xml after encryption of new_config_xml");

        $decrypted_config_xml = decrypt_data( $new_config_xml );

        $firephp->log(utf8_encode($decrypted_config_xml), "inside save_config_xml after decryption of new_config_xml");

        if ( $compressed ) {

            $decrypted_decompressed_config_xml = decompress_config_xml($decrypted_config_xml);

            $firephp->log( utf8_encode($decrypted_decompressed_config_xml), "inside save_config_xml after decryption and then decompression - decrypted_decompressed_config_xml");
        }
    }

    $file_handle = fopen($file, 'wb');
    $bytes_written = fwrite($file_handle, $new_config_xml);
    fclose($file_handle);
    
    $new_config_was_saved_successfully = (($bytes_written > 0) ? 1 : 0);

    return $new_config_was_saved_successfully;
}

/**
 * Function save_config_xml writes the config XML file based on the passed in associative array
 * @param $config_assoc_array is the associative array passed in to save as the config XML file
 * @param $encrypted true or false
 * @return bool to indicate whether a new config was saved or not (return value could be 0, 1 
 *              or -1 if the config file was the same as the existing config file)
 */ 
function save_user_config_xml($db, $user_id, $encrypted_config_xml) {

    // Prepare the insert statement for inserting into the config history table
    $insert_update_user_config_sql = "INSERT INTO ".$db->database.".users_configs (user_id, config) VALUES (?, ?) ON DUPLICATE KEY UPDATE config=?";
                
    $query = $db->query($insert_update_user_config_sql, array($user_id, $encrypted_config_xml, $encrypted_config_xml));

    // affected_rows will be 1 with normal insert, and 2 if on duplicate key update
    if (($db->affected_rows() == 1) || ($db->affected_rows() == 2)) {

        return true;
    }

    return false;
}


/**
 * Function save_config_xml writes the config XML file based on the passed in associative array
 * @param $config_assoc_array is the associative array passed in to save as the config XML file
 * @param $encrypted true or false
 * @return bool to indicate whether a new config was saved or not (return value could be 0, 1 
 *              or -1 if the config file was the same as the existing config file)
 */ 
function get_user_config($db, $user_id) {

    $encrypted_user_config_xml = "";

    // Prepare the select statement for selecting the encrypted user config
    $select_user_config_sql = "SELECT config FROM ".$db->database.".users_configs WHERE user_id=?";

    $query = $db->query($select_user_config_sql, array($user_id));

    if ($query->num_rows() > 0) {

        $row = $query->row();
    }

    $encrypted_user_config_xml = NULL;

    if (isset($row)) {

        $encrypted_user_config_xml = $row->config;

    } else {
        
        return false;
    }

    if (isset($encrypted_user_config_xml)) {

        $decrypted_user_config_xml = decrypt_data($encrypted_user_config_xml);

        $decrypted_uncompressed_user_config_xml = decompress_config_xml($decrypted_user_config_xml);
        
        $constants_groups = array(0=>'mpd', 1=>'music');

        $users_configs = config_xml_to_assoc($decrypted_uncompressed_user_config_xml, $constants_groups, false);

        return $users_configs;

    } else {
        
        return false;
    }
}

function delete_user_config($db, $user_id, $firephp) {

    $delete_users_config_sql = "DELETE FROM ".$db->database.".users_configs WHERE user_id=?";

    $query = $db->query($delete_users_config_sql, array($user_id));

    if ($db->affected_rows() == 1) {
        
        return true;
    }

    return false;
}

function decompress_config_xml($decrypted_config_xml, $debug=false) {
    
    /*
        $this->zipdata .=
        "\x50\x4b\x03\x04\x14\x00\x00\x00\x08\x00"
        .pack('v', $file_mtime)
        .pack('v', $file_mdate)
        .pack('V', $crc32)
        .pack('V', $compressed_size)
        .pack('V', $uncompressed_size)
        .pack('v', strlen($filepath)) // length of filename
        .pack('v', 0) // extra field length
        .$filepath
        .$gzdata; // "file data" segment
    */

    $beginning_string   = substr($decrypted_config_xml, 0, 10);
    $file_mtime         = unpack('v', substr($decrypted_config_xml, 10, 2));
    $file_mdate         = unpack('v', substr($decrypted_config_xml, 12, 2));
    $crc32              = unpack('V', substr($decrypted_config_xml, 14, 4));
    $compressed_size    = unpack('V', substr($decrypted_config_xml, 18, 4));
    $uncompressed_size  = unpack('V', substr($decrypted_config_xml, 22, 4));
    $filename_length    = unpack('v', substr($decrypted_config_xml, 26, 2));
    $extra_field_length = unpack('v', substr($decrypted_config_xml, 28, 2));
    $filename           = substr($decrypted_config_xml, 30, intval($filename_length[1]));
    $extra_field        = substr($decrypted_config_xml, (30 + intval($filename_length[1])), intval($extra_field_length[1]));
    $gzdata             = substr($decrypted_config_xml, (30 + intval($filename_length[1]) + intval($extra_field_length[1])), intval($compressed_size[1]));

    $left_at_the_end    = substr($decrypted_config_xml, (30 + intval($filename_length[1]) + intval($extra_field_length[1]) + intval($compressed_size[1])), (strlen($decrypted_config_xml) - (30 + intval($filename_length[1]) + intval($extra_field_length[1]) + intval($compressed_size[1]))));

    $first_two_bytes    = substr( $extra_field, 0, 2);
    $last_four_bytes    = substr( $extra_field, 3, 4);

    $decrypted_uncompressed_config_xml = gzuncompress($first_two_bytes.$gzdata.$last_four_bytes);

    if ($debug) {
    echo("<br />---------------------------------------------------------------------<br />");
    echo("beginning_string: ".$beginning_string);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("file_mtime: ");
    print_r($file_mtime);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("file_mdate: ");
    print_r($file_mdate);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("crc32: ");
    print_r($crc32);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("compressed_size: ");
    print_r($compressed_size);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("uncompressed_size: ");
    print_r($uncompressed_size);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("filename_length: ");
    print_r($filename_length);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("extra_field_length: ");
    print_r($extra_field_length);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("filename: ");
    print_r($filename);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("extra_field: ");
    print_r($extra_field);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("strlen(gzdata): ".strlen($gzdata));
    echo("<br />---------------------------------------------------------------------<br />");
    echo("first_two_bytes: ".$first_two_bytes);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("<br />---------------------------------------------------------------------<br />");
    echo("gzdata: ".$gzdata);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("<br />---------------------------------------------------------------------<br />");
    echo("last_four_bytes: ".$last_four_bytes);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("strlen(left_at_the_end): ".strlen($left_at_the_end));
    echo("<br />---------------------------------------------------------------------<br />");
    echo("left_at_the_end: ".$left_at_the_end);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("<br />---------------------------------------------------------------------<br />");
    var_dump($decrypted_uncompressed_config_xml);
    echo("<br />---------------------------------------------------------------------<br />");
    }
    /*
        $zip_data = $this->zipdata;
        $zip_data .= $this->directory."\x50\x4b\x05\x06\x00\x00\x00\x00";
        $zip_data .= pack('v', $this->entries); // total # of entries "on this disk"
        $zip_data .= pack('v', $this->entries); // total # of entries overall
        $zip_data .= pack('V', strlen($this->directory)); // size of central dir
        $zip_data .= pack('V', strlen($this->zipdata)); // offset to start of central dir
        $zip_data .= "\x00\x00"; // .zip file comment length
    */

    /*$comment_length               = substr($left_at_the_end, -2);
    $offset_to_start_central_dir    = unpack('V', substr($left_at_the_end, -6, -2));
    $size_of_central_dir            = unpack('V', substr($left_at_the_end, -10, -6));
    $total_num_entries_overall      = unpack('v', substr($left_at_the_end, -12, -10));
    $total_num_entries_this_disk    = unpack('v', substr($left_at_the_end, -14, -12));
    $directory_separator            = substr($left_at_the_end, -18, -14);
    $zipdata                        = substr($left_at_the_end, 0, -18);

    echo("<br />---------------------------------------------------------------------<br />");
    echo("comment_length: ".$comment_length);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("offset_to_start_central_dir: ");
    print_r($offset_to_start_central_dir);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("size_of_central_dir: ");
    print_r($size_of_central_dir);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("total_num_entries_overall: ");
    print_r($total_num_entries_overall);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("total_num_entries_this_disk: ");
    print_r($total_num_entries_this_disk);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("directory_separator: ");
    print_r($directory_separator);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("strlen(zipdata): ".strlen($zipdata));
    echo("<br />---------------------------------------------------------------------<br />");
    echo("zipdata: ".$zipdata);
    echo("<br />---------------------------------------------------------------------<br />");*/

    /*
        $this->directory .=
        "\x50\x4b\x01\x02\x00\x00\x14\x00\x00\x00\x08\x00"
        .pack('v', $file_mtime)
        .pack('v', $file_mdate)
        .pack('V', $crc32)
        .pack('V', $compressed_size)
        .pack('V', $uncompressed_size)
        .pack('v', strlen($filepath)) // length of filename
        .pack('v', 0) // extra field length
        .pack('v', 0) // file comment length
        .pack('v', 0) // disk number start
        .pack('v', 0) // internal file attributes
        .pack('V', 32) // external file attributes - 'archive' bit set
        .pack('V', $this->offset) // relative offset of local header
        .$filepath;
    */

    /*$beginning_string     = substr($zipdata, 0, 12);
    $file_mtime             = unpack('v', substr($zipdata, 12, 2));
    $file_mdate             = unpack('v', substr($zipdata, 14, 2));
    $crc32                  = unpack('V', substr($zipdata, 16, 4));
    $compressed_size        = unpack('V', substr($zipdata, 20, 4));
    $uncompressed_size      = unpack('V', substr($zipdata, 24, 4));
    $filename_length        = unpack('v', substr($zipdata, 28, 2));
    $extra_field_length     = unpack('v', substr($zipdata, 30, 2));
    $file_comment_length    = unpack('v', substr($zipdata, 32, 2));
    $disk_number_start      = unpack('v', substr($zipdata, 34, 2));
    $internal_file_attr     = unpack('v', substr($zipdata, 36, 2));
    $external_file_attr     = unpack('V', substr($zipdata, 38, 4));
    $relative_offset        = unpack('V', substr($zipdata, 42, 4));
    $filename               = substr($zipdata, 46, 15);
    $ending_string          = substr($zipdata, 61, (strlen($zipdata)-61) );

    echo("---------------------------------------------------------------------<br />");
    echo("beginning_string: ".$beginning_string);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("file_mtime: ");
    print_r($file_mtime);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("file_mdate: ");
    print_r($file_mdate);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("crc32: ");
    print_r($crc32);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("compressed_size: ");
    print_r($compressed_size);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("uncompressed_size: ");
    print_r($uncompressed_size);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("filename_length: ");
    print_r($filename_length);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("extra_field_length: ");
    print_r($extra_field_length);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("file_comment_length: ");
    print_r($file_comment_length);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("disk_number_start: ");
    print_r($disk_number_start);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("internal_file_attr: ");
    print_r($internal_file_attr);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("external_file_attr: ");
    print_r($external_file_attr);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("relative_offset: ");
    print_r($relative_offset);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("filename: ");
    print_r($filename);
    echo("<br />---------------------------------------------------------------------<br />");
    echo("ending_string: ".$ending_string);
    echo("<br />---------------------------------------------------------------------<br />");*/

    return $decrypted_uncompressed_config_xml;
}

function destroy_user_directories($mpd_dir, $music_dir, $queue_dir, $art_dir, $firephp) {

    /*exec(escapeshellcmd('rm -f '.$mpd_dir.'/playlists/*.m3u'));
    exec(escapeshellcmd('rm -f '.$mpd_dir.'/mpd.db'));
    exec(escapeshellcmd('rm -f '.$mpd_dir.'/mpd.log'));
    exec(escapeshellcmd('rm -f '.$mpd_dir.'/mpd.pid'));
    exec(escapeshellcmd('rm -f '.$mpd_dir.'/mpd.state'));
    exec(escapeshellcmd('rm -f '.$mpd_dir.'/mpd.conf'));
    exec(escapeshellcmd('rm -f '.$mpd_dir.'/*.jpeg'));
    exec(escapeshellcmd('rmdir '.$mpd_dir.'/playlists'));
    exec(escapeshellcmd('rmdir '.$mpd_dir));
    exec(escapeshellcmd('rm -r -f '.$music_dir));
    exec(escapeshellcmd('rm -f '.$queue_dir.'*.mp3'));
    exec(escapeshellcmd('rmdir '.$queue_dir));
    exec(escapeshellcmd('rm -f '.$art_dir.'*.jpeg'));
    exec(escapeshellcmd('rmdir '.$art_dir));*/

    // kill the process if there is one that exists
    $mpd_dir_array = explode("/", $mpd_dir);
    $mpd_dir_secret_section = $mpd_dir_array[1];
    $ps_output_array = array();
    $result = 0;
    exec("ps ax | grep ".$mpd_dir_secret_section." 2>&1 | awk '{ print $1}'", $ps_output_array, $result);
    $mpd_process_id = $ps_output_array[0];
    exec("kill -9 ".$mpd_process_id);

    exec(escapeshellcmd('rm -r -f '.$mpd_dir));
    exec(escapeshellcmd('rm -r -f '.$music_dir));
    exec(escapeshellcmd('rm -r -f '.$queue_dir));
    exec(escapeshellcmd('rm -r -f '.$art_dir));

    return true;
}


function setup_user_directories($base_directories, $anonymize=true, $master_setup=false, $firephp) {

    $mpd_dir        = NULL;
    $music_dir      = NULL;
    $queue_dir      = NULL;
    $art_dir        = NULL;
    $directories    = array();

    foreach($base_directories as $base_directory) {

        // the mercurial repo ignores the xml/* but it causes the xml dir to not exist in a fresh clone
        if (!file_exists($base_directory)) {

            // first, let's create the music, queue and art directoris for this new user.
            $firephp->log('mkdir -p '.$base_directory, "executing mkdir command");
            exec(escapeshellcmd('mkdir -p '.$base_directory));
        }
    }

    if (($master_setup) && (!$anonymize)) {

        $mpd_dir                = $base_directories['mpd_dir']."master/";
        $music_dir              = $base_directories['music_dir']."master/";
        $queue_dir              = $base_directories['queue_dir']."master/";
        $art_dir                = $base_directories['art_dir']."master/";
    
    } else {

        $secret_string_mask     = generate_password_mask(32);
        $firephp->log($secret_string_mask, "secret_string_mask");

        $secret_string          = create_password($secret_string_mask);
        $firephp->log($secret_string, "secret_string");

        $secret_string_hash     = hash('SHA512', $secret_string.$password_salt);
        $firephp->log($secret_string_hash, "secret_string_hash");

        $music_dir_secret       = substr($secret_string_hash, 0, 32);
        $queue_dir_secret       = substr($secret_string_hash, 31, 32);
        $art_dir_secret         = substr($secret_string_hash, 63, 32);
        $mpd_dir_secret         = substr($secret_string_hash, 95, 32);

        $mpd_dir                = $base_directories['mpd_dir'].$mpd_dir_secret."/";
        $music_dir              = $base_directories['music_dir'].$music_dir_secret."/";
        $queue_dir              = $base_directories['queue_dir'].$queue_dir_secret."/";
        $art_dir                = $base_directories['art_dir'].$art_dir_secret."/";
    }

    if ((isset($mpd_dir)) && (isset($music_dir)) && (isset($queue_dir)) && (isset($art_dir))) {

        $mpd_dir = ltrim($mpd_dir, "/");
        $queue_dir = ltrim($queue_dir, "/");
        $art_dir = ltrim($art_dir, "/");

        $directories['mpd_dir'] = $mpd_dir;
        $directories['music_dir'] = $music_dir;
        $directories['queue_dir'] = $queue_dir;
        $directories['art_dir'] = $art_dir;

        // first, let's create the music, queue and art directoris for this new user.
        $firephp->log('mkdir -p '.$music_dir, "mkdir music_dir");
        exec(escapeshellcmd('mkdir -p '.$music_dir));
        
        $firephp->log('mkdir -p '.$queue_dir, "mkdir queue_dir");
        exec(escapeshellcmd('mkdir -p '.$queue_dir));
        
        $firephp->log('mkdir -p '.$art_dir, "mkdir art_dir");
        exec(escapeshellcmd('mkdir -p '.$art_dir));

        // now, create the mpd directory
        $firephp->log('mkdir -p '.$mpd_dir, "mkdir mpd_dir");
        exec(escapeshellcmd('mkdir -p '.$mpd_dir));

        if (isset($directories)) {

            return $directories;
        }
    }

    return false;
}

function setup_user_mpd_instance($mpd_conf_parameters, $master_setup=false, $firephp=null) {

    $mpd_dir = $mpd_conf_parameters['mpd_dir'];

    exec(escapeshellcmd('mkdir '.$mpd_dir.'/playlists'));
    exec(escapeshellcmd('touch '.$mpd_dir.'/mpd.db'));
    exec(escapeshellcmd('touch '.$mpd_dir.'/mpd.log'));
    exec(escapeshellcmd('touch '.$mpd_dir.'/mpd.pid'));
    exec(escapeshellcmd('touch '.$mpd_dir.'/mpd.state'));

    $secret_string_mask = generate_password_mask(16);

    $mpd_password = create_password($secret_string_mask);
    
    $mpd_conf_parameters["password"] = $mpd_password;

    // Now, create the mpd.conf file for the master mpd instance
    $mpd_conf_file = $mpd_dir.'mpd.conf';

    if (isset($firephp)) {

        $firephp->log($secret_string_mask, "secret_string_mask");
        $firephp->log($mpd_password, "mpd_password");
        $firephp->log($mpd_conf_parameters, "mpd_conf_parameters");
        $firephp->log($mpd_conf_file, "mpd_conf_file");
    }

    $success = create_mpd_conf_file($mpd_conf_file, $mpd_conf_parameters, $master_setup);

    // check if success here
    if ($success) {

        // we only really need to spawn a new mpd instance when the user logs in
        /*$mpd_output = exec(escapeshellcmd('mpd '.$mpd_conf_file));

        $firephp->log($mpd_output, "mpd_output");*/

        return $mpd_conf_parameters;
    }

    return false;
}

function compress_config_xml($config_xml, $type, $zipper, $firephp) {

	// What filename to use in the zip archive 
	$config_file_name = 'config.xml';

	switch($type) {

		case 'user':
			$config_file_name = 'user_config.xml';
			break;
		case 'paypal':
			$config_file_name = 'paypal.xml';
			break;
		default :
			$config_file_name = 'config.xml';
			break;
	}

        // Add the data to be compressed by the Zip library
        $zipper->add_data($config_file_name, $config_xml);

        // Get the compressed data
        $compressed_config_xml = $zipper->get_zip();

        // Clear the zipper's buffers
        $zipper->clear_data();

        return $compressed_config_xml;
}

function create_compress_encrypt_config($configs, $type, $zipper, $firephp){

	$config_assoc_array = array();

	$config_assoc_array['config']['constants'] = $configs;

	// Create the config XML using the multi-dimensional associative array created with the passed in configs array
	$config_xml = assoc_to_config_xml($config_assoc_array);

	$firephp->log($config_xml, 'config_xml');
 
        // Compress the user's config xml
        $compressed_config_xml = compress_config_xml($config_xml, $type, $zipper, $firephp);

	$firephp->log(utf8_encode($compressed_config_xml), 'compressed_config_xml');

        // Encrypt the compressed user_config.xml
        $encrypted_config_xml = encrypt_data($compressed_config_xml);

	$firephp->log(utf8_encode($encrypted_config_xml), 'encrypted_config_xml');

        // Convert the encrypted binary data into hex and prepend it with '0x' to make it compatible with MySQL binary hex
        $config = '0x'.strtoupper(bin2hex($encrypted_config_xml));

	$firephp->log($config, 'binary hex config');

        return $config;
}

function create_mpd_conf_file($mpd_conf_file_path, $mpd_conf_parameters, $master_setup=false) {

    $document_root          = $mpd_conf_parameters['document_root'];
    $music_dir              = $mpd_conf_parameters['music_dir'];
    $mpd_dir                = $mpd_conf_parameters['mpd_dir'];
    $ip_address             = $mpd_conf_parameters['ip_address'];
    $port                   = $mpd_conf_parameters['port'];
    $mpd_password           = $mpd_conf_parameters['password'];
    $http_streaming_port    = $mpd_conf_parameters['http_streaming_port'];

    $mpd_privileges         = "read,add,control,admin";

    $mpd_conf_file = '
music_directory                         "'.$music_dir.'"
playlist_directory                      "'.$document_root.$mpd_dir.'playlists"
db_file                                 "'.$document_root.$mpd_dir.'mpd.db"
log_file                                "'.$document_root.$mpd_dir.'mpd.log"
pid_file                                "'.$document_root.$mpd_dir.'mpd.pid"
state_file                              "'.$document_root.$mpd_dir.'mpd.state"
bind_to_address                         "'.$ip_address.'"
port                                    "'.$port.'"
log_level                               "secure"
gapless_mp3_playback                    "yes"
save_absolute_paths_in_playlists        "no"
metadata_to_use                         "artist,album,title,track,name,genre,date,composer,performer,disc"
auto_update                             "yes"
password                                "'.$mpd_password.'@'.$mpd_privileges.'"
default_permissions                     "read"

audio_output {
        type            "httpd"
        name            "MPD HTTP Streaming"
        encoder         "vorbis"                # optional, vorbis or lame
        port            "'.$http_streaming_port.'"
#       quality         "5.0"                   # do not define if bitrate is defined
        bitrate         "192"                   # do not define if quality is defined
        format          "44100:16:2"
        max_clients     "0"                     # optional 0=no limit
}

';

$audio_local_output_type = $mpd_conf_parameters['audio_local_output_type'];

// If this is for the master MPD instance, then we need to include the pulse audio output block
if (($master_setup) && ($audio_local_output_type == "pulse")) {

    $mpd_conf_file .= '
# This will set up an MPD output to the local PulseAudio server
audio_output {
        type "pulse"
        name "MPD Pulse Audio"
}

';

}

if (($master_setup) && ($audio_local_output_type == "alsa")) {

    $mpd_conf_file .= '
# This will also work to configure MPD to use PulseAudio server via ALSA 
audio_output {
        type "alsa"
        name "MPD"
        device "pulse"
        mixer_control "Master"
}

';

}

    $mpd_conf_file .= '
audio_buffer_size                       "'.$mpd_conf_parameters['audio_buffer_size'].'"
buffer_before_play                      "'.$mpd_conf_parameters['buffer_before_play'].'"
connection_timeout                      "'.$mpd_conf_parameters['connection_timeout'].'"
max_connections                         "'.$mpd_conf_parameters['max_connections'].'"
max_playlist_length                     "'.$mpd_conf_parameters['max_playlist_length'].'"
max_command_list_size                   "'.$mpd_conf_parameters['max_command_list_size'].'"
max_output_buffer_size                  "'.$mpd_conf_parameters['max_output_buffer_size'].'"
filesystem_charset                      "UTF-8"
id3v1_encoding                          "UTF-8"';

    $file_handle = fopen($mpd_conf_file_path, 'wb');
    $bytes_written = fwrite($file_handle, $mpd_conf_file);
    fclose($file_handle);

    if ($bytes_written > 0) {
        
        return true;
    }

    return false;
}

?>
