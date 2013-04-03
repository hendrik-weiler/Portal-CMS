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

<body>

<?php print language_switcher(); ?>
<?php print navigation($Main_navigation); ?>
<?php print show_sub_navigation(content()); ?>
<?php print $navigation_text_color ?>
<?php print $navigation_background_color ?>
<?php print navigation($Footer); ?>


  <?php print seo('analytics'); ?>

  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->
  
</body>
</html>
