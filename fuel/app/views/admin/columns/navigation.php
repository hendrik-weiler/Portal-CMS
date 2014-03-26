<div class="row">
	<div class="col-xs-3 vertical graycontainer globalmenu navigationmenu" style="display: none">
		<div class="description">
			Navigationsgruppen
		</div>
		<div class="list">
			<ul>
			<?php
				$navi_groups = model_db_navgroup::find('all');

				$permissions = model_permission::getNavigationRights();

                $maintitle = '';

				if(empty($navi_groups))
				{
					print '<li class="no_entries">' . __('navigation.no_entries') . '</li>';
				}
				else
				{
					foreach($navi_groups as $navipoint)
					{
							$nav = $navipoint;

							$active = '';
							if(isset($groupid) && $nav->id == $groupid) {
								$active = 'class="active"';
                                $maintitle = $nav->title;
							}

                            print '<li>';
                            print '<div class="options">';
                            print '<div class="options-label" data-id="' . $navipoint->id . '">';
                            print '<a ' . $active . ' href="' . Uri::create("admin/navigation/" . $nav->id) . '">' . $nav->title . '</a>';
                            print '</div>';
                            print '<div class="options-action">';

                            print '<a class="icon edit-group" href="' . Uri::create('admin/sites/edit/' . $nav->id) . '"><img src="' . Uri::create('assets/img/icons/edit.png') . '" alt="" /></a>';
                            print '<a class="icon delete-group" data-id="' . $nav->id . '" class="delete" href="#" alt="" /><img src="' . Uri::create('assets/img/icons/delete.png') . '" alt="" /></a>';
                            print '<a class="icon edit" href="' . Uri::create("admin/navigation/" . $nav->id) . '"><img src="' . Uri::create('assets/img/icons/arrow_right.png') . '" alt="" /></a>';

                            print '</div>';
                            print '</div>';
                            print '</li>';
						}
					}
				?>
			</ul>
            <ul>
                <li>
                    <form method="post" action="<?php print Uri::create('admin/navigation/group/new') ?>">
                        <div class="options navigation_groups">
                            <div class="options-label">
                                <input placeholder="<?php print __('navigation.placeholder_group') ?>" type="text" name="group" />
                            </div>
                            <div class="options-action">
                                <input class="button" type="submit" name="submit" value="<?php print __('navigation.add') ?>"/>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
		</div>
	</div>
    <div class="col-xs-1 open-navigationmenu">
        <img src="<?php print Uri::create('assets/img/icons/arrow_right.png') ?>" alt=""/>
    </div>
	<div class="col-xs-4 vertical graycontainer globalmenu">
		<div class="description">
            <div class="navigationmenu-opened">
                <?php print __('navigation.site_header') . ' ' . $maintitle ?>
            </div>
            <div class="navigationmenu-closed">
                <?php print __('navigation.site_headersimple') ?>
            </div>
		</div>
		<div class="list">
			<ul id="navigation_list">
			<?php
				$navi_points = model_db_navigation::find('all',array(
					'where' => array('parent'=>'0','group_id'=>Uri::segment(3)),
					'order_by' => array('sort'=>'ASC')
				));

                $title = "";

				$permissions = model_permission::getNavigationRights();

				if(empty($navi_points))
				{
					print '<li class="no_entries">' . __('navigation.no_entries') . '</li>';
				}
				else
				{
					foreach($navi_points as $navipoint)
					{
						$nav = $navipoint;
						$sub_points = model_db_navigation::find('all',array(
							'where' => array('parent'=>$navipoint->id),
							'order_by' => array('sort'=>'ASC')
						));

						$site = model_db_site::find('first',array(
							'where' => array('navigation_id'=>$nav->id)
						));

						$has_sub = model_db_navigation::find('first',array(
							'where' => array('parent'=>$nav->id)
						));

						if(in_array($navipoint->id,$permissions['data']) || $permissions['admin']) {

							if(is_object($has_sub)) {
								$url = Uri::create('admin/navigation/edit/' . $nav->id);
							}
							else {
								$url = Uri::create('admin/sites/edit/' . $site->id);
							}
							$delete = Uri::create('admin/navigation/delete/' . $nav->id);

							$active = '';
							if(isset($mainid) && $nav->id == $mainid) {
								$active = 'class="active"';
                                $title = $nav->label;
							}

							print '<li id="' . $navipoint->id . '" class="list_entry">';
                            print '<div class="options">';
                            print '<div class="options-label">';
                            $url = Uri::create("admin/navigation/" . $groupid . "/" . $nav->id);
							print '<a ' . $active . ' href="' . $url . '">' . $nav->label . '</a>';
                            print '</div>';
                            print '<div class="options-action">';

                            print '<a class="icon edit" href="' . Uri::create('admin/sites/edit/' . $nav->id) . '"><img src="' . Uri::create('assets/img/icons/edit.png') . '" alt="" /></a>';
                            print '<a class="icon delete" data-id="' . $nav->id . '" class="delete" href="#" alt="" /><img src="' . Uri::create('assets/img/icons/delete.png') . '" alt="" /></a>';
                            print '<a class="icon move" href="#"><img src="' . Uri::create('assets/img/icons/arrow_move.png') . '" alt="" /></a>';
                            print '<a class="icon edit" href="' . Uri::create('admin/sites/edit/' . $nav->id) . '"><img src="' . Uri::create('assets/img/icons/arrow_right.png') . '" alt="" /></a>';

                            print '</div>';
                            print '</div>';
							print '</li>';
						}
					}
				}
			?>
			</ul>
            <ul>
                <li>
                    <form method="post" action="<?php print Uri::create('admin/navigation/add') ?>">
                        <input type="hidden" name="id" value="<?php print Uri::segment(3) ?>"/>
                        <input type="hidden" name="parent" value="0"/>
                        <div class="options navigation_sites">
                            <div class="options-label">
                                <input placeholder="<?php print __('navigation.placeholder_site') ?>" type="text" name="label" />
                            </div>
                            <div class="options-action">
                                <input class="button" type="submit" name="submit" value="<?php print __('navigation.add') ?>"/>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
		</div>
	</div>
    <?php

    $subnavi_points = model_db_navigation::find('all',array(
        'where' => array('parent'=>Uri::segment(1),'group_id'=>Uri::segment(3)),
        'order_by' => array('sort'=>'ASC')
    ));

    ?>
    <?php if(isset($mainid)): ?>
	<div class="col-xs-4 vertical graycontainer globalmenu">
		<div class="description">
            <?php print __('navigation.subsite_header') . ' ' . $title ?>
		</div>
		<div class="list">
			<ul id="navigation_list2">

			<?php

                $subnavi_points = model_db_navigation::find('all',array(
                    'where' => array('parent'=>$mainid,'group_id'=>Uri::segment(3)),
                    'order_by' => array('sort'=>'ASC')
                ));

				$permissions = model_permission::getNavigationRights();

				if(empty($subnavi_points))
				{
					print '<li class="no_entries">' . __('navigation.no_entries') . '</li>';
				}
				else
				{
					foreach($subnavi_points as $navipoint)
					{
						$nav = $navipoint;
						$sub_points = model_db_navigation::find('all',array(
							'where' => array('parent'=>$navipoint->id),
							'order_by' => array('sort'=>'ASC')
						));

						$site = model_db_site::find('first',array(
							'where' => array('navigation_id'=>$nav->id)
						));

						$has_sub = model_db_navigation::find('first',array(
							'where' => array('parent'=>$nav->id)
						));

						if(in_array($navipoint->id,$permissions['data']) || $permissions['admin']) {

							if(is_object($has_sub)) {
								$url = Uri::create('admin/navigation/edit/' . $nav->id);
							}
							else {
								$url = Uri::create('admin/sites/edit/' . $site->id);
							}
							$delete = Uri::create('admin/navigation/delete/' . $nav->id);

							$active = '';
							if(isset($subid) && $nav->id == $subid) {
								$active = 'class="active"';
							}

                            print '<li id="' . $navipoint->id . '" class="list_entry">';
                            print '<div class="options">';
                            print '<div class="options-label">';
                            print '<a ' . $active . ' href="' . Uri::create('admin/sites/edit/' . $nav->id) . '">' . $nav->label . '</a>';
                            print '</div>';
                            print '<div class="options-action">';

                            print '<a class="icon edit" href="' . Uri::create('admin/sites/edit/' . $nav->id) . '"><img src="' . Uri::create('assets/img/icons/edit.png') . '" alt="" /></a>';
                            print '<a class="icon delete" data-id="' . $nav->id . '" class="delete" href="#" alt="" /><img src="' . Uri::create('assets/img/icons/delete.png') . '" alt="" /></a>';
                            print '<a class="icon move" href="#"><img src="' . Uri::create('assets/img/icons/arrow_move.png') . '" alt="" /></a>';
                            print '<a class="icon edit" href="' . Uri::create('admin/sites/edit/' . $nav->id) . '"><img src="' . Uri::create('assets/img/icons/arrow_right.png') . '" alt="" /></a>';

                            print '</div>';
                            print '</div>';
                            print '</li>';

						}
					}
				}
			?>
			</ul>
            <ul>
                <li>
                    <form method="post" action="<?php print Uri::create('admin/navigation/add') ?>">
                        <input type="hidden" name="id" value="<?php print Uri::segment(3) ?>"/>
                        <input type="hidden" name="parent" value="<?php print Uri::segment(4) ?>"/>
                        <div class="options">
                            <div class="options-label">
                                <input placeholder="<?php print __('navigation.placeholder_site') ?>" type="text" name="label" />
                            </div>
                            <div class="options-action">
                                <input class="button" type="submit" name="submit" value="<?php print __('navigation.add') ?>"/>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
		</div>
	</div>
    <?php endif; ?>
<?php print Asset\Manager::get('js->navigation') ?>