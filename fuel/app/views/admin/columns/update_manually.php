<div id="update">
  <ul class="tabs" data-tabs="tabs">
    <li><a href="<?php print Uri::create('admin/advanced') ?>"><?php print __('advanced.tabs.back') ?></a></li>
    <li class="active"><a href="<?php print Uri::create('admin/advanced/update') ?>">Updates</a></li>
  </ul>
  <div class="row">
  </div>

  <div class="message row hide">
    <?php print __('advanced.updater.no_update_able'); ?>
  </div>

  <?php if(Input::get('result') != ''): ?>
  <div class="result message <?php print Input::get('result') ?> row">
    <?php print __('advanced.updater.' . Input::get('result')) ?>
  </div>
  <?php endif; ?>

  <div class="update head row">
    <div class="release_date span3"><?php print __('advanced.updater.release_date') ?></div>
    <div class="version span1"><?php print __('advanced.updater.version') ?></div>
    <div class="description span8"><?php print __('advanced.updater.description') ?></div>
  </div>

  <div class="results">...</div>

  <?php print Form::open(array('action' => 'admin/advanced/update/execute/manually','enctype'=>'multipart/form-data')) ?>
<div class="row information">
  <?php print __('advanced.updater.manually.instruction') ?>
</div>
  <?php print Form::file('package'); ?>
  <p>
  <?php print Form::submit('update_to',__('advanced.updater.manually.update'),array('class'=>'btn primary')); ?>
</p>
  <?php print Form::close(); ?>
</div>
<script type="text/javascript">

var _base_url = "http://<?php print Config::get('url') ?>";

var _lang_not_ready_yet = "<?php print __('advanced.updater.update_not_available') ?>";

var _lang_update = "<?php print __('advanced.updater.manually.download') ?>";

var _current_lang = "<?php print $user_lang ?>";

var _current_version = <?php print model_about::$version ?>;

var row = $('<div class="update row">' +
    '<div class="release_date span3"></div>' +
    '<div class="version span1"></div>' +
    '<div class="description span7"></div>' +
    '<div class="update-action span4"><a target="_blank" class="btn" href=""></a></div>' +
  '</div>');

$.ajax({
    url: _base_url + '/updates.js',
    dataType: 'jsonp',
    complete: function(data) { 

      console.log(updates);

      $('.results').html('');

      if(updates.updater_content.message.default != "")
      {
        var lang = _current_lang;
        if(!updates.updater_content.message[_current_lang])
          lang = 'default';

        $('.message').html(updates.updater_content.message[lang]);
      }  
      else
        $('.message').remove();

      $.each(updates.updater_content.updates.update, function(key, obj) {

        var new_row = $(row).clone();

        if(obj.release_date == '')
          obj.release_date = '&nbsp;';

        $(new_row).find('.release_date').html(obj.release_date);

        $(new_row).find('.version').html(obj.version);

        if(!obj.description[_current_lang])
          _current_lang = 'default';

        $(new_row).find('.description').html(obj.description[_current_lang]);

        if(obj.released == '0')
          $(new_row).find('.update-action a').attr('href','#').addClass('disabled').html(_lang_not_ready_yet);
        else if(_current_version >= parseFloat(obj.version))
          $(new_row).find('.update-action a').remove();
        else 
          $(new_row).find('.update-action a').attr('href',_base_url + '/' + obj.filename).html(_lang_update + obj.version);

        $(new_row).find('.update-action a').click(function(e) {
          if($(this).attr('href') == '#') {
            e.preventDefault();
          }
        });

        $('.results').append(new_row);
      });
    },
    error : function() {
      $('div.message.hide').show();
    }
});

$('.result').delay(1000).fadeOut(2000);
</script>