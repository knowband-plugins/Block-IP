{*
 * Paypal Donate
 * @author Knowband <support@knowband.com>
 * @copyright Copyright (c) 2018 Knowband.
 * @license GNU/LGPL version 3
 * @version 1.0.0
 * @link www.knowband.com
 *}

<!-- Donation Paypal -->

<section id='donationpaypal_block_left' class="donationpaypal block">
	<h4 class="title_block">{$title}</h4>
	<div class="block_content">
		
		<!-- Display information message -->
		{if !empty($display_message)}
		<div class="form-group">
			<p>{$display_message}</p>
		</div>
		{/if}
			
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">

			<!-- Identify your business so that you can collect the payments. -->
			<input type="hidden" name="business" value="{$business_id}">
			<input type="hidden" name="return" value="{$urls.base_url}"> <!-- OK optionnal -->
			<input type="hidden" name="rm" value="2" ><!-- OK optionnal -->
			<input type="hidden" name="cancel_return" value="{$urls.base_url}" ><!-- OK optionnal -->
			<input type="hidden" name="charset" value="UTF-8"><!-- OK optionnal -->

			<!--OK Specify a Donate button. -->
			<input type="hidden" name="cmd" value="_donations">
			
			{if isset($company_name)}
			<!--OK An identifier of the source Optionnel -->
			<input type="hidden" name="bn" value="{$company_name}_Donate_WPS_{$iso_code}">
			{/if}

			<!-- Specify details about the contribution -->
			<input type="hidden" name="currency_code" value="{$currency->iso_code}"> <!-- OK optionnal -->
			<input type="hidden" name="lc" value="{$iso_code}">
			<div class="form-group lbab-amount">
				<input type="text" name="amount" maxlength="16"><span>{$currency->sign}</span> <!-- OK optionnal -->
			</div>
			{if !empty($item_name)}
			<input type="hidden" name="item_name" value="{$item_name}"><!-- OK optionnal -->
			{/if}
			
			<!--  DESIGN -->
			<input type="hidden" name="page_style" value="{$page_style}"><!-- OK optionnal -->

			<!-- OTHER -->
			{*
				{if $no_note == 1}
				<input type="hidden" name="no_note" value={$no_note}><!-- OK optionnal -->
				{/if}
				<input type="hidden" name="cn" value="{$cn}"><!-- OK optionnal -->
			*}
			<input type="hidden" name="cbt" value="{$cbt}"><!-- OK optionnal -->
			
			{if $customer}
				<!-- USER -->
				<input type="hidden" name="email" value="{$customer->email|escape:'htmlall':'UTF-8'}"><!-- OK optionnal -->
				<input type="hidden" name="first_name" value="{$customer->firstname}"><!-- OK optionnal -->
				<input type="hidden" name="last_name" value="{$customer->lastname}"><!-- OK optionnal -->
				<input type="hidden" name="country" value="{$iso_code}"><!-- OK optionnal -->
				
				{if isset($address)}
					<input type="hidden" name="address1" value="{$address->address1}"><!-- OK optionnal -->
					<input type="hidden" name="city" value="{$address->city}"><!-- OK optionnal -->
					
					{if isset($address->address2)}
					<input type="hidden" name="address2" value="{$address->address2}"><!-- OK optionnal -->
					{/if}
					{if isset($address->postcode)}
					<input type="hidden" name="zip" value="{$address->postcode}"><!-- OK optionnal -->
					{/if}
				{/if}
			
			{/if}
			
			<!-- Display the payment button. -->
			<div class="form-group lbab-donation-btn">
			<input type="submit" name="submit" value="{l s='Donate Now' mod='donationpaypal'}">
			</div>
		</form>
		
	</div>
</section>
<!-- /Donation Paypal -->
