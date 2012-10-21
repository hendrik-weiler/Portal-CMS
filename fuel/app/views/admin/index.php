
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title><?php print $title; ?></title>
  <meta name="description" content="">
  <meta name="author" content="">

  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- CSS concatenated and minified via ant build script-->
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/admin.css') ?>">
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/elrte/smoothness/jquery-ui-1.8.13.custom.css') ?>">
  <!-- end CSS-->

  <?php print Asset\Manager::get('js->include->0_modernizr->modernizr') ?>
  <?php print Asset\Manager::get('js->include->1_jquery->jquery') ?>
  <?php print Asset\Manager::get('js->include->1_jquery->jquery-ui') ?>
  <?php print Asset\Manager::get('js->include->1_jquery->jquery.hotkeys') ?>
  <?php print Asset\Manager::get('js->include->4_swfobject->swfobject') ?>
  <?php print Asset\Manager::get('js->dialog->dialog') ?>
  <?php print Asset\Manager::get('js->picturemanager->picturemanager') ?>

  <script>
    var _prompt = {
      header : '<?php print __('prompt.' . Uri::segment(2) . '.header') ?>',
      text : '<?php print __('prompt.' . Uri::segment(2) . '.text') ?>',
      ok : '<?php print __('prompt.' . Uri::segment(2) . '.ok') ?>',
      cancel : '<?php print __('prompt.' . Uri::segment(2) . '.cancel') ?>'
    };
    var _url = '<?php print Uri::create('/') ?>';
    var _currentPos = '<?php 
    if(Uri::segment(2) == 'accounts')
      print 'advanced';
    else if(Uri::segment(2) == 'content')
      print 'sites';
    else
      print Uri::segment(2);
    ?>';
  </script>
  <script src="<?php print Uri::create('assets/js/libs/bootstrap-tabs.js') ?>"></script>
</head>

<body>
  <div class="container">
    <header>
      <div class="userarea row">
        <div class="user span3"><strong><?php print __('constants.user') . ':</strong> ' . model_db_accounts::getCol(Session::get('session_id'),'username') ?></div>  
        <div class="span11 supersearch">
          <div class="row">
            <div class="span2">Supersearch</div>
            <div class="span4"><?php print Form::select('supersearch_cat',0,Controller_Supersearch_Supersearch::get_supersearch_columns($permission)) ?></div>
            <div class="span4"><?php print Form::input('supersearch_input','',array('class'=>'large')) ?></div>
          </div>
        </div>
        <div class="logout span2"><a href="<?php print Uri::create('admin/logout'); ?>"><?php print __('nav.logout') ?></a></div>
      </div>

      <div class="row logo_lang">

      <div class="span7">
        <img src="<?php print Uri::create('assets/img/admin/logo.png'); ?>" /> 
        <div class="version"><?php print model_about::show_version() ?></div>
      </div>
      <?php print file_exists(APPPATH . 'INSTALL_TOOL_DISABLED') ? '' :  '<div class="error">' . __('constants.install_tool_usable') . '</div>' ?>
      
      <div class="span8" id="change_lang">
    <div class="clearfix">
     <?php print Form::label(__('constants.choose_lang')); ?>
     <div class="input">
        <?php 
          

          $langs = model_permission::getValidLanguages();

          $curr_prefix = model_db_language::find('first',array(
            'where' => array('prefix'=>Session::get('lang_prefix'))
          ));

          print Form::select('lang_prefix',$curr_prefix->id,$langs) . ' ';

          print Form::submit('change',__('constants.choose_lang_submit'),array('class'=>'btn'));

        ?>
      </div>
    </div>
      </div>

      </div>

    </header>
    <div id="main" role="main">
      <nav class="clearfix">
        <ul class="tabs">
          <li <?php print (Uri::segment(2) == 'dashboard') ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/dashboard'); ?>"><?php print __('nav.dashboard') ?></a></li>

          <?php if($permission[0]['valid']): ?>
          <li <?php print (in_array(Uri::segment(2),array('sites','content','navigation'))) ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/navigation'); ?>"><?php print __('nav.navigation') ?></a></li>
          <?php endif; ?>

          <?php if($permission[2]['valid']): ?>
          <li <?php print (Uri::segment(2) == 'news') ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/news'); ?>"><?php print __('nav.news') ?></a></li>
          <?php endif; ?>

          <?php if($permission[4]['valid']): ?>
          <li <?php print (Uri::segment(2) == 'language') ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/language'); ?>"><?php print __('nav.language') ?></a></li>
          <?php endif; ?>

          <?php if($permission[3]['valid']): ?>
          <li <?php print (Uri::segment(2) == 'settings') ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/settings'); ?>"><?php print __('nav.settings') ?></a></li>
          <?php endif; ?>

          <?php if($permission[5]['valid']): ?>
          <li <?php print (in_array(Uri::segment(2),array('advanced','accounts'))) ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/advanced'); ?>"><?php print __('nav.advanced') ?></a></li>
          <?php endif; ?>
        </ul>
      </nav>
      <section id="content" class="clearfix">
        <?php print $content; ?>
      </section>
    </div>
    <footer>

    </footer>
  </div> <!--! end of #container -->

  <!-- scripts concatenated and minified via ant build script-->
  <script src="<?php print Uri::create('assets/js/mylibs/prompt.js') ?>"></script>
  <script defer src="<?php print Uri::create('assets/js/plugins.js') ?>"></script>
  <script defer src="<?php print Uri::create('assets/js/admin.js') ?>"></script>

  <script src="<?php print Uri::create('assets/js/tour/tour.js') ?>"></script>
  <script type="text/javascript">
  var _tour_mouse_picture = "<?php print Uri::create('assets/img/admin/cursor.png') ?>";
  var _tour_language = "<?php print model_db_accounts::getCol(Session::get('session_id'),'language') ?>";
  var _tour_next_button = "<?php print __('constants.next_step') ?>";
  var _tour_end_tour_button = "<?php print __('constants.end_tour') ?>";
  var _tour_base_url = _url;
  var tour = new pcms.tour();
  </script>

  <script src="<?php print Uri::create('assets/js/supersearch/supersearch.js') ?>"></script>
  <script type="text/javascript">
  var _supersearch_base_url = _url;
  var _supersearch_lang_version = "<?php print Session::get('lang_prefix') ?>";
  var supersearch = new pcms.supersearch();
  supersearch.init_keyboard_shorcuts();
  </script>
  <!-- end scripts-->
  
</body>
</html>
