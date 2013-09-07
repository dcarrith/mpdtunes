@extends('layouts.master')

@section('content')

<div id="{{ $tracksPageId }}" data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}"><!--data-dom-cache="true"--> 
	
	@include('partials.header')

	<div data-role="content">

		{{ HTML::link('/playlist/confirm_delete?item_type=playlist&item_name='.$playlist_name, $delete_playlist_i18n, array('data-role'=>'button', 'data-rel'=>'dialog', 'data-transition'=>$default_alert_transition, 'data-theme'=>$theme_alert)) }}

		<a href="#" onclick="post = {{ $add_tracks_post_json }}; control_mpd('add_all', post.parameters[0]);" data-role="button" data-theme="{{ $theme_action }}" class="ui-btn ui-btn-corner-all ui-shadow ui-btn-up-{{ $theme_action }}">

			<span class="ui-btn-inner ui-btn-corner-all">
				<span class="ui-btn-text">{{ $add_all_songs_to_queue_i18n }}</span>
			</span>
		</a>

		<input type="hidden" id="param_one" name="param_one" value="{{ $playlist_name }}" />

		<ul id="{{ $tracksUlId }}" data-role="listview" data-inset="true" data-divider-theme="{{ $theme_bars }}" data-theme="{{ $theme_buttons }}" <?php echo $dataNameAttribute; ?>> 

			<li data-role="list-divider" data-icon="plus">{{ $tracks_i18n }}</li>
			
			@for($i = 0; $i < count($tracks); $i++)

                                @include('partials.playlistTrack')

                        @endfor
			
			<li data-role="list-divider"></li>

		</ul>

		@include('partials/playlistTrackPopup')
	
		@include('partials/lazyloaderProgress')

		<br class="clear" />		
	</div> 

	@include('partials.footer')

</div>
