<?php
require_once('model.php');
$model = new User();
//エラー処理メソッド

function showError($message) {
        echo "<script>alert('エラー: " . addslashes($message) . "');</script>";
};
//userのセッションを確認
$user_id = $_SESSION['user_id'];

//myeditの入力を受け取り
$user = array(
    'user_account' => $_POST['user_account'],
    'password' => $_POST['newpass'],
);
$pass = $_POST['pass'];
$newpass = $_POST['newpass'];
$newpasscheck = $_POST['newpasscheck'];

//userのパスワードと一致しているかを確認
if($pass == $_SESSION['user_password']){
    //新規パスワードが一致しているかを確認
    if($newpass == $newpasscheck){
        //送信データを保存
        $model ->update($user,"user_id='{$user_id}'");
        //echo 'OK!';
        //header('Location:?do=user_myedit');
    }else{
        //エラー処理
        showError('新規パスワードが一致しません。');
        //header('Location:?do=user_myedit');
    }
}else{
//エラー処理
showError('既存パスワードが違います。');
//header('Location:?do=user_myedit');
}


//遷移
//header('Location:?do=user_myedit');
header('Location:?do=user_myedit');
exit;

?>