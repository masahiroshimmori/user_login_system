<?php

require_once './auth.php';

if(2 > $_SESSION['admin_auth']['role']){
    header('Location: ./top.php');
    exit();
}