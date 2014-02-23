<?php 
$class = '';
$accept == 1 and $class = 'accept';
$canceled == 1 and $class = 'canceled';
?>
<?php print Form::open(Uri::current()); ?>
<div class="col-xs-1 backbutton">
    <label>
        <?php print Form::submit('back',__('types.15.back'),array('class'=>'hide')); ?>
        <img src="<?php print Uri::create('assets/img/icons/arrow_left.png') ?>" alt=""/>
    </label>
</div>
<?php print Form::close(); ?>
<div class="col-xs-11 vertical graycontainer globalmenu">
    <div class="description">

    </div>
    <div class="list padding15">
        <div class="shop-order-form-group">
            <?php if(!$canceled && !$accept): ?>
                <a class="button inline" href="<?php print Uri::create('admin/shop/orders/accept/' . $order_id) ?>"><?php print __('shop.orders.accept'); ?></a>
                <a class="button inline" href="<?php print Uri::create('admin/shop/orders/cancel/' . $order_id) ?>"><?php print __('shop.orders.cancel'); ?></a>
            <?php else: ?>
                <div class="button inline disabled"><?php print __('shop.orders.accept'); ?></div>
                <div class="button inline disabled"><?php print __('shop.orders.cancel'); ?></div>
            <?php endif; ?>
            <a class="button inline" target="_blank" href="<?php print Uri::create('admin/shop/orders/display/invoice/' . $order_id) ?>"><?php print __('shop.orders.mail'); ?></a>
        </div>
        <br />
        <h3><?php print __('shop.orders.delivery_address')  ?></h3>
        <table class="shop-order-table col-xs-12">
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
            <table class="<?php print $class; ?> col-xs-12">
                <tr class="shop-cart-bottom-line">
                    <th class="padding15"><?php print __('shop.cart.amount') ?></th>
                    <th class="padding15"><?php print __('shop.cart.article') ?></th>
                    <th class="padding15"><?php print __('shop.cart.unit_price') ?></th>
                    <th class="padding15"><?php print __('shop.cart.total_price') ?></th>
                </tr>
                <?php foreach ($cart as $article): ?>
                    <tr>
                        <td class="padding15"><?php print $article['amount'] ?></td>
                        <td class="padding15"><?php print $article['text']; ?></td>
                        <td class="padding15"><?php print $article['unit_price']; ?></td>
                        <td class="padding15"><?php print $article['total_price']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="shop-cart-top-line">
                    <td class="padding15" colspan="3" class="shop-cart-right"><?php print __('shop.cart.netto') ?></td>
                    <td class="padding15"><?php print $summary_prices['netto'] ?></td>
                </tr>
                <tr>
                    <td class="padding15" colspan="3" class="shop-cart-right"><?php print __('shop.cart.shipping_cost') ?></td>
                    <td class="padding15"><?php print $summary_prices['shipping_cost'] ?></td>
                </tr>
                <tr>
                    <td class="padding15" colspan="3" class="shop-cart-right"><?php print __('shop.cart.tax') ?></td>
                    <td class="padding15"><?php print $summary_prices['tax'] ?></td>
                </tr>
                <tr>
                    <td class="padding15" colspan="3" class="shop-cart-right"><?php print __('shop.cart.summary') ?></td>
                    <td class="padding15"><?php print $summary_prices['summary'] ?></td>
                </tr>
            </table>
        </div>
        <br/><br/>
    </div>
</div>
<style type="text/css">
	.accept {
		background: #B1FFA3;
	}
	.canceled {
		background: #F07F7F;
	}
</style>