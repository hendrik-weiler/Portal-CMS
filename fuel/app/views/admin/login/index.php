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
  <link href='http://fonts.googleapis.com/css?family=Alegreya+Sans:300,500,700' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/bootstrap-theme.min.css') ?>">
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/ui.css') ?>">
  <link rel="stylesheet" href="<?php print Uri::create('assets/css/admin_login.css') ?>">
  <!-- end CSS-->

  <?php print Asset\Manager::get('js->include->0_modernizr->mod') ?>
</head>

<body>

  <div class="container">
      <div style="text-align:center;margin: 20px 0;"><img src="<?php print Uri::create('assets/img/admin/logo.png'); ?>" /></div>   
      <div class="graycontainer padding20">
      <?php 
      print Form::open('admin/login'); 
      print Form::input('username',$username,array('placeholder'=>__('username'))); 
      print Form::password('password','',array('placeholder'=>__('password'))); 
      print Form::submit('login',__('button'),array('class'=>'button')); 
      print Form::close();
      print $error;
      ?>
    </div>
    <div style="text-align:center;padding-top:15px;"><?php print model_about::show_version() ?></div>
  </div>


  <?php print Asset\Manager::get('js->include->1_jquery->jquery') ?>


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
