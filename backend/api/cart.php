<?php
require_once('../ripcord/ripcord.php');
// header('Content-Type: application/json');
class cart
{
    protected $username;
    protected $password;
    protected $uid;

    protected $url = 'http://localhost:8069';
    protected $db = 'odoo';
    protected $admin_username = "muh.ahsan.cs@gmail.com";
    protected $admin_password = 'apple123';
    protected $admin_uid;

// // Set the session variable "cart1" to true
function sendUserId()
{
    $url = 'http://localhost:8069';
    $db = 'odoo';
    $username = "muh.ahsan.cs@gmail.com";
    $password = 'apple123';

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());
    return $uid;
}
    function addToCart($cart_id,$pid,$quantity)
    {
        $url = 'http://localhost:8069';
        $db = 'odoo';
        $username = "muh.ahsan.cs@gmail.com";
        $password = 'apple123';

        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $password, array());

        if ($uid) {
            $models = ripcord::client("$this->url/xmlrpc/2/object");

            // Cart (sale order) ID to add the product
          // Replace with the actual cart (order) ID
            // Prepare the order line data
            $orderLineData = array(
                array(
                    'order_id' => $cart_id,
                    'product_id' => $pid,
                    'product_uom_qty' => $quantity,
                )
            );

            
          //   $orderLineData = array(
          //     array(
          //         'order_id' => $cart_id,
          //         'product_id' => 1,
          //         'product_uom_qty' => 2,
          //     ),
          //     array(
          //         'order_id' => $cart_id,
          //         'product_id' => 2,
          //         'product_uom_qty' => 2,
          //     )
          // );
            // Create a new order line (add product to cart)
            $newOrderLineId = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order.line', 'create', array($orderLineData));
           header('location:../../frontend/cart.html');


            // return json_encode($newOrderLineId);
        } else {
            return "No IDs found.";
        }
    }
  


function createSaleOrder($quantity,$pid,$partner_id)
{
    $url = 'http://localhost:8069';
    $db = 'odoo';
    $username = 'muh.ahsan.cs@gmail.com';
    $password = 'apple123';

    $common = ripcord::client("$url/xmlrpc/2/common");
    $uid = $common->authenticate($db, $username, $password, array());

    if ($uid) {
        $models = ripcord::client("$url/xmlrpc/2/object");

        // Define partner ID and product ID
        // $partner_id = 2; 
        // $product_id = 1; 
        // $quantity = 2;

        // Prepare sale order data
        $orderData = array(
            'partner_id' => $partner_id,
            'order_line' => array(
                array(array(
                    'product_id' => $pid,
                    'product_uom_qty' => $quantity,
                ))
            ),
        );

        // Create a new sale order (cart)
        $order_id = $models->execute_kw($db, $uid, $password, 'sale.order', 'create', array($orderData));

        if ($order_id) {

            return json_encode($order_id);
        } else {
            return "Failed to create Sale Order.";
        }
    } else {
        return "Authentication failed.";
    }
}
}
$cart=new cart();


if (isset($_POST['quantity_submit'])) {
  session_start();

  if(isset($_SESSION['cart_id']))
  {
    $quanity=(int)$_POST['quantity'];
    $pid=(int)$_POST['pid'];
    $cart_id=$_SESSION['cart_id'];
    echo $cart_id;
   $order_id= $cart->addToCart((int)$cart_id,$pid,$quanity);
   echo $order_id;
  }
  else{
    $quanity=(int)$_POST['quantity'];
    $pid=(int)$_POST['pid'];
    $user_id=$_SESSION['user_id'];
   $cart_id=$cart->createSaleOrder($quanity,$pid,$user_id);
   $order_id= $cart->addToCart((int)$cart_id,$pid,$quanity);
   
   $_SESSION['cart_id']= $cart_id;
  }
}
// if (isset($_POST['remove_cart'])) {
   
// session_start();
    
// $cart_id=$_SESSION['cart_id'];
// echo $cart_id;
// $_SESSION['cart']=false;
// $cart->clearUserCarts((int)$cart_id);
// }
  

?>
