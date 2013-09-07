<?php 

class UploadsController extends MPDTunesController {

	function __construct() {

        	parent::__construct();

                // Get and merge the site config defaults into the main data array
                $this->data = array_merge($this->data, Configurator::getDefaults("uploader"));
	}

	public function index() {

                // Get and merge all the words we need for the base controller into the main data array
                $this->data = array_merge($this->data, Langurator::getLocalizedWords("uploader"));

		$this->data['data_url'] = "";

		return View::make('uploader', $this->data);		
	}

	public function uploadMusic() {

		$uploads_directory = $this->data['default_base_uploads_dir'];

		$this->firephp->log($uploads_directory, "uploads_directory");

		// 5 minutes execution time
		@set_time_limit(5 * 60);

		// Get parameters
		$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
		$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
		$filename = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

		// Clean the filename for security reasons
		$filename = preg_replace('/[^\w\._]+/', '', $filename);

		// Make sure the filename is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($uploads_directory . $filename)) {

			$ext_start_pos = strrpos($filename, '.');
			$filename_part_one = substr($filename, 0, $ext_start_pos);
			$filemame_part_two = substr($filename, $ext_start_pos);

			$count = 1;

			while (file_exists($uploads_directory . $filename_part_one . '_' . $count . $filename_part_two)) {
				$count++;
			}

			$filename = $filename_part_one . '_' . $count . $filename_part_two;
		}

		// Create target dir
		if (!file_exists($uploads_directory))
			@mkdir($uploads_directory);

		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
			$content_type = $_SERVER["HTTP_CONTENT_TYPE"];

		if (isset($_SERVER["CONTENT_TYPE"]))
			$content_type = $_SERVER["CONTENT_TYPE"];

		$poidsMax = ini_get('post_max_size'); 
		
		$this->firephp->log($poidsMax, "post_max_size");

		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($content_type, "multipart") !== false) {
		
			$this->firephp->log($_FILES, "_FILES");
	
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {

				$this->firephp->log("content-type is multipart, now trying to open: " . $uploads_directory . $filename, "message");

				$out = fopen($uploads_directory . $filename, $chunk == 0 ? "wb" : "ab");
				
				if ($out) {

					// Read binary input stream and append it to temp file
					$in = fopen($_FILES['file']['tmp_name'], "rb");

					if ($in) {
						while ($buff = fread($in, 4096))
							fwrite($out, $buff);
					} else {

						die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
					}

					fclose($in);
					fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				
				} else {

					die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
				}

			} else {

				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			}

		} else {

			$this->firephp->log("content-type is NOT multipart, now trying to open: " . $uploads_directory . $filename, "message");
			
			//var_dump("trying to open: " . $uploads_directory . DIRECTORY_SEPARATOR . $filename);
			// Open temp file
			$out = fopen($uploads_directory . $filename, $chunk == 0 ? "wb" : "ab");
			
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen("php://input", "rb");

				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else {

					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				}

				fclose($in);
				fclose($out);
			
			} else {

				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			}
		}

		// Make sure the entire file has been received before trying to analyze the id3 tags
		if (($chunk == ($chunks - 1)) || (($chunks == 0) && ($chunk == 0))) {

			require_once('includes/php/modules/getid3/getid3/getid3.php');

			$id3 = array();

			$getID3 = new getID3();

			$artist = "";
			$album_artist = "";
			$album = "";
			$title = "";
			$track = "";
			$ext = "";

			try { 
				
				$id3 = $getID3->analyze($uploads_directory . $filename);

				$this->firephp->log($id3['tags'], "id3['tags']");

				$filename_ra = explode(".", $filename);
				$extension = $filename_ra[count($filename_ra)-1];

				if ($extension == 'ogg'){

					if (isset($id3['tags']['vorbiscomment']['artist'][0])) {
						$artist = $id3['tags']['vorbiscomment']['artist'][0];
					}

					if (isset($id3['tags']['vorbiscomment']['album'][0])) {
						$album = $id3['tags']['vorbiscomment']['album'][0];
					}

					if (isset($id3['tags']['vorbiscomment']['title'][0])) {
						$title = $id3['tags']['vorbiscomment']['title'][0];
					}

					if (isset($id3['tags']['vorbiscomment']['tracknumber'][0])) {
						$track = $id3['tags']['vorbiscomment']['tracknumber'][0];
						if (strlen($track) == 1) {
							$track = "0".$track;
						}
					}			

				} else {

					if (isset($id3['tags']['id3v1']['artist'][0])) {
						$artist = $id3['tags']['id3v1']['artist'][0];
					}

					if (isset($id3['tags']['id3v1']['album'][0])) {
						$album = $id3['tags']['id3v1']['album'][0];
					}

					if (isset($id3['tags']['id3v1']['title'][0])) {
						$title = $id3['tags']['id3v1']['title'][0];
					}

					if (isset($id3['tags']['id3v1']['track'][0])) {
						$track = $id3['tags']['id3v1']['track'][0];
						if (strlen($track) == 1) {
							$track = "0".$track;
						}

					} else if (isset($id3['tags']['id3v1']['track_number'][0])) {
						$track = $id3['tags']['id3v1']['track_number'][0];
						
						if (strpos($track, "/")) {
							
							$track_ra = explode("/", $track);
							$track = $track_ra[0];
						}

						if (strlen($track) == 1) {
							$track = "0".$track;
						}

					} else {
						
						// see if the first two characters are digits, if so, use them as the track numbers
						/*$first_char = substr($filename, 0, 1);
						$second_char = substr($filename, 1, 1);

						if (preg_match('/[0-9]/', $first_char)) {
							
							if (preg_match('/[0-9]/', $second_char)) {
						
								$track = $first_char.$second_char;

							} else {
								
								$track = "0".$first_char;
							}
						}*/
					}

					if (isset($id3['fileformat'])) {
						$ext = $id3['fileformat'];
					}

					if (isset($id3['tags']['id3v2']['artist'][0])) {
						$artist = $id3['tags']['id3v2']['artist'][0];
					}

					if (isset($id3['tags']['id3v2']['band'][0])) {
						$album_artist = $id3['tags']['id3v2']['band'][0];
					}

					if (isset($id3['tags']['id3v2']['album'][0])) {
						$album = $id3['tags']['id3v2']['album'][0];
					}

					if (isset($id3['tags']['id3v2']['title'][0])) {
						$title = $id3['tags']['id3v2']['title'][0];
					}

					if (isset($id3['tags']['id3v2']['track'][0])) {
						$track = $id3['tags']['id3v2']['track'][0];
						if (strlen($track) == 1) {
							$track = "0".$track;
						}

					} else if (isset($id3['tags']['id3v2']['track_number'][0])) {
						$track = $id3['tags']['id3v2']['track_number'][0];
						
						if (strpos($track, "/")) {
							
							$track_ra = explode("/", $track);
							$track = $track_ra[0];
						}
						
						if (strlen($track) == 1) {
							$track = "0".$track;
						}

					} else {
						
						// see if the first two characters are digits, if so, use them as the track numbers
						/*$first_char = substr($filename, 0, 1);
						$second_char = substr($filename, 1, 1);

						if (preg_match('/[0-9]/', $first_char)) {
							
							if (preg_match('/[0-9]/', $second_char)) {
						
								$track = $first_char.$second_char;

							} else {
								
								$track = "0".$first_char;
							}
						}*/
					}
				}

				if (isset($id3['fileformat'])) {
					$ext = $id3['fileformat'];
				}

				if (strpos($artist, "/")) {
					
					$artist = preg_replace("/\//", "-", $artist);
					$artist = preg_replace("/\>/", "-", $artist);
					$artist = preg_replace("/\</", "-", $artist);
					$artist = preg_replace("/\|/", "-", $artist);
					$artist = preg_replace("/\:/", "-", $artist);
					$artist = preg_replace("/&/", "-", $artist);
				}

				if (strpos($album_artist, "/")) {
					
					$album_artist = preg_replace("/\//", "-", $album_artist);
					$album_artist = preg_replace("/\>/", "-", $album_artist);
					$album_artist = preg_replace("/\</", "-", $album_artist);
					$album_artist = preg_replace("/\|/", "-", $album_artist);
					$album_artist = preg_replace("/\:/", "-", $album_artist);
					$album_artist = preg_replace("/&/", "-", $album_artist);
				}

				if (strpos($album, "/")) {
					
					$album = preg_replace("/\//", "-", $album);
					$album = preg_replace("/\>/", "-", $album);
					$album = preg_replace("/\</", "-", $album);
					$album = preg_replace("/\|/", "-", $album);
					$album = preg_replace("/\:/", "-", $album);
					$album = preg_replace("/&/", "-", $album);
				}

				if (strpos($title, "/")) {
					
					$title = preg_replace("/\//", "-", $title);
					$title = preg_replace("/\>/", "-", $title);
					$title = preg_replace("/\</", "-", $title);
					$title = preg_replace("/\|/", "-", $title);
					$title = preg_replace("/\:/", "-", $title);
					$title = preg_replace("/&/", "-", $title);
				}

				$this->firephp->log($artist, "artist");
				$this->firephp->log($album_artist, "album_artist");
				$this->firephp->log($album, "album");
				$this->firephp->log($title, "title");
				$this->firephp->log($track, "track");
				$this->firephp->log($ext, "ext");

				if (	(isset($artist) && ($artist != "")) && 
					(isset($album) 	&& ($album != ""))  && 
					(isset($title) 	&& ($title != ""))  && 
					(isset($track) 	&& ($track != ""))  && 
					(isset($ext) 	&& ($ext != "")) ) {

					$music_dir = $this->data['music_dir'];
					$this->firephp->log($music_dir, "music_dir");

					$artist_dir = $music_dir.$artist;

					if ($album_artist != "") {

						$artist_dir = $music_dir.$album_artist;
					}

					// Create artist directory if it doesn't already exist
					if (!file_exists($artist_dir)) {
						@mkdir($artist_dir);
					}

					// Create album directory if it doesn't already exist
					if (!file_exists($artist_dir.DIRECTORY_SEPARATOR.$album)) {
						@mkdir($artist_dir.DIRECTORY_SEPARATOR.$album);
					}

					$this->firephp->log("file_exists(\"".$artist_dir.DIRECTORY_SEPARATOR.$album.DIRECTORY_SEPARATOR.$track." ".$title.".".$ext."\")", "checking if");

					// Move the file into place as long as it doesn't already exist
					if (!file_exists('"'.$artist_dir.DIRECTORY_SEPARATOR.$album.DIRECTORY_SEPARATOR.$track.' '.$title.'.'.$ext.'"')) {
						
						$this->firephp->log('mv "'.$uploads_directory . $filename.'" "'.$music_dir.$artist.DIRECTORY_SEPARATOR.$album.DIRECTORY_SEPARATOR.$track.' '.$title.'.'.$ext.'"', "debug");

						$result_ra = array();

						// move the temporary file into place 
						$result_ra = exec('mv "'.$uploads_directory . $filename.'" "'.$artist_dir.DIRECTORY_SEPARATOR.$album.DIRECTORY_SEPARATOR.$track.' '.$title.'.'.$ext.'"', $result_ra);

						$this->firephp->log($result_ra, "result_ra from exec mv command");

						//var_dump($result_ra);

						chmod($artist_dir.DIRECTORY_SEPARATOR.$album.DIRECTORY_SEPARATOR.$track.' '.$title.'.'.$ext, 0750);

					  	require_once('includes/php/library/art.inc.php');

					  	$config = array();

						$configs['music_dir'] 			= $this->data['music_dir'];
						$configs['art_dir'] 			= $this->data['art_dir'];
						$configs['document_root'] 		= $this->data['document_root'];
						$configs['default_no_album_art_image'] 	= $this->data['default_no_album_art_image'];

						$this->firephp->log($configs, "configs");

						$file = $artist.DIRECTORY_SEPARATOR.$album.DIRECTORY_SEPARATOR.$track.' '.$title.'.'.$ext;

						if ($album_artist != "") {

							$file = $album_artist.DIRECTORY_SEPARATOR.$album.DIRECTORY_SEPARATOR.$track.' '.$title.'.'.$ext;
						
							// By getting the album art, we are pre-caching it so we don't have to do it during navigation
							$album_art = "/".get_album_art	(	$file, 
												$album_artist, 
												$album, 
												$configs,
												$this->firephp	);
						} else {

							// By getting the album art, we are pre-caching it so we don't have to do it during navigation
							$album_art = "/".get_album_art	(	$file, 
												$artist, 
												$album, 
												$configs,
												$this->firephp	);
						}

						$this->firephp->log($file, "file");

						$this->firephp->log($album_art, "album_art");
					}
				}

			} catch (Exception $error) { 

				print($error->message); 
			}
		}

		// Return JSON-RPC response
		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
	}

	public function uploadStationsIcon() {

                $document_root                  = $this->data['document_root'];
                $base_protocol                  = $this->data['base_protocol'];
                $base_domain                    = $this->data['base_domain'];
                $mpd_dir                        = $this->data['mpd_dir'];
                $default_no_station_icon        = $this->data['default_no_station_icon'];

                require_once('includes/php/library/crypto.inc.php');

                $secret_string_mask = generate_password_mask(32);
                $secret_string = create_password($secret_string_mask);
 
		$password_salt_mask = generate_password_mask(32);
		$password_salt = create_password($password_salt_mask);

               	$secret_string_hash = hash('SHA512', $secret_string.$password_salt);

		// grab 32 characters from the middle
                $unique_filename = substr($secret_string_hash, 63, 32);

		$file = Input::file('file');
	
		$path = $file->getRealPath();
		$name = $file->getClientOriginalName();
		$ext = $file->getClientOriginalExtension();
		$size = $file->getSize();
		$mime = $file->getMimeType();

		$file->move($document_root.$mpd_dir, $unique_filename.".".$ext);	
		
		$filepath = $document_root . $mpd_dir . $unique_filename . "." . $ext;

		$this->firephp->log($file, "file");
		$this->firephp->log($path, "path");
		$this->firephp->log($name, "name");
		$this->firephp->log($ext, "ext");
		$this->firephp->log($size, "size");
		$this->firephp->log($mime, "mime");
		$this->firephp->log($filepath, "filepath");

                $convert_to_file_ext = 'jpeg';

                $new_station_image_file_name = $unique_filename.".".$convert_to_file_ext;

                require_once('includes/php/library/art.inc.php');

                image_convert($filepath, $convert_to_file_ext, 100);

                $new_station_image_file_path = $document_root.$mpd_dir.$new_station_image_file_name;
                
                $this->firephp->log($new_station_image_file_path, "new_station_image_file_path");

                create_thumbnail($new_station_image_file_path, $convert_to_file_ext, 64, 64, $this->firephp);

		$user = Auth::user();

		$stationsIcon = new StationsIcon;
		$stationsIcon->filename = $new_station_image_file_name;
		$stationsIcon->filepath = $document_root.$mpd_dir;
		$stationsIcon->baseurl = $mpd_dir;
		$stationsIcon->creator_id = $user->id;
		$stationsIcon->save();

                $icon_id = $stationsIcon->id;

                $this->firephp->log($icon_id, "Uploaded icon will have the following id");

                if (!$icon_id){
                        
                        // set to default icon
                        $stationsIcon = StationsIcon::find(1);
                }

		$this->firephp->log($stationsIcon->toJson(), "stationsIcon->toJson()");

		echo($stationsIcon->toJson());
        }		
}

