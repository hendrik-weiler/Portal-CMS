<?php 
	if(preg_match('#[0-9]#i',Uri::segment(3)))
		$group_id = Uri::segment(3);
	else
	{
		$site = model_db_site::find(Uri::Segment(4));
		$group_id = $site->group_id;
                if($site == null)
                   Response::redirect('admin/sites');

        $nav = model_db_navigation::find($site->navigation_id);

        $subpointbackaddition = "";
        if($nav->parent != 0) {
            $subpointbackaddition = "/" . $nav->parent;
        }
	}
?>
<div class="row">
    <div class="col-xs-1 backbutton">
        <a href="<?php print Uri::create('admin/navigation/' . $group_id . $subpointbackaddition) ?>">
            <img src="<?php print Uri::create('assets/img/icons/arrow_left.png') ?>" alt=""/>
        </a>
    </div>
    <div class="col-xs-5 vertical graycontainer globalmenu">
        <div class="description">
            <h3>
                <?php print __('sites.' . $mode . '_header'); ?>
            </h3>
        </div>
        <div class="list padding15">
		<?php
			print Form::open(array('action'=>($mode == 'add') ? 'admin/sites/add' : Uri::current(),'class'=>'form_style_1','enctype'=>'multipart/form-data'));
	?>
	<div class="clearfix">
	  <?php print Form::label(__('sites.label')); ?>
	  <div class="input">
	    <?php print Form::input('label',$label); ?>
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
	    <?php print Form::input('redirect',$redirect); ?>
	  </div>
	</div>
    <?php if($got_sub_points): ?>
        <?php print Form::label(__('navigation.show_sub')); ?>
        <br/><br/>
        <?php print Form::select('show_sub',$show_sub, array(
            0 => __('navigation.show_sub_list.none'),
            1 => __('navigation.show_sub_list.left'),
            2 => __('navigation.show_sub_list.right'),
        )); ?>
        <br/><br/>
    <?php else: ?>
    <a class="more-options" href="#"><?php print __('constants.more_options') ?></a>
    <a class="less-options" href="#"><?php print __('constants.less_options') ?></a>
    <?php endif; ?>
    <div class="more-options-box">

	<div class="clearfix">
	 <?php print Form::label(__('sites.landingpage')); ?>
	 <div class="input">
             <?php 
            $lprefix = Session::get('lang_prefix');
            
            $lid = model_db_language::prefixToId($lprefix);

            $landing_page = model_db_option::getKey('landing_page');
            $landing_page->value == '' and $landing_page->value = '[]';
            
            $format = Format::forge($landing_page->value,'json')->to_array();
             $checked = isset($format[$lid]) && $format[$lid] == Uri::segment(4) ? array('checked'=>'checked') : array(); ?>
	    <?php print Form::checkbox('landing_page',1,$checked); ?>
	  </div>
	</div>
	<div class="clearfix">
	 <?php print Form::label(__('sites.current_template')); ?>
	 <div class="input">
	    <?php print Form::select('current_template',$current_template,array(
                'default' => __('sites.template_default'),
                __('sites.template_from_folder') => model_db_site::getLayoutFromFolder(),
            )); ?>
	  </div>
	</div>
	<div class="clearfix">
	  <label for="xlInput"><?php print __('sites.site_title');  ?></label>
	  <div class="input">
	    <?php print Form::input('site_title',$site_title); ?>
	  </div>
	</div>
	<div class="clearfix">
	  <label for="xlInput"><?php print __('sites.keywords'); ?></label>
	  <div class="input">
	    <?php print Form::textarea('keywords',$keywords); ?>
	  </div>
	</div>
	<div class="clearfix">
	  <label for="xlInput"><?php print __('sites.description');  ?></label>
	  <div class="input">
	    <?php print Form::textarea('description',$description); ?>
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

<?php print Form::label(__('navigation.parent')); ?>
<div class="clearfix">
  <div class="input">
    <?php 
    if(empty($parent_array))
    {
    	Response::redirect('admin/navigation');
    	exit;
    }
    print Form::select('parent',$parent,$parent_array); 
    ?>
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

<hr />

<div class="clearfix">
  <?php print Form::label(__('navigation.description')); ?>
  <div class="input">
    <?php print Form::textarea('navi_description',$navi_description,array('style'=>'width:100%;height:120px;')); ?>
  </div>
</div>

<div class="clearfix">
	<?php print Form::label(__('navigation.image')); ?>
  <div class="input">
	<?php if($image_exists): ?>
	<img src="<?php print $image ?>" />
        <a href="<?php print Uri::create('admin/sites/edit/' . $site->id . '/delete/image') ?>"><img src="<?php print Uri::create('assets/img/icons/delete.png') ?>"></a>
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
  <?php print Form::label(__('navigation.use_default_styles')); ?>
  <div class="input">
  	<?php $check = empty($use_default_styles) ? array() : array('checked'=>'checked'); ?>
    <?php print Form::checkbox('use_default_styles',1,$check); ?>
  </div>
</div>

<div class="clearfix">
  <?php print Form::label(__('navigation.text_color')); ?>
  <div class="input">
    <?php print Form::input('text_color',$text_color); ?>
  </div>
</div>

<div class="clearfix">
  <?php print Form::label(__('navigation.background_color')); ?>
  <div class="input">
    <?php print Form::input('background_color',$background_color); ?>
  </div>
</div>

<?php print Form::hidden('id',Uri::segment(3)) ?>
    </div>
	<div class="actions">
		<?php 
			print Form::submit('submit',__('sites.' . $mode),array('class'=>'button')) . ' ';
		 ?>
	</div>
	<?php	print Form::close();	?>
    </div>
	</div>
    <?php if(!$got_sub_points): ?>
    <div class="col-xs-5 vertical graycontainer globalmenu">
        <div class="description">
		<?php if(Uri::segment(3) == 'edit'): ?>
		<h3>
			<?php print __('sites.content_header'); ?>
		</h3>
        </div>
        <div class="list padding15">
            <div class="row">
                <div class="col-xs-9">
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
        13 => __('content.type.13'),
        14 => __('content.type.14'),
        15 => __('content.type.15'),
	 		));
    ?>
                </div>
            <div class="col-xs-3">
            <?php
			print Form::submit('addContent',__('content.add_button'),array('class'=>'button')) . ' ';

			print Form::close();
	?>
            </div>
            </div>
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
        </div>
		<?php endif; ?>
	</div>
<?php endif; ?>
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

			print '<a href="' . Uri::create('admin/sites/edit/' . $nav->id) . '"><img src="' . Uri::create('assets/img/icons/edit.png') . '" /></a> ';

			if($nav->navigation_id == 0)
			print '<a class="delete" href="' . Uri::create('admin/sites/delete/' . $nav->id) . '"><img src="' . Uri::create('assets/img/icons/delete.png') . '" /></a>';

			print '</div>';

			print '</div>';
		}

        function getRowName($nav,$id,$class)
        {
            if(in_array($nav->type,array(1,2,3,6,7,10,11,12,13,14)))
            {
                return empty($nav->label) ? '&nbsp;' : $nav->label;
            }
            else if($nav->type == 15)
            {
                if($nav->label != __('constants.untitled_element')) {

                    $labels = array();
                    $data = explode(',', $nav->label);
                    foreach ($data as $json) {
                        $data = Format::forge($json,'json')->to_array();
                        $prefix = Session::get('lang_prefix');
                        if(isset($data[$prefix])) {
                            $labels[] = $data[$prefix];
                        } else {
                            $labels[] = array_shift($data);
                        }

                    }

                    return implode(', ', $labels);

                } else {
                    return '&nbsp;';
                }
            }
            else
            {
                return '&nbsp;';
            }
        }

		function writeRowContent($nav,$id,$class='content_entry')
		{

			$style = model_db_content::genStyleFromClassname($nav->classname);

			print '<div id="' . $nav->id . '" class="list_entry site_content ' . $class . ' row">';

			print '<div class="col-xs-3">';

			print '<strong>' . __('content.type.' . $nav->type) . '</strong>: <br />';

            print getRowName($nav, $id, $class);

			print '</div>';

			print '<div class="split_box col-xs-4">';

            print '<div class="content_width_container">';

            print '<div class="content_width_size" style="width:' . $style->type . '%"></div>';

            print '<div class="content_width_text">';

            print $style->type . ' %';

            print '</div>';

            print '<div class="content_width_checkbox">';

            print Form::checkbox('split_box[]',1,array('data-content-id'=>$nav->id));

            print '</div>';

            print '</div>';

			print '</div>';

			print '<div class="col-xs-6 options">';

			if(in_array($nav->type,array(1,2,3,5,6,7,8,9,10,11,12,13,14,15)))
				print '<a href="' . Uri::create('admin/content/' . $id . '/edit/' . $nav->id . '/type/' . $nav->type) . '"><img src="' . Uri::create('assets/img/icons/edit.png') . '" /></a> ';

            print '<a href="#"><img class="icon move" src="' . Uri::create('assets/img/icons/arrow_move.png') . '" /></a> ';

			print '<a data-id="' . $nav->id . '" class="delete" href="' . Uri::create('admin/content/delete/' . $nav->id) . '"><img src="' . Uri::create('assets/img/icons/delete.png') . '" /></a>';

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

	print Form::button('split_box_choice_button',__('content.confirm'),array('class'=>'button'));
	?>
</div>
<script type="text/javascript" src="<?php print Uri::create('assets/js/split_box.js') ?>"></script>
<script type="text/javascript">
	var _site_id = "<?php print Uri::segment(4) ?>";


	var dialog = new pcms.dialog('.delete', {
		title : _prompt.header,
		text : _prompt.text,
		confirm : _prompt.ok,
		cancel : _prompt.cancel
	});
	dialog.onConfirm = function(helper, event) {
		var id = $(event.initiator).attr('data-id');
		helper.post_data(_url + 'admin/content/delete/' + id, {});
	}
	dialog.render(); 

	$('input[name=text_color],input[name=background_color]').spectrum({
		color : $(this).attr('value'),
		showButtons: false,
		showInput: true,
		move : function(color) {
			var c = color.toHexString();
			var color = c;

			if(c.length == 4) {
				color = '#';
				for (var i = 0; i < c.length; i++) {
					color += c[i] + c[i];
				};
			}


			$(this).attr('value',color);
		}
	});
</script>
<script type="text/javascript" src="<?php print Uri::create('assets/js/pageedit.js') ?>"></script>