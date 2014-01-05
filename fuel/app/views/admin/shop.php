
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
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/spectrum.css') ?>">
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/elrte/smoothness/jquery-ui-1.8.13.custom.css') ?>">
  <!-- end CSS-->

  <?php print Asset\Manager::get('js->include->0_modernizr->modernizr') ?>
  <?php print Asset\Manager::get('js->include->1_jquery->jquery') ?>
  <?php print Asset\Manager::get('js->include->1_jquery->jquery-ui') ?>
  <?php print Asset\Manager::get('js->include->1_jquery->jquery.hotkeys') ?>
  <?php print Asset\Manager::get('js->include->4_swfobject->swfobject') ?>
  <?php print Asset\Manager::get('js->dialog->dialog') ?>
  <?php print Asset\Manager::get('js->picturemanager->picturemanager') ?>
  <?php print Asset\Manager::get('js->libs->spectrum') ?>

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
        <div class="span10 supersearch">
        </div>
        <div class="logout span3">
          <a href="<?php print Uri::create('admin/logout'); ?>"><?php print __('nav.logout') ?></a>
        </div>
      </div>

      <div class="row logo_lang">

      <div class="span7">
        <img src="<?php print Uri::create('assets/img/admin/logo.png'); ?>" /> 
        <div class="version"><?php print model_about::show_version() ?></div>
      </div>
      
      <div class="span8" id="change_lang">
      </div>

      </div>

    <?php print file_exists(APPPATH . 'INSTALL_TOOL_DISABLED') ? '' :  '<div class="error">' . __('constants.install_tool_usable') . '</div>' ?>
    
    </header>
    <div id="main" role="main">
      <noscript>
        <div class="well"><?php print __('nojavascript'); ?></div>
      </noscript>
      <nav class="clearfix">
        <ul class="tabs">
          <li><a href="<?php print Uri::create('admin/dashboard'); ?>"><?php print __('nav.back') ?></a></li>

          <li <?php print (Uri::segment(3) == 'orders') ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/shop/orders'); ?>"><?php print __('nav.orders') ?></a></li>

          <li <?php print (Uri::segment(3) == 'articles') ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/shop/articles'); ?>"><?php print __('nav.article') ?></a></li>

          <li <?php print (Uri::segment(3) == 'groups') ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/shop/groups'); ?>"><?php print __('nav.groups') ?></a></li>

          <li <?php print (Uri::segment(3) == 'tax') ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/shop/tax'); ?>"><?php print __('nav.tax_groups') ?></a></li>

          <li <?php print (Uri::segment(3) == 'settings') ? 'class="active"' : '' ?>><a href="<?php print Uri::create('admin/shop/settings'); ?>"><?php print __('nav.shop_settings') ?></a></li>
        </ul>
      </nav>
      <section id="content" class="clearfix">
        <?php print $content; ?>
      </section>
    </div>
  </div> <!--! end of #container -->

  <!-- scripts concatenated and minified via ant build script-->
  <script src="<?php print Uri::create('assets/js/mylibs/prompt.js') ?>"></script>
  <script defer src="<?php print Uri::create('assets/js/plugins.js') ?>"></script>
  <script defer src="<?php print Uri::create('assets/js/admin.js') ?>"></script>
  <!-- end scripts-->
  
</body>
</html>
