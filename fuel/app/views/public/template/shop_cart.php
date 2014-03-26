<div class="shop-cart">
	<?php print Form::open(Uri::current()); ?>
	<table>
		<tr class="shop-cart-bottom-line">
			<th><?php print __('shop.cart.amount') ?></th>
			<th><?php print __('shop.cart.article') ?></th>
			<th><?php print __('shop.cart.unit_price') ?></th>
			<th><?php print __('shop.cart.total_price') ?></th>
			<th></th>
		</tr> 
		<?php foreach ($cart_content as $key => $article): ?>
			<tr>
				<?php if(!$supress_interaction): ?>
				<td><?php print Form::input('amount_' . $key,$article['amount'], array('class'=>'shop-cart-amount-input')) ?></td>
				<?php else: ?>
					<td><?php print $article['amount'] ?></td>
				<?php endif; ?>
				<td><?php print $article['article']->get_label(); ?></td>
				<td><?php print $article['article']->get_price(); ?></td>
				<td><?php print $article['article']->get_price_by_amount($article['amount']); ?></td>
				
				<td>
					<?php if(!$supress_interaction): ?>
					<?php print $article['article']->remove_from_cart_anchor($key); ?>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php if(count($cart_content) == 0): ?>
			<tr>
				<td colspan="5"><?php print __('shop.cart.no_cart_content') ?></td>
			</tr>
		<?php endif; ?>
		<?php if(!$shipping_costs_reached): ?>
			<tr>
				<td class="shop-cart-costs-reached" colspan="5"><?php print __('shop.cart.shipping_costs_reached') ?></td>
			</tr>
		<?php endif; ?>
		<tr class="shop-cart-top-line">
			<td colspan="4" class="shop-cart-right"><?php print __('shop.cart.netto') ?></td>
			<td><?php print $netto ?></td>
		</tr>
		<tr>
			<td colspan="4" class="shop-cart-right"><?php print __('shop.cart.shipping_cost') ?></td>
			<td><?php print $shipping_cost ?></td>
		</tr>
		<tr>
			<td colspan="4" class="shop-cart-right"><?php print __('shop.cart.tax') ?></td>
			<td><?php print $tax ?></td>
		</tr>
		<tr>
			<td colspan="4" class="shop-cart-right"><?php print __('shop.cart.summary') ?></td>
			<td><?php print $summary ?></td>
		</tr>
	</table>
	<?php if(!$supress_interaction): ?>
	<div class="shop-cart-action">
		<?php print Form::submit('shop_modify', __('shop.cart.modify'),array('class'=>'shop-cart-button-modify')) ?> 
		<?php if($shipping_costs_reached): ?>
		<?php print Form::submit('shop_order', __('shop.cart.order'),array('class'=>'shop-cart-button-order')) ?>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<?php print Form::close(); ?>
</div>