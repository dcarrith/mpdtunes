@extends('layouts.anonymous')

@section('content')

<div data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}" id="login"> 

	<div class="mpdtunes-logo-banner-div width-hundred-percent">

		<div data-role="header" role="banner" class="mpdtunes-logo-banner-header width-hundred-percent ui-bar-{{ $theme_bars }} ui-header"></div>
		<div data-role="header" role="banner" class="mpdtunes-logo-banner-main width-hundred-percent align-center ui-bar-{{ $theme_bars }} ui-header">
			<div class="mpdtunes-logo-div-outer">
				<div class="mpdtunes-logo-div"></div>
			</div>
		</div>
	</div>


	<div class="spacer-div-fifteen"></div>
		
	<div class="align-center" data-role="content" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">

		{{ Form::open(array('id'=>'login_form', 'route'=>'login', 'method'=>'POST', 'data-ajax'=>'false')) }}

		{{ Form::input('hidden', 'csrf_token', csrf_token()) }}

		<div data-role="fieldcontain" class="align-left">

			<div class="form-field-div width-hundred-percent center-element">
				    
			  	{{ Form::label('username', $username_i18n) }}

				{{-- Form :: input ( type, name, value, other attributes ) --}}	
			  	{{ Form::input('text', 'username', $username, array('id'=>'username', 'data-theme'=>$theme_buttons)) }}

				@if ($errors->has('username'))
				<div class="required-field-error">{{ $errors->first('username') }}</div>
				@endif

			</div>

			<div class="form-field-div width-hundred-percent center-element">

				{{ Form::label('password', $password_i18n) }}

			    	{{ Form::input('password', 'password', $password, array('id'=>'password', 'data-theme'=>$theme_buttons)) }}
				
				@if ($errors->has('password'))	
				<div class="required-field-error">{{ $errors->first('password') }}</div>
				@endif	

                        	@if (Session::has('login_errors'))
                                <div class="required-field-error">The password is incorrect</div>
                       	 	@endif

			</div>
		</div>

		<div class="spacer-div-fifteen"></div>

		{{ Form::submit($login_i18n, array('id'=>'login_submit', 'name'=>'login_submit', 'type'=>'submit', 'data-theme'=>$theme_action, 'aria-disabled'=>'false')) }}
	
		{{ HTML::link('register', $register_i18n, array('data-role'=>'button', 'data-theme'=>$theme_buttons, 'data-transition'=>$default_dialog_transition, 'data-ajax'=>'true', 'title'=>$register_i18n)) }}
	
	</form>
</div>

<div class="fake-footer-outer-div width-hundred-percent">
	<div data-role="footer" class="fake-footer-outer-div fake-footer-inner-div width-hundred-percent ui-bar-{{ $theme_bars }} ui-footer" data-position="fixed"></div>
</div>

@stop
