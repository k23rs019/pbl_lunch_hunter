<?php
require_once('model.php');

// 店舗情報取得用
$model_store = new Restaurant();
// 口コミ情報取得用
$model_review = new Review();

session_start();

// ロールを仮定（本来はログイン時に設定）
$role = $_SESSION['role'] ?? 'employee'; // 'employee' or 'admin'

// 店舗IDをGETで受け取る
$store_id = $_GET['store_id'] ?? null;
if (!$store_id) {
    echo "<p>店舗IDが指定されていません。</p>";
    exit;
}

// 店舗情報を取得
$store = $model_store->getDetail("store_id='" . $store_id . "'");

// 口コミ一覧を取得
$reviews = $model_review->getList("store_id='" . $store_id . "'");

// 総合評価の平均を算出
$total_rating = 0;
$review_count = count($reviews);
if ($review_count > 0) {
    foreach ($reviews as $r) {
        $total_rating += $r['rating'];
    }
    $review_avg = round($total_rating / $review_count, 1);
} else {
    $review_avg = 0;
}
$review_stars = str_repeat('★', floor($review_avg)) . str_repeat('☆', 5 - floor($review_avg));
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>店舗詳細 - Lunch Hunt</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .container { max-width: 900px; margin: auto; }
    .section { margin-bottom: 30px; }
    label { font-weight: bold; display: block; margin-top: 10px; }
    img { max-width: 100%; height: auto; margin-top: 10px; }
    .review_card { border: 1px solid #ccc; padding: 10px; margin-top: 10px; border-radius: 5px; }
    .admin_action { background: #fee; padding: 10px; margin-top: 10px; border: 1px solid #f00; }
  </style>
</head>
<body>
  <div class="container">
    <!-- 店舗情報 -->
    <div class="section">
      <h2>店舗詳細</h2>
      <p><strong>店舗名：</strong><?= htmlspecialchars($store['store_name']) ?></p>
      <p><strong>住所：</strong><?= htmlspecialchars($store['address']) ?></p>
      <p><strong>電話番号：</strong><?= htmlspecialchars($store['tel']) ?></p>
      <p><strong>営業時間：</strong><?= htmlspecialchars($store['hours']) ?></p>
      <p><strong>定休日：</strong><?= htmlspecialchars($store['holiday']) ?></p>
      <p><strong>駐車場：</strong><?= htmlspecialchars($store['parking']) ?></p>
      <p><strong>支払方法：</strong><?= htmlspecialchars($store['payment']) ?></p>
      <p><strong>URL：</strong><a href="<?= htmlspecialchars($store['url']) ?>" target="_blank">公式サイト</a></p>
      <p><strong>外見写真：</strong></p>
      <img src="<?= htmlspecialchars($store['photo'] ?? 'default.jpg') ?>" alt="店舗外観">
    </div>

    <!-- 総合評価 -->
    <div class="section">
      <h3>総合評価</h3>
      <p><?= $review_stars ?>（<?= $review_avg ?> / 5、<?= $review_count ?>件）</p>
    </div>

    <!-- 社員用：コメント投稿フォーム -->
    <?php if ($role === 'employee'): ?>
      <div class="section">
        <h3>コメント投稿</h3>
        <form method="post" enctype="multipart/form-data">
          <label for="comment">コメント（250文字以内）</label>
          <textarea id="comment" name="comment" maxlength="250"></textarea>

          <label for="photo">写真（任意）</label>
          <input type="file" id="photo" name="photo">

          <label for="rating">評価（1〜5）</label>
          <select id="rating" name="rating">
            <option value="1">★☆☆☆☆</option>
            <option value="2">★★☆☆☆</option>
            <option value="3">★★★☆☆</option>
            <option value="4">★★★★☆</option>
            <option value="5">★★★★★</option>
          </select>

          <button type="submit" name="submit_review">投稿</button>
        </form>
      </div>
    <?php endif; ?>

    <!-- 口コミ一覧 -->
    <div class="section">
      <h3>口コミ</h3>
      <?php if ($review_count > 0): ?>
        <?php foreach ($reviews as $r): ?>
          <div class="review_card">
            <p><strong>アカウント：</strong><?= htmlspecialchars($r['account_name']) ?></p>
            <p><strong>評価：</strong><?= str_repeat('★', $r['rating']) ?><?= str_repeat('☆', 5 - $r['rating']) ?></p>
            <p><strong>コメント：</strong><?= htmlspecialchars($r['comment']) ?></p>
            <?php if (!empty($r['photo'])): ?>
              <img src="<?= htmlspecialchars($r['photo']) ?>" alt="料理写真">
            <?php endif; ?>

            <!-- 管理者用：非表示ボタン -->
            <?php if ($role === 'admin'): ?>
              <div class="admin_action">
                <form method="post">
                  <input type="hidden" name="review_id" value="<?= htmlspecialchars($r['review_id']) ?>">
                  <button type="submit" name="hide_review">口コミを非表示にする</button>
                </form>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>口コミはまだありません。</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>