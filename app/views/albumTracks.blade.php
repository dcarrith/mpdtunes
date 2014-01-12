@extends('layouts.master')

@section('content')

<div id="{{ $tracksPageId }}" data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">
	
	@include('partials.header')

	<div data-role="content">

		<ul data-role="listview" data-inset="true" data-divider-theme="{{ $theme_bars }}" data-theme="{{ $theme_buttons }}"> 

			<li data-role="list-divider">{{ $selected_album_i18n }}</li>

			<li>
				<table class="width-hundred-percent align-left">
					<tr>
						<td class="width-hundred-percent align-left">
							<table class="width-hundred-percent">
								<tr>
									<td rowspan="3" class="track-album-info-cell">
										<img src="{{ $album_art_file }}" class="track-album-art" alt="Album Art" />
									</td>

									<td style="text-align:left;">
										<div id="artistDiv" class="track-artist-div"><?php echo stripslashes(stripslashes($artist_name)); ?></div>
									</td>
								</tr>
								<tr>
									<td style="text-align:left;">
										<div id="albumDiv" class="track-album-div"><?php echo stripslashes($album_name); ?></div>
									</td>
								</tr>
								<tr>
									<td style="text-align:left;">
										<div id="totalLengthDiv" class="total-length-div">Album length: <?php echo $total_length; ?></div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</li>

			<li data-role="list-divider"></li>
		</ul>

		<a href="#" onclick="post = {{ $add_tracks_post_json }}; control_mpd('add_all', post.parameters[0]);" data-role="button" data-theme="{{ $theme_action }}" class="ui-btn ui-btn-corner-all ui-shadow ui-btn-up-{{ $theme_action }}">

			<span class="ui-btn-inner ui-btn-corner-all">
				<span class="ui-btn-text">{{ $add_all_songs_to_queue_i18n }}</span>
			</span>
		</a>

		<form class="ui-filterable">
    			<input id="albumTracksListFilter" data-type="search">
		</form>
 
		<input type="hidden" id="param_one" name="param_one" value="{{ $album_name }}" />

		<ul id="{{ $tracksUlId }}" data-role="listview" data-filter="true" data-input="#albumTracksListFilter" data-inset="true" data-divider-theme="{{ $theme_bars }}" data-theme="{{ $theme_buttons }}" <?php echo $dataNameAttribute; ?>> 

			<li data-role="list-divider" data-icon="plus"><{{ $tracks_i18n }}</li>
			
			@for($i = 0; $i < count($tracks); $i++)

                                @include('partials.albumTrack')

                        @endfor

			<li data-role="list-divider"></li>
		</ul>

		@include('partials/albumTrackPopup')

		<br class="clear" />		
	</div> 

	@include('partials.footer')

</div>
