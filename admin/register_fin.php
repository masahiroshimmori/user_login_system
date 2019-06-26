<?php

require_once './auth_full_control.php';
//password_hash
require_once '../user_data.php';

date_default_timezone_set('Asia/Tokyo');

//入力情報を受け取る
$user_input_data = array();

$params = array('user_id', 'name', 'role', 'pass_1', 'pass_2');
foreach($params as $p){
    $user_input_data[$p] = (string)@$_POST[$p];
}

//確認
//var_dump($user_input_data);

//ユーザ入力のvalidate
//パスワードチェック
$error_detail = validate_user_password($user_input_data);

//必須入力のチェック
foreach($params as $p){
    if('' === $user_input_data[$p]){
        $error_detail["error_must_{$p}"] = true;
    }
}

//roleは大雑把に値を揃えておく（0−2の間のみを許容）
$user_input_data['role'] = abs($user_input_data['role']) % 3;

//CSRF
if(false === is_csrf_token_admin()){
    $error_detail['error_csrf'] = true;
}

//エラーが出たら入力ページに遷移する
if(false === empty($error_detail)){
    $_SESSION['output_buffer'] = $error_detail;

    //エラー情報と一緒に入力データも持ち回る
    $_SESSION['output_buffer'] += $user_input_data;

    header('Location: ./register.php');
    exit();
}

$dbh = get_dbh();

$sql ='INSERT INTO admin_users(user_id, name, pass, role, created, updated)VALUES(:user_id, :name, :pass, :role, :created, :updated);';
$pre = $dbh->prepare($sql);

$pre->bindValue(':user_id', $user_input_data['user_id'], PDO::PARAM_STR);
$pre->bindValue(':name', $user_input_data['name'], PDO::PARAM_STR);
$pre->bindValue(':pass', user_pass_hash($user_input_data['pass_1']), PDO::PARAM_STR);
$pre->bindValue(':role', (int)$user_input_data['role'], PDO::PARAM_INT);
$pre->bindValue(':created', date(DATE_ATOM), PDO::PARAM_STR);
$pre->bindValue(':updated', date(DATE_ATOM), PDO::PARAM_STR);

$r = $pre->execute();
if(false === $r){
    //Duplicate entry 'user_id' for key 'PRIMART'なら入力画面に突き返す。普通に起きうるエラーなので
    $e = $pre->errorInfo();
    //var_dump($e);
    if(0 === strncmp($e[2], 'Duplicate entry', strlen('Duplicate entry'))){
        $_SESSION['output_buffer']['error_overlap_user_id'] = true;
        $_SESSION['output_buffer'] += $user_input_data;
        header('Location: ./register.php');
        exit();
    }
    //else
    echo 'システムでエラーが起きました。';
    exit();

}
//登録したメッセージを出力するためのフラグを持ち回る
$_SESSION['output_buffer']['register_success'] = true;
header('Location: ./user_list.php');
