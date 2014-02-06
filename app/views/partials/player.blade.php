<div data-role="header" class="ui-bar-{{ $theme_bars }} ui-header" role="banner">

	<div id="jukebox">
	
		<div class="controls">
	
			<table>
				<tr>
					<td>
						<div id="playerCurrentlyPlayingDiv" {{ $player_playing_div_style }}>
							<table class="width-hundred-percent">
								<tr>
									<td class="album-art-cell" style="width:68px;" >
										<div id="albumArtDiv">
											<img src="{{ $current_album_art }}" id="currentAlbumArtImg" class="albumart" alt="Album Art" />
										</div>
									</td>
									<!-- for use when testing spectrum analyzer -->
									<!--<td style="width:40%;">-->
									<td>
										
										<!--<div id="spectrum_analyzer" style="z-index: 10; position: fixed;"></div>-->
										<!--<div style="z-index: 100; position: relative;">-->
										<table>
											<tr>
												<td class="valign-top align-left">
													<div id="artistDiv">{{ $current_artist }}</div>
												</td>
											</tr>
											<tr>
												<td class="valign-top align-left">
													<div id="albumDiv">{{ $current_album }}</div>
												</td>
											</tr>
											<tr>
												<td class="valign-top align-left">
													<div id="trackDiv">{{ $current_track }}</div>
												</td>
											</tr>
										</table>
										<!--</div>-->
									</td>
									<td>
										<!--<div id="holder">-->	
										<!--<canvas id="canvas" width="800" height="512" style="display: block;"></canvas>-->
										<!--</div>-->
										<div id="spectrum_analyzer"></div>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td class="width-hundred-percent">

						<table class="width-hundred-percent">
							<tr>
								<td class="player-little-cell">

									<?php echo HTML::link('#repeatOptionsPopup', '', array('id'=>'repeat', 'data-role'=>'button', 'data-rel'=>'popup', 'data-icon'=>'repeat', 'data-iconpos'=>'notext', 'data-theme'=>(($repeat) ? $theme_action : $theme_buttons), 'class'=>$theme_icon_class, 'title'=>$repeat_i18n, 'style'=>'z-index:101;')); ?>

								</td>
								<td class="player-main-cell width-hundred-percent align-center">
					
									<div class="center-element align-center player-main-div">

										<table class="align-center">

											<tr>
												<td class="player-prev-cell align-right">
																										
													<?php echo HTML::link('#', '', array('id'=>'prev', 'data-role'=>'button', 'data-icon'=>'previous', 'data-iconpos'=>'notext', 'data-theme'=>$theme_controls, 'class'=>'button48 '.$theme_icon_class, 'title'=>$previous_i18n, 'style'=>'z-index:101;')); ?>

												</td>
												
												<td class="player-play-cell align-center">
													
													<?php echo HTML::link('#', '', array('id'=>'playpause', 'data-role'=>'button', 'data-icon'=>'play', 'data-iconpos'=>'notext', 'data-theme'=>$theme_controls, 'class'=>'button64 '.$theme_icon_class, 'title'=>$play_i18n, 'style'=>'z-index:101;')); ?>
											
												</td>

												<td class="player-next-cell align-left">

													<?php echo HTML::link('#', '', array('id'=>'next', 'data-role'=>'button', 'data-icon'=>'next', 'data-iconpos'=>'notext', 'data-theme'=>$theme_controls, 'class'=>'button48 '.$theme_icon_class, 'title'=>$next_i18n, 'style'=>'z-index:101;')); ?>
												
												</td>
											</tr>
										</table>
									</div>
								</td>

								<td class="player-little-cell">

									<?php echo HTML::link('#', '', array('id'=>'shuffle', 'data-role'=>'button', 'data-icon'=>'shuffle', 'data-iconpos'=>'notext', 'data-theme'=>(($shuffle) ? $theme_action : $theme_buttons), 'class'=>$theme_icon_class, 'title'=>$shuffle_i18n, 'style'=>'z-index:101;')); ?>

								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</div>

	@include('partials/repeatOptionsPopup')
</div>
