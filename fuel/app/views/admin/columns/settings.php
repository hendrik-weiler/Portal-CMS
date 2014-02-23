<?php
	print Form::open(array('action'=>'admin/settings/edit','id'=>'settings_form','class'=>'form_style_1'));
?>
<div class="col-xs-12 vertical graycontainer globalmenu">
    <div class="description">
        <?php print __('nav.settings') ?>
    </div>
    <div class="list padding15">
        <h3>
            <?php print __('settings.header.language') ?>
        </h3>


        <?php print Form::label(__('settings.lang')); ?>

        <?php
        $langs = model_lang::to_selectbox();

        print Form::select('language',model_auth::$user['language'],$langs);
        ?>



        <?php
        print Form::submit('submit',__('constants.save'),array('class'=>'button'));

        print Form::close();
        ?>
    </div>
</div>