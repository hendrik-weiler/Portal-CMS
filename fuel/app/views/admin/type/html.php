<div class="row">
  <h3>
    <?php print __('types.11.header') ?>
  </h3>

  <?php
    print Form::open(array('action'=>Uri::current(),'class'=>'form_style_1'));
  ?>
  <div class="row">
    <button id="addplaceholder" class="btn secondary"><?php print __('types.11.addplaceholder') ?></button>
  </div>
  <div class="row placeholder_container">
    <?php foreach ($parameter as $placeholder): ?>
    <div class="span4">
      <div class="delete">
        <a href="#"><?php print __('types.11.placeholder_delete') ?></a>
      </div>
      <?php print __('types.11.placeholder_name') ?>
      <input value="<?php print $placeholder['name'] ?>" name="placeholder_name[]" />
      <?php print __('types.11.placeholder_text') ?>
      <textarea name="placeholder_text[]"><?php print $placeholder['text'] ?></textarea>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="row">
        <textarea name="html" style="width:100%;height:350px;"><?php print $text; ?></textarea>
  </div>
  <?php
    print '<div class="actions">';

    print Form::submit('submit',__('types.1.submit'),array('class'=>'btn primary')) . ' ';
    print Form::submit('back',__('types.1.back'),array('class'=>'btn secondary'));

    print '</div>';

    print Form::close();
  ?>
</div>
<script>
    var placeholder_html = $('<div class="span4"><div class="delete"><a href="#"><?php print __('types.11.placeholder_delete') ?></a></div><?php print __('types.11.placeholder_name') ?><input value="" name="placeholder_name[]" /><?php print __('types.11.placeholder_text') ?><textarea name="placeholder_text[]"></textarea></div>');

  $('#addplaceholder').click(function(e) {
    e.preventDefault();
    var name = $('.placeholder_container').find('div.span4').length;
    $('.placeholder_container').append(placeholder_html.clone());
  });

  $('.placeholder_container div.delete a').live('click',function(e) {
    e.preventDefault();
    $(this).parentsUntil('div.span4').parent().remove();
  });
</script>