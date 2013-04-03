<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <?php print seo('head'); ?>

  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- CSS concatenated and minified via ant build script-->
  <?php print asset_manager_insert('css') ?>
  <?php print asset_manager_insert('js'); ?>
  <!-- end CSS-->
</head>
<?php //var_dump(get_public_variables()); ?>

<body <?php ($navigation_background_color != 'transparent') and print 'style="border-top: 5px solid ' . $navigation_background_color .'"'; ?>>

  <div class="container">
    <div class="header row">
      <div class="logo span4">
        <a href="<?php print \Uri::create('/' . model_generator_preparer::$lang) ?>"><?php print asset_manager_get('img->admin->logo'); ?></a>
      </div>
      <div class="language span8">
        <?php print language_switcher(); ?>
      </div>
    </div>
    <div class="navigation row" <?php ($navigation_background_color != 'transparent') and print 'style="background: ' . $navigation_background_color .'"'; ?>>
      <?php print navigation($Main_navigation); ?>
    </div>
    <div class="subnavigation row" <?php ($navigation_background_color != 'transparent') and print 'style="border-bottom:3px solid ' . $navigation_background_color .'"'; ?>>
      <?php print get_sub_navigation(); ?>
    </div>
    <div class="content row">
      <?php print show_sub_navigation(content()); ?>
    </div>
    <div class="footer row" <?php ($navigation_background_color != 'transparent') and print 'style="border-top: 5px solid ' . $navigation_background_color .'"'; ?>>
      <div class="span8">
        © <?php print date('Y') ?>, mysite.com
      </div>
      <div class="span4">
         <?php print navigation($Footer); ?>
      </div>
    </div>
  </div>

  <?php print seo('analytics'); ?>

  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->
  
</body>
</html>
