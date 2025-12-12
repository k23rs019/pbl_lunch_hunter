<!--
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
-->
<style>
.point {
    display: flex;
    flex-direction: row-reverse; /* 右から左へ並べる */
    justify-content: flex-start;
    width: 300px;
}

/* ラジオボタンは非表示 */
.point input {
    display: none;
}

/* 星のスタイル */
.point label {
    font-size: 30px;      
    color: #ccc;          /* 初期は灰色 */
    cursor: pointer;
    padding: 5px;
    transition: color 0.2s;
}

/* チェックされた星（★）から左側を黄色にする */
.point input:checked ~ label {
    color: gold;
}

.big-textarea {
    width: 500px;    /* 幅を指定 */
    height: 200px;   /* 高さを指定 */
    font-size: 16px; /* 文字サイズも調整可 */
}

body {
    font-size: 20px;
}
.danger-btn {
    color: #fff;
    font-weight: bold;
}

.link-white {
    color: #fff !important;       /* 青を上書きして白にする */
    font-weight: bold;            /* 太字 */
    text-decoration: none !important; /* 下線消す */
}
</style>
<?php
//初期処理

require_once('model.php');
$user = new User();
$restaurant = new Restaurant();
$review = new Review();

//レビューをリンクから取得
$rst_id = ['rst_id' => $_GET['rst_id']];
$rstdata = $restaurant -> get_RstDetail($rst_id);
//print_r($rstdata);
//$rst_holiday = 
//$rst_pay =

$rvlist = $review->getList("rst_id=".$rst_id['rst_id'].'&&rev_state=1');
//print_r($rvlist);
$userlist = $user->getList();
//print_r($userlist);
//評価平均
$total = 0;
$count = count($rvlist);

foreach ($rvlist as $rv) {
    $total += (int)$rv["eval_point"];
}

$avg = $count > 0 ? $total / $count : 0;
$avg = round($avg, 1); // 小数第1位までにしたい場合

?>
<h1 style="text-align:center;">店舗詳細</h1>
<div>
    ＜<a href="">戻る(店舗一覧)</a>
    <?php
    if($_SESSION['usertype_id']==1){
        if($_SESSION['user_id']==$rstdata['user_id']){  
            echo '<a href="?do=rst_edit&rst_id='.$rst_id['rst_id'].'" class=btn>編集</a>';
        }
        echo '<button>お気に入り</button>';
    }elseif($_SESSION['usertype_id']==9){  
        echo '<a href="?do=rst_edit&rst_id='.$rst_id['rst_id'].'" class=btn>編集</a>';
    }
    ?>
    
</div>
<br>
<div class="shopinfo">
    <h3><?php echo $rstdata['rst_name']; ?></h3>
    <table border="1">
        <tr>
            <td><div>住所</div></td>
            <td><?php echo $rstdata['rst_address']?></td>
        </tr>
        <tr>
            <td><div>電話番号</div></td>
            <td><?php echo $rstdata['tel_num']?></td>
        </tr>
        <tr>
            <td><div>店休日</div></td>
            <td>
                <?php
                foreach($rstdata['holidays']  as $h){
                    echo $h.' ';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td><div>営業時間</div></td>
            <td><?php echo $rstdata['start_time'].'~'.$rstdata['end_time'] ?></td>
        </tr>
        <tr>
            <td><div>ジャンル</div></td>
            <td>
                <?php
                foreach($rstdata['rst_genre']  as $rg){
                    echo $rg['genre'].' ';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td><div>支払方法</div></td>
            <td>
                <?php
                foreach($rstdata['pays']  as $p){
                    echo $p.' ';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td><div>URL</div></td>
            <td><?php echo $rstdata['rst_info']; ?></td>
        </tr>
        
    </table>
    <div class="shop-phot">
        <img src="" alt="未登録">
    </div>
</div>

<div>
    <h3>評価</h3>
    <div>総評価人数</div>
    <ul class="gurafu">
        <li>1</li>
        <li>2</li>
        <li>3</li>
        <li>4</li>
        <li>5</li>
    </ul>
    <div><h3>平均評価：<?= $avg ?></h3></div>
    <div>★★★★</div>
</div>
<?php if ($_SESSION['usertype_id'] == 1) { ?>
<div class="shop-point">
    <form action="?do=rev_save" method="post" enctype="multipart/form-data">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6">
                <h3>評価</h3>
                <div class="point">
                    <!-- 星は右から並べる -->
                    <input type="radio" id="star5" name="point" value="5">
                    <label for="star5">★</label>

                    <input type="radio" id="star4" name="point" value="4">
                    <label for="star4">★</label>

                    <input type="radio" id="star3" name="point" value="3">
                    <label for="star3">★</label>

                    <input type="radio" id="star2" name="point" value="2">
                    <label for="star2">★</label>

                    <input type="radio" id="star1" name="point" value="1">
                    <label for="star1">★</label>
                </div><br>
                <textarea name="comment" class="big-textarea" placeholder="コメントを入力してください"></textarea>
            </div>
            <div class="col-xs-4">
                <div class="phot">
                    <!-- 1枚目 -->
                    <input type="file" id="imageInput0" name="img[]" accept="image/*">
                    <div id="previewArea0" style="margin-top:10px; display:none;">
                        <img id="previewImage0" src="" style="max-width:200px;">
                        <button type="button" id="deleteBtn0">選択解除</button>
                    </div>

                    <!-- 2枚目 -->
                    <input type="file" id="imageInput1" name="img[]" accept="image/*">
                    <div id="previewArea1" style="margin-top:10px; display:none;">
                        <img id="previewImage1" src="" style="max-width:200px;">
                        <button type="button" id="deleteBtn1">選択解除</button>
                    </div>

                    <!-- 3枚目 -->
                    <input type="file" id="imageInput2" name="img[]" accept="image/*">
                    <div id="previewArea2" style="margin-top:10px; display:none;">
                        <img id="previewImage2" src="" style="max-width:200px;">
                        <button type="button" id="deleteBtn2">選択解除</button>
                    </div>
                    <br>
            </div>
        </div>
    </div>
</div>

<div>
<?php
echo '<input type="hidden" name="user_id" value='.$_SESSION['user_id'].'>';
echo '<input type="hidden" name="rst_id" value='.$rst_id['rst_id'].'>';
?>
<input type="hidden" name="order" value="2">
<input type="submit" value="編集" class="btn btn-primary link-white">

<input type="hidden" name="order" value="1">
<input type="submit" value="登録" class="btn btn-primary link-white">

<a href="?do=rev_save" class="btn btn-danger link-white">削除</a>
</form>
</div>
<?php } ?>
<div>

    <h2>口コミ</h2>
    <?php
    $perPage = 15;                         
    $total = count($rvlist);               
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $page = max($page, 1);                 
    $start = ($page - 1) * $perPage;
    $reviews = array_slice($rvlist, $start, $perPage); 
    $totalPages = ceil($total / $perPage);
    ?>

    <style>
    .review-box {
        border: 2px solid #000;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 15px;
        background: #fff;
        height: 100%;
    }

    .comment-box {
        border: 1px solid #000;
        background: #f9f9f9;
        padding: 5px;
        min-height: 80px;
    }

    .star {
        color: gold;
        font-size: 20px;
    }
    </style>

    <div class="container mt-4">

        <div class="row">
        <?php foreach ($reviews as $i => $review): ?>

            <?php
            $rid = $review["review_id"];
            //$user_id['user_account'];
            ?>

            <!-- 1列は3つなので col-md-4 -->
            <div class="col-md-4 col-sm-6 col-12 mb-3">
                <div class="review-box">

                    <!-- アカウント名 -->
                    <div class="fw-bold mb-1">
                        <?= htmlspecialchars($review["user_id"]) ?>
                    </div>

                    <!-- 星評価 -->
                    <div class="star mb-2">
                        <?php
                        $stars = intval($review["eval_point"]);
                        echo str_repeat("★", $stars) . str_repeat("☆", 5 - $stars);
                        ?>
                    </div>

                    <!-- コメント -->
                    <div class="comment-box mb-2">
                        <?= nl2br(htmlspecialchars(mb_substr($review["review_comment"], 0, 20))) ?>
                        <?= (mb_strlen($review["review_comment"]) > 20 ? "..." : "") ?>
                    </div>

                    <!-- 詳細ボタン -->
                    <a href="?do=rev_detail&rid=<?= $rid ?>" class="btn btn-primary w-100">
                        詳細を見る
                    </a>

                </div>
            </div>

            <?php 
            // 3列ごとに row を区切る
            if (($i + 1) % 3 == 0): ?>
                </div><div class="row">
            <?php endif; ?>

        <?php endforeach; ?>
        </div>

        <!-- ページネーション -->
        <?php
        // --- 前提 ---
        // $page: 現在のページ番号
        // $totalPages: 全ページ数
        // $rst_id: 店ID
        // $do = "rst_detail";

        // URL ベース
        $base = "index.php?do=rst_detail&rst_id={$rst_id['rst_id']}&page=";
        ?>

        <nav>
            <ul class="pagination justify-content-center mt-4">

                <!-- 最初のページ -->
                <li class="page-item <?= ($page == 1) ? "active" : "" ?>">
                    <a class="page-link" href="<?= $base ?>1">1</a>
                </li>

                <!-- 現在のページが3ページ以上なら "..." を表示 -->
                <?php if ($page > 3): ?>
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                <?php endif; ?>

                <!-- 前のページ -->
                <?php if ($page - 1 > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= $base . ($page - 1) ?>"><?= $page - 1 ?></a>
                    </li>
                <?php endif; ?>

                <!-- 現在のページ -->
                <?php if ($page != 1 && $page != $totalPages): ?>
                    <li class="page-item active">
                        <span class="page-link"><?= $page ?></span>
                    </li>
                <?php endif; ?>

                <!-- 次のページ -->
                <?php if ($page + 1 < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= $base . ($page + 1) ?>"><?= $page + 1 ?></a>
                    </li>
                <?php endif; ?>

                <!-- 現在のページが最後-2より前なら "..." を表示 -->
                <?php if ($page < $totalPages - 2): ?>
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                <?php endif; ?>

                <!-- 最後のページ（1ページしかない場合は非表示） -->
                <?php if ($totalPages > 1): ?>
                    <li class="page-item <?= ($page == $totalPages) ? "active" : "" ?>">
                        <a class="page-link" href="<?= $base . $totalPages ?>"><?= $totalPages ?></a>
                    </li>
                <?php endif; ?>

            </ul>
        </nav>

    </div>
</div>

<script>
// 画像3つ分をまとめて処理
for (let i = 0; i < 3; i++) {

    const input = document.getElementById(`imageInput${i}`);
    const area = document.getElementById(`previewArea${i}`);
    const img = document.getElementById(`previewImage${i}`);
    const del = document.getElementById(`deleteBtn${i}`);

    // プレビュー表示
    input.addEventListener("change", function () {
        const file = this.files[0];
        
        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                img.src = e.target.result;
                area.style.display = "block";
            };

            reader.readAsDataURL(file);
        }
    });

    // 削除ボタン
    del.addEventListener("click", function () {
        img.src = "";
        area.style.display = "none";
        input.value = ""; // 選択解除
    });
}
</script>