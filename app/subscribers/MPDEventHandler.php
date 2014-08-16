<?php

class MPDEventHandler {

    /**
     * Handle MPD idle events of the player subsystem
     */
    public function onPlayerUpdate( $data, $track, $station )
    {
	$message = array();

	// Add the update data and newTrack data to the message
	$message["msg"] = array( 	"action" 	=> "idle",
					"update" 	=> $data,
					"track"  	=> $track,
					"subsystem" 	=> "player" );
	
	// Publish the new track to all listeners of the station through the WebSocket
	Latchet::publish('radio/station/'.$station->id, $message);				
    }

    /**
     * Handle MPD idle events of the playlist subsystem
     */
    public function onPlaylistUpdate($mpd)
    {

    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen('mpd.player', 'MPDEventHandler@onPlayerUpdate');
	
        $events->listen('mpd.playlist', 'MPDEventHandler@onPlaylistUpdate');
    }

}

?>
