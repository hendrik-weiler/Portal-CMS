<script type="text/javascript" src="<?php print Uri::create('assets/js/tiny_mce/tiny_mce.js'); ?>"></script>
<script type="text/javascript" src="<?php print Uri::create('assets/js/siteselector/siteselector.js') ?>"></script>
<div class="col-xs-1 backbutton">
    <label>
    		<?php print Form::open(array('action'=>'admin/content/' . Uri::segment(3) . '/edit/' . Uri::segment(5) . '/type/13/edit','enctype'=>'multipart/form-data')) ?>
        <?php print Form::submit('back',__('types.15.back'),array('class'=>'hide')); ?>
        <img src="<?php print Uri::create('assets/img/icons/arrow_left.png') ?>" alt=""/>
        <?php print Form::close(); ?>
    </label>
</div>
<div class="col-xs-11 vertical graycontainer globalmenu">
    <div class="description">
      <?php print Form::label(__('content.type.13')); ?>
    </div>
    <?php if($currentFile==''): ?>
    <div class="tplnormalcontent">
    <div class="list">
    <div class="col-xs-6 padding15">
    		<?php print Form::open(array('action'=>'admin/content/' . Uri::segment(3) . '/edit/' . Uri::segment(5) . '/type/13/edit','enctype'=>'multipart/form-data')) ?>
        <div class="picturemanager-button button"><?php print Lang::get('picturemanager_button') ?></div>
        <?php print Form::label(__('types.13.template')); ?>
        <div class="input">
            <?php print Form::select('template', $selected_template, $templates); ?>
        </div>
    <?php
    $variableStorage = array();
    if(!empty($template_variables))
    {
    	foreach ($template_variables as $variable) 
    	{
        if(in_array($variable, $variableStorage)) continue;

        $variableStorage[] = $variable;

        $split_var = explode('_', $variable);
        print '<h5>' . $split_var[count($split_var)-1] . ' <span style="font-size:12px;color:#aaa">( ' . $variable . ' )</span></h5>';
    		if(preg_match('#(\$tpl_file_[\w]+)#i', $variable))
    		{
    			print Form::file(str_replace('$', '', $variable));
    		}

    		if(preg_match('#(\$tpl_text_[\w]+)#i', $variable))
    		{
    			!isset( ${str_replace('$','',$variable)} ) and ${str_replace('$','',$variable)} = '';
    			print Form::textarea(str_replace('$', '', $variable),
                    str_replace(array('\"',"\'"),array('"',"'"),${str_replace('$','',$variable)})
                    , array('style'=>'width:100%','class'=>'mceEditor'));
    		}

        if(preg_match('#(\$tpl_rawtext_[\w]+)#i', $variable))
        {
          !isset( ${str_replace('$','',$variable)} ) and ${str_replace('$','',$variable)} = '';
          print Form::textarea(str_replace('$', '', $variable),
                    str_replace(array('\"',"\'"),array('"',"'"),${str_replace('$','',$variable)})
                    , array('style'=>'width:100%'));
        }
        if(preg_match('#(\$tpl_siteselector_[\w]+)#i', $variable))
        {
          !isset( ${str_replace('$','',$variable)} ) and ${str_replace('$','',$variable)} = '';
          $name = str_replace('$','',$variable);
          print '<button class="button siteselector_button_' . $name . ' ' . $name . '">' . __('siteselector_button') . '</button>';
          $label = '&nbsp;';
          $navigation = model_db_navigation::find(${str_replace('$','',$variable)});
          if(!is_object($navigation)) {
            $navigation = new stdClass;
            $navigation->label = '';
          } 
          else {
            $label = $navigation->label;
          }
          print '<div class="siteselector_selected_item siteselector_selected_item_' . $name . '">' . $label .'</div>';
          print '<script>var siteselector_' . $name . ' = new pcms.siteselector($(".siteselector_button_' . $name . '"),{
            title : "' . __('siteselector.title') . '",
            text : "' . __('siteselector.text') . '",
            confirm : "' . __('siteselector.confirm') . '",
            cancel : "' . __('siteselector.cancel') . '"
          });siteselector_' . $name . '.render();siteselector_' . $name . '.onConfirm = function(dialog_helper, event) {
            $("input[name=' . $name . ']").attr("value",event.option_id);
            $(".siteselector_selected_item_' . $name . '").html(event.option_label);
            dialog_helper.cancel_dialog();
          };</script>';
          print Form::hidden($name, ${str_replace('$','',$variable)});
        }
    	}
    }


    ?>
</div>
</div>
<div class="col-xs-6">
	<h3><?php print __('types.13.preview') ?></h3>
	<p>
		<?php if($selected_template === 0) print __('types.13.info'); ?>
		<?php if($selected_template !== 0): ?>

		<iframe style="width:100%;height:500px;border:1px solid black;" src="<?php print Uri::create('admin/content/1/edit/' . Uri::segment(5) . '/type/13/preview') ?>"></iframe>

		<?php endif; ?>
	</p>
</div>

<div class="col-xs-12 padding15">
    <?php print Form::submit('confirm',__('types.13.submit'),array('class'=>'button')); ?>
</div>
<?php print Form::close(); ?>


</div>
<?php endif; ?>
<style>
  #closeetplditor,
  #openetplditor {
    font-size: 26px;
    text-decoration: none;
    color: red;
  }
</style>
<?php if($currentFile==''): ?>
<a id="openetplditor" href="#">Open Editor</a>
<?php else: ?>
<a id="closeetplditor" href="#">Close Editor</a>
<?php endif; ?>

<div class="tpleditor" style="display: <?php ($currentFile=='') ? print 'none' : print 'block' ?>;">
<link rel="stylesheet" href="<?php print Uri::create('assets/css/codemirror/codemirror.css') ?>">
<link rel="stylesheet" href="<?php print Uri::create('assets/css/codemirror/night.css') ?>">
<script type="text/javascript" src="<?php print Uri::create('assets/js/codemirror/codemirror.js'); ?>"></script>
<script type="text/javascript" src="<?php print Uri::create('assets/js/codemirror/xml.js') ?>"></script>
<script type="text/javascript" src="<?php print Uri::create('assets/js/codemirror/css.js') ?>"></script>
<script type="text/javascript" src="<?php print Uri::create('assets/js/codemirror/htmlmixed.js') ?>"></script>
<script type="text/javascript" src="<?php print Uri::create('assets/js/codemirror/php.js') ?>"></script>
<div class="col-xs-6 padding15">
<h1>Editor</h1>
	<div class="list templatelist">
		<ul>
			<?php
			$layout = model_db_option::getKey('layout');
			$path = APPPATH . '../../layouts/'.$layout->value.'/content_templates/custom';
			$customFiles = scandir($path);
			?>
			<?php foreach($customFiles as $file): ?>
				<?php if($file != '.' and $file != '..'): ?>
				<li>
					<a <?php $currentFile==$file and print 'class="active"'; ?> href="<?php print Uri::create('/admin/content/3/edit/' . Uri::segment(5) . '/type/13/file/'. $file); ?>"><?php print $file; ?></a>
				</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php print Form::open(array('action'=>'admin/content/' . Uri::segment(3) . '/edit/' . Uri::segment(5) . '/type/13/file/add')) ?>
	<input placeholder="Filename" type="text" name="filename">
	<?php print Form::submit('back',__('shop.settings.add_shipping'),array('class'=>'button')); ?>
	<?php print Form::close(); ?>

</div>
<?php if($currentFile!=''): ?>
<div class="col-xs-6 padding15">
<h1>&nbsp;</h1>
<?php print Form::open(array('action'=>'admin/content/' . Uri::segment(3) . '/edit/' . Uri::segment(5) . '/type/13/file/'.Uri::segment(9).'/save')) ?>
<input placeholder="Filename" type="text" name="filename" value="<?php print $currentFile ?>">
<textarea id="code" name="code"><?php print $code ?></textarea>
<?php print Form::submit('back',__('types.13.submit'),array('class'=>'button')); ?>
<?php print Form::submit('delete',__('shop.groups.delete'),array('class'=>'button')); ?>
<?php print Form::close(); ?>
</div>
<?php endif; ?>

</div>
</div>
<script>

$('#openetplditor').click(function(e) {
  var loc = window.location.href;
  var fsplit = loc.split('/file');
  e.preventDefault();
   $('.tpleditor').show();
   $('.tplnormalcontent').hide();
   $(this).html('Close Editor');
   if(fsplit.length==1) {
    window.location.href = loc+"/file/"+$('.templatelist a:eq(0)').html();
   }
});

$('#closeetplditor').click(function(e) {
  var loc = window.location.href;
  var fsplit = loc.split('/file');

  $('.tpleditor').hide();
  $('.tplnormalcontent').show();
  $(this).html('Open Editor');
   if(fsplit.length==2) {
    window.location.href = fsplit[0];
   }
});
  
  <?php if($currentFile!=''): ?>

  var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
    lineNumbers: true,
    matchBrackets: true,
    mode: "text/html",
    indentUnit: 4,
    indentWithTabs: true
  });
  editor.on('keyup', function(cm) {
  	cm.save();
  });
  <?php endif; ?>
</script>
<script type="text/javascript">
var picturemanager = new pcms.picturemanager();
picturemanager.build_button('.picturemanager-button');

tinyMCE.init({
  theme : "advanced",
  editor_selector : "mceEditor",
  mode : "textareas",
  theme_advanced_toolbar_location : "top",
  theme_advanced_buttons1 : "formatselect,fontsizeselect,bold,italic,underline,strikethrough,forecolor,separator,justifyleft,justifycenter,justifyright,justifyfull",
  theme_advanced_buttons2 : "outdent,indent,blockquote,link,numlist,bullist,code,sourcecode,autolinkvar,fullscreen",
  plugins : 'safari,inlinepopups,fullscreen,codeextras',
  theme_advanced_buttons1_add : "emotions",
  language : '<?php print Session::get('lang_prefix') ?>'
});
</script>