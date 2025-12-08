<?php
require_once 'model.php';

$error = false;

// 必須項目チェック
$required_fields = ['store_name','address','open_time','close_time','tel_part1','tel_part2','tel_part3','holiday','genre'];
foreach($required_fields as $field){
    if(empty($_POST[$field])){
        $error = true;
        break;
    }
}

// 電話番号結合
$tel_num = $_POST['tel_part1'] . $_POST['tel_part2'] . $_POST['tel_part3'];

// エラーがなければ登録処理
if(!$error){
    $rst_save = new Restaurant();

    // 定休日・ジャンル・支払方法の合計（ビットフラグ）
    $holiday = array_sum($_POST['holiday'] ?? []);
    $genre = array_sum($_POST['genre'] ?? []);
    $pay = isset($_POST['payment']) ? array_sum($_POST['payment']) : 0;

    // ファイル処理
    $photo_file = '';
    if(isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] === UPLOAD_ERR_OK){
        $upload_dir = 'uploads/';
        if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $photo_file = basename($_FILES['photo_file']['name']);
        move_uploaded_file($_FILES['photo_file']['tmp_name'], $upload_dir . $photo_file);
    }

    // 登録データ
    $data = [
        'rst_name'=> $_POST['store_name'],
        'rst_address'=> $_POST['address'],
        'start_time'=> $_POST['open_time'],
        'end_time'=> $_POST['close_time'],
        'tel_num'=> $tel_num,
        'rst_holiday'=> $holiday,
        'rst_pay'=> $pay,
        'rst_info'=> $_POST['url'] ?? '',
        'photo1'=> $photo_file,
        'user_id'=> $_SESSION['user_id'],
        'discount'=> 0
    ];

    // データベースに登録
    $rows = $rst_save->insert($data);

    // 登録した店舗のIDを取得
    $rst_detail = $rst_save->get_RstDetail(['rst_name' => $data['rst_name']]);
    $rst_id = $rst_detail['rst_id'] ?? null;

    $genre_save = new Genre();
    // ジャンル保存
    $genre_array = $_POST['genre'] ?? [];
    if($rst_id !== null){
        $rows = $genre_save->save_genre($rst_id, $genre_array);
    }

    // 結果メッセージ
    $_SESSION['message'] = $rows > 0 ? "店舗が登録されました。" : "登録に失敗しました。";

    // 店舗一覧ページに遷移
    header('Location:?do=rst_list');
    exit();

} else {
    // 入力エラー時はフォームに戻す
    $_SESSION['old'] = $_POST;
    $_SESSION['error'] = true;
    header('Location:?do=rst_input');
    exit();
}