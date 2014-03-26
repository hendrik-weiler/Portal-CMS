
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
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/bootstrap-theme.min.css') ?>">
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/admin.css') ?>">
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/ui.css') ?>">
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
  <?php print Asset\Manager::get('js->libs->bootstrap.min') ?>

  <script>
    var _prompt = {
      header : '<?php print __('prompt.' . Uri::segment(2) . '.header') ?>',
      text : '<?php print __('prompt.' . Uri::segment(2) . '.text') ?>',
      ok : '<?php print __('prompt.' . Uri::segment(2) . '.ok') ?>',
      cancel : '<?php print __('prompt.' . Uri::segment(2) . '.cancel') ?>'
    };
    var _language = "<?php print str_replace("/","",model_db_accounts::getCol(Session::get('session_id'),'language')) ?>";
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
</head>

<body>

<div class="line row" style="margin-right:0;">
  <div class="col-xs-8 padding15 searchdiv">
    <div class="row inputbutton">
      <div class="col-xs-8 inputbutton-input">
          <?php print Form::input('supersearch_input','',array('placeholder'=>'Webseite durchsuchen...')) ?>
          <?php print Form::select('supersearch_cat',0,Controller_Supersearch_Supersearch::get_supersearch_columns($permission),array('style'=>'display:none;')) ?>
      </div>
      <div class="col-xs-4 inputbutton-button">
        <img src="<?php print Uri::create('assets/img/icons/lupe.gif') ?>" alt="">
      </div>
    </div>
  </div>
  <div class="col-xs-4 padding15 logoutdiv">
      <a href="<?php print Uri::create('admin/clear_cache?return=' . Uri::current()); ?>"><?php print __('nav.clear_cache') ?></a>
      <a href="<?php print Uri::create('admin/logout'); ?>"><?php print __('nav.logout') ?></a>
  </div>
</div>
<div class="graycontainer globalmenu">
  <div class="description">
    <?php print __('constants.languageversion') ?>
  </div>
  <div class="list">
    <ul>
    <?php 
      $langs = model_permission::getValidLanguages();

      $curr_prefix = model_db_language::find('first',array(
        'where' => array('prefix'=>Session::get('lang_prefix'))
      ));

      $active = '';
      foreach ($langs as $key => $value) {
        $curr_prefix->id == $key ? $active = 'class="active"' : $active = '';
        $lang = model_db_language::find($key);
        print '<li><a ' . $active . ' href="' . Uri::create('admin/inlineedit/lang/change/' . $lang->prefix) . '?redirect=' . Uri::create('admin/dashboard') . '">' . $value . '</a></li>';
      }
    ?>
    </ul>
  </div>
</div>

<div class="graycontainer globalmenu">
  <div class="description">
    CMS
  </div>
  <div class="list cms_nav">
    <ul>
          <li><a <?php print (Uri::segment(2) == 'dashboard') ? 'class="active"' : '' ?> href="<?php print Uri::create('admin/dashboard'); ?>"><?php print __('nav.dashboard') ?></a></li>

          <?php if($permission[0]['valid']): ?>
          <li><a <?php print (in_array(Uri::segment(2),array('sites','content','navigation'))) ? 'class="active"' : '' ?> href="<?php print Uri::create('admin/navigation'); ?>"><?php print __('nav.navigation') ?></a></li>
          <?php endif; ?>

          <?php if($permission[2]['valid']): ?>
          <li><a <?php print (Uri::segment(2) == 'news') ? 'class="active"' : '' ?> href="<?php print Uri::create('admin/news'); ?>"><?php print __('nav.news') ?></a></li>
          <?php endif; ?>

          <?php if($permission[4]['valid']): ?>
          <li class="break"><a <?php print (Uri::segment(2) == 'language') ? 'class="active"' : '' ?> href="<?php print Uri::create('admin/language'); ?>"><?php print __('nav.language') ?></a></li>
          <?php endif; ?>

          <?php if($permission[3]['valid']): ?>
          <li><a <?php print (Uri::segment(2) == 'settings') ? 'class="active"' : '' ?> href="<?php print Uri::create('admin/settings'); ?>"><?php print __('nav.settings') ?></a></li>
          <?php endif; ?>

          <?php if($permission[5]['valid']): ?>
          <li><a <?php print (in_array(Uri::segment(2),array('advanced','accounts'))) ? 'class="active"' : '' ?> href="<?php print Uri::create('admin/advanced'); ?>"><?php print __('nav.advanced') ?></a></li>
          <?php endif; ?>
    </ul>
  </div>
</div>

<?php if(model_permission::$user->admin): ?>
<div class="graycontainer globalmenu">
  <div class="description">
    Shop
  </div>
  <div class="list shop_nav">
    <ul>
          <li><a <?php print (Uri::segment(3) == 'orders') ? 'class="active"' : '' ?> href="<?php print Uri::create('admin/shop/orders'); ?>"><?php print __('nav.orders') ?></a></li>

          <li><a <?php print (Uri::segment(3) == 'articles') ? 'class="active"' : '' ?> href="<?php print Uri::create('admin/shop/articles'); ?>"><?php print __('nav.article') ?></a></li>

          <li><a <?php print (Uri::segment(3) == 'groups') ? 'class="active"' : '' ?> href="<?php print Uri::create('admin/shop/groups'); ?>"><?php print __('nav.groups') ?></a></li>

          <li class="break"><a <?php print (Uri::segment(3) == 'tax') ? 'class="active"' : '' ?> href="<?php print Uri::create('admin/shop/tax'); ?>"><?php print __('nav.tax_groups') ?></a></li>

          <li><a <?php print (Uri::segment(3) == 'settings') ? 'class="active"' : '' ?> href="<?php print Uri::create('admin/shop/settings'); ?>"><?php print __('nav.shop_settings') ?></a></li>
    </ul>
  </div>
</div>
<?php endif; ?>

    <?php print file_exists(APPPATH . 'INSTALL_TOOL_DISABLED') ? '' :  '<div class="error">' . __('constants.install_tool_usable') . '</div>' ?>
    

      <noscript>
        <div class="well"><?php print __('nojavascript'); ?></div>
      </noscript>

      <div class="content graycontainer">
        <?php print $content; ?>
      </div>


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


  <?php print Asset\Manager::get('js->tooltip'); ?>
  <?php  print Asset\Manager::get('js->type->global');  ?>
  <!-- end scripts-->
  
</body>
</html>
