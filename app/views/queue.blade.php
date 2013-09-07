@extends('layouts.master')

@section('content')

<div id="queue" data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}"> 

	@include('partials.header')

	<div id="queueTracksContent" data-role="content" class="align-center" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">
		
		<div id="currentlyPlayingInfoDiv" {{ $currently_playing_info_div_style }}>
		
			<ul data-role="listview" data-inset="true" data-divider-theme="{{ $theme_bars }}" data-theme="{{ $theme_buttons }}"> 

				<li data-role="list-divider">{{ $current_state_message }}</li>

				<li>
					<table class="width-hundred-percent align-left">
						<tr>
							<td class="width-hundred-percent align-left">
								
								<table class="width-hundred-percent">
									<tr>
										<td rowspan="3" class="currently-playing-album-art-cell align-left">
											<div id="currentlyPlayingAlbumArtDiv" class="currently-playing-album-art-div">
												<img src="{{ $current_album_art }}" alt="Album Art" class="currentalbumart"/>
											</div>
										</td>
										<td class="align-left">
											<div id="currentlyPlayingArtistDiv" class="currently-playing-info-div font-size-thirteen-pixels">{{ $current_artist }}</div>
										</td>
									</tr>
									<tr>
										<td class="align-left">
											<div id="currentlyPlayingAlbumDiv" class="currently-playing-info-div font-size-twelve-pixels">{{ $current_album }}</div>
										</td>
									</tr>
									<tr>
										<td class="align-left">
											<div id="currentlyPlayingTrackDiv" class="currently-playing-info-div font-size-eleven-pixels">{{ $current_track }}</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</li>

				<li data-role="list-divider"></li>
			</ul>
		</div>

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
