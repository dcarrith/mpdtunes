<?php

use \Sidney\Latchet\BaseTopic;

class RadioStationController extends BaseTopic {

        // Save all current connections
        protected $listeners = array();

        public function subscribe($connection, $channel, $stationId = null)
        {	
                // Check if the station exists first
                if($station = Station::where('id', '=', $stationId)->first()) {

                        // Save current listener to the database
                        $listener = $station->listeners()->save($connection->MPDTunes->listener);

                        // Save listener to $stations array so we have an easy accessible collection
                        // of all currently connected listeners
                        $this->listeners[$stationId][$listener->session_id] = $listener;

                        // Save current station (vis-a-vis the channel parameter) and this instance to the connection
                        $connection->MPDTunes->station = $channel;
                        $connection->MPDTunes->handler = $this;
			
                        // Broadcast that a new listener has tuned in to the station
                        $msg = array(
                                'action' => 'newListener',
                                'listener' => $listener->toJson(),
                                'msg' => 'Listener ' . $listener->name . ' has tuned in.'
                        );

                        $this->broadcast($channel, $msg, $exclude = array($listener->session_id));

                        // List all currently connected listeners to the new listener
                        foreach ($this->listeners[$stationId] as $existingListener) {

                                if($existingListener->session_id != $listener->session_id) {

					if($existingListener->connected) {

	                                        $msg = array(
        	                                        'action' => 'existingListener',
                	                                'listener' => $existingListener->toJson()
                        	                );

                                	        $this->broadcast($channel, $msg, $exclude = array(), $eligible = array($listener->session_id));

                                	} else {

						// Delete listeners that are no longer connected
						$existingListener->delete();
					}
				}
                        }
                
		} else {

                        // Station does not exist
                        $connection->close();
                }
        }

        public function publish($connection, $channel, $message, array $exclude, array $eligible)
        {
		// This is where I think I need to push out updates to clients when the track is changed

	}

        public function call($connection, $id, $channel, array $params)
        {
                switch ($params['action']) {

                        case 'update':
                                if($params['type'] == 'listener')
                                {
                                        $result = $this->updateListener($params['model'], $connection, $id, $channel);
                                }
                                break;
                        case 'create':
                                if($params['type'] == 'message')
                                {
                                        $result = $this->newMessage($params['model'], $connection, $id, $channel);
                                }
                                break;
                        default:
                                // Something went wrong
                                $connection->close();
                                break;
                }
        }

        public function unsubscribe($connection, $channel, $stationId = null)
        {
                // Broadcast to all subscribing listeners that the user tuned out
                $listener = $connection->MPDTunes->listener;

                $msg = array(
                                'action' => 'listenerDropped',
                                'listener' => $listener->toJson(),
                                'msg' => 'Listener ' . $listener->name . ' tuned out.'
                        );

                $this->broadcast($channel, $msg);

                // Remove listener from the collection
                unset($this->listeners[$listener->station->name][$listener->session_id]);

                // Save to database
                $listener->stationId = 0;
                $listener->save();
        }

        /**
         * update the current listener
         *
         * @param array $model
         * @param object $connection
         * @param string $id
         * @param object $channel
         */
        private function updateListener($model, $connection, $id, $channel)
        {
                $listener = $connection->MPDTunes->listener;
                $listener->name = $model['name'];

                if($listener->save() && $listener->isUnique($model['name'], $model['station'])) {

                        // Just send back the new model so its in sync with the client version
                        $connection->callResult($id, $listener->toArray());

                } else {

                        // If there was a validation error, then raise an error
                        $connection->callError($id, $channel, $listener->validationErrors->first());
                }
        }

        /**
         * new message got submitted, save it the db
         * and broadcast it
         *
         * @param array $model
         * @param object $connection
         * @param string $id
         * @param object $station
         */
        private function newMessage($model, $connection, $id, $channel)
        {
                $listener = $connection->MPDTunes->listener;

                $newMessage = new Message;
                $newMessage->content = $model['content'];
                $newMessage->stationId = $listener->stationId;

                if($listener->messages()->save($newMessage)) {

                        // Broadcast new message to all other listeners
                        $msg = array(
                                'action' => 'newMessage',
                                'listener' => $listener->toJson(),
                                'message' => $newMessage->toJson()
                        );
                        $this->broadcast($channel, $msg, $exclude = array($listener->session_id));
                        $connection->callResult($id, $newMessage->toArray());

                } else {

                        $connection->callError($id, $channel, $listener->validationErrors->first('content'));
                }
        }
}
