<div class="row">
	<div id="gallery_3_form" class="span9">
	<h3>
		<?php print __('types.3.header') ?>
	</h3>
	<?php
		print Form::open(array('action'=>Uri::current(),'class'=>'form_style_1','enctype'=>'multipart/form-data'));
	?>
	  <div class="clearfix">
	   <?php print Form::label(__('types.3.label')); ?>
	   <div class="input">
	      <?php print Form::input('label',$label); ?>
	    </div>
	  </div>
	    <div class="clearfix">
	   <div class="input">
	      <?php
					$styling = array('style'=>'display:inline;width:auto;');

					$check = ($mode == 'lightbox') ? array('checked'=>'checked') : array();
					print Form::radio('mode','lightbox',$check + $styling) . ' Lightbox<br />';

					$check = ($mode == 'slideshow') ? array('checked'=>'checked') : array();
					print Form::radio('mode','slideshow',$check + $styling) . ' Slideshow<br />';

					print '<div class="well">';
					$mode = explode('/',$mode);
					$check = ($mode[0] == 'custom') ? array('checked'=>'checked') : array();
					print Form::radio('mode','custom',$check + $styling) . ' ' . __('types.3.custom') . '<br />';

					$custom = array();
					$custom['select'] = array();
					$custom['index'] = 0;

                    if(is_dir(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/custom'))
                        $custom_path = LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/content_templates/custom';
                    else
						$custom_path = APPPATH . 'views/public/template/custom';

					foreach(File::read_dir($custom_path,1) as $key => $file)
					{
						$file = str_replace('.php','',$file);

						$custom['select'][$file] = $file;

						if($file == $customFile)
							$custom['index'] = $file;
					}

					print Form::select('nr',$custom['index'],$custom['select']);

					print '</div>';
	      ?>
	    </div>
	  </div>
	    <div class="clearfix">
	   <?php print Form::label(__('types.3.description')); ?>
	   <div class="input">
	      <?php print Form::textarea('text',$text,array('style'=>'width:300px;height:100px')); ?>
	    </div>
	  </div>
		<div class="actions">
			<?php print Form::submit('submit',__('types.3.submit'),array('class'=>'btn primary')); ?>
		</div>

		<?php print '<h3>' . __('types.3.upload') . '</h3>'; ?>

	   <div class="clearfix">
	   <?php print Form::label(__('types.3.label')); ?>
	   <div class="input">
	      <?php
					print Form::file('file[]',array('style'=>'width:300px'));
					print Form::file('file[]',array('style'=>'width:300px'));
					print Form::file('file[]',array('style'=>'width:300px'));
	      ?>
	    </div>
	  </div>
		


	<?php
		print '<div class="actions">';

		print Form::submit('submit',__('types.3.picture_submit'),array('class'=>'btn primary')) . ' ';
		print Form::submit('back',__('types.3.back'),array('class'=>'btn secondary'));

		print '</div>';

		print Form::close();
	?>
	</div>

	<div id="gallery_3_list" class="span6">
		<?php
			$gallery_width  = model_db_option::getKey('gallery_thumbs_width')->value;
			$gallery_height = model_db_option::getKey('gallery_thumbs_height')->value;
		?>
		<h3>
			<?php print __('types.3.image_header') ?>
		</h3>
		<ul class="picture_list" data-id="<?php print $id ?>">
			<?php
				if(empty($pictures))
				{
					print __('types.3.no_entries');
				}
				else
				{
					foreach($pictures as $picture)
					{
						print '<li style="overflow:hidden;">';
						print '<a data-id="' . $id . '" data-src="' . $picture . '" class="gallery_pic_delete" href="' . Uri::create('admin/content/gallery/delete/') . '">' . __('constants.delete') . '</a>';
						print '<img style="width:' . $gallery_width . 'px;height:' . $gallery_height . 'px;" data-src="' . $picture . '" src="' . Uri::create('uploads/' . Session::get('lang_prefix') . '/gallery/' . $id . '/thumbs/' . $picture) . '" />';
						print '</li>';
					}
				}
			?>
		</ul>
	</div>
</div>
<script type="text/javascript">
	var dialog = new pcms.dialog('.gallery_pic_delete', {
		title : _prompt.header,
		text : _prompt.text,
		confirm : _prompt.ok,
		cancel : _prompt.cancel
	});
	dialog.onConfirm = function(helper, event) {
		helper.post_data(_url + 'admin/content/gallery/delete', {
			filename : $(event.initiator).attr('data-src'),
			content_id : $(event.initiator).attr('data-id')
		});
	}
	dialog.render(); 

	$('.picture_list').sortable({
		tolerance : 'pointer',
		update: function(event, ui) {
		var data = [];
		$.each($('.picture_list img').not('.no_gal'),function(key,value) {
			data[key] = $(this).attr('data-src');
		});

		$.post(_url + 'admin/content/gallery/' + $(this).attr('data-id') + '/order/update',{'order' : data});
		}
	});
</script>