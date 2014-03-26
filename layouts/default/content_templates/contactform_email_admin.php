<?php print $title; ?>

<?php print $time; ?>

<?php
  foreach($data as $key => $value)
  {
    print str_replace(array('_text','_'),array('',' '),ucfirst($key));
    print ': ' . $value;
    print PHP_EOL;
  }
?>