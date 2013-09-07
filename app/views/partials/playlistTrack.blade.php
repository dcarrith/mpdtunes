<li id="playlistTrack_{{ $i }}" style="position:relative;" data-playlist-track-index="{{ $i }}" data-playlist-track-file="{{ $tracks[$i][1] }}" class="ui-li-has-thumb ui-btn ui-btn-icon-right ui-btn-up-{{ $theme_buttons }}" data-theme="{{ $theme_buttons }}">

        <a href="#{{ $popupMenuId }}" data-rel="popup" data-theme="{{ $theme_buttons }}">
		<img src="{{ $tracks[$i][3] }}" class="ui-li-thumb track-item-image" />
                <h3 class="ui-li-heading">{{ stripslashes( $tracks[$i][0] ) }}</h3>
                <p class="ui-li-aside ui-li-desc">{{ get_timer_display( $tracks[$i][2] ) }}</p>
        </a>

	<a href="" data-icon="move" class="{{ $theme_icon_class }} ui-li-link-alt ui-btn ui-btn-up-{{ $theme_buttons }} move" data-theme="{{ $theme_buttons }}" title="{{ $taphold_then_drag_to_reorder_i18n }}">{{ $taphold_then_drag_to_reorder_i18n }}</a>
</li>
