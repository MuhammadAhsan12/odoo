<?php
require_once('../ripcord/ripcord.php');
// header('Content-Type: application/json');
class testing
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
 function getCartContents($user_id)
{
  $url = 'http://localhost:8069';
  $db = 'odoo';
  $username = "muh.ahsan.cs@gmail.com";
  $password = 'apple123';

  $common = ripcord::client("$url/xmlrpc/2/common");
  $uid = $common->authenticate($db, $username, $password, array());

  if ($uid) {
      $models = ripcord::client("$this->url/xmlrpc/2/object");
      $saleOrderSearch = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order', 'search',array(array(array('partner_id', '=',$user_id))),array());
      
      $cartIdRead = $models->execute_kw($db, $uid,  $this->admin_password, 'sale.order', 'read', array($saleOrderSearch), array('fields'=>array('id')));
      
      foreach($cartIdRead as $cart)
      {
        $cartId[]=$cart['id'];
        
      }
      $lastCartId=max($cartId);
      $newOrderLineId = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order.line', 'search',array(array(array('order_id', '=',$lastCartId))),array());
      $ids = $models->execute_kw($db, $uid,  $this->admin_password, 'sale.order.line', 'read', array($newOrderLineId), array('fields'=>array('id', 'order_id','product_id', 'name_short','product_uom_qty','price_unit', 'order_partner_id')));
      
      // $newOrderLineId = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order', 'search',array(array('id','=','54')),array());
      // $ids = $models->execute_kw($db, $uid,  $this->admin_password, 'sale.order', 'read', array($newOrderLineId), array());
    //   $newOrderLineId = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order.line', 'search',array(array(array('order_id', '=',$cart_id))),array());
    //   $ids = $models->execute_kw($db, $uid,  $this->admin_password, 'sale.order.line', 'read', array($newOrderLineId), array('fields'=>array('id', 'order_id','product_id', 'name_short','product_uom_qty','price_unit', 'order_partner_id')));
      
      return json_encode($ids);
  } else {
      return "No IDs found.";
  }
}
}
session_start();
$testing=new testing();
if(isset($_SESSION['user_id']))
{
    $user_id=$_SESSION['user_id'];
    // echo $cart_id;
    echo $testing->getCartContents($user_id);    
}
else{
    echo "NO cart added";
}
?>