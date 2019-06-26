<?php

//パスワード再設定のページ

ob_start();
session_start();

require_once ('common_function.php');

$token = (string)@$_GET['t'];

$view_data = array();

if(true === isset($_SESSION['output_buffer'])){
    $view_data = $_SESSION['output_buffer'];
}
//var_dump($view_data);

unset($view_data);

$csrf_token = create_csrf_token();

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>パスワード再設定</title>
        <style>
            .error{ color: red;}
        </style>
    </head>
    <body>
        <?php if(isset($view_data['error_csrf']) && true === $view_data['error_csrf']): ?>
            <span class="error">CSRFトークンでエラーが発生しました。正しい遷移を5分以内に操作してください。<br></span>
        <?php endif ; ?>
            
            パスワードを変更します。
            
            <form action="./reminder_password_input_fin.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
                <input type="hidden" name="t" value="<?php echo h($token); ?>">
                
                <?php if(isset($view_data['error_must_email_1']) && true === $view_data['error_must_email_1']) : ?>
                <span class="error">パスワードが未入力です。<br></span>
                <?php endif; ?>
                
                <?php if(isset($view_data['error_toolong_email_1']) && true === $view_data['error_toolong_email_1']) : ?>
                <span class="error">パスワードは72文字以内でお願いします。<br></span>
                <?php endif; ?>

                <?php if(isset($view_data['error_tooshort_email_1']) && true === $view_data['error_tooshort_email_1']) : ?>
                <span class="error">パスワードは4文字以上でお願いします。<br></span>
                <?php endif; ?>
                
                パスワード：<input type="password" name="pass_1" value=""><br>
                
                <?php if(isset($view_data['error_invalid_pass']) && true === $view_data['error_invalid_pass']) : ?>
                <span class="error">パスワードが異なります。<br></span>
                <?php endif; ?>
                
                パスワード（再）：<input type="password" name="pass_2" value=""><br>
                <br>
                <button type="submit">再設定</button>
                
            </form>
    </body>
</html>