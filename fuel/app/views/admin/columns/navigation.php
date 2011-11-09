<h3>
	<?php print __('navigation.' . $mode . '_header'); ?>
</h3>

<?php
	print Form::open(array('action'=>($mode == 'add') ? 'admin/navigation/add' : Uri::current(),'class'=>'form_style_1'));
?>
<div class="clearfix">
  <label for="xlInput"<?php print Form::label(__('navigation.label')); ?></label>
  <div class="input">
    <?php print Form::input('label',$label); ?>
  </div>
</div>
<div class="clearfix">
  <div class="input">
    <?php print Form::select('parent',$parent,$parent_array); ?>
  </div>
</div>
	
<div class="actions">
	<?php print Form::submit('submit',__('navigation.' . $mode),array('class'=>'btn primary')); ?>
</div>

<?php	print Form::close(); ?>
<h3>
	<?php print __('navigation.current_entries'); ?>
</h3>
<section id="navigation_list">
<?php
	$navi_points = model_db_navigation::find('all',array(
		'where' => array('parent'=>'0'),
		'order_by' => array('sort'=>'ASC')
	));

	$permissions = model_permission::getNavigationRights();

	if(empty($navi_points))
	{
		print __('navigation.no_entries');
	}
	else
	{
		foreach($navi_points as $navipoint)
		{
			$sub_points = model_db_navigation::find('all',array(
				'where' => array('parent'=>$navipoint->id),
				'order_by' => array('sort'=>'ASC')
			));

			if(in_array($navipoint->id,$permissions['data']) || $permissions['admin'])
				writeRow($navipoint);

			if(!empty($sub_points))
			{
				foreach($sub_points as $navi)
				{
					if(in_array($navi->id,$permissions['data']) || $permissions['admin'])
						writeRow($navi,'list_entry_sub');
				}
			}
		}
	}

	function writeRow($nav,$class='list_entry')
	{
		print '<section id="' . $nav->id . '" class="list_entry clearfix ' . $class . '">';

		print '<span>';

		print $nav->label;

		print '</span>';


		print '<div>';

		print '<a href="' . Uri::create('admin/navigation/edit/' . $nav->id) . '">' . __('constants.edit') . '</a> ';
		print '<a class="delete" href="' . Uri::create('admin/navigation/delete/' . $nav->id) . '">' . __('constants.delete') . '</a>';

		print '</div>';

		print '</section>';
	}
?>
</section>