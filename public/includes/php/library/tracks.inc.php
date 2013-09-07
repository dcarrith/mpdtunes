<?php

function get_first_track_from_album($tracks){

	for ($i=0; $i<1; $i++){

		$track_name = $tracks[$i][0];
		$file_path = $tracks[$i][1];
	}

	return $file_path;
}
?>