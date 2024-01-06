<?php
require_once('ripcord/ripcord.php');
require_once('odooAuth.php');

// $url = 'http://localhost:8069';
// $db = 'odoo';
// $username = "zkermark@gmail.com";
// $api_key = '572282eeadc5c2df51d4dc03440df08a69f12767';

// $common = ripcord::client("$url/xmlrpc/2/common");
// $uid = $common->authenticate($db, $username, $api_key, array());

// $models = ripcord::client("$url/xmlrpc/2/object");
// $ids = $models->execute_kw($db, $uid, $api_key, 'res.partner', 'search', array(array(array('is_company', '=', true))), array('limit'=>1, 'offset' => 2));
// $records = $models->execute_kw($db, $uid, $api_key, 'res.partner', 'read', array($ids), array('fields'=>array('name', 'country_id', 'comment')));
// $fields = $models->execute_kw($db, $uid, $api_key, 'res.users', 'fields_get', array(), array('attributes' => array('string', 'help', 'type', 'required')));

// $new_uid = $models->execute_kw($db, $uid, $api_key, 'res.partner', 'create', array(array('name'=>'Test partner')));
// $new_user = $models->execute_kw($db, $uid, $api_key, 'res.partner', 'search_read',array(array(array('id', '=', $new_uid))), array('fields' => array('name')));
// print_r($fields);

// signUp('shery', 'dexter', 'apple123', 'zker@gmail.com');
getProduct();
