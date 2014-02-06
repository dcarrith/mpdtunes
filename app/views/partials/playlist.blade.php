<li>
	<a href="/playlist/{{ urlencode(urlencode($playlist['name'])) }}/tracks" data-transition="{{ $default_page_transition }}">{{ $playlist['name'] }}
	@if ($show_playlist_track_count_bubbles)
		<span class="ui-li-count ui-btn-up-{{ $theme_buttons }} ui-btn-corner-all">{{ $playlist['tracks_count'] }}</span>
	@endif

	</a> 
</li>
