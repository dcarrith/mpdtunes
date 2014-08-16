<?php

class NotificationHandler {

    public function send( $job, $data ) {

	Log::info('NotificationHandler', array('jobId' => $job->getJobId()));
	Log::info('NotificationHandler', array('data' => $data));

	Mail::send('emails.notification', $data, function( $message ) {

		Log::info('NotificationHandler', array('from' => 'admin@mpdtunes.com'));
		Log::info('NotificationHandler', array('to' => 'dcarrith@gmail.com'));
		Log::info('NotificationHandler', array('cc' => 'davecarrithers@gmail.com'));
		Log::info('NotificationHandler', array('message' => $message));

		$message->from('admin@mpdtunes.com', 'MPDTunes');

		$message->to('dcarrith@gmail.com');

		$message->cc('davecarrithers@gmail.com');
	});

	Log::info('NotificationHandler', array('jobId' => $job->getJobId()));

	$job->delete();
    }
}

?>
