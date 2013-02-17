<script type="text/javascript" src="<?php print Uri::create('assets/js/tiny_mce/tiny_mce.js'); ?>"></script>
<?php print Form::open(array('action'=>'admin/content/' . Uri::segment(3) . '/edit/' . Uri::segment(5) . '/type/13/edit','enctype'=>'multipart/form-data')) ?>
<div class="span7">
    <div class="clearfix">
      <?php print Form::label(__('types.13.template')); ?>
      <div class="input">
        <?php print Form::select('template', $selected_template, $templates); ?>
      </div>
    </div>
    <?php

    if(!empty($template_variables))
    {
    	foreach ($template_variables as $variable) 
    	{
    		print '<h5>' . $variable . '</h5>';
    		if(preg_match('#(\$tpl_file_[\w]+)#i', $variable))
    		{
    			print Form::file(str_replace('$', '', $variable));
    		}

    		if(preg_match('#(\$tpl_text_[\w]+)#i', $variable))
    		{
    			!isset( ${str_replace('$','',$variable)} ) and ${str_replace('$','',$variable)} = '';
    			print Form::textarea(str_replace('$', '', $variable),
                    str_replace(array('\"',"\'"),array('"',"'"),${str_replace('$','',$variable)})
                    , array('style'=>'width:100%'));
    		}
    	}
    }


    ?>
</div>
<div class="span8">
	<h3><?php print __('types.13.preview') ?></h3>
	<p>
		<?php if($selected_template === 0) print __('types.13.info'); ?>
		<?php if($selected_template !== 0): ?>

		<iframe style="width:100%;height:500px;border:1px solid black;" src="<?php print Uri::create('admin/content/1/edit/' . Uri::segment(5) . '/type/13/preview') ?>"></iframe>

		<?php endif; ?>
	</p>
</div>
<div class="span16">
	<div class="actions">
		<?php print Form::submit('confirm',__('types.13.submit'),array('class'=>'btn primary')); ?>
		<?php print Form::submit('back',__('types.13.back'),array('class'=>'btn secondary')); ?>
	</div>
</div>
<?php print Form::close(); ?>

<script type="text/javascript">
tinyMCE.init({
  theme : "advanced",
  mode : "textareas",
  theme_advanced_toolbar_location : "top",
  theme_advanced_buttons1 : "formatselect,fontsizeselect,bold,italic,underline,strikethrough,forecolor,separator,justifyleft,justifycenter,justifyright,justifyfull",
  theme_advanced_buttons2 : "outdent,indent,blockquote,link,numlist,bullist,code,fullscreen",
  plugins : 'safari,inlinepopups,fullscreen',
  theme_advanced_buttons1_add : "emotions",
  language : '<?php print Session::get('lang_prefix') ?>'
});
</script>