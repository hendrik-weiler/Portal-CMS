<?php
    if($mode == 'edit')
      $action = Uri::current();
    else
      $action = 'admin/accounts/add';

    # ------------------------------------------

    print Form::open(array('action'=>$action,'class'=>'form_style_1'));
?>
<div class="col-xs-1 backbutton">
    <label>
        <?php print Form::submit('back',__('types.15.back'),array('class'=>'hide')); ?>
        <img src="<?php print Uri::create('assets/img/icons/arrow_left.png') ?>" alt=""/>
    </label>
</div>
<div class="col-xs-11 vertical graycontainer globalmenu">
        <div class="description">

        </div>
        <div class="list padding15">
            <?php print Form::label(__('advanced.accounts.add.user')); ?>

            <?php print Form::input('username',$username); ?>

            <?php print Form::label(__('advanced.accounts.' . $mode . '.pass')); ?>

            <?php print Form::input('password'); ?>

            <?php print Form::label(__('advanced.accounts.add.language')); ?>
            <br/><br/>
            <?php print Form::select('language',$language,model_lang::to_selectbox()); ?>
            <a class="more-options" href="#"><?php print __('constants.more_options') ?></a>
            <a class="less-options" href="#"><?php print __('constants.less_options') ?></a>
            <div class="more-options-box">
                <br/><br/>
                <?php print Form::label(__('advanced.accounts.admin')); ?>
                <br/>
                <?php
                    $checked = array();
                    if($mode != 'edit') { $checked = array('checked'=>'checked'); }
                    print Form::checkbox('admin',1,$admin + array('style'=>'width:auto;display:inline') + $checked);
                ?>

                <?php print '<h3>' . __('advanced.accounts.permissions') . '</h3>'; ?>


                <?php print Form::label(__('advanced.accounts.languages')); ?>
                <br/><br/>
                <?php
                $languages = model_db_language::getLanguages();

                foreach($languages as $key => $language)
                {
                    if(isset($global_language) && in_array($key,$global_language))
                        $check = array('checked'=>'checked');
                    else
                        $check = array();

                    if($mode != 'edit') { $check = array('checked'=>'checked'); }

                    print Form::checkbox('global_language[]',$key,$check + array('style'=>'width:auto;display:inline')) . ' ' . $language . '<br />';
                }
                ?>
                <br/>
                <?php print Form::label(__('advanced.accounts.categories')); ?>
                <div class="input">

                    <ul id="tabs_menu" class="nav nav-tabs" >
                        <?php
                        foreach($languages as $key => $language)
                        {
                            $key = model_db_language::idToPrefix($key);
                            print '<li><a data-toggle="tab" href="#' . $key . '">' . $language . '</a></li>';
                        }

                        ?>
                    </ul>

                    <div class="tab-content">
                        <?php
                        $counter = 0;
                        foreach($languages as $key => $language)
                        {
                            $navis = model_generator_navigation::getNaviAsArray($key,$key);
                            $key = model_db_language::idToPrefix($key);

                            $class = "tab-pane fade padding15";
                            if($counter == 0) {
                                $class = "tab-pane fade in active padding15";
                            }

                            print '<div id="' . $key . '" class="' . $class . '">';

                            $key = model_db_language::prefixToId($key);

                            if(isset($intern['categories_' . $key]) && in_array(0,$intern['categories_' . $key]))
                                $checked = array('checked'=>'checked');
                            else
                                $checked = array();

                            if($mode != 'edit') { $checked = array('checked'=>'checked'); }

                            print Form::checkbox('categories_' . $key . '[]',0,$checked + array('style'=>'width:auto;display:inline')) . " " . __('nav.navigation') . '<br />';

                            if(isset($intern['categories_' . $key]) && in_array(2,$intern['categories_' . $key]))
                                $checked = array('checked'=>'checked');
                            else
                                $checked = array();

                            if($mode != 'edit') { $checked = array('checked'=>'checked'); }

                            print Form::checkbox('categories_' . $key . '[]',2,$checked + array('style'=>'width:auto;display:inline')) . " " . __('nav.news') . '<br />';

                            if(isset($intern['categories_' . $key]) && in_array(3,$intern['categories_' . $key]))
                                $checked = array('checked'=>'checked');
                            else
                                $checked = array();

                            if($mode != 'edit') { $checked = array('checked'=>'checked'); }

                            print Form::checkbox('categories_' . $key . '[]',3,$checked + array('style'=>'width:auto;display:inline')) . " " . __('nav.settings') . '<br />';

                            print '</div>';

                            $counter++;
                        }

                        ?>
                    </div>
                    </div>
                </div>
                <?php
                print Form::submit('submit',__('advanced.accounts.' . $mode . '.button'),array('class'=>'button'));

                print Form::close();
                ?>
        </div>
</div>

<script src="<?php print Uri::create('assets/js/libs/bootstrap-tabs.js') ?>"></script>