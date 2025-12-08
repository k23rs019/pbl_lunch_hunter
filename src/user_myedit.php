<h3>ユーザー情報登録</h3>
<form action="?do=user_myedit_save" method="post">
<table class="table table-hover">
<tr><td>ユーザ名変更：</td><td><input type="text" name="user_account" class="form-control"></td></tr>
<tr><td>現パスワード：</td><td><input type="password" name="pass" class="form-control"></td></tr>
<tr><td>新パスワード：</td><td><input type="password" name="newpass" class="form-control"></td></tr>
<tr><td>新パスワード(再入力)：</td><td><input type="password" name="newpasscheck" class="form-control"></td></tr>
</table>
<input type="submit" value="更新" class="btn btn-primary">
</form>