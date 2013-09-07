<div id="index_header" data-role="header" data-theme="{{ $theme_bars }}" role="banner" data-position="fixed" data-tap-toggle="false" data-update-page-padding="false" >

	{{ HTML::link('#', $settings_i18n, array('data-role'=>'button', 'data-theme'=>$theme_buttons, 'data-icon'=>'gear', 'data-iconpos'=>'left', 'title'=>$settings_i18n, 'id'=>'settings_button')) }}

	<h1 id="headerTitleDiv" class="ui-title" tabindex="0" role="heading" aria-level="1"></h1>

	{{ HTML::link('queue', $queue_i18n, array('data-role'=>'button', 'data-theme'=>$theme_buttons, 'data-icon'=>'arrow-r', 'data-iconpos'=>'right', 'data-transition'=>$default_page_transition, 'title'=>$queue_i18n)) }}

	@include('partials/settings')

</div>
