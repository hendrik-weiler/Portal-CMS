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
  <?php print asset_manager_get_group('js->include->jquery') ?>
  <!-- end CSS-->
</head>

<body>

  <div id="container">
    <header>
      <section id="top">
        <figure class="left banner">
          <?php print asset_manager_get('img->admin->logo'); ?>
        </figure>
        <nav class="right navi">
          <?php print navigation(); ?>
        </nav>
        <?php print language_switcher(); ?>
      </section>
    </header>
    <div id="main" role="main">   
      <section id="content">
        <p>
          <div id="round">This file is located in "apps/views/public/index.php" !</div>
        </p>
        <p>
          <?php print content(); ?>
        </p>
      </section>
    </div>
    <footer>
      
    </footer>
  </div>

  <?php print asset_manager_insert('js'); ?>
  <?php print seo('analytics'); ?>

  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->
  
</body>
</html>
