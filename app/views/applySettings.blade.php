<div id="apply_settings" data-role="dialog" data-theme="{{ $theme_body }}" data-close-btn="none">

    <div data-role="header" role="banner" data-theme="{{ $theme_bars }}" class="ui-corner-top ui-header ui-bar-{{ $theme_bars }}">
	<h1>{{ $refresh_i18n }}</h1>
    </div>

    <div data-role="content" data-theme="{{ $theme_body }}">
        
        <p>{{ $settings_saved_successfully_i18n }}</p>
        <p>{{ $force_refresh_question_i18n }}</p>
        <p><small>{{ $note_music_interruption_i18n }}</small></p>

        <a href="#" id="apply_settings_yes" data-role="button" data-rel="back" class="ui-btn ui-btn-corner-all ui-shadow ui-btn-up-{{ $theme_action }}" data-theme="{{ $theme_action }}" data-transition="pop">

            <span class="ui-btn-inner ui-btn-corner-all">
                <span class="ui-btn-text">{{ $yes_i18n }}</span>
            </span>
        </a>
        <a href="#" id="apply_settings_no" data-role="button" data-rel="back" class="ui-btn ui-btn-corner-all ui-shadow ui-btn-up-{{ $theme_buttons }}" data-theme="{{ $theme_buttons }}" data-transition="pop">

            <span class="ui-btn-inner ui-btn-corner-all">
                <span class="ui-btn-text">{{ $no_i18n }}</span>
            </span>
        </a>
    </div>
    
    <div data-role="footer" data-theme="{{ $theme_bars }}">&nbsp;</div>
</div>
