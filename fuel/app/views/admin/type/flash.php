<?php print Form::open(array('action'=>Uri::current(),'enctype'=>'multipart/form-data')) ?>
    <div class="col-xs-1 backbutton">
        <label>
            <?php print Form::submit('back',__('types.15.back'),array('class'=>'hide')); ?>
            <img src="<?php print Uri::create('assets/img/icons/arrow_left.png') ?>" alt=""/>
        </label>
    </div>
    <div class="col-xs-11 vertical graycontainer globalmenu">
        <div class="description">Flash</div>
        <div class="list padding15">
            <div class="col-xs-6">
                <?php print Form::label(__('types.1.label')) ?>

                <?php print Form::input('label',$label) ?>


                <?php print Form::label(__('types.10.params')) ?>

                <?php print Form::textarea('params',$params,array('style'=>'height:100px;')) ?>
                <span class="help-block"><?php print __('types.10.params-help') ?></span>


                <?php print Form::label(__('advanced.thumbs.width')) ?>

                <?php print Form::input('width',$width,array('class'=>'small')) ?>


                <?php print Form::label(__('advanced.thumbs.height')) ?>

                <?php print Form::input('height',$height,array('class'=>'small')) ?>


                <?php print Form::label('wMode') ?>

                <?php print Form::select('wMode',$wMode,array('window'=>'window(Default)','opaque'=>'opaque','transparent'=>'transparent')) ?>
            </div>
            <div class="col-xs-6">
                <?php print Form::label(__('types.10.flash_vid')) ?>
                <br/>
                <?php print $flash; ?>
                <?php print Form::file('flash_file') ?>


                <?php print Form::label(__('types.10.replace_pic')) ?>
                <br/>
                <img src="<?php print $picture; ?>" >
                <?php print Form::file('pictures') ?>
            </div>
            <div class="col-xs-12">
                <?php print Form::submit('submit',__('types.2.submit'),array('class'=>'button')); ?>
            </div>
        </div>
</div>
<?php print Form::close() ?>