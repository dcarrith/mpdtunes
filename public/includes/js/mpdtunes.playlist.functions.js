// query mpd for the current playlist
function get_mpd_playlist(shuffle_queue) {
	
	var json_playlist;

	//alert("Waiting...");

	$.ajax({
	   type: "POST",
	   url: "/musicpd/query",
	   async: false,
	   data: "query=playlist",
	   success: function(msg){

	   	 json_playlist = msg;
	     //alert( "Query for playlist complete: \n\n" + json_playlist );

		 if (shuffle_queue){
		 	
		 	json_playlist = shuffle_playlist(json_playlist);

		 	//alert(JSON.stringify(json_playlist));
		 }
	   }
	 });

	 return json_playlist;
}

function shuffle_playlist(playlist_to_shuffle) {
	
	playlist_to_shuffle = $.parseJSON(playlist_to_shuffle);

	shuffled_playlist = '{ "tracks" : [';

	number_of_tracks = playlist_to_shuffle.tracks.length;
	count_of_tracks = number_of_tracks;

	for(i=0; i<number_of_tracks; i++){
		
		var random_number = Math.floor(Math.random()*count_of_tracks);

		if (count_of_tracks == 1) {
	
			shuffled_playlist += JSON.stringify(playlist_to_shuffle.tracks[random_number]);
			
		} else {

			shuffled_playlist += JSON.stringify(playlist_to_shuffle.tracks[random_number]) + ", ";
		}

		count_of_tracks--;
	}

	shuffled_playlist += ']}';
	
	return shuffled_playlist;
}

function unshuffle_playlist() {

	unshuffled_playlist = $.parseJSON( get_mpd_playlist() );

	return unshuffled_playlist;
}
