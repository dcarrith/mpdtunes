
		<div data-role="popup" id="queueTrackPopupMenu" data-overlay-theme="{{ $theme_body }}" data-shadow="true" data-theme="{{ $theme_body }}">
			<a href="#" data-rel="back" data-role="button" data-theme="{{ $theme_body }}" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<ul data-role="listview" data-inset="true" data-theme="{{ $theme_body }}">
				<li data-role="divider" data-theme="{{ $theme_body }}">Track Management</li>
				<li data-icon="plus" data-iconpos="right" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-theme="{{ $theme_action }}" class="ui-btn ui-btn-icon-right ui-li ui-btn-up-{{ $theme_action }}">
					<a href="" data-id="playQueueTrack" data-theme="{{ $theme_action }}" class="ui-link-inherit" title="Bump to top of queue">Play Track</a>
				</li>
				<li data-icon="delete" data-iconpos="right" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-theme="{{ $theme_alert }}" class="ui-btn ui-btn-icon-right ui-li ui-btn-up-{{ $theme_alert }}">
					<a href="" data-id="removeFromQueue" data-theme="{{ $theme_alert }}" class="ui-link-inherit" title="Remove from queue">Remove from Queue</a>
				</li>
			</ul>
		</div>

