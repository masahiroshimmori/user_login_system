<?php

require_once './auth_full_control.php';

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>管理者用トップページ</title>
        <style>
            .error{color: red;}
        </style>
    </head>
    <body>
        <h1>ログイン後TopPage:管理権限のあるPage</h1>
        <a href="./top.php">閲覧のみでもOK Page</a><br>
        <a href="./top_nomal.php">通常ユーザ以上　Page</a><br>
        <a href="./top_full_control.php">管理者権限のあるPage</a><br>
        <a href="./logout.php">ログアウト</a>
    </body>
</html>