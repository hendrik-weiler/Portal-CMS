<?php print Form::open(array('action'=>Uri::current(),'class'=>'form_style_1','enctype'=>'multipart/form-data')); ?>
<div class="col-xs-1 backbutton">
    <label>
        <?php print Form::submit('back',__('types.15.back'),array('class'=>'hide')); ?>
        <img src="<?php print Uri::create('assets/img/icons/arrow_left.png') ?>" alt=""/>
    </label>
</div>
<div class="col-xs-11 vertical graycontainer globalmenu">
    <div class="description">
        <h3>
          <?php print __('types.2.header'); ?>
        </h3>
    </div>
    <div class="list padding15">
      <div class="clearfix">
       <?php print Form::label(__('types.3.label')); ?>
       <div class="input">
          <?php print Form::input('label',$label); ?>
        </div>
      </div>
        <div class="clearfix">
       <?php print Form::label(__('types.2.sendTo')); ?>
       <div class="input">
          <?php  print Form::input('sendTo',$sendTo); ?>
        </div>
      </div>

    <h3>
      <?php print __('types.2.form_header'); ?>
    </h3>
    <table>
      <thead>
        <tr>
          <th><?php print __('types.2.required'); ?></th>
          <th><?php print __('types.2.visible'); ?></th>
          <th><?php print __('types.3.label'); ?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <?php $checked = isset($company_required) && $company_required ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('company_required',1,$checked) ?>
          </td>
          <td>
            <?php $checked = isset($company_visible) && $company_visible ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('company_visible',1,$checked) ?>
          </td>
          <td>
            <?php print Form::input('company_label',$company_label) ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php $checked = isset($first_name_required) && $first_name_required ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('first_name_required',1,$checked) ?>
          </td>
          <td>
            <?php $checked = isset($first_name_visible) && $first_name_visible ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('first_name_visible',1,$checked) ?>
          </td>
          <td>
            <?php print Form::input('first_name_label',$first_name_label) ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php $checked = isset($last_name_required) && $last_name_required ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('last_name_required',1,$checked) ?>
          </td>
          <td>
            <?php $checked = isset($last_name_visible) && $last_name_visible ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('last_name_visible',1,$checked) ?>
          </td>
          <td>
            <?php print Form::input('last_name_label',$last_name_label) ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php $checked = isset($postal_code_required) && $postal_code_required ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('postal_code_required',1,$checked) ?>
          </td>
          <td>
            <?php $checked = isset($postal_code_visible) && $postal_code_visible ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('postal_code_visible',1,$checked) ?>
          </td>
          <td>
            <?php print Form::input('postal_code_label',$postal_code_label) ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php $checked = isset($city_required) && $city_required ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('city_required',1,$checked) ?>
          </td>
          <td>
            <?php $checked = isset($city_visible) && $city_visible ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('city_visible',1,$checked) ?>
          </td>
          <td>
            <?php print Form::input('city_label',$city_label) ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php $checked = isset($email_required) && $email_required ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('email_required',1,$checked) ?>
          </td>
          <td>
            <?php $checked = isset($email_visible) && $email_visible ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('email_visible',1,$checked) ?>
          </td>
          <td>
            <?php print Form::input('email_label',$email_label) ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php $checked = isset($phone_required) && $phone_required ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('phone_required',1,$checked) ?>
          </td>
          <td>
            <?php $checked = isset($phone_visible) && $phone_visible ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('phone_visible',1,$checked) ?>
          </td>
          <td>
            <?php print Form::input('phone_label',$phone_label) ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php $checked = isset($text_required) && $text_required ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('text_required',1,$checked) ?>
          </td>
          <td>
            <?php $checked = isset($text_visible) && $text_visible ? array('checked'=>'checked') : array()   ?>
            <?php print Form::checkbox('text_visible',1,$checked) ?>
          </td>
          <td>
            <?php print Form::textarea('text_label',$text_label,array('style'=>'width:300px;height:120px')) ?>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="actions">
      <?php print Form::submit('submit',__('types.2.submit'),array('class'=>'button')); ?>
      <?php print Form::close(); ?>
    </div>
</div>