<?php
require_once 'model.php';

$error = false;

// 必須項目チェック
if(empty($_POST['rst_name'])
|| empty($_POST['rst_address'])
|| empty($_POST['start_time'])
|| empty($_POST['end_time'])
|| empty($_POST['tel_num']) 
|| empty($_POST['rst_holiday'])
|| empty($_POST['rst_genre']))
{
    $error = true;
}

if(!$error){
    $rst_save = new Restaurant();

    // 定休日を合計
    $holiday = array_sum($_POST['rst_holiday'] ?? []);

    $data = [
        'rst_name'=> $_POST['rst_name'],
        'rst_address'=> $_POST['rst_address'],
        'start_time'=> $_POST['start_time'],
        'end_time'=> $_POST['end_time'],
        'tel_num'=> $_POST['tel_num'],
        'rst_holiday'=> $holiday,
        'rst_pay'=> isset($_POST['rst_pay']) ? array_sum($_POST['rst_pay']) : null,
        'rst_info'=> $_POST['rst_info'] ?? null,
        'photo'=> $_POST['photo'] ?? null,
        'user_id'=> $_POST['user_id'],
        'discount'=> false
    ];

    // データ登録
    $rows = $rst_save->insert($data);
    $genre = $_POST['rst_genre'];
    $rst_id = $rst_save->getDetail(["'rst_name' = '{$data['rst_name']}'"]);
    $rows = $rst_save->save_genre($rst_id,$genre);
    // 登録成功か判定
    if($rows > 0){
        $_SESSION['message'] = "店舗が登録されました。";
    } else {
        $_SESSION['message'] = "登録に失敗しました。";
    }
    // 登録結果ページまたは一覧ページへ遷移
    header('Location:?do=rst_list');
    exit();

} else {
    // 入力エラー時はフォームに戻す
    $_SESSION['old'] = $_POST;
    $_SESSION['error'] = true;
    header('Location:?do=rst_input');
    exit();
}
