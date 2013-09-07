@extends('layouts.master')

@section('content')

<div id="index" data-role="page" data-theme="{{ $theme_body; }}" data-divider-theme="{{ $theme_bars; }}" data-url="/home" data-dom-cache="true"> 
	
	@include('partials.indexHeader')

	@include('partials.player')

	<div data-role="content">

		<ul id="libraryNav" data-role="listview" data-inset="true" data-divider-theme="{{ $theme_bars; }}" data-theme="{{ $theme_buttons; }}">

			<li data-role="list-divider">{{ $library_i18n; }}</li> 

			<li>
				{{ HTML::link('artists', $artists_i18n, array('data-transition'=>$default_page_transition)) }}
			</li> 
			<li>
				{{ HTML::link('genres', $genres_i18n, array('data-transition'=>$default_page_transition)) }}
			</li>
			<li>
				{{ HTML::link('playlists', $playlists_i18n, array('data-transition'=>$default_page_transition)) }}
			</li>
			<li>
				{{ HTML::link('stations', $stations_i18n, array('data-transition'=>$default_page_transition)) }}
			</li>
		</ul>

		<ul id="adminNav" data-role="listview" data-inset="true" data-divider-theme="{{ $theme_bars; }}" data-theme="{{ $theme_buttons; }}">

			<li data-role="list-divider">{{ $general_i18n; }}</li> 

			<li>
				{{ HTML::link('uploader', $upload_music_i18n, array('data-transition'=>$default_page_transition)) }}
			</li> 


			@if ($user_id != $demo_user_id)

				<li>
					{{ HTML::link('admin', $administration_i18n, array('data-transition'=>$default_page_transition, 'data-ajax'=>'false')) }}
				</li>
			@endif

		</ul>
	</div>

	@include('partials.indexFooter')

</div>

@stop
