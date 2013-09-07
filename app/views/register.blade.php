@extends('layouts.anonymous')

@section('content')

<div data-role="dialog" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}" id="register_form"> 

	<div class="align-center" data-role="content" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">
		
		{{ Form::open(array('id'=>'register', 'url'=>'/register', 'method'=>'POST', 'data-ajax'=>'true', 'data-transition'=>'none', 'data-history'=>'false')) }}

                {{ Form::input('hidden', 'csrf_token', csrf_token()) }}

			<input type="hidden" id="keywords" name="keywords" value="Stream your music collection to your device or remotely control your MPDTunes music server.  You can listen to your music anytime, anywhere, and on any device." />
			<input type="hidden" id="more_keywords" name="more_keywords" value="{{ $site_title }}" />

			<div data-role="fieldcontain" class="align-left">

				<div class="form-field-div width-hundred-percent center-element">

                                {{ Form::label('first_name', $first_name_i18n) }}

                                {{-- Form :: input ( type, name, value, other attributes ) --}}
				{{ Form::input('text', 'first_name', $first_name, array('id'=>'first_name', 'data-theme'=>$theme_buttons)) }}

                                @if ($errors->has('first_name'))
                                <div class="required-field-error">{{ $errors->first('first_name') }}</div>
                                @endif

			    </div>

				<div class="form-field-div width-hundred-percent center-element">

                                {{ Form::label('last_name', $last_name_i18n) }}

                                {{ Form::input('text', 'last_name', $last_name, array('id'=>'last_name', 'data-theme'=>$theme_buttons)) }}

                                @if ($errors->has('last_name'))
                                <div class="required-field-error">{{ $errors->first('last_name') }}</div>
                                @endif

			    </div>

				<div class="form-field-div width-hundred-percent center-element">

                                {{ Form::label('username', $username_i18n) }}

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

			    </div>

				<div class="form-field-div width-hundred-percent center-element">
			    
                                {{ Form::label('password_confirmation', $password_confirm_i18n) }}

                                {{ Form::input('password', 'password_confirmation', $password_confirmation, array('id'=>'password_confirmation', 'data-theme'=>$theme_buttons)) }}

                                @if ($errors->has('password_confirmation'))
                                <div class="required-field-error">{{ $errors->first('password_confirmation') }}</div>
                                @endif

				</div>
			</div>

			<div data-role="fieldcontain">

				<div id="recaptcha_widget" class="width-hundred-percent align-left">

					<!--<label>{{ $recaptcha_challenge_i18n }}</label>-->
					<div class="recaptcha-challenge-image-div align-center ui-corner-all ui-shadow-inset">
						<div id="recaptcha_image"></div>
					</div>

					<span class="recaptcha_only_if_image">
						{{ $enter_the_two_words_above_i18n }}:
					</span>
					<span class="recaptcha_only_if_audio">
						{{ $enter_the_numbers_you_hear_i18n }}:
					</span>

                                	{{ Form::input('text', 'recaptcha_response_field', Input::get('recaptcha_response_field'), array('id'=>'recaptcha_response_field', 'data-theme'=>$theme_buttons, 'class'=>'form-field-div center-element')) }}

                                	@if ($errors->has('recaptcha_response_field'))
                                	<div class="required-field-error">{{ $errors->first('recaptcha_response_field') }}</div>
                                	@endif
					
					<table class="width-hundred-percent">
						<tr>
							<td class="width-fifty-percent height-fifty-pixels align-center">
								<div>
									{{ Form::button($refresh_captcha_i18n, array('id'=>'refresh_captcha', 'data-theme'=>$theme_buttons)) }}
								</div>
							</td>
							<td class="width-fifty-percent height-fifty-pixels align-center">
								<div class="recaptcha_only_if_image">

									{{ Form::button($switch_type_audio_i18n, array('id'=>'switch_type_audio', 'data-theme'=>$theme_buttons)) }}		
								</div>
								<div class="recaptcha_only_if_audio">
	
									{{ Form::button($switch_type_image_i18n, array('id'=>'switch_type_image', 'data-theme'=>$theme_buttons)) }}
								</div>
							</td>
						</tr>
					</table>
				</div>

				<script type="text/javascript" src="https://www.google.com/recaptcha/api/challenge?k={{ $recaptcha_public_key }}"></script>

				<noscript>
					<iframe src="https://www.google.com/recaptcha/api/noscript?k={{ $recaptcha_public_key }}" height="300" width="500" frameborder="0"></iframe>
					<br>
					<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
					<input type="hidden" name="recaptcha_response_field" value="manual_challenge">
				</noscript>

			</div>	
			
			<div data-role="fieldcontain">	

        	        	{{ Form::submit($submit_i18n, array('id'=>'register_submit', 'name'=>'register_submit', 'type'=>'submit', 'data-theme'=>$theme_action, 'aria-disabled'=>'false')) }}

                		{{ HTML::link('', $cancel_i18n, array('data-role'=>'button', 'data-theme'=>$theme_buttons, 'data-transition'=>$default_dialog_transition, 'data-direction'=>'reverse', 'data-rel'=>'back', 'title'=>$cancel_i18n)) }}

			</div>
		</form>
	</div>

</div>

@stop
