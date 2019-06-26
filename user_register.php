<?php

ob_start();
session_start();

require_once ('common_function.php');

//セッションに入っている情報を確認
//var_dump($_SESSION);

//セッション内にエラーフラグが入っていたら取り出す
$view_data = array();
if(true === isset($_SESSION['output_buffer'])){
    $view_data = $_SESSION['output_buffer'];
}

//var_dump($view_data);

//二重出力しないように出力情報を削除
unset($_SESSION['output_buffer']);

//var_dump($view_data);

//csrfの取得
$csrf_token = create_csrf_token();

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>ユーザー登録フォーム</title>
        <style type="text/css">
            .error{color: red;}
        </style>
    </head>
    <body>
        <?php if(isset($view_data['error_csrf']) && true === $view_data['error_csrf']) :?>
        <span class="error">CSRFエラーが起きました。正しい遷移を５分以内に操作してください。<br></span>
        <?php endif; ?>
        
        <form action="./user_register_fin.php" name="" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
            
        <?php if(isset($view_data['error_must_name']) && true === $view_data['error_must_name']): ?>
            <span class="error">名前が未入力です。<br></span>
        <?php endif; ?>
            名前：<input type="text" name="name" value="<?php echo h(@$view_data['name']); ?>"><br>
            
        <?php if(isset($view_data['error_must_email']) && true === $view_data['error_must_email']): ?>
            <span class="error">メールアドレスが未入力です。<br></span>
        <?php endif; ?>
        <?php if(isset($view_data['error_format_email']) && true === $view_data['error_format_email']): ?>
            <span class="error">メールアドレスの書式に誤りがあります。<br></span>
        <?php endif; ?>            
            メールアドレス：<input type="text" name="email" value="<?php echo h(@$view_data['email']); ?>"><br>

        <?php if(isset($view_data['error_must_pass1']) && true === $view_data['error_must_pass1']): ?>
            <span class="error">パスワードが未入力です。<br></span>
        <?php endif; ?>            
        <?php if(isset($view_data['error_toolong_pass1']) && true === $view_data['error_toolong_pass1']): ?>
            <span class="error">パスワードが長すぎます。<br></span>
        <?php endif; ?>
        <?php if(isset($view_data['error_tooshort_pass1']) && true === $view_data['error_tooshort_pass1']): ?>
            <span class="error">パスワードは4文字以上で設定してください。<br></span>
        <?php endif; ?>               
            パスワード：<input type="password" name="pass1" value=""><br>         
                       
        <?php if(isset($view_data['error_invalid_pass']) && true === $view_data['error_invalid_pass']): ?>
            <span class="error">パスワードが一致しません。<br></span>
        <?php endif; ?>            
            パスワード(再)：<input type="password" name="pass2" value=""><br>           
            <br>
            <button type="submit">データ登録</button>
        </form>
    </body>
</html>