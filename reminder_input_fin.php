<?php

ob_start();
session_start();

require_once ('common_function.php');
require_once ('user_data.php');

date_default_timezone_set('Asia/Tokyo');

$error_detail = array();

//ユーザ入力情報の取得

$email = (string)@$_POST['email'];

if('' === $email){
    $error_detail['error_must_email'] = true;
}

//CSRFチェック
if(false === is_csrf_token()){
    $error_detail['error_csrf'] = true;
}

//エラーが出たら入力ページに遷移する
if(false === empty($error_detail)){
    $_SESSION['output_buffer'] = $error_detail;
    
    header('Location: ./reminder_input.php');
    exit();
}

//データベースハンドルの取得
$dbh = get_dbh();

$sql = 'SELECT * FROM users where email = :email;';
$pre = $dbh->prepare($sql);

$pre->bindValue(':email', $email, PDO::PARAM_STR);
$r = $pre->execute();

if(false === $r){
    echo 'システムでエラが起きました';
    exit();
}

//セレクトした内容の取得
$datum = $pre->fetch(PDO::FETCH_ASSOC);
//var_dump($datum);

//emailが存在していたら作業を継続
if(false !== $datum){
    //トークンの作成
    $token = hash('sha512', openssl_random_pseudo_bytes(128));
    //var_dump($token);


    //トークンとユーザIDをトークン管理テーブルに入れる
    $sql = 'INSERT INTO reminder_token(token, user_id, created) VALUES(:token, :user_id, :created);';
    $pre = $dbh->prepare($sql);

    $pre->bindValue(':token', $token, PDO::PARAM_STR);
    $pre->bindValue(':user_id', $datum['user_id'], PDO::PARAM_INT);
    $pre->bindValue(':created', date(DATE_ATOM), PDO::PARAM_STR);

    $r = $pre->execute();
    if(false === $r){
        echo 'システムでエラーが発生しました';
        exit();
    }

    //mail用の本文を作成
    $mail_body = <<<EOD
以下のURLに一時間以内にアクセスして、パスワードを設定してください。
http://localhost/user_login_system/reminder_password_input.php?t={$token}
EOD;
    var_dump($mail_body);
}

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>送信完了</title>
        <style>
            .error{color: red;}
        </style>
    </head>
    <body>
        入力いただいたメールアドレスに送信しました。<br>
        もし一定時間経過してもメールが届かない場合、入力ミスの可能性がありますので、お手数ですが再度操作手続きをお願いします。
    </body>
</html>