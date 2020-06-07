<?php
include("functions/int.php");
session_destroy();
redirect("login.php");

if(isset($_COOKIE['mail']))
{
    unset($_COOKIE['mail']);
    setcookie('mail','',time()-86400);
}









?>