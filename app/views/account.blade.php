@extends('layouts.master')

@section('content')

<div data-role="dialog" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}" id="account"> 
	
	{{-- @include('partials.header') --}}

	<div data-role="content" class="align-center" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">
		
                @if( $saved_successfully )

                        @include('partials.success')

                @endif

		{{ Form::open(array('url'=>'/admin/account', 'method'=>'POST', 'data-ajax'=>'true', 'data-transition'=>'none', 'data-history'=>'false')) }}

                <?php echo Form::input('hidden', 'csrf_token', csrf_token()); ?>

			<div data-role="fieldcontain" class="align-left">

                                <div class="form-field-div width-hundred-percent center-element">

                                	<?php echo Form::label('first_name', $first_name_i18n); ?>

                                	{{-- Form :: input ( type, name, value, other attributes ) --}}
                                	<?php echo Form::input('text', 'first_name', $first_name, array('id'=>'first_name', 'data-theme'=>$theme_buttons)); ?>

                                	@if ($errors->has('first_name'))
                                	<div class="required-field-error"><?php echo $errors->first('first_name'); ?></div>
                                	@endif

                            	</div>

                                <div class="form-field-div width-hundred-percent center-element">

                                	<?php echo Form::label('last_name', $last_name_i18n); ?>

					{{-- Form :: input ( type, name, value, other attributes ) --}}
                                	<?php echo Form::input('text', 'last_name', $last_name, array('id'=>'last_name', 'data-theme'=>$theme_buttons)); ?>

                                	@if ($errors->has('last_name'))
                                	<div class="required-field-error"><?php echo $errors->first('last_name'); ?></div>
                                	@endif

                            	</div>

                                <div class="form-field-div width-hundred-percent center-element">

                                	<?php echo Form::label('username', $username_i18n); ?>

                                	{{-- Form :: input ( type, name, value, other attributes ) --}}
                                	<?php echo Form::input('text', 'username', $username, array('id'=>'username', 'data-theme'=>$theme_buttons)); ?>

                                	@if ($errors->has('username'))
                                	<div class="required-field-error"><?php echo $errors->first('username'); ?></div>
                                	@endif

				</div>

                                <div class="form-field-div width-hundred-percent center-element">

                                	<?php echo Form::label('password', $password_i18n); ?>

                                	<?php echo Form::input('password', 'password', $password, array('id'=>'password', 'data-theme'=>$theme_buttons)); ?>

                                	@if ($errors->has('password'))
                                	<div class="required-field-error"><?php echo $errors->first('password'); ?></div>
                                	@endif

				</div>

                                <div class="form-field-div width-hundred-percent center-element">

                                	<?php echo Form::label('password_confirmation', $password_confirm_i18n); ?>

                                	<?php echo Form::input('password', 'password_confirmation', $password_confirmation, array('id'=>'password_confirmation', 'data-theme'=>$theme_buttons)); ?>

                                	@if ($errors->has('password_confirmation'))
                                	<div class="required-field-error"><?php echo $errors->first('password_confirmation'); ?></div>
                                	@endif

                                </div>

			</div>

			<div data-role="fieldcontain">

                                <?php echo Form::submit($save_i18n, array('type'=>'submit', 'data-theme'=>$theme_action, 'aria-disabled'=>'false')); ?>

                                <?php echo HTML::link('', $cancel_i18n, array('data-role'=>'button', 'data-theme'=>$theme_buttons, 'data-transition'=>$default_dialog_transition, 'data-ajax'=>'true', 'data-direction'=>'reverse', 'data-rel'=>'back', 'title'=>$cancel_i18n)); ?>

			</div>
		</form>
	</div>

	{{-- @include('partials.footer') --}}
</div>

@stop
