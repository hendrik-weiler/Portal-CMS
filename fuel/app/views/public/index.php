<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <?php print model_generator_seo::render(); ?>

  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- CSS concatenated and minified via ant build script-->
  <?php print Asset\Manager::insert('css'); ?>
  <!-- end CSS-->

  <?php print Asset\Manager::insert('js'); ?>
</head>

<body>

  <div id="container">
    <header>
      <section id="top">
        <figure class="left banner">
          <?php print Asset\Manager::get('img->admin->logo') ?>
        </figure>
        <nav class="right navi">
          <?php #print model_generator_navigation::render(); ?>
        </nav>
      </section>
    </header>
    <div id="main" role="main">
      <?php #print model_generator_tools::viewLanguageSelection() ?>      
      <section id="content">
        <p>
          <div id="round">This file is located in "apps/views/public/index.php" !</div>
        </p>
        <?php print model_generator_content::render(); ?>
      </section>
    </div>
    <footer>
      
    </footer>
  </div>

  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->
  
</body>
</html>
