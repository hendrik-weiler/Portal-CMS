<?php print Form::open(array('action'=>Uri::current(),'enctype'=>'multipart/form-data')); ?>
<div class="row">
	<div class="span16">
		<h3><?php print __('shop.groups.add_label'); ?></h3>
		<?php foreach ($labels as $prefix => $value): ?>
		<div class="input-field">
			<?php print Form::label($prefix); ?> 
			<?php print Form::input('lang_' . $prefix, $value); ?>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<div class="row action">
	<?php print Form::submit('edit_article',__('shop.articles.save'), array('class'=>'btn primary')) ?> 
	<a href="<?php print Uri::create('admin/shop/groups') ?>" class="btn secondary"><?php print __('shop.articles.back') ?></a>
</div>
<?php print Form::close(); ?>
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