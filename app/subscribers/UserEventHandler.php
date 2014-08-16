<?php

class UserEventHandler {

    /**
     * Handle user login events.
     */
    public function onUserLogin($user)
    {
    	$user->last_login = new DateTime;

    	$user->save();
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($user)
    {
	// Whatever you want to do when a user logs out
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen('user.login', 'UserEventHandler@onUserLogin');

        $events->listen('user.logout', 'UserEventHandler@onUserLogout');
    }

}

?>
