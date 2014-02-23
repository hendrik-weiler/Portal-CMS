<div class="col-xs-12 vertical graycontainer globalmenu">
    <div class="description">
        <?php print __('nav.groups') ?>
    </div>
    <div class="list padding15">
        <?php print Form::open('admin/shop/groups/add'); ?>

        <?php print Form::label(__('shop.groups.add_label')); ?>

        <?php print Form::input('label', ''); ?>

        <?php print Form::submit('add_article',__('shop.groups.add'), array('class'=>'button')); ?>

        <?php print Form::close(); ?>

        <div class="article_list">
            <ul>
                <?php foreach($groups as $group): ?>
                    <li>
                        <div class="article">
                            <div class="col-xs-9 padding15">
                            <?php
                            $labels = $group->get_label_group();
                            foreach ($labels as $key => $value) {
                                print $key . ' : ' . $value . '<br />';
                            }
                            ?>
                            </div>
                            <div class="col-xs-3 acticle-options">
                                <a class="icon delete" href="<?php print Uri::create('admin/shop/groups/delete/' . $group->id) ?>"><img src="<?php print Uri::create('assets/img/icons/delete.png') ?>"/></a>
                                <a class="icon edit" href="<?php print Uri::create('admin/shop/groups/edit/' . $group->id) ?>"><img src="<?php print Uri::create('assets/img/icons/edit.png') ?>"/></a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>