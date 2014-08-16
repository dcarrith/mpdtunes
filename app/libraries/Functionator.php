<?php

class Functionator {

    public static function organize( $data, $caller = "Functionator" )
    {
	Log::info($caller, array('data' => $data));

	// 5 minutes execution time
	@set_time_limit(5 * 60);

	// Extract the data array into local variables
	extract( $data );

	// Read, write and execute for everyone
	//chmod($source, 0777);

	// Instantiate the LetId3 object so we can parse the id3 tags of the music file
	$letId3 = new LetId3();

	// Analyze the id3 tags of the uploaded file
	$id3 = $letId3->analyze( $source );

	Log::info($caller, array('id3' => (array)$id3));

	// Parse out only the id3 data we care about into the magic variables
	$letId3->parseEssentialTagData(	$id3, array( "TIT2", "TPE1", "TALB", "TRCK", "artist", "album", "title", "track" ));

	Log::info($caller, array('id3' => (array)$id3));

	// Copy the magic variables into local variables so it'll be faster to reference them below
	$artist = $letId3->artist;
	$album = $letId3->album;
	$title = $letId3->title;
	$track = $letId3->track;

	Log::info($caller, array('artist' => $artist));
	Log::info($caller, array('album' => $album));
	Log::info($caller, array('title' => $title));
	Log::info($caller, array('track' => $track));

	// If the key fields aren't set, then we don't need to continue past this points
	if (	( isset( $artist ) 	&& ( $artist != "" )) &&
		( isset( $album )	&& ( $album != "" ))  &&
		( isset( $title ) 	&& ( $title != "" ))  &&
		( isset( $track ) 	&& ( $track != "" ))  &&
		( isset( $ext ) 	&& ( $ext != ""	))) {

		$artist_dir = $music_dir . $artist;

		// Create artist directory if it doesn't already exist
		if ( !file_exists( $artist_dir )) {
			//@mkdir( $artist_dir );
			File::makeDirectory( $artist_dir, 0755 );
            		chown($artist_dir, 'apachetwo');
            		chgrp($artist_dir, 'threews');
		}

		// Create album directory if it doesn't already exist
		if ( !file_exists( $artist_dir . "/" . $album )) {
			//@mkdir( $artist_dir . "/" . $album );
			File::makeDirectory( $artist_dir.'/'.$album, 0755 );
            		chown($artist_dir.'/'.$album, 'apachetwo');
            		chgrp($artist_dir.'/'.$album, 'threews');
		}

		// Move the file into place as long as it doesn't already exist
		if ( !file_exists( '"' . $artist_dir . "/" . $album . "/" . $track . ' ' . $title . '.' . $ext . '"' )) {

			$destination = $music_dir . $artist . "/" . $album . "/" . $name;

			Log::info($caller, array('source' => $source));
			Log::info($caller, array('destination' => $destination));

			if( File::move( $source, $destination )) {

				$result = array('status' => 'success', 'code' => 200);

			} else {

				$result = array('status' => 'error', 'code' => 400);
			}

		} else {

			$result = array('status' => 'file exists', 'code' => 304);
		}

	} else {

		$result = array('status' => 'missing tags', 'code' => 400);
	}

	Log::info($caller, array('result' => $result));

	return $result;
    }
}

?>
