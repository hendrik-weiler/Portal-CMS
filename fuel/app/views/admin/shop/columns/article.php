<div class="col-xs-12 vertical graycontainer globalmenu">
    <div class="description">
        <?php print __('nav.article') ?>
    </div>
    <div class="list padding15">

            <?php print Form::open('admin/shop/articles/add'); ?>

                <?php print Form::label(__('shop.articles.add_label')); ?>

                <?php print Form::input('label', ''); ?>

                <?php print Form::submit('add_article',__('shop.articles.add'), array('class'=>'button')); ?>

            <?php print Form::close(); ?>
        <div class="article_list">
            <ul>
                <?php foreach($articles as $article): ?>
                    <li>
                        <div class="article">
                            <div class="col-xs-9 padding15">
                                <?php
                                $labels = $article->get_label_group();
                                foreach ($labels as $key => $value) {
                                    print $key . ' : ' . $value . '<br />';
                                }
                                ?>
                            </div>
                            <div class="col-xs-3 acticle-options">
                                <a class="icon delete" href="<?php print Uri::create('admin/shop/articles/delete/' . $article->id) ?>"><img src="<?php print Uri::create('assets/img/icons/delete.png') ?>"/></a>
                                <a class="icon edit" href="<?php print Uri::create('admin/shop/articles/edit/' . $article->id) ?>"><img src="<?php print Uri::create('assets/img/icons/edit.png') ?>"/></a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>