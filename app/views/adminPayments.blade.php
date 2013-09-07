@extends('layouts.master')

@section('content')
                
<div data-role="dialog" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}" id="admin_payments" data-transition="{{ $default_page_transition }}"> 
	
	{{-- @include('partials.header') --}}

	<div data-role="content" class="align-center" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">

                @if( $saved_successfully )

                        @include('partials.success')

                @endif                

	       {{ Form::open(array('url'=>'/admin/payments', 'id'=>'admin_payments_form', 'method'=>'POST', 'data-ajax'=>'true', 'data-transition'=>'none', 'data-history'=>'false')) }}

                        {{ Form::input('hidden', 'csrf_token', csrf_token()) }}

                        {{ Form::input('hidden', 'user_id', $user_id, array('id'=>'user_id')) }}

                        <div data-role="fieldcontain" class="width-hundred-percent align-left">

                                <div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('paypal_sandbox_mode', $paypal_sandbox_mode_i18n) }}

                                        {{-- Form :: input ( type, name, value, other attributes ) --}}
                                        {{ Form::input('checkbox', 'paypal_sandbox_mode', $paypal_sandbox_mode, array_merge(array('id'=>'paypal_sandbox_mode', 'data-theme'=>$theme_buttons), $sandbox_enabled_input_options)) }}

                                </div>

                                <div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('sandbox_master_account', $sandbox_master_account_i18n) }}

                                        {{ Form::input('text', 'sandbox_master_account', $sandbox_master_account, array('id'=>'sandbox_master_account', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('sandbox_master_account'))
                                        <div class="required-field-error">{{ $errors->first('sandbox_master_account') }}</div>
                                        @endif
                                </div>

                                <div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('sandbox_api_username', $sandbox_api_username_i18n) }}

                                        {{ Form::input('text', 'sandbox_api_username', $sandbox_api_username, array('id'=>'sandbox_api_username', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('sandbox_api_username'))
                                        <div class="required-field-error">{{ $errors->first('sandbox_api_username') }}</div>
                                        @endif
                                </div>

                                <div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('sandbox_api_password', $sandbox_api_password_i18n) }}

                                        {{ Form::input('password', 'sandbox_api_password', $sandbox_api_password, array('id'=>'sandbox_api_password', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('sandbox_api_password'))
                                        <div class="required-field-error">{{ $errors->first('sandbox_api_password') }}</div>
                                        @endif
                                </div>

                                <div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('sandbox_api_signature', $sandbox_api_signature_i18n) }}

                                        {{ Form::input('text', 'sandbox_api_signature', $sandbox_api_signature, array('id'=>'sandbox_api_signature', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('sandbox_api_signature'))
                                        <div class="required-field-error">{{ $errors->first('sandbox_api_signature') }}</div>
                                        @endif
                                </div>

                                <div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('paypal_master_account', $paypal_master_account_i18n) }}

                                        {{ Form::input('text', 'paypal_master_account', $paypal_master_account, array('id'=>'paypal_master_account', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('paypal_master_account'))
                                        <div class="required-field-error">{{ $errors->first('paypal_master_account') }}</div>
                                        @endif

                                </div>

                                <div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('paypal_api_username', $paypal_api_username_i18n) }}

                                        {{ Form::input('text', 'paypal_api_username', $paypal_api_username, array('id'=>'paypal_api_username', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('paypal_api_username'))
                                        <div class="required-field-error">{{ $errors->first('paypal_api_username') }}</div>
                                        @endif
                                </div>

                                <div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('paypal_api_password', $paypal_api_password_i18n) }}

                                        {{ Form::input('password', 'paypal_api_password', $paypal_api_password, array('id'=>'paypal_api_password', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('paypal_api_password'))
                                        <div class="required-field-error">{{ $errors->first('paypal_api_password') }}</div>
                                        @endif
                                </div>


                                <div class="form-field-div width-hundred-percent align-left">

                                        {{ Form::label('paypal_api_signature', $paypal_api_signature_i18n) }}

                                        {{ Form::input('text', 'paypal_api_signature', $paypal_api_signature, array('id'=>'paypal_api_signature', 'data-theme'=>$theme_buttons)) }}

                                        @if ($errors->has('paypal_api_signature'))
                                        <div class="required-field-error">{{ $errors->first('paypal_api_signature') }}</div>
                                        @endif
                                </div>
                        </div>

                        <div data-role="fieldcontain">

                                {{ Form::submit($save_i18n, array('type'=>'submit', 'data-theme'=>$theme_action, 'aria-disabled'=>'false')) }}

                                {{ HTML::link('/', $cancel_i18n, array('data-role'=>'button', 'data-theme'=>$theme_buttons, 'data-transition'=>$default_dialog_transition, 'data-direction'=>'reverse', 'data-rel'=>'back', 'title'=>$cancel_i18n)) }}

                        </div>
                </form>
        </div>		

	{{-- @include('partials.footer') --}}
</div>

@stop
