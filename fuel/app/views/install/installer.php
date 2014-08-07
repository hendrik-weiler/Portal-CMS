<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>Installer Tool</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- CSS concatenated and minified via ant build script-->
  <link href='http://fonts.googleapis.com/css?family=Alegreya+Sans:300' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/install.css') ?>">
  <!-- end CSS-->

  <?php print Asset\Manager::get('js->include->0_modernizr->modernizr'); ?>
</head>

<body>

  <div id="container">
    <header>
      <ul class="clearfix">
        <li class="<?php print $step_1 ?>">1 - <?php print __('steps.1.header') ?></li>
        <li class="<?php print $step_2 ?>">2 - <?php print __('steps.2.header') ?></li>
        <li class="<?php print $step_3 ?>">3 - <?php print __('steps.3.header') ?></li>
      </ul>
    </header>
    <div id="main" role="main">
      <?php print $content; ?>
    </div>
    <footer>
      <h4>
        <?php print __('choose_lang') ?>
      </h4>
      <?php print $languages; ?>
    </footer>
  </div> <!--! end of #container -->


   <?php print Asset\Manager::get('js->include->1_jquery->jquery'); ?>
  <!--[if lt IE 10]>
  <script src="<?php print Uri::create('assets/js/libs/PIE.js') ?>"></script>
  <![endif]-->


  <!-- scripts concatenated and minified via ant build script-->
  <script defer src="<?php print Uri::create('assets/js/plugins.js') ?>"></script>
  <script defer src="<?php print Uri::create('assets/js/install.js') ?>"></script>
  <!-- end scripts-->

  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->
  
</body>
</html>
