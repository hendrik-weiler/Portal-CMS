
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
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/elrte/elrte.min.css') ?>">
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/elrte/elrte-inner.css') ?>">
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/elrte/elfinder.css') ?>">
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/elrte/smoothness/jquery-ui-1.8.13.custom.css') ?>">
  <!-- end CSS-->

  <?php print Asset\Manager::get('js->include->0_modernizr->modernizr') ?>
  <?php print Asset\Manager::get('js->include->1_jquery->jquery') ?>
  <?php print Asset\Manager::get('js->include->1_jquery->jquery-ui') ?>
  <?php print Asset\Manager::get('js->include->4_swfobject->swfobject') ?>
  <script src="<?php print Uri::create('assets/js/libs/bootstrap-tabs.js') ?>"></script>
</head>

<body>
  <div class="container">
    <header>
      <figure>
        <img src="<?php print Uri::create('assets/img/admin/logo.png'); ?>" />      
        <div class="version">Version: <?php print number_format((model_about::$version),2); ?></div>
      </figure>
      <?php print file_exists(APPPATH . 'INSTALL_TOOL_DISABLED') ? '' :  '<div class="error">' . __('constants.install_tool_usable') . '</div>' ?>
      <section class="span16" id="change_lang">
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
      </section>
    </header>
    <div id="main" role="main">
      <nav class="clearfix">
        <ul class="tabs">
          <?php if($permission[0]['valid']): ?>
          <li <?php print (Uri::segment(2) == 'navigation') ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/navigation'); ?>"><?php print __('nav.navigation') ?></a></li>
          <?php endif; ?>

          <?php if($permission[1]['valid']): ?>
          <?php $first_site = model_db_site::find('first'); ?>
          <?php if(!empty($first_site)): ?>
          <li <?php print (in_array(Uri::segment(2),array('sites','content'))) ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/sites/edit/' . $first_site->id); ?>"><?php print __('nav.sites') ?></a></li>
          <?php endif; ?>
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

          <li><a href="<?php print Uri::create('admin/logout'); ?>"><?php print __('nav.logout') ?></a></li>
        </ul>
      </nav>
      <section id="content" class="clearfix">
        <?php print $content; ?>
      </section>
    </div>
    <footer>

    </footer>
  </div> <!--! end of #container -->


  <!--[if lte IE 8]>
  <script src="<?php print Uri::create('assets/js/libs/elrte-ie.min.js') ?>"></script>
  <![endif]-->
  <!--[if !IE]> -->
  <script src="<?php print Uri::create('assets/js/libs/elrte.min.js') ?>"></script>
  <!-- <![endif]-->
  <script src="<?php print Uri::create('assets/js/libs/elfinder.min.js') ?>"></script>
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

  <!-- scripts concatenated and minified via ant build script-->
  <script src="<?php print Uri::create('assets/js/mylibs/prompt.js') ?>"></script>
  <script defer src="<?php print Uri::create('assets/js/plugins.js') ?>"></script>
  <script defer src="<?php print Uri::create('assets/js/admin.js') ?>"></script>
  <!-- end scripts-->

  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->
  
</body>
</html>
