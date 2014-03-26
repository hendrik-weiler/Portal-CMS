<?php print Form::open(array('action'=>'admin/shop/settings/edit','enctype'=>'multipart/form-data')); ?>
<div class="col-xs-12 vertical graycontainer globalmenu">
<div class="description">
    <?php print __('nav.settings') ?>
</div>
<div class="list padding15">

	<div class="col-xs-6">
	<div>
		<h3><?php print __('shop.settings.invoice_logo'); ?></h3>
		<?php if(!empty($invoice_logo)): ?>
		<img src="<?php print Uri::create('uploads/shop/logo/' . $invoice_logo) ?>"><br	/>
		<?php endif; ?>
		<?php print Form::file('invoice_logo') ?>
	</div>
	<div>
		<h3><?php print __('shop.settings.order_format'); ?></h3>
		<div class="input-field">
			<?php print Form::input('order_format', $order_format, array('class'=>'large')); ?> 
		</div>
	</div>
	<div>
			<h3><?php print __('shop.settings.exchange_rates'); ?></h3>
			<?php foreach ($rates as $prefix => $value): ?>
			<div class="input-field">
				<?php print Form::label($prefix); ?> 
				<?php print Form::input('rates_' . $prefix, $value, array('class'=>'mini')); ?> : 1.00
			</div>
			<?php endforeach; ?>
	</div>
	<div>
			<h3><?php print __('shop.settings.payment_methods'); ?></h3>
			<div class="input-field">
				<?php 
				$checked = array();
				$payment_method_advance_payment == '1' and $checked = array('checked','checked');
				print Form::checkbox('payment_method_advance_payment',1, $checked);
				?>
				<?php print Form::label(__('shop.settings.payment_method_advance_payment')); ?> 
			</div>
			<div class="input-field">
				<?php 
				$checked = array();
				$payment_method_invoice_payment == '1' and $checked = array('checked','checked');
				print Form::checkbox('payment_method_invoice_payment',1, $checked);
				?>
				<?php print Form::label(__('shop.settings.payment_method_invoice_payment')); ?> 
			</div>
	</div>
	<div>
			<h3><?php print __('shop.settings.shipping_costs'); $counter = 0; ?></h3>
			<div class="shipping_fields">
				<?php foreach ($shipping_costs as $key => $value): ?>
				<div class="input-field">
					<?php print __('shop.settings.shipping_costs_with'); ?> 
					<?php print Form::input('cost_summary_' . $counter, number_format($key, 2, ',', ''), array('class'=>'mini')); ?>
					<?php print __('shop.settings.shipping_costs_costs_of'); ?> 
					<?php print Form::input('cost_value_' . $counter, number_format($value, 2, ',', ''), array('class'=>'mini')); ?>
					<?php if($counter >= 1): ?>
					<div data-index="<?php print $counter ?>" class="button remove_shipping error"><?php print __('shop.settings.remove_shipping'); ?></div>
					<?php endif; ?>
					<?php $counter++; ?>
				</div>
				<?php endforeach; ?>
			</div>
			<div class="button success add_shipping"><?php print __('shop.settings.add_shipping'); ?></div>
	</div>

</div>

<div class="col-xs-6">
	<h3><?php print __('shop.settings.invoice_data'); ?></h3>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.company')); ?>
		<?php print Form::input('company', $company, array('class'=>'large')); ?> 
	</div>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.first_name')); ?>
		<?php print Form::input('first_name', $first_name, array('class'=>'large')); ?> 
	</div>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.last_name')); ?>
		<?php print Form::input('last_name', $last_name, array('class'=>'large')); ?> 
	</div>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.street')); ?>
		<?php print Form::input('street', $street, array('class'=>'large')); ?> 
	</div>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.zip_code')); ?>
		<?php print Form::input('zip_code', $zip_code, array('class'=>'large')); ?> 
	</div>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.location')); ?>
		<?php print Form::input('location', $location, array('class'=>'large')); ?> 
	</div>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.phone')); ?>
		<?php print Form::input('phone', $phone, array('class'=>'large')); ?> 
	</div>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.fax')); ?>
		<?php print Form::input('fax', $fax, array('class'=>'large')); ?> 
	</div>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.country')); ?>
		<?php print Form::select('country', $country, $country_list,array('class'=>'large')); ?> 
	</div>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.email')); ?>
		<?php print Form::input('email', $email, array('class'=>'large')); ?> 
	</div>

	<hr />

	<div class="input-field">
		<?php print Form::label(__('shop.settings.ust_id')); ?>
		<?php print Form::input('ust_id', $ust_id, array('class'=>'large')); ?> 
	</div>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.tax_id')); ?>
		<?php print Form::input('tax_id', $tax_id, array('class'=>'large')); ?> 
	</div>

	<hr />

	<div class="input-field">
		<?php print Form::label(__('shop.settings.local_court')); ?>
		<?php print Form::input('local_court', $local_court, array('class'=>'large')); ?> 
	</div>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.commercial_register_number')); ?>
		<?php print Form::input('commercial_register_number', $commercial_register_number, array('class'=>'large')); ?> 
	</div>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.manager')); ?>
		<?php print Form::input('manager', $manager, array('class'=>'large')); ?> 
	</div>

	<hr />

	<div class="input-field">
		<?php print Form::label(__('shop.settings.bank_name')); ?>
		<?php print Form::input('bank_name', $bank_name, array('class'=>'large')); ?> 
	</div>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.account_number')); ?>
		<?php print Form::input('account_number', $account_number, array('class'=>'large')); ?> 
	</div>

	<div class="input-field">
		<?php print Form::label(__('shop.settings.bank_sort_code')); ?>
		<?php print Form::input('bank_sort_code', $bank_sort_code, array('class'=>'large')); ?> 
	</div>

</div>
	<div class="col-xs-12">
	    <br/>
	    <?php print Form::submit('edit_settings',__('shop.articles.save'), array('class'=>'button')) ?>
    </div>
</div>


	<?php print Form::close(); ?>
</div>




<script type="text/javascript">
	var next = -1;

	$('.remove_shipping').click(function() {
		var index = $(this).attr('data-index');
		$('.shipping_fields .input-field').eq(index).remove();
	});

	$('.add_shipping').click(function() {
		var clone = $('.shipping_fields .input-field').eq(0).clone();

		var new_id = $('.shipping_fields .input-field').length;

		clone.find('input').eq(0)
		.attr('name','cost_summary_' + new_id)
		.attr('id','form_cost_summary_' + new_id)
		.val(next);
		--next;
		clone.find('input').eq(1)
		.attr('name','cost_value_' + new_id)
		.attr('id','form_cost_value_' + new_id);

		$('.shipping_fields').append(clone);
	});
</script>
<style type="text/css">
.input-field {
	padding: 5px;
	clear: left;
}

.input-field label {
	padding-right: 5px;
}

.action {
	margin: 5px;
	margin-top: 20px;
}

.article-images ul {
	list-style: none;
	padding: 0;
	margin: 0;
}
</style>