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

		// Create target uploads dir if it doesn't exist
		if (!file_exists($uploads_directory)) {

			@mkdir($uploads_directory);
		}

		// 5 minutes execution time
		@set_time_limit(5 * 60);

		// Retrieve the uploaded file 
		$file = Input::file('file'); 

		// Get some of the properties of the uploaded file
		$name = $file->getClientOriginalName();
		$path = $file->getRealPath();
		$ext = $file->getClientOriginalExtension();
	
		// How to write a chunked file upload
		//file_put_contents($filename, file_get_contents("php://input"), FILE_APPEND);
			
		$letId3 = new LetId3();

		// Analyze the id3 tags of the uploaded file	
		$id3 = $letId3->analyze( $path );

		// Parse out only the id3 data we care about into the magic variables
		$letId3->parseEssentialTagData(	$id3, array( "TIT2", "TPE1", "TALB", "TRCK", "artist", "album", "title", "track" ));

		// Copy the magic variables into local variables so it'll be faster to reference them below
		$artist = $letId3->artist;
		$album = $letId3->album;
		$title = $letId3->title;
		$track = $letId3->track;
		
		// If the key fields aren't set, then we don't need to continue past this points
		if (	( isset( $artist ) 	&& ( $artist != "" )) && 
			( isset( $album )	&& ( $album != "" ))  && 
			( isset( $title ) 	&& ( $title != "" ))  && 
			( isset( $track ) 	&& ( $track != "" ))  && 
			( isset( $ext ) 	&& ( $ext != ""	))) {

			$music_dir = $this->data['music_dir'];

			$artist_dir = $music_dir . $artist;

			// Create artist directory if it doesn't already exist
			if ( !file_exists( $artist_dir )) {
				@mkdir( $artist_dir );
			}

			// Create album directory if it doesn't already exist
			if ( !file_exists( $artist_dir . "/" . $album )) {
				@mkdir( $artist_dir . "/" . $album );
			}

			// Move the file into place as long as it doesn't already exist
			if ( !file_exists( '"' . $artist_dir . "/" . $album . "/" . $track . ' ' . $title . '.' . $ext . '"' )) {

				$uploadSuccess = Input::file( 'file' )->move( $music_dir . $artist . "/" . $album . "/", $name );
		
				if( $uploadSuccess ) {

					return Response::json('success', 200); 

				} else {

					return Response::json('error', 400);
				}

			} else {
		
				return Response::json('file exists', 304);
			}
		}

		return Response::json('incomplete tags', 400);
	}

	public function uploadStationsIcon() {

		// Get the logged in user
		$user = Auth::user();

		// Get the real path of the uploaded file so we can use it to create a unique filename
		$realPath = Input::file( 'file' )->getRealPath();

		// Let's check to make sure realPath is what we think it is
		$this->firephp->log($realPath, "realPath of uploaded file");

		// grab 32 characters from the middle of the hash of realPath / userid
                $uniqueFilename = substr( hash( 'SHA512', ( $realPath . "/" . $user->id )), 63, 32 );

		// Create the absolute path using the desired filetype jpeg
		$filepath = $this->data['document_root'] . $this->data['mpd_dir'] . $uniqueFilename . ".jpeg";

		// Retrieve the uploaded image, resize it to 64x64 and then save it to the unique_filename
		Image::make( $realPath )->resize( 64, 64 )->save( $filepath );

		// Create a new StationsIcon object and save it
		$stationsIcon = new StationsIcon;
		$stationsIcon->filename = $uniqueFilename . ".jpeg";
		$stationsIcon->filepath = $this->data['document_root'] . $this->data['mpd_dir'];
		$stationsIcon->baseurl = $this->data['mpd_dir'];
		$stationsIcon->creator_id = $user->id;
		$stationsIcon->save();
                
		if ( !$stationsIcon->id ){
                        
                        // set to default icon
                        $stationsIcon = StationsIcon::find( 1 ); 
                }

		// Respond with the stationsIcon as JSON
		echo $stationsIcon->toJson();
        }		
}
