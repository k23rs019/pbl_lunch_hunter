<?php
require_once('model.php');
$model = new User();

// ユーザIDをGETで受け取る
$user_id = $_GET['user_id'] ?? null;
$user = $model->getDetail("user_id='" . $user_id . "'");

// 更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $data = [
        'user_name'    => $_POST['user_name'] ?? '',
        'user_kana'    => $_POST['user_kana'] ?? '',
        'account_name' => $_POST['account_name'] ?? ''
    ];
    $where = "user_id='" . $user_id . "'";
    $model->update($data, $where);

    header("Location: user_list_admin.php");
    exit;
}

// パスワードリセット処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $data = [
        'password' => password_hash("init1234", PASSWORD_DEFAULT) // 初期パスワード
    ];
    $where = "user_id='" . $user_id . "'";
    $model->update($data, $where);

    echo "<script>alert('パスワードを初期化しました');</script>";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>管理者用ユーザー情報編集</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    form { max-width: 560px; margin: auto; }
    label { display: block; margin-top: 10px; }
    input { width: 100%; padding: 8px; margin-top: 5px; }
    button { margin-top: 15px; padding: 10px 15px; }
    .readonly { background-color: #f0f0f0; }
    #popup_overlay {
      position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center;
    }
    #popup_box {
      background: #fff; padding: 20px; border-radius: 8px; text-align: center;
    }
    #popup_box button { margin: 10px; }
  </style>
</head>
<body>
  <h1>管理者用ユーザー情報編集</h1>
  <?php if ($user): ?>
  <form method="post">
    <label for="user_id">ユーザーID</label>
    <input type="text" id="user_id" name="user_id" value="<?= htmlspecialchars($user['user_id'] ?? '') ?>" readonly class="readonly">

    <label for="account_name">アカウント名</label>
    <input type="text" id="account_name" name="account_name" value="<?= htmlspecialchars($user['account_name'] ?? '') ?>">

    <label for="user_name">氏名</label>
    <input type="text" id="user_name" name="user_name" value="<?= htmlspecialchars($user['user_name'] ?? '') ?>">

    <label for="user_kana">氏名（カナ）</label>
    <input type="text" id="user_kana" name="user_kana" value="<?= htmlspecialchars($user['user_kana'] ?? '') ?>">

    <div style="margin-top:20px;">
      <button type="submit" name="update">編集</button>
      <!-- パスワードリセットは確認ポップアップを経由 -->
      <button type="button" onclick="show_reset_popup()">パスワードリセット</button>
      <button type="button" onclick="window.location.href='user_list_admin.php'">戻る</button>
    </div>
  </form>
  <?php else: ?>
    <p>ユーザーが見つかりません。</p>
  <?php endif; ?>

  <!-- パスワードリセット確認ポップアップ -->
  <div id="popup_overlay">
    <div id="popup_box">
      <p>本当にパスワードをリセットしますか？</p>
      <form method="post">
        <button type="submit" name="reset_password">Yes</button>
        <button type="button" onclick="hide_reset_popup()">No</button>
      </form>
    </div>
  </div>

  <script>
    function show_reset_popup() {
      document.getElementById("popup_overlay").style.display = "flex";
    }
    function hide_reset_popup() {
      document.getElementById("popup_overlay").style.display = "none";
    }
  </script>
</body>
</html>