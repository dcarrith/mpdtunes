@extends('layouts.master')

@section('content')

<div id="admin" data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}"> 

	<?php //$this->load->view('partials/admin_index_header.php'); ?>

	@include('partials.header')

	<div data-role="content">

		<a href="admin/account" data-role="button" data-theme="{{ $theme_buttons }}" data-transition="{{ $default_dialog_transition }}"><img src="/images/account.png"/></a>

		<a href="admin/users" data-role="button" data-theme="{{ $theme_buttons }}" data-transition="{{ $default_page_transition }}"><img src="/images/users.png"/></a>

		<!--<a href="setup/database" data-role="button" data-theme="{{ $theme_buttons }}" data-transition="{{ $default_page_transition }}"><img src="/images/database.png"/></a>-->

		<a href="admin/payments" data-role="button" data-theme="{{ $theme_buttons }}" data-transition="{{ $default_dialog_transition }}"><img src="/images/payments.png"/></a>

		<!--<a href="setup/storage" data-role="button" data-theme="{{ $theme_buttons }}" data-transition="{{ $default_page_transition }}"><img src="/images/storage.png"/></a>-->		

	</div>

	@include('partials.footer')
</div>

@stop
