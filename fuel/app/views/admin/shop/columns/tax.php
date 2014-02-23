	<?php print Form::open('admin/shop/tax/add'); ?>
    <div class="col-xs-12 vertical graycontainer globalmenu">
        <div class="description">
            <?php print __('nav.article') ?>
        </div>
        <div class="list padding15">

			<?php print Form::label(__('shop.tax.add_label')); ?>

			<?php print Form::input('label', ''); ?>

            <?php print Form::label(__('shop.tax.add_value')); ?>

            <?php print Form::input('value', '0', array('style'=>'width:30%;')); ?> %

			<?php print Form::submit('add_article',__('shop.tax.add'), array('class'=>'button')); ?>

            <br>


            <div class="article_list">
                <ul>
                    <?php foreach($taxes as $tax): ?>
                        <li>
                            <div class="article">
                                <div class="col-xs-9 padding15">
                                    <?php print $tax->label . ' (' . $tax->value . '%)';	?>
                                </div>
                                <div class="col-xs-3 acticle-options">
                                    <a class="icon delete" href="<?php print Uri::create('admin/shop/tax/delete/' . $tax->id) ?>"><img src="<?php print Uri::create('assets/img/icons/delete.png') ?>"/></a>
                                    <a class="icon edit" href="<?php print Uri::create('admin/shop/tax/edit/' . $tax->id) ?>"><img src="<?php print Uri::create('assets/img/icons/edit.png') ?>"/></a>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
</div>
<?php print Form::close(); ?>