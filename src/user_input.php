<?php
if (!empty($_GET['msg'])) {
    if($_GET['msg']==1){
        echo '登録が完了しました。';
    }else{
        echo 'このユーザIDは既に使用されているため、登録できません。';
    }
    
}
?>

<style>
    #popup_overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center;
    }
    #popup_box {
        background: #fff; padding: 20px; border-radius: 8px; text-align: center;
    }
    #popup_box button { margin: 10px; }
</style>

<h1 style="text-align:center;">ユーザー情報登録</h1>

<form action="?do=user_save" method="post" id="userForm">

<div class="modal fade" id="confirmModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">確認</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        登録してもよろしいですか？
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button type="submit" class="btn btn-primary">Yes</button>
      </div>

    </div>
  </div>
</div>
<table class="table table-hover" width="100%" style="table-layout:fixed;">
<tr><td>ユーザID</td><td></td><td>氏名_姓</td><td>氏名_名</td></tr>
<tr>
    <td><input type="text" name="user_id" class="form-control" required></td>
    <td></td>
    <td><input type="text" name="user_l_name" class="form-control" required></td>
    <td><input type="text" name="user_f_name" class="form-control" required></td>
</tr>
<tr><td>初期パスワード</td><td></td><td>フリガナ_姓</td><td>フリガナ_名</td></tr>
<tr><td><input type="password" name="pass" class="form-control" required></td>
    <td></td>
    <td><input type="text" name="user_l_kana" class="form-control" required></td>
    <td><input type="text" name="user_f_kana" class="form-control" required></td>
</tr>
</table>
<input type="hidden" name="mode" value="input">
<button type="button" onclick="show_popup()" class="btn btn-primary">登録</button>
<!-- パスワードリセット確認ポップアップ -->
<div id="popup_overlay">
  <div id="popup_box">
    <p>本当にユーザーを登録しますか？</p>
      <button type="submit" name="reset_password">Yes</button>
      <button type="button" onclick="hide_popup()">No</button>
    </form>
  </div>
</div>
</form>

<script>
function show_popup() {
  const form = document.getElementById("userForm");

  // HTML5バリデーション実行
  if (!form.checkValidity()) {
    form.reportValidity(); // エラーメッセージ表示
    return; // ポップアップ出さない
  }

  // 問題なければポップアップ表示
  document.getElementById("popup_overlay").style.display = "flex";
}

function hide_popup() {
  document.getElementById("popup_overlay").style.display = "none";
}
</script>
