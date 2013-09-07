<li class="ui-li-has-thumb">

	<a href="/artist/{{ $encoded_artist_name }}/album/{{ $album['encoded_album_name'] }}/tracks" class="ui-link-inherit" data-transition="{{ $default_page_transition }}">
		<img src="/{{ $album['album_art'] }}" class="ui-li-thumb album-art-img" />
		<h3 class="ui-li-heading album-name-heading">{{ $album['album_name'] }}</h3>
		
		@if($show_album_tracks_length)
		<p class="ui-li-aside ui-li-desc">{{ $album['total_length'] }}</p>
		@endif
		
		@if($show_album_count_bubbles) 
		<span class="ui-li-count ui-btn-up-{{ $theme_buttons }} ui-btn-corner-all">{{ $album['track_count'] }}</span>
		@endif
	</a>
</li>
