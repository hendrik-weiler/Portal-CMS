<div class="flvvideoplayer row">
	<?php print Form::open(array('action'=>'admin/content/' . Uri::segment(3) . '/edit/' . Uri::segment(5) . '/type/14/edit','enctype'=>'multipart/form-data')) ?>
	<script type="text/javascript">
	var _save_skin_url = "<?php print Uri::create('admin/content/' . Uri::segment(3) . '/edit/' . Uri::segment(5) . '/type/14/save/skin') ?>";
	var _dialog_save_headline = "<?php print __('types.14.dialog_save_headline') ?>";
	var _dialog_save_confirm = "<?php print __('types.14.dialog_save_confirm') ?>";
	var _dialog_save_cancel = "<?php print __('types.14.dialog_save_cancel') ?>";
	</script>
	<?php print Form::hidden('skin_saved',0) ?>
	<div class="row">
		<div class="span10">
			<div class="row preview">
				<h2 style="border-bottom:1px solid black"><?php print __('types.14.preview'); ?></h2>
				<iframe src="<?php print 'admin/content/' . Uri::segment(3) . '/edit/' . Uri::segment(5) . '/type/14/preview' ?>"></iframe>
			</div>
			<div class="row">
				<br />
				<p><strong><?php print __('types.14.video_path') ?></strong><br /><?php print $video_path  ?></p>
				<p><strong><?php print __('types.14.skin_path') ?></strong><br /><?php print $skin_path  ?></p>
			</div>
		</div>
		<div class="span6">
		  <div class="clearfix">
		   <?php print Form::label(__('types.14.label')); ?>
		   <div class="input">
		      <?php print Form::input('label',$label) ?>
		    </div>
		  </div>
		  <div class="clearfix">
		   <?php print Form::label(__('types.14.height')); ?>
		   <div class="input">
		      <?php print Form::input('height',$height) ?>
		    </div>
		  </div>
		  <div class="clearfix">
		   <?php print Form::label(__('types.14.width')); ?>
		   <div class="input">
		      <?php print Form::input('width',$width) ?>
		    </div>
		  </div>	
		  <div class="clearfix">
		   <?php print Form::label(__('types.14.preview_pic')); ?>
		   <div class="input">
		   	  <?php 
		   	  if(isset($video_preview) && !empty($video_preview))
		   	  print Form::submit('video_preview_delete',__('types.14.preview_pic_delete'),array('class'=>'btn secondary')); 
		   	  ?>
		      <?php print Form::file('video_preview') ?>
		    </div>
		  </div>
		  <div class="clearfix">
		   <?php print Form::label(__('types.14.file')); ?>
		   <div class="input">
		      <?php print Form::file('video_file') ?>
		    </div>
		  </div>
		  <div class="clearfix">
		   <?php print Form::label(__('types.14.file_choose')); ?>
		   <div class="input">
		      <?php print Form::select('video_name', $video_name, $videos) ?>
		    </div>
		  </div>	  
		  <div class="clearfix">
		   <?php print Form::label(__('types.14.autoplay')); ?>
		   <div class="input">
		   	  <?php 
		   	  $checked = array();
		   	  $autoplay == 'true' and $checked = array('checked'=>'checked');
		   	  ?>
		      <?php print Form::checkbox('autoplay',1,$checked) ?>
		    </div>
		  </div>
		  <div class="clearfix">
		   <?php print Form::label(__('types.14.autohide')); ?>
		   <div class="input">
		   	  <?php 
		   	  $checked = array();
		   	  $autohide == 'true' and $checked = array('checked'=>'checked');
		   	  ?>
		      <?php print Form::checkbox('autohide',1,$checked) ?>
		    </div>
		  </div>
		  <div class="clearfix">
		   <?php print Form::label(__('types.14.fullscreen')); ?>
		   <div class="input">
		   	  <?php 
		   	  $checked = array();
		   	  $fullscreen == 'true' and $checked = array('checked'=>'checked');
		   	  ?>
		      <?php print Form::checkbox('fullscreen',1,$checked) ?>
		    </div>
		  </div>
		  <div class="clearfix">
		   <?php print Form::label(__('types.14.skin')); ?>
		   <div class="input">
		      <?php print Form::select('skin',$selected_skin,$skins) ?>
		    </div>
		    <div class="row input" style="padding:5px">
			    	<?php print Form::button('load',__('types.14.load'),array('class'=>'btn')) ?>
					<br /><br />
			    	<?php print Form::button('save',__('types.14.save'),array('class'=>'btn')) ?>
		    </div>
		  </div>
		  <div class="well">
		  	  <h3><?php print __('types.14.player_color') ?></h3>
			  <div class="clearfix">
			   <?php print Form::label(__('types.14.color_text')); ?>
			   <div class="input">
			      <?php print Form::input('color_text',$color_text) ?>
			    </div>
			  </div>
			  <div class="clearfix">
			   <?php print Form::label(__('types.14.color_seekbar')); ?>
			   <div class="input">
			      <?php print Form::input('color_seekbar',$color_seekbar) ?>
			    </div>
			  </div>
			  <div class="clearfix">
			   <?php print Form::label(__('types.14.color_loadingbar')); ?>
			   <div class="input">
			      <?php print Form::input('color_loadingbar',$color_loadingbar) ?>
			    </div>
			  </div>
			  <div class="clearfix">
			   <?php print Form::label(__('types.14.color_seekbarbg')); ?>
			   <div class="input">
			      <?php print Form::input('color_seekbarbg',$color_seekbarbg) ?>
			    </div>
			  </div>
			  <div class="clearfix">
			   <?php print Form::label(__('types.14.color_button_out')); ?>
			   <div class="input">
			      <?php print Form::input('color_button_out',$color_button_out) ?>
			    </div>
			  </div>
			  <div class="clearfix">
			   <?php print Form::label(__('types.14.color_button_over')); ?>
			   <div class="input">
			      <?php print Form::input('color_button_over',$color_button_over) ?>
			    </div>
			  </div>
			  <div class="clearfix">
			   <?php print Form::label(__('types.14.color_button_highlight')); ?>
			   <div class="input">
			      <?php print Form::input('color_button_highlight',$color_button_highlight) ?>
			    </div>
			  </div>
		  </div>
		</div>
	</div>
	<div class="row">
		<div class="actions">
			<?php print Form::submit('confirm',__('types.13.submit'),array('class'=>'btn primary')); ?>
			<?php print Form::submit('back',__('types.13.back'),array('class'=>'btn secondary')); ?>
		</div>
	</div>
	<?php print Form::close(); ?>
</div>
<?php print Asset\Manager::get('js->type->flvvideoplayer') ?>