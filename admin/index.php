<?php

ob_start();
session_start();

require_once '../common_function.php';

//var_dump($_SESSION);

$view_data = array();

if(true === isset($_SESSION['output_buffer'])){
    $view_data = $_SESSION['output_buffer'];
}

//var_dump($view_data);

//二重出力しないようにセッション内の情報をリセット
unset($_SESSION['output_buffer']);

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>管理画面</title>
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <h1>管理画面</h1>
            
            <div class="row">
                <?php if(isset($view_data['error_invalid_login']) && true === $view_data['error_invalid_login']): ?>
                <span class="text_danger">メールアドレスまたはパスワードに誤りがあります<br></span>
                <?php endif; ?>
                
                <?php if(isset($view_data['error_must_id']) && true === $view_data['error_must_id']): ?>
                <span class="text_danger">管理IDが未入力です。<br></span>
                <?php endif; ?>
                
                <?php if(isset($view_data['error_must_pass']) && true === $view_data['error_must_pass']): ?>
                <span class="text_danger">パスワードが未入力です。<br></span>
                <?php endif; ?>
                
                <form action="./login.php" method="post" class="form_signin col-md-4">
                    <input type="text" name="id" class="form-control" placeholder="ID" value="<?php echo h(@$view_data['id']); ?>" required autofocus>
                    <input type="password" name="pass" class="form-control" placeholder="Password" required>
                    <button type="submit" class="btn btn-lg btn-primary btn-block">ログイン</button>
                </form>
                
            </div><!--row-->
        </div><!--container-->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>