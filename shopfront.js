/*
 * This is a starting point only -- not yet complete!
 */

/*
 * item_id: string (id of item)
 * element: string (tag name of element)
 */
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
  e.innerHTML = value;
}

/*
 * e: object from DOM tree (item_quantity that made )
 * item_id: string (id of item)
 */
function updateLineCost(e, item_id) {
  let p = getStockItemValue(item_id, "item_price");
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
    sub_total += parseFloat(line_costs[i].innerHTML);
  }
  document.getElementById("sub_total").innerHTML = sub_total.toFixed(2);
  getDeliveryCost();
}

function getDeliveryCost() {
  let delivery_charge = 0;
  let sub_total = parseFloat(document.getElementById("sub_total").innerHTML);
  if (sub_total < 100) {
    delivery_charge = parseFloat(document.getElementById("sub_total").innerHTML) * 0.1;
  }
  document.getElementById("delivery_charge").innerHTML = delivery_charge.toFixed(2);
  getVAT();
}

function getVAT() {
  let sub_total = parseFloat(document.getElementById("sub_total").innerHTML);
  let delivery_charge = parseFloat(document.getElementById("delivery_charge").innerHTML);
  document.getElementById("vat").innerHTML = ((sub_total+delivery_charge) * 0.2).toFixed(2);
  getTotal();
}

function getTotal() {
  let sub_total = parseFloat(document.getElementById("sub_total").innerHTML);
  let delivery_charge = parseFloat(document.getElementById("delivery_charge").innerHTML);
  let VAT = parseFloat(document.getElementById("vat").innerHTML);
  document.getElementById("total").innerHTML = (VAT + delivery_charge + sub_total).toFixed(2);
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