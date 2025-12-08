<?php


// 仮の店舗データ
$store = [
  'store_name' => '丸兎製麺 九産大前店',
  'address' => '福岡市東区〇丁目20-4 ◇◇ビル2階',
  'tel' => '092-000-0000',
  'hours' => '9:30〜11:00',
  'holiday' => '火曜日',
  'genre' => 'うどん、和食',
  'payment' => '現金 QRコード 電子マネー クレジットカード',
  'url' => 'https://example.com',
  'photo' => 'photo_sample.jpg'
];

// 仮の口コミデータ
$reviews = [
  ['review_id' => 'r001', 'account_name' => '社員A', 'user_id' => 'u001', 'rating' => 5, 'comment' => '出汁が最高でした！', 'photo' => 'udon.jpg'],
  ['review_id' => 'r002', 'account_name' => '社員B', 'user_id' => 'u002', 'rating' => 4, 'comment' => '雰囲気が落ち着いていて良かったです。', 'photo' => ''],
  ['review_id' => 'r003', 'account_name' => '社員C', 'user_id' => 'u003', 'rating' => 3, 'comment' => 'もう少し量が欲しいです。', 'photo' => 'interior.jpg']
];

// 総合評価の算出
$total_rating = array_sum(array_column($reviews, 'rating'));
$review_count = count($reviews);
$review_avg = $review_count > 0 ? round($total_rating / $review_count, 1) : 0;
$review_stars = str_repeat('★', floor($review_avg)) . str_repeat('☆', 5 - floor($review_avg));

// 自分の口コミを抽出
$my_review = null;
$other_reviews = [];
foreach ($reviews as $r) {
    if ($r['user_id'] === $user_id) {
        $my_review = $r;
    } else {
        $other_reviews[] = $r;
    }
}
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
    .review_card { border: 1px solid #ccc; padding: 10px; margin-top: 10px; border-radius: 5px; }
  </style>
</head>
<body>
  <div class="container">
    <p><strong>ログイン中：</strong><?= htmlspecialchars($user_name) ?>（ID: <?= htmlspecialchars($user_id) ?>）</p>

    <!-- 店舗情報 -->
    <div class="section">
      <h2>店舗詳細</h2>
      <p><strong>店舗名：</strong><?= $store['store_name'] ?></p>
      <p><strong>住所：</strong><?= $store['address'] ?></p>
      <p><strong>電話番号：</strong><?= $store['tel'] ?></p>
      <p><strong>営業時間：</strong><?= $store['hours'] ?></p>
      <p><strong>店休日：</strong><?= $store['holiday'] ?></p>
      <p><strong>ジャンル：</strong><?= $store['genre'] ?></p>
      <p><strong>支払方法：</strong><?= $store['payment'] ?></p>
      <p><strong>URL：</strong><a href="<?= $store['url'] ?>" target="_blank">公式サイト</a></p>
      <img src="<?= $store['photo'] ?>" alt="店舗外観">
    </div>

    <!-- 総合評価 -->
    <div class="section">
      <h3>総合評価</h3>
      <p><?= $review_stars ?>（<?= $review_avg ?> / 5、<?= $review_count ?>件）</p>
    </div>

    <!-- 投稿フォーム -->
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
        <button type="submit" name="delete_review">投稿削除</button>
      </form>

      <!-- 自分の口コミを投稿フォーム直下に表示 -->
      <?php if ($my_review): ?>
        <div class="review_card">
          <p><strong>あなたの口コミ：</strong></p>
          <p><strong>評価：</strong><?= str_repeat('★', $my_review['rating']) ?><?= str_repeat('☆', 5 - $my_review['rating']) ?></p>
          <p><strong>コメント：</strong><?= htmlspecialchars($my_review['comment']) ?></p>
          <?php if (!empty($my_review['photo'])): ?>
            <img src="<?= htmlspecialchars($my_review['photo']) ?>" alt="あなたの写真">
          <?php endif; ?>
          <form method="post">
            <input type="hidden" name="review_id" value="<?= $my_review['review_id'] ?>">
            <button type="submit" name="edit_review">編集</button>
            <button type="submit" name="delete_review">削除</button>
          </form>
        </div>
      <?php endif; ?>
    </div>

    <!-- 他人の口コミ一覧 -->
    <div class="section">
      <h3>他の口コミ</h3>
      <?php if (!empty($other_reviews)): ?>
        <?php foreach ($other_reviews as $r): ?>
          <div class="review_card">
            <p><strong>アカウント：</strong><?= htmlspecialchars($r['account_name']) ?></p>
            <p><strong>評価：</strong><?= str_repeat('★', $r['rating']) ?><?= str_repeat('☆', 5 - $r['rating']) ?></p>
            <p><strong>コメント：</strong><?= htmlspecialchars($r['comment']) ?></p>
            <?php if (!empty($r['photo'])): ?>
              <img src="<?= htmlspecialchars($r['photo']) ?>" alt="写真">
            <?php endif; ?>
            <form method="post">
              <input type="hidden" name="review_id" value="<?= $r['review_id'] ?>">
              <label for="reason">通報理由：</label>
              <select name="reason">
                <option value="不適切な表現">不適切な表現</option>
                <option value="写真が不適切">写真が不適切</option>
                <option value="虚偽の内容">虚偽の内容</option>
              </select>
              <button type="submit" name="report_review">通報</button>
            </form>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>他の口コミはまだありません。</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>