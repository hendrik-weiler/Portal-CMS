<!doctype html>
<html>
<head>
  <title></title>
</head>
<body>
  <h1>
    <?php print $title; ?>
  </h1>
  <h3>
    <?php print $time; ?>
  </h3>
  <table style="width:50%;">
    <tbody>
    <?php
      foreach($data as $key => $value)
      {
        print '<tr>';
        print '<td>' . str_replace(array('_text','_'),array('',' '),ucfirst($key)) . '</td>';
        print '<td>' . $value . '</td>';
        print '</tr>';
      }
    ?>
    </tbody>
  </table>
</body>
</html>