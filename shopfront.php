<?php
clearstatcache(); // http://php.net/manual/en/function.clearstatcache.php

define("STOCK_FILE_NAME", "stock.txt"); // Local file - insecure!
define("STOCK_FILE_LINE_SIZE", 256); // 256 line length should enough.

define("PHOTO_DIR", "piks/large/"); // large photo, local files, insecure!
define("THUMBNAIL_DIR", "piks/thumbnail/"); // thumbnail, local files, insecure!

function photoCheck($photo) { // Do we have photos?
  $result = "";
  $p = PHOTO_DIR . $photo;
  $t = THUMBNAIL_DIR . $photo;
  if (!file_exists($p) || !file_exists($t)) { $result = "(No photo)"; }
  else { $result = "<a href=\"{$p}\"><img src=\"{$t}\" border=\"0\" /></a>"; }
  return $result;
}


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
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="shopfront.css" type="text/css" />
  <title>Items for sale</title>
</head>

<body onload="hidePayment()">

<script src="shopfront.js"></script>

<h1>Items for Sale</h1>

<hr />

<form name="order" id="order" onsubmit="return validateMyForm();" action="shopback.php" method="POST">

<stock_list>

  <stock_item>
    <item_photo class="heading">Photo</item_photo>
    <item_name class="heading">Name</item_name>
    <item_info class="heading">Description</item_info>
    <item_price class="heading"> &pound; (exc. VAT)</item_price>
    <item_quantity class="heading">Quantity</item_quantity>
    <line_cost class="heading">Cost</line_cost>
  </stock_item>

<?php

foreach(array_keys($stock_list) as $id) {
  // spacing in HTML output for readability only
  echo "  <stock_item id=\"{$id}\">\n";
  $item = $stock_list[$id];
  $p = photoCheck($item["photo"]);
  echo "    <item_photo>{$p}</item_photo>\n";
  echo "    <item_name>{$item["name"]}</item_name>\n";
  echo "    <item_info>{$item["info"]}</item_info>\n";
  echo "    <item_price>{$item["price"]}</item_price>\n";
  echo "    <item_quantity><input name=\"{$id}\" type=\"text\" value=\"0\"  size=\"3\" onchange=\"updateLineCost(this, '{$id}');\" /></item_quantity>\n";
  echo "    <line_cost>0.00</line_cost>\n";
  echo "  </stock_item>\n\n";
}

?>

</stock_list>

<br />
<p>Sub-total: <span name="sub_total" id="sub_total" value=""></span></p>

<p>Delivery charge: <span id="delivery_charge" name="delivery_charge"></span></p>

<p>VAT: <span id="vat" name="vat"></span></p>

<p>Total: <span id="total" name="total"></span></p>
<input type="button" onclick="showPaymentInfoForm()" value="Add Payment Details" />
<hr />
<credit_info>
<p>Credit Card type:
<select name="cc_type" size="1" onchange="validateCCNumber()" required>
<option value="" selected>-</option>
<option value="mastercard">MasterCard</option>
<option value="visa">Visa</option>
</select>
</p>

<p>Credit Card number:
<input type="text" name="cc_number" size="16" required oninvalid="invalidInput(this); validCCNum(this);" onchange="validCCNum(this)" /></p>
<span class="popuptext" id="cc_number_requirements">
  <li>Credit Card Number must be 16 characters long</li>
  <li>The first number of a visa card must be 4</li>
  <li>The first number of a mastercard must be 5</li>
</span>

<p>Name on Credit Card (also the name for delivery):
<input type="text" name="cc_name" size="80" oninvalid="invalidInput(this); validCCName(this);" required onchange="validCCName(this)"/></p>
<span class="popuptext" id="cc_name_requirements"> 
  <li>Required</li>
</span>


<p>Credit Card security code:
<input type="text" id="cc_code" name="cc_code" pattern="[0-9]{3}" size="3" required oninvalid="invalidInput(this); validCCCode(this);" onchange="validCCCode(this)"/></p>
<span class="popuptext" id="cc_code_requirements"> 
    <li>Code my be at least 3 characters long</li>
    <li>Security Code my be a positive number</li>
  </span>

<p>Delivery street address:
<input type="text" name="delivery_address" size="128" required oninvalid="invalidInput(this); validAddress(this);" onchange="validAddress(this)"/></p>
<span class="popuptext" id="delivery_address_req">  
  <li>Required</li>
</span>

<p>Delivery postcode:
<input type="text" name="delivery_postcode" size="40" required oninvalid="invalidInput(this); validPostcode(this);" onchange="validPostcode(this)"/></p>
<span class="popuptext" id="delivery_postcode_req"> 
  <li>Required</li>
</span>
<p>Delivery country:
<input type="text" name="delivery_country" size="80" required oninvalid="invalidInput(this); validDeliveryCountry(this);" onchange="validDeliveryCountry(this)"/></p>
<span class="popuptext" id="delivery_country_req"> 
  <li>Required</li>
</span>

<p>Email:
<input type="email" pattern="[a-zA-Z]+[@][a-zA-Z]+(?:[.][a-zA-Z]+)+" name="email" required oninvalid="invalidInput(this); validEmail(this)" onchange="validEmail(this)"/></p>
<span class="popuptext" id="email_requirements">     
  <li>Must be a valid email address</li>
</span>
<hr />


</form>
<input type="submit" value="Place Order" />


</credit_info>
<hr />

</body>
</html>
