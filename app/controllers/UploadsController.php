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

		// Retrieve the uploaded file
		$file = Input::file('file');

		// The music directory into which all music is stored
		$music_dir = $this->data['music_dir'];

		// Get some of the properties of the uploaded file
		$source = $file->getRealPath();
		$name = $file->getClientOriginalName();
		$ext = $file->getClientOriginalExtension();

		// Get the default uploads directory for the site
		$uploadsDirectory = $this->data['default_base_uploads_dir'];

		// Create target uploads dir if it doesn't exist
		if (!file_exists($uploadsDirectory)) {

			@mkdir($uploadsDirectory);
		}

		// Compact the variables we need into the data array
		$data = compact( 'music_dir', 'source', 'name', 'ext' );

		// Create a hash of the temporary file path as the unique filename
		$tmpName = hash('sha1', $source) . "." . $ext;
		$tmpPath = $uploadsDirectory . $tmpName;

		// Move the file out of the /tmp directory and into the uploads directory
		$file->move( $uploadsDirectory, $tmpName );

		// Merge the new source path into the data array to be passed in to the queue push
		$data = array_merge( $data, array( 'source' => $tmpPath ));

		// If queuing is enabled, then let's use it
		if( Config::get( 'queue.enabled' )) {

			// Pass the uploaded file to the queue that handles file uploads
			//$result = Queue::bulk(array('FileUploadsHandler@organize', 'MailHandler@send'), $data);
			$result = Queue::push( 'FileUploadsHandler@organize', $data, 'uploads' );
			//$result = Queue::push( 'FileUploadsHandler@organize', $data, 'https://sqs.us-east-1.amazonaws.com/204060697438/uploads');

			// Return a success 200 since we successfully passed the file to the queue to be parsed out and moved appropriately
			return Response::json( 'File has been queued up for analysis', 200 );

		} else {

			$caller = 'UploadsController';

			// Organize the uploaded music file into it's place
			$result = Functionator::organize( $data, $caller );

			// Extract the status and code from the result
			extract( $result );

			// Return status and code in a JSON response
			return Response::json( $status, $code );
		}
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
