<?php
$infos=array(
    [
    '社員番号ID'=>'123456',
    'アカウント名'=>'あああ',
    '姓'=>'九州',
    '名'=>'太郎',
    'セイ'=>'キュウシュウ',
    'メイ'=>'タロウ',
    ]
);

$shops=array(
    [
    '店舗名'=>'丸亀製麵',
    '評価'=>'5',
    'ジャンル'=>'うどん 和食',
    '0'=>'割引有',
    ],
    [
    '店舗名'=>'あああ',
    '評価'=>'1',
    'ジャンル'=>'いいい',
    '0'=>'割引有',
    '1'=>'割引無',
    ],
    [
    '店舗名'=>'あああ',
    '評価'=>'1',
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
    <title>通報済み口コミ一覧</title>
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
        grid-template-columns: repeat(2, 1fr);
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
        padding-left:15px;
    }

</style>

<?php foreach ($infos as $info): ?>
    <div class="main">
        <h1>マイページ</h1>
        <!--ユーザ情報編集btn-->
        <button class="btn1" onclick="location.href='?do=user_edit.php'">ユーザ情報編集</button> 
    </div>
    <div class="info-area">   
        <!--アカウント情報-->
        <div class="info">
            <div>
                <div class="item1">社員ID:</div>
                <!--<input type="text" placeholder="<?php echo $info['社員番号ID'] ?>"-->
                <div><?php echo $info['社員番号ID'] ?></div><br>
            </div>
            <div>
                <div class="item1">氏名:</div>
                <!--<input type="text" placeholder="<?php echo $info['姓'] ?>"><input type="text" placeholder="<?php echo$info['名'] ?>">-->
                <div><?php echo $info['姓'],$info['名'] ?></div><br>
            </div>
        </div>
        <div class="info1">
            <div>
                <div class="item1">アカウント名:</div>
                <!--<input type="text" placeholder="<?php echo $info['アカウント名'] ?>">-->
                <div><?php echo $info['アカウント名'] ?></div><br>
            </div>
            <div>
                <div class="item1">フリガナ:</div>
                <!--<input type="text" placeholder="<?php echo $info['セイ']?>"><input type="text" placeholder="<?php echo $info['メイ']?>">-->
                <div><?php echo $info['セイ'],$info['メイ'] ?></div><br>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php foreach ($shops as $shop): ?>
    <!--投稿店舗-->
    <div class="shop">
        <div class="item">
            <div class="shopi">
                <h4>店舗名:<?php echo $shop['店舗名'] ?></h4>
                <div class="star">
                    <div>評価：</div>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php echo $i<=(int)$shop['評価'] ? "★" : "☆" ?>
                    <?php endfor; ?>
                    <div><?php echo $shop['評価']?></div>
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
    </div>
<?php endforeach; ?>
</div>
