<div class="shop-order-form shop-order-form-step1 row">
	<?php print Form::open(Uri::current()); ?>
	<div class="shop-order-form-steps-overview row">
		<div class="shop-order-form-steps-overview-step1 span4 active">
			<?php print __('shop.order.steps.step1') ?>
		</div>
		<div class="shop-order-form-steps-overview-step2 span4">
			<?php print __('shop.order.steps.step2') ?>
		</div>
		<div class="shop-order-form-steps-overview-step3 span4">
			<?php print __('shop.order.steps.step3') ?>
		</div>
	</div>
	<div class="shop-order-form-col1 span6">
		<h5><?php print __('shop.order.delivery_address') ?></h5>

		<div class="shop-order-form-group">
			<?php print Form::label(__('shop.order.company')) ?>
			<?php print Form::input('company', $company, array('class'=>'shop-order-form-input')) ?>
		</div>

		<div class="shop-order-form-group">
			<?php print Form::label(__('shop.order.email')) ?>
			<?php print Form::input('email', $email, array('class'=>'shop-order-form-input')) ?>
		</div>

		<div class="shop-order-form-group">
			<?php $error_class = ''; $errors['first_name_error'] and $error_class = 'error'; ?>
			<?php print Form::label(__('shop.order.first_name')) ?>
			<?php print Form::input('first_name', $first_name, array('class'=>'shop-order-form-input ' . $error_class)) ?>
		</div>

		<div class="shop-order-form-group">
			<?php $error_class = ''; $errors['last_name_error'] and $error_class = 'error'; ?>
			<?php print Form::label(__('shop.order.last_name')) ?>
			<?php print Form::input('last_name', $last_name, array('class'=>'shop-order-form-input ' . $error_class)) ?>
		</div>

		<div class="shop-order-form-group">
			<?php $error_class = ''; $errors['street_error'] and $error_class = 'error'; ?>
			<?php print Form::label(__('shop.order.street')) ?>
			<?php print Form::input('street', $street, array('class'=>'shop-order-form-input ' . $error_class)) ?>
		</div>

		<div class="shop-order-form-group">
			<?php $error_class = ''; $errors['zip_code_error'] and $error_class = 'error'; ?>
			<?php print Form::label(__('shop.order.zip_code')) ?>
			<?php print Form::input('zip_code', $zip_code, array('class'=>'shop-order-form-input ' . $error_class)) ?>
		</div>

		<div class="shop-order-form-group">
			<?php $error_class = ''; $errors['location_error'] and $error_class = 'error'; ?>
			<?php print Form::label(__('shop.order.location')) ?>
			<?php print Form::input('location', $location, array('class'=>'shop-order-form-input ' . $error_class)) ?>
		</div>

		<div class="shop-order-form-group">
			<?php print Form::label(__('shop.order.phone')) ?>
			<?php print Form::input('phone', $phone, array('class'=>'shop-order-form-input ')) ?>
		</div>

		<div class="shop-order-form-group">
			<?php $error_class = ''; $errors['country_error'] and $error_class = 'error'; ?>
			<?php print Form::label(__('shop.order.country')) ?>
			<?php print Form::select('country', $country, $country_list, array('class'=>'shop-order-form-input ' . $error_class)) ?>
		</div>
	</div>
	<div class="shop-order-form-col2 span5">
		<h5><?php print __('shop.order.payment_method') ?></h5>

		<?php if(model_db_option::getKey('payment_method_advance_payment')->value == '1'): ?>
		<div class="shop-order-form-group shop-order-form-group-payment-advance">
			<?php $checked = array(); $payment_method == 'advance_payment' and $checked = array('checked'=>'checked'); ?>
			<?php print Form::radio('payment_method', 'advance_payment', array('class'=>'shop-order-form-input') + $checked) ?> 
			<?php print __('shop.order.advance_payment') ?>
		</div>
		<?php endif; ?>

	<?php if(model_db_option::getKey('payment_method_invoice_payment')->value == '1'): ?>
		<div class="shop-order-form-group shop-order-form-group-payment-invoice">
			<?php $checked = array(); $payment_method == 'invoice_payment' and $checked = array('checked'=>'checked'); ?>
			<?php print Form::radio('payment_method', 'invoice_payment', array('class'=>'shop-order-form-input') + $checked) ?> 
			<?php print __('shop.order.invoice_payment') ?>
		</div>
		<?php endif; ?>

		<?php if(model_db_option::getKey('payment_method_advance_payment')->value == '1'
						or model_db_option::getKey('payment_method_invoice_payment')->value == '1'): ?>
		<div class="shop-order-form-group">
			<?php print Form::submit('back',__('shop.order.back')) ?>
			<?php print Form::submit('next',__('shop.order.next')) ?>
		</div>
		<?php endif; ?>

	</div>
	<?php print Form::close(); ?>
</div>
<script type="text/javascript">
	var payment_selector = '.shop-order-form-group-payment-advance, .shop-order-form-group-payment-invoice';
	$(payment_selector).hover(function() {
		$(this).addClass('hover');
	}, function() {
		$(this).removeClass('hover');
	}).click(function() {
		$(payment_selector).find('input').removeAttr('checked');
		$(payment_selector).removeClass('active');
		$(this).addClass('active');
		$(this).find('input').attr('checked','checked');
	}).css({
		'cursor' : 'pointer'
	}).find('input').css('visibility','hidden');
	$(payment_selector).find('input:checked').trigger('click');
</script>