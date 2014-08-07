<?php print Form::open(array('action'=>Uri::current(),'enctype'=>'multipart/form-data')); ?>
<div class="col-xs-1 backbutton">
    <label>
        <?php print Form::submit('back',__('types.15.back'),array('class'=>'hide')); ?>
        <img src="<?php print Uri::create('assets/img/icons/arrow_left.png') ?>" alt=""/>
    </label>
</div>
<div class="col-xs-11 vertical graycontainer globalmenu">
    <div class="description">
        <?php print __('nav.groups'); ?>
    </div>
    <div class="list padding15">

		<h3><?php print __('shop.tax.add_label'); ?></h3>

        <?php print Form::label(__('shop.tax.add_label')); ?>
        <?php print Form::input('label', $group->label); ?>

        <?php print Form::label(__('shop.tax.add_value')); ?>
        <?php print Form::input('value', $group->value, array('style'=>'width:30%;')); ?> %

        <?php print Form::submit('edit_article',__('shop.articles.save'), array('class'=>'button')) ?>
    </div>
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