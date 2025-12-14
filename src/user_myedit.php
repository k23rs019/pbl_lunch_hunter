<?php
if (!empty($_GET['error'])) {
    if($_GET['error']==1){
        showError('新パスワードが一致しません。');
    }elseif($_GET['error']==2){
        showError('現パスワードが違います。');
    }
    
}
require_once('model.php');
$user = new User();
$user_id = $_SESSION['user_id'];
$user_detail = $user->get_Userdetail(['user_id' => $user_id]);
?>
<h3>ユーザ情報編集</h3>
<form action="?do=user_myedit_save" method="post">
<table class="table table-hover">
<?php if($_SESSION['usertype_id']!=9){ ?>
<tr><td>ユーザ名変更：</td><td><input type="text" name="user_account" class="form-control" value="<?= htmlspecialchars($user_detail['user_account']) ?>" required></td></tr>
<?php }?>
<tr><td>現パスワード：</td><td><input type="password" name="pass" class="form-control" required></td></tr>
<tr><td>新パスワード：</td><td><input type="password" name="newpass" class="form-control"></td></tr>
<tr><td>新パスワード(再入力)：</td><td><input type="password" name="newpasscheck" class="form-control"></td></tr>
</table>
<input type="submit" value="更新" class="btn btn-primary">
</form>

<?php
function showError($message) {
        echo "<script>alert('エラー: " . addslashes($message) . "');</script>";
};
?>