<?php

require_once 'auth_full_control.php';

//ユーザ入力情報を格納する変数を用意
$user_input_data = array();

$params = array('user_id');
foreach ($params as $p){
    $user_input_data[$p] = (string)@$_POST[$p];
}

//var_dump($user_input_data);

//ユーザ入力のvalidate
foreach ($params as $p) {
    if('' === $user_input_data[$p]){
        $error_detail["error_must_{$p}"] = true;
    }
}

//CSRFチェック
if(false === is_csrf_token_admin()){
    $error_detail['error_csrf'] = true;
}

//エラーが出たら入力ページに遷移
if(false === empty($error_detail)){
    $_SESSION['output_buffer'] = $error_detail;
    //削除なので入力情報を持ち回らない
    header('Location: ./user_list.php');
}

$dbh = get_dbh();

$sql = 'DELETE FROM admin_users WHERE user_id = :user_id;';
$pre = $dbh->prepare($sql);
$pre->bindValue(':user_id', $user_input_data['user_id'], PDO::PARAM_STR);

$r = $pre->execute();
if(false !== $r){
    //削除成功フラグを持ち回る
    $_SESSION['output_buffer']['delete_success'] = true;
}

header('Location: ./user_list.php');