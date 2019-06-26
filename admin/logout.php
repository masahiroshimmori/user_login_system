<?php

ob_start();
session_start();

unset($_SESSION['auth.php']);
//非ログインページへ
header('Location: ./index.php');