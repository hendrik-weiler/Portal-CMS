<?php 
	if(preg_match('#[0-9]#i',Uri::segment(3)))
		$group_id = Uri::segment(3);
	else
	{
		$site = model_db_site::find(Uri::Segment(4));
		$group_id = $site->group_id;
                if($site == null)
                   Response::redirect('admin/sites');
	}
?>
<div class="row">
	<div class="span8">
		<h3>
			<?php print __('sites.' . $mode . '_header'); ?>
		</h3>

		<?php
			print Form::open(array('action'=>($mode == 'add') ? 'admin/sites/add' : Uri::current(),'class'=>'form_style_1','enctype'=>'multipart/form-data'));
	?>
	<div class="clearfix">
	  <?php print Form::label(__('sites.label')); ?>
	  <div class="input">
	    <?php print Form::input('label',$label,array('style'=>'width:210px;')); ?>
	  </div>
	</div>
    <?php 		
		print Form::hidden('id',$group_id);
		if(Uri::segment(3) == 'edit')
			print Form::hidden('navigation_id',$navigation_id);
		else
			print Form::hidden('navigation_id',0);
	?>
	<div class="clearfix">
	 <?php print Form::label(__('sites.redirect')); ?>
	 <div class="input">
	    <?php print Form::input('redirect',$redirect,array('style'=>'width:210px;')); ?>
	  </div>
	</div>
	<div class="clearfix">
	 <?php print Form::label(__('sites.landingpage')); ?>
	 <div class="input">
             <?php 
            $lprefix = Session::get('lang_prefix');
            
            $lid = model_db_language::prefixToId($lprefix);
            
            $landing_page = model_db_option::getKey('landing_page');
            
            $format = Format::forge($landing_page->value,'json')->to_array();
             $checked = isset($format[$lid]) && $format[$lid] == Uri::segment(4) ? array('checked'=>'checked') : array(); ?>
	    <?php print Form::checkbox('landing_page',1,$checked + array('style'=>'width:210px;')); ?>
	  </div>
	</div>
	<div class="clearfix">
	 <?php print Form::label(__('sites.current_template')); ?>
	 <div class="input">
	    <?php print Form::select('current_template',$current_template,array(
                'default' => __('sites.template_default'),
                __('sites.template_from_folder') => model_db_site::getLayoutFromFolder(),
            ),array('style'=>'width:210px;')); ?>
	  </div>
	</div>
	<div class="clearfix">
	  <label for="xlInput"><?php print __('sites.site_title');  ?></label>
	  <div class="input">
	    <?php print Form::input('site_title',$site_title,array('style'=>'width:210px')); ?>
	  </div>
	</div>
	<div class="clearfix">
	  <label for="xlInput"><?php print __('sites.keywords'); ?></label>
	  <div class="input">
	    <?php print Form::textarea('keywords',$keywords,array('style'=>'width:210px;height:40px;')); ?>
	  </div>
	</div>
	<div class="clearfix">
	  <label for="xlInput"><?php print __('sites.description');  ?></label>
	  <div class="input">
	    <?php print Form::textarea('description',$description,array('style'=>'width:210px;height:60px;')); ?>
	  </div>
	</div>
        <?php print Form::hidden('site_id',$site_id); ?>
<hr />

<div class="clearfix">
  <?php print Form::label(__('navigation.show_in_navigation')); ?>
  <div class="input">
  	<?php $check = empty($show_in_navigation) ? array() : array('checked'=>'checked'); ?>
    <?php print Form::checkbox('show_in_navigation',1,$check); ?>
  </div>
</div>

<div class="clearfix">
	<?php print Form::label(__('navigation.image')); ?>
  <div class="input">
	<?php if(!empty($image)): ?>
	<img src="<?php print $image ?>" />
	<?php endif; ?>
    <?php print Form::file('image'); ?>
  </div>
</div>

<div class="clearfix">
  <?php print Form::label(__('navigation.image_is_shown')); ?>
  <div class="input">
  	<?php if(Uri::segment(3) != 'edit') $image_is_shown = 1; ?>
  	<?php $check = empty($image_is_shown) ? array() : array('checked'=>'checked'); ?>
    <?php print Form::checkbox('image_is_shown',1,$check); ?>
  </div>
</div>

<div class="clearfix">
    <?php print Form::label(__('navigation.nav_group')); ?>
    <div class="input">
    <?php 		
                        $select_data = model_db_navgroup::asSelectBox();

                        print Form::select('group_id',$group_id,$select_data);
                ?>
        </div>
</div>

<?php print Form::label(__('navigation.parent')); ?>
<div class="clearfix">
  <div class="input">
    <?php print Form::select('parent',$parent,$parent_array); ?>
  </div>
</div>
<?php print Form::hidden('id',Uri::segment(3)) ?>

	<div class="actions">
		<?php 
			print Form::submit('submit',__('sites.' . $mode),array('class'=>'btn primary')) . ' ';

			if(Uri::segment(3) == 'edit')
				print '<a class="btn secondary" href="' . Uri::create('admin/navigation/' . $group_id) . '">' . __('news.edit.back') . '</a>';
		 ?>
	</div>
	<?php	print Form::close();	?>
	</div>
	<div class="span7">
		<?php if(Uri::segment(3) == 'edit'): ?>
		<h3>
			<?php print __('sites.content_header'); ?>
		</h3>
	<?php
			print Form::open(array('action'=>'admin/content/add/' . $id,'class'=>'form_style_2'));

			print Form::select('type',0,array(
				__('content.txtcon') => array(
					1 => __('content.type.1'),
					6 => __('content.type.6'),
					7 => __('content.type.7'),
				),
				2 => __('content.type.2'),
				3 => __('content.type.3'),
				4 => __('content.type.4'),
				__('content.cl') => array(
					5 => __('content.type.5'),
					8 => __('content.type.8'),
					9 => __('content.type.9'),
				),
				10 => __('content.type.10'),
                11 => __('content.type.11'),
                //12 => __('content.type.12'),
	 		),array('style'=>'width:210px;'));

			print Form::submit('addContent',__('content.add_button'),array('class'=>'btn')) . ' ';

			print Form::close();
	?>
		<section id="content_list">
			<img id="moveable_content" src="<?php print Uri::create('assets/img/admin/moveable.png') ?>" alt="Moveable">
			<?php
				
				$contents = model_db_content::find()->where('site_id',$id)->order_by(array('sort'=>'ASC'))->get();

				if(empty($contents))
				{
					print '<p>' . __('content.none_available') . '</p>';
				}
				else
				{
					foreach($contents as $content)
						writeRowContent($content,$id);
				}

			?>
		</section>
		</div>

		<?php endif; ?>
	</div>
		<?php

		function writeRow($nav,$class='sites_entry')
		{

			

			print '<div id="' . $nav->id . '" class="list_entry clearfix ' . $class . '">';

			print '<span>';

			print $nav->label;

			print '</span>';


			print '<div>';

			if($nav->navigation_id != 0)
				print '<a target="preview" href="' . Controller_Pages_Pages::generateUrl($nav->id) . '">' . __('content.preview') . '</a> ';			

			print '<a href="' . Uri::create('admin/sites/edit/' . $nav->id) . '">' . __('constants.edit') . '</a> ';

			if($nav->navigation_id == 0)
			print '<a class="delete" href="' . Uri::create('admin/sites/delete/' . $nav->id) . '">' . __('constants.delete') . '</a>';

			print '</div>';

			print '</div>';
		}

		function writeRowContent($nav,$id,$class='content_entry')
		{

			$style = model_db_content::genStyleFromClassname($nav->classname);

			print '<div id="' . $nav->id . '" class="list_entry clearfix ' . $class . '">';

			print '<span>';

			print '<strong>' . __('content.type.' . $nav->type) . '</strong>: <br />';

			if(in_array($nav->type,array(1,2,3,6,7,10,11,12)))
			{
				print empty($nav->label) ? '&nbsp;' : $nav->label;
			}
			else
			{
				print '&nbsp;';
			}

			print '</span>';

			print '<div class="split_box">';

			print $style->type . ' %';

			print Form::checkbox('split_box[]',1,array('data-content-id'=>$nav->id));

			print '</div>';

			print '<div>';

			if(in_array($nav->type,array(1,2,3,5,6,7,8,9,10,11,12)))
				print '<a href="' . Uri::create('admin/content/' . $id . '/edit/' . $nav->id . '/type/' . $nav->type) . '">' . __('constants.edit') . '</a> ';
				
			print '<a class="delete" href="' . Uri::create('admin/content/delete/' . $nav->id) . '">' . __('constants.delete') . '</a>';

			print '</div>';

			print '</div>';
		}
	?>
</div>
<script type="text/javascript">
var _confirm_count_single = "<?php print __('content.confirm_count_single'); ?>";
var _confirm_count_multiple = "<?php print __('content.confirm_count_multiple'); ?>";
</script>
<div class="split_box_container">
	<div class="entries-text">
		0 <?php print __('content.confirm_count_multiple'); ?>
	</div>
	<?php 
	print Form::select('split_box_choice',0,array(
		4 => '25%',
		3 => '33%',
		2 => '50%',
		1 => '75%',
		0 => '100%'
	)); 

	print Form::button('split_box_choice_button',__('content.confirm'),array('class'=>'btn'));
	?>
</div>
<script type="text/javascript" src="<?php print Uri::create('assets/js/split_box.js') ?>"></script>