
<?php
// 店舗一覧
$mockStores = [
    [
        'RST_ID' => 1,
        'RST_NAME' => '丸亀製麺 九重大橋店',
        'RST_ADDRESS' => '福岡県福岡市城南区七隈〜〜',
        'START_TIME_H' => 10, 'START_TIME_M' => 30,
        'END_TIME_H' => 21, 'END_TIME_M' => 0,
        'TEL_NUM' => '092-000-0000',
        'RST_HOLIDAY' => 0,
        'RST_PAY' => 2,
        'RST_INFO' => '讃岐うどんの専門店。コシのある麺が自慢で、天ぷらも人気です。',
        'PHOTO1' => 'https://images.unsplash.com/photo-1683431686868-bdb1c683cc6d',
        'USER_ID' => 'KYUSHU01',
        'DISCOUNT' => false,
        'rating' => 4.0,
        'isFavorite' => false,
        'tags' => ['うどん', '和食'],
    ],
    [
        'RST_ID' => 2,
        'RST_NAME' => '福工大前 食堂',
        'RST_ADDRESS' => '福岡県東区和白東〜〜',
        'START_TIME_H' => 9, 'START_TIME_M' => 0,
        'END_TIME_H' => 22, 'END_TIME_M' => 0,
        'TEL_NUM' => '092-111-2222',
        'RST_HOLIDAY' => 1,
        'RST_PAY' => 1,
        'RST_INFO' => 'リーズナブルで学生に大人気。ボリューム満点の定食があります。',
        'PHOTO1' => 'https://images.unsplash.com/photo-1562560471-cb5b5f96c1ab',
        'USER_ID' => 'FUKUKO01',
        'DISCOUNT' => true,
        'rating' => 3.0,
        'isFavorite' => true,
        'tags' => ['和食', 'その他'],
    ],
];

function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

$filter = mb_strtolower(trim((string)($_GET['filter'] ?? '')));
$page = max(0, (int)($_GET['page'] ?? 0));
$perPage = 10;

// フィルタ処理（店舗名・住所）
$filtered = array_values(array_filter($mockStores, function($s) use ($filter) {
    if ($filter === '') return true;
    return (mb_stripos($s['RST_NAME'], $filter) !== false) || (mb_stripos($s['RST_ADDRESS'], $filter) !== false);
}));

$total = count($filtered);
$displayed = array_slice($filtered, $page * $perPage, $perPage);

// 曜日・支払方法ラベル
$holidayName = ['日','月','火','水','木','金','土'];
$payMethod = ['現金','電子マネー','クレジットカード'];
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>店舗一覧 - Lunch Hunter</title>
<style>
body{font-family:system-ui,-apple-system,Segoe UI,Roboto,"Hiragino Kaku Gothic ProN",Meiryo,sans-serif;background:#f7f7f7;margin:0;padding:24px}
.container{max-width:1100px;margin:0 auto}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
.page-title{font-size:24px;font-weight:700}
.form-input{width:100%;padding:10px;border-radius:8px;border:1px solid #ddd}
.store-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:18px;margin-top:18px}
.card{background:#fff;border-radius:10px;padding:12px;box-shadow:0 1px 4px rgba(0,0,0,0.06);cursor:default}
.store-image{width:100%;height:180px;object-fit:cover;border-radius:8px;display:block}
.store-header{display:flex;align-items:center;justify-content:space-between;margin-top:10px}
.store-name{font-size:18px;font-weight:700}
.store-rating{display:flex;align-items:center;gap:6px;color:#ffb400}
.badge{display:inline-block;padding:4px 8px;border-radius:999px;background:#eee;color:#333;font-size:12px}
.discount-badge{background:#ff6b00;color:#fff}
.tag-badge{background:#e6e6e6}
.store-info-row{display:flex;align-items:center;gap:8px;margin-top:8px;color:#444}
.store-footer{display:flex;justify-content:flex-end;margin-top:12px}
.pagination{display:flex;gap:12px;align-items:center;justify-content:center;margin-top:22px}
.pager-btn{padding:8px 12px;border-radius:6px;border:1px solid #ddd;background:#fff;cursor:pointer}
.pager-btn[disabled]{opacity:0.5;cursor:default}
.favorite-toggle{background:transparent;border:none;cursor:pointer;font-size:16px}
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1 class="page-title">店舗一覧（DB仕様対応）</h1>
    <div>
      <button onclick="location.href='register.php'">店舗登録</button>
    </div>
  </div>

  <form method="get" style="margin-bottom:12px">
    <input type="text" name="filter" class="form-input" placeholder="検索（店舗名・住所）" value="<?=h($_GET['filter'] ?? '')?>">
  </form>

  <div class="store-grid">
    <?php if (empty($displayed)): ?>
      <div class="card">該当する店舗がありません。</div>
    <?php endif; ?>

    <?php foreach($displayed as $s): ?>
      <div class="card" onclick="location.href='store.php?id=<?=h($s['RST_ID'])?>'">
        <img src="<?=h($s['PHOTO1'])?>" alt="<?=h($s['RST_NAME'])?>" class="store-image">
        <div class="store-header">
          <div>
            <div class="store-name"><?=h($s['RST_NAME'])?></div>
            <?php if (isset($s['rating'])): ?>
              <div class="store-rating">★ <?=h(number_format($s['rating'],1))?></div>
            <?php endif; ?>
          </div>
          <div>
            <button class="favorite-toggle" type="button" aria-label="お気に入り切替" onclick="event.stopPropagation();toggleFav(this);">
              <?= $s['isFavorite'] ? '★' : '☆' ?>
            </button>
          </div>
        </div>

        <div class="store-info-row"><strong>住所：</strong><span><?=h($s['RST_ADDRESS'])?></span></div>
        <div class="store-info-row"><strong>営業時間：</strong>
          <span>
            <?=str_pad((string)$s['START_TIME_H'],2,'0',STR_PAD_LEFT)?>:<?=str_pad((string)$s['START_TIME_M'],2,'0',STR_PAD_LEFT)?> 〜
            <?=str_pad((string)$s['END_TIME_H'],2,'0',STR_PAD_LEFT)?>:<?=str_pad((string)$s['END_TIME_M'],2,'0',STR_PAD_LEFT)?>
          </span>
        </div>
        <div class="store-info-row"><strong>休業日：</strong><span><?=h($holidayName[$s['RST_HOLIDAY']] ?? '-')?>曜</span></div>
        <div class="store-info-row"><strong>支払い：</strong><span><?=h($payMethod[$s['RST_PAY']] ?? '-')?></span></div>

        <?php if (!empty($s['tags'])): ?>
          <div style="margin-top:10px">
            <?php foreach($s['tags'] as $tag): ?>
              <span class="badge tag-badge"><?=h($tag)?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($s['DISCOUNT'])): ?>
          <div style="margin-top:8px"><span class="badge discount-badge">割引</span></div>
        <?php endif; ?>

        <div class="store-footer">
          <a href="store.php?id=<?=h($s['RST_ID'])?>"><button type="button">詳細を見る</button></a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="pagination" role="navigation" aria-label="ページネーション">
    <?php $totalPages = (int)ceil($total / $perPage); ?>
    <form id="pagerForm" method="get" style="display:inline">
      <input type="hidden" name="filter" value="<?=h($_GET['filter'] ?? '')?>">
      <button class="pager-btn" type="button" onclick="changePage(<?=max(0,$page-1)?>)" <?= $page === 0 ? 'disabled' : '' ?>>前へ</button>
      <span><?=($page+1)?> / <?=max(1,$totalPages)?></span>
      <button class="pager-btn" type="button" onclick="changePage(<?=min($totalPages-1,$page+1)?>)" <?= ($page+1) >= $totalPages ? 'disabled' : '' ?>>次へ</button>
    </form>
  </div>
</div>

<script>
// ページ切替
function changePage(p){
  const params = new URLSearchParams(window.location.search);
  params.set('page', p);
  window.location.search = params.toString();
}
// 表示上のお気に入りトグル（サーバー永続化は未実装）
function toggleFav(btn){
  btn.textContent = btn.textContent === '★' ? '☆' : '★';
  btn.classList.toggle('fav');
}
</script>
</body>
</html>
