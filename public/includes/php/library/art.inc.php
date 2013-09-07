<?php 
require_once('includes/php/modules/getid3/getid3/getid3.php');

function get_album_art($filepath, $artist, $album, $configs, $firephp=null) {

    $music_dir                  = $configs['music_dir'];
    $art_dir                    = $configs['art_dir'];
    $document_root              = $configs['document_root'];
    $default_no_album_art_image = $configs['default_no_album_art_image'];

    if (isset($firephp)) {

        $firephp->log($filepath, "filepath");
        $firephp->log($artist, "artist");
        $firephp->log($album, "album");
        $firephp->log($configs, "configs");
        $firephp->log($music_dir, "music_dir");
        $firephp->log($art_dir, "art_dir");
    }

    //define('NO_ALBUM_ART_MD5', '74ec2ed1b5856df36c21263a7ab47f3d');
    //define('NO_ALBUM_ART_MD52', 'd89199131765f24bafae77ab8685f58a');

    if (!isset($filepath)){
        
        return $default_no_album_art_image;
    }

    $absolute_path = $music_dir.$filepath;

    $track_ra = explode("/", $filepath);

    $filename = sha1($artist . " - " . $album);

    // check for an existing album art cache file based on a default value and type
    $album_art_file_exists = file_exists($document_root.$art_dir.$filename.".jpeg");

    /*if ($album_art_file_exists !== true) {
        
        return $document_root.ltrim($art_dir, "/").$album_art_file_exists.".jpeg";
    }*/

    $is_the_default_no_album_art_image = false;

    $mdfive = "";

    if ($album_art_file_exists === true) {

        $mdfive = md5_file($document_root.$art_dir.$filename.".jpeg");
        
        $is_the_default_no_album_art_image = ((NO_ALBUM_ART_MD5 == $mdfive) || (NO_ALBUM_ART_MD52 == $mdfive));
    }

    if (isset($firephp)) {
        
        $firephp->log($absolute_path, "absolute_path");
        $firephp->log($track_ra, "track_ra");
        $firephp->log($filename, "filename");
        $firephp->log($album_art_file_exists, "album_art_file_exists");
        $firephp->log($is_the_default_no_album_art_image, "is_the_default_no_album_art_image");
        $firephp->log($mdfive, "mdfive");
    }

    //var_dump(NO_ALBUM_ART_MD5);
    //var_dump(md5_file($document_root.ltrim($document_root.$art_dir.$filename.".jpeg"));

    // If no album art cache file exists yet, or if it is the no album art image, then create a cache file
    if (($album_art_file_exists === false) || ($is_the_default_no_album_art_image === true)) {

        $id3 = array();

        $getID3 = new getID3();

        try { 

            if (isset($firephp)) {
                $firephp->log($album_art_file_exists, "analyzing id3");
		$firephp->log($absolute_path, "absolute path");
            }

            $id3 = $getID3->analyze($absolute_path);

            if (isset($firephp)) {
                $firephp->log($id3, "id3");
            }

            if (isset($id3['fileformat'])) {

                if ($id3['fileformat'] == 'ogg') {

                    $art_file = $art_dir.$filename.'.jpeg';

                    if (isset($firephp)) {
                    
                        $firephp->log($art_file, "art_file");
                    }

                    $art_file_decoded = null;
                    $art_file_decoded_truncated = null;
                    $art_file_image_data = null;

                    if (isset($id3['comments']['metadata_block_picture'][0])) {

                        // Get the art_file data from the ogg vorbis metadata_block_picture element
                        $art_file_image_data = $id3['comments']['metadata_block_picture'][0]; 

                    } else if (isset($id3['tags']['vorbis_comment']['metadata_block_picture'][0])) {

                        // Get the art_file data from the ogg vorbis metadata_block_picture element
                        $art_file_image_data = $id3['tags']['vorbis_comment']['metadata_block_picture'][0]; 

                    } else if (isset($id3['ogg']['comments']['metadata_block_picture'][0])) {

                        // Get the art_file data from the ogg vorbis metadata_block_picture element
                        $art_file_image_data = $id3['ogg']['comments']['metadata_block_picture'][0]; 

                    } else {

                        // don't know of any other cases yet
                    }

                    if (isset($firephp)) {
         
                        $firephp->log($art_file_image_data, "art_file_image_data");
                    }

                    if (isset($art_file_image_data)) {

                        // Base64 decode it
                        $art_file_decoded = base64_decode($art_file_image_data);

                       //var_dump(strpos($art_file_decoded, "image/jpeg"));
                        //var_dump(strpos($art_file_decoded, "image/png"));

                        if (isset($firephp)) {
             
                            $firephp->log(utf8_encode($art_file_decoded), "art_file_decoded");
                            $firephp->log(strpos($art_file_decoded, "image/jpeg"), "strpos(image/jpeg)");
                            $firephp->log(strpos($art_file_decoded, "image/png"), "strpos(image/png)");
                        }

                        if (($art_file_decoded !== false) && ($art_file_decoded != '') && ($art_file_decoded !== null) && ($art_file_decoded !== NULL) && ($art_file_decoded !== "NULL") && (isset($art_file_decoded))) {

                            if (strpos($art_file_decoded, "image/jpeg")) {

                                // Need to truncate off the mimetype info since it doesn't need to be written with the file
                                $art_file_decoded_truncated = substr($art_file_decoded, 42, (strlen($art_file_decoded) - 42));

                            } else if (strpos($art_file_decoded, "image/png")) {

                                // Need to truncate off the mimetype info since it doesn't need to be written with the file
                                $art_file_decoded_truncated = substr($art_file_decoded, 41, (strlen($art_file_decoded) - 41));

                            } else {

                                // Need to truncate off the mimetype info since it doesn't need to be written with the file
                                $art_file_decoded_truncated = substr($art_file_decoded, 42, (strlen($art_file_decoded) - 42));
                            }

                            //var_dump($art_file_decoded_truncated);

                            if (isset($firephp)) {
                 
                                $firephp->log(utf8_encode($art_file_decoded_truncated), "art_file_decoded_truncated");
                            }

                            // Write the full sized file so we can retrieve it and resize it to 64x64
                            $file_put_success = file_put_contents($document_root.$art_file, $art_file_decoded_truncated);  

                            if (isset($firephp)) {

                                $firephp->log($file_put_success, "file_put_success");
                            }
                        
                        } else {

                            $art_file_image_data = file_get_contents($document_root.ltrim($default_no_album_art_image, "/"));

                            $firephp->log($art_file_image_data, "art_file_image_data");

                            // Write the full sized file so we can retrieve it and resize it to 64x64
                            $file_put_success = file_put_contents($document_root.$art_file, $art_file_image_data);                             
                        }

                    } else {

                        $art_file_image_data = file_get_contents($document_root.ltrim($default_no_album_art_image, "/"));

                        $firephp->log($art_file_image_data, "art_file_image_data");

                        // Write the full sized file so we can retrieve it and resize it to 64x64
                        $file_put_success = file_put_contents($document_root.$art_file, $art_file_image_data); 
                    }

                    // check for an existing album art cache file based on a default value and type
                    //$album_art_file_exists = file_exists($document_root.$art_dir.$filename.".jpeg");

                    if (strpos($art_file_decoded, "image/png")) {

                        $art_file = $art_dir.$filename.'.jpeg';

                        $file_put_success = file_put_contents($document_root.$art_file, file_get_contents($document_root.ltrim($default_no_album_art_image, "/")));

                        if (isset($firephp)) {
             
                            $firephp->log($art_file, "art_file");
                            $firephp->log($file_put_success, "file_put_success");
                            $firephp->log($default_no_album_art_image, "default_no_album_art_image");
                            $firephp->log($art_file, "DEFAULT FILE WRITTEN");
                        }
                    }

                } else { // must be mp3

                        if (isset($firephp)) {
				$firephp->log($id3['comments']['picture'], 'picture');
                        }

                    if (isset($id3['comments']['picture'][0]['image_mime'])){
                        
                        $art_file = $art_dir.$filename.'.'.str_replace('image/', '', $id3['comments']['picture'][0]['image_mime']);
                    
                    } else {
 
                        $art_file = $art_dir.$filename.'.jpeg';
                    }

                    if (!isset($id3['comments']['picture'][0]['data'])){
            	
                        $file_put_success = file_put_contents($document_root.$art_file, file_get_contents($document_root.ltrim($default_no_album_art_image, "/")));

                    } else {

                        $file_put_success = file_put_contents($document_root.$art_file, $id3['comments']['picture'][0]['data']);		
		    }
                }
            }

	    if( file_exists($art_file) ) {

            	chmod($art_file, 0777);

            	image_convert($art_file, 'jpeg', 100);

            	//image_constrain($art_file, 64, 64);

            	create_thumbnail($art_file, 'jpeg');
	    }

        } catch (Exception $error) { 
        
            print($error->message); 
        }
    
    } else {

        // return the relative path to the album art image
        $art_file = $art_dir.$filename.".jpeg"; 
    }

    return $art_file;
}

function create_thumbnail($absolute_filepath, $extension="jpeg", $max_width=64, $max_height=64, $firephp=null) {

    $image_width = 0;
    $image_height = 0;

    if (isset($firephp)) {

	$firephp->log($absolute_filepath, "absolute_filepath");
	$firephp->log($extension, "extension");
    }

    $original_image = @imagecreatefromjpeg($absolute_filepath);

    //$absolute_filepath = ltrim($absolute_filepath, "/");

    list($image_width, $image_height) = getimagesize($absolute_filepath);

    $width_ratio = (($max_width && $max_width < $image_width) ? ($max_width/$image_width) : 1);

    $height_ratio = (($max_height && $max_height < $image_height) ? ($max_height/$image_height) : 1);

    $image_ratio = min($width_ratio, $height_ratio);

    $new_width = round($image_ratio * $image_width);

    $new_height = round($image_ratio * $image_height);

    $position_x = round(($max_width - $new_width) / 2);

    $position_x = ($position_x < 1) ? 1 : $position_x;

    $position_y = round(($max_height - $new_height) / 2);

    $position_y = ($position_y < 1) ? 1 : $position_y;

    $temp_image = imagecreatetruecolor($new_width, $new_height);

    $original_image = image_open($absolute_filepath);

    if (isset($firephp)) {

	    $firephp->log($absolute_filepath, "absolute_filepath after ltrim");
	    $firephp->log($temp_image, "temp_image");
	    $firephp->log($original_image, "original_image");
	    $firephp->log($new_width, "new_width");
	    $firephp->log($new_height, "new_height");
	    $firephp->log($image_width, "image_width");
	    $firephp->log($image_height, "image_height");
    }

    $resized = @imagecopyresampled($temp_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);

    $new_image = ($resized) ? $temp_image : $original_image;

    if (image_write($new_image, $absolute_filepath)) {

	// Image was written to disk

	if (isset($firephp)) {
		$firephp->log($new_image, "new_image");
		$firephp->log($absolute_filepath, "absolute_filepath");
	}

    } else {

	if (isset($firephp)) {
		$firephp->log("Image could NOT be written to disk", "message");
	}
        // Image couldn't be written to disk
    }

    chmod($absolute_filepath, 0644);

    return $absolute_filepath;
}

function image_open($filename){

    $original = pathinfo($filename);

    $converted = false;
    
    if ((strtolower($original['extension']) == 'jpg') || (strtolower($original['extension']) == 'jpeg')) {
    
        $converted = imagecreatefromjpeg($filename);        
    
    } else if (strtolower($original['extension']) == 'gif') {
    
        $converted = imagecreatefromgif($filename);
    
    } else if (strtolower($original['extension']) == 'png') {
    
        $converted = imagecreatefrompng($filename);
    
    } else {
    
        $converted = false;
    }

    return $converted;
}

function image_write($image_res, $filename, $quality=80){

    $original = pathinfo($filename);

    $converted = false;
    
    if ((strtolower($original['extension']) == 'jpg') || (strtolower($original['extension']) == 'jpeg')) {
    
        $converted = imagejpeg($image_res, $filename, $quality);        
    
    } else if (strtolower($original['extension']) == 'gif') {
    
        $converted = imagegif($image_res, $filename);
    
    } else if (strtolower($original['extension']) == 'png') {
    
        $png_quality = jpeg_quality_to_png_quality($quality);
        
        $converted = imagepng($image_res, $filename, $png_quality);
    
    } else {
    
        return $converted;
    }

    return $converted;
}

function jpeg_quality_to_png_quality($jpeg_quality){
    
    $png_quality = 0;

    if (($jpeg_quality < 101) && ($jpeg_quality > 90)) $png_quality = 0;
    else if (($jpeg_quality < 91) && ($jpeg_quality > 80)) $png_quality = 1;
    else if (($jpeg_quality < 81) && ($jpeg_quality > 70)) $png_quality = 2;
    else if (($jpeg_quality < 71) && ($jpeg_quality > 60)) $png_quality = 3;
    else if (($jpeg_quality < 61) && ($jpeg_quality > 50)) $png_quality = 4;
    else if (($jpeg_quality < 51) && ($jpeg_quality > 40)) $png_quality = 5;
    else if (($jpeg_quality < 41) && ($jpeg_quality > 30)) $png_quality = 6;
    else if (($jpeg_quality < 31) && ($jpeg_quality > 20)) $png_quality = 7;
    else if (($jpeg_quality < 21) && ($jpeg_quality > 10)) $png_quality = 8;
    else if (($jpeg_quality < 11) && ($jpeg_quality > 0)) $png_quality = 9;
    else $png_quality = 0;

    return $png_quality;
}

function image_convert($filename, $type = 'jpeg', $img_quality=80 ){

    if(!$filename || !file_exists($filename)) {
        return false;
    }

    $img = image_open($filename);
    
    unlink($filename);
    
    $new_filename = explode('.', $filename);
    
    array_pop($new_filename);
    
    array_push($new_filename, $type);
    
    $new_filename = implode('.', $new_filename);
    
    $png_quality = jpeg_quality_to_png_quality($img_quality);
    
    switch($type){
    
    case 'png':
        imagepng($img, $new_filename, $png_quality);
        break;

    case 'gif':
        imagegif($img, $new_filename);
        break;

    case 'jpg':
        imagejpeg($img, $new_filename, $img_quality);
        exit();
        break;

    default:
        imagejpeg($img, $new_filename, $img_quality);
        break;
    }

    imagedestroy($img);
    
    return true;
}

function image_constrain($filename,$new_width=0,$new_height=0){

    if (!$new_width && !$new_height) {
        return false;
    }

    if (!$filename || !file_exists($filename)) {
        return false;
    }
    
    list($old_width,$old_height) = getimagesize($filename);

    $width_ratio = ($new_width && $new_width < $old_width)?($new_width/$old_width):1;

    $height_ratio = ($new_height && $new_height < $old_height)?($new_height/$old_height):1;

    $ratio = min($width_ratio,$height_ratio);

    $new_width = $ratio*$old_width;

    $new_height = $ratio*$old_height;

    $newpic = imagecreatetruecolor($new_width, $new_height);

    $oldpic = image_open($filename);

    imagecopyresampled($newpic, $oldpic, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);

    image_write($newpic, $filename);

    imagedestroy($oldpic);

    imagedestroy($newpic);

    return true;
}

?>
