<div class="row">
  <h3>
    <?php print __('types.11.header') ?>
  </h3>

  <?php
    print Form::open(array('action'=>Uri::current(),'class'=>'form_style_1'));
  ?>
    
  <div class="row">
        <textarea name="html" style="width:100%;height:500px;"><?php print $text; ?></textarea>
  </div>
  <?php
    print '<div class="actions">';

    print Form::submit('submit',__('types.1.submit'),array('class'=>'btn primary')) . ' ';
    print Form::submit('back',__('types.1.back'),array('class'=>'btn secondary'));

    print '</div>';

    print Form::close();
  ?>
</div>