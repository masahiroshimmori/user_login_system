<?php
require_once './auth_nomal.php';
//validate_user_password用
require_once '../user_data.php';
 
date_default_timezone_set('Asia/Tokyo');

$user_input_data = array();

$params = array('email', 'pass_1', 'pass_2');
foreach ($params as $p) {
    $user_input_data[$p] = (string)@$_POST[$p];
}
//var_dump($user_input_data);

//validate
//パスワードチェック
$error_detail = validate_user_password($user_input_data);
//空チェック
foreach ($params as $p) {
    if('' === $user_input_data[$p]){
        $error_detail["error_must_{$p}"] = true;
    }    
}

//CSRF
if(false === is_csrf_token_admin()){
    $error_detail['error_csrf'] = true;
}

if(false === empty($error_detail)){
    $_SESSION['output_buffer'] = $error_detail;
    $_SESSION['output_buffer'] += $user_input_data;
    header('Location: ./front_password_change.php');
    exit();
}

$dbh = get_dbh();
$sql = 'UPDATE users SET pass = :pass, updated = :updated WHERE email = :email;';
$pre = $dbh->prepare($sql);

$pre->bindValue(':email', $user_input_data['email'], PDO::PARAM_STR);
$pre->bindValue(':pass', user_pass_hash($user_input_data['pass_1']), PDO::PARAM_STR);
$pre->bindValue(':updated', date(DATE_ATOM), PDO::PARAM_STR);

$r = $pre->execute();
if(false === $r){
    echo 'システムでエラーが起きました。';
    exht();
}

//影響行の確認
//update文によって作用した行数が０なら該当がないのでエラーを返す
//emailカラムはUNIQUE制約が付いているので２行以上の作用はない前提

if(1 !== $pre->rowCount()){
    $_SESSION['output_buffer']['error_invalid_email'] = true;
    $_SESSION['output_buffer'] += $user_input_data;
    header('Location: ./front_password_change.php');
}
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>一般ユーザパスワード変更完了</title>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>
<div class="container">

  <a href="./top.php">topに戻る</a><br>

  <h1>ユーザパスワード変更</h1>
    パスワードを変更しました。
</div>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>