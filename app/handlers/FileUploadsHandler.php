<?php

class FileUploadsHandler {

    public function organize( $job, $data )
    {
	// Store the name of the calling function so we can pass it to the logger
	$caller = 'FileUploadsHandler';

	Log::info( $caller, array( 'jobId' => $job->getJobId() ));

	// Organize the music file into it's place
	$result = Functionator::organize( $data, $caller );

	// Extract the result into status and code variables
	extract( $result );

	Log::info( $caller, array( 'status' => $status ));
	Log::info( $caller, array( 'code' => $code ));

	// If the file was parsed and moved into place successfully, then delete the job from the queue
	if( $status == 'success' ) {
				
		Log::info( $caller, array( 'jobId' => $job->getJobId() ));

		$job->delete();
	
	// Otherwise, we want to try three times before we delete the job from the queue
	} else if ( $job->attempts() > 3 ) {

		Log::info( $caller, array( 'attempts' => $job->attempts() ));
		Log::info( $caller, array( 'jobId' => $job->getJobId() ));

		$job->delete();
	}
    }
}

?>
