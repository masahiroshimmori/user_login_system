<?php

//パスワード再設定のためのメールアドレス入力画面

ob_start();
session_start();

require_once ('common_function.php');

//セッション内にエラー情報のフラグが入っていたら取り出す
$view_data = array();
if(true === isset($_SESSION['output_buffer'])){
    $view_data = $_SESSION['output_buffer'];
}

//var_dump($view_data);

//二重に出力しないように
unset($_SESSION['output_buffer']);

$csrf_token = create_csrf_token();

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
    <title>パスワード再設定</title>
    <style>
        .error{color: red;}
    </style>
    </head>
    <body>
        <?php if (isset($view_data['error_csrf']) && true === $view_data['error_csrf']): ?>
            <span class="error">CSRFトークンエラーで起きました。正しい遷移を5分以内に操作してください。<br></span>
        <?php endif ;?>
            
        入力されたメールアドレスにパスワード再設定用のURLを送信します。
        
        <form action="./reminder_input_fin.php" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
            
            <?php if(isset($view_data['error_must_email']) && true === $view_data['error_must_email']): ?>
            <span class="error">メールアドレスが未入力です。<br></span>
            <?php endif; ?>
                メールアドレス：<input type="text" name="email" value="<?php echo h(@$view_data['email']); ?>"><br>
                <br>
                <button type="submit">メール送信</button>
        </form>
    </body>
</html>
