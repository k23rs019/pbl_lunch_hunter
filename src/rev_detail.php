<!-- 通報ボタン -->
<style>
    body {
        font-size: 20px;
    }
/* --- モーダル背景 --- */
    #modalBg {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
    }

/* --- モーダル本体 --- */
    #modalBox {
        background: #fff;
        width: 300px;
        padding: 20px;
        border-radius: 10px;
        margin: 150px auto;
        text-align: center;
    }
</style>

<h1 style="text-align:center;">口コミ個別表示</h1>
<?php
//確認リンク http://localhost/dashboard/pbl_lunch_hunter/index.php?do=rev_detail&rid=0001
require_once('model.php');
$modelR = new Review();
$modelU = new user();
//レビューをリンクから取得
$review_id = $_GET['rid'];
//レビューをリンクから取得
$review = $modelR -> getDetail("review_id =". $review_id);
//print_r($review); //デバッグ
//echo $review['review_id'];
//レビューから口コミ投稿者の名称を取得
$user = $modelU -> getDetail("user_id='".$review['user_id']."'");

// 前のレビューIDを取得
//$prev = $model->insert("review_id < {$review_id}", "review_id DESC");

// 次のレビューIDを取得
//$next = $model->insert("review_id > {$review_id}", "review_id ASC");
?>

<?php
if($_SESSION['usertype_id']==1){
    echo '<form id="reportForm" action=?do=rev_detail_rpsave&rid='.$review_id.'&rst_id='.$review['rst_id'].' method="post">';
    echo '＞<a href="?do=rst_detail&rst_id='.$review['rst_id'].'" >戻る</a></button>';
    echo '<button type="button" onclick="openModal()">通報する</button></form>';
}elseif($_SESSION['usertype_id']==9){
    echo '<form id="hideForm' . $review['review_id'] . '" method="POST" action="?do=rev_save" style="display:none;">';
    echo '<input type="hidden" name="review_id" value="' . $review['review_id'] . '">';
    echo '<input type="hidden" name="rst_id" value="'.$review['rst_id'].'">';
    echo '<input type="hidden" name="order" value="3">';
    echo '</form>';
    echo '<button onclick="confirmHide(' . $review['review_id'] . ')">非表示</button>';
}
# localhost\dashboard\pbl_lunch_hunter\src\rev_detail.php

?>
<div id="modalBg">
    <div id="modalBox">
        <p>通報理由（1つ以上選択してください）</p>

        <label><input type="checkbox" id="reason_comment"> コメント</label><br>
        <label><input type="checkbox" id="reason_photo"> 写真</label><br><br>

        <p>本当に通報しますか？</p>

        <button onclick="submitReport()">YES</button>
        <button onclick="closeModal()">NO</button>
    </div>
</div>

<div>
    <table border="1" width="100%" style="table-layout:fixed;">
        <tr>
            <td style="text-align:center;"><h2><?php echo $user['user_account']; ?></td>
            <td rowspan="2" style="vertical-align: top; text-align: left;"><?php echo $review['review_comment']; ?></td>
        </tr><tr>
            <td style="text-align:center;">
            <span style="font-size: 4em; color: yellow;">
            <?php
            for ($i=0; $i<$review['eval_point']; $i++){
                echo '★';
            }
            for ($i=5; $i>$review['eval_point']; $i--){
                echo '☆';
            }
                
            ?>
            </span>
            </td>
        </tr>
    </table>
</div>
<div>
    <table border="1" width="100%" style="table-layout:fixed;">
        <td style="text-align:center;">
        <?php
        
        if (!empty($review['photo1'])) {
            $img64 = base64_encode($review['photo1']);
            $mime  = 'image/webp';  // 例： image/jpeg, image/png

            echo '<img src="data:' . $mime . ';base64,' . $img64 . '" style="max-width:300px;" />';
        } else {
            echo "画像なし";
        }
        echo '</td><td style="text-align:center;">';
        if (!empty($review['photo2'])) {
            $img64 = base64_encode($review['photo2']);
            $mime  = 'image/webp';  // 例： image/jpeg, image/png

            echo '<img src="data:' . $mime . ';base64,' . $img64 . '" style="max-width:300px;" />';
        } else {
            echo "画像なし";
        }
        echo '</td><td style="text-align:center;">';
        if (!empty($review['photo3'])) {
            $img64 = base64_encode($review['photo3']);
            $mime  = 'image/webp'; // 例： image/jpeg, image/png

            echo '<img src="data:' . $mime . ';base64,' . $img64 . '" style="max-width:300px;" />';
        } else {
            echo "画像なし";
        }
        ?>
        </td>
    </table>

</div>

<button>ひとつ前へ</button>
<button>次へ</button>

<script>
// モーダルを開く
function openModal() {
    document.getElementById('modalBg').style.display = 'block';
}

// モーダルを閉じる
function closeModal() {
    document.getElementById('modalBg').style.display = 'none';
}

// YESボタン押下 → チェック確認 → 送信
function submitReport() {
    const c1 = document.getElementById("reason_comment").checked;
    const c2 = document.getElementById("reason_photo").checked;

    if (!c1 && !c2) {
        alert("通報理由を1つ以上選択してください。");
        return; // モーダル閉じずにそのまま
    }

    // hidden input を生成してフォームに追加（送信用）
    const form = document.getElementById("reportForm");
    if (c1) {
        form.insertAdjacentHTML('beforeend',
            '<input type="hidden" name="reason[]" value="1">'
        );
    }
    if (c2) {
        form.insertAdjacentHTML('beforeend',
            '<input type="hidden" name="reason[]" value="1">'
        );
    }

    form.submit();
}

function confirmHide(id) {
    if (confirm("本当に非表示にしますか？")) {
        document.getElementById("hideForm" + id).submit();
    }
}

</script>