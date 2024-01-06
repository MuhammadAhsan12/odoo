<?php

require_once('ripcord/ripcord.php');
// header('Content-Type: application/json');
class auth
{
    protected $username;
    protected $password;
    protected $uid;

    protected $url = 'http://localhost:8069';
    protected $db = 'odoo';
    protected $admin_username = "muh.ahsan.cs@gmail.com";
    protected $admin_password = 'apple123';
    protected $admin_uid;

    function signUp(
        $name,
        $user_name,
        $password,
        $email,
        $phone
    ) {


        $common = ripcord::client("$this->url/xmlrpc/2/common");
        $models = ripcord::client("$this->url/xmlrpc/2/object");
        $uid = $common->authenticate($this->db, $this->admin_username, $this->admin_password, array());


        $newUserData = array(
            "name" => $name,
            "login" => $user_name,
            "password" => $password,
            "email" => $email,

            // "phone" => $phone,
            "company_id" => 1,  // Replace with actual company ID
            "active" => true,
        );

        // print_r($newUserData);
        $new_uid = $models->execute_kw($this->db, $uid, $password, 'res.users', 'create', array($newUserData));
        if ($new_uid) {
            header('location:../frontend/signin.html');
        }
    }
    //     function addToCart($product_id, $quantity)
    //     {

    //         $url = 'http://localhost:8069/';
    //         $db = 'odoo';
    //         $username = "muh.ahsan.cs@gmail.com";
    //         $password = $this->password;

    //         $common = ripcord::client("$url/xmlrpc/2/common");
    //         $uid = $common->authenticate($db, $username, $this->admin_password, array());

    //         if ($uid) {
    //             $models = ripcord::client("$this->url/xmlrpc/2/object");

    //             $cart_id = 10; // Replace with the actual cart (order) ID
    // // Replace with the actual cart (order) ID

    //             // Prepare the order line data
    //             $orderLineData = array(
    //                 'order_id' => $cart_id,
    //                 'product_id' => $product_id,
    //                 'product_uom_qty' => $quantity,
    //             );

    //             // Create a new order line (add product to cart)
    //             $newOrderLineId = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order.line', 'create', array($orderLineData));

    //             $response[] = $newOrderLineId;

    //             echo json_encode($response);

    //         } else {
    //             echo "No IDs found.";
    //         }
    //     }
    function getUserId()
    {
        $url = 'http://localhost:8069/';
        $db = 'odoo';
        $username = "muh.ahsan.cs@gmail.com";
        $password = $this->password;

        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $this->admin_password, array());
        return $uid;
    }
    function addToCart($product_id, $quantity)
    {

        $url = 'http://localhost:8069/';
        $db = 'odoo';
        $username = "muh.ahsan.cs@gmail.com";
        $password = $this->password;

        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $this->admin_password, array());

        if ($uid) {
            $models = ripcord::client("$this->url/xmlrpc/2/object");

            // Cart (sale order) ID to add the product
            $cart_id = 10; // Replace with the actual cart (order) ID

            // Prepare the order line data
            $orderLineData = array(
                'partner_id' => 12,
                'product_id' => $product_id,
                'product_uom_qty' => $quantity,
            );
            // Create a new order line (add product to cart)
            $newOrderLineId = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order.line', 'create', array($orderLineData));

            $response[] = $newOrderLineId;

            echo json_encode($response);
        } else {
            echo "No IDs found.";
        }
    }

    function getCategory()
    {
        $url = 'http://localhost:8069';
        $db = 'odoo';
        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $this->admin_username, $this->admin_password, array());

        $models = ripcord::client("$url/xmlrpc/2/object");

        // Search criteria (e.g., all active products)
        $searchCriteria = array(
            array('active', '=', true)
        );

        // Fetch product IDs based on search criteria
        $categoryIds = $models->execute_kw($db, $uid, $this->admin_password, 'product.category', 'search', array($searchCriteria));

        if ($categoryIds) {
            // Fetch product information for the retrieved product IDs
            $categorys = $models->execute_kw($db, $uid, $this->admin_password, 'product.category', 'read', array($categoryIds));
            if ($categorys) {
                $response_cat = array();

                foreach ($categorys as $category) {


                    $categoryData = array(
                        'cat_id' => $category['id'],
                        'ca' => $category['name'],
                    );
                    $response_cat[] = $categoryData;
                }
                // Fetch product information for the retrieved product IDs
                echo json_encode($response_cat);
            } else {
                echo json_encode(array('error' => 'No products found.'));
            }
        } else {
            echo "No product IDs found.";
        }
    }
    function getProduct()
    {
        $url = 'http://localhost:8069';
        $db = 'odoo';
        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $this->admin_username, $this->admin_password, array());
        $catid = isset($_GET['catid']) ? $_GET['catid'] : '';
        // print_r($catid);
        // die;
        $models = ripcord::client("$url/xmlrpc/2/object");
        if (empty($catid) || $catid==1) {
            $searchCriteria = array(
                array('active', '=', true)
            );
        } else {
            $searchCriteria =
                    array(
                        array('active', '=', true),
                        array('categ_id', '=', (int)$catid)
                );
        }
        // Search criteria (e.g., all active products)
        
        $productIds = $models->execute_kw($db, $uid, $this->admin_password, 'product.product', 'search', array($searchCriteria), array());
        // $productIds = $models->execute_kw($db, $uid, $this->admin_password, 'product.product', 'search', array(array(array('categ_id', '=', (int)$catid), array('active', '=', true))), array());
        // $productIds = $models->execute_kw($db, $uid, $this->admin_password, 'product.product', 'search', array($searchCriteria), array());
        // print_r($productIds);
        // die;
        if ($productIds) {
            // Fetch product information for the retrieved product IDs
            $products = $models->execute_kw($db, $uid, $this->admin_password, 'product.product', 'read', array($productIds), array());
            if ($products) {
                $response = array();

                foreach ($products as $product) {

                    $productData = array(
                        'id' => $product['id'],
                        'rating' => $product['rating_count'],
                        'image_gallery1' => $product['image_512'],
                        'image_gallery2' => $product['image_1920'],
                        'image_gallery3' => $product['image_1920'],
                        'name' => $product['name'],
                        'list_price' => $product['list_price'],
                        'weight' => $product['weight'],
                        'description' => $product['description_sale'],
                        'image' => $product['image_1920'],
                        'category_id' => $product['categ_id'][0],
                        'category_name' => $product['categ_id'][1],
                        'category' => $product['categ_id'],
                    );
                    $response[] = $productData;
                }
                // Fetch product information for the retrieved product IDs
                echo json_encode($response);
            } else {
                echo json_encode(array('error' => 'No products found.'));
            }
        } else {
            echo "No product IDs found.";
        }
    }

    function login($username, $password)
    {
        $url = 'http://localhost:8069';
        $db = 'odoo';

        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($this->db, $this->admin_username, $this->admin_password, array());
        // $uid = $common->authenticate($db, $username, $password, array());

        if ($uid) {
            $_SESSION['user_id']=$uid;
            $this->username = $username;
            $this->password = $password;
            $this->uid = $uid;
            header('location:../frontend/index.html');
        }
        else
        {
            header("location:../frontend/signin.html");
        }
    }
}

$auth = new auth();
if (isset($_POST['signup'])) {

    $fullName = $_POST['full_name'];
    $userEmail = $_POST['user_email'];
    $phoneNumber = $_POST['phone_number'];
    $userPassword = $_POST['user_password'];
    $auth->signUp($fullName, $userEmail, $userPassword, $userEmail, $phoneNumber);
}
if (isset($_POST['login'])) {
    $userEmail = $_POST['user_email'];
    $userPassword = $_POST['user_password'];
    $auth->login($userEmail, $userPassword);
}
if (isset($_POST['quantity_submit'])) {
    $quanity = (int)$_POST['quantity'];
    $pid = (int)$_POST['pid'];
    $auth->addToCart($pid, $quanity);
    echo "================================================";
}
$auth->getProduct();
