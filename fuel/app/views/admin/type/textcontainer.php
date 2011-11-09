<div class="row">
  <h3>
    <?php print __('types.1.header') ?>
  </h3>

  <?php
    print Form::open(array('action'=>Uri::current(),'class'=>'form_style_1'));
  ?>

  <div class="clearfix">
   <?php print Form::label(__('types.1.label')); ?>
   <div class="input">
      <?php print Form::input('label',$label); ?>
    </div>
  </div>
    
  <div class="row">
    <div class="span16">
      <div id="editor">
        <?php print $text; ?>
      </div>
    </div>
  </div>
  <?php
    print '<div class="actions">';

    print Form::submit('submit',__('types.1.submit'),array('class'=>'btn primary')) . ' ';
    print Form::submit('back',__('types.1.back'),array('class'=>'btn secondary'));

    print '</div>';

    print Form::close();
  ?>
</div>