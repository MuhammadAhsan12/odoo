<?php
require_once('../../ripcord/ripcord.php');
// header('Content-Type: application/json');
class GetInvoice
{
    protected $username;
    protected $password;
    protected $uid;

    protected $url = 'http://localhost:8069';
    protected $db = 'odoo';
    protected $admin_username = "muh.ahsan.cs@gmail.com";
    protected $admin_password = 'apple123';
    protected $admin_uid;


function getInvoice($invoiceNumber)
    {
        $url = 'http://localhost:8069';
        $db = 'odoo';
        $username = 'muh.ahsan.cs@gmail.com';
        $password = 'apple123';

        $common = ripcord::client("$url/xmlrpc/2/common");
        $uid = $common->authenticate($db, $username, $password, array());

        if ($uid) {
            $models = ripcord::client("$url/xmlrpc/2/object");

            // Search for the invoice using its number
            $invoiceId = $models->execute_kw(
                $db,
                $uid,
                $password,
                'account.move',
                'search',
                array(
                    array(
                        array('id', '=', $invoiceNumber)
                    )
                ),
                array()
            );

            // Read the invoice data
//             $fields = $models->execute_kw($db, $uid, $password, 'account.move', 'fields_get', array(), array('attributes' => array('string', 'help', 'type', 'required')));
// print_r($fields);
//             die;

            $invoiceData = $models->execute_kw(
                $db,
                $uid,
                $password,
                'account.move',
                'read',
                array($invoiceId),
                array("fields" => array('invoice_line_ids', 'date', 'invoice_date_due', 'payment_state', 'amount_total'))
            );
            // print_r($invoiceData);
            // die;

            if ($invoiceData) {
                $invoice_line_ids = $invoiceData[0]['invoice_line_ids'];
                // $integer_invoice_line_ids = array_map('intval', $invoice_line_ids);

                $line_item = $models->execute_kw(
                    $db,
                    $uid,
                    $password,
                    'account.move.line',
                    'read',
                    array($invoice_line_ids),
                    array("fields" => array('name', 'quantity', 'price_unit'))
                );

                // print_r($line_item);
                // die;

                return json_encode($line_item);
            } else {
                return "Invoice not found.";
            }
            // $response[] = $invoiceData;
            // return json_encode($invoiceData);
        } else {
            return "Invoice not found.";
        }
    }
}

session_start();
$GetInvoice=new GetInvoice();
if(isset($_SESSION['user_id']) && isset($_SESSION['cart_id']))
{

    // $GetInvoice->getInvoice();
}
else
{
    echo "no user found or cart found";
}