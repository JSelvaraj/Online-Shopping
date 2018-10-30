<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>Receipt</title>
</head>

<body>

<h1>Receipt</h1>

<p>
<?php

define("STOCK_FILE_NAME", "stock.txt"); // Local file - insecure!
define("STOCK_FILE_LINE_SIZE", 256); // 256 line length should enough.

// http://php.net/manual/en/function.htmlspecialchars.php
function getFormInfo($k) {
  return isset($_POST[$k]) ? htmlspecialchars($_POST[$k]) : null;
}
function validQuantity($k) {
  if ($_POST[$k] < 0 or filter_var($_POST[$k], FILTER_VALIDATE_INT) === false) {
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

function validCCCode($k) {
  if (strlen($_POST[$k]) != 3 && preg_match('/[0-9]{3}/',$_POST[$k]) != 1 && filter_var($_POST[$k], FILTER_VALIDATE_INT) === false) {
    header('Location: Invalid_Input.html');
  }
}

function required($k) {
  if (strlen($_POST[$k]) == 0) {
    header('Location: Invalid_Input.html');
  }
}

function validEMail($k) {
  $pattern = '/[a-zA-Z]+@[a-zA-Z]+(.[a-zA-Z]+)+/'; //Not perfect, matches after the dot at least twice
  preg_match($pattern, $_POST[$k], $matches);
  if (!isset($matches[0])){
    header('Location: Invalid_Input.html');
  }

}

$date = date('d/j/y');
echo "<b>Date:</b> $date ";

$email = $_POST['email'];
$email = substr(hash('md5', $email), 0, 5); //generates unique prefix
$TransactionID = uniqid($email); 
echo "<b>Transaction ID:</b> $TransactionID <br/>";
echo "<hr>";
if (!file_exists(STOCK_FILE_NAME)) {
  die("File not found for read - " . STOCK_FILE_NAME . "\n"); // Script exits.
}

$f = fopen(STOCK_FILE_NAME, "r");
$stock_list = null;
print_r($stock_list);
while (($row = fgetcsv($f, STOCK_FILE_LINE_SIZE)) != false) {
  $stock_item = array(
    "id" => $row[0], /// needs to be unique!
    "photo" => $row[0] . ".jpg",
    "name" => $row[1],
    "info" => $row[2],
    "price" => $row[3]);
  $stock_list[$row[0]] = $stock_item; // Add stock.
}
fclose($f);

$sub_total = 0;
$cc_Type = null;
$cc_number = null;
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
    if ($_POST[$k] != 0) {
      $line_cost = ($_POST[$k] * $stock_list[$k]['price']);
      $sub_total += $line_cost;
      $line_cost = number_format($line_cost, 2);
      echo "{$k} : {$v} x {$stock_list[$k]['price']} = £$line_cost <br />\n";
    }
    if ($k == "totem") {
      echo "<hr>";
      if ($sub_total < 100) {
        $delivery_charge = $sub_total * 0.1;
        $vat = ($sub_total + $delivery_charge) * 0.2;
        $total = $sub_total+$delivery_charge+$vat;
        $delivery_charge = "£$delivery_charge"; 
      } else {
        $delivery_charge = "Free delivery :)";
        $vat = $sub_total * 0.2;
        $total = $sub_total+$vat;
      }
      echo "Sub-Total: £$sub_total<br/>";
      echo "Delivery Charge: $delivery_charge <br/>";
      echo "VAT: £$vat<br/>";
      echo "Total: £{$total} <br/>";
      echo "<hr>";
    }
    break;
    case "cc_type":
    $cc_Type = $_POST[$k];
    validCCType($k);
    break;
    case "cc_number":
    $cc_number = $_POST[$k];
    validCCNumber($k, $cc_Type);
    break;
    case "delivery_address":
    echo "Delivery Address: <br/> {$_POST[$k]}<br/>";
    break;
    case "delivery_postcode":
    echo "{$_POST[$k]} <br/>";
    break;
    case "delivery_country":
    echo "{$_POST[$k]} <br/>";
    break;
    case  "cc_name":
    required($k);
    break;
    case "cc_code":
    validCCCode($k);
    break;
    case "email":
    validEMail($k);
  }
}
$cc1 = substr($cc_number,0 , 2);
$cc2 = substr($cc_number, 14);
$cc_string = "{$cc1}xxxxxxxxxxxx$cc2";
echo "Card Type: $cc_Type";
echo "Card Number: $cc_string";

?>
</p>

</body>
</html>
