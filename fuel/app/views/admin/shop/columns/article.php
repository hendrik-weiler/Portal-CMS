<div class="row">
	<?php print Form::open('admin/shop/articles/add'); ?>
	<div class="span3">
		<?php print Form::label(__('shop.articles.add_label')); ?>
	</div>
	<div class="span4">
		<?php print Form::input('label', ''); ?>
	</div>
	<div class="span4">
		<?php print Form::submit('add_article',__('shop.articles.add'), array('class'=>'btn primary')); ?>
	</div>
	<?php print Form::close(); ?>
</div>
<div class="row article_list">
<ul>
	<?php foreach($articles as $article): ?>
		<li>
			<div class="row article">
			<div class="span6">
			<?php 
			$labels = $article->get_label_group();
			foreach ($labels as $key => $value) {
				print $key . ' : ' . $value . '<br />';
			}
			?>
			</div>
			<div class="span6">
				<a class="btn secondary" href="<?php print Uri::create('admin/shop/articles/edit/' . $article->id) ?>"><?php print __('shop.articles.edit') ?></a>
				<a class="btn error" href="<?php print Uri::create('admin/shop/articles/delete/' . $article->id) ?>"><?php print __('shop.articles.delete') ?></a>
			</div>
			</div>
		</li>
	<?php endforeach; ?>
</ul>
</div>
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

.article_list ul {
	list-style: none;
	padding: 0;
	margin: 0;
}

.article_list {
	margin-top: 20px;
}

.article_list .article {
	border-top: 2px dotted gray;
	padding: 15px;
}
</style>