<?php
require_once('model.php');
$model = new User();

// ユーザIDをGETで受け取る
$user_id = $_GET['id'] ?? null;
$user = $model->getDetail("user_id='" . $user_id . "'");

?>
<style>
  body { font-family: Arial, sans-serif; margin: 20px; }
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

<h1 style="text-align:center;">管理者用ユーザー情報編集</h1>
<div>
  <?php if ($user): ?>
  <form action="?do=user_save" method="post">
  <table class="table table-hover" width="100%" style="table-layout:fixed;">
  <tr><td>ユーザID</td><td></td><td>氏名姓</td><td>氏名名</td></tr>
  <tr>
      <td><input type="text" name="user_id" class="form-control" value="<?=$user['user_id'] ?>" readonly></td>
      <td></td>
      <td><input type="text" name="user_l_name" class="form-control" value="<?=$user['user_l_name'] ?>" required></td>
      <td><input type="text" name="user_f_name" class="form-control" value="<?=$user['user_f_name'] ?>" required></td>
  </tr>
  <tr><td>アカウント名</td><td></td><td>フリガナ姓</td><td>フリガナ名</td></tr>
  <tr><td><input type="text" name="user_account" class="form-control" value="<?=$user['user_account'] ?>" readonly></td>
      <td></td>
      <td><input type="text" name="user_l_kana" class="form-control" value="<?=$user['user_l_kana'] ?>" required></td>
      <td><input type="text" name="user_f_kana" class="form-control" value="<?=$user['user_f_kana'] ?>" required></td>
  </tr>
  </table>
</div>
<table width="100%" style="table-layout:fixed;">
  <tr><td>
    <button type="button" onclick="show_reset_popup()" class="btn btn-default">パスワードリセット</button>
  </td><td></td><td>
    <input type="hidden" name="mode" value="update">
    <input type="submit" value="編集確定" class="btn btn-primary btn-lg">
  </form>
  </td><td>
    <form action="?do=user_save" method="post">
      <?php if($user['usertype_id']==1){ ?>
        <input type="hidden" name="user_id" value="<?=$user['user_id'] ?>">
        <input type="hidden" name="mode" value="stop">
        <input type="submit" value="アカウント停止" class="btn btn-danger btn-lg">
      <?php }elseif($user['usertype_id']==2){ ?>
        <input type="hidden" name="user_id" value="<?=$user['user_id'] ?>">
        <input type="hidden" name="mode" value="restart">
        <input type="submit" value="アカウント再開" class="btn btn-success btn-lg">
      <?php } ?>
    </form>
  </tr></table>

    <!-- パスワードリセットは確認ポップアップを経由 -->
    

</form>
<?php else: ?>
  <p>ユーザーが見つかりません。</p>
<?php endif; ?>

<!-- パスワードリセット確認ポップアップ -->
<div id="popup_overlay">
  <div id="popup_box">
    <p>本当にパスワードをリセットしますか？</p>
    <form method="post">
      <input type="hidden" name="user_id" value="<?=$user['user_id'] ?>">
      <input type="hidden" name="mode" value="reset">
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