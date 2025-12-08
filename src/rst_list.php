<?php
if(isset($_SESSION['message'])){
    echo "<p style='color:green'>{$_SESSION['message']}</p>";
    unset($_SESSION['message']);
}
// モックデータ（本来はDBから取得）
$stores = [
    ['name'=>'丸亀製麺 九産大前店','genres'=>['うどん','和食'],'discount'=>true,'favorite'=>true,'rating'=>4.0,'tags'=>['#うどん','#和食'],'registered_by'=>'九州男児','created_at'=>'2025-11-28'],
    ['name'=>'博多一幸舎 本店','genres'=>['ラーメン','和食'],'discount'=>false,'favorite'=>true,'rating'=>4.5,'tags'=>['#ラーメン','#和食'],'registered_by'=>'井上','created_at'=>'2025-12-01'],
    ['name'=>'大名カレー研究所','genres'=>['カレー','洋食'],'discount'=>true,'favorite'=>false,'rating'=>3.8,'tags'=>['#カレー','#洋食'],'registered_by'=>'研究員A','created_at'=>'2025-11-15'],
    ['name'=>'中華厨房 天神店','genres'=>['中華'],'discount'=>false,'favorite'=>false,'rating'=>4.2,'tags'=>['#中華'],'registered_by'=>'天神太郎','created_at'=>'2025-12-02'],
    ['name'=>'糸島カフェ 風の杜','genres'=>['カフェ','洋食'],'discount'=>true,'favorite'=>true,'rating'=>4.7,'tags'=>['#カフェ','#洋食'],'registered_by'=>'糸島人','created_at'=>'2025-11-30'],
];

// フォーム入力を取得
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$genres  = isset($_GET['genre']) ? $_GET['genre'] : [];
$discount= isset($_GET['discount']);
$favorite= isset($_GET['favorite']);
$sort    = isset($_GET['sort']) ? $_GET['sort'] : 'popularity';
$page    = isset($_GET['page']) ? max(1,intval($_GET['page'])) : 1;
$perPage = 3; // 1ページあたり表示件数

// 絞り込み処理
$filtered = array_filter($stores,function($s) use($keyword,$genres,$discount,$favorite){
    $ok = true;
    if($keyword!==''){
        $ok = stripos($s['name'],$keyword)!==false;
    }
    if($ok && !empty($genres)){
        $ok = count(array_intersect($s['genres'],$genres))>0;
    }
    if($ok && $discount){
        $ok = $s['discount'];
    }
    if($ok && $favorite){
        $ok = $s['favorite'];
    }
    return $ok;
});

// ソート処理
usort($filtered,function($a,$b) use($sort){
    if($sort==='new'){
        return strtotime($b['created_at']) <=> strtotime($a['created_at']);
    }
    return $b['rating'] <=> $a['rating'];
});

// ページネーション処理
$total = count($filtered);
$totalPages = max(1,ceil($total/$perPage));
$page = min($page,$totalPages);
$offset = ($page-1)*$perPage;
$paginated = array_slice($filtered,$offset,$perPage);

// クエリを除いた現在ページのURL（閉じる用）
$baseUrl = strtok($_SERVER['REQUEST_URI'], '?');
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Lunch Hunter</title>
<style>
body { font-family: sans-serif; margin:0; background:#f7f7f7; }
header { background:#fff; border-bottom:1px solid #ccc; padding:10px; display:flex; justify-content:space-between; }
header .left a { color:#b91c1c; text-decoration:none; }
header .right a { margin-left:15px; text-decoration:none; color:#333; }

main { max-width:1000px; margin:20px auto; padding:0 15px; }

/* 検索フォーム */
.search-box { background:#fff; padding:15px; border:1px solid #ddd; margin-bottom:20px; }
.search-box input[type=text] { width:60%; padding:5px; }
.search-box button { margin-left:5px; }
.genre-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:5px; margin:10px 0; }

/* 店舗カード一覧 */
.store-list { display:grid; grid-template-columns:repeat(3,1fr); gap:15px; }
.store { background:#fff; border:1px solid #ddd; border-radius:6px; overflow:hidden; }
.store img { width:100%; height:150px; object-fit:cover; }
.store .info { padding:10px; }
.store .info div { margin-bottom:5px; }
.store .rating { color:#d97706; font-weight:bold; }

/* ページネーション */
.pagination { text-align:center; margin-top:20px; }
.pagination a { margin:0 5px; text-decoration:none; color:#333; }
.pagination a.active { font-weight:bold; color:#0366d6; }

/* ボタン風リンク */
.btn-secondary { background:#e5e7eb; color:#111; padding:6px 10px; border-radius:6px; text-decoration:none; }
</style>
</head>
<body>

<main>
  <!-- 検索フォーム -->
  <div class="search-box">
    <form method="get" action="">
      <label>キーワード入力:</label>
      <input type="text" name="q" value="<?=htmlspecialchars($keyword)?>">
      <button type="submit">決定</button>
      <!-- 閉じるはクエリを消して未検索状態へ -->
      <a href="<?=$baseUrl?>" class="btn-secondary">閉じる</a>
      
      <div style="margin-top:10px;">
        <button name="sort" value="popularity">人気順</button>
        <button name="sort" value="new">新着順</button>
      </div>
      
      <div style="margin-top:10px;"><strong>ジャンル:</strong></div>
      <div class="genre-grid">
        <?php foreach(['うどん','ラーメン','定食','カレー','ファーストフード','カフェ','焼肉','和食','洋食','中華','その他'] as $g): ?>
          <label><input type="checkbox" name="genre[]" value="<?=$g?>" <?=in_array($g,$genres)?'checked':''?>><?=$g?></label>
        <?php endforeach; ?>
      </div>
      
      <div style="margin-top:10px;">
        <label><input type="checkbox" name="discount" <?= $discount?'checked':''?>>割引有り</label>
        <label><input type="checkbox" name="favorite" <?= $favorite?'checked':''?>>お気に入り店舗</label>
      </div>
    </form>
  </div>

  <!-- 店舗一覧 -->
  <div class="store-list">
    <?php if(empty($paginated)): ?>
      <p>条件に一致する店舗がありません。</p>
    <?php else: ?>
      <?php foreach($paginated as $s): ?>
        <div class="store">
          <img src="https://via.placeholder.com/300x150" alt="外観写真">
          <div class="info">
            <div>店舗名：<?=$s['name']?> <?= $s['discount']?'<span style="color:green;">割引有</span>':''?></div>
            <div class="rating">
              <?=str_repeat('★',(int)$s['rating'])?><?=str_repeat('☆',5-(int)$s['rating'])?> <?=$s['rating']?>
            </div>
            <div><?=implode(' ',$s['tags'])?></div>
            <div>登録者：<?=$s['registered_by']?></div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- ページネーション -->
  <div class="pagination">
    <?php for($i=1;$i<=$totalPages;$i++): ?>
      <?php
        $qs = $_GET;
        $qs['page']=$i;
        $url='?'.http_build_query($qs);
      ?>
      <a href="<?=$url?>" class="<?=($i==$page)?'active':''?>"><?=$i?></a>
    <?php endfor; ?>
  </div>
</main>
</body>
</html>