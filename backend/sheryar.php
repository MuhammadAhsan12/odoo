<?php
require_once('ripcord/ripcord.php');
header('Content-Type: application/json');
class auth
{
    protected $username;
    protected $password;
    protected $uid;

    protected $url = 'http://localhost:8069';
    protected $db = 'aqahi';
    protected $admin_username = "zkermark@gmail.com";
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
            "company_id" => 1,
            // Replace with actual company ID
            "active" => true,
        );

        // print_r($newUserData);
        $new_uid = $models->execute_kw($this->db, $uid, $password, 'res.users', 'create', array($newUserData));
        if ($new_uid) {
            return $uid;
            // header('location:../frontend/signin.html');
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
            $this->username = $username;
            $this->password = $password;
            $this->uid = $uid;
            header('location:../frontend/index.html');
        }
    }

    function getProduct()
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
        $productIds = $models->execute_kw($db, $uid, $this->admin_password, 'product.product', 'search', array($searchCriteria));

        if ($productIds) {
            // Fetch product information for the retrieved product IDs
            $products = $models->execute_kw($db, $uid, $this->admin_password, 'product.product', 'read', array($productIds));
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
                        'category_name' => $product['categ_id'][1],
                    );
                    $response[] = $productData;
                }

                echo json_encode($response);
            } else {
                echo json_encode(array('error' => 'No products found.'));
            }
        } else {
            echo "No product IDs found.";
        }
    }

    function addToCart()
    {
        $url = 'http://localhost:8069';
        $db = 'aqahi';
        $username = "zkermark@gmail.com";
        $password = 'apple123';

        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $password, array());

        if ($uid) {
            $models = ripcord::client("$this->url/xmlrpc/2/object");

            // Cart (sale order) ID to add the product
            $cart_id = 37; // Replace with the actual cart (order) ID

            // Prepare the order line data
            $orderLineData = array(
                array(
                    'order_id' => $cart_id,
                    'product_id' => 1,
                    'product_uom_qty' => 2,
                ),
                array(
                    'order_id' => $cart_id,
                    'product_id' => 2,
                    'product_uom_qty' => 2,
                )

            );

            // Create a new order line (add product to cart)
            $newOrderLineId = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order.line', 'create', array($orderLineData));

            $response[] = $newOrderLineId;

            echo json_encode($response);
        } else {
            echo "No IDs found.";
        }
    }

    function getOrder()
    {
        $url = 'http://localhost:8069';
        $db = 'aqahi';
        $username = "zkermark@gmail.com";
        $password = 'apple123';

        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $password, array());

        if ($uid) {
            $models = ripcord::client("$this->url/xmlrpc/2/object");
            $ids = $models->execute_kw($db, $uid, $password, 'sale.order.line', 'search', array(array(array('id', '=', '37'))), array());
            $ids = $models->execute_kw($db, $uid, $password, 'sale.order', 'read', array(['37']), array());
            print_r($ids);
            die;
        }
    }
    function createSaleOrder()
    {

        $url = 'http://localhost:8069';
        $db = 'aqahi';
        $username = "zkermark@gmail.com";
        $password = 'apple123';

        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $this->admin_password, array());

        if ($uid) {
            $models = ripcord::client("$this->url/xmlrpc/2/object");


            // Prepare sale order data
            $partner_id = 1; // Replace with actual partner ID
            $product_id = 1; // Replace with actual product ID
            $quantity = 2;

            // Create a new sale order (cart)
            $orderData = array(
                'partner_id' => $partner_id,
                'order_line' => array(
                    array(
                        0, 0,
                        array(
                            'product_id' => $product_id,
                            'product_uom_qty' => $quantity,
                        )
                    )
                ),
            );


            try {
                $newOrder = $models->execute_kw($db, $uid, $password, 'sale.order', 'create', array($orderData));

                if ($newOrder) {
                    // Order creation succeeded
                    $response[] = $newOrder;

                    echo json_encode($response);
                } else {
                    // Order creation failed
                }
            } catch (Exception $e) {
                // Handle the exception
                echo "Exception: " . $e->getMessage();
            }
        } else {
            echo "No IDs found.";
        }
    }

    protected function getUserCarts($user_id)
    {
        // Implement logic to fetch user's cart IDs
        // For demonstration, using a simple array to store cart IDs
        // Replace this with actual logic to retrieve user's cart IDs from the database
        $userCarts = array(
            1,
            // Example cart IDs
            2,
        );
        return $userCarts;
    }

    protected function getCartContents($cart_id)
    {
        // Implement logic to fetch cart contents (order lines)
        // For demonstration, using a simple array to store cart contents
        // Replace this with actual logic to retrieve cart contents from the database
        $cartContents = array(
            array(
                'product_id' => 1,
                // Example product ID
                'quantity' => 2,
            ),
            array(
                'product_id' => 2,
                'quantity' => 3,
            ),
        );
        return $cartContents;
    }

    protected function clearUserCarts($user_id)
    {

        $url = 'http://localhost:8069';
        $db = 'odoo';
        $username = "muh.ahsan.cs@gmail.com";
        $password = $this->password;

        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $this->admin_password, array());

        if ($uid) {
            $models = ripcord::client("$this->url/xmlrpc/2/object");
            $cart_ids = $this->getUserCarts($user_id);
            // Create a new order line (add product to cart)
            foreach ($cart_ids as $cart_id) {
                $newOrderLineId = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order', 'unlink', array($cart_id));
            }
            // $response[] = $newOrderLineId;

            // echo json_encode($response);

            return $newOrderLineId;
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

    function confirmOrder()
    {
        $url = 'http://localhost:8069';
        $db = 'aqahi';
        $username = "zkermark@gmail.com";
        $password = 'apple123';
        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $this->admin_password, array());

        if ($uid) {
            $cart_id = 37;
            $models = ripcord::client("$this->url/xmlrpc/2/object");
            try {
                $confirmed = $models->execute_kw(
                    $db,
                    $uid,
                    $password,
                    'sale.order',
                    'action_confirm',
                    array($cart_id)
                );

                if ($confirmed) {
                    try {
                        $updated = $models->execute_kw(
                            $db,
                            $uid,
                            $password,
                            'sale.order',
                            'write',
                            array(array($cart_id), array('state' => 'paid'))
                        );

                        if ($updated) {
                            // Order status update succeeded
                            // Checkout process completed
                        } else {
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
    public function getAllUsers()
    {
        $url = 'http://localhost:8069';
        $db = 'odoo';
        $username = "muh.ahsan.cs@gmail.com";
        $password = $this->password;

        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $this->admin_password, array());

        if ($uid) {

            $models = ripcord::client("$this->url/xmlrpc/2/object");

            // Search criteria (empty array to retrieve all users)
            $searchCriteria = array();

            // Fields to fetch for each user
            $userFields = array(
                "name",
                "login",
                "email",
                // Add more fields here if needed
            );

            // Fetch user IDs based on search criteria
            $userIds = $models->execute_kw($this->db, $uid, $this->admin_password, 'res.users', 'search', array($searchCriteria));

            if ($userIds) {
                // Fetch user information for the retrieved user IDs
                $users = $models->execute_kw($this->db, $uid, $this->admin_password, 'res.users', 'read', array($userIds, $userFields));

                $response[] = $users;

                echo json_encode($response);
            } else {
                return array();
            }
        } else {
            echo "No IDs found.";
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
// $auth->getProduct();
// $auth->addToCart();
// $auth->createSaleOrder();
//$auth->getAllUsers();
// $auth->confirmOrder();

$auth->getOrder();
