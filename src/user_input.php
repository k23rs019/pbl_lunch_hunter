<?php
if (!empty($_GET['msg'])) {
    if($_GET['msg']==1){
        echo '登録が完了しました。';
    }else{
        echo 'ユーザーIDが重複、または空欄があります。';
    }
    
}
?>

<h1 style="text-align:center;">ユーザー情報登録</h1>

<form action="?do=user_save" method="post">
<table class="table table-hover" width="100%" style="table-layout:fixed;">
<tr><td>ユーザID</td><td></td><td>氏名姓</td><td>氏名名</td></tr>
<tr>
    <td><input type="text" name="user_id" class="form-control" required></td>
    <td></td>
    <td><input type="text" name="user_l_name" class="form-control" required></td>
    <td><input type="text" name="user_f_name" class="form-control" required></td>
</tr>
<tr><td>初期パスワード</td><td></td><td>フリガナ姓</td><td>フリガナ名</td></tr>
<tr><td><input type="password" name="pass" class="form-control" required></td>
    <td></td>
    <td><input type="text" name="user_l_kana" class="form-control" required></td>
    <td><input type="text" name="user_l_kana" class="form-control" required></td>
</tr>
</table>
<input type="hidden" name="mode" value="input">
<input type="submit" value="登録" class="btn btn-primary">
</form>
