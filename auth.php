<?php

ob_start();
session_start();

require_once 'common_function.php';

//var_dump($_SESSION);

if(false === isset($_SESSION['auth'])){
    header('Location: ./index.php');
    exit();
}

