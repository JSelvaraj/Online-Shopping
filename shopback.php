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
function validQuantity($k) {
  if ($_POST[$k] < 0) {
    header('Location: Invalid_Input.html');
  }
}

function validCCType($k) {
  if($_POST[$k] != 'mastercard' && $_POST[$k] != 'visa') {
    header('Location: Invalid_Input.html');
  } 
}

function validCCNumber($k, $cc_Type) {
  switch($cc_Type) {
    case 'mastercard':
    $pattern = '/[5]{1}[0-9]{15}/';
    break;
    case 'visa':
    $pattern = '/[4]{1}[0-9]{15}/';
  }
  if (preg_match($pattern, $_POST[$k]) != 1 || strlen($_POST[$k]) != 16) {
    header('Location: Invalid_Input.html');
  }

}
foreach (array_keys($_POST) as $k) {
  $v = getFormInfo($k);
  switch($k) {
    case "crawdad":
    case "gorilla":
    case "psion":
    case "ninja":
    case "psion":
    case "totem":
    validQuantity($k);
    break;
    case "cc_type":
    $cc_Type = $_POST[$k];
    validCCType($k);
    break;
    case "cc_number":
    validCCNumber($k, $cc_Type);
    break;






  }


  echo "{$k} : {$v}<br />\n";
}
?>
</p>

</body>
</html>
