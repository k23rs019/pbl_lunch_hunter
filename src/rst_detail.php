<?php
//初期処理

require_once('model.php');
$user = new User();
$restaurant = new Restaurant();
$review = new Review();
$fav = new Favorite();

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

//ユーザーリスト
$userMap = [];
foreach ($userlist as $u) {
    $userMap[$u["user_id"]] = $u["user_account"];
}

// --- お気に入り状態チェック ---
$where = "user_id='".$_SESSION['user_id']."' AND rst_id='".$rst_id['rst_id']."'";
$exists = $fav->getList($where);
$isFav = count($exists) > 0;
?>
<style>
    /*グラフ*/
    .graph-row {
        display: flex;
        align-items: center;
        margin: 6px 0;
    }

    .graph-label {
        width: 60px;
        font-weight: bold;
    }

    .graph-bar {
        height: 20px;
        background-color: #4CAF50;
        border-radius: 4px;
    }

    .graph-value {
        margin-left: 10px;
    }
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
    .star{
        display: flex;
        gap:0.2px;
    }

    .star-rating {
    --rate: 0;        /* 0〜5 の小数(0.1 刻みなど)を直接入れる */
    --size: 40px;
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

    .fav-area {
        text-align: right;   /* 右寄せ */
        margin: 10px 0;      /* 上下の余白（お好みで） */
    }

    .heart-btn {
        background: none;
        border: none;
        font-size: 40px;    /* 大きく */
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }

    .heart-on {
        color: red;
    }

    .heart-off {
        color: white;
        -webkit-text-stroke: 2px red;
    }
</style>

<h1 style="text-align:center;">店舗詳細</h1>
<div>
    ＜<a href="?do=rst_list">戻る(店舗一覧)</a>
    <div style="text-align: right; font-size: 35px; margin-bottom: 10px;">
    <?php
    if($_SESSION['usertype_id']==1){
        if($_SESSION['user_id']==$rstdata['user_id']){  
            echo '<a href="?do=rst_edit&rst_id=' . $rst_id['rst_id'] . '" class="btn btn-info btn-lg">編集</a>';
        }
    ?>

            <?php if ($isFav): ?>
                <!-- 登録済み（赤ハート） -->
                <a href="?do=rst_favsave&rst_id=<?= $rst_id['rst_id'] ?>&mode=delete" style="color: red; text-decoration: none;">
                    ♥
                </a>
            <?php else: ?>
                <!-- 未登録（枠の赤ハート） -->
                <a href="?do=rst_favsave&rst_id=<?= $rst_id['rst_id'] ?>&mode=add" style="color: red; text-decoration: none;">
                    ♡
                </a>
            <?php endif; ?>
    <?php
    }elseif($_SESSION['usertype_id']==9){  
        echo '<a href="?do=rst_edit&rst_id=' . $rst_id['rst_id'] . '" class="btn btn-info btn-lg">編集</a>';
    }
    ?>
    </div>
    
</div>
<br>
<div class="shopinfo">
    <h2><?php echo $rstdata['rst_name']; ?></h2>
    <table border="1" width="100%" style="table-layout:fixed;">
        <tr>
            <td>
                <table border="2" width="100%" style="table-layout:fixed;">
                    <colgroup>
                        <col style="width: 100px;">    
                        <col style="width: auto;">   
                    </colgroup>
                    <tr>
                        <td><div>住所</div></td>
                        <td ><?php echo $rstdata['rst_address']?></td>
                    </tr>
                    <tr>
                        <td><div>電話番号</div></td>
                        <td><?php echo $rstdata['tel_num']?></td>
                    </tr>
                    <tr>
                        <td><div>店休日</div></td>
                        <td>
                            <?php foreach($rstdata['holidays'] as $h){ echo $h.' '; } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><div>営業時間</div></td>
                        <td><?php echo $rstdata['start_time'].'~'.$rstdata['end_time'] ?></td>
                    </tr>
                    <tr>
                        <td><div>ジャンル</div></td>
                        <td>
                            <?php foreach($rstdata['rst_genre'] as $rg){ echo $rg['genre'].' '; } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><div>支払方法</div></td>
                        <td>
                            <?php foreach($rstdata['pays'] as $p){ echo $p.' '; } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><div>URL</div></td>
                        <td><?php echo $rstdata['rst_info']; ?></td>
                    </tr>
                </table>
            </td>

            <!-- ここが画像のセル（右側） -->
            <td style="text-align:center;">
                <?php
                if (!empty($rstdata['photo1'])) {
                    $img64 = base64_encode($rstdata['photo1']);
                    $mime  = 'image/webp';  // 例： image/jpeg, image/png

                    echo '<img src="data:' . $mime . ';base64,' . $img64 . '" style="max-width:300px;" />';
                } else {
                    echo "画像なし";
                }
                ?>
            </td>
        </tr>
    </table>
</div>

<?php
// 1〜5 のカウント用配列
$ratingCount = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

// rvdata から評価点を集計
foreach ($rvlist as $review) {
    $point = intval($review["eval_point"]);
    if ($point >= 1 && $point <= 5) {
        $ratingCount[$point]++;
    }
}

// 最大人数（横棒の最大幅計算用）
$maxCount = max($ratingCount);
?>
<div>
    <table border="1" width="100%" style="table-layout:fixed;">
        <tr>
            <!-- 左：評価グラフ -->
            <td width="500" valign="top" style="padding:10px;">

                <h2>評価</h2>

                <div>
                    <?php foreach ($ratingCount as $point => $count): ?>
                        <?php
                            // 棒の幅を計算
                            $width = $maxCount > 0 ? ($count / $maxCount) * 300 : 0;
                        ?>
                        <div style="display:flex; align-items:center; margin-bottom:4px;">
                            <div style="width:40px;"><?= $point ?> 点</div>
                            <div style="background:gold; height:12px; width:<?= $width ?>px; margin:0 6px;"></div>
                            <div><?= $count ?> 人</div>
                        </div>
                    <?php endforeach; ?>
                </div>
                    <?php
                    //評価平均
                    $total = 0;
                    $count = count($rvlist);

                    foreach ($rvlist as $rv) {
                        $total += (int)$rv["eval_point"];
                    }

                    $avg = $count > 0 ? $total / $count : 0;
                    $avg = round($avg, 1); // 小数第1位までにしたい場合
                    ?>
                <div style="margin-top:10px;">総評価人数：<?= $count ?>人</div>

            </td>

            <!-- 右：平均評価 -->
            <td valign="top" width="400" style="padding:10px;">

                <div><h3>平均評価：<?= $avg ?></h3></div>

                <div style="margin-top:10px;">
                    <div class="star-rating" style="--rate:<?= floatval($avg) ?>; --star-size:40px;"></div>
                </div>

            </td>
        </tr>
    </table>
</div><br>
<hr>
<?php if ($_SESSION['usertype_id'] == 1) { ?>
<div class="shop-point">
    <form action="?do=rev_save" method="post" enctype="multipart/form-data">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6">
                <h2>評価</h2>
                <div class="point">
                    <!-- 星は右から並べる -->
                    <input type="radio" id="star5" name="eval_point" value="5">
                    <label for="star5">★</label>

                    <input type="radio" id="star4" name="eval_point" value="4">
                    <label for="star4">★</label>

                    <input type="radio" id="star3" name="eval_point" value="3">
                    <label for="star3">★</label>

                    <input type="radio" id="star2" name="eval_point" value="2">
                    <label for="star2">★</label>

                    <input type="radio" id="star1" name="eval_point" value="1">
                    <label for="star1">★</label>
                </div><br>
                <textarea name="review_comment" class="big-textarea" placeholder="コメントを入力してください"></textarea>
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
//userがコメントしているか調査
$hasReview = false;

foreach ($rvlist as $rv) {
    if ($rv["user_id"] == $_SESSION["user_id"]) {
        $hasReview = true;
        $myreview_id = $rv["review_id"];
        break;
    }
}
?>
<?php if ($hasReview): ?>
    <input type="hidden" name="review_id" value="<?= $myreview_id ?>">
    <input type="hidden" name="mode" value="update">
    <input type="submit" value="編集" class="btn btn-primary link-white btn-lg">
<?php else: ?>
    <input type="hidden" name="mode" value="create">
    <input type="submit" value="登録" class="btn btn-primary link-white btn-lg">
<?php endif; ?>

<a href="?do=rev_save" class="btn btn-danger link-white btn-lg">削除</a>
</form>
</div>
<?php } ?>
<hr>
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
                        <?= htmlspecialchars($userMap[$review["user_id"]] ?? "不明ユーザー") ?>
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
                        <?= nl2br(htmlspecialchars(mb_substr($review["review_comment"] ?? '', 0, 20))) ?>
                        <?= (mb_strlen($review["review_comment"] ?? '') > 20 ? "..." : "") ?>
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