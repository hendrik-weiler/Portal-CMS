<?php
    if($mode == 'edit')
      $action = Uri::current();
    else
      $action = 'admin/accounts/add';

    # ------------------------------------------

    print Form::open(array('action'=>$action,'class'=>'form_style_1'));
?>
      <div class="clearfix">
       <?php print Form::label(__('advanced.accounts.add.user')); ?>
       <div class="input">
          <?php print Form::input('username',$username); ?>
        </div>
      </div>
          <div class="clearfix">
       <?php print Form::label(__('advanced.accounts.' . $mode . '.pass')); ?>
       <div class="input">
          <?php print Form::input('password'); ?>
        </div>
      </div>
          <div class="clearfix">
       <?php print Form::label(__('advanced.accounts.add.language')); ?>
       <div class="input">
          <?php print Form::select('language',$language,model_lang::to_selectbox()); ?>
        </div>
      </div>
          <div class="clearfix">
       <?php print Form::label(__('advanced.accounts.admin')); ?>
       <div class="input">
          <?php     print Form::checkbox('admin',1,$admin + array('style'=>'width:auto;display:inline')); ?>
        </div>
        <div class="input">
            <?php print '<h3>' . __('advanced.accounts.permissions') . '</h3>'; ?>
        </div>
      </div>
          <div class="clearfix">
       <?php print Form::label(__('advanced.accounts.languages')); ?>
       <div class="input">
          <?php 
            $languages = model_db_language::getLanguages();

            foreach($languages as $key => $language)
            {
                if(isset($global_language) && in_array($key,$global_language))
                    $check = array('checked'=>'checked');
                else
                    $check = array();

                print Form::checkbox('global_language[]',$key,$check + array('style'=>'width:auto;display:inline')) . ' ' . $language . '<br />';
            }
           ?>
        </div>
      </div>

<div class="clearfix">
    <?php print Form::label(__('advanced.accounts.categories')); ?>
    <div class="input">

        <ul id="tabs_menu" class="pills" data-tabs="tabs">
        <?php
            foreach($languages as $key => $language)
            {
                $key = model_db_language::idToPrefix($key);
                print '<li><a href="#' . $key . '">' . $language . '</a></li>';
            }
            
        ?>
        </ul>

        <div class="pill-content">
        <?php
            foreach($languages as $key => $language)
            {
                $navis = model_generator_navigation::getNaviAsArray($key);
                $key = model_db_language::idToPrefix($key);

                print '<div id="' . $key . '">';

                $key = model_db_language::prefixToId($key);
                
                if(isset($intern['categories_' . $key]) && in_array(0,$intern['categories_' . $key]))
                    $checked = array('checked'=>'checked');
                else
                    $checked = array();

                print Form::checkbox('categories_' . $key . '[]',0,$checked + array('style'=>'width:auto;display:inline')) . __('nav.navigation') . '<br />';

                if(isset($intern['categories_' . $key]) && in_array(1,$intern['categories_' . $key]))
                    $checked = array('checked'=>'checked');
                else
                    $checked = array();
                    
                print Form::checkbox('categories_' . $key . '[]',1,$checked + array('style'=>'width:auto;display:inline')) . __('nav.sites') . '<br />';
                
                if(isset($intern['categories_' . $key]) && in_array(2,$intern['categories_' . $key]))
                    $checked = array('checked'=>'checked');
                else
                    $checked = array();
                    
                print Form::checkbox('categories_' . $key . '[]',2,$checked + array('style'=>'width:auto;display:inline')) . __('nav.news') . '<br />';

                if(isset($intern['categories_' . $key]) && in_array(3,$intern['categories_' . $key]))
                    $checked = array('checked'=>'checked');
                else
                    $checked = array();
                    
                print Form::checkbox('categories_' . $key . '[]',3,$checked + array('style'=>'width:auto;display:inline')) . __('nav.settings') . '<br />';

                  print '</div>';
                }
            
        ?>
    </div>

    </div>
</div>

<?php

    print '<div class="clearfix actions">';
    print Form::submit('submit',__('advanced.accounts.' . $mode . '.button'),array('class'=>'btn primary')) . ' ';
    print Form::submit('back',__('advanced.accounts.add.back'),array('class'=>'btn secondary'));
    print '</div>';

    print Form::close();
  ?>

<script src="<?php print Uri::create('assets/js/libs/bootstrap-tabs.js') ?>"></script>