<?php
session_start();
require_once('../../ripcord/ripcord.php');
// header('Content-Type: application/json');
class MultiInvoiceGet
{
    protected $username;
    protected $password;
    protected $uid;

    protected $url = 'http://localhost:8069';
    protected $db = 'odoo';
    protected $admin_username = "muh.ahsan.cs@gmail.com";
    protected $admin_password = 'apple123';
    protected $admin_uid;


function getMultiInvoice($partnerID)
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
                        array('partner_id', '=', $partnerID)
                    )
                ),
                array()
            );


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
            return json_encode($invoiceData);
        } else {
            return "Invoice not found.";
        }
    }
}

$user_id=$_SESSION['user_id'];
$MultiInvoiceGet = new MultiInvoiceGet();
echo $MultiInvoiceGet->getMultiInvoice($user_id);
