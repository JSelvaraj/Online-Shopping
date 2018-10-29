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
  // Also need to update sub_total, delivery_charge, vat, and total.
}

function updateSubtotal () {
  let line_costs = document.getElementsByTagName("line_cost");
  let subtotal = 0;
  for (i = 0;i <line_costs.length;i++) {
    subtotal += line_costs[i].innerHTML;
  }
  document.getElementById(subtotal).innerHTML = subtotal;
}
