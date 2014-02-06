@extends('layouts.master')

@section('content')

<div id="queue" data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}"> 

	@include('partials.header')

	<div id="queueTracksContent" data-role="content" class="align-center" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">

		{{ HTML::link('playlist/create', $create_playlist_i18n, array('data-role'=>'button', 'data-rel'=>'dialog', 'data-transition'=>$default_dialog_transition, 'data-theme'=>$theme_action)) }}

		<ul id="queueTracksList" data-role="listview" data-inset="true" class="ui-listview ui-listview-inset ui-corner-all ui-shadow" data-theme="{{ $theme_buttons }}" data-divider-theme="{{ $theme_bars }}">

			<li data-role="list-divider"><?php echo $current_queue_i18n; ?></li>

			@foreach($tracks as $track)

				@include('partials.queueTrack')

			@endforeach

			<li data-role="list-divider"></li>
		</ul>

		@include('partials/queueTrackPopup')

		@include('partials/lazyloaderProgress')

	</div>

	@include('partials.footer')
</div>

@stop
