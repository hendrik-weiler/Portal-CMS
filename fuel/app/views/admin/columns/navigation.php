<?php

	function writeRow($nav,$class='list_entry')
	{
		print '<section id="' . $nav->id . '" class="list_entry clearfix ' . $class . '">';

		print '<span>';

		print $nav->label;

		print '</span>';


		print '<div>';

		$site = model_db_site::find('first',array(
			'where' => array('navigation_id'=>$nav->id)
		));

		$has_sub = model_db_navigation::find('first',array(
			'where' => array('parent'=>$nav->id)
		));

		if(!is_object($has_sub))
			print '<a target="preview" href="' . Controller_Pages_Pages::generateUrl($site->id) . '">' . __('content.preview') . '</a> | ';

		if(is_object($has_sub))
			print '<a href="' . Uri::create('admin/navigation/edit/' . $nav->id) . '">' . __('constants.edit') . '</a> | ';
		else
			print '<a href="' . Uri::create('admin/sites/edit/' . $site->id) . '">' . __('constants.edit') . '</a> | ';

		print '<a class="delete" href="' . Uri::create('admin/navigation/delete/' . $nav->id) . '">' . __('constants.delete') . '</a>';

		print '</div>';

		print '</section>';
	}

?>



<script type="template" id="addnav_tpl">
	<li>
		<?php print Form::input('group_title','',array('class'=>'medium')); ?>
		<a href="#" id="submit_group" class="btn success">O</a>
		<a href="#" id="submit_cancel" class="btn error">X</a>
	</li>
</script>

<script type="template" id="editnav_tpl">
	<li>
		<?php print Form::input('group_title','|title|',array('class'=>'medium')); ?>
		<a href="#" id="rename_group" class="btn success">O</a>
	</li>
</script>

<script type="template" id="new_nav_tpl">
	<li>
	  <a href="|url|">|title|</a>
	</li>
</script>

<h3>
	<?php print __('navigation.' . $mode . '_header'); ?>
</h3>

<ul id="groups" class="tabs">
	<?php
		$navi_groups = model_db_navgroup::find('all');
		if(!empty($navi_groups)):
		foreach($navi_groups as $group):
	?>
	<li id="<?php print $group->id; ?>" <?php print Uri::segment(3) == $group->id ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/navigation/' . $group->id) ?>"><?php print $group->title ?></a></li>
	<?php 
			endforeach;
			endif; 
	?>
	<li><a id="addnav" href="#">+</a></li>
</ul>

<?php
	print Form::open(array('action'=>($mode == 'add') ? 'admin/navigation/add' : Uri::current(),'class'=>'form_style_1','enctype'=>'multipart/form-data'));
?>

<div class="clearfix">
	<img id="rightclick_navigation" src="<?php print Uri::create('assets/img/admin/rightclick.png') ?>" alt="Rightclickable">
  <?php print Form::label(__('navigation.label')); ?>
  <div class="input">
    <?php print Form::input('label',$label); ?>
  </div>
</div>

<div class="clearfix">
  <?php print Form::label(__('navigation.show_in_navigation')); ?>
  <div class="input">
  	<?php if(Uri::segment(3) != 'edit') $show_in_navigation = 1; ?>
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

<?php if(Uri::segment(3) == 'edit'): ?>
<div class="clearfix">
    <?php print Form::label(__('navigation.nav_group')); ?>
    <div class="input">
    <?php 		
                        $select_data = model_db_navgroup::asSelectBox();

                        print Form::select('group_id',$group_id,$select_data);
                ?>
        </div>
</div>
<?php endif; ?>


<?php if(Uri::segment(3) == 'edit' && $show_sub_field): ?>
<?php print Form::label(__('navigation.show_sub')); ?>
<div class="clearfix">
  <div class="input">
    <?php print Form::select('show_sub',$show_sub, array(
    	0 => __('navigation.show_sub_list.none'),
    	1 => __('navigation.show_sub_list.left'),
    	2 => __('navigation.show_sub_list.right'),
    )); ?>
  </div>
</div>
<?php endif; ?>

<?php print Form::label(__('navigation.parent')); ?>
<div class="clearfix">
  <div class="input">
    <?php print Form::select('parent',$parent,$parent_array); ?>
  </div>
</div>
<?php print Form::hidden('id',Uri::segment(3)) ?>
<div class="actions">
	<?php print Form::submit('submit',__('navigation.' . $mode),array('class'=>'btn primary')); ?>
	<?php if(Uri::segment(3) == 'edit'): ?>
	<a class="btn secondary" href="<?php print Uri::create('admin/navigation/' . $group_id) ?>"><?php print __('constants.back') ?></a>
	<?php endif; ?>
</div>

<?php	print Form::close(); ?>

<?php if(Uri::segment(3) != 'edit'): ?>


<h3>
	<?php print __('navigation.current_entries'); ?>
</h3>
<section id="navigation_list">
	<img id="moveable_navigation" src="<?php print Uri::create('assets/img/admin/moveable.png') ?>" alt="Moveable">
<?php
	$navi_points = model_db_navigation::find('all',array(
		'where' => array('parent'=>'0','group_id'=>Uri::segment(3)),
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
?>
</section>

<div class="nav_menu">
	<li><a href="#"><?php print __('navigation.menu_rename') ?></a></li>
	<li><a href="#"><?php print __('navigation.menu_delete') ?></a></li>
</div>

<?php endif; ?>