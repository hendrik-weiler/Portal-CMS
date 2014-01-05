<?php foreach ($category_names as $key => $name): ?>
	<div class="shop-category">
		<div class="shop-category-article-title"><?php print $name ?></div>
		<div class="shop-category-article-list">
			<ul>
			<?php foreach ($categories[$key] as $article): ?>
				<li>
					<div class="shop-category-article-list-article">
						<div class="shop-category-article-list-article-image">
							<img src="<?php print $article->get_main_image('medium'); ?>">
						</div>
						<div class="shop-category-article-list-article-title">
							<a href="<?php print $article->get_fullview_link() ?>"><?php print $article->get_label(model_generator_preparer::$lang) ?></a>
						</div>
						<div class="shop-category-article-list-article-preis">
							<?php print $article->get_price() ?><br >
							<!-- Netto: <?php print $article->get_price($article->get_netto_price()) ?> -->
						</div>
					</div>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
<?php endforeach; ?>
<script type="text/javascript" src="<?php print Uri::create('assets/js/shop/category_select.js') ?>"></script>