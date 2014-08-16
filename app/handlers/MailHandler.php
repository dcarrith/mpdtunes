<?php

class MailHandler {

    public function send( $job, $data ) {

	//$job->release(30);
	//exit();

	Log::info('MailHandler', array('jobId' => $job->getJobId()));
	Log::info('MailHandler', array('data' => $data));

	Mail::send('emails.login', $data, function( $message ) {

		Log::info('MailHandler', array('from' => 'admin@mpdtunes.com'));
		Log::info('MailHandler', array('to' => 'dcarrith@gmail.com'));
		Log::info('MailHandler', array('cc' => 'davecarrithers@gmail.com'));
		Log::info('MailHandler', array('message' => $message));

		$message->from('admin@mpdtunes.com', 'MPDTunes');

		$message->to('dcarrith@gmail.com');

		$message->cc('davecarrithers@gmail.com');
	});

	Log::info('MailHandler', array('jobId' => $job->getJobId()));

	$job->delete();
    }
}

?>
