function del(cartId, productIds) {
  const rawResponse = fetch(
    "http://localhost/odooo/backend/api/clearCart.php",
    {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ cartid: cartId, productid: productIds }),
    }
  );

  const body = JSON.stringify({ cartid: cartId, productid: productIds });
  console.log(body);
  //   const content = rawResponse.json();

  //   console.log(content);
}

const productIds = [];
let cartId;

const url_product = "http://localhost/odooo/backend/api/getCartContent.php";

fetch(url_product)
  .then((response) => {
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    return response.json();
  })
  .then((data) => {
    // Add items to the list one by one

    let products = data;
    const cartContainer = document.getElementById("cartContainer");
    console.log(products);
    let subTotal = 0;
    // Assuming newdata is an array you want to iterate over
    products.forEach(function (data) {
      let cartHTML = `<tr>
                                <td class="row">
                                    <div class="left col-lg-3 col-md-3 col-3">
                                        <input type="hidden" name="pid" value="${
                                          data["product_id"][0]
                                        }"/>
                                        <input type="checkbox" class="productCheckbox" data-product-id="${
                                          data["product_id"][0]
                                        }" name="" id="">
                                    <img  alt="" class="img-fluid">
                                    </div>
                                    <div class="right col-lg-6 col-md-9 col-7">
                                        <h4>${data["name_short"]}</h4>
                                        <span>250kg</span>
                                    </div>
                                </td>
                                <td>
                                    <input type="number" class="quantity"  value="${
                                      data["product_uom_qty"]
                                    }">
                                </td>
                                <td>
                                    <h4>${
                                      data["price_unit"] *
                                      data["product_uom_qty"]
                                    } SR</h4>
                                </td>
                            </tr>
            `;
      /////

      cartContainer.innerHTML += cartHTML;

      //////
      console.log(productIds);
      cartId = parseInt(data["order_id"][0]);
      console.log(cartId);
      subTotal += data["price_unit"] * data["product_uom_qty"];

      console.log(data["name_short"]);
      //
    });
    document.getElementById("subTotal").innerHTML = subTotal + " SR";
    document.getElementById("grandTotal").innerHTML = subTotal + " SR";
  })
  .catch(function (error) {
    console.error("Fetch error:", error);
  });
const productCheckbox = cartContainer.querySelector(
  `[data-product-id="${data["product_id"][0]}"]`
);

productCheckbox.addEventListener("change", function () {
  if (productCheckbox.checked) {
    productIds.push(data["product_id"][0]);
  } else {
    const index = productIds.indexOf(data["product_id"][0]);
    if (index !== -1) {
      productIds.splice(index, 1);
    }
  }
});
const deleteButton = document.getElementById("deleteButton");
deleteButton.addEventListener("click", function () {
  alert('Helo World');
  let checkboxes = document.querySelector('.productCheckBox');
  let  ids = [];
  checkboxes.forEach(box => {
    if(box.checked) {
      ids.push(box.getAttribute('data-product-id'));
    }
  });
  // Assuming 'cartId' and 'productIds' are available in this scope
  del(cartId, ids);
});
// Update the cart display
updateCartDisplay();
function updateCartDisplay() {
  // var cartContainer = document.getElementById('cart');
  // cartContainer.innerHTML = '';
  // cart.forEach(function (item) {
  //     var itemElement = document.createElement('div');
  //     itemElement.textContent = `${item.productName} x${item.quantity} - $${item.price}`;
  //     cartContainer.appendChild(itemElement);
  // });
}
