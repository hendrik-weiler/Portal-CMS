<script type="text/javascript" src="<?php print Uri::create('assets/js/tiny_mce/tiny_mce.js'); ?>"></script>
<?php print Form::open(array('action'=>Uri::current(),'class'=>'form_style_1')); ?>
<div class="col-xs-1 backbutton">
    <label>
        <?php print Form::submit('back',__('types.15.back'),array('class'=>'hide')); ?>
        <img src="<?php print Uri::create('assets/img/icons/arrow_left.png') ?>" alt=""/>
    </label>
</div>
<div class="col-xs-11 vertical graycontainer globalmenu">
  <div class="description">
      <h3>
        <?php print __('types.1.header') ?>
      </h3>
  </div>
  <div class="list padding15">

   <?php print Form::label(__('types.1.label')); ?>
   <?php print Form::input('label',$label); ?>

   <div class="picturemanager-button"><?php print Lang::get('picturemanager_button') ?></div>

      <br/>
    <?php print Form::textarea('editor',$text,array('style'=>'width:100%;height:400px')); ?>
  <?php
    print Form::submit('submit',__('types.1.submit'),array('class'=>'button'));

    print Form::close();
  ?>
  </div>
</div>
<script type="text/javascript">
var picturemanager = new pcms.picturemanager();
picturemanager.build_button('.picturemanager-button');

tinyMCE.init({
  theme : "advanced",
  mode : "textareas",
  theme_advanced_toolbar_location : "top",
  theme_advanced_buttons1 : "bold,italic,underline,strikethrough,forecolor,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,outdent,indent,blockquote,separator,undo,redo,image,bullist,numlist,table,link,code",
  plugins : 'emotions,safari,inlinepopups',
  theme_advanced_buttons1_add : "emotions",
  language : '<?php print model_db_accounts::get_system_language() ?>'
});
</script>