<?php
require_once 'model.php';
$model = new User();

$mode = $_POST['mode'] ?? '';
$user_id = $_POST['user_id'];
$where = ['user_id' => $user_id];
$userold = $model->get_userDetail($where);

//print_r($_POST);
//POSTを変数に格納
switch ($mode) {
    case 'input':
        //登録
        $user_account = "{$_POST['user_l_name']}{$_POST['user_f_name']}";
        $user = array(
            'user_id' => $_POST['user_id'],
            'password' => $_POST['pass'],
            'user_l_name' => $_POST['user_l_name'],
            'user_f_name' => $_POST['user_f_name'],
            'user_l_kana' => $_POST['user_l_kana'],
            'user_f_kana' => $_POST['user_f_kana'],
            'user_account' => $user_account,
            'usertype_id' => 1
        );

        //
        if (empty($userold['user_id'])) {
            header('Location:?do=user_input&msg=2');
        }
        //テストコード
        //print_r($user);
        //DBに登録
        $model->insert($user);
        //header('Location:?do=user_input');
        header('Location:?do=user_input&msg=1');
        exit;
    case 'update':
        //編集更新
        $user = array(
            'user_l_name' => $_POST['user_l_name'],
            'user_f_name' => $_POST['user_f_name'],
            'user_l_kana' => $_POST['user_l_kana'],
            'user_f_kana' => $_POST['user_f_kana'],
        );
        $model->update($user, $where);
        header('Location:?do=user_list');
        exit;
    case 'stop':
        //停止
        $user = ['usertype_id' => '2'];
        $model->update($user, $where);
        header('Location:?do=user_edit&id=' . $user_id);
        exit;
    case 'restart':
        //再開
        $user = ['usertype_id' => '1'];
        $model->update($user, $where);
        header('Location:?do=user_edit&id=' . $user_id);
        exit;
    case 'reset':
        //パスワードリセット
        $user = ['password' => '1234'];
        $rows = $model->update($user, $where);
        header('Location:?do=user_edit&id=' . $user_id);
        exit;
    default:
        exit('登録に失敗しました。お手数をおかけしますがもう一度やり直してください。');
}
