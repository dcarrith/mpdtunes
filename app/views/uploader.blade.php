@extends('layouts.master')

@section('content')

<div id="music_uploader" data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}" data-dom-cache="true">

	@include('partials.header')

	<div data-role="content">

		<div class="padding-bottom-fifteen-pixels"></div>

		<form id="music_file_uploader">

		    <div id="uploader">
		        <p>{{ $no_html5_support_i18n }}</p>
		    </div>
		</form>
	
		<?php echo HTML::link('/', $all_done_i18n, array('data-role'=>'button', 'data-rel'=>'back', 'data-direction'=>'reverse', 'data-theme'=>$theme_buttons, 'data-transition'=>$default_page_transition)); ?>

	</div>

	@include('partials.footer')

</div>

@stop
