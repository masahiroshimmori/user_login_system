<?php

ob_start();
session_start();

require_once('common_function.php');

//var_dump($_SESSION);

$view_data = array();

if(true === isset($_SESSION['output_buffer'])){
    $view_data = $_SESSION['output_buffer'];
}

//var_dump($view_data);

unset($_SESSION['output_buffer']);

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>会員ログイン画面</title>
        <style>
            .error{color: red;}
        </style>
    </head>
    <body>
        <form action="./login.php" method="post">
            
            <?php if(isset($view_data['error_invalid_login']) && true === $view_data['error_invalid_login']): ?>
            <span class="error">メールアドレスまたはパスワードに誤りがあります。<br></span>
            <?php endif; ?>
                
            <?php if(isset($view_data['error_must_email']) && true === $view_data['error_must_email']): ?>
                <span class="error">メールアドレスが未入力です。<br></span>
            <?php endif; ?>
                <input type="text" name="email" value="<?php echo h(@$view_data['email']); ?>"><br>
                
            <?php if(isset($view_data['error_must_pass']) && true === $view_data['error_must_pass']): ?>
                <span class="error">パスワードが未入力です。<br></span>
            <?php endif; ?>
                <input type="password" name="pass" value=""><br>
                <br>
                <button type="submit">ログイン</button>
        </form>
    </body>
</html>