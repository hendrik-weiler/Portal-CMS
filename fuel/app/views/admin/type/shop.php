<?php print Form::open(array('action'=>'admin/content/' . Uri::segment(3) . '/edit/' . Uri::segment(5) . '/type/15/edit','enctype'=>'multipart/form-data')) ?>
<div class="col-xs-1 backbutton">
    <label>
        <?php print Form::submit('back',__('types.15.back'),array('class'=>'hide')); ?>
        <img src="<?php print Uri::create('assets/img/icons/arrow_left.png') ?>" alt=""/>
    </label>
</div>
<div class="row">
    <div class="col-xs-11 vertical graycontainer globalmenu">
        <div class="description">
            <h3><?php print __('types.15.choose'); ?></h3>
        </div>
	    <div class="list padding15">
            <?php foreach ($article_groups as $group): ?>
                <div class="">
                    <?php
                    $checked = array();
                    in_array($group->id,$selected_checkbox) and $checked = array('checked'=>'checked');
                    ?>
                    <?php print Form::checkbox('group[]', $group->id, $checked) ?>
                    <?php
                    $data = Format::forge($group->label,'json')->to_array();
                    $prefix = Session::get('lang_prefix');
                    if(isset($data[$prefix])) {
                        print $data[$prefix];
                    } else {
                        print array_shift($data);
                    }
                    ?>
                </div>
            <?php endforeach; ?>
            <?php print Form::submit('confirm',__('types.15.submit'),array('class'=>'button')); ?>
	    </div>
	</div>
</div>
<?php print Form::close(); ?>