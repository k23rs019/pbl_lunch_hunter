<?php
require_once('model.php'); // 必要なモデル読み込み

$fav = new Favorite(); // お気に入りモデル（あなたの環境に合わせて）

$user_id = $_SESSION['user_id'];
$rst_id  = $_GET['rst_id'];
$mode    = $_GET['mode'];  // add or delete

$where = "user_id='$user_id' AND rst_id='$rst_id'";
if ($mode === "add") {

    // --- 登録処理 ---
    $fav->insert([
        "user_id" => $user_id,
        "rst_id"  => $rst_id
    ]);
} else if ($mode === "delete") {

    // --- 削除処理 ---
    $fav->delete($where);
}

// 元のページへ戻す
header("Location:?do=rst_detail&rst_id=".$rst_id);
    exit;