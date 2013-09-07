<li id="albumTrack_{{ $i }}" style="position:relative;" data-album-track-index="{{ $i }}" data-album-track-file="{{ $tracks[$i][1] }}" class="ui-btn ui-btn-icon-right ui-btn-up-{{ $theme_buttons }}" data-theme="{{ $theme_buttons }}" data-icon="grid" data-icon-pos="right">

        <a href="#{{ $popupMenuId }}" data-rel="popup" data-theme="{{ $theme_buttons }}">

                <h3 class="ui-li-heading">{{ stripslashes($tracks[$i][0]) }}</h3>
                <p class='ui-li-aside ui-li-desc'>{{ get_timer_display($tracks[$i][2]) }}</p>
        </a>
</li>
