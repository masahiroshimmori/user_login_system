<?php

ob_start();
session_start();

require_once ('common_function.php');
require_once ('user_data.php');

date_default_timezone_set('Asia/Tokyo');

$user_input_data = array();

$params = array('pass_1','pass_2' , 't');

foreach ($params as $p){
    
    $user_input_data[$p] = (string)@$_POST[$p];
}

//var_dump($user_input_data);

//ユーザ入力のvalidate
$error_detail = validate_user_password($user_input_data);

//csrfチェック
if(false === is_csrf_token()){
    $error_detail['error_csrf'] = true;
}

//エラーが出たら入力ページへ遷移
if(false === empty($error_detail)){
    
    $_SESSION['output_buffer'] = $error_detail;
    
    header('Location: ./reminder_password_input.php?t=' .rawurlencode($user_input_data['t']));
    exit();
}

$dbh = get_dbh();

//tokenのチェック
$sql = 'SELECT * FROM reminder_token where token = :token';
$pre = $dbh->prepare($sql);

$pre->bindValue(':token', $user_input_data['t'], PDO::PARAM_STR);

$r = $pre->execute();

if(false === $r){
    echo 'トークン確認中にシステムでエラーが発生しました';
    exit();
}

//データの取得＊今回は１件と明示的にわかっているのでfetchで
$datum = $pre->fetch(PDO::FETCH_ASSOC);
if(true === empty($datum)){
    echo '無効なトークンです。';
    exit();
}

//この時点でトークンの有効無効にかかわらずこのトークンは不要なので削除しておく
$sql = 'DELETE FROM reminder_token where token = :token';
$pre = $dbh->prepare($sql);

$pre->bindValue('token', $user_input_data['t'], PDO::PARAM_STR);
$pre->execute();

//有効時間をチェック
if(time() > (strtotime($datum['created']) + 3600)){
    echo 'tokenの有効時間（１時間）を超えています。改めて「<a href = "./reminder_input.php">トークンの発行</a>」から操作をお願いします。';
    exit();
}

//UPDATE文の作成と発行
$sql = 'UPDATE users SET pass = :pass, updated = :updated where user_id = :user_id;';
$pre = $dbh->prepare($sql);

$pre->bindValue(':user_id', $datum['user_id'], PDO::PARAM_STR);
$pre->bindValue(':pass', user_pass_hash($user_input_data['pass_1']), PDO::PARAM_STR);
$pre->bindValue(':updated', date(DATE_ATOM), PDO::PARAM_STR);

$r = $pre->execute();
if(false === $r){
    echo 'アップデート時にシステムでエラーが起きました';
    exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>パスワード再設定完了</title>
        <style>
            .error{ color: red;}
        </style>
    </head>
    <body>
        パスワードを変更しました。
    </body>
</html>