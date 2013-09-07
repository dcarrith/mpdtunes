<div data-role="header" data-theme="{{ $theme_bars }}" class="ui-bar-{{ $theme_bars }} ui-header" role="banner" data-position="fixed" data-tap-toggle="false" data-update-page-padding="false" >

    {{ HTML::link('', $back_i18n, array('id'=>'header-back-link', 'data-role'=>'button', 'data-theme'=>$theme_buttons, 'data-icon'=>'arrow-l', 'data-iconpos'=>'left', 'class'=>'ui-btn-left ui-btn-up-'.$theme_buttons, 'data-transition'=>$default_page_transition, 'data-direction'=>'reverse', 'data-rel'=>'back', 'title'=>$back_i18n)) }}

	<h1 id="headerTitleDiv" class="ui-title" tabindex="0" role="heading" aria-level="1"></h1>

    {{ HTML::link($home_link, $home_i18n, array('id'=>'header-home-link', 'data-role'=>'button', 'data-theme'=>$theme_buttons, 'data-icon'=>'home', 'data-iconpos'=>'notext', 'class'=>'ui-btn-right ui-btn-up-'.$theme_buttons, 'data-transition'=>$default_home_transition, 'data-direction'=>'reverse', 'title'=>$home_i18n, 'data-ajax'=>$home_link_data_ajax)) }}

</div>
