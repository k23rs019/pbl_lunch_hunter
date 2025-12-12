<?php
require_once 'model.php';

$review_save = new Review();
//オーダー内容によって処理を分岐
$order = $_POST['order'];
if ($order == 1) {
    //登録
    function readBlob($key)
    {
        if (!empty($_FILES['img']['tmp_name'][$key])) {
            return file_get_contents($_FILES['img']['tmp_name'][$key]);  // バイナリ取得
        }
        return null;
    }

    $data = [
        'eval_point' => $_POST['point'], 'review_comment' => $_POST['comment'] ?? null, 'rst_id' => $_POST['rst_id'], 'user_id' => $_POST['user_id'], 'photo1' => readBlob(0), 'photo2' => readBlob(1), 'photo3' => readBlob(2), 'rev_state' => true
    ];
    //print_r($_FILES);
    //print_r($data);
    $review_save->insert($data);
} elseif ($order == 2) {
    //編集
    //レビューidを受け取る
    $review_id = $_POST['review_id'];

    function readBlob($key)
    {
        if (!empty($_FILES['img']['tmp_name'][$key])) {
            return file_get_contents($_FILES['img']['tmp_name'][$key]);  // バイナリ取得
        }
        return null;
    }

    $data = [
        'eval_point' => $_POST['point'], 'review_comment' => $_POST['comment'] ?? null, 'rst_id' => $_POST['rst_id'], 'user_id' => $_POST['user_id'], 'photo1' => readBlob(0), 'photo2' => readBlob(1), 'photo3' => readBlob(2), 'rev_state' => true
    ];
    //print_r($_FILES);
    //print_r($data);
    $review_save->update($data, 'review_id=' . $review_id);
} elseif ($order == 3) {
    //非表示
    $review_id = $_POST['review_id'];
    $data = $review_save->getDetail("review_id =" . $review_id);
    $state['rev_state'] = 0;
    $review_save->update($state, 'review_id=' . $review_id);
} elseif ($order == 4) {
    //削除

}

// モード取得
$mode = $_POST['mode'] ?? '';

// ID取得
$rev_id = $_POST['rev_id'] ?? null;
$repo_id = $_POST['repo_id'] ?? null;
$rst_id = $_POST['rst_id'] ?? null;

// モード別処理
switch ($mode) {

        // ★ 新規作成
    case 'create':
        $data = [
            'eval_point'      => $_POST['eval_point'],
            'review_comment'  => $_POST['review_comment'] ?? null,
            'rst_id'          => $_SESSION['rst_id'],
            'user_id'         => $_SESSION['user_id'],
            'photo1'          => readBlob('photo1'),
            'photo2'          => readBlob('photo2'),
            'photo3'          => readBlob('photo3'),
            'rev_state'       => 1
        ];

        $review->insert($data);
        // 終了後リダイレクト
        header('Location:?do=rst_detail&rst_id=' . $data['rst_id'] . '');
        exit;
        break;

        // ★ 取り消し（例：rev_state を false にするなど）
    case 'cancel':
        if ($rev_id == null || $repo_id == null) exit('Invalid rev_id');

        $review->update(['rev_state' => 1], ['review_id' => $rev_id]);
        $report->update(['report_state' => 3], ['report_id' => $repo_id]);
        header('Location:?do=rev_report');
        exit;
        break;

        // ★ 削除
    case 'delete':
        if ($rev_id == null || $repo_id == null) exit('Invalid rev_id');

        $review->update(['rev_state' => 0], ['review_id' => $rev_id]);
        $report->update(['report_state' => 2], ['report_id' => $repo_id]);
        header('Location:?do=rev_report');
        exit;
        break;

    case 'report':
        if ($rev_id == null || $rst_id == null) exit('Invalid request');

        $reason = 0;
        if (!empty($_POST['reason'])) {
            $r = $_POST['reason'];
            if (in_array('1', $r) && in_array('2', $r)) $reason = 3;
            elseif (in_array('1', $r)) $reason = 1;
            elseif (in_array('2', $r)) $reason = 2;
        }
        $data = [
            'review_id'     => $rev_id,
            'user_id'       => $_SESSION['user_id'],
            'report_reason' => $reason,
            'report_state'  => 1,
        ];
        $report->insert($data);

        header('Location:?do=rst_detail&rst_id=' . $rst_id);
        exit;
        break;
    default:
        exit('Invalid mode');
}
