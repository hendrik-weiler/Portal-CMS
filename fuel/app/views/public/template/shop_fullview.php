<div class="shop-fullview">
	<div class="shop-fullview-article-title"><?php print $article->get_label() ?></div>
	<div class="shop-fullview-article-body">
		<div class="shop-fullview-article-pictures">
			<img src="<?php print $article->get_main_image('medium'); ?>">
		</div>
		<div class="shop-fullview-article-description">
			<?php print $article->get_description() ?>
		</div>
		<div class="shop-fullview-article-list-article-preis">
			<?php print $article->get_price() ?><br >
			<!-- Netto: <?php print $article->get_price($article->get_netto_price()) ?> -->
		</div>
		<?php if($article->sold_out): ?>
			<div class="shop-fullview-sold-out"><?php print __('shop.fullview.sold_out'); ?></div>
		<?php else: ?>
		<div class="shop-fullview-article-list-article-preis">
			<?php print Form::open(Uri::current()); ?>
			<?php print Form::input('shop_amount', 1, array('class'=>'shop_amount')) ?>
			<?php print Form::submit('shop_add_article',__('shop.fullview.add_to_cart'), array('class'=>'shop_add_article')) ?>
			<?php print Form::close(); 
			?>
		</div>
		<?php endif; ?>
	</div>
</div>