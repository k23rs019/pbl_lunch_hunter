<?php
// ダミーデータ（実際はDBから取得）
$accounts = [
    ['name' => '斎藤巧', 'kana' => 'サイトウ コウ', 'id' => 'u0001', 'account' => 'タックン'],
    ['name' => '田中花子', 'kana' => 'タナカ ハナコ', 'id' => '390002', 'account' => 'ハナちゃん'],
    ['name' => '田村時丸', 'kana' => 'タムラ トキマル', 'id' => '390003', 'account' => '坂上田村丸'],
    // ...最大10件表示
];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>アカウント情報一覧</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .nav { display: flex; gap: 10px; margin-bottom: 20px; }
        .search-box, .filter-box { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .pagination { text-align: center; }
        .pagination a { margin: 0 5px; text-decoration: none; }
    </style>
</head>
<body>

<h2>アカウント情報一覧</h2>

<div class="search-box">
    <form method="get">
        <label for="search">IDまたはアカウント名：</label>
        <input type="text" id="search" name="search">
        <button type="submit">検索</button>
    </form>
</div>

<div class="filter-box">
    <label><input type="radio" name="sort" value="id"> ユーザーID順</label>
    <label><input type="radio" name="sort" value="address"> 住所順</label>
    <label><input type="checkbox" name="suspended"> 停止済みアカウント</label>
</div>

<table>
    <thead>
        <tr>
            <th>お名前（フリガナ）</th>
            <th>ID</th>
            <th>アカウント名</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($accounts as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['name']) ?>（<?= htmlspecialchars($user['kana']) ?>）</td>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['account']) ?></td>
            <td><a href="?do=user_edit.php?id=<?= urlencode($user['id']) ?>">編集</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="pagination">
    <a href="#">1</a> ...
    <a href="#">3</a>
    <a href="#">4</a>
    <a href="#">5</a>
    <a href="#">6</a>
    <a href="#">7</a> ...
    <a href="#">10</a>
</div>

</body>
</html>
