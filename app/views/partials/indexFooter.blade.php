<div id="index_footer" data-role="footer" data-theme="{{ $theme_bars }}" data-position="fixed" data-tap-toggle="false" data-update-page-padding="false" >

	<div id="trackProgressDiv" style="{{ $track_progress_div_display }}">
		<table class="width-hundred-percent">
			<tr>
			    <td colspan="3" class="width-hundred-percent">
			    	<div class="width-hundred-percent ui-bar-progress-div">
			            <div class="ui-bar-progress ui-bar-load-progress" style="{{ $load_progress_div_width }}" id="loadProgressDiv" >
			            </div>
	                    <div class="ui-bar-progress ui-bar-play-progress" style="{{ $play_progress_div_width }}" id="playProgressDiv" >
	                    </div>
	                    <div class="ui-bar-progress ui-bar-stream-progress" id="streamProgressDiv" >
	                    </div>
		            </div>
			    </td>
			</tr>
			<tr>
				<td class="track-play-duration-cell valign-bottom align-left" >

					<div id="trackPlayDuration">{{ $current_audio_time }}</div>
				</td>
				<td class="track-duration-middle-cell align-center">
					<!--<div id="stationStreamProgressDiv"></div>-->
				</td>
				<td class="track-total-duration-cell valign-bottom align-right">
					<div id="trackTotalDuration">{{ $current_track_duration }}</div>
				</td>
			</tr>
		</table>
	</div>
</div>
