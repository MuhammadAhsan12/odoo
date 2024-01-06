<?php
require_once('../ripcord/ripcord.php');
// header('Content-Type: application/json');
class OrderConfirm
{
    protected $username;
    protected $password;
    protected $uid;

    protected $url = 'http://localhost:8069';
    protected $db = 'odoo';
    protected $admin_username = "muh.ahsan.cs@gmail.com";
    protected $admin_password = 'apple123';
    protected $admin_uid;


function confirmOrder($user_id)
    {
        $cart_id=62;
        $url = 'http://localhost:8069';
        $db = 'odoo';
        $username = "muh.ahsan.cs@gmail.com";
        $password = 'apple123';
        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $this->admin_password, array());

        if ($uid) {
            $models = ripcord::client("$this->url/xmlrpc/2/object");
            $saleOrderSearch = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order', 'search',array(array(array('partner_id', '=',$user_id))),array());
      
            $cartIdRead = $models->execute_kw($db, $uid,  $this->admin_password, 'sale.order', 'read', array($saleOrderSearch), array('fields'=>array('id')));
            
            foreach($cartIdRead as $cart)
            {
              $cartId[]=$cart['id'];
              
            }
            $lastCartId=max($cartId);
           
            try {
                $confirmed = $models->execute_kw(
                    $db,
                    $uid,
                    $password,
                    'sale.order',
                    'action_confirm',
                    array($lastCartId)
                );
                if ($confirmed) {
                    try {
                        // echo $confirmed;
                        $updated = $models->execute_kw(
                            $db,
                            $uid,
                            $password,
                            'sale.order',
                            'write',
                            array(array($lastCartId), array('state' => 'paid'))
                        );

                        if ($updated) {
                            
                session_start();
                unset($_SESSION['cart_id']);
                            // $newOrderLineId = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order.line', 'search',array(array(array('order_id', '=',$cart_id))),array());
                            // $ids = $models->execute_kw($db, $uid,  $this->admin_password, 'sale.order.line', 'unlink', array($newOrderLineId), array());
                            // session_start();
                            // unset($_SESSION['cart_id']);
                            // echo "jjj";
                            // session_start();
                            // unset($_SESSION['cart_id']);
                             header('location:../../frontend/order-confirm.html');
                            // echo $updated;
                            // Order status update succeeded
                            // Checkout process completed
                        } else {
                            header('location:../../frontend/order-cancel.html');
                            // Order status update failed
                            // Handle the error here
                        }
                    } catch (Exception $e) {
                        // Handle the exception
                        echo "Exception: " . $e->getMessage();
                    }
                } else {
                    // Sale order confirmation failed
                    // Handle the error here
                }
            } catch (Exception $e) {
                // Handle the exception
                echo "Exception: " . $e->getMessage();
            }
        }
    }
}

$OrderConfirm=new OrderConfirm();
session_start();
if(isset($_SESSION['user_id']))
{
    $user_id=$_SESSION['user_id'];
    $OrderConfirm->confirmOrder($user_id);

}
else{
    echo "No cart Added";
}
// if (isset($_POST['remove_cart'])) {
   
// //    session_start();
       
// //    $cart_id=$_SESSION['cart_id'];
// //    echo $cart_id;
// //    $_SESSION['cart']=false;
//    }
?>
