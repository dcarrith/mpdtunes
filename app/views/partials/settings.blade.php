
	<div data-role="popup" data-overlay-theme="{{ $theme_body }}" data-shadow="true" id="{{ $popupMenuId; }}" data-theme="{{ $theme_body }}">

		<a href="#" data-rel="back" data-role="button" data-theme="{{ $theme_buttons }}" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>

		<input type="hidden" id="currently_selected_theme_id" value="{{ $selected_theme; }}" />
		<input type="hidden" id="currently_selected_language_id" value="{{ $selected_language; }}" />

		<ul data-role="listview" data-inset="true" data-theme="{{ $theme_buttons }}" data-divider-theme="{{ $theme_bars }}">

			<li data-role="divider" class="ui-li ui-li-static ui-corner-top">{{ $settings_i18n }}</li>

			<li data-icon="delete" data-iconpos="right" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="div" class="ui-btn ui-li">
					
				{{ HTML::link('', $clear_playlist_i18n, array('onclick'=>'control_mpd("clear")', 'data-rel'=>'back', 'data-direction'=>'reverse', 'class'=>'ui-link-inherit', 'data-theme'=>$theme_buttons)) }}
			</li>

			<div data-role="collapsible-set" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" data-iconpos="right" data-theme="{{ $theme_action }}" data-item-name="Languages">
				<div data-role="collapsible" data-inset="true" data-collapsed="true">

					<form class="ui-filterable">
    						<input id="languagesListFilter" data-type="search">
					</form>

					<h2>{{ $languages_i18n }}</h2>

					<ul id="availableLanguages" data-role="listview" data-filter="true" data-input="#languagesListFilter" data-theme="{{ $theme_action }}">

						@if (isset($language_options) && (count($language_options) > 0) && $language_options != '') 

							@foreach ($language_options as $key=>$value)

								@include('partials.languageOption')
								
							@endforeach

						@endif
					</ul>
				</div>
			</div>

			<div data-role="collapsible-set" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" data-iconpos="right" data-theme="{{ $theme_action }}" data-item-name="Themes">
				<div data-role="collapsible" data-inset="true" data-collapsed="true">

					<form class="ui-filterable">
    						<input id="themesListFilter" data-type="search">
					</form>

					<h2>{{ $themes_i18n }}</h2>

					<ul id="availableThemes" data-role="listview" data-filter="true" data-input="#themesListFilter" data-theme="{{ $theme_action }}">

						@if (isset($theme_options) && (count($theme_options) > 0) && $theme_options != '')

							@foreach($theme_options as $key=>$value)

								@include('partials.themeOption')
								
							@endforeach

						@endif
					</ul>
				</div>
			</div>

			<li data-icon="arrow-r" data-iconpos="right" data-inset="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="div" class="ui-btn ui-li">
					
				{{ HTML::link('#', $volume_crossfade_i18n, array('id'=>'volume_crossfade_button', 'class'=>'ui-link-inherit', 'data-theme'=>$theme_buttons)) }}
			</li>

			<li data-icon="refresh" data-iconpos="right" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="div" class="ui-btn ui-li">
					
				{{ HTML::link('', $refresh_mpd_database_i18n, array('onclick'=>'control_mpd(\'update\');', 'data-rel'=>'back', 'data-direction'=>'reverse', 'class'=>'ui-link-inherit', 'data-theme'=>$theme_buttons)) }}
			</li>

			<li data-role="divider" class="ui-li ui-li-static ui-corner-top"></li>
		</ul>
	</div>
