<?php
	print Form::open(array('action'=>'admin/advanced/edit','id'=>'advanced_form','class'=>'form_style_1'));
?>
<div class="col-xs-12 vertical graycontainer globalmenu">
    <div class="description">
        <?php print __('nav.advanced') ?>
    </div>
    <div class="list padding15">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#general"><?php print __('advanced.tabs.general') ?></a></li>
            <li><a data-toggle="tab" href="#seo"><?php print __('advanced.tabs.seo') ?></a></li>
            <li><a data-toggle="tab" href="#accounts"><?php print __('advanced.tabs.accounts') ?></a></li>
            <li><a data-toggle="tab" href="#modules"><?php print __('advanced.tabs.modules') ?></a></li>
            <li><a data-toggle="tab" href="#assets"><?php print __('advanced.tabs.assets') ?></a></li>
            <li><a href="<?php print Uri::create('admin/advanced/layout') ?>"><?php print __('advanced.tabs.layout') ?></a></li>
            <!-- <li><a href="<?php print Uri::create('admin/advanced/update') ?>">Updates</a></li> -->
            <!-- <li><a href="<?php print Uri::create('admin/advanced/import') ?>">Data Import</a></li> -->
        </ul>

        <div class="tab-content">
        <div class="tab-pane fade in active" id="general" style="overflow: hidden">
            <div class="col-xs-6">
                <h3>
                    <?php print __('advanced.header.thumbnails') ?>
                </h3>
                <h4>
                    <?php print __('advanced.subHeader.news') ?>
                </h4>
                <div class="clearfix">
                    <?php print Form::label(__('advanced.thumbs.width')); ?>
                    <div class="input">
                        <?php print Form::input('news_thumbs_width',$news_thumbs_width); ?>
                    </div>
                </div>
                <div class="clearfix">
                    <?php print Form::label(__('advanced.thumbs.height')); ?>
                    <div class="input">
                        <?php print Form::input('news_thumbs_height',$news_thumbs_height); ?>
                    </div>
                </div>
                <h4>
                    <?php print __('advanced.subHeader.gallery') ?>
                </h4>
                <div class="clearfix">
                    <?php print Form::label(__('advanced.thumbs.width')); ?>
                    <div class="input">
                        <?php print Form::input('gallery_thumbs_width',$gallery_thumbs_width); ?>
                    </div>
                </div>
                <div class="clearfix">
                    <?php print Form::label(__('advanced.thumbs.height')); ?>
                    <div class="input">
                        <?php print Form::input('gallery_thumbs_height',$gallery_thumbs_height); ?>
                    </div>
                </div>

                <h4>
                    <?php print __('advanced.subHeader.navi_images') ?>
                </h4>
                <div class="clearfix">
                    <?php print Form::label(__('advanced.thumbs.width')); ?>
                    <div class="input">
                        <?php print Form::input('navigation_image_width',$navigation_image_width); ?>
                    </div>
                </div>
                <div class="clearfix">
                    <?php print Form::label(__('advanced.thumbs.height')); ?>
                    <div class="input">
                        <?php print Form::input('navigation_image_height',$navigation_image_height); ?>
                    </div>
                </div>

            </div>
            <div class="col-xs-6">
                <h3>
                    <?php print __('advanced.header.general') ?>
                </h3>
                <div class="clearfix">
                    <?php print Form::label(__('advanced.header.site_caching')); ?>
                    <div class="input">
                        <?php
                        $checked = array();
                        $value = 1;
                        if($site_caching == 1) $checked = array('checked'=>'checked');
                        ?>
                        <?php print Form::checkbox('site_caching',1, $checked); ?>
                    </div>
                </div>
                <h3>
                    <?php print __('advanced.header.help') ?>
                </h3>
                <div class="clearfix">
                    <?php print Form::label(__('advanced.header.inline_edit')); ?>
                    <div class="input">
                        <?php
                        $checked = array();
                        $value = 1;
                        if($inline_edit == 1) $checked = array('checked'=>'checked');
                        ?>
                        <?php print Form::checkbox('inline_edit',1, $checked); ?>
                    </div>
                </div>

                <h3>
                    <?php print __('advanced.header.news') ?>
                </h3>
                <h4>
                    <?php print __('advanced.subHeader.view') ?>
                </h4>
                <div class="clearfix">
                    <?php print Form::label(__('advanced.news.show_last')); ?>
                    <div class="input">
                        <?php print Form::input('show_last',$show_last); ?>
                    </div>
                </div>
                <div class="clearfix">
                    <?php print Form::label(__('advanced.news.show_max_token')); ?>
                    <div class="input">
                        <?php print Form::input('show_max_token',$show_max_token); ?>
                    </div>
                </div>
                <div class="clearfix">
                    <?php print Form::label(__('advanced.header.show_full_news')); ?>
                    <div class="input">
                        <?php
                        $checked = array();
                        $value = 0;
                        if($show_full_news == 1) $checked = array('checked'=>'checked');
                        ?>
                        <?php print Form::checkbox('show_full_news',1, $checked); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="seo">
            <div class="clearfix">
                <?php print Form::label(__('advanced.seo.analytics_id')); ?>
                <div class="input">
                    <?php print Form::input('analytics_id',$analytics_id); ?>
                </div>
            </div>
            <div class="clearfix">
                <?php print Form::label(__('advanced.seo.robots')); ?>
                <div class="input">
                    <?php print Form::input('robots',$robots); ?>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="modules">

                <h3><?php print __('advanced.modules.description'); ?></h3>
            <br/>
                <?php $check = $module_navigation ? array('checked'=>'checked') : array(); ?>
                <span><?php print __('advanced.modules.navigation'); ?></span>
                <?php print Form::checkbox('module_navigation',1,$check); ?>

                <?php $check = $module_content ? array('checked'=>'checked') : array(); ?>
                <span><?php print __('advanced.modules.content'); ?></span>
                <?php print Form::checkbox('module_content',1,$check); ?>

                <?php $check = $module_seo_head ? array('checked'=>'checked') : array(); ?>
                <span><?php print __('advanced.modules.seo_head'); ?></span>
                <?php print Form::checkbox('module_seo_head',1,$check); ?>

                <?php $check = $module_seo_analytics ? array('checked'=>'checked') : array(); ?>
                <span><?php print __('advanced.modules.seo_analytics'); ?></span>
                <?php print Form::checkbox('module_seo_analytics',1,$check); ?>

                <?php $check = $module_language_switcher ? array('checked'=>'checked') : array(); ?>
                <span><?php print __('advanced.modules.language_switcher'); ?></span>
                <?php print Form::checkbox('module_language_switcher',1,$check); ?>


        </div>
        <div class="tab-pane fade" id="assets">
            <div class="span16">
                <h3><?php print __('advanced.assets.list') ?></h3>
                <br/>
                <?php $check = $asset_jquery ? array('checked'=>'checked') : array(); ?>
                <span>jQuery, jQuery ui</span>
                <?php print Form::checkbox('asset_jquery',1,$check); ?>

                <?php $check = $asset_modernizr ? array('checked'=>'checked') : array(); ?>
                <span>modernizr</span>
                <?php print Form::checkbox('asset_modernizr',1,$check); ?>

                <?php $check = $asset_nivo_slider ? array('checked'=>'checked') : array(); ?>
                <span>nivo slider</span>
                <?php print Form::checkbox('asset_nivo_slider',1,$check); ?>

                <?php $check = $asset_colorbox ? array('checked'=>'checked') : array(); ?>
                <span>colorbox</span>
                <?php print Form::checkbox('asset_colorbox',1,$check); ?>

                <?php $check = $asset_swfobject ? array('checked'=>'checked') : array(); ?>
                <span>jquery.swfObject</span>
                <?php print Form::checkbox('asset_swfobject',1,$check); ?>

                <?php $check = $asset_custom ? array('checked'=>'checked') : array(); ?>
                <span>custom</span>
                <?php print Form::checkbox('asset_custom',1,$check); ?>
            </div>
        </div>
        <div class="tab-pane fade" id="accounts">

            <h3>
                <?php print __('advanced.header.accounts') ?>
            </h3>

            <?php
            print '<a class="button" href="' . Uri::create('admin/accounts/add') . '">' . __('advanced.accounts.add.button') . '</a><br/>';
            $accounts = model_db_accounts::find('all',array(
                'order_by' => array('language'=>'ASC')
            ));

            foreach($accounts as $account)
            {
                print writeRow($account);
            }

            function writeRow($account,$class='list_entry_accounts')
            {
                print '<div id="' . $account->id . '" class="list_entry clearfix ' . $class . '">';

                print '<div class="col-xs-4 padding15"><strong>';

                print $account->language;

                print '</strong></div>';

                print '<div class="col-xs-4 padding15">';

                print $account->username;

                print '</div>';


                print '<div class="col-xs-4 account-options">';

                if($account->username != model_auth::$user['username'])
                    print '<a class="delete" href="' . Uri::create('admin/accounts/delete/' . $account->id) . '"><img src="' . Uri::create('assets/img/icons/delete.png') . '"/></a>';

                print '<a href="' . Uri::create('admin/accounts/edit/' . $account->id) . '"><img src="' . Uri::create('assets/img/icons/edit.png') . '"/></a> ';

                print '</div>';

                print '</div>';
            }
            ?>
            </div>
        <hr/>
        <?php
        print Form::submit('submit',__('constants.save'),array('class'=>'button'));

        print Form::close();
        ?>
        </div>
        </div>
    </div>
</div>