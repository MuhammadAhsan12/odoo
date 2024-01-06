<?php
require_once('../ripcord/ripcord.php');
// header('Content-Type: application/json');
class ClearCart
{
    protected $username;
    protected $password;
    protected $uid;

    protected $url = 'http://localhost:8069';
    protected $db = 'odoo';
    protected $admin_username = "muh.ahsan.cs@gmail.com";
    protected $admin_password = 'apple123';
    protected $admin_uid;

    function clearUserCarts($cart_id,$product_ids)
    {

        $url = 'http://localhost:8069';
        $db = 'odoo';
        $username = "muh.ahsan.cs@gmail.com";
        $password = 'apple123';

        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $this->admin_password, array());

        if ($uid) {

            $models = ripcord::client("$this->url/xmlrpc/2/object");
            // $cart_ids = $this->getUserCarts($user_id);
            // Create a new order line (add product to cart)
            foreach ($product_ids as $product_id) {
                // $saleOrderId = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order', 'unlink', array($cart_id));
                $newOrderLineId = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order.line', 'search',array(array(array('order_id', '=',$cart_id),array('product_id', '=',(int)$product_id))),array());
                $ids = $models->execute_kw($db, $uid,  $this->admin_password, 'sale.order.line', 'unlink', array($newOrderLineId), array());
      
            }
            $newOrderLineIds = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order.line', 'search',array(array(array('order_id', '=',$cart_id))),array());
            $Id = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order.line', 'read',array($newOrderLineIds),array());
            
            if($Id==[])
            {
                session_start();
                unset($_SESSION['cart_id']);
            }
            // $response[] = $newOrderLineId;

            // echo json_encode($response);

            // return $newOrderLineId;
return $Id;

        } else {
            echo "No IDs found.";
        }

        // $models = ripcord::client("$this->url/xmlrpc/2/object");

        // // Fetch cart IDs associated with the user
        // $cart_ids = $this->getUserCarts($user_id);
        // $response[] = $cart_ids;

        // echo json_encode($response);

        // foreach ($cart_ids as $cart_id) {
        //     // Delete the cart from the Odoo database
        //     $models->execute_kw($this->db, $this->uid, $this->admin_password, 'sale.order', 'unlink', array(array($cart_id)));
        // }
        // $response[] = $cart_ids;

        //         echo json_encode($response);
    }
}


$ClearCart=new ClearCart();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request body
    $json_data = file_get_contents("php://input");

    $data = json_decode($json_data, true);

    $cartId = $data['cartid'];
    $productIds = $data['productid'];

  
    $response= $ClearCart->clearUserCarts((int)$cartId,$productIds);

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
// if (isset($_POST['remove_cart'])) {
   
//    session_start();
       
//    $cart_id=$_SESSION['cart_id'];
//    echo $cart_id;
//    $_SESSION['cart']=false;
//    $ClearCart->clearUserCarts((int)$cart_id);
//    }
?>