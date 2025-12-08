<?php
require_once 'model.php';

$error = false;

// 必須項目チェック
if(empty($_POST['store_name'])
|| empty($_POST['address'])
|| empty($_POST['open_time'])
|| empty($_POST['close_time'])
|| empty($_POST['tel_part1']) 
|| empty($_POST['tel_part2'])
|| empty($_POST['tel_part3'])
|| empty($_POST['holiday'])
|| empty($_POST['genre']))
{
    $error = true;
}

$tel_num = $_POST['tel_part1'] . $_POST['tel_part2'] . $_POST['tel_part3'];

if(!$error){
    $rst_save = new Restaurant();
    // 定休日を合計
    $holiday = array_sum($_POST['holiday'] ?? []);

    $genre = array_sum($_POST['genre'] ?? []);

    $data = [
        'rst_name'=> $_POST['store_name'],
        'rst_address'=> $_POST['address'],
        'start_time'=> $_POST['open_time'],
        'end_time'=> $_POST['close_time'],
        'tel_num'=> $tel_num,
        'rst_holiday'=> $holiday,
        'rst_genre'=> $genre,
        'rst_pay'=> isset($_POST['payment']) ? array_sum($_POST['payment']) : null,
        'rst_url'=> $_POST['url'] ?? null,
        'photo_file'=> $_POST['photo_file'] ?? null,
        'user_id'=> $_POST['user_id'] ?? 1,
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
