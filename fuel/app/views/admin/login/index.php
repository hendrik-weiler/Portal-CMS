<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>Admin - Login</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- CSS concatenated and minified via ant build script-->
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/admin_login.css') ?>">
  <!-- end CSS-->

  <script src="<?php print Uri::create('assets/js/libs/modernizr-2.0.6.min.js') ?>"></script>
</head>

<body>

  <div class="container">
    <figure>
      <img src="<?php print Uri::create('assets/img/admin/logo.png'); ?>" />      
    </figure>
    <div class="row">
      <?php print Form::open('admin/login'); ?>
        <div class="clearfix">
          <label for="xlInput"><?php print Form::label(__('username')); ?></label>
          <div class="input">
            <?php print Form::input('username',$username); ?>
          </div>
        </div>
        <div class="clearfix">
          <label for="xlInput"><?php print Form::label(__('password')); ?></label>
          <div class="input">
            <?php print Form::password('password',''); ?>
          </div>
        </div>
      
      <div class="actions">
        <?php print Form::submit('login',__('button'),array('class'=>'btn primary')); ?>
      </div>
    
    <?php
      print Form::close();

      print $error;
    ?>
    </div>
  </div> <!--! end of #container -->


  <?php print Asset\Manager::get('js->include->jquery') ?>


  <!-- scripts concatenated and minified via ant build script-->
  <script defer src="<?php print Uri::create('assets/js/plugins.js') ?>"></script>
  <script defer src="<?php print Uri::create('assets/js/admin_login.js') ?>"></script>
  <!-- end scripts-->

  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->
  
</body>
</html>
