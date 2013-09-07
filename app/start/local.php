<?php

Event::listen('eloquent.saving', function(Station $station)
{
	if ( ! $station->isValid()) {
		var_dump("station is not valid?");
		return false;
	} else {

		var_dump("station is valid.");
	}
});
