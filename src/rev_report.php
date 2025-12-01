$reports = [
    [
        "account" => "タックン",
        "rating" => 2,
        "store" => "ラーメン",
        "report_reason" => "写真",
        "comment" => "店主が臭い",
        "poster" => "九尾 太郎",
        "reporter" => "美輪 明宏",
    ],
    [
        "account" => "アカウント名",
        "rating" => 4,
        "store" => "店舗名",
        "report_reason" => "コメント",
        "comment" => "コメント一部",
        "poster" => "投稿主",
        "reporter" => "通報者",
    ]
];
?>

<?php include('pg_header.php'); ?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <title>通報済み口コミ一覧</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>通報済み口コミ一覧表示</h1>

<div class="top-btn">
    <button>通報取り消し一覧</button>
    <button>非表示</button>
    <button>投稿の古い順</button>
</div>

<?php foreach ($reports as $r): ?>
<section class="report-box">

    <div class="left">
        <h3><?= htmlspecialchars($r["account"]) ?></h3>

        <div class="star">
            評価：
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <?= $i <= $r["rating"] ? "★" : "☆" ?>
            <?php endfor; ?>
        </div>

        <p><?= htmlspecialchars($r["comment"]) ?></p>

        <div class="small">
            投稿主：<?= htmlspecialchars($r["poster"]) ?><br>
            通報者：<?= htmlspecialchars($r["reporter"]) ?>
        </div>
    </div>

    <div class="right">
        <h3>▲ <?= htmlspecialchars($r["store"]) ?></h3>
        <p>通報内容：<?= htmlspecialchars($r["report_reason"]) ?></p>

        <button class="btn-detail">詳細</button>
        <button class="btn-cancel">取り消し</button>
        <button class="btn-delete">削除</button>
    </div>

</section>
<?php endforeach; ?>

</body>
</html>
<?php include('pg_footer.php'); ?>