	<?php print Form::open('admin/shop/tax/add'); ?>
	<div class="row">
		<div class="span3">
			<?php print Form::label(__('shop.tax.add_label')); ?>
		</div>
		<div class="span4">
			<?php print Form::input('label', ''); ?>
		</div>
		<div class="span4">
			<?php print Form::submit('add_article',__('shop.tax.add'), array('class'=>'btn primary')); ?>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="span3">
			<?php print Form::label(__('shop.tax.add_value')); ?>
		</div>
		<div class="span4">
			<?php print Form::input('value', '', array('style'=>'width:30%;')); ?> %
		</div>
		<div class="span4">
			&nbsp;
		</div>
	</div>
<?php print Form::close(); ?>
<div class="row article_list">
<ul>
	<?php foreach($taxes as $tax): ?>
		<li>
			<div class="row article">
			<div class="span6">
			<?php print $tax->label . ' (' . $tax->value . '%)';	?>
			</div>
			<div class="span6">
				<a class="btn secondary" href="<?php print Uri::create('admin/shop/tax/edit/' . $tax->id) ?>"><?php print __('shop.tax.edit') ?></a>
				<a class="btn error" href="<?php print Uri::create('admin/shop/tax/delete/' . $tax->id) ?>"><?php print __('shop.tax.delete') ?></a>
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