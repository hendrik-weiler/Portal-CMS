<script type="text/javascript" src="<?php print Uri::create('assets/js/tiny_mce/tiny_mce.js'); ?>"></script>
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

    <div class="span16" style="margin-bottom:20px;">
      <div class="picturemanager-button"><?php print Lang::get('picturemanager_button') ?></div>
    </div>

    <div class="span4">
      <?php print Form::textarea('editor',$text,array('style'=>'width:300px;height:400px;')); ?>
    </div>

    <div class="span4" style="margin-left:90px;">
      <?php print Form::textarea('editor2',$text2,array('style'=>'width:300px;height:400px;')); ?>
    </div>

    <div class="span4"  style="margin-left:90px;">
      <?php print Form::textarea('editor3',$text3,array('style'=>'width:300px;height:400px;')); ?>
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

<script type="text/javascript">
var picturemanager = new pcms.picturemanager();
picturemanager.build_button('.picturemanager-button');

tinyMCE.init({
  theme : "advanced",
  mode : "textareas",
  theme_advanced_toolbar_location : "top",
  theme_advanced_buttons1 : "bold,italic,underline,strikethrough,forecolor,separator,justifyleft,justifycenter,justifyright,justifyfull",
  theme_advanced_buttons2 : "separator,outdent,indent,blockquote,separator,undo,redo,image,bullist,numlist,table,link,code",
  theme_advanced_buttons3 : "",
  plugins : 'emotions,safari,inlinepopups',
  theme_advanced_buttons1_add : "emotions",
  language : '<?php print Session::get('lang_prefix') ?>'
});
</script>