@extends('layouts.anonymous')

@section('content')

<div id="queue" data-role="page" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">
	
        @include('partials.header')

	<div data-role="content" class="align-center" data-theme="{{ $theme_body }}" data-divider-theme="{{ $theme_bars }}">
		<div>
			<ul data-role="listview" data-inset="true" data-divider-theme="{{ $theme_bars }}" data-theme="{{ $theme_buttons }}"> 
				<li data-role="list-divider">{{ $registration_complete_i18n }}</li>
				<li>
					<table class="width-hundred-percent align-left">
						<tr>
							<td class="width-hundred-percent align-left">
								
								<table class="width-hundred-percent">
									<tr>
										<td rowspan="3" class="success-image-cell align-left">
											<div id="success_image_div" class="success-image-div">
												<img src="/images/success.png" class="success-image" alt="Success" class="currentalbumart"/>
											</div>
										</td>
										<td class="align-left">
											<div class="success-description-div">{{ $success_message_first_half_i18n }} {{ $new_users_email }} {{ $success_message_second_half_i18n }}</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</li>
				<li data-role="list-divider"></li>
			</ul>
		</div>

		<!--<form action="samples/checkout.php" method="POST" data-ajax="false">
			<input type="hidden" name="cmd" value="_s-xclick" />
			<input type="hidden" name="hosted_button_id" value="LTCCBCN7MG47C" />
			<table class="width-hundred-percent padding-left-twenty-pixels">
				<tr>
					<td class="align-left padding-bottom-ten-pixels">
						<input type="hidden" name="on0" value="Account Levels" />Account Levels
					</td>
				</tr>
				<tr>
					<td sclass="align-right padding-bottom-ten-pixels">
						<select name="os0">
							<option value="MUSOTIC50GB">50 GB Limit : $10.00/month</option>
							<option value="MUSOTIC100GB">100 GB Limit : $20.00/month</option>
							<option value="MUSOTIC200GB">200 GB Limit : $30.00/month</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="align-right">
						<input type="hidden" name="currency_code" value="USD" />
						<input data-role="none" type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribe_LG.gif" border="0" name="paypal_submit" id='paypal_submit' alt="PayPal - The safer, easier way to pay online!" />
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</td>
				</tr>
			</table>
		</form>-->
	</div>
	
        @include('partials.footer')

</div>

@stop
