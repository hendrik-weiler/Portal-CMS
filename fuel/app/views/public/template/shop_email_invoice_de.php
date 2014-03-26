<div style="font-family: Trebuchet MS;">
<table style="width:100%">
	<tr>
		<td>
			<br><br><br>
			<span style="font-size:10px"><?php print $setting_company ?> &bull; <?php print $setting_street ?> &bull; <?php print $setting_zip_code . ' ' . $setting_location ?></span><br>
			<?php print $company ?><br />
			<?php print $first_name . ' ' . $last_name ?><br />
			<?php print $street ?><br />
			<?php print $zip_code . ' ' . $location ?><br />
			<?php print model_shop_countries::$countries[$country] ?><br />
		</td>
		<td>
			<img src="<?php print $image; ?>"><br>
			<p>
				Ansprechpartner<br>
				<?php print $first_name . ' ' . $last_name ?><br />
				<?php print $setting_phone ?>
			</p>
			<p>
				<?php print $setting_location . ', ' . date('d.m.Y') ?>
			</p>
		</td>
	</tr>
</table>

<strong>Rechnung <?php print $order->get_order_nr(); ?></strong>

<div class="shop-order-cart">
<table style="width:100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<th style="border-bottom:2px solid black"><?php print __('shop.cart.amount') ?></th>
			<th style="border-bottom:2px solid black"><?php print __('shop.cart.article') ?></th>
			<th style="border-bottom:2px solid black"><?php print __('shop.cart.unit_price') ?></th>
			<th style="border-bottom:2px solid black"><?php print __('shop.cart.total_price') ?></th>
		</tr> 
		<?php foreach ($cart as $article): ?>
			<tr>
				<td style="text-align:center;"><?php print $article['amount'] ?></td>
				<td style="text-align:center;"><?php print $article['text']; ?></td>
				<td style="text-align:center;"><?php print $article['unit_price']; ?></td>
				<td style="text-align:center;"><?php print $article['total_price']; ?></td>
			</tr>
		<?php endforeach; ?>
		<tr>
			<td style="border-top:1px solid black;text-align:right;" colspan="3"><?php print __('shop.cart.netto') ?></td>
			<td style="border-top:1px solid black;text-align:center;"><?php print $summary_prices['netto'] ?></td>
		</tr>
		<tr>
			<td colspan="3" style="text-align:right;"><?php print __('shop.cart.shipping_cost') ?></td>
			<td style="text-align:center;"><?php print $summary_prices['shipping_cost'] ?></td>
		</tr>
		<tr>
			<td colspan="3" style="text-align:right;"><?php print __('shop.cart.tax') ?></td>
			<td style="text-align:center;"><?php print $summary_prices['tax'] ?></td>
		</tr>
		<tr>
			<td colspan="3" style="text-align:right;"><?php print __('shop.cart.summary') ?></td>
			<td style="text-align:center;"><?php print $summary_prices['summary'] ?></td>
		</tr>
	</table>
</div>

<p>Es gelten die gesetzlichen Bestimmungen des Kaufvertrags. Darüber hinaus gelten die Garantiebestimmungen der Hersteller.</p>

<p>Diese Rechnung wurde maschinell erstellt und ist auch ohne Unterschrift gültig.</p>

<div class="footer" style="clear:both;font-size:12px;border-top:1px solid black;">
	<div class="sp1" style="float:left;width:79%;">
		<?php print $setting_company ?><br>
		<?php print $setting_street ?><br>
		<?php print $setting_zip_code . ' ' . $setting_location ?><br>
		Tel.: <?php print $setting_phone ?><br>
		Fax.: <?php print $setting_fax ?>
	</div>
	<div class="sp2" style="float:left;width:19%;">
		Bankverbindung:<br>
		<?php print $setting_bank_name ?><br>
		Konto: <?php print $setting_account_number ?><br>
		BLZ: <?php print $setting_bank_sort_code ?><br>
		St.-Nr.: <?php print $setting_tax_id ?>
	</div>
</div>
</div>

<?php if(!$is_mail): ?>
	<style type="text/css">
	.footer {
		position: absolute;
		left: 0;
		bottom: 0;
		width: 100%;
	}

	.sp1,.sp2 {
		margin-top: 5px;
	}

	</style>
<?php endif; ?>