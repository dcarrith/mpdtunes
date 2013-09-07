






		
		$modeltest = false;

		if ($modeltest) {

                /***********************************************************************************************************************
		 *                                            TESTING THE MODELS
		 ***********************************************************************************************************************/
        
                // Retrieve User with id = 1
                $user = User::find(1);

		print("<br />-------------------------------------FIND >> USER--------------------------------------------------<br />");
		var_dump($user);
		print("<br />---------------------------------------------------------------------------------------------------<br />");

                // Get the list of stations created by the user
                $stations = $user->stations;

                print("<br />----------------------------------------STATIONS---------------------------------------------------<br />");
                var_dump($stations);
                print("<br />---------------------------------------------------------------------------------------------------<br />");

		foreach($stations as $station) {

			print("<br />-------------------------FOREACH STATIONS AS STATION---------------------------------------<br />");
                	var_dump($station);
                	print("<br />-------------------------------------------------------------------------------------------<br />");

                        print("<br />----------------------FOREACH STATIONS AS STATION >> OWNER---------------------------------<br />");
                        var_dump($station->owner);
                        print("<br />-------------------------------------------------------------------------------------------<br />");
		
                        print("<br />---------------------FOREACH STATIONS AS STATION >> CREATOR--------------------------------<br />");
                        var_dump($station->creator);
                        print("<br />-------------------------------------------------------------------------------------------<br />");
		}

		// Get the Station owned by the User
		$station = $user->station;

                print("<br />------------------------------------USER >> STATION------------------------------------------------<br />");
                var_dump($station);
                print("<br />---------------------------------------------------------------------------------------------------<br />");

		// Get the stationIcon from the Station
		$stationIcon = $station->stationIcon;

                print("<br />---------------------------------STATION >> STATION ICON-------------------------------------------<br />");
                var_dump($stationIcon);
                print("<br />---------------------------------------------------------------------------------------------------<br />");

		// Get the Station from the StationIcon
		$station = $stationIcon->station;

                print("<br />---------------------------STATION >> STATION ICON >> STATION--------------------------------------<br />");
                var_dump($station);
                print("<br />---------------------------------------------------------------------------------------------------<br />");

		// Get the UserPreferences for this user
		$preferences = $user->preferences;

                print("<br />----------------------------------USER >> PREFERENCES----------------------------------------------<br />");
                var_dump($preferences);
                print("<br />---------------------------------------------------------------------------------------------------<br />");

		// Get the User from the UserPreferences object
		$user = $preferences->user;

                print("<br />---------------------------------PREFERENCES >> USER-----------------------------------------------<br />");
                var_dump($user);
                print("<br />---------------------------------------------------------------------------------------------------<br />");

                // Get the UserPreferences for this user
                $preferences = $user->preferences;

                print("<br />----------------------------PREFERENCES >> USER >> PREFERENCES-------------------------------------<br />");
                var_dump($preferences);
                print("<br />---------------------------------------------------------------------------------------------------<br />");

		// Get the Theme from UsersPreferences
		$theme = $preferences->theme;

                print("<br />---------------------------------PREFERENCES >> THEME----------------------------------------------<br />");
                var_dump($theme);
                print("<br />---------------------------------------------------------------------------------------------------<br />");

		// Get the preferences that use the Theme
		$theme_preferences = $theme->preferences;

                print("<br />---------------------------------THEME >> PREFERENCES----------------------------------------------<br />");
                var_dump($theme_preferences);
                print("<br />---------------------------------------------------------------------------------------------------<br />");

		// Get the Language from the UsersPreferences
		$language = $preferences->language;
	
                print("<br />--------------------------------PREFERENCES >> LANGUAGE--------------------------------------------<br />");
                var_dump($language);
                print("<br />---------------------------------------------------------------------------------------------------<br />");

		// Get the preferences that use the Language
                $language_preferences = $language->preferences;
	
                print("<br />---------------------------------LANGUAGE >> PREFERENCES-------------------------------------------<br />");
                var_dump($language_preferences);
                print("<br />---------------------------------------------------------------------------------------------------<br />");

		// Get the User's Role object
		$role = $user->role;
	
                print("<br />-------------------------------------USER >> ROLE--------------------------------------------------<br />");
                var_dump($role);
                print("<br />---------------------------------------------------------------------------------------------------<br />");
	
		// Get all User objects associated with the Role
		$users = $role->users;
                // Retrieve Role with id = $role_id
                $users_role = Role::find($user->role_id);

                // Retrieve all users of a particular role
                $users = $users_role->users;

                print("<br />-------------------------------------ROLE >> USERS-------------------------------------------------<br />");
                var_dump($users);
                print("<br />---------------------------------------------------------------------------------------------------<br />");

                // Retrieve the usersConfig from the User object
                $users_config = $user->usersConfig;

                print("<br />-----------------------------------USER >> USERSCONFIG---------------------------------------------<br />");
                var_dump($users_config);
                print("<br />---------------------------------------------------------------------------------------------------<br />");

                // Retrieve the User that a UserConfig object belongs to
                $user = $users_config->user;

                print("<br />-----------------------------------USERSCONFIG >> USER---------------------------------------------<br />");
                var_dump($user);
                print("<br />---------------------------------------------------------------------------------------------------<br />");

                /***********************************************************************************************************************
                 *                                             MODEL TESTING COMPLETE
                 ***********************************************************************************************************************/
		exit();
		}
