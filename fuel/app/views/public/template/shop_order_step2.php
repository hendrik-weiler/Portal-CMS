<div class="shop-order-form shop-order-form-step2 row">
	<?php print Form::open(Uri::current()); ?>
	<div class="shop-order-form-steps-overview row">
		<div class="shop-order-form-steps-overview-step1 span4">
			<?php print __('shop.order.steps.step1') ?>
		</div>
		<div class="shop-order-form-steps-overview-step2 span4 active">
			<?php print __('shop.order.steps.step2') ?>
		</div>
		<div class="shop-order-form-steps-overview-step3 span4">
			<?php print __('shop.order.steps.step3') ?>
		</div>
	</div>
	<div class="shop-order-form-col1 span6">

		<table class="shop-order-table">
			<tr>
				<td><?php print Form::label(__('shop.order.company')) ?></td>
				<td><?php print $company ?></td>
			</tr>
			<tr>
				<td><?php print Form::label(__('shop.order.email')) ?></td>
				<td><?php print $email ?></td>
			</tr>
			<tr>
				<td><?php print Form::label(__('shop.order.first_name')) ?></td>
				<td><?php print $first_name ?></td>
			</tr>
			<tr>
				<td><?php print Form::label(__('shop.order.last_name')) ?></td>
				<td><?php print $last_name ?></td>
			</tr>
			<tr>
				<td><?php print Form::label(__('shop.order.street')) ?></td>
				<td><?php print $street ?></td>
			</tr>
			<tr>
				<td><?php print Form::label(__('shop.order.zip_code')) ?></td>
				<td><?php print $zip_code ?></td>
			</tr>
			<tr>
				<td><?php print Form::label(__('shop.order.location')) ?></td>
				<td><?php print $location ?></td>
			</tr>
			<tr>
				<td><?php print Form::label(__('shop.order.country')) ?></td>
				<td><?php print model_shop_countries::$countries[$country] ?></td>
			</tr>
			<tr>
				<td><?php print Form::label(__('shop.order.payment_method')) ?></td>
				<td><?php print __('shop.order.' . $payment_method) ?></td>
			</tr>
		</table>

		<div class="shop-order-cart">
			<?php print model_shop_cart::render(true) ?>
		</div>

		<div class="shop-order-form-group">
			<?php print Form::submit('back',__('shop.order.back')) ?>
			<?php print Form::submit('confirm',__('shop.order.confirm')) ?>
		</div>
	</div>
		<?php print Form::close(); ?>
</div>