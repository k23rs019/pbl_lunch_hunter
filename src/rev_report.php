<?php
$reports = array(
    [
        'id'=> '1',
        'アカウント名'=> 'タックン',
        '評価点'=> '1',
        'ジャンル'=> 'ラーメン',
        '通報理由'=> '写真',
        'コメント'=> '店主が臭い',
        '通報者'=> '九尾 太郎',
        '投稿主'=> '美輪 明宏',
    ],
    [
        'id'=> '2',
        'アカウント名'=> 'アカウント名',
        '評価点'=> '2',
        'ジャンル'=> 'ジャンル',
        '通報理由'=> 'コメント',
        'コメント'=> 'コメント一部',
        '通報者'=> '通報者',
        '投稿主'=> '投稿主',
    ],
    [
        'id'=> '3',
        'アカウント名'=> 'タックン',
        '評価点'=> '3',
        'ジャンル'=> 'ラーメン',
        '通報理由'=> '写真',
        'コメント'=> '店主が臭い',
        '通報者'=> '九尾 太郎',
        '投稿主'=> '美輪 明宏',
    ],
    [
        'id'=> '4',
        'アカウント名'=> 'アカウント名',
        '評価点'=> '4',
        'ジャンル'=> 'ジャンル',
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

<style>
    h1{
        text-align: center;
    }
    .top-btn{
        margin-left:80%;
        display: flex;
        flex-direction: column;
        gap: 10px; /* ボタンの間隔 */

    }
    .report-box{
        display: flex;
        padding: 20px;
        border: 0.5px solid;
        border-radius: 10px;
        margin:10px;
        gap:20%;
    }
    .star{
        display: flex;
        background-color: red;
    }

    .kome{
        border:0.5px solid;
    }


</style>


<h1 class="report_title">通報済み口コミ一覧表示</h1>

<div class="top-btn">
    <button type="button">通報取り消し一覧</button>
    <button type="button" id="hidbtn">非表示</button>
    <button type="button" id="sortBtn">並び替え（新着順）</button>
</div>


<div id="reportArea">
<?php foreach ($reports as $report): ?>
    <section class="report-box">

        <div class="left">
            <h3><?php echo htmlspecialchars($report['アカウント名']) ?></h3>

            <div class="star">
                <div>評価：</div>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?php echo $i<=(int)$report['評価点'] ? "★" : "☆" ?>
                <?php endfor; ?>
                <div><?php echo $report['評価点']?></div>
            </div>

            <div class="kome"><?php echo htmlspecialchars($report['コメント']) ?></div>

            <div class="small">
                <p>投稿主：<?php echo htmlspecialchars($report['通報者']) ?></p>
                <p>通報者：<?php echo htmlspecialchars($report['投稿主']) ?></p>
            </div>
        </div>

        <div class="right">
            <h3>#<?php echo htmlspecialchars($report['ジャンル']) ?></h3>
            <p>通報内容：<?php echo htmlspecialchars($report['通報理由']) ?></p>

            <button type="button" onclick="location.href='/src/rev_detail.php'">詳細</button>
            <button type="button" onclick="location.href='cancel.php'">取り消し</button>
            <button class="btn0" popovertarget="my-<?= $report['id'] ?>">削除</button>
            
            <div class="pop" popover="manual" id="my-<?= $report['id'] ?>">
                <p>本当に削除しますか？</p>
                    <div class="yn">
                        <button type="button" onclick="location.href='cancel.php?id=<?php echo $report['id'] ?? 0 ?>'">yes</button>
                        <button type="button" onclick="document.getElementById('my-<?= $report['id'] ?>').hidePopover()">no</button>
                     </div>
            </div>
        </div>
    </section>
<?php endforeach; ?>
</div>

<script>
    let reports = <?php echo json_encode($reports); ?>;
    let isDesc = false;

    const renderReports = () => {
        const area = document.getElementById("reportArea");
        area.innerHTML = "";

        reports.forEach(report => {
            area.innerHTML += `
            <section class="report-box">
                <div class="left">
                    <h3>${report['アカウント名']}</h3>
                    <div class="star">
                        <p>評価：${report['評価点']}</p>
                        ${"★".repeat(report['評価点']) + "☆".repeat(5 - report['評価点'])}
                    </div>
                    <p>${report['コメント']}</p>
                    <div class="small">
                        <p>投稿主：${report['通報者']}</p>
                        <p>通報者：${report['投稿主']}</p>
                    </div>
                </div>
                <div class="right">
                    <h3>#${report['ジャンル']}</h3>
                    <p>通報内容：${report['通報理由']}</p>
                    <button type="button" onclick="location.href='detail.php?id=${report['id']}'">詳細</button>
                    <button type="button" onclick="location.href='cancel.php?id=${report['id']}'">取り消し</button>
                    <button class="btn0" popovertarget="my-<?= $report['id'] ?>">削除</button>
            
                    <div class="pop" popover="manual" id="my-<?= $report['id'] ?>">
                        <p>本当に削除しますか？</p>
                        <div class="yn">
                            <button type="button" onclick="location.href='cancel.php?id=<?php echo $report['id'] ?? 0 ?>'">yes</button>
                            <button type="button" onclick="document.getElementById('my-<?= $report['id'] ?>').hidePopover()">no</button>
                        </div>
                    </div>
                </div>
            </section>
            `;
        });
    };

    // 初回描画
    renderReports();

    // 並び替えボタン
    document.getElementById("sortBtn").onclick = () => {
        isDesc = !isDesc;
        reports.reverse();

        document.getElementById("sortBtn").innerText = isDesc 
            ? "並び替え（古い順）"
            : "並び替え（新着順）";

        renderReports();
    };
</script>

</body>
</html>
