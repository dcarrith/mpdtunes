<li>	
	<a href="/artist/{{ urlencode(urlencode($artist['artist'])) }}/albums" data-transition="{{ $default_page_transition }}">
		{{ $artist['artist'] }}
		
		@if($show_album_count_bubbles) 
			<span class="ui-li-count ui-btn-up-{{ $theme_buttons }} ui-btn-corner-all">{{ $artist['album_count'] }}</span>
		@endif
	</a>
</li>
