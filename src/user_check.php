<?php
$user_id = $_POST['user_id'];
$user_password=$_POST['user_password'];
if($user_id = "kyusan" && $user_password = "pbl2025"){ //動作チェック用
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_password'] = $user_password;
    $_SESSION['usertype_id'] = 1;
    header('Location:index.php');
} else {
    header('Location:?do=user_login');
}
?>