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
    ]
);

?>

<?php foreach ($infos as $info): ?>
    <div class="main">
        <h1>マイページ</h1>
        <!--ユーザ情報編集btn-->
        <button type="button" href="">ユーザ情報編集</button>
    </div>
    <!--アカウント情報-->
    <div class="info">
        <div>
            <div class="item1">社員ID</div>
            <!--<input type="text" placeholder="社員IDを入力">-->
            <div><?php echo $info['社員番号ID'] ?></div><br>
        </div>
        <div>
            <div class="item1">氏名</div>
            <!--<input type="text" placeholder="姓"><input type="text" placeholder="名">-->
            <div><?php echo $info['姓'],$info['名'] ?></div><br>
        </div>
    </div>
    <div class="info1">
        <div>
            <div class="item1">アカウント名</div>
            <!--<input type="text" placeholder="アカウント名を入力">-->
            <div><?php echo $info['アカウント名'] ?></div><br>
        </div>
        <div>
            <div class="item1">フリガナ</div>
            <!--<input type="text" placeholder="セイ"><input type="text" placeholder="メイ">-->
            <div><?php echo $info['セイ'],$info['メイ'] ?></div><br>
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
                <img class="img" src="" alt="未登録">
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
