	
		<div data-role="popup" data-overlay-theme="{{ $theme_body }}" data-shadow="true" id="{{ $popupMenuId }}" data-theme="{{ $theme_body }}">
			<a href="#" data-rel="back" data-role="button" data-theme="{{ $theme_body }}" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<ul data-role="listview" data-inset="true" data-theme="{{ $theme_body }}">
				<li data-role="divider" data-theme="{{ $theme_body }}">Track Management</li>
				<li data-icon="plus" data-iconpos="right" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-theme="{{ $theme_action }}" class="ui-btn ui-btn-icon-right ui-li ui-btn-up-{{ $theme_action }}">
					<a href="" data-id="addTrackToQueue" data-theme="{{ $theme_action }}" class="ui-link-inherit">Add to Current Queue</a>
				</li>

				<div data-role="collapsible-set" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" data-iconpos="right" data-theme="{{ $theme_action }}">
					<div data-role="collapsible" data-inset="true" data-collapsed="true">
						<h2>Add to Playlist</h2>

						<ul id="availablePlaylists" data-role="listview" data-theme="{{ $theme_action }}">

							<?php if (isset($playlists) && (count($playlists) > 0) && $playlists != '') : ?>

								<?php foreach($playlists as $key=>$value) : ?>

									<li data-playlist-name="{{ $value['playlist'] }}" data-theme="{{ $theme_action }}" data-corners="false" data-shadow="false" data-iconshadow="true" data-icon="plus" data-inset="true" data-iconpos="right">
										<a href="" data-id="addTrackToPlaylist" data-theme="{{ $theme_action }}" class="ui-link-inherit">{{ $value['playlist'] }}</a>
									</li>
										
								<?php endforeach; ?>

							<?php endif; ?>
						</ul>
					</div>
				</div>

			</ul>
		</div>
