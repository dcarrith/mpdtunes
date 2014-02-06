<li id="queueTrack_{{ $track['Pos'] }}" class="ui-li-has-thumb" style="position:relative;" data-queue-track-index="{{ $track['Pos'] }}" data-queue-track-title="{{ $track['Title'] }}" data-queue-track-file="{{ $track['file'] }}">
	<a href="#queueTrackPopupMenu" data-rel="popup" data-icon="none" class="ui-link-inherit">
		<img src="{{ $track['Art'] }}" class="ui-li-thumb track-item-image" />
		<h3 class="track-title-heading ui-li-heading">{{ $track['Track'] }} - {{ $track['Title'] }}</h3>
		<p class='ui-li-aside ui-li-desc'><?php echo get_timer_display( $track['Time'] ); ?></p>
	</a>
	<a href="" data-icon="move" class="{{ $theme_icon_class }} ui-li-link-alt ui-btn ui-btn-up-{{ $theme_buttons }} move" data-theme="{{ $theme_buttons }}" title="{{ $taphold_then_drag_to_reorder_i18n }}">{{ $taphold_then_drag_to_reorder_i18n }}</a>
</li>
