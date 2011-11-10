<?php print Form::open(array('action'=>Uri::current(),'enctype'=>'multipart/form-data')) ?>
<div class="row">
  <div class="span7">
    <div class="clearfix">
      <?php print Form::label(__('types.1.label')) ?>
      <div class="input">
        <?php print Form::input('label',$label) ?>
      </div><!-- / -->
    </div><!-- / -->
    <div class="clearfix">
      <?php print Form::label(__('types.10.params')) ?>
      <div class="input">
        <?php print Form::textarea('params',$params,array('style'=>'height:100px;')) ?>
        <span class="help-block"><?php print __('types.10.params-help') ?></span>
      </div>
    </div>
    <div class="clearfix">
      <?php print Form::label(__('advanced.thumbs.width')) ?>
      <div class="input">
        <?php print Form::input('width',$width,array('class'=>'small')) ?>
      </div><!-- / -->
    </div><!-- / -->
    <div class="clearfix">
      <?php print Form::label(__('advanced.thumbs.height')) ?>
      <div class="input">
        <?php print Form::input('height',$height,array('class'=>'small')) ?>
      </div><!-- / -->
    </div><!-- / -->
    <div class="clearfix">
      <?php print Form::label('wMode') ?>
      <div class="input">
        <?php print Form::select('wMode',$wMode,array('window'=>'window(Default)','opaque'=>'opaque','transparent'=>'transparent')) ?>
      </div>
    </div>
  </div>
  <div class="span8">
    <div class="row">
      <?php print Form::label(__('types.10.flash_vid')) ?>
      <div class="input">
        <?php print $flash; ?>
        <?php print Form::file('flash_file') ?>
      </div>
    </div>
    <div class="row">
      <?php print Form::label(__('types.10.replace_pic')) ?>
      <div class="input">
        <img src="<?php print $picture; ?>" >
        <?php print Form::file('pictures') ?>
      </div>
    </div>
  </div>
</div>
  <div class="actions">
    <?php print Form::submit('submit',__('types.2.submit'),array('class'=>'btn primary')) . ' '; ?>
    <?php print Form::submit('back',__('types.2.back'),array('class'=>'btn secondary')); ?>
  </div>

  <?php print Form::close() ?>