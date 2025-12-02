<?php
$reports = array(
    [
        'id'=> '1',
        'アカウント名'=> 'タックン',
        '評価点'=> '2',
        'ジャンル'=> 'ラーメン',
        '通報理由'=> '写真',
        'コメント'=> '店主が臭い',
        '通報者'=> '九尾 太郎',
        '投稿主'=> '美輪 明宏',
    ],
    [
        'id'=> '2',
        'アカウント名'=> 'アカウント名',
        '評価点'=> '4',
        'ジャンル'=> '店舗名',
        '通報理由'=> 'コメント',
        'コメント'=> 'コメント一部',
        '通報者'=> '通報者',
        '投稿主'=> '投稿主',
    ] 
);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>通報済み口コミ一覧</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>通報済み口コミ一覧表示</h1>

<div class="top-btn">
    <button type="button">通報取り消し一覧</button>
    <button type="button">非表示</button>
    <button type="button">投稿の古い順</button>
</div>


<?php foreach ($reports as $report): ?>
    <section class="report-box">

        <div class="left">
            <h3><?php echo htmlspecialchars($report['アカウント名']) ?></h3>

            <div class="star">
                <p>評価：<?php echo $report['評価点']?></p>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?php echo $i<=(int)$report['評価点'] ? "★" : "☆" ?>
                <?php endfor; ?>
            </div>

            <p><?php echo htmlspecialchars($report['コメント']) ?></p>

            <div class="small">
                <p>投稿主：<?php echo htmlspecialchars($report['通報者']) ?></p><br>
                <p>通報者：<?php echo htmlspecialchars($report['投稿主']) ?></p>
            </div>
        </div>

        <div class="right">
            <h3>#<?php echo htmlspecialchars($report['ジャンル']) ?></h3>
            <p>通報内容：<?php echo htmlspecialchars($report['通報理由']) ?></p>

            <!-- 遷移ボタン（ID を URL パラメータとして渡す） -->
            <button type="button" onclick="location.href='detail.php?id=<?php echo $report['id']?? 0 ?>'">詳細</button>
            <button type="button" onclick="location.href='cancel.php?id=<?php echo $report['id']?? 0 ?>'">取り消し</button>
            <button type="button" onclick="location.href='delete.php?id=<?php echo $report['id']?? 0 ?>'">削除</button>
        </div>

    </section>
<?php endforeach; ?>

</body>
</html>
