<?php
require_once('model.php');
$user = new User();

// GET パラメータ取得
$search_key = $_GET['q'] ?? '';
$sort       = $_GET['sort'] ?? '';
$stop_user  = isset($_GET['stop_user']);

$per_page = 20;

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;

$user_list = $user->get_userlist_filtered($search_key, $stop_user, $sort, $per_page, $offset);

$page        = max(1, (int)($_GET['page'] ?? 1));
?>

<style>
    body {
        font-family: sans-serif;
        margin: 20px;
    }

    .nav {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .search-box {
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
    }

    .pagination {
        text-align: center;
    }

    .pagination a {
        margin: 0 5px;
        text-decoration: none;
    }

    .pagination a.current-page {
        font-weight: bold;
        pointer-events: none;
        /* クリック不可 */
        cursor: default;
        /* カーソルを変更 */
    }
</style>

<h2>アカウント情報一覧</h2>
<!-- 検索ボタン -->
<div class="mb-3">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#searchModal">
        検索
    </button>
</div>

<!-- 検索モーダル -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="get" action="index.php">
                <input type="hidden" name="do" value="user_list">

                <!-- モーダルヘッダー -->
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">ユーザ検索</h5>
                </div>

                <!-- モーダル本文 -->
                <div class="modal-body">
                    <!-- 検索 -->
                    <div class="search-box">
                        <label for="search">IDまたはアカウント名：</label>
                        <input type="text" id="search" name="q" value="<?= htmlspecialchars($search_key) ?>">
                    </div>

                    <!-- ソート -->
                    <div class="filter-box" style="margin-top:10px;">
                        <label><input type="radio" name="sort" value="id" <?= ($sort === 'id') ? 'checked' : ''; ?>> ユーザーID順</label>
                        <label><input type="radio" name="sort" value="address" <?= ($sort === 'address') ? 'checked' : ''; ?>> 五十順</label>
                        <label><input type="checkbox" name="stop_user" <?= $stop_user ? 'checked' : ''; ?>> 停止済みアカウント</label>
                    </div>
                </div>

                <!-- モーダルフッター -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                    <button type="submit" class="btn btn-primary">検索</button>
                </div>
            </form>
        </div>
    </div>
</div>


<table>
    <thead>
        <tr>
            <th>名前（フリガナ）</th>
            <th>ID</th>
            <th>アカウント名</th>
            <th>ユーザ種別</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($user_list)) : ?>
            <?php foreach ($user_list as $user_one) :
                $user_detail = $user->get_Userdetail(['user_id' => $user_one['user_id']]);
            ?>
                <tr>
                    <td><?= htmlspecialchars($user_detail['username']) ?>（<?= htmlspecialchars($user_detail['userkana']) ?>）</td>
                    <td><?= htmlspecialchars($user_detail['user_id']) ?></td>
                    <td><?= htmlspecialchars($user_detail['user_account']) ?></td>
                    <td><?= htmlspecialchars($user_detail['usertype']) ?>
                    <td><a href="index.php?do=user_edit&id=<?= urlencode($user_detail['user_id']) ?>">編集</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="4">該当するアカウントは見つかりませんでした。</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php
// GET パラメータ
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 10;
$total_items = count($user_list); // DBから取得してもOK
$total_pages = ceil($total_items / $per_page);

$range = 2;
$start_page = max(1, $page - $range);
$end_page   = min($total_pages, $page + $range);

// 現在の検索・フィルタパラメータをURLに引き継ぐ
$params = $_GET;
unset($params['page']); // pageだけは置き換え
$base_url = '?' . http_build_query($params);

if ($page <= 2) {
    // 最初のページ付近
    $start_page = 1;
    $end_page   = min(3, $total_pages);

} elseif ($page >= $total_pages - 1) {
    // 最後のページ付近
    $start_page = max(1, $total_pages - 2);
    $end_page   = $total_pages;

} else {
    // 真ん中
    $start_page = $page - 1;
    $end_page   = $page + 1;
}
?>

<div class="pagination">
    <?php if ($start_page > 1) : ?>
        <a href="<?= $base_url ?>&page=1">1</a>
        <?php if ($start_page > 2) echo '...'; ?>
    <?php endif; ?>

    <?php for ($i = $start_page; $i <= $end_page; $i++) : ?>
        <?php if ($i == $page) : ?>
            <!-- 現在ページも <a> にしてクリック不可 -->
            <a href="#" class="current-page"><?= $i ?></a>
        <?php else : ?>
            <a href="<?= $base_url ?>&page=<?= $i ?>"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($end_page < $total_pages) : ?>
        <?php if ($end_page < $total_pages - 1) echo '...'; ?>
        <a href="<?= $base_url ?>&page=<?= $total_pages ?>"><?= $total_pages ?></a>
    <?php endif; ?>
</div>