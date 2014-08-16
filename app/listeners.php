<?php

/*Event::listen('user.login', function($user)
{
    $user->updated_at = new DateTime;

    $user->save();
});*/


// Register the UserEventHandler subscriber 
$subscriber = new UserEventHandler;
Event::subscribe($subscriber);

// Register the MPDEventHandler subscriber 
$subscriber = new MPDEventHandler;
Event::subscribe($subscriber);

?>
