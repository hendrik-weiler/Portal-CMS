<div id="update">
  <ul class="tabs" data-tabs="tabs">
    <li><a href="<?php print Uri::create('admin/advanced') ?>"><?php print __('advanced.tabs.back') ?></a></li>
    <li class="active"><a href="<?php print Uri::create('admin/advanced/update') ?>">Updates</a></li>
  </ul>
  <div class="row">
  </div>

  <?php if(!is_array($update_list['message']) && !empty($update_list['message'])): ?>
  <div class="message row">
    <?php 

      $message = $update_list['message']['default'];

      if(isset($update_list['message'][$user_lang]))
        $message = $update_list['message'][$user_lang];

      print $message; 
    ?>
  </div>
  <?php endif; ?>

  <?php if(Input::get('result') != ''): ?>
  <div class="result message <?php print Input::get('result') ?> row">
    <?php print __('advanced.updater.' . Input::get('result')) ?>
  </div>
  <?php endif; ?>

  <?php print Form::open('admin/advanced/update/execute') ?>

  <div class="update head row">
    <div class="release_date span3"><?php print __('advanced.updater.release_date') ?></div>
    <div class="version span1"><?php print __('advanced.updater.version') ?></div>
    <div class="description span8"><?php print __('advanced.updater.description') ?></div>
  </div>

  <?php if(isset($update_list['updates']['update'])): ?>
  <?php foreach($update_list['updates']['update'] as $update): ?>

  <div class="update row">
    <div class="release_date span3">
      <?php 
        if(is_array($update['release_date']))
          print '&nbsp;';
        else
          print date(__('advanced.updater.dateformat'),strtotime($update['release_date'])); 
      ?>
    </div>
    <div class="version span1"><?php print $update['version']; ?></div>
    <div class="description span7">
      <?php 

        $description = $update['description']['default'];

        if(isset($update['description'][$user_lang]))
          $description = $update['description'][$user_lang];

        print $description; 
      ?>
    </div>
    <div class="update-action span4">
      <?php 

      if(!$update['done']) 
      {
        print Form::hidden($update['version'] . '_filename', $update['filename']);

        if(!$update['released'])
          print Html::Anchor('#',__('advanced.updater.update_not_available'),array('class'=>'btn disabled update_not_available'));
        else if(!$update['is_updateable'])
          print Html::Anchor('#',__('advanced.updater.update') . ' : ' . $update['version'],array('class'=>'btn disabled'));
        else
          print Form::submit('update_to',__('advanced.updater.update') . ' : ' . $update['version'],array('class'=>'btn'));
      }

      ?>
    </div>
  </div>

  <?php endforeach; ?>
  <?php else: ?>
  <div class="update">
    <?php print __('advanced.updater.no_updates') ?>
  </div>
  <?php endif; ?>

  <?php print Form::close(); ?>
</div>
<script type="text/javascript">
$('.result').delay(1000).fadeOut(2000);
</script>