<?php

ob_start();
session_start();

require_once '../common_function.php';
require_once '../common_auth.php';

date_default_timezone_set('Asia/Tokyo');

$user_input_data = array();
$error_detail = array();

$params = array('id', 'pass');
foreach ($params as $p) {
    $user_input_data[$p] = (string)@$_POST[$p];
    if('' === $user_input_data[$p]){
        $error_detail["error_must_{$p}"] = true;
    }
}

//var_dump($user_input_data);

//エラーが出たら入力ページに遷移する
if(false === empty($error_detail)){
    $_SESSION['output_buffer'] = $error_detail;
    //IDは保持する
    $_SESSION['output_buffer']['id'] = $user_input_data['id'];
    
    header('Location: ./index.php');
    exit();
}

//比較用のパスワード情報取得と比較
$dbh = get_dbh();

$sql = 'SELECT * FROM admin_users WHERE user_id = :user_id;';
$pre = $dbh->prepare($sql);

$pre->bindValue(':user_id', $user_input_data['id'], PDO::PARAM_STR);

$r = $pre->execute();

if(false === $r){
    echo 'システムエラーです';
    exit();
}

$datum = $pre->fetch(PDO::FETCH_ASSOC);
//var_dump($datum);

//ログイン処理(共通化)
$login_flg = login($user_input_data['pass'], $datum, 'admin_user_login_lock');

//var_dump($login_flg);

if(false === $login_flg){
    $_SESSION['ouput_buffer']['error_invalid_login'] = true;
    //IDは保持する
    $_SESSION['output_buffer']['id'] = $user_input_data['id'];
    
    header('Location: ./index.php');
    exit();
}

session_regenerate_id(true);

$_SESSION['admin_auth']['user_id'] = $datum['user_id'];
$_SESSION['admin_auth']['name'] = $datum['name'];
$_SESSION['admin_auth']['role'] = $datum['role'];

header('Location: ./top.php');