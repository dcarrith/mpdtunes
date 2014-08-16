<?php
 
use Carbon\Carbon;

function get_timer_display($timer_input) {

	// Get a Carbon object composed of whatever today's date is (with 00:00:00 as H:m:s)
	$pseudoTime = Carbon::today()->addSeconds($timer_input);

	// Get the default display of the pseudoTime
	$timerDisplay = $pseudoTime->toTimeString();

	// Check to see if the hour property is 00
	if( $pseudoTime->hour == "00" ) {

		// We don't want to show hour if it's 00 since that will usually be the case
		$timerDisplay = substr( $timerDisplay, ( strpos( $timerDisplay, ":" ) + 1 ), 5 ) ;
	}
	
	return $timerDisplay;
	//return Carbon::today()->addSeconds($timer_input)->toTimeString();

	$minutes = "-";
	$seconds = "--";

	if ((!isset($timer_input)) || ($timer_input === "Infinity") || ($timer_input === "")) {
		
		return "∞";
	
	} else {

		if ($timer_input > 0) {

			$minutes = floor($timer_input / 60);

			$seconds = floor($timer_input % 60);

			if ($seconds < 10) {
				
				$seconds = "0" . $seconds;
			}
		}
	}

	return ($minutes.':'.$seconds);
}

?>
