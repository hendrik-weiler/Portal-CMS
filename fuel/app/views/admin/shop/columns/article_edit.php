<script type="text/javascript" src="<?php print Uri::create('assets/js/tiny_mce/tiny_mce.js'); ?>"></script>
<?php print Form::open(array('action'=>Uri::current(),'enctype'=>'multipart/form-data')); ?>
<div class="col-xs-1 backbutton">
    <label>
        <?php print Form::submit('back',__('types.15.back'),array('class'=>'hide')); ?>
        <img src="<?php print Uri::create('assets/img/icons/arrow_left.png') ?>" alt=""/>
    </label>
</div>
<div class="col-xs-11 vertical graycontainer globalmenu">
    <div class="description">
        <?php print __('nav.article') ?>
    </div>
    <div class="list padding15">
        <div class="col-xs-6">
            <h3><?php print __('shop.articles.label_of_article'); ?></h3>
            <ul class="nav nav-tabs">
            <?php $counter = 0; ?>
            <?php foreach ($labels as $prefix => $value): ?>
                <?php $active = ''; if($counter == 0) {$active = "active";} ?>
                <li class="<?php print $active ?>">
                    <a data-toggle="tab" href="#<?php print $prefix ?>_1">
                        <?php print $prefix ?>
                    </a>
                </li>
                <?php $counter++; ?>
            <?php endforeach; ?>
            </ul>

            <div class="tab-content">

            <?php $counter = 0; ?>
            <?php foreach ($labels as $prefix => $value): ?>
                <?php $active = 'tab-pane fade'; if($counter == 0) {$active = "tab-pane fade in active";} ?>
                <div id="<?php print $prefix ?>_1" class="<?php print $active ?>">
                    <br/>
                    <?php print Form::input('lang_' . $prefix, $value); ?>
                </div>
                <?php $counter++; ?>
            <?php endforeach; ?>

            </div>

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
                <?php print Form::select('article_group', $article->article_group_id, model_db_article_group::to_selectbox(Session::get('lang_prefix'))); ?>
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

            <ul class="nav nav-tabs">
            <?php $counter = 0; ?>
            <?php foreach ($labels as $prefix => $value): ?>
                <?php $active = ''; if($counter == 0) {$active = "active";} ?>
                <li class="<?php print $active ?>">
                    <a data-toggle="tab" href="#<?php print $prefix ?>_2">
                        <?php print $prefix ?>
                    </a>
                </li>
                <?php $counter++; ?>
            <?php endforeach; ?>
            </ul>

            <div class="tab-content">

                <?php $counter = 0; ?>
                <?php foreach ($labels as $prefix => $value): ?>
                    <?php $active = 'tab-pane fade'; if($counter == 0) {$active = "tab-pane fade in active";} ?>
                    <div id="<?php print $prefix ?>_2" class="<?php print $active ?>">
                        <br/>
                        <?php print Form::textarea('editor_' . $prefix,$value,array('style'=>'width:90%;height:200px;')); ?>
                    </div>
                    <?php $counter++; ?>
                <?php endforeach; ?>

            </div>

        </div>
        <div class="col-xs-6">
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
        <div class="col-xs-12">
            <?php print Form::submit('edit_article',__('shop.articles.save'), array('class'=>'button')) ?>
        </div>
    </div>
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