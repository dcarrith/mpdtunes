<li id="playlistTrack_{{ $index }}" style="position:relative;" data-playlist-track-index="{{ $index }}" data-playlist-track-file="{{ $track['file'] }}" class="ui-li-has-thumb ui-btn ui-btn-icon-right ui-btn-up-{{ $theme_buttons }}" data-theme="{{ $theme_buttons }}">

        <a href="#{{ $popupMenuId }}" data-rel="popup" data-theme="{{ $theme_buttons }}">
		<img src="{{ $track['Art'] }}" class="ui-li-thumb track-item-image" />
                <h3 class="ui-li-heading">{{ $track['Track'] }} - {{ stripslashes( $track['Title'] ) }}</h3>
                <p class="ui-li-aside ui-li-desc">{{ get_timer_display( $track['Time'] ) }}</p>
        </a>

	<a href="" data-icon="move" class="{{ $theme_icon_class }} ui-li-link-alt ui-btn ui-btn-up-{{ $theme_buttons }} move" data-theme="{{ $theme_buttons }}" title="{{ $taphold_then_drag_to_reorder_i18n }}">{{ $taphold_then_drag_to_reorder_i18n }}</a>
</li>
