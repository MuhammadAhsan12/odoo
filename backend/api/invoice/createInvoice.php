<?php
require_once('../../ripcord/ripcord.php');
// header('Content-Type: application/json');
class CreateInvoice
{
    protected $username;
    protected $password;
    protected $uid;

    protected $url = 'http://localhost:8069';
    protected $db = 'odoo';
    protected $admin_username = "muh.ahsan.cs@gmail.com";
    protected $admin_password = 'apple123';
    protected $admin_uid;
    function generateInvoice($partner_id)
    {
        $url = 'http://localhost:8069';
        $db = 'odoo';
        $username = 'muh.ahsan.cs@gmail.com';
        $password = 'apple123';

        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $password, array());

        if ($uid) {
            $models = ripcord::client("$url/xmlrpc/2/object");

            $saleOrderSearch = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order', 'search',array(array(array('partner_id', '=',$partner_id))),array());
      
            $cartIdRead = $models->execute_kw($db, $uid,  $this->admin_password, 'sale.order', 'read', array($saleOrderSearch), array('fields'=>array('id')));
            
            foreach($cartIdRead as $cart)
            {
              $cartId[]=$cart['id'];
              
            }
            $lastCartId=max($cartId);
           

            $newOrderLineId = $models->execute_kw($db, $uid, $this->admin_password, 'sale.order.line', 'search',array(array(array('order_id', '=',$lastCartId))),array());
            $ids = $models->execute_kw($db, $uid,  $this->admin_password, 'sale.order.line', 'read', array($newOrderLineId), array('fields'=>array('id', 'order_id','product_id', 'name_short','product_uom_qty','price_unit', 'order_partner_id')));
             
            $cartDetailed = [];
            foreach ($ids as $cartProduct) {
    
                $cartdata = array(0,0,array(
                        'product_id' => $cartProduct['product_id'][0],
                        'name' => $cartProduct['name_short'],
                        'quantity' => $cartProduct['product_uom_qty'],
                        'price_unit' => $cartProduct['price_unit'],
                    )
                );
                array_push($cartDetailed,$cartdata);
            }
            
// print_r($cartDetailed);
// die;

            $invoiceData = array(
                'name' => 'Invoice for Order ' . $lastCartId,
                'move_type' => 'out_invoice',
                // Use 'out_invoice' for customer invoice
                'partner_id' => $partner_id,
                // Replace with actual partner ID

                'invoice_line_ids' => 
                    $cartDetailed
                    // Add more invoice line items as needed
                
            );
    
            // Create an invoice
            $invoice_id = $models->execute_kw($db, $uid, $password, 'account.move', 'create', array($invoiceData));

            if ($invoice_id) {
                $response[] = $invoice_id;
                return json_encode($invoice_id);
            } else {
                return "Failed to create Invoice.";
            }
        } else {
            return "Authentication failed.";
        }
    }
}
$CreateInvoice = new CreateInvoice();
session_start();
if (isset($_SESSION['user_id'])) {
    $partner_id = $_SESSION['user_id'];
    echo $CreateInvoice->generateInvoice($partner_id);
} else {
    echo "no user found or cart found";
}
