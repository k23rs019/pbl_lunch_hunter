<?php
require_once('model.php');
$model = new User();
//$rstModel= new Restaurant();

//userのセッションを確認
$user_id = $_SESSION['user_id'];

//userのデータを取得
$mydata = $model -> getDetail("user_id='{$user_id}'");

//$rstdata= $rstModel -> getList();

//print_r($mydata);
//姓名を結合
$mydata['name'] = $model -> username($mydata);
$mydata['kana'] = $model -> userkana($mydata);





$shops=array(
    [
    '店舗名'=>'丸亀製麵',
    '評価'=>'3.2',
    'ジャンル'=>'うどん 和食',
    '0'=>'割引有',
    ],
    [
    '店舗名'=>'あああ',
    '評価'=>'1.4',
    'ジャンル'=>'いいい',
    '0'=>'割引有',
    '1'=>'割引無',
    ],
    [
    '店舗名'=>'あああ',
    '評価'=>'1.5',
    'ジャンル'=>'いいい',
    '0'=>'割引有',
    '1'=>'割引無',
    ],
);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MY PAGE</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<style>
    h1{
        text-align: center;
    }

    .btn1{
        margin-left: 80%;
    }

    .info{
        margin: 30px;
        display: flex;
        gap:  30%;
    }
    .info1{
        margin: 30px;
        display: flex;
        gap:  30%;
    }

    .shop{
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 10px;
    }

    .star{
        display: flex;
    }

    .item{
        display: flex;
        border-radius: 10px;
        border: 0.5px solid;
        margin-bottom: 10px;
        padding:15px;
        gap:30px;
    }
    .item:hover{
        box-shadow: 0.5px 0.5px 3px;
    }

    .star-rating {
    --rate: 0;        /* 0〜5 の小数(0.1 刻みなど)を直接入れる */
    --size: 20px;
    --star-color: #ccc;
    --star-fill: gold;

    font-size: var(--size);
    font-family: "Arial", sans-serif;
    position: relative;
    display: inline-block;
    line-height: 1;
    }

    .star-rating::before {
        content: "★★★★★";
        color: var(--star-color);
    }

    .star-rating::after {
        content: "★★★★★";
        color: var(--star-fill);
        position: absolute;
        left: 0;
        top: 0;
        width: calc(var(--rate) * 20%);  /* ★ 小数点をそのまま使用（0.1 → 2%） */
        overflow: hidden;
        white-space: nowrap;
    }


</style>
    <div class="main">
        <h1>マイページ</h1>
        <!--ユーザ情報編集btn-->
        <button class="btn1" onclick="location.href='?do=user_myedit'"><a href="?do=user_myedit">ユーザ情報編集</a></button> 
    </div>
    <div class="info-area">   
        <!--アカウント情報-->
        <div class="info">
            <div>
                <div class="item1">社員番号ID:</div>
                <div><?php echo $mydata['user_id'] ?></div><br>
            </div>
            <div>
                <div class="item1">氏名:</div>
                <div><?php echo $mydata['name'] ?></div><br>
            </div>
        </div>
        <div class="info1">
            <div>
                <div class="item1">アカウント名:</div>
                <div><?php echo $mydata['user_account'] ?></div><br>
            </div>
            <div>
                <div class="item1">フリガナ:</div>
                <div><?php echo $mydata['kana'] ?></div><br>
            </div>
        </div>
    </div>
<!--投稿店舗-->
<div class="shop">
    <!--$shops as $shop-->
    <?php foreach ($shops as $shop): ?>
            <div class="item">
                <div class="shopi">
                    <h4>店舗名:<?php echo $shop['店舗名'] ?></h4>
                    <div class="star">
                        <!--<div>評価：</div>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php echo $i<=(int)$shop['評価'] ? "★" : "☆" ?>
                        <?php endfor; ?>
                        <div><?php echo $shop['評価']?></div>-->

                        <div>評価：</div>
                        <?php $rate = (float)$shop['評価']; ?>
                        <div class="star-rating" style="--rate: <?= $rate ?>;"></div>
                        <div><?= htmlspecialchars($shop['評価']) ?></div>

                    </div>
                    <div>ジャンル:<?php echo $shop['ジャンル'] ?></div>
                    <div><?php echo $shop['0']?></div>
                </div>
                <div class="phot">
                    <a href="/src/detail.php">
                        <img class="img" src="" alt="未登録">
                    </a>
                </div>
            </div>

            <script>
                <div class="star">
                    <p>評価：${shop['評価']}</p>
                    <div class="star-rating" style="--rate:${parseFloat(shop['評価'])}"></div>
                </div>
            </script>
    <?php endforeach; ?>
</div>