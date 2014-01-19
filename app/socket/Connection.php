<?php
use \Sidney\Latchet\BaseConnection;

class Connection extends BaseConnection {

	public function open($connection)
        {
                //in case of a mysql timeout, reconnect
                //to the database
                $app = app();
                $app['db']->reconnect();

                $listener = new Listener;
                $listener->session_id = $connection->WAMP->sessionId;
                $listener->connected = 1;

                //validate model
                if($listener->save())
                {
                        //we'll cache the listener model here so we don't have to
                        //get it from the database everytime
                        $connection->MPDTunes = new StdClass;
                        $connection->MPDTunes->listener = $listener;
                }
                else
                {
                        $connection->close();
                }

                echo "A new connection was established. The listener has the session_id: " . $listener->session_id . " \n";
        }

        public function close($connection)
        {
                //maybe the close gets fired before we could create a new listener model
                if(isset($connection->MPDTunes))
                {
                        $listener = $connection->MPDTunes->listener;
                        $listener->connected = 0;
                        $listener->save();

                        // Unsubscribe from current station, if we have subscribed to one
                        if(isset($connection->MPDTunes->handler))
                        {
                                $connection->MPDTunes->handler->unsubscribe($connection, $connection->MPDTunes->station);
                        }

                        echo "A connection was closed. The listener with session_id '" . $listener->session_id . "' has dropped.\n";
                }
                else
                {
                        echo "The connection was closed. The listener was not even connected.\n";
                }

        }

        public function error($connection, $exception)
        {
                //close the connection
                $connection->close();
                echo $exception->getMessage();
                
		throw new Exception($exception);
        }
}
