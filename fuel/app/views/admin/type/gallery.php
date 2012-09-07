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

                    if(is_dir(LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/cms_template/custom'))
                        $custom_path = LAYOUTPATH . '/' . model_db_option::getKey('layout')->value . '/cms_template/custom';
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
		<h3>
			<?php print __('types.3.image_header') ?>
		</h3>
		<p>
			<?php
				if(empty($pictures))
				{
					print __('types.3.no_entries');
				}
				else
				{
					foreach($pictures as $picture)
					{
						print '<div class="left">';

						print '<div class="clearfix">';
						print '<img class="left" src="' . Uri::create('uploads/' . Session::get('lang_prefix') . '/gallery/' . $id . '/thumbs/' . $picture) . '" />';
						print '<a title="uploads/' . Session::get('lang_prefix') . '/gallery/' . $id . '/thumbs/' . $picture . '" class="pic_delete left" href="' . Uri::create('admin/content/gallery/delete/') . '">' . __('constants.delete') . '</a>';
						print '</div>';
						print '</div>';
					}
				}
			?>
		</p>
	</div>
</div>