<script type="text/javascript" src="<?php print Uri::create('assets/js/tiny_mce/tiny_mce.js'); ?>"></script>
<?php print Form::open(array('action'=>Uri::current(),'enctype'=>'multipart/form-data')); ?>
<div class="row">
	<div class="span8">
		<h3><?php print __('shop.articles.label_of_article'); ?></h3>
		<?php foreach ($labels as $prefix => $value): ?>
		<div class="input-field">
			<?php print Form::label($prefix); ?> 
			<?php print Form::input('lang_' . $prefix, $value); ?>
		</div>
		<?php endforeach; ?>
		<h3><?php print __('shop.articles.pricing'); ?></h3>
		<div class="input-field">
			<?php print Form::label(__('shop.articles.price')); ?> 
			<?php print Form::input('price', number_format($article->price, 2, ',', '')); ?>
		</div>
		<div class="input-field">
			<?php print Form::label(__('shop.articles.tax_group')); ?> 
			<?php print Form::select('tax_group', $article->tax_group_id, model_db_tax_group::to_selectbox()); ?>
		</div>
		<h3><?php print __('shop.articles.restructuring'); ?></h3>
		<div class="input-field">
			<?php print Form::label(__('shop.articles.group')); ?> 
			<?php print Form::select('article_group', $article->article_group_id, model_db_article_group::to_selectbox()); ?>
		</div>
		<div class="input-field">
			<?php print Form::label(__('shop.articles.article_nr')); ?> 
			<?php print Form::input('nr', $article->nr); ?>
		</div>
		<div class="input-field">
			<?php print Form::label(__('shop.articles.sold_out')); ?> 
			<?php 
			$selected = array();
			$article->sold_out and $selected = array('checked'=>'checked');
			print Form::checkbox('sold_out', 1, $selected); 
			?>
		</div>
		<h3><?php print __('shop.articles.description'); ?></h3>
		<?php foreach ($descriptions as $prefix => $value): ?>
			<h5><?php print $prefix ?></h5>
		<?php print Form::textarea('editor_' . $prefix,$value,array('style'=>'width:90%;height:200px;')); ?>
		<?php endforeach; ?>
	</div>
	<div class="span7">
		<h3><?php print __('shop.articles.article_images'); ?></h3>
		<div class="article-images">
			<ul>
				<?php foreach ($images as $index => $image): ?>
					<li>
						<div class="row">
							<div class="span1">
								<img src="<?php print Uri::create('uploads/shop/article/' . $article->id . '/thumbs/' . $image) ?>">
							</div>
							<div class="span3">
								<div class="main_image">
								<?php 
									$selected = array();
									$article->main_image_index == $index and $selected = array('checked'=>'checked');
									print Form::radio('main_image_index', $index, $selected) . ' ' . __('shop.articles.main_image'); 
								?>
								</div>
								<div class="delete_image">
									<a href="<?php print Uri::create('admin/shop/articles/delete/' . $article->id . '/picture/' . $index) ?>"><?php print __('shop.articles.delete_image') ?></a>
								</div>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<hr />
		<?php print Form::file('image'); ?>
	</div>
</div>
<div class="row action">
	<?php print Form::submit('edit_article',__('shop.articles.save'), array('class'=>'btn primary')) ?> 
	<a href="<?php print Uri::create('admin/shop/articles') ?>" class="btn secondary"><?php print __('shop.articles.back') ?></a>
</div>
<?php print Form::close(); ?>
<script type="text/javascript">
	tinyMCE.init({
	  theme : "advanced",
	  mode : "textareas",
	  theme_advanced_toolbar_location : "top",
	  theme_advanced_buttons1 : "bold,italic,underline,strikethrough,forecolor,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,outdent,indent,blockquote,separator,undo,redo,image,bullist,numlist",
	  theme_advanced_buttons2 : "table,link,code",
	  plugins : 'emotions,safari,inlinepopups',
	  theme_advanced_buttons1_add : "emotions",
	  language : '<?php print Session::get('lang_prefix') ?>'
	});
</script>
<style type="text/css">
.input-field {
	padding: 5px;
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