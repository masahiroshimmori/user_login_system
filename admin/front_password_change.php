<?php
//通常管理者以上
require_once './auth_nomal.php';

$view_data = array();

if(true === isset($_SESSION['output_buffer'])){
    $view_data = $_SESSION['output_buffer'];
}

//var_dump($view_data);

unset($_SESSION['output_buffer']);

//CSRF取得
$csrf_token = create_csrf_token_admin();
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>一般ユーザパスワード変更画面</title>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>
<div class="container">

  <a href="./top.php">topに戻る</a><br>

  <h1>ユーザパスワード変更</h1>
<?php if ( (isset($view_data['error_csrf']))&&(true === $view_data['error_csrf']) ) : ?>
    <span class="text-danger">CSRFトークンでエラーが起きました。正しい遷移を、５分以内に操作してください。<br></span>
<?php endif; ?>

<?php if ( (isset($view_data['error_must_email']))&&(true === $view_data['error_must_email']) ) : ?>
    <span class="text-danger">emailが未入力です<br></span>
<?php endif; ?>
<?php if ( (isset($view_data['error_invalid_email']))&&(true === $view_data['error_invalid_email']) ) : ?>
    <span class="text-danger">emailが存在しません<br></span>
<?php endif; ?>
<?php if ( (isset($view_data['error_must_pass_1']))&&(true === $view_data['error_must_pass_1']) ) : ?>
    <span class="text-danger">パスワードが未入力です<br></span>
<?php endif; ?>
<?php if ( (isset($view_data['error_must_pass_2']))&&(true === $view_data['error_must_pass_2']) ) : ?>
    <span class="text-danger">パスワード(再)が未入力です<br></span>
<?php endif; ?>
<?php if ( (isset($view_data['error_invalid_pass']))&&(true === $view_data['error_invalid_pass']) ) : ?>
    <span class="text-danger">パスワードとパスワード(再)が不一致です<br></span>
<?php endif; ?>
<?php if ( (isset($view_data['error_toolong_pass_1']))&&(true === $view_data['error_toolong_pass_1']) ) : ?>
    <span class="error">パスワードは72文字以内でお願いします<br></span>
<?php endif; ?>


  <form action="./front_password_change_fin.php" method="post">
  <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">

  <table class="table table-hover">
  <tr>
    <th>eメールアドレス
    <td><input name="email" value="<?php echo h(@$view_data['email']); ?>">
  <tr>
    <th>パスワード
    <td><input name="pass_1" type="password" value="">
  <tr>
    <th>パスワード(再)
    <td><input name="pass_2" type="password" value="">
  </table>
  <button>ユーザのパスワードを上書きする</button>
  </form>

</div>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>