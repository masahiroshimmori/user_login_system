<?php

ob_start();
session_start();

require_once ('common_function.php');
require_once ('user_data.php');

//タイムゾーンの設定
date_default_timezone_set('Asia/Tokyo');

$user_input_data = array();

$params = array('name','email', 'pass1', 'pass2');

foreach ($params as $p){
    $user_input_data[$p] = (string)@$_POST[$p];
}

//var_dump($user_input_data);

//validate
//基本のエラーチェック
$error_detail = validate_user($user_input_data);
//var_dump($error_detail);

//csrfのチェック
if(false === is_csrf_token()){
    $error_detail['error_csrf'] = true; 
}
//エラーが出たら入力ページへ遷移する
if(false === empty($error_detail)){
    //エラー情報をセッションに入れて持ち回る
    $_SESSION['output_buffer'] = $error_detail;
    //var_dump($_SESSION); 
    //入力値をセッションに追加して持ち回る
    $_SESSION['output_buffer'] += $user_input_data;
    //var_dump($_SESSION);
    header('Location: ./user_register.php');
   exit();    
    
}

//データベースハンドルの取得
$dbh = get_dbh();

$sql = 'INSERT INTO users(name, email, pass, created, updated) VALUE (:name, :email, :pass, :created, :updated);';
$pre = $dbh->prepare($sql);

$pre->bindValue(':name', $user_input_data['name'], PDO::PARAM_STR);
$pre->bindValue(':email', $user_input_data['email'], PDO::PARAM_STR);
$pre->bindValue(':pass', password_hash($user_input_data['pass1'], PASSWORD_DEFAULT), PDO::PARAM_STR);
$pre->bindValue(':created', date(DATE_ATOM), PDO::PARAM_STR);
$pre->bindValue(':updated', date(DATE_ATOM), PDO::PARAM_STR);

$r = $pre->execute();

if(false === $r){
    echo 'システムエラー';
    exit();
}

//var_dump($_SESSION);
unset($_SESSION['output_buffer']);
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>登録完了</title>
        <style>
            .error{
                color: red;
            }
        </style>
    </head>
    <head>
        入力いただきましてありがとうございました。
    </head>
</html>