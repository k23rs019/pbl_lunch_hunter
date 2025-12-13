<h3>ユーザー情報登録</h3>

<form action="?do=user_input_save" method="post">
<table class="table table-hover">
<tr><td>ユーザID</td><td></td><td>氏名姓</td><td>氏名名</td></tr>
<tr>
    <td><input type="text" name="user_account" class="form-control"></td>
    <td></td>
    <td><input type="text" name="user_sei" class="form-control"></td>
    <td><input type="text" name="user_mei" class="form-control"></td>
</tr>
<tr><td>初期パスワード</td><td></td><td>フリガナ姓</td><td>フリガナ名</td></tr>
<tr><td><input type="password" name="pass" class="form-control"></td>
    <td></td>
    <td><input type="text" name="user_kanasei" class="form-control"></td>
    <td><input type="text" name="user_kanamei" class="form-control"></td>
</tr>
</table>
<input type="submit" value="登録" class="btn btn-primary">
</form>
