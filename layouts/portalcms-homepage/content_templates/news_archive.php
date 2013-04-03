<section id="archive">
  <h1><?php print __('news.archive_header'); ?></h1>
  <?php 

  if(!empty($entries))
    print $entries;
  else
    print __('news.no_archive_data');

  ?>
</section>