<?php print Form::open(array('action'=>Uri::current(),'class'=>'form_style_1')); ?>
<div class="col-xs-1 backbutton">
    <label>
        <?php print Form::submit('back',__('types.15.back'),array('class'=>'hide')); ?>
        <img src="<?php print Uri::create('assets/img/icons/arrow_left.png') ?>" alt=""/>
    </label>
</div>
<div class="row">
    <div class="col-xs-11 vertical graycontainer globalmenu">
      <div class="description">
          <h3>
            <?php print __('types.11.header') ?>
          </h3>
      </div>
      <div class="list padding15">

    <button id="addplaceholder" class="button"><?php print __('types.11.addplaceholder') ?></button>

  <div class="placeholder_container">
    <?php foreach ($parameter as $placeholder): ?>
    <div class="col-xs-3">
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

  <textarea name="html" style="width:100%;height:350px;"><?php print $text; ?></textarea>

  <?php print Form::submit('submit',__('constants.save'),array('class'=>'button')); ?>
      </div>
</div>
</div>
<?php print Form::close(); ?>
<script>
    var placeholder_html = $('<div class="col-xs-3"><div class="delete"><a href="#"><?php print __('types.11.placeholder_delete') ?></a></div><?php print __('types.11.placeholder_name') ?><input value="" name="placeholder_name[]" /><?php print __('types.11.placeholder_text') ?><textarea name="placeholder_text[]"></textarea></div>');

  $('#addplaceholder').click(function(e) {
    e.preventDefault();
    var name = $('.placeholder_container').find('div.col-xs-3').length;
    $('.placeholder_container').append(placeholder_html.clone());
  });

  $('.placeholder_container').on('click',"div.delete a",function(e) {
    e.preventDefault();
    $(this).parentsUntil('div.col-xs-3').parent().remove();
  });
</script>