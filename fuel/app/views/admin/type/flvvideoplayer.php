<?php print Form::open(array('action'=>'admin/content/' . Uri::segment(3) . '/edit/' . Uri::segment(5) . '/type/14/edit','enctype'=>'multipart/form-data')) ?>
<div class="col-xs-1 backbutton">
    <label>
        <?php print Form::submit('back',__('types.15.back'),array('class'=>'hide')); ?>
        <img src="<?php print Uri::create('assets/img/icons/arrow_left.png') ?>" alt=""/>
    </label>
</div>
    <style>
        .flvvideoplayer label {
            display: block;
            margin: 15px 0;
        }
        .prompt {
            width: 350px;
        }
        .prompt button {
            display: inline;
        }
    </style>
<div class="col-xs-11 vertical graycontainer globalmenu flvvideoplayer">
    <script type="text/javascript">
        var _save_skin_url = "<?php print Uri::create('admin/content/' . Uri::segment(3) . '/edit/' . Uri::segment(5) . '/type/14/save/skin') ?>";
        var _dialog_save_headline = "<?php print __('types.14.dialog_save_headline') ?>";
        var _dialog_save_confirm = "<?php print __('types.14.dialog_save_confirm') ?>";
        var _dialog_save_cancel = "<?php print __('types.14.dialog_save_cancel') ?>";
    </script>
    <div class="description">FLV Videoplayer</div>
    <div class="list padding15">
        <div class="col-xs-6">
            <?php print Form::hidden('skin_saved',0) ?>
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
        <div class="col-xs-6">
            <?php print Form::label(__('types.14.label')); ?>

            <?php print Form::input('label',$label) ?>


            <?php print Form::label(__('types.14.height')); ?>

            <?php print Form::input('height',$height) ?>

            <?php print Form::label(__('types.14.width')); ?>

            <?php print Form::input('width',$width) ?>

            <hr/>
            <?php print Form::label(__('types.14.file')); ?>

            <?php print Form::file('video_file') ?>
            <?php print Form::label(__('types.14.preview_pic')); ?>
            <?php
            if(isset($video_preview) && !empty($video_preview))
                print Form::submit('video_preview_delete',__('types.14.preview_pic_delete'),array('class'=>'button'));
            ?>
            <?php print Form::file('video_preview') ?>
            <hr/>

            <?php print Form::label(__('types.14.file_choose')); ?>

            <?php print Form::select('video_name', $video_name, $videos) ?>
            <br/>
            <?php print Form::label(__('types.14.autoplay')); ?>

            <?php
            $checked = array();
            $autoplay == 'true' and $checked = array('checked'=>'checked');
            ?>
            <?php print Form::checkbox('autoplay',1,$checked) ?>

            <?php print Form::label(__('types.14.autohide')); ?>

            <?php
            $checked = array();
            $autohide == 'true' and $checked = array('checked'=>'checked');
            ?>
            <?php print Form::checkbox('autohide',1,$checked) ?>

            <?php print Form::label(__('types.14.fullscreen')); ?>

            <?php
            $checked = array();
            $fullscreen == 'true' and $checked = array('checked'=>'checked');
            ?>
            <?php print Form::checkbox('fullscreen',1,$checked) ?>

            <?php print Form::label(__('types.14.skin')); ?>

            <?php print Form::select('skin',$selected_skin,$skins) ?>

            <?php print Form::button('load',__('types.14.load'),array('class'=>'button')) ?>
            <br /><br />
            <?php print Form::button('save',__('types.14.save'),array('class'=>'button')) ?>

            <h3><?php print __('types.14.player_color') ?></h3>

            <div class="col-xs-6">
                <?php print Form::label(__('types.14.color_text')); ?>

                <?php print Form::input('color_text',$color_text) ?>

                <?php print Form::label(__('types.14.color_seekbar')); ?>

                <?php print Form::input('color_seekbar',$color_seekbar) ?>

                <?php print Form::label(__('types.14.color_loadingbar')); ?>

                <?php print Form::input('color_loadingbar',$color_loadingbar) ?>

                <?php print Form::label(__('types.14.color_seekbarbg')); ?>

                <?php print Form::input('color_seekbarbg',$color_seekbarbg) ?>
            </div>
            <div class="col-xs-6">
                <?php print Form::label(__('types.14.color_button_out')); ?>

                <?php print Form::input('color_button_out',$color_button_out) ?>

                <?php print Form::label(__('types.14.color_button_over')); ?>

                <?php print Form::input('color_button_over',$color_button_over) ?>

                <?php print Form::label(__('types.14.color_button_highlight')); ?>

                <?php print Form::input('color_button_highlight',$color_button_highlight) ?>
            </div>
        </div>
        <div class="col-xs-12">
            <?php print Form::submit('confirm',__('types.13.submit'),array('class'=>'button')); ?>
        </div>
    </div>
	<?php print Form::close(); ?>
</div>
<?php print Asset\Manager::get('js->type->flvvideoplayer') ?>