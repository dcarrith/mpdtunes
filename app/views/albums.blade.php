@extends('layouts.master')

@section('content')

<div id="albums" data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}" {{ $data_url }}>
	
	@include('partials.header')

	<div data-role="content">

		<div class="padding-bottom-ten-pixels">

			<a href="#" onclick="post = { 'parameters' : [ { 'artist_name' : '{{ $artist_name }}' } ] }; control_mpd('add_albums', post.parameters[0]);" data-role="button" data-theme="{{ $theme_action }}" class="ui-btn ui-btn-corner-all ui-shadow ui-btn-up-{{ $theme_action }}">

				<span class="ui-btn-inner ui-btn-corner-all">
					<span class="ui-btn-text">{{ $add_all_albums_to_queue_i18n }}</span>
				</span>
			</a>
		</div>

		<input type="hidden" id="param_one" name="param_one" value="{{ $artist_name }}" />

		<ul id="albumsList" data-role="listview" data-filter="true" data-inset="true" data-divider-theme="{{ $theme_bars }}" data-theme="{{ $theme_buttons }}"> 

			<li data-role="list-divider">{{ $albums_i18n }}</li>

			@foreach($albums as $album)

				@include('partials.album')

			@endforeach

			<li data-role="list-divider"></li>
		</ul>

		@include('partials/lazyloaderProgress')

	</div> 

	@include('partials.footer')
</div>

@stop
