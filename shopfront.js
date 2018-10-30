/*
 * This is a starting point only -- not yet complete!
 */

/*
 * item_id: string (id of item)
 * element: string (tag name of element)
 */


function hidePayment() {
  document.getElementsByTagName("credit_info")[0].style.display = "none";
}

function getStockItemValue(item_id, element) {
  let i = document.getElementById(item_id);
  let e = i.getElementsByTagName(element)[0];  // assume only 1!
  let v = e.innerHTML;
  return v;
}

/*
 * item_id: string (id of item)
 * element: string (tag name of element)
 * value: string (the value of the element)
 */
function setStockItemValue(item_id, element, value) {
  let i = document.getElementById(item_id);
  let e = i.getElementsByTagName(element)[0];  // assume only 1!
  e.innerHTML = "£" + value ;
}

/*
 * e: object from DOM tree (item_quantity that made )
 * item_id: string (id of item)
 */
function updateLineCost(e, item_id) {
  let p = getStockItemValue(item_id, "item_price");
  if (e.value == "") {
    e.value = 0;
  }
  let q = e.value;
  let c = p * q; // implicit type conversion
  c = c.toFixed(2); // 2 decimal places always.
  setStockItemValue(item_id, "line_cost", c);
  updateSubTotal();
  // Also need to update sub_total, delivery_charge, vat, and total.
}
function updateSubTotal() {
  let sub_total = 0;
  let line_costs = document.getElementsByTagName("line_cost");
  for (i = 1; i < line_costs.length; i++) { //First line_cost is a table heading so we can ignore
    sub_total += parseFloat(line_costs[i].innerHTML.substring(1));
  }
  document.getElementById("sub_total").innerHTML = "£" + sub_total.toFixed(2);
  getDeliveryCost();
}

function getDeliveryCost() {
  let delivery_charge = 0;
  let sub_total = parseFloat(document.getElementById("sub_total").innerHTML.substring(1));
  if (sub_total < 100) {
    delivery_charge = parseFloat(document.getElementById("sub_total").innerHTML.substring(1)) * 0.1;
  }
  document.getElementById("delivery_charge").innerHTML = "£" + delivery_charge.toFixed(2);
  getVAT();
}

function getVAT() {
  let sub_total = parseFloat(document.getElementById("sub_total").innerHTML.substring(1));
  let delivery_charge = parseFloat(document.getElementById("delivery_charge").innerHTML.substring(1));
  document.getElementById("vat").innerHTML ="£" + ((sub_total+delivery_charge) * 0.2).toFixed(2);
  getTotal();
}

function getTotal() {
  let sub_total = parseFloat(document.getElementById("sub_total").innerHTML.substring(1));
  let delivery_charge = parseFloat(document.getElementById("delivery_charge").innerHTML.substring(1));
  let VAT = parseFloat(document.getElementById("vat").innerHTML.substring(1));
  document.getElementById("total").innerHTML = "£" + (VAT + delivery_charge + sub_total).toFixed(2);
}

function validateCCNumber() {
  let type = document.getElementsByName("cc_type"); //should only be 1
  switch(type[0].value) {
    case "mastercard":
    document.getElementsByName("cc_number")[0].pattern = "[5]{1}[0-9]{15}";
    break;
    case "visa":
    document.getElementsByName("cc_number")[0].pattern = "[4]{1}[0-9]{15}";
    break;
  }
}

function validateMyForm(){
  if (confirm("Are you sure you want to submit?")) {
    document.getElementById("order").submit();
    return true;
  } else {
    return false;
  }
}

function showPaymentInfoForm() {
  switch (document.getElementsByTagName("credit_info")[0].style.display) {
    case "inline":
    document.getElementsByTagName("credit_info")[0].style.display = "none";
    break;
    case "none":
    document.getElementsByTagName("credit_info")[0].style.display = "inline";
  }
}

function invalidInput(obj) {
obj.style.border = "2px solid red";
}

function validCCNum(obj) {
  let cc_number = obj.value;
  let cc_type = document.getElementsByName("cc_type")[0].value;
  switch (cc_type) {
    case "visa":
    if (cc_number.substring(0, 1) == "4" && cc_number.length == 16) {
      obj.style.border = "2px solid green";
    }
    break;
    case "mastercard":
    if (cc_number.substring(0, 1) == "5" && cc_number.length == 16) {
      obj.style.border = "2px solid green";
    }
  }
}

function present(obj) {
  if (obj.value.length > 0) {
    obj.style.border = "2px solid green";
  }
}

function validCCCode(obj) {
  if (obj.value.length == 3 && obj.value > 0) {
    obj.style.border = "2px solid green";
  }
}