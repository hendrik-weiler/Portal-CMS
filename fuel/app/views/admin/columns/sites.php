<?php 
	if(preg_match('#[0-9]#i',Uri::segment(3)))
		$group_id = Uri::segment(3);
	else
	{
		$site = model_db_site::find(Uri::Segment(4));
		$group_id = $site->group_id;
	}
?>
<div class="row">
	<div class="span8">
		<h3>
			<?php print __('sites.' . $mode . '_header'); ?>
		</h3>

		<?php
			print Form::open(array('action'=>($mode == 'add') ? 'admin/sites/add' : Uri::current(),'class'=>'form_style_1'));
	?>
	<div class="clearfix">
	  <?php print Form::label(__('sites.label')); ?>
	  <div class="input">
	    <?php print Form::input('label',$label,array('style'=>'width:210px;')); ?>
	  </div>
	</div>
	<div class="clearfix">
	  <?php print Form::label(__('sites.navigation_id')); ?>
	  <div class="input">
	    <?php 		
		    $select_data = array(0=>__('constants.not_set'));
				$select_data = $select_data + model_db_navigation::asSelectBox($group_id);
				print Form::hidden('id',$group_id);
				print Form::select('navigation_id',$navigation_id,$select_data,array('style'=>'width:210px;'));
			?>
		</div>
	</div>
	<div class="clearfix">
	  <?php print Form::label(__('sites.nav_group')); ?>
	  <div class="input">
	    <?php 		
		    $select_data = array(0=>__('constants.not_set'));
				$select_data = $select_data + model_db_navgroup::asSelectBox();

				print Form::select('group_id',$group_id,$select_data,array('style'=>'width:210px;'));
			?>
		</div>
	</div>
	<div class="clearfix">
	 <?php print Form::label(__('sites.redirect')); ?>
	 <div class="input">
	    <?php print Form::input('redirect',$redirect,array('style'=>'width:210px;')); ?>
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
	<div class="actions">
		<?php 
			print Form::submit('submit',__('sites.' . $mode),array('class'=>'btn primary')) . ' ';

			if(Uri::segment(3) == 'edit')
				print '<a class="btn secondary" href="' . Uri::create('admin/sites') . '">' . __('news.edit.back') . '</a>';
		 ?>
	</div>

	<?php	print Form::close();	?>
			
		<?php if(Uri::segment(3) == 'edit'): ?>
		<img id="moveable_content" src="<?php print Uri::create('assets/img/admin/moveable.png') ?>" alt="Moveable">
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
	 		),array('style'=>'width:210px;'));

			print Form::submit('addContent',__('content.add_button'),array('class'=>'btn')) . ' ';

			print Form::close();
	?>
		<section id="content_list">
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
		<?php endif; ?>
	</div>
	<div class="span7">
		<h3>
			<?php print __('sites.current_entries'); ?>
		</h3>

		<ul id="groups" class="tabs">
			<?php
				$navi_groups = model_db_navgroup::find('all');
				if(!empty($navi_groups)):
				foreach($navi_groups as $group):
			?>
			<li id="<?php print $group->id; ?>" <?php print $group_id == $group->id ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/sites/' . $group->id) ?>"><?php print $group->title ?></a></li>
			<?php 
					endforeach;
					endif; 
			?>
		</ul>
		
		<section id="site_list">
	<?php
		$rights = model_permission::getNavigationRights();

		$navigations = model_db_navigation::asSelectBox($group_id);
		$navigations = array(0=>__('constants.not_set')) + $navigations;
	if(empty($navigations))
	{
		print __('sites.no_entries');
	}
	else
	{

		foreach($navigations as $key => $navi)
		{
			if(is_array($navi))
			{
				$_nav = model_db_navigation::find('first',array(
					'where' => array('label'=>$key)
				));

				if(!in_array($_nav->id,$rights['data']) && !$rights['admin'])
					continue;

				print '<h4>' . $key . '</h4><blockquote>';

				foreach($navi as $subKey => $subNavi)
				{
					if(!in_array($subKey,$rights['data']) && !$rights['admin'])
						continue;
					print '<h4>' . $subNavi . '</h4>';

					$sites = model_db_site::find()->where('navigation_id',$subKey)->order_by(array('sort'=>'ASC'))->get();
					foreach($sites as $site)
						writeRow($site,'sites_entry_sub');
				}

				print '</blockquote>';
			}
			else
			{
				if(!in_array($key,$rights['data']) && !$rights['admin'] && $key != 0)
					continue;

				print '<h4>' . $navi . '</h4><blockquote>';
				$sites = model_db_site::find()->where('navigation_id',$key)->order_by(array('sort'=>'ASC'))->get();
				foreach($sites as $site)
					writeRow($site);

				print '</blockquote>';
			}
		}
	}

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
			print '<a class="delete" href="' . Uri::create('admin/sites/delete/' . $nav->id) . '">' . __('constants.delete') . '</a>';

			print '</div>';

			print '</div>';
		}

		function writeRowContent($nav,$id,$class='content_entry')
		{
			print '<div id="' . $nav->id . '" class="list_entry clearfix ' . $class . '">';

			print '<span>';

			print '<strong>' . __('content.type.' . $nav->type) . '</strong>: ';

			if(in_array($nav->type,array(1,2)))
			print $nav->label;

			print '</span>';


			print '<div>';

			if(in_array($nav->type,array(1,2,3,5,6,7,8,9,10)))
				print '<a href="' . Uri::create('admin/content/' . $id . '/edit/' . $nav->id . '/type/' . $nav->type) . '">' . __('constants.edit') . '</a> ';
				
			print '<a class="delete" href="' . Uri::create('admin/content/delete/' . $nav->id) . '">' . __('constants.delete') . '</a>';

			print '</div>';

			print '</div>';
		}
	?>
		</section>
	</div>
</div>