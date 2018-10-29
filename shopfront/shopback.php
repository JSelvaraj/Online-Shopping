<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>Receipt</title>
</head>

<body>

<h1>Receipt -- PHP yet to be completed!</h1>

<p>
<?php
// http://php.net/manual/en/function.htmlspecialchars.php
function getFormInfo($k) {
  return isset($_POST[$k]) ? htmlspecialchars($_POST[$k]) : null;
}

foreach (array_keys($_POST) as $k) {
  $v = getFormInfo($k);
  echo "{$k} : {$v}<br />\n";
}
?>
</p>

</body>
</html>
