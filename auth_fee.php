<?php

ob_start();
session_start();

require_once 'common_function.php';

//var_dump($_SESSION);

if(1 !== $_SESSION['auth']['role']){
    header('Location: ./top.php');
    exit();
}