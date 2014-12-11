<?php

class Functionator {

    public static function organize( $data, $caller = "Functionator" )
    {
	// 5 minutes execution time
	@set_time_limit(5 * 60);

	// Extract the data array into local variables
	extract( $data );

	// Analyze the id3 tags of the uploaded file
	$id3 = LetID3::analyze( $source );

	// Parse out only the id3 data we care about into the magic variables
	LetID3::parseEssentialTagData($id3, array( "TIT2", "TPE1", "TALB", "TRCK", "artist", "album", "title", "track" ));

	// Copy the magic variables into local variables so it'll be faster to reference them below
	$artist = LetID3::get('artist');
	$album = LetID3::get('album');
	$title = LetID3::get('title');
	$track = LetID3::get('track');

	// If the key fields aren't set, then we don't need to continue past this points
	if (	( isset( $artist ) 	&& ( $artist != "" )) &&
		( isset( $album )	&& ( $album != "" ))  &&
		( isset( $title ) 	&& ( $title != "" ))  &&
		( isset( $track ) 	&& ( $track != "" ))  &&
		( isset( $ext ) 	&& ( $ext != ""	))) {

		$artist_dir = $music_dir . $artist;

		// Create artist directory if it doesn't already exist
		if ( !File::exists( $artist_dir )) {

			File::makeDirectory( $artist_dir, 0755 );

			// Make sure the directory is owned by apachetwo and the group threews
            		chown($artist_dir, 'apachetwo');
            		chgrp($artist_dir, 'threews');
		}

		// Create album directory if it doesn't already exist
		if ( !File::exists( $artist_dir . "/" . $album )) {

			File::makeDirectory( $artist_dir.'/'.$album, 0755 );

			// Make sure the directory is owned by apachetwo and the group threews
            		chown($artist_dir.'/'.$album, 'apachetwo');
            		chgrp($artist_dir.'/'.$album, 'threews');
		}

		// Move the file into place as long as it doesn't already exist
		if ( !File::exists( '"' . $artist_dir . "/" . $album . "/" . $track . ' ' . $title . '.' . $ext . '"' )) {

			$destination = $music_dir . $artist . "/" . $album . "/" . $name;

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

	return $result;
    }
}

?>
