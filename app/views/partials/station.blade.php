<li class="ui-li-has-thumb">

	<a href="/stations/edit/{{ $station->id }}" data-transition="{{ $default_dialog_transition }}" data-rel="dialog">

		<img src="/{{ $station->stationsIcon->baseurl }}{{ $station->stationsIcon->filename }}" class="ui-li-thumb stations-li-image" />
		<h3 class="ui-li-heading stations-li-text">{{ stripslashes($station->name) }}</h3>
		<p class="ui-li-desc stations-li-text">{{ stripslashes($station->description) }}</p>
	</a>

	<a href="" data-icon="add" onclick="post = { 'parameters' : [ { 'station_url' : '{{ $station->url }}' } ] };  control_mpd('add_url', post.parameters[0]); $.mobile.showPageLoadingMsg(theme.bars, 'Adding stream to queue', true); setTimeout(function(){ $.mobile.hidePageLoadingMsg(); }, 1500);" class="ui-li-link-alt ui-btn ui-btn-up-{{ $theme_buttons }}" data-theme="{{ $theme_buttons }}" title="{{ $add_stream_to_queue_i18n }}">{{ $add_stream_to_queue_i18n }}</a>

</li>
