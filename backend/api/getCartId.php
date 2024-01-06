<?php
session_start();
if(isset($_SESSION['cart_id']))
{
    echo $_SESSION['cart_id'];
}
else{
    echo "no cart Id Found";
}
?>