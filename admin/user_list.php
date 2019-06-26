<?php

//認証処理取り込み
require_once 'auth.php';

//セッション内にエラー情報のフラグが入っていたら取り出す
if(true === isset($_SESSION['output_buffer'])){
    $view_data = $_SESSION['output_buffer'];
}
//var_dump($view_data);
    
unset($_SESSION['output_buffer']);

//一覧の取得
$dbh = get_dbh();

$sql = 'SELECT * FROM admin_users ORDER BY updated;';
$pre = $dbh->prepare($sql);

//今回はバイトなしにてスキップ

$r = $pre->execute();

$data = $pre->fetchAll(PDO::FETCH_ASSOC);
//var_dump($data);

//role表示配列の作成
$role_print = array(
    '0' => '閲覧のみ',
    '1' => '通常操作',
    '2' => '管理者管理',
);

//CSRF
$csrf_token = create_csrf_token_admin();

?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>DB講座上級 管理画面</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1>一覧</h1>
        <a href="./top.php">TOPに戻る</a><br>
        <hr>
        
        <?php if(true === isset($view_data['register_success'])): ?>
        <span class="text-danger">管理者を新たに登録しました。<br></span>
        <?php endif; ?>

        <?php if(true === isset($view_data['delete_success'])): ?>
        <span class="text-danger">管理者を一件削除しました。<br></span>
        <?php endif; ?>

        <?php if(isset($view_data['error_csrf']) && true === $view_data['error_csrf']): ?>
        <span class="text-danger">CSRFトークンでエラーが起きました。正しい遷移を5分以内に操作してください。<br></span>
        <?php endif; ?>
        
        <table class="table table-hover">
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>role</th>
                <th>作成日</th>
                <th></th>
            </tr>
            <?php foreach($data as $datum): ?>
            <tr>
                <td><?php echo h($datum['user_id']); ?></td>
                <td><?php echo h($datum['name']); ?></td>
                <td><?php echo h($role_print[$datum['role']]); ?></td>
                <td><?php echo h($datum['created']); ?></td>
                <td><form action="./admin_password_change.php" method="get">
                        <input type="hidden" name="user_id" value="<?php echo h($datum['user_id']); ?>">
                        <button class="btn btn-nomal">パスワード上書き</button>
                    </form>                    
                </td>
                <td>
                    <form action="./user_delete.php" method="post">
                        <input type="hidden" name="user_id" value="<?php echo h($datum['user_id']); ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
                        <button class="btn btn-danger" onclick="return confirm('本当に削除しますか？');">削除する</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <hr>
        <a href="./logout.php">ログアウト</a>
        <hr>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>