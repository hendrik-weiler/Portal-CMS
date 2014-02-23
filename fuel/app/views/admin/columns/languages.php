<?php

$ifEdit = (!empty($id)) ? '/' . $id : '';

print Form::open(array('action'=>'admin/language/' . $mode . $ifEdit,'id'=>'languages_form','class'=>'form_style_1'));
?>
<?php if(!empty($id)): ?>
    <div class="col-xs-1 backbutton">
        <label>
            <?php print Form::submit('back',__('types.15.back'),array('class'=>'hide')); ?>
            <img src="<?php print Uri::create('assets/img/icons/arrow_left.png') ?>" alt=""/>
        </label>
    </div>
<?php endif; ?>
<div class="col-xs-11 vertical graycontainer globalmenu">
    <div class="description">
        <h3>
            <?php print __('languages.' . $mode . '_lang_header') ?>
        </h3>
    </div>
    <div class="list padding15">


        <?php print Form::label(__('languages.form.lang')); ?>

        <?php print Form::input('label',$label); ?>



        <?php print Form::label(__('languages.form.lang_prefix')); ?>

        <?php print Form::input('prefix',$prefix); ?>


        <?php
        print Form::submit('submit',__('languages.form.' . $mode . '_button'),array('class'=>'button'));

        print Form::close();
        ?>

        <hr />
        <img id="moveable_language" src="<?php print Uri::create('assets/img/admin/moveable.png') ?>" alt="Moveable">
        <h5>
            <?php print __('languages.sortable') ?>
        </h5>
        <div id="language_list">
            <?php

            $languages = model_db_language::find('all',array(
                'order_by' => array('sort'=>'ASC')
            ));

            $count = 0;

            foreach($languages as $lang)
            {
                print '<div class="language_entry" id="' . $lang['id'] . '">';

                print '<div class="col-xs-4 padding15">';

                print $lang['label'];

                print '</div>';

                print '<div class="col-xs-4 padding15">';

                print $lang['prefix'];

                print '</div>';

                print '<div class="col-xs-4 language-options">';

                print '<a class="icon move" href="#"><img src="' . Uri::create('assets/img/icons/arrow_move.png') . '"/></a>';

                if(count($languages) > 1)
                    print ' <a class="delete" href="' . Uri::create('admin/language/delete/' . $lang['id']) . '"><img src="' . Uri::create('assets/img/icons/delete.png') . '" /></a>';

                print '<a href="' . Uri::create('admin/language/edit/' . $lang['id']) . '"><img src="' . Uri::create('assets/img/icons/edit.png') . '"/></a>';

                print '</div>';

                if($count == 0)
                    print '<div class="col-xs-12 startlanguage-text">' . __('languages.startlanguage') . '</div>';

                print '</div>';

                $count++;
            }

            ?>
        </div>

    </div>
</div>