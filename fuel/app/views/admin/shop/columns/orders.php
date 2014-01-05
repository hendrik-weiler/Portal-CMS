<table class="order-table">
	<tr>
		<th><?php print __('shop.orders.order_id'); ?></th>
		<th><?php print __('shop.orders.order_person'); ?></th>
		<th><?php print __('shop.orders.order_price'); ?></th>
		<th><?php print __('shop.orders.order_optionen'); ?></th>
	</tr>
	<?php foreach ($orders as $order): ?>
		<?php 
			$address = $order->get_delivery_address(); 
			$summary_prices = $order->get_summary_prices();

			$class = '';
			$order->accept == 1 and $class = 'accept';
			$order->canceled == 1 and $class = 'canceled';
		?>
		<tr class="<?php print $class ?>">
			<td><?php print $order->get_order_nr(); ?></td>
			<td><?php print $address['last_name'] . ' ' . $address['first_name'] ?></td>
			<td><?php print $summary_prices['summary'] ?></td>
			<td>
				<a class="btn primary" href="<?php print Uri::create('admin/shop/orders/display/' . $order->id) ?>"><?php print __('shop.orders.display'); ?></a> 
				
			</td>
		</tr>
	<?php endforeach; ?>
</table>
<style type="text/css">
	.accept {
		background: #B1FFA3;
	}
	.canceled {
		background: #F07F7F;
	}
</style>