<div id="paypal" data-role="page" data-theme="<?php echo $theme_body; ?>" data-divider-theme="<?php echo $theme_bars; ?>">

	<?php $this->load->view('partials/paypal_header.php'); ?>

	<div data-role="content" class="align-center" data-theme="<?php echo $theme_body; ?>" data-divider-theme="<?php echo $theme_bars; ?>">

		<?php echo form_open($secure_checkout_url, array('id'=>'paypal_form', 'method'=>'post', 'data-ajax'=>'false')); ?>
		<!--<form action="<?php str_replace($base_protocol, $secure_protocol, site_url()).'paypal/checkout'; ?>" method="POST" data-ajax="false">-->
			
			<input type="hidden" name="cmd" value="_s-xclick" />
			<!--<input type="hidden" name="hosted_button_id" value="LTCCBCN7MG47C" />-->

			<div data-role="fieldcontain" class="width-hundred-percent align-left">

		    	<div class="form-field-div width-hundred-percent align-left">

		         	<input type="radio" name="subscription_account_levels" id="MUSOTIC50GB" value="MUSOTIC50GB" checked="checked" />
		         	<label for="MUSOTIC50GB">$3.99/month for 50 GB of storage</label>

		         	<input type="radio" name="subscription_account_levels" id="MUSOTIC100GB" value="MUSOTIC100GB"  />
		         	<label for="MUSOTIC100GB">$7.99/month for 100 GB of storage</label>

		         	<input type="radio" name="subscription_account_levels" id="MUSOTIC200GB" value="MUSOTIC200GB"  />
		         	<label for="MUSOTIC200GB">$14.99/month for 200 GB of storage</label>

		        </div>
			</div>

			<table class="width-hundred-percent padding-left-twenty-pixels">
				<tr>
					<td class="align-left">
					</td>
				</tr>
				<tr>
					<td class="align-right">
					</td>
				</tr>
				<tr>
					<td class="align-right">

						<input type="hidden" name="currency_code" value="USD" />

						<?php echo form_button(array('id'=>'paypal_submit', 'name'=>'paypal_submit', 'data-theme'=>$theme_action, 'type'=>'submit', 'aria-disabled'=>'false', 'content'=>$subscribe_i18n, 'alt'=>'PayPal - The safer, easier way to pay online!')); ?>

					</td>
				</tr>
			</table>
		</form>
	</div>

	<?php $this->load->view('partials/footer.php'); ?>

</div>