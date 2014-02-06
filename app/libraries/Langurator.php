<?php

class Langurator {

	public static function getLocalizedWords($page){
        	
		$words = array();
       
		switch($page) {

			case 'account':

                		$words['first_name_i18n']			= Lang::get($page.'.first_name');
                		$words['last_name_i18n'] 			= Lang::get($page.'.last_name');
                		$words['username_i18n'] 			= Lang::get($page.'.username');
                		$words['password_i18n'] 			= Lang::get($page.'.password');
                		$words['password_confirm_i18n']			= Lang::get($page.'.password_confirm');
                		$words['save_i18n'] 				= Lang::get($page.'.save');
                		$words['cancel_i18n']				= Lang::get($page.'.cancel');
                		$words['saved_successfully_i18n']		= Lang::get($page.'.saved_successfully');
				break;

			case 'admin':

				$words['mpd_host_i18n']				= Lang::get($page.'.mpd_host');
				$words['mpd_port_i18n']                  	= Lang::get($page.'.mpd_port');
				$words['mpd_stream_port_i18n']           	= Lang::get($page.'.mpd_stream_port');
				$words['mpd_password_i18n']              	= Lang::get($page.'.mpd_password');
				$words['confirm_mpd_password_i18n']      	= Lang::get($page.'.confirm_mpd_password');
				$words['mpd_dir_i18n']                   	= Lang::get($page.'.mpd_dir');
				$words['music_dir_i18n']                 	= Lang::get($page.'.music_dir');
				$words['queue_dir_i18n']                 	= Lang::get($page.'.queue_dir');
				$words['art_dir_i18n']                   	= Lang::get($page.'.art_dir');
				$words['save_i18n']                		= Lang::get($page.'.save');
				$words['cancel_i18n']              		= Lang::get($page.'.cancel');
				$words['delete_user_account_i18n'] 		= Lang::get($page.'.delete_user_account');
				$words['user_account_active_i18n'] 		= Lang::get($page.'.user_account_active');
				$words['saved_successfully_i18n']  		= Lang::get($page.'.saved_successfully');
				$words['users_i18n']				= Lang::get($page.'.users');
				break;

			case 'albums':

				$words['albums_i18n']				= Lang::get($page.'.albums');
				$words['add_all_albums_to_queue_i18n']		= Lang::get($page.'.add_all_albums_to_queue');
				break;

			case 'artists':

				$words['artists_i18n']				= Lang::get($page.'.artists');
				break;

			case 'base': 
                		
                		$words['back_i18n']				= Lang::get($page.'.back');
                		$words['home_i18n']				= Lang::get($page.'.home');
                		$words['scroll_up_i18n']			= Lang::get($page.'.scroll_up');
                		$words['scroll_down_i18n']			= Lang::get($page.'.scroll_down');
                		$words['toggle_profiler_results_i18n']		= Lang::get($page.'.toggle_profiler_results');
                		$words['meta_description_content_i18n']		= Lang::get($page.'.meta_description_content');
				$words['confirm_delete_i18n']			= Lang::get($page.'.confirm_delete');
				$words['are_you_sure_i18n']			= Lang::get($page.'.are_you_sure');
				$words['note_gone_forever_i18n']		= Lang::get($page.'.note_gone_forever');
				$words['yes_i18n']				= Lang::get($page.'.yes');
				$words['no_i18n']				= Lang::get($page.'.no');
				break;

			case 'genres':

				$words['genres_i18n']				= Lang::get($page.'.genres');
				break;

                        case 'home':

                		$words['library_i18n']				= Lang::get($page.'.library');
                		$words['artists_i18n']				= Lang::get($page.'.artists');
                		$words['genres_i18n']				= Lang::get($page.'.genres');
                		$words['playlists_i18n']			= Lang::get($page.'.playlists');
                		$words['stations_i18n']				= Lang::get($page.'.stations');
                		$words['settings_i18n']				= Lang::get($page.'.settings');
                		$words['queue_i18n']				= Lang::get($page.'.queue');
                		$words['repeat_i18n']				= Lang::get($page.'.repeat');
                		$words['previous_i18n']				= Lang::get($page.'.previous');
                		$words['play_i18n']				= Lang::get($page.'.play');
                		$words['next_i18n']				= Lang::get($page.'.next');
                		$words['shuffle_i18n']				= Lang::get($page.'.shuffle');
                		$words['upload_music_i18n']			= Lang::get($page.'.upload_music');
                		$words['my_account_i18n']			= Lang::get($page.'.my_account');
                		$words['my_site_i18n']				= Lang::get($page.'.my_site');
                		$words['administration_i18n']			= Lang::get($page.'.administration');
                		$words['general_i18n']				= Lang::get($page.'.general');
				$words['clear_playlist_i18n']			= Lang::get($page.'.clear_playlist');
                		$words['refresh_mpd_database_i18n']		= Lang::get($page.'.refresh_mpd_database');
                		$words['settings_i18n']				= Lang::get($page.'.settings');
                		$words['languages_i18n']			= Lang::get($page.'.languages');
                		$words['themes_i18n']				= Lang::get($page.'.themes');
                		$words['volume_crossfade_i18n']			= Lang::get($page.'.volume_crossfade');
                		$words['meta_description_content_i18n']		= Lang::get($page.'.meta_description_content');
				break;

			case 'login':
 
                		$words['username_i18n']				= Lang::get($page.'.username');
                		$words['password_i18n']				= Lang::get($page.'.password');
                		$words['login_i18n']				= Lang::get($page.'.login');
                		$words['register_i18n']				= Lang::get($page.'.register');
                		$words['meta_description_content_i18n']		= Lang::get($page.'.meta_description_content');   
				break;

			case 'payments':

                        	$words['paypal_sandbox_mode_i18n']         	= Lang::get($page.'.paypal_sandbox');
                        	$words['sandbox_master_account_i18n']     	= Lang::get($page.'.sandbox_master_account');
                        	$words['sandbox_api_username_i18n']       	= Lang::get($page.'.sandbox_api_username');
                        	$words['sandbox_api_password_i18n']        	= Lang::get($page.'.sandbox_api_password');
                        	$words['sandbox_api_signature_i18n']       	= Lang::get($page.'.sandbox_api_signature');
                        	$words['paypal_master_account_i18n']       	= Lang::get($page.'.paypal_master_account');
                        	$words['paypal_api_username_i18n']         	= Lang::get($page.'.paypal_api_username');
                        	$words['paypal_api_password_i18n']         	= Lang::get($page.'.paypal_api_password');
                        	$words['paypal_api_signature_i18n']        	= Lang::get($page.'.paypal_api_signature');
                        	$words['save_i18n']                        	= Lang::get($page.'.save');
                        	$words['cancel_i18n']                      	= Lang::get($page.'.cancel');
                        	$words['saved_successfully_i18n']          	= Lang::get($page.'.saved_successfully');
				break;

			case 'paypal':

                		$words['registration_complete_i18n']       	= Lang::get($page.'.registration_complete');
                		$words['success_message_first_half_i18n']  	= Lang::get($page.'.success_message_first_half');
                		$words['success_message_second_half_i18n'] 	= Lang::get($page.'.success_message_second_half');
                		$words['subscribe_i18n']                   	= Lang::get($page.'.subscribe');

                		$words['credit_card_type_i18n']            	= Lang::get($page.'.credit_card_type');
                		$words['credit_card_number_i18n']          	= Lang::get($page.'.credit_card_number');
                		$words['credit_card_expiration_date_i18n'] 	= Lang::get($page.'.credit_card_expiration_date');
                		$words['credit_card_ccv_i18n']             	= Lang::get($page.'.credit_card_ccv');
	
                		$words['subscription_account_level_i18n']  	= Lang::get($page.'.subscription_account_level');
				break;		
				
			case 'playlists':

				$words['playlists_i18n']   			= Lang::get($page.'.playlists');
				$words['playlist_name_i18n'] 			= Lang::get($page.'.playlist_name');
				$words['enter_playlist_name_i18n']		= Lang::get($page.'.enter_playlist_name');
				$words['playlist_with_that_name_exists_i18n']	= Lang::get($page.'.playlist_with_that_name_exists');
				$words['playlist_name_max_length_i18n']		= Lang::get($page.'.playlist_name_max_length');
				$words['save_i18n'] 				= Lang::get($page.'.save');
				$words['cancel_i18n'] 				= Lang::get($page.'.cancel');				
				$words['saved_successfully_i18n']  		= Lang::get($page.'.saved_successfully');
				break;

			case 'queue':

                		$words['current_queue_i18n']			= Lang::get($page.'.current_queue');
                		$words['create_playlist_i18n']			= Lang::get($page.'.create_playlist_based_on_queue');
                		$words['taphold_then_drag_to_reorder_i18n']	= Lang::get($page.'.taphold_then_drag_to_reorder');
		                $words['playlist_name_i18n']			= Lang::get($page.'.playlist_name');
				$words['save_i18n']				= Lang::get($page.'.save');
                		$words['cancel_i18n']				= Lang::get($page.'.cancel');
				$words['saved_successfully_i18n']  		= Lang::get($page.'.saved_successfully');
				break;
			
			case 'recaptcha':

                        	$words['instructions_visual_i18n'] 		= Lang::get($page.'.instructions_visual');
                        	$words['instructions_audio_i18n']		= Lang::get($page.'.instructions_audio');
                        	$words['play_again_i18n']			= Lang::get($page.'.play_again');
                        	$words['cant_hear_this_i18n']			= Lang::get($page.'.cant_hear_this');
                        	$words['visual_challenge_i18n']			= Lang::get($page.'.visual_challenge');
				$words['audio_challenge_i18n']			= Lang::get($page.'.audio_challenge');
                        	$words['refresh_btn_i18n']			= Lang::get($page.'.refresh_btn');
				$words['help_btn_i18n']				= Lang::get($page.'.help_btn');
                        	$words['incorrect_try_again_i18n']		= Lang::get($page.'.incorrect_try_again');
				break;

			case 'register':

        	                $words['first_name_i18n']			= Lang::get($page.'.first_name');
        	                $words['last_name_i18n']			= Lang::get($page.'.last_name');
        	                $words['username_i18n']				= Lang::get($page.'.username');
        	                $words['password_i18n']				= Lang::get($page.'.password');
        	                $words['password_confirm_i18n']			= Lang::get($page.'.password_confirm');
        	                $words['submit_i18n']				= Lang::get($page.'.submit');
        	                $words['cancel_i18n']				= Lang::get($page.'.cancel');	
				$words['refresh_captcha_i18n']			= Lang::get($page.'.refresh_captcha');
        	                $words['switch_type_audio_i18n']		= Lang::get($page.'.switch_type_audio');
        	                $words['switch_type_image_i18n']		= Lang::get($page.'.switch_type_image');
        	                $words['show_captcha_help_i18n']		= Lang::get($page.'.show_captcha_help');
        	                $words['recaptcha_challenge_i18n']		= Lang::get($page.'.recaptcha_challenge');
        	                $words['enter_the_two_words_above_i18n']	= Lang::get($page.'.enter_the_two_words_above');
        	                $words['enter_the_numbers_you_hear_i18n']	= Lang::get($page.'.enter_the_numbers_you_hear');
        	                $words['meta_description_content_i18n']		= Lang::get($page.'.meta_description_content');
				$words['success_message_first_half_i18n']	= Lang::get($page.'.success_message_first_half');	
				$words['success_message_second_half_i18n']	= Lang::get($page.'.success_message_second_half');	
				$words['registration_complete_i18n']		= Lang::get($page.'.registration_complete');
				break;

			case 'settings': 

		                $words['clear_playlist_i18n']              	= Lang::get($page.'.clear_playlist');
                		$words['theme_i18n']                       	= Lang::get($page.'.theme');
                		$words['operating_mode_i18n']              	= Lang::get($page.'.operating_mode');
                		$words['volume_control_i18n']              	= Lang::get($page.'.volume');
                		$words['xfade_control_i18n']               	= Lang::get($page.'.xfade_in_seconds');
                		$words['mixrampdb_control_i18n']               	= Lang::get($page.'.mixrampdb_in_decibels');
                		$words['mixrampdelay_control_i18n']             = Lang::get($page.'.mixrampdelay_in_seconds');
                		$words['volume_fade_control_i18n']         	= Lang::get($page.'.volume_fade_in_seconds');
                		$words['preferred_language_i18n']          	= Lang::get($page.'.preferred_language');
                		$words['refresh_mpd_database_i18n']        	= Lang::get($page.'.refresh_mpd_database');
				$words['theme_name_i18n']	  		= Lang::get($page.'.theme_name');
				$words['bars_i18n'] 	 			= Lang::get($page.'.bars');
				$words['buttons_i18n']				= Lang::get($page.'.buttons');
				$words['body_i18n']				= Lang::get($page.'.body');
				$words['controls_i18n']  			= Lang::get($page.'.controls');
				$words['action_i18n']				= Lang::get($page.'.action');
				$words['active_state_i18n']			= Lang::get($page.'.active_state');
				$words['icon_color_i18n']			= Lang::get($page.'.icon_color');
				$words['save_i18n']                        	= Lang::get($page.'.save');
                		$words['cancel_i18n']                      	= Lang::get($page.'.cancel');
                		$words['refresh_i18n']                     	= Lang::get($page.'.refresh');
                		$words['settings_saved_successfully_i18n'] 	= Lang::get($page.'.settings_saved_successfully');
                		$words['force_refresh_question_i18n']      	= Lang::get($page.'.force_refresh_question');
                		$words['note_music_interruption_i18n']     	= Lang::get($page.'.note_music_interruption');
                		$words['yes_i18n']                         	= Lang::get($page.'.yes');
                		$words['no_i18n']                          	= Lang::get($page.'.no');
				break;

			case 'stations':

				$words['customize_your_station_i18n']		= Lang::get($page.'.customize_your_station');
				$words['add_a_new_station_i18n']		= Lang::get($page.'.add_a_new_station');
				$words['delete_station_i18n']			= Lang::get($page.'.delete_station');
				$words['stations_i18n']				= Lang::get($page.'.stations');
	                	$words['station_url_i18n'] 			= Lang::get($page.'.station_url');
                		$words['station_name_i18n'] 			= Lang::get($page.'.station_name');
                		$words['station_description_i18n']		= Lang::get($page.'.station_description');
                		$words['save_i18n']				= Lang::get($page.'.save');
                		$words['cancel_i18n']				= Lang::get($page.'.cancel');
                		$words['saved_successfully_i18n']		= Lang::get($page.'.saved_successfully'); 
 	             		$words['cant_be_changed_i18n'] 			= Lang::get($page.'.cant_be_changed');
				$words['url_special_note_i18n'] 		= Lang::get($page.'.must_be_online');
                		$words['icon_jpg_gif_or_png_i18n'] 		= Lang::get($page.'.icon_jpg_gif_or_png');
                		$words['station_name_maximum_i18n']		= Lang::get($page.'.station_name_maximum');
                		$words['station_description_maximum_i18n']	= Lang::get($page.'.station_description_maximum');
                		$words['broadcast_to_the_public_i18n'] 		= Lang::get($page.'.broadcast_to_the_public');
        			$words['saved_successfully_i18n']		= Lang::get($page.'.saved_successfully');
				$words['add_stream_to_queue_i18n']		= Lang::get($page.'.add_stream_to_queue');
				break;
	
			case 'tracks':

               	 		$words['tracks_i18n']				= Lang::get($page.'.tracks');
                		$words['selected_album_i18n']			= Lang::get($page.'.selected_album');
                		$words['add_this_song_to_queue_i18n']		= Lang::get($page.'.add_this_song_to_queue');
                		$words['taphold_then_drag_to_reorder_i18n']	= Lang::get($page.'.taphold_then_drag_to_reorder');
                		$words['add_all_songs_to_queue_i18n']		= Lang::get($page.'.add_all_songs_to_queue');
                		$words['delete_playlist_i18n']			= Lang::get($page.'.delete_playlist');
				break;

			case 'uploader':

				$words['no_html5_support_i18n'] 		= Lang::get($page.'.no_html5_support');
				$words['all_done_i18n'] 			= Lang::get($page.'.all_done');
				break;

			case 'users':

				break;	
			
			default: 

				break;
		}

		return $words;
	}
}

?>
