<?php 
$class = '';
$accept == 1 and $class = 'accept';
$canceled == 1 and $class = 'canceled';
?>
<div class="shop-order-form-group">
	<a class="btn primary" href="<?php print Uri::create('admin/shop/orders') ?>"><?php print __('shop.order.back') ?></a> 
	<?php if(!$canceled && !$accept): ?>
	<a class="btn success large" href="<?php print Uri::create('admin/shop/orders/accept/' . $order_id) ?>"><?php print __('shop.orders.accept'); ?></a> 
	<a class="btn error large" href="<?php print Uri::create('admin/shop/orders/cancel/' . $order_id) ?>"><?php print __('shop.orders.cancel'); ?></a>
	<?php else: ?>
	<div class="btn success disabled large"><?php print __('shop.orders.accept'); ?></div> 
	<div class="btn error disabled large"><?php print __('shop.orders.cancel'); ?></div>
	<?php endif; ?>
	<a class="btn large" target="_blank" href="<?php print Uri::create('admin/shop/orders/display/invoice/' . $order_id) ?>"><?php print __('shop.orders.mail'); ?></a>
</div>
<br />
<h3><?php print __('shop.orders.delivery_address')  ?></h3>
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
		<td><?php print Form::label(__('shop.order.phone')) ?></td>
		<td><?php print $phone ?></td>
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
<h3><?php print __('shop.orders.cart_content')  ?></h3>
<div class="shop-order-cart">
<table class="<?php print $class; ?>">
		<tr class="shop-cart-bottom-line">
			<th><?php print __('shop.cart.amount') ?></th>
			<th><?php print __('shop.cart.article') ?></th>
			<th><?php print __('shop.cart.unit_price') ?></th>
			<th><?php print __('shop.cart.total_price') ?></th>
		</tr> 
		<?php foreach ($cart as $article): ?>
			<tr>
				<td><?php print $article['amount'] ?></td>
				<td><?php print $article['text']; ?></td>
				<td><?php print $article['unit_price']; ?></td>
				<td><?php print $article['total_price']; ?></td>
			</tr>
		<?php endforeach; ?>
		<tr class="shop-cart-top-line">
			<td colspan="3" class="shop-cart-right"><?php print __('shop.cart.netto') ?></td>
			<td><?php print $summary_prices['netto'] ?></td>
		</tr>
		<tr>
			<td colspan="3" class="shop-cart-right"><?php print __('shop.cart.shipping_cost') ?></td>
			<td><?php print $summary_prices['shipping_cost'] ?></td>
		</tr>
		<tr>
			<td colspan="3" class="shop-cart-right"><?php print __('shop.cart.tax') ?></td>
			<td><?php print $summary_prices['tax'] ?></td>
		</tr>
		<tr>
			<td colspan="3" class="shop-cart-right"><?php print __('shop.cart.summary') ?></td>
			<td><?php print $summary_prices['summary'] ?></td>
		</tr>
	</table>
</div>
<style type="text/css">
	.accept {
		background: #B1FFA3;
	}
	.canceled {
		background: #F07F7F;
	}
</style>