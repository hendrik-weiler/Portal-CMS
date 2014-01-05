<div class="row">
	<?php print Form::open('admin/shop/groups/add'); ?>
	<div class="span3">
		<?php print Form::label(__('shop.groups.add_label')); ?>
	</div>
	<div class="span4">
		<?php print Form::input('label', ''); ?>
	</div>
	<div class="span4">
		<?php print Form::submit('add_article',__('shop.groups.add'), array('class'=>'btn primary')); ?>
	</div>
	<?php print Form::close(); ?>
</div>
<div class="row article_list">
<ul>
	<?php foreach($groups as $group): ?>
		<li>
			<div class="row article">
			<div class="span6">
			<?php 
			$labels = $group->get_label_group();
			foreach ($labels as $key => $value) {
				print $key . ' : ' . $value . '<br />';
			}
			?>
			</div>
			<div class="span6">
				<a class="btn secondary" href="<?php print Uri::create('admin/shop/groups/edit/' . $group->id) ?>"><?php print __('shop.groups.edit') ?></a>
				<a class="btn error" href="<?php print Uri::create('admin/shop/groups/delete/' . $group->id) ?>"><?php print __('shop.groups.delete') ?></a>
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