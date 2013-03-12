<section id="layout">
  <ul class="tabs" data-tabs="tabs">
    <li><a href="<?php print Uri::create('admin/advanced') ?>"><?php print __('advanced.tabs.back') ?></a></li>
    <li class="active"><a href="<?php print Uri::create('admin/advanced/layout') ?>"><?php print __('advanced.tabs.layout') ?></a></li>
  </ul>
  <div class="row">
    <div class="span4">
      <h2><?php print __('advanced.layout.current') ?></h2>
      <?php
          $layout = model_db_option::getKey('layout');

          if(is_dir(LAYOUTPATH . '/' . $layout->value))
          {
            $settings = file_get_contents(LAYOUTPATH . '/' . $layout->value . '/settings.json');
            $settings = Format::forge($settings,'json')->to_array();

            $description = isset($settings['description'][$lang]) ? $settings['description'][$lang] : $settings['description']['default'];
            print '
            <div>
              <h3>' . $settings['name'] . '</h3>
              <img src="' . Uri::create('admin/advanced/layout/preview/' . strtolower($layout->value) . '/' . $settings['preview']) . '" alt="' . $settings['name'] . '_preview" />
              <article>' . $description . '</article>
            </div>
            ';
          }

          print Form::open(Uri::create('admin/advanced/layout/edit'));

          foreach($settings['components'] as $key => $value)
          {

            try
            {

              $search = model_db_navgroup::find('first',array(
                'where' => array('title'=>$value)
              ));
            }
            catch(Exception $e)
            {
              Controller_Language_Language::add_language(Session::get('lang_prefix'),'',true);
            }

            $choose = empty($search) ? 0 : $search->id;
            print '<div style="padding:10px; 0">' . $key . '</div>';
            print Form::select($key,$choose,array(0=>__('constants.not_set')) + model_db_navgroup::asSelectBox());
          }

          print '<div class="actions">';
          print Form::submit('submit',__('constants.save'),array('class'=>'btn primary'));
          print '</div>';

          print Form::close();
      ?>
    </div>
    <div class="span11">
      <?php 
        foreach(File::read_dir(LAYOUTPATH . '/',1) as $key => $dir) 
        {
          $key = str_replace(DS,'',$key);
          if(is_dir(LAYOUTPATH . '/' . $key))
          {
            $settings = file_get_contents(LAYOUTPATH . '/' . $key . '/settings.json');
            $settings = Format::forge($settings,'json')->to_array();

            $check = $layout->value == $key ? array('checked'=>'checked') : array();

            $description = isset($settings['description'][$lang]) ? $settings['description'][$lang] : $settings['description']['default'];

            $radio = Form::radio('layout',$key,$check + array('class'=>'choose_layout'));
            print '
            <div>
              <h3>' . $settings['name'] . '</h3>
              <img height="150" width="150" src="' . Uri::create('admin/advanced/layout/preview/' . strtolower($key) . '/' . $settings['preview']) . '" alt="' . $settings['name'] . '_preview" />
              <article>' . $description . '</article>
              ' . $radio . '
            </div>
            ';
          }
        }
      ?>
    </div>
  </div>

</section>
<script>var _wait = "<?php print __('advanced.layout.wait') ?>";</script>