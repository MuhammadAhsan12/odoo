<?php
require_once('../ripcord/ripcord.php');
// header('Content-Type: application/json');
class CategoryGet
{
    protected $username;
    protected $password;
    protected $uid;

    protected $url = 'http://localhost:8069';
    protected $db = 'odoo';
    protected $admin_username = "muh.ahsan.cs@gmail.com";
    protected $admin_password = 'apple123';
    protected $admin_uid;

    function getCategory()
    {

        $url = 'http://localhost:8069';
        $db = 'odoo';
        $username = "muh.ahsan.cs@gmail.com";
        $password = 'apple123';

        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $this->admin_password, array());

        if ($uid) {

            $models = ripcord::client("$this->url/xmlrpc/2/object");

            $newOrderLineId = $models->execute_kw($db, $uid, $this->admin_password, 'product.category', 'search',array(array()), array());
            // print_r($newOrderLineId);
            // die;
            $ids = $models->execute_kw($db, $uid,  $this->admin_password, 'product.category', 'read',array($newOrderLineId), array('fields'=>array('name', 'id')));
            // $cart_ids = $this->getUserCarts($user_id);
            // print_r($ids);
            // die;
            return json_encode($ids);
  } else {
      return "No IDs found.";
  }
    }
}
// session_start();
$CategoryGet=new CategoryGet();
// $cart_id=$_SESSION['cart_id'];
// echo $cart_id;
$category=$CategoryGet->getCategory();
echo $category;
