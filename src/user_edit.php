<?php
require_once('model.php');
$model = new User();

$user_id = $_GET['id'] ?? null;
$user = $model->get_userDetail(['user_id' => $user_id]);
?>
<style> 
body { font-family: Arial, sans-serif; margin: 20px; } 
label { display: block; margin-top: 10px; } 
input { width: 100%; padding: 8px; margin-top: 5px; } 
button { margin-top: 15px; padding: 10px 15px; } 
.readonly { background-color: #f0f0f0; } 
#popup_overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; } 
#popup_box { background: #fff; padding: 20px; border-radius: 8px; text-align: center; } 
#popup_box button { margin: 10px; } 
</style>
<h1 style="text-align:center;">管理者用ユーザー情報編集</h1>

<?php if ($user) : ?>

  <!-- ================= 編集用フォーム ================= -->
  <form action="?do=user_save" method="post">
    <table class="table table-hover" width="100%" style="table-layout:fixed;">
      <tr>
        <td>ユーザID</td>
        <td></td>
        <td>氏名_姓</td>
        <td>氏名_名</td>
      </tr>
      <tr>
        <td>
        <input type="text" class="form-control readonly" value="<?= htmlspecialchars($user['user_id']) ?>" readonly>
        </td>
        <td></td>
        <td>
          <input type="text" name="user_l_name" value="<?= $user['user_l_name'] ?>" required>
        </td>
        <td>
          <input type="text" name="user_f_name" value="<?= $user['user_f_name'] ?>" required>
        </td>
      </tr>

      <tr>
        <td>アカウント名</td>
        <td></td>
        <td>フリガナ_姓</td>
        <td>フリガナ_名</td>
      </tr>
      <tr>
        <td>
        <input type="text" class="form-control readonly" value="<?= htmlspecialchars($user['user_account']) ?>" readonly>

        </td>
        <td></td>
        <td>
          <input type="text" name="user_l_kana" value="<?= $user['user_l_kana'] ?>" required>
        </td>
        <td>
          <input type="text" name="user_f_kana" value="<?= $user['user_f_kana'] ?>" required>
        </td>
      </tr>
    </table>
    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
    <input type="hidden" name="mode" value="update">
    <button type="submit" class="btn btn-primary btn-lg">更新</button>
  </form>

  <br>

  <!-- ================= 操作ボタン群 ================= -->
  <div style="display:flex; gap:20px;">

    <!-- パスワードリセット（表示のみ） -->
    <button type="button" onclick="show_reset_popup()" class="btn btn-default">
      パスワードリセット
    </button>

    <!-- 停止 / 再開 -->
    <form action="?do=user_save" method="post">
      <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
      <?php if ($user['usertype_id'] == 1) : ?>
        <input type="hidden" name="mode" value="stop">
        <button type="submit" class="btn btn-danger btn-lg">アカウント停止</button>
      <?php else : ?>
        <input type="hidden" name="mode" value="restart">
        <button type="submit" class="btn btn-success btn-lg">アカウント再開</button>
      <?php endif; ?>
    </form>

  </div>

<?php else : ?>
  <p>ユーザーが見つかりません。</p>
<?php endif; ?>

<!-- ================= パスワードリセット確認ポップアップ ================= -->
<div id="popup_overlay" style="display:none;">
  <div id="popup_box">
    <p>本当にパスワードをリセットしますか？</p>

    <form action="?do=user_save" method="post">
      <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
      <input type="hidden" name="mode" value="reset">
      <button type="submit" class="btn btn-secondary">Yes</button>
      <button type="button" class="btn btn-danger" onclick="hide_reset_popup()">No</button>
    </form>

  </div>
</div>

<script>
  function show_reset_popup() {
    document.getElementById('popup_overlay').style.display = 'flex';
  }

  function hide_reset_popup() {
    document.getElementById('popup_overlay').style.display = 'none';
  }
</script>