<li id="albumTrack_{{ $index }}" style="position:relative;" data-album-track-index="{{ $index }}" data-album-track-file="{{ $track['file'] }}" class="ui-btn ui-btn-icon-right ui-btn-up-{{ $theme_buttons }}" data-theme="{{ $theme_buttons }}" data-icon="grid" data-icon-pos="right">

        <a href="#{{ $popupMenuId }}" data-rel="popup" data-theme="{{ $theme_buttons }}">

                <h3 class="ui-li-heading">{{ stripslashes($track['Title']) }}</h3>
                <p class='ui-li-aside ui-li-desc'>{{ get_timer_display($track['Time']) }}</p>
        </a>
</li>
